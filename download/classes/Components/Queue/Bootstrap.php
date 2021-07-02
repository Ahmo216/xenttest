<?php

declare(strict_types=1);

namespace Xentral\Components\Queue;

use Xentral\Components\SchemaCreator\Collection\SchemaCollection;
use Xentral\Components\SchemaCreator\Index\Primary;
use Xentral\Components\SchemaCreator\Index\Unique;
use Xentral\Components\SchemaCreator\Schema\TableSchema;
use Xentral\Components\SchemaCreator\Type\Integer;
use Xentral\Components\SchemaCreator\Type\Text;
use Xentral\Components\SchemaCreator\Type\Tinyint;
use Xentral\Components\SchemaCreator\Type\Varchar;

/**
 * This Bootstrap class only exists to create the table schema for the queue system.
 * All code related to the queue system lives in App/Core/Queue.
 *
 * @todo: Once the system is switched to laravel database this class should be removed.
 */
class Bootstrap
{
    public static function registerTableSchemas(SchemaCollection $collection): void
    {
        $queueSchema = new TableSchema('queue_jobs');
        $queueSchema->addColumn(Integer::asAutoIncrement('id'));
        $queueSchema->addColumn(new Varchar('queue'));
        $queueSchema->addColumn(new Text('payload'));
        $queueSchema->addColumn(Tinyint::asUnsigned('attempts'));
        $queueSchema->addColumn(Tinyint::asUnsigned('reserved'));
        $queueSchema->addColumn(Integer::asUnsigned('reserved_at'));
        $queueSchema->addColumn(Integer::asUnsigned('available_at'));
        $queueSchema->addColumn(Integer::asUnsigned('created_at'));
        $queueSchema->addIndex(new Primary(['id']));
        $collection->add($queueSchema);

        $failedJobsSchema = new TableSchema('failed_jobs');
        $failedJobsSchema->addColumn(Integer::asAutoIncrement('id'));
        $failedJobsSchema->addColumn(new Varchar('uuid'));
        $failedJobsSchema->addColumn(new Varchar('connection'));
        $failedJobsSchema->addColumn(new Varchar('queue'));
        $failedJobsSchema->addColumn(new Text('payload'));
        $failedJobsSchema->addColumn(new Text('exception'));
        $failedJobsSchema->addColumn(Integer::asUnsigned('failed_at'));
        $failedJobsSchema->addIndex(new Primary(['id']));
        $failedJobsSchema->addIndex(new Unique(['uuid']));
        $collection->add($failedJobsSchema);
    }
}
