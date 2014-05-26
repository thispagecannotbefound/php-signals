<?php

namespace ThisPageCannotBeFound\Signals\Exception;

/**
 * @author Abel de Beer <abel@thispagecannotbefound.com>
 */
class ResolverNotCallableException extends \RuntimeException implements ExceptionInterface {

    /**
     * @param callable $resolver The invalid callable
     * @param \Exception $previous
     */
    public function __construct($resolver, \Exception $previous = null) {
        is_callable($resolver, false, $callable_name);

        $message = sprintf('Resolver "%s" is not callable', $callable_name);

        parent::__construct($message, 0, $previous);
    }

}
