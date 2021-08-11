<?php
namespace Xentral\Modules\Export;

class ClientsCSV extends CSV {

    const DATE1_HEADER_POS = 5;
    const DATE2_HEADER_POS = 6;
    const DATE3_HEADER_POS = 12;
    const USERNAME_HEADER_POS = 8;
    const BERATERNUMMER_HEADER_POS = 10;
    const MANDANTENUMMER_HEADER_POS = 11;
    const SACHKONTENLAENGE_HEADER_POS = 13;

    protected $cachedIndexes = array();
    public function __construct(string $date1, string $date2, string $date3, string $username, string $beraternummer, string $mandantennummer, string $sachkontenlaenge) {
        $this->header_one[self::DATE1_HEADER_POS] = $date1;
        $this->header_one[self::DATE2_HEADER_POS] = $date2;
        $this->header_one[self::DATE3_HEADER_POS] = $date3;
        $this->header_one[self::USERNAME_HEADER_POS] = $username;
        $this->header_one[self::BERATERNUMMER_HEADER_POS] = $beraternummer;
        $this->header_one[self::MANDANTENUMMER_HEADER_POS] = $mandantennummer;
        $this->header_one[self::SACHKONTENLAENGE_HEADER_POS] = $sachkontenlaenge;
    }

    public function getHeader() : string {
        return utf8_decode($this->getFirstHeader() . PHP_EOL . implode(";", $this->header_two));
    }

    public function getFirstHeader() : string {
        return implode(";", $this->header_one);
    }

    protected function findIdxInCache($columnName) {
        if (array_key_exists($columnName, $this->cachedIndexes)) {
            return $this->cachedIndexes[$columnName];
        }
        return null;
    }

    public function findIdx($columnName) {
        $idx = array_search($columnName, $this->header_two);
        if ($idx >=0 && $idx < count($this->header_two)) {
            $this->cachedIndexes[$columnName] = $idx;
            return $idx;
        }

        return null;
    }

    public function getRow(array $namesValues = array()) : string    {
        $row = array_fill(0, count($this->header_two), "\"\"");
        $keys = array_keys($namesValues);
        foreach($keys as $key) {
            $colIdx = -1;
            if (gettype($key) === "integer") {
                $colIdx = $key;
            } else {
                $colIdx = $this->findIdxInCache($key);
                if ($colIdx === null) {
                    $colIdx = $this->findIdx($key);
                }
            }

            if ($colIdx === null || $colIdx < 0 || $colIdx >= count($this->header_two)) {
                throw new \Exception("No such column ($key).");
            }

            $row[$colIdx] = $namesValues[$key];
        }

        return implode(";", $row);
    }


    protected $header_one = array(
        "\"EXTF\"",
        "510",
        "16",
        "\"Debitoren/Kreditoren\"",
        "5",
        "",
        "",
        "\"RE\"",
        "",
        "\"\"",
        "",
        "",
        "",
        "",
        "",
        "",
        "\"Debitoren/Kreditoren\"",
        "\"\"",
        "1",
        "0",
        "",
        "\"\"",
        "",
        "\"\"",
        "",
        "350504",
        "\"04\"",
        "",
        "",
        "\"\"",
        "\"\""
    );

