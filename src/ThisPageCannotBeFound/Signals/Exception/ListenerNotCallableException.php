<?php

namespace ThisPageCannotBeFound\Signals\Exception;

/**
 * @author Abel de Beer <abel@thispagecannotbefound.com>
 */
class ListenerNotCallableException extends \RuntimeException implements ExceptionInterface {

    /**
     * @param callable $listener The invalid callable
     * @param \Exception $previous
     */
    public function __construct($listener, \Exception $previous = null) {
        is_callable($listener, false, $callable_name);

        $message = sprintf('Listener "%s" is not callable', $callable_name);

        parent::__construct($message, 0, $previous);
    }

}
