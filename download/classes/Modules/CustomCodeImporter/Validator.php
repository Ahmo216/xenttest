<?php

declare(strict_types=1);

namespace Xentral\Modules\CustomCodeImporter;

use Xentral\Modules\CustomCodeImporter\Exception\InvalidClassNameException;
use Xentral\Modules\CustomCodeImporter\Exception\InvalidFilenameException;
use Xentral\Modules\CustomCodeImporter\Exception\InvalidTargetDirectoryException;

class Validator
{
    /** Characters allowed to be used in names of custom files and classes. */
    private const ALLOWED_CHARS = 'a-zA-Z0-9_';

    /**
     * Validate the given filename and file contents.
     *
     * @param string $filename
     * @param string $contents
     *
     * @throws InvalidClassNameException
     * @throws InvalidFilenameException
     * @throws InvalidTargetDirectoryException
     */
    public function validate(string $filename, string $contents)
    {
        $this->validateFilename($filename);

        $this->validateTargetDirectory($filename);

        if ($this->containsClass($contents)) {
            $this->validateClassName($contents);
        }
    }

    /**
     * Validate that .php, .tpl and image files are being imported to the correct directory.
     *
     * @param $filename
     *
     * @throws InvalidTargetDirectoryException
     */
    private function validateTargetDirectory($filename)
    {
        if (strpos($filename, '_custom.php') !== false) {
            if (strpos($filename, 'www/pages/') !== false) {
                return;
            }

            if (strpos($filename, 'www/lib/') !== false) {
                return;
            }

            if (strpos($filename, 'cronjobs/') !== false) {
                return;
            }

            $shortname = pathinfo($filename, PATHINFO_BASENAME);

            throw new InvalidTargetDirectoryException("<p>The target directory of the file {$shortname} is " .
                'wrong.</p><p>Valid target directories for custom PHP files are:
                    <ul>
                        <li>www/pages/</li>
                        <li>www/lib/</li>
                        <li>cronjobs/</li>
                    </ul>
                </p>');
        }

        if (strpos($filename, '_custom.tpl') !== false) {
            if (strpos($filename, 'www/pages/content/') === false) {
                throw new InvalidTargetDirectoryException("The target directory of the file {$filename} " .
                    'is wrong. Custom template files should be saved under the www/pages/content/ directory.');
            }
        }

        if ($this->isImageFile($filename)) {
            if (strpos($filename, 'www/themes/new/images/') === false) {
                throw new InvalidTargetDirectoryException("The target directory of the file {$filename} " .
                    'is wrong. Custom image files should be saved under the www/themes/new/images/ directory.');
            }
        }
    }

    /**
     * Check whether the given string contains a PHP class.
     *
     * @param string $fileContents
     *
     * @return bool
     */
    private function containsClass(string $fileContents): bool
    {
        $tokens = token_get_all($fileContents);

        foreach ($tokens as $token) {
            if (!is_array($token)) {
                // This is most likely the very last closing curly bracket, which has no additional info as an array.
                continue;
            }

            if (in_array('class', $token)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Validate that the custom file has been named correctly.
     *
     * All custom files should have the string "_custom" appended to the filename.
     *
     * @param string $filename
     *
     * @throws InvalidFilenameException
     */
    private function validateFileName(string $filename)
    {
        $chars = self::ALLOWED_CHARS;

        $basename = basename($filename);

        if (preg_match("/[{$chars}]+/", $filename) !== 1) {
            throw new InvalidFilenameException("The file {$filename} is invalid. Files containing custom " .
                "code are only allowed to use the characters {$chars}.");
        }

        if (strpos($basename, '_custom.php') !== false) {
            return;
        }

        if (strpos($basename, '_custom.tpl') !== false) {
            return;
        }

        if ($this->isImageFile($basename)) {
            // Allow images to be imported without a specific naming convention.
            return;
        }

        throw new InvalidFilenameException("The file {$filename} is invalid. Files containing custom code " .
            'need to end with "_custom" For example "class_custom.php" or "template_custom.tpl"');
    }

    /**
     * Validate that the custom class has been named correctly.
     *
     * All custom classes should have the string "Custom" appended to the class name.
     *
     * @param $contents
     *
     * @throws InvalidClassNameException
     */
    private function validateClassName(string $contents)
    {
        $chars = self::ALLOWED_CHARS;

        // First parse the class name from the file contents.
        preg_match("/class ([{$chars}]+) /", $contents, $matches);
        $className = $matches[1];

        // Check whether the class name ends with the string "Custom".
        $isNamedCorrectly = preg_match("/[{$chars}]+Custom$/", $className, $matches);

        if ($isNamedCorrectly !== 1) {
            throw new InvalidClassNameException("The class name {$className} is invalid. Name of the class" .
                'needs to end with "Custom", for example "DeliveryCustom".');
        }
    }

    /**
     * Check based on the file extension whether the given file is an image.
     *
     * @param string $filename
     *
     * @return bool
     */
    private function isImageFile(string $filename): bool
    {
        $extension = pathinfo($filename, PATHINFO_EXTENSION);

        return in_array($extension, ['png', 'jpg', 'jpeg', 'gif', 'svg']);
    }
}
