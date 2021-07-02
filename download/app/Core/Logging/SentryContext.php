<?php

declare(strict_types=1);

namespace App\Core\Logging;

use App\Core\Helpers\AppVersion;
use App\Core\Logging\Services\FetchAwsCustomerIdAction;
use App\Core\Logging\Services\FetchAwsInstanceIdAction;
use Sentry\State\HubInterface;
use Sentry\State\Scope;

class SentryContext
{
    /** @var HubInterface */
    private $hub;

    /** @var FetchAwsCustomerIdAction */
    private $fetchAwsCustomerIdAction;

    /** @var FetchAwsInstanceIdAction */
    private $fetchAwsInstanceIdAction;

    public function __construct(
        HubInterface $hub,
        FetchAwsCustomerIdAction $fetchAwsCustomerIdAction,
        FetchAwsInstanceIdAction $fetchAwsInstanceIdAction
    ) {
        $this->hub = $hub;
        $this->fetchAwsCustomerIdAction = $fetchAwsCustomerIdAction;
        $this->fetchAwsInstanceIdAction = $fetchAwsInstanceIdAction;
    }

    public function addTags(): void
    {
        $this->hub->configureScope(function (Scope $scope): void {
            $this->setVersionTag($scope);
            $this->setCustomerIdTag($scope);
            $this->setInstanceIdTag($scope);
        });
    }

    private function setVersionTag(Scope $scope): void
    {
        $scope->setTag('xentral.version', sprintf('%s.%s', AppVersion::major(), AppVersion::minor()));
    }

    private function setCustomerIdTag(Scope $scope): void
    {
        $customerId = ($this->fetchAwsCustomerIdAction)();
        if ($customerId === null) {
            return;
        }

        $scope->setTag('xentral.customer_id', $customerId);
    }

    private function setInstanceIdTag(Scope $scope): void
    {
        $instanceId = ($this->fetchAwsInstanceIdAction)();
        if ($instanceId === null) {
            return;
        }

        $scope->setTag('xentral.instance_id', $instanceId);
    }
}
