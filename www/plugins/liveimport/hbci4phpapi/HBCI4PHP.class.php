<?php
/**
 * HBCI4PHP Library v4.0
 *
 * Copyright 2019 Web.Cloud.Apps. GmbH | Schillerstr. 14 | 71638 Ludwigsburg. | Alle Rechte vorbehalten.
 * Telefon: 07141-2589050 | E-Mail: info@web-cloud-apps.com
 *
 **/

/** Include the HBCI4PHP Base class */
require_once("HBCI4PHPBase.class.php");

class HBCI4PHP extends HBCI4PHPBase
{
    /**
     * Setze Registriernummer (PSD2 Richtlinie: Jedes Produkt bzw. jeder Betreiber muss sich selbst registrieren. Die Registriernummern der
     * von Modulen/Bibliotheken dürfen im praktischen Betrieb nicht verwendet werden. siehe https://www.hbci-zka.de/register/register_faq.htm)
     */
    protected $registrationNumber = 'CF84205B0B0556F80DB66A221';

    /**
     * Log level can be between 0 (no log at all) and 5 (all messages included)
     *
     * Level 0: No log
     * Level 1: Actions and variables will be logged
     * Level 2: (for future use)
     * Level 3: (for future use)
     * Level 4: (for future use)
     * Level 5: All actions and messages will be logged, containing raw data for debug
     */
    public $logLevel = 0;

    /**
     * Constructor - init the HBCI4PHP class
     *
     * @param $account - Benutzerkennung oder Online-Banking Name
     * @param $iban - IBAN
     * @param $bic - BIC
     * @param $pin - Banking PIN
     * @param null $serverUrl - HBCI Endpunkt URL
     * @param null $tanMethod
     * @param int $logLevel - LogLevel for the lib - default is 1
     * @param string $hbci - HBCI Version 2.2 (220) or 3.0 (300)
     * @param int $sslVerfiyPeer - If zero, Curl doesnt verify SSL. Default in Lib and Curl is 1.
     * @param null $logFilePath - If file path set, HBCI4PHP will log to this file
     * @param null $tan
     * @param null $auftragsreferenz
     * @param null $dialogid
     */
    public function __construct($account, $iban, $bic, $pin, $serverUrl, $tanMethod = null, $logLevel = 1, $hbci = "300", $sslVerfiyPeer = 1, $logFilePath = null, $tan = null, $auftragsreferenz = null, $dialogid = null) {
        $this->_initHBCI4PHP($account, $iban, $bic, $pin, $serverUrl, $tanMethod, $logLevel, $hbci, $sslVerfiyPeer, $logFilePath, $tan, $auftragsreferenz, $dialogid);
    }

    public function setRegistrationNumber($registrationNumber) {
        $this->_setRegistrationNumber($registrationNumber);
    }

    /** Get the account saldo (not supported by every bank) */
    public function getSaldo() {
        return $this->_getSaldo();
    }

    /**
     * Returns the allowed TAN methods for the user
     */
    public function getAllowedTanMethods() {
        return $this->_getAllowedTanMethods();
    }

    /**
     * @return string
     */
    public function getCurrentTanMethod() {
        return $this->_getCurrentTanMethod();
    }

    /**
     * @return bool
     */
    public function authNeeded() {
        return $this->_authNeeded();
    }

    /**
     * @return array
     */
    public function getAuthChallenge() {
        return $this->_getAuthChallenge();
    }

    /**
     * Returns the accepted TAN Media for this TAN method
     *
     * @return array|bool Return an array of strings with tan media names, or false if none found or method not supported
     */
    public function getTanMedia() {
        return $this->_getTanMedia();
    }

    /**
     * @param $mediaName - The name of the TAN Media returned by getTanMedia()
     *
     * @return bool - true if media found and set, otherwise false.
     */
    public function setTanMedia($mediaName) {
        return $this->_setTanMedia($mediaName);
    }

    /**
     * Returns the HHD graphics for hardware TAN generator
     */
    public function getTanGraphics($challenge) {
        return $this->_getTanGraphics($challenge);
    }

