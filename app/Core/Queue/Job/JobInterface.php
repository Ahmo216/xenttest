<?php

declare(strict_types=1);

namespace App\Core\Queue\Job;

use Illuminate\Contracts\Queue\ShouldQueue;

interface JobInterface extends ShouldQueue
{
    /**
     * A Job class must implement a "handle" method!
     * The parameters of the handle method are the depencies.
     * The Dependencies are auto-injected by type-hint when the job is executed.
     *
     * The following example shows a handle method that has
     * the database as a dependency.
     */
    /*
    public function handle(Database $db): void
    {
        $data = $db->fetchRow(...);
        // process data
    }
    */
}
