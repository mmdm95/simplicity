<?php

namespace App\Logic;

use Sim\Event\ClosureProvider;
use Sim\Event\EventProvider;

class Event
{
    public function closures(): ClosureProvider
    {
        $closureProvider = new ClosureProvider();
        // add closures
        //...

        return $closureProvider;
    }

    public function events(): EventProvider
    {
        $eventProvider = new EventProvider();
        // add events
        //...

        return $eventProvider;
    }
}