    /**
     * Sende ein Sammel- oder Einzelüberweisung zum Server
     *
     * @param $classCredit "Credit"-Klasse welche die Aufträge enthält. Hinzufügen von Überweisungen: add($transactionDate, $amount, $name, $iban, $bic=NULL, $purpose=NULL)
     * @param $tanMethod - Der Code einer von der Bank zulässigen TAN-Methode. Verfügbare TAN-Methoden können über getAllowedTanMethods() abgerufen werden.
     * @param null $tan - Nur im zweiten Schritt zu übergeben, nachdem der Auftrag eingereicht wurde.
     * @param null $auftragsreferenz - Die Auftragsreferenz muss im zweiten Schritt gesetzt werden..
     *
     * @return array|void
     */
    public function doBankOrder($classOrder, $tan = null, $auftragsreferenz = null, $dialogid = null) {
        return $this->_doBankOrder($classOrder, $tan , $auftragsreferenz, $dialogid);
    }

    /**
     * Get the turnovers. It returns true if everything okay, otherwise it returns an array with information for TAN.
     *
     * @param $startDate (optional) - Start-Datum ab welchem die Umsätze abgeholt werden. (Format: JJJJMMTT)
     * @param $endDate (optional) - End-Datum bis zu welchem die Umsätze maximal abgeholt werden. (Format: JJJJMMTT)
     * @param string $returnType - set 'mt940' for a string in mt940 format, otherwise array with parsed mt940 will be returned.
     *
     * @return array|bool
     */
    public function getAccountTurnovers($startDate = null, $endDate = null, $tan = null, $auftragsreferenz = null, $dialogid = null, $returnType = 'array') {
        return $this->_getAccountTurnovers($startDate, $endDate, '', $tan, $auftragsreferenz, $dialogid, $returnType);
    }

    /**
     * Gets the account turnovers in a raw MT940 Format
     *
     * @param $startDate (optional) - Start-Datum ab welchem die Umsätze abgeholt werden. (Format: JJJJMMTT)
     * @param $endDate (optiona9 - End-Datum bis zu welchem die Umsätze maximal abgeholt werden. (Format: JJJJMMTT)
     *
     * @return array|string
     */
    public function getMT940Turnovers($startDate = null, $endDate = null) {
        return $this->_getAccountTurnovers($startDate, $endDate, '', 'mt940');
    }

    /** Returns a string with informations abount the current library version. */
    public function getVersion() {
        return $this->_getVersion();
    }

    public function getLog() {
        return htmlentities($this->log);
    }
}

class BusinessDebit extends BusinessDebitBase
{
    /**
     * @param $transactionDate - Datum der Firmenlastschrift
     * @param $creditorId - Gläubiger-ID
     * @param null $kundenRef - Referenz (Payment-Information-ID)
     */
    public function __construct($transactionDate, $creditorId, $kundenRef=NULL)
    {
        parent::__construct($transactionDate, $creditorId, $kundenRef);
    }

    /**
     * @param $amount - Betrag
     * @param $name - Name des Zahlungspflichtigen
     * @param $iban - IBAN des Zahlungspflichtigen
     * @param null $bic - BIC des Zahlungspflichtigen. Bei Lastschriften in Deutschland optional.
     * @param null $purpose - Verwendungszweck
     * @param null $e2eRef - Ende-zu-Ende Referenz der Zahlung
     * @param null $mandateRef - Mandantsreferenz
     * @param null $mandateDate - Ausstellungsdatum des Mandates
     * @param string $seqType - OOFF: Einmalige Lastschrift, FRST: Erste Lastschrift, RCUR: Wiederholte Lastschrift, FNAL: Letzte Lastschrift
     */
    public function add( $amount, $name, $iban, $bic=NULL, $purpose=NULL, $e2eRef=NULL, $mandateRef, $mandateDate, $seqType = 'OOFF' ) {
        parent::add($amount, $name, $iban, $bic, $purpose, $e2eRef, $mandateRef, $mandateDate, $seqType);
    }

    /**
     * @param $auftraggeber - Name des Auftraggebers
     * @param $iban - IBAN des Auftraggebers
     * @param $bic - BIC des Auftragsgebers
     * @return string - XML SEPA Nachricht
     */
    public function getXML($auftraggeber, $iban, $bic) {
        return parent::getXML($auftraggeber, $iban, $bic);
    }
}

class Debit extends DebitBase
{
    /**
     * @param $transactionDate - Datum der Lastschrift
     * @param $creditorId - Gläubiger-ID
     * @param null $kundenRef - Referenz (Payment-Information-ID)
     * @param string $type - CORE: Normale Lastschrift, COR1 für Lastschrift im Eilverfahren
     */
    public function __construct($transactionDate, $creditorId, $type="CORE", $kundenRef=NULL)
    {
        parent::__construct($transactionDate, $creditorId, $type, $kundenRef);
    }

