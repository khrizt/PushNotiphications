<?php

namespace Khrizt\PushNotiphications\Exception\Fcm;

use Exception;

class ServerNotAvailableException extends Exception
{
    public function __construct()
    {
        parent::__construct('Internal Server Error. The server encountered an error while trying to process the request. You could retry the same request following the requirements listed in "Timeout" (see below). If the error persists, please report the problem in the android-gcm group.

Timeout. The server couldn\'t process the request in time. Retry the same request, but you must:

    Honor the Retry-After header if it is included in the response from the GCM Connection Server.
    Implement exponential back-off in your retry mechanism. (e.g. if you waited one second before the first retry, wait at least two second before the next one, then 4 seconds and so on). If you\'re sending multiple messages, delay each one independently by an additional random amount to avoid issuing a new request for all messages at the same time.

Senders that cause problems risk being blacklisted.');
    }
}
