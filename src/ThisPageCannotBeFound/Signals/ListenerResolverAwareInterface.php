<?php

namespace ThisPageCannotBeFound\Signals;

/**
 * @author Abel de Beer <abel@thispagecannotbefound.com>
 */
interface ListenerResolverAwareInterface {

    /**
     * Returns the listener resolver.
     *
     * @return callable Listener resolver
     */
    public function getListenerResolver();

    /**
     * Sets the listener resolver, a factory that creates a class instance for
     * listeners that contain the class name.
     *
     * @param callable $listenerResolver
     */
    public function setListenerResolver($listenerResolver);
}