    protected $header_two = array(
        "Konto",
        "Name (Adressattyp Unternehmen)",
        "Unternehmensgegenstand",
        "Name (Adressattyp natürl. Person)",
        "Vorname (Adressattyp natürl. Person)",
        "Name (Adressattyp keine Angabe)",
        "Adressattyp",
        "Kurzbezeichnung",
        "EU-Land",
        "EU-UStID",
        "Anrede",
        "Titel/Akad. Grad",
        "Adelstitel",
        "Namensvorsatz",
        "Adressart",
        "Straße",
        "Postfach",
        "Postleitzahl",
        "Ort",
        "Land",
        "Versandzusatz",
        "Adresszusatz",
        "Abweichende Anrede",
        "Abw. Zustellbezeichnung 1",
        "Abw. Zustellbezeichnung 2",
        "Kennz. Korrespondenzadresse",
        "Adresse Gültig von",
        "Adresse Gültig bis",
        "Telefon",
        "Bemerkung (Telefon)",
        "Telefon GL",
        "Bemerkung (Telefon GL)",
        "E-Mail",
        "Bemerkung (E-Mail)",
        "Internet",
        "Bemerkung (Internet)",
        "Fax",
        "Bemerkung (Fax)",
        "Sonstige",
        "Bemerkung (Sonstige)",
        "Bankleitzahl 1",
        "Bankbezeichnung 1",
        "Bank-Kontonummer 1",
        "Länderkennzeichen 1",
        "IBAN-Nr. 1",
        "Leerfeld",
        "SWIFT-Code 1",
        "Abw. Kontoinhaber 1",
        "Kennz. Hauptbankverb. 1",
        "Bankverb 1 Gültig von",
        "Bankverb 1 Gültig bis",
        "Bankleitzahl 2",
        "Bankbezeichnung 2",
        "Bank-Kontonummer 2",
        "Länderkennzeichen 2",
        "IBAN-Nr. 2",
        "Leerfeld",
        "SWIFT-Code 2",
        "Abw. Kontoinhaber 2",
        "Kennz. Hauptbankverb. 2",
        "Bankverb 2 Gültig von",
        "Bankverb 2 Gültig bis",
        "Bankleitzahl 3",
        "Bankbezeichnung 3",
        "Bank-Kontonummer 3",
        "Länderkennzeichen 3",
        "IBAN-Nr. 3",
        "Leerfeld",
        "SWIFT-Code 3",
        "Abw. Kontoinhaber 3",
        "Kennz. Hauptbankverb. 3",
        "Bankverb 3 Gültig von",
        "Bankverb 3 Gültig bis",
        "Bankleitzahl 4",
        "Bankbezeichnung 4",
        "Bank-Kontonummer 4",
        "Länderkennzeichen 4",
        "IBAN-Nr. 4",
        "Leerfeld",
        "SWIFT-Code 4",
        "Abw. Kontoinhaber 4",
        "Kennz. Hauptbankverb. 4",
        "Bankverb 4 Gültig von",
        "Bankverb 4 Gültig bis",
        "Bankleitzahl 5",
        "Bankbezeichnung 5",
        "Bank-Kontonummer 5",
        "Länderkennzeichen 5",
        "IBAN-Nr. 5",
        "Leerfeld",
        "SWIFT-Code 5",
        "Abw. Kontoinhaber 5",
        "Kennz. Hauptbankverb. 5",
        "Bankverb 5 Gültig von",
        "Bankverb 5 Gültig bis",
        "Leerfeld",
        "Briefanrede",
        "Grußformel",
        "Kunden-/Lief.-Nr.",
        "Steuernummer",
        "Sprache",
        "Ansprechpartner",
        "Vertreter",
        "Sachbearbeiter",
        "Diverse-Konto",
        "Ausgabeziel",
        "Währungssteuerung",
        "Kreditlimit (Debitor)",
        "Zahlungsbedingung",
        "Fälligkeit in Tagen (Debitor)",
        "Skonto in Prozent (Debitor)",
        "Kreditoren-Ziel 1 Tg.",
        "Kreditoren-Skonto 1 %",
        "Kreditoren-Ziel 2 Tg.",
        "Kreditoren-Skonto 2 %",
        "Kreditoren-Ziel 3 Brutto Tg.",
        "Kreditoren-Ziel 4 Tg.",
        "Kreditoren-Skonto 4 %",
        "Kreditoren-Ziel 5 Tg.",
        "Kreditoren-Skonto 5 %",
        "Mahnung",
        "Kontoauszug",
        "Mahntext 1",
        "Mahntext 2",
        "Mahntext 3",
        "Kontoauszugstext",
        "Mahnlimit Betrag",
        "Mahnlimit %",
        "Zinsberechnung",
        "Mahnzinssatz 1",
        "Mahnzinssatz 2",
        "Mahnzinssatz 3",
        "Lastschrift",
        "Verfahren",
        "Mandantenbank",
        "Zahlungsträger",
        "Indiv. Feld 1",
        "Indiv. Feld 2",
        "Indiv. Feld 3",
        "Indiv. Feld 4",
        "Indiv. Feld 5",
        "Indiv. Feld 6",
        "Indiv. Feld 7",
        "Indiv. Feld 8",
        "Indiv. Feld 9",
        "Indiv. Feld 10",
        "Indiv. Feld 11",
        "Indiv. Feld 12",
        "Indiv. Feld 13",
        "Indiv. Feld 14",
        "Indiv. Feld 15",
        "Abweichende Anrede (Rechnungsadresse)",
        "Adressart (Rechnungsadresse)",
        "Straße (Rechnungsadresse)",
        "Postfach (Rechnungsadresse)",
        "Postleitzahl (Rechnungsadresse)",
        "Ort (Rechnungsadresse)",
        "Land (Rechnungsadresse)",
        "Versandzusatz (Rechnungsadresse)",
        "Adresszusatz (Rechnungsadresse)",
        "Abw. Zustellbezeichnung 1 (Rechnungsadresse)",
        "Abw. Zustellbezeichnung 2 (Rechnungsadresse)",
        "Adresse Gültig von (Rechnungsadresse)",
        "Adresse Gültig bis (Rechnungsadresse)",
        "Bankleitzahl 6",
        "Bankbezeichnung 6",
        "Bank-Kontonummer 6",
        "Länderkennzeichen 6",
        "IBAN-Nr. 6",
        "Leerfeld",
        "SWIFT-Code 6",
        "Abw. Kontoinhaber 6",
        "Kennz. Hauptbankverb. 6",
        "Bankverb 6 Gültig von",
        "Bankverb 6 Gültig bis",
        "Bankleitzahl 7",
        "Bankbezeichnung 7",
        "Bank-Kontonummer 7",
        "Länderkennzeichen 7",
        "IBAN-Nr. 7",
        "Leerfeld",
        "SWIFT-Code 7",
        "Abw. Kontoinhaber 7",
        "Kennz. Hauptbankverb. 7",
        "Bankverb 7 Gültig von",
        "Bankverb 7 Gültig bis",
        "Bankleitzahl 8",
        "Bankbezeichnung 8",
        "Bank-Kontonummer 8",
        "Länderkennzeichen 8",
        "IBAN-Nr. 8",
        "Leerfeld",
        "SWIFT-Code 8",
        "Abw. Kontoinhaber 8",
        "Kennz. Hauptbankverb. 8",
        "Bankverb 8 Gültig von",
        "Bankverb 8 Gültig bis",
        "Bankleitzahl 9",
        "Bankbezeichnung 9",
        "Bank-Kontonummer 9",
        "Länderkennzeichen 9",
        "IBAN-Nr. 9",
        "Leerfeld",
        "SWIFT-Code 9",
        "Abw. Kontoinhaber 9",
        "Kennz. Hauptbankverb. 9",
        "Bankverb 9 Gültig von",
        "Bankverb 9 Gültig bis",
        "Bankleitzahl 10",
        "Bankbezeichnung 10",
        "Bank-Kontonummer 10",
        "Länderkennzeichen 10",
        "IBAN-Nr. 10",
        "Leerfeld",
        "SWIFT-Code 10",
        "Abw. Kontoinhaber 10",
        "Kennz. Hauptbankverb. 10",
        "Bankverb 10 Gültig von",
        "Bankverb 10 Gültig bis",
        "Nummer Fremdsystem",
        "Insolvent",
        "SEPA-Mandatsreferenz 1",
        "SEPA-Mandatsreferenz 2",
        "SEPA-Mandatsreferenz 3",
        "SEPA-Mandatsreferenz 4",
        "SEPA-Mandatsreferenz 5",
        "SEPA-Mandatsreferenz 6",
        "SEPA-Mandatsreferenz 7",
        "SEPA-Mandatsreferenz 8",
        "SEPA-Mandatsreferenz 9",
        "SEPA-Mandatsreferenz 10",
        "Verknüpftes OPOS-Konto",
        "Mahnsperre bis",
        "Lastschriftsperre bis",
        "Zahlungssperre bis",
        "Gebührenberechnung",
        "Mahngebühr 1",
        "Mahngebühr 2",
        "Mahngebühr 3",
        "Pauschalenberechnung",
        "Verzugspauschale 1",
        "Verzugspauschale 2",
        "Verzugspauschale 3",
        "Alternativer Suchname",
        "Status",
        "Anschrift manuell geändert (Korrespondenzadresse)",
        "Anschrift individuell (Korrespondenzadresse)",
        "Anschrift manuell geändert (Rechnungsadresse)",
        "Anschrift individuell (Rechnungsadresse)",
        "Fristberechnung bei Debitor",
        "Mahnfrist 1",
        "Mahnfrist 2",
        "Mahnfrist 3",
        "Letzte Frist"
    );
}
