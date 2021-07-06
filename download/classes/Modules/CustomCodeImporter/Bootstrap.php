<?php

declare(strict_types=1);

namespace Xentral\Modules\CustomCodeImporter;

use GitWrapper\GitWrapper;
use Xentral\Components\Filesystem\FilesystemFactory;
use Xentral\Core\DependencyInjection\ContainerInterface;
use Xentral\Modules\CustomCodeImporter\UpdateServer\ApiCall;
use Xentral\Modules\CustomCodeImporter\UpdateServer\FileParser;

final class Bootstrap
{
    /**
     * Defines the system-wide services provided by the module.
     *
     * @return array
     */
    public static function registerServices()
    {
        return [
            'CustomCodeImporterFactory' => 'onInitCustomCodeImporterFactory',
            'CustomCodeRepositoryManager' => 'onInitCustomCodeRepositoryManager',
            FileParser::class => 'onInitCustomCodeFileParser',
            ApiCall::class => 'onInitUpdateServerApiCall',
        ];
    }

    /**
     * Return a new instance of ImporterFactory.
     *
     * @param ContainerInterface $container
     *
     * @return ImporterFactory
     */
    public static function onInitCustomCodeImporterFactory(ContainerInterface $container): ImporterFactory
    {
        /** @var LegacyApplication $app */
        $app = $container->get('LegacyApplication');

        // The directory under dataroot that contains the actual repository directory.
        $repositoryPath = "{$app->Conf->WFuserdata}/custom_code/";

        $validator = new Validator();

        return new ImporterFactory(
            $validator,
            $repositoryPath,
            self::getProjectRoot()
        );
    }

    /**
     * Return an instance of LocalRepositoryManager
     *
     * @param ContainerInterface $container
     *
     * @return LocalRepositoryManager
     */
    public static function onInitCustomCodeRepositoryManager(ContainerInterface $container): LocalRepositoryManager
    {
        $gitWrapper = new GitWrapper('/usr/bin/git');

        /** @var LegacyApplication $app */
        $app = $container->get('LegacyApplication');

        // The directory under dataroot that contains the actual repository directory.
        $repositoryPath = "{$app->Conf->WFuserdata}/custom_code/";

        return new LocalRepositoryManager($gitWrapper, $repositoryPath);
    }

    /**
     * Return an instance of FileParser
     *
     * @param ContainerInterface $container
     *
     * @return FileParser
     */
    public static function onInitCustomCodeFileParser(ContainerInterface $container): FileParser
    {
        /** @var FilesystemFactory $filesystemFactory */
        $filesystemFactory = $container->get(FilesystemFactory::class);

        $filesystem = $filesystemFactory->createLocal(self::getProjectRoot());

        return new FileParser(
            $filesystem,
            self::getProjectRoot()
        );
    }

    /**
     * Return an instance of ApiCall
     *
     * @param ContainerInterface $container
     *
     * @return ApiCall
     */
    public static function onInitUpdateServerApiCall(ContainerInterface $container): ApiCall
    {
        return new ApiCall(
            $container->get(FileParser::class),
            $container->get('LegacyApplication'),
            self::getProjectRoot()
        );
    }

    /**
     * @return array
     */
    public static function registerStylesheets(): array
    {
        return [
            'CustomCodeImporter' => [
                './classes/Modules/CustomCodeImporter/www/css/customcodeimporter.css',
            ],
        ];
    }

    /**
     * Get the absolute path of the project root.
     *
     * @return string
     */
    private static function getProjectRoot(): string
    {
        return dirname(dirname(dirname(__DIR__)));
    }
}
