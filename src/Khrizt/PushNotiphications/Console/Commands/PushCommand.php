<?php

namespace Khrizt\PushNotiphications\Console\Commands;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Khrizt\PushNotiphications\Client\Apns;
use Khrizt\PushNotiphications\Collection\Collection;
use Khrizt\PushNotiphications\Constants;
use Khrizt\PushNotiphications\Model\Device;
use Khrizt\PushNotiphications\Model\Apns\Message as ApnsMessage;

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
                'Adapter (apns, gcm, specific class name, ...)'
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
                'API key (for GCM adapter)'
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
        $env = $input->getOption('env');

        if ($adapter === 'apns') {
            $certificate = $input->getOption('certificate');
            if (!file_exists($certificate)) {
                throw new \InvalidArgumentException('Certificate does not exists');
            }
            $certificatePassphrase = $input->getOption('certificatePassphrase');
            $topic = $input->getOption('topic');
            $this->sendApnsNotification($token, $message, $certificate, $env, $certificatePassphrase, $topic);
        } elseif ($adapter === 'gcm') {
            //
        } else {
            throw new \InvalidArgumentException('Adapter '.$adapter.' is not a valid value. Values are: apns and gcm');
        }
    }

    protected function sendApnsNotification(string $token, string $message, string $certificate, string $env, string $certificatePassphrase = null, string $topic = null)
    {
        $apnsMessage = new ApnsMessage();
        $apnsMessage->setBody($message);
        if (!is_null($topic)) {
            $apnsMessage->setTopic($topic);
        }

        $device = new Device($token);
        $collection = new Collection();
        $collection->append($device);

        $client = new Apns($env, $certificate, $certificatePassphrase);
        $responseCollection = $client->send($apnsMessage, $collection);

        foreach ($responseCollection as $response) {
            $this->output->writeLn('Status for notification sent to '.$response->getToken().' was '.$response->getStatus().($response->getStatus() === 200 ?: '. Error message: '.$response->getErrorMessage()));
        }
    }
}
