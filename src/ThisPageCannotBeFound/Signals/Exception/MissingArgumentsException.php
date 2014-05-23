<?php

namespace ThisPageCannotBeFound\Signals\Exception;

/**
 * @author Abel de Beer <abel@thispagecannotbefound.com>
 */
class MissingArgumentsException extends \LengthException implements ExceptionInterface {

    /**
     * @param integer $expected Expected number of arguments
     * @param integer $actual Received number of arguments
     * @param \Exception $previous
     */
    public function __construct($expected, $actual, \Exception $previous = null) {
        $message = sprintf('Expected %d arguments, but received %d', $expected, $actual);

        parent::__construct($message, 0, $previous);
    }

}
