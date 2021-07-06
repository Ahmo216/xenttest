<?php

declare(strict_types=1);

namespace Xentral\Modules\CustomCodeImporter\UpdateServer;

use Xentral\Components\Filesystem\Filesystem;

class FileParser
{
    private $filesystem;

    private $projectRoot;

    /**
     * FileParser constructor.
     *
     * @param Filesystem $filesystem
     * @param string $projectRoot
     */
    public function __construct(
        Filesystem $filesystem,
        string $projectRoot
    ) {
        $this->filesystem = $filesystem;
        $this->projectRoot = $projectRoot;
    }

    /**
     * Parses a list of custom files into an array processable by the update server.
     *
     * Creates the following structure from the custom files:
     *
     *     $example = [
     *         'path/to/file1.php' => [
     *             0 => [
     *                'line 5 contents',
     *                'line 6 contents',
     *                 ...
     *             ],
     *             1 => [
     *                 'line 25 contents',
     *                 'line 26 contents',
     *                 ...
     *             ],
     *         ],
     *         'path/to/file2.php' => [
     *              0 => [
     *                  ...
     *              ]
     *         ]
     *     ];
     *
     * The first-level key is the filename. On the second level there is a incrementing numeric
     * key for each found function. The third level has the contents of the lines five lines
     * before and five lines after the row where the function has been declared.
     */
    public function parseFileList(array $filenames): array
    {
        $customCode = [];

        foreach ($filenames as $filename) {
            $chosenLines = $this->extractFileContentsAroundFunctions($filename);

            if (!empty($chosenLines)) {
                $customCode[$filename][] = $chosenLines;
            }
        }

        return $customCode;
    }

    /**
     * Find all function declarations from the file and make a summary of the contents.
     *
     *     $array = [
     *         // The first found function declaration.
     *         0 => [
     *             'line 5 contents',
     *             'line 6 contents',
     *              ...
     *             'line 13 contents',
     *             'line 14 contents',
     *         ],
     *         // The second found function declaration.
     *         1 => [
     *             'line 25 contents',
     *             'line 26 contents',
     *             ...
     *         ],
     *     ];
     *
     * @param string $filename
     * @return array
     */
    private function extractFileContentsAroundFunctions(string $filename): array
    {
        $filepath = "{$this->projectRoot}/{$filename}";
        $fileRows = file($filepath);

        $contents = $this->filesystem->read($filename);
        $tokens = token_get_all($contents);

        $chosenLines = [];
        foreach ($tokens as $key => $token) {
            if (is_string($token)) {
                continue;
            }

            list($typeId, $text, $rowNumber) = $token;

            if ($typeId !== T_FUNCTION) {
                // We are only interested in finding functions, so skip everything else.
                continue;
            }

            // Extract five rows both before and after the function declaration.
            $startRow = $rowNumber - 5;
            if ($startRow < 0) {
                $startRow = 0;
            }

            $endRow = $rowNumber + 5;
            if ($endRow > count($fileRows)) {
                $endRow = count($fileRows);
            }

            for ($row = $startRow; $row < $endRow; $row++) {
                $chosenLines[$key][] = $fileRows[$row];
            }
        }

        return $chosenLines;
    }
}
