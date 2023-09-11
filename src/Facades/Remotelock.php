<?php

namespace InstanceCode\Remotelock\Facades;

use Illuminate\Support\Facades\Facade;

class Remotelock extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor() {
        return 'instance-code-remotelock';
    }
}
