<?php

namespace Khrizt\PushNotiphications\Console\Commands;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Khrizt\PushNotiphications\Client\Apns;
use Khrizt\PushNotiphications\Client\Fcm;
use Khrizt\PushNotiphications\Collection\Collection;
use Khrizt\PushNotiphications\Constants;
use Khrizt\PushNotiphications\Model\Device;
use Khrizt\PushNotiphications\Model\Apns\Message as ApnsMessage;
use Khrizt\PushNotiphications\Model\Fcm\Message as FcmMessage;

/**
 * PushCommand.
 */
class PushCommand extends Command
{
    protected $input;

    protected $output;

    /**
     * @see Command
     */
    protected function configure()
    {
        parent::configure();

        $this
            ->setName('push')
            ->setDescription('Manual push notification')
            ->addArgument(
                'adapter',
                InputArgument::REQUIRED,
                'Adapter (apns, fcm, specific class name, ...)'
            )
            ->addArgument(
                'token',
                InputArgument::REQUIRED,
                'Device Token or Registration ID'
            )
            ->addArgument(
                'message',
                InputArgument::REQUIRED,
                'Message'
            )
            ->addOption(
                'title',
                null,
                InputOption::VALUE_OPTIONAL,
                'Title',
                null
            )
            ->addOption(
                'certificate',
                null,
                InputOption::VALUE_OPTIONAL,
                'Certificate path (for APNS adapter)'
            )
            ->addOption(
                'certificatePassphrase',
                null,
                InputOption::VALUE_OPTIONAL,
                'Certificate passphrase (for APNS adapter)'
            )
            ->addOption(
                'topic',
                null,
                InputOption::VALUE_OPTIONAL,
                'Topic (for APNS adapter)',
                null
            )
            ->addOption(
                'apiKey',
                null,
                InputOption::VALUE_OPTIONAL,
                'API key (for FCM adapter)'
            )
            ->addOption(
                'env',
                null,
                InputOption::VALUE_REQUIRED,
                sprintf(
                    'Environment (%s, %s)',
                    Constants::DEVELOPMENT,
                    Constants::PRODUCTION
                ),
                Constants::DEVELOPMENT
            );
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->input = $input;
        $this->output = $output;

        $adapter = $input->getArgument('adapter');
        $token = $input->getArgument('token');
        $message = $input->getArgument('message');
        $title = $input->getOption('title');

        if ($adapter === 'apns') {
            $env = $input->getOption('env');
            $certificate = $input->getOption('certificate');
            if (!file_exists($certificate)) {
                throw new \InvalidArgumentException('Certificate does not exists');
            }
            $certificatePassphrase = $input->getOption('certificatePassphrase');
            $topic = $input->getOption('topic');
            $this->sendApnsNotification($token, $title, $message, $certificate, $env, $certificatePassphrase, $topic);
        } elseif ($adapter === 'fcm') {
            $apiKey = $input->getOption('apiKey');
            $this->sendFcmNotification($token, $title, $message, $apiKey);
        } else {
            throw new \InvalidArgumentException('Adapter '.$adapter.' is not a valid value. Values are: apns and fcm');
        }
    }

    protected function sendApnsNotification(
        string $token,
        ?string $title,
        string $message,
        string $certificate,
        string $env,
        string $certificatePassphrase = null,
        string $topic = null
    ): void {
        $apnsMessage = new ApnsMessage();
        $apnsMessage->setBody($message);
        if (!is_null($title)) {
            $apnsMessage->setTitle($title);
        }
        if (!is_null($topic)) {
            $apnsMessage->setTopic($topic);
        }

        $device = new Device($token);
        $collection = new Collection();
        $collection->append($device);

        $client = new Apns($env, $certificate, $certificatePassphrase);
        $responseCollection = $client->send($apnsMessage, $collection);

        foreach ($responseCollection as $response) {
            $this->output->writeLn(
                'Status for notification sent to '.$response->getToken().' was '.
                ($response->isOk() ? 'OK' : '. Error message: '.$response->getErrorMessage())
            );
        }
    }

    public function sendFcmNotification(
        string $token,
        ?string $title,
        string $message,
        string $apiKey
    ): void {
        $fcmMessage = new FcmMessage();
        $fcmMessage->setBody($message)
                   ->setData([
                        'title' => $title,
                        'message' => $message,
                    ]);
        if (!is_null($title)) {
            $fcmMessage->setTitle($title);
        }

        $device = new Device($token);
        $collection = new Collection();
        $collection->append($device);

        $client = new Fcm($apiKey);
        $responseCollection = $client->send($fcmMessage, $collection);

        foreach ($responseCollection as $response) {
            $this->output->writeLn(
                'Status for notification sent to '.$response->getToken().' was '.
                ($response->isOk() ? 'OK' : ' Error. Error message: '.$response->getErrorMessage())
            );
        }
    }
}
