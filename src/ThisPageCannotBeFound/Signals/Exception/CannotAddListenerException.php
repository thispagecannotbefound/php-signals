<?php

namespace ThisPageCannotBeFound\Signals\Exception;

/**
 * @author Abel de Beer <abel@thispagecannotbefound.com>
 */
class CannotAddListenerException extends \LogicException implements ExceptionInterface {

    public function __construct($message, \Exception $previous = null) {
        parent::__construct($message, 0, $previous);
    }

}
