<?php

namespace ThisPageCannotBeFound\Signals\Exception;

/**
 * @author Abel de Beer <abel@thispagecannotbefound.com>
 */
class InvalidTypeException extends \InvalidArgumentException implements ExceptionInterface {

    /**
     * @param string $type Type name
     * @param array $native Supported native types
     * @param \Exception $previous
     */
    public function __construct($type, array $native = array(), \Exception $previous = null) {
        $message = sprintf('No class or interface called "%s" exists', $type);

        if (!empty($native)) {
            $message .= sprintf('. Supported native types: %s', implode(', ', $native));
        }

        parent::__construct($message, 0, $previous);
    }

}
