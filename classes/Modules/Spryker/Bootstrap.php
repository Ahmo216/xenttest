<?php

declare(strict_types=1);

namespace Xentral\Modules\Spryker;

use Xentral\Core\DependencyInjection\ContainerInterface;

final class Bootstrap
{

    /**
     * @return array
     */
    public static function registerServices(): array
    {
        return [
            'SprykerRepository' => 'onInitSprykerRepository',
        ];
    }

    /**
     * @param ContainerInterface $container
     *
     * @return SprykerRepository
     */
    public static function onInitSprykerRepository(ContainerInterface $container
    ): SprykerRepository {
        return new SprykerRepository(
            $container->get('Database')
        );
    }
}
