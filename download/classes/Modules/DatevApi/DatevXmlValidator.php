<?php

declare(strict_types=1);

namespace Xentral\Modules\DatevApi;

use DOMDocument;
use Xentral\Modules\DatevApi\Data\ValidationResultData;
use Xentral\Modules\DatevApi\Data\ValidationResultErrorData;

final class DatevXmlValidator
{
    public const XML_TYPE_LEDGER_IMPORT_V5 = 'Belegverwaltung_online_ledger_import_v050.xsd';

    public const XML_TYPE_DOCUMENT_V5 = 'Document_v050.xsd';

    /** @var string $xsdDirectoryPath */
    private $xsdDirectoryPath;

    /**
     * @param string $xsdDirectoryPath
     */
    public function __construct(string $xsdDirectoryPath)
    {
        $this->xsdDirectoryPath = $xsdDirectoryPath;
    }

    /**
     * @param string $xmlContent
     * @param string $xmlFileType
     *
     * @return ValidationResultData
     */
    public function validate(string $xmlContent, string $xmlFileType): ValidationResultData
    {
        libxml_use_internal_errors(true);

        $xsdFilePath = $this->xsdDirectoryPath . DIRECTORY_SEPARATOR . $xmlFileType;

        $xml = new DOMDocument();
        $xml->loadXML($xmlContent);
        $errors = [];
        $isValid = true;
        if (!$xml->schemaValidate($xsdFilePath)) {
            $errors = $this->getLibXmlErrors();
            $isValid = false;
        }

        return new ValidationResultData($isValid, $errors);
    }

    /**
     * @return array|ValidationResultErrorData[]
     */
    private function getLibXmlErrors(): array
    {
        $libXmlErrors = libxml_get_errors();
        $errors = [];
        foreach ($libXmlErrors as $error) {
            if ($error->level === LIBXML_ERR_WARNING) {
                $level = ValidationResultErrorData::DATEV_XML_WARNING;
            } elseif ($error->level === LIBXML_ERR_ERROR) {
                $level = ValidationResultErrorData::DATEX_XML_ERROR;
            } else {
                $level = ValidationResultErrorData::DATEV_XML_FATAL;
            }

            $errors[] = new ValidationResultErrorData($level, $error->code, $error->message, $error->line);
        }
        libxml_clear_errors();

        return $errors;
    }
}
