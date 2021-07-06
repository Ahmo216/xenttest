<?php

namespace Xentral\Modules\CustomCodeImporter\UpdateServer;

use Application;

/**
 * This class simply wraps the existing API call structure into a class.
 *
 * There is currently no proper API, but just a file that gets included and
 * chooses the correct API method based on which variables have been defined.
 */
class ApiCall
{
    /** @var FileParser */
    private $fileParser;

    /** @var string */
    private $systemRoot;

    /** @var Application */
    private $app;

    /**
     * ApiCall constructor.
     *
     * @param FileParser  $fileParser
     * @param Application $app
     * @param string      $systemRoot
     */
    public function __construct(
        FileParser $fileParser,
        Application $app,
        string $systemRoot
    ) {
        $this->fileParser = $fileParser;
        $this->app = $app;
        $this->systemRoot = $systemRoot;
    }

    /**
     * Include the file that is responsible for doing the actual API call.
     *
     * @param array $files
     */
    public function send(array $files)
    {
        // The $customCode variable defined here gets read by the file required below.
        // Passing variables back an forth like this is a hack, but this is not the
        // time and place to rewrite it.
        $customCode = $this->fileParser->parseFileList($files);

        /** See \UpgradeClient::sendCustomCode() */
        require_once $this->systemRoot . '/upgradesystemclient2_includekey.php';

        // The $ok variable gets defined in the file required above.
        if (!$ok) {
            // TODO Log an error?
        }
    }
}
