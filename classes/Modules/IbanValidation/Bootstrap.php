<?php

namespace Xentral\Modules\IbanValidation;

use Xentral\Core\DependencyInjection\ContainerInterface;
use Xentral\Modules\IbanValidation\Service\IbanValidationService;
use Xentral\Modules\IbanValidation\Wrapper\IbanValidationWrapper;


class Bootstrap
{
    /**
     * @return array
     */
    public static function registerServices():array
    {
        return [
            'IbanValidationService'          => 'onInitIbanValidationService'
        ];
    }

    /**
     * @param ContainerInterface $container
     *
     * @return IbanValidationService
     */
    public static function onInitIbanValidationService(ContainerInterface $container):IbanValidationService
    {

        return new IbanValidationService(new IbanValidationWrapper());
    }
}
