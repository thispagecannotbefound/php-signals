<?php

namespace ThisPageCannotBeFound\Signals\Exception;

/**
 * @author Abel de Beer <abel@thispagecannotbefound.com>
 */
class ValueTypeMismatchException extends \UnexpectedValueException implements ExceptionInterface {

    /**
     * @param string $expected Expected type
     * @param mixed $value Received value
     * @param boolean $native Expected a native type?
     * @param \Exception $previous
     */
    public function __construct($expected, $value, $native = false, \Exception $previous = null) {
        $valType = is_object($value) ? get_class($value) . ' instance' : gettype($value);
        $expType = $expected . ($native ? ' value' : ' instance');
        $message = sprintf('Expected %s, but received %s', $expType, $valType);

        parent::__construct($message, 0, $previous);
    }

}