    /**
     * @param $amount - Betrag
     * @param $name - Name des Zahlungspflichtigen
     * @param $iban - IBAN des Zahlungspflichtigen
     * @param null $bic - BIC des Zahlungspflichtigen. Bei Lastschriften in Deutschland optional.
     * @param null $purpose - Verwendungszweck
     * @param null $e2eRef - Ende-zu-Ende Referenz der Zahlung
     * @param null $mandateRef - Mandantsreferenz
     * @param null $mandateDate - Ausstellungsdatum des Mandates
     * @param string $seqType - OOFF: Einmalige Lastschrift, FRST: Erste Lastschrift, RCUR: Wiederholte Lastschrift, FNAL: Letzte Lastschrift
     */
    public function add( $amount, $name, $iban, $bic=NULL, $purpose=NULL, $e2eRef=NULL, $mandateRef, $mandateDate, $seqType = 'OOFF' ) {
        parent::add($amount, $name, $iban, $bic, $purpose, $e2eRef, $mandateRef, $mandateDate, $seqType);
    }

    /**
     * @param $auftraggeber - Name des Auftraggebers
     * @param $iban - IBAN des Auftraggebers
     * @param $bic - BIC des Auftragsgebers
     * @return string - XML SEPA Nachricht
     */
    public function getXML($auftraggeber, $iban, $bic) {
        return parent::getXML($auftraggeber, $iban, $bic);
    }
}

class Credit extends CreditBase
{
    /**
     * @param null $kundenRef - Referenz (Payment-Information-ID)
     * @param string $chargeBearer - SEPA Entgeltverrechnung: Konstante SLEV (Kosten werden geteilt). Weitere Optionen: DEBT (Auftraggeber trägt alle Kosten), CRED (Empfänger trägt alle Kosten)
     */
    public function __construct($kundenRef = NULL, $chargeBearer = 'SLEV') {
        parent::__construct($kundenRef, $chargeBearer);
    }

    /**
     * @param $amount - Betrag
     * @param $name - Name des Zahlungsempfängers
     * @param $iban - IBAN des Zahlungsempfängers
     * @param null $bic - BIC des Zahlungsempfängers. Innerhalb Deutschlands optional.
     * @param null $purpose - Verwendungszweck
     * @param null $e2eRef - Ende-zu-Ende Referenz der Zahlung
     */
    public function add($amount, $name, $iban, $bic=NULL, $purpose=NULL, $e2eRef=NULL) {
        parent::add($amount, $name, $iban, $bic, $purpose, $e2eRef);
    }

    /**
     * @param $auftraggeber - Name des Auftraggebers
     * @param $iban - IBAN des Auftraggebers
     * @param $bic - BIC des Auftragsgebers
     * @return string - XML SEPA Nachricht
     */
    public function getXML($auftraggeber, $iban, $bic) {
        return parent::getXML($auftraggeber, $iban, $bic);
    }
}

class ScheduledCredit extends ScheduledCreditBase
{
    /**
     * @param $transactionDate - Datum an dem die Überweisung getätigt werden soll.
     * @param null $kundenRef - Referenz (Payment-Information-ID)
     * @param string $chargeBearer
     */
    public function __construct($transactionDate, $kundenRef = NULL, $chargeBearer = 'SLEV')
    {
        parent::__construct($transactionDate, $kundenRef, $chargeBearer);
    }

    /**
     * @param $amount - Betrag
     * @param $name - Name des Zahlungsempfängers
     * @param $iban - IBAN des Zahlungsempfängers
     * @param null $bic - BIC des Zahlungsempfängers. Innerhalb Deutschlands optional.
     * @param null $purpose - Verwendungszweck
     * @param null $e2eRef - Ende-zu-Ende Referenz der Zahlung
     */
    public function add( $amount, $name, $iban, $bic=NULL, $purpose=NULL, $e2eRef=NULL ) {
        parent::add($amount, $name, $iban, $bic, $purpose, $e2eRef);
    }

    /**
     * @param $auftraggeber - Name des Auftraggebers
     * @param $iban - IBAN des Auftraggebers
     * @param $bic - BIC des Auftragsgebers
     * @return string - XML SEPA Nachricht
     */
    public function getXML($auftraggeber, $iban, $bic) {
        return parent::getXML($auftraggeber, $iban, $bic);
    }
}