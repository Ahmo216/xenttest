<?php
/**
 * HBCI4PHP Extended Library
 *
 * Copyright 2015 Web.Cloud.Apps. GmbH | Schillerstr. 14 | 71638 Ludwigsburg. | Alle Rechte vorbehalten.
 * Telefon: 07141-2589050 | E-Mail: info@web-cloud-apps.com
 *
 * Produkt-ID: CF84205B0B0556F80DB66A221
 **/

class HBCI4PHPBase
{
    public $error = false;
    public $errorMessage = "";

    private $EXPIRE_TIMESTAMP = 0;
    private $LICENSE_VERSION = 4;
    private $TYPE = 'Regular'; // Regular, Extended
    private $EDITION = 'Basic'; // Basic, Plus, Pro
    private $LICENSED_TO = '';

    // Users login data to the bank account
    private $userPin = "";
    private $iban = "";
    private $bic = "";
    private $userAccount = "";
    private $userAccountNumber = "";
    private $userBlz = "";
    private $hbciServerUri = "https://hbci-pintan-by.s-hbci.de/PinTanServlet"; // DKB

    // Here we save the name of the user
    private $kontoUserName = "";

    // segment counter. Should be increment everytime when it is used.
    // a segment is part of a message and end with (')
    private $segmentNumber = 2;

    // Dialog-ID
    // The Dialog-ID is returned by the bank, when we start a conversation
    private $dialogId = 0;

    // sending message number
    // The message number it the number of our messages within a dialog
    private $messageNumber = 1;

    // Variable holding the log
    protected $log = "";

    // variable holding the file for the log
    private $logFile = null;

    // This is the systemID for autorising. When we do not have one, we will ask for one..
    private $systemId = "0";

    // Supported TAN methods array
    private $supportedTanMethods = array();

    // Supported TAN methods array
    private $allowedTanMethods = array();

    // Allowed PIN/TAN - Default 999
    private $pinTanMethod = "999";

    private $arrTANMedia = array();

    // BPD Version
    private $bpdVersion = "0";

    // UPD Version
    private $updVersion = "0";

    // HBCI Version
    private $hbciVersion = "300";

    // HKSAL Version (Get Saldo)
    private $arrSupportedHKSALVersions = array(5, 6, 7);
    private $HKSALVersion = 0;  // If fields remains zero, this function is not supported

    // HKKAZ Version (Get Umsaetze)
    private $arrSupportedHKKAZVersions = array(5, 6, 7);
    private $HKKAZVersion = 0;  // If fields remains zero, this function is not supported

    // HKTAB - get supported TAN-medien
    private $arrSupportedHKTABVersions = array(2, 3, 4, 5);
    private $HKTABVersion = 0;  // If fields remains zero, this function is not supported

    // HKTAN
    private $arrSupportedHKTANVersions = array(3, 4, 5, 6);
    private $HKTANVersion = 0;  // If fields remains zero, this function is not supported

    // Check for allowed jobs
    private $allowedHKTAB = false;

    // The name and version of our System
    protected $registrationNumber = "";
    private $product_name = "HBCI4PHP";
    private $product_version = "4.0.1";

    // shutdown the SSL Verification
    private $SSL_VERFIYPEER = 1;

    // Runtime token, for logging to file
    private $runtimeToken = '';

    private $anonymDialogRunning = false;
    private $nationalAccountAllowed = true;

    private $authNeeded = false;
    private $authChallenge = [];

    private function checkLicence() {
        // Check expire
        $time = time();
        if( $this->EXPIRE_TIMESTAMP > 0 && $time > $this->EXPIRE_TIMESTAMP )
            die("Ungültige oder abgelaufene Lizenz!");

        if( $this->LICENSE_VERSION < 4 )
            die("Ungültige oder abgelaufene Lizenz!");
    }

    private function quersumme($digits)
    {
        $strDigits = ( string ) $digits;

        for( $intCrossfoot = $i = 0; $i < strlen ( $strDigits ); $i++ ) {
            $intCrossfoot += $strDigits{$i};
        }

        return $intCrossfoot;
    }

    private function arrayToJS($dataArray)
    {
        $result = 'var hhdData = [];';

        foreach( $dataArray as $row ) {
            $result .= 'hhdData.push([' . implode(', ', $row) . ']);';
        }

        return $result;
    }

    protected function _getTanGraphics($challenge)
    {
        $tanMethodParamter = $this->supportedTanMethods[$this->pinTanMethod];
        $tanVersion = $tanMethodParamter['version'];

        $lcLength = 3;
        if( $tanVersion == '1.3.2' )
            $lcLength = 2;

        // ToDo: Check challenge format. If not valid, return false
        $luhn = 0;
        $xor = 0;

        $currentStringPosition = 0;
        $result = array();

        // Insert sync
        $result[] = array(0, 0, 0, 0);
        $result[] = array(1, 1, 1, 1);
        $result[] = array(1, 1, 1, 1);

        // Insert LC
        $lc = (int) substr($challenge, $currentStringPosition, $lcLength);
        $currentStringPosition += $lcLength;

        $lsb = array(0, 0, 0, 0);
        $result[] = $lsb;

        $msb = array(0, 0, 0, 0);
        $result[] = $msb;

        // Get data from LC
        $totalStringLength = $lc;

        // Insert LS
        $ls = substr($challenge, $currentStringPosition, 2);
        $currentStringPosition += 2;

        $ls = base_convert($ls, 16, 10);

        $lsb = array(0, 0, 0, 0);
        if( $ls & 0x01 ) $lsb[0] = 1;
        if( $ls & 0x02 ) $lsb[1] = 1;
        if( $ls & 0x04 ) $lsb[2] = 1;
        if( $ls & 0x08 ) $lsb[3] = 1;
        $result[] = $lsb;

        $msb = array(0, 0, 0, 0);
        if( $ls & 0x10 ) $msb[0] = 1;
        if( $ls & 0x20 ) $msb[1] = 1;
        if( $ls & 0x40 ) $msb[2] = 1;
        if( $ls & 0x80 ) $msb[3] = 1;
        $result[] = $msb;

        // Get data from LS
        $lengthStartCode = $ls & 0x3F;
        $startCodeASCII = ($ls & 0x40) >> 6;
        $hasControlByte = $ls >> 7;

        if( $hasControlByte ) {
            $controlByte = substr($challenge, $currentStringPosition, 2);
            $controlByte = base_convert($controlByte, 16, 10);

            $lsb = array(0, 0, 0, 0);
            if( $controlByte & 0x01 ) $lsb[0] = 1;
            if( $controlByte & 0x02 ) $lsb[1] = 1;
            if( $controlByte & 0x04 ) $lsb[2] = 1;
            if( $controlByte & 0x08 ) $lsb[3] = 1;
            $result[] = $lsb;

            $msb = array(0, 0, 0, 0);
            if( $controlByte & 0x10 ) $msb[0] = 1;
            if( $controlByte & 0x20 ) $msb[1] = 1;
            if( $controlByte & 0x40 ) $msb[2] = 1;
            if( $controlByte & 0x80 ) $msb[3] = 1;
            $result[] = $msb;

            $currentStringPosition += 2;

            $luhn += ($controlByte & 0xF0) >> 4;
            $luhn += $this->quersumme(($controlByte & 0x0F) * 2);

            // XOR over ControlByte
            $xor = $xor ^ ($controlByte >> 4);
            $xor = $xor ^ ($controlByte & 0x0F);
        }

        // Get start code
        if( $startCodeASCII ) {

        }
        else
        {
            // temporary, because we need to change
            $tmpResult = array();

            for( $i = 0; $i < $lengthStartCode; $i++)
            {
                $char = (int) substr($challenge, $currentStringPosition, 1);

                $lsb = array(0, 0, 0, 0);
                if( $char & 0x01 ) $lsb[0] = 1;
                if( $char & 0x02 ) $lsb[1] = 1;
                if( $char & 0x04 ) $lsb[2] = 1;
                if( $char & 0x08 ) $lsb[3] = 1;
                $tmpResult[] = $lsb;

                $currentStringPosition++;

                // Calculate LUHN
                if( $i%2 == 0 )
                    $luhn += $char;
                else
                    $luhn += $this->quersumme(($char & 0x0F) * 2);

                // XOR over StartCode
                $xor = $xor ^ $char;
            }

            if( ($lengthStartCode%2) == 1 ) {
                $i++;
                $tmpResult[] = array(1, 1, 1, 1);
                $xor = $xor ^ 0x0F;
                $luhn += $this->quersumme(0x0F * 2);
            }

            for($i = 0; $i < count($tmpResult); $i = $i+2)
            {
                $result[] = $tmpResult[$i+1];
                $result[] = $tmpResult[$i];
            }

            // Recalculate LS and set it
            $lengthStartCode = ($i/2);

            // recuperate first 3 bits
            $lengthStartCode = ($ls&0xE0) | ($lengthStartCode&0x1F);

            $lsb = array(0, 0, 0, 0);
            if( $lengthStartCode & 0x01 ) $lsb[0] = 1;
            if( $lengthStartCode & 0x02 ) $lsb[1] = 1;
            if( $lengthStartCode & 0x04 ) $lsb[2] = 1;
            if( $lengthStartCode & 0x08 ) $lsb[3] = 1;
            $result[5] = $lsb;

            $msb = array(0, 0, 0, 0);
            if( $lengthStartCode & 0x10 ) $msb[0] = 1;
            if( $lengthStartCode & 0x20 ) $msb[1] = 1;
            if( $lengthStartCode & 0x40 ) $msb[2] = 1;
            if( $lengthStartCode & 0x80 ) $msb[3] = 1;
            $result[6] = $msb;

            // XOR over LS
            $xor = $xor ^ ($lengthStartCode >> 4);
            $xor = $xor ^ ($lengthStartCode & 0x0F);
        }

        // Check if we have further data

        while( $currentStringPosition < $totalStringLength ) {
            // Get LDE1
            $lde1 = (int)substr($challenge, $currentStringPosition, 2);
            $currentStringPosition += 2;

            // Set ASCII to LDE1
            $_lde1 = $lde1 | 0x40;

            $lsb = array(0, 0, 0, 0);
            if ($_lde1 & 0x01) $lsb[0] = 1;
            if ($_lde1 & 0x02) $lsb[1] = 1;
            if ($_lde1 & 0x04) $lsb[2] = 1;
            if ($_lde1 & 0x08) $lsb[3] = 1;
            $result[] = $lsb;

            $msb = array(0, 0, 0, 0);
            if ($_lde1 & 0x10) $msb[0] = 1;
            if ($_lde1 & 0x20) $msb[1] = 1;
            if ($_lde1 & 0x40) $msb[2] = 1;
            if ($_lde1 & 0x80) $msb[3] = 1;
            $result[] = $msb;

            // XOR over LDE1
            $xor = $xor ^ ($_lde1 >> 4);
            $xor = $xor ^ ($_lde1 & 0x0F);

            // Get DE1
            for ($i = 0; $i < $lde1; $i++) {
                $char = ord(substr($challenge, $currentStringPosition, 1));

                $lsb = array(0, 0, 0, 0);
                if ($char & 0x01) $lsb[0] = 1;
                if ($char & 0x02) $lsb[1] = 1;
                if ($char & 0x04) $lsb[2] = 1;
                if ($char & 0x08) $lsb[3] = 1;
                $result[] = $lsb;

                $msb = array(0, 0, 0, 0);
                if ($char & 0x10) $msb[0] = 1;
                if ($char & 0x20) $msb[1] = 1;
                if ($char & 0x40) $msb[2] = 1;
                if ($char & 0x80) $msb[3] = 1;
                $result[] = $msb;

                $currentStringPosition++;

                // Calculate LUHN
                $luhn += ($char & 0xF0) >> 4;
                $luhn += $this->quersumme(($char & 0x0F) * 2);

                // XOR over DE1
                $xor = $xor ^ ($char >> 4);
                $xor = $xor ^ ($char & 0x0F);
            }
        }

        // Calculate and correct LC
        $totalMsgLength = ((count($result)-3)/2) - 1 + 1; // For the ControlByte
        $lsb = array(0, 0, 0, 0);
        if( $totalMsgLength & 0x01 ) $lsb[0] = 1;
        if( $totalMsgLength & 0x02 ) $lsb[1] = 1;
        if( $totalMsgLength & 0x04 ) $lsb[2] = 1;
        if( $totalMsgLength & 0x08 ) $lsb[3] = 1;
        $result[3] = $lsb;

        $msb = array(0, 0, 0, 0);
        if( $totalMsgLength & 0x10 ) $msb[0] = 1;
        if( $totalMsgLength & 0x20 ) $msb[1] = 1;
        if( $totalMsgLength & 0x40 ) $msb[2] = 1;
        if( $totalMsgLength & 0x80 ) $msb[3] = 1;
        $result[4] = $msb;

        // XOR over LC
        $xor = $xor ^ ($totalMsgLength >> 4);
        $xor = $xor ^ ($totalMsgLength & 0x0F);

        // Finish and add XOR (LSB)
        $lsb = array(0, 0, 0, 0);
        if ($xor & 0x01) $lsb[0] = 1;
        if ($xor & 0x02) $lsb[1] = 1;
        if ($xor & 0x04) $lsb[2] = 1;
        if ($xor & 0x08) $lsb[3] = 1;
        $result[] = $lsb;

        // Finish LUHN (MSB)
        $endLuhn = $luhn % 10;
        if( $endLuhn != 0 )
            $endLuhn = 10 - $endLuhn;

        $lsb = array(0, 0, 0, 0);
        if ($endLuhn & 0x01) $lsb[0] = 1;
        if ($endLuhn & 0x02) $lsb[1] = 1;
        if ($endLuhn & 0x04) $lsb[2] = 1;
        if ($endLuhn & 0x08) $lsb[3] = 1;
        $result[] = $lsb;

        return $this->arrayToJS($result);
    }

    protected function _getVersion() {
        $resultString = $this->product_name . ' ' . $this->product_version . ' ' . $this->TYPE . " " . $this->EDITION . "\n";
        $resultString .= 'Lizenziert auf: ' . $this->LICENSED_TO . "\n";
        $resultString .= 'Ablaufdatum: ' . ($this->EXPIRE_TIMESTAMP > 0?date("d.m.Y H:i", $this->EXPIRE_TIMESTAMP):'-') . "\n";

        return $resultString;
    }

    private function log($message) {
        $this->log .= utf8_encode($message . "\n");

        if( $this->logFile ) {
            $currentTime = date("Y-m-d H:i:s", time());
            fwrite($this->logFile, $currentTime . ' [' . $this->runtimeToken . '] ' . $message . "\n");
        }
    }

    protected function _initHBCI4PHP($account, $iban, $bic, $pin, $serverUrl, $pintanMethod = null, $logLevel = 1, $hbci = "300", $sslVerfiyPeer = 1, $logFilePath = null, $tan = null, $auftragsreferenz = null, $dialogid = null)
    {
        // Create a token
        if( $logFilePath ) {
            $this->runtimeToken = bin2hex(openssl_random_pseudo_bytes(6));
        }

        // set UTF-8
        mb_internal_encoding('UTF-8');

        // Get the license information
        $license = ioncube_license_properties();
        if( !empty($license) ) {
            if( isset($license['expires']['value']) )
                $this->EXPIRE_TIMESTAMP = $license['expires']['value'];

            if( isset($license['company']['value']) )
                $this->LICENSED_TO = $license['company']['value'];

            if( isset($license['type']['value']) )
                $this->TYPE = $license['type']['value'];

            if( isset($license['edition']['value']) )
                $this->EDITION = $license['edition']['value'];

            if( isset($license['version']['value']) )
                $this->LICENSE_VERSION = $license['version']['value'];
        }

        // First of all, check the licence
        $this->checkLicence();

        // Check for HBCI version
        if( $hbci != 300 ) {
            $this->setError("HBCI Version wird nicht unterstützt!");
            return;
        }

        /** Save the settings to class */
        $this->userPin = utf8_decode($pin);
        $this->iban = $iban;
        $this->userAccount = $this->HBCIEscape($account);
        $this->userAccountNumber = (int)$this->HBCIEscape( substr($iban, 12, 10) );
        $this->userBlz = (int)substr($iban, 4, 8);
        $this->bic = $bic;
        $this->SSL_VERFIYPEER = $sslVerfiyPeer?1:0;

        if( $logFilePath ) {
            $fp = fopen($logFilePath, 'a');
            if( $fp === false ) {
                $this->log('Log-Datei kann nicht zum Schreiben geöffnet werden. Bitte prüfen Sie die Schreibrechte.');
            }
            else {
                $this->logFile = $fp;
            }
        }

        if( $hbci != null )
            $this->hbciVersion = $hbci;

        if( $serverUrl != null )
            $this->hbciServerUri = $serverUrl;

        if( is_numeric($logLevel) )
            $this->logLevel = $logLevel;

        if( $this->logLevel > 0 ) {
            $this->log("--- HBCI4PHP INITIALIZATION STARTED ---");
            $this->log("SYSTEM DATE: " . date('Y-m-d H:i:s', time()));
            $this->log("ACCOUNT: $account");
            $this->log("ACCOUNT-NUMBER: " . $this->userAccountNumber);
            $this->log("BLZ: " . $this->userBlz);
            $this->log("BIC: " . $this->bic);
            $this->log("PIN: XXXXXX");
            $this->log("HBCI: $hbci");
            $this->log("HBCI ENDPOINT URI: $serverUrl");
            $this->log("--- HBCI4PHP INITIALIZATION ENDED ---");
        }

        // Start anonym message
        $this->anonymMessage();

        // Send synchronize message
        $this->synchroniseMessage();

        // Check if TAN-Method allowed and select it
        if( $pintanMethod !== null ) {
            $allowed = $this->_getAllowedTanMethods();
            if( !$pintanMethod || !isset($allowed[$pintanMethod]) ) {
                $this->setError("TAN-Method empty or not allowed!");
                return;
            }
            $this->pinTanMethod = $pintanMethod;
        }

        // Read TAN Media
        $this->_requestTANMedia();

        // Send again SYNC but secure
        $this->synchroniseMessageSecure($tan, $auftragsreferenz, $dialogid);
    }

    protected function _setRegistrationNumber($registrationNumber) {
        $this->registrationNumber = $registrationNumber;
    }

    protected function _getAllowedTanMethods()
    {
        $result = array();

        foreach( $this->allowedTanMethods as $tanId ) {
            if( isset($this->supportedTanMethods[$tanId]) ) {
                $result[$tanId] = $this->supportedTanMethods[$tanId];
            }
        }

        return $result;
    }

    protected function _getCurrentTanMethod()
    {
        return $this->pinTanMethod;
    }

    protected function _requestTANMedia()
    {
        /** Init the dialog  */
        $this->initDialogMessage(true, 'HKTAB');

        // Get list of generatores
        if( $this->HKTABVersion > 0  ) { // && $this->allowedHKTAB == true ) {
            $this->getGeneratorList();
        }

        // End dialog
        $this->endDialogMessage();;
    }

    protected function _authNeeded()
    {
        return $this->authNeeded;
    }

    protected function _getAuthChallenge()
    {
        return $this->authChallenge;
    }

    protected function _getTANMedia()
    {
        $result = [];
        foreach( $this->arrTANMedia as $mediaName ) {
            $result[] = $mediaName;
        }

        return $result;
    }

    protected function _setTanMedia($mediaName)
    {
        foreach( $this->arrTANMedia as $name ) {
            if( $mediaName == $name ) {
                $this->supportedTanMethods[$this->pinTanMethod]['mediumname'] = $name;
                return true;
            }
        }

        return false;
    }

    protected function _getAccountTurnovers($startDate = null, $endDate = null, $aufsetzpunkt = '', $tan = null, $auftragsreferenz = null, $dialogid = null, $returnType = 'array')
    {
        // Check if we have an error, then don't continue
        if( $this->error == true )
            return;

        /** Check date parameter and correct */
        if( $startDate == null ) {
            $startDate = '';
        }

        if( $endDate == null ) {
            $endDate = '';
        }

        // Check the start date
        if( $startDate != null )
        {
            if( strlen($startDate) != 8 ) {
                die('FEHLER: Das Startdatum fuer die Umsaetze scheint ein falsches Format zu haben.');
            }

            $y = substr($startDate, 0, 4);
            $m = substr($startDate, 4, 2);
            $d = substr($startDate, 6, 2);

            $startTime = mktime(0, 0, 0, $m, $d, $y);

            if( $startTime > time() ) {
                die('FEHLER: Das Startdatum fuer die Umsaetze scheint ein falsches Format zu haben oder liegt in der Zukunft.');
            }
        }

        // If we got an end date, check if its not in the future
        if( $endDate != null )
        {
            if( strlen($endDate) != 8 ) {
                die('FEHLER: Das Enddatum fuer die Umsaetze scheint ein falsches Format zu haben.');
            }

            $y = substr($endDate, 0, 4);
            $m = substr($endDate, 4, 2);
            $d = substr($endDate, 6, 2);

            $endTime = mktime(0, 0, 0, $m, $d, $y);

            if( $endTime > time() ) {
                die('FEHLER: Das Enddatum fuer die Umsaetze scheint ein falsches Format zu haben oder liegt in der Zukunft.');
            }
        }
        else {
            // Make sure we don't allow to get payments newer then EXPIRE_TIMESTAMP
            if( $this->EXPIRE_TIMESTAMP > 0 ) {
                $endTime = time();
                $endDate = date("Ymd", $endTime);
            }
        }

        // Check the dates to each other
        if( $startDate != null && $endDate != null )
        {
            if( $startTime > $endTime ) {
                die('FEHLER: Das Enddatum fuer die Umsaetze darf nicht vor dem Startdatum liegen.');
            }
        }

        /** Check if we support this function */
        if( $this->HKKAZVersion == 0 ) {
            $this->log("GET ACCOUNT TURNOVERS NOT SUPPORTED");
            return;
        }

        if( $this->logLevel > 0 ) {
            $this->log("ACTION: GET ACCOUNT TURNOVERS");
            $this->log("AUFSETZPUNKT: $aufsetzpunkt");
        }

        // Start new Dialog or resume last one
        if( $dialogid == null || empty($dialogid) || $auftragsreferenz == null && empty($auftragsreferenz) ) {
            $message = $this->initDialogMessage(true, 'HKIDN');

            if( $this->readAuthNeeded($message) ) {
                $auftragsreferenz = $this->messageNumber . $this->readAuftragsReferenz($message);
                $challenge = $this->readChallenge($message);
                $challengeData = $this->readChallengeData($message);

                $this->authNeeded = true;
                $this->authChallenge = [
                    'auftragsreferenz' => $auftragsreferenz,
                    'dialogid' => $this->dialogId,
                    'systemid' => $this->systemId,
                    'challenge' => $challenge,
                    'challenge_data' => $challengeData
                ];

                return false;
            }

            /** create message **/
            $message = $this->createHNSHK();
            $message .= $this->createHKKAZ($startDate, $endDate, $aufsetzpunkt);

            if( $this->HKTANVersion == 6 ) {
                $message .= $this->createHKTAN(4);
            }

            $message .= $this->createHNSHA($tan);

            /** send message and store answer in correspondent variable $answer **/
            $message = $this->sendMessage($message);

            // Check message for auth request. Then return dialogid and auftragsreferenz
            // HIRMS:4:2:4+0030::Auftrag empfangen - Sicherheitsfreigabe erforderlich'
            if( $this->readAuthNeeded($message) ) {
                $auftragsreferenz = $this->messageNumber . $this->readAuftragsReferenz($message);
                $challenge = $this->readChallenge($message);
                $challengeData = $this->readChallengeData($message);

                $this->authNeeded = true;
                $this->authChallenge = [
                    'auftragsreferenz' => $auftragsreferenz,
                    'dialogid' => $this->dialogId,
                    'systemid' => $this->systemId,
                    'challenge' => $challenge,
                    'challenge_data' => $challengeData
                ];

                return false;
            }
        }
        else {
            $this->dialogId = $dialogid;        // Set our dialog ID

            // Regenerate the message number
            $this->messageNumber = substr($auftragsreferenz, 0, 1);
            $auftragsreferenz = substr($auftragsreferenz, 1);

            // SUBMIT TAN
            $message = $this->createHNSHK();

            if( $this->HKTANVersion == 6 ) {
                $message .= $this->createHKTAN(2, $auftragsreferenz);
            }

            $message .= $this->createHNSHA($tan);
            $message = $this->sendMessage($message);
        }

        /** parse MT940 Message and get information about account turnovers. */
        $turnoverReults = $this->parseMT940Message($startDate, $endDate, $message, $returnType);

        /** End dialog and clean up the fields */
        if( $dialogid != null  && $aufsetzpunkt == '' )
            $this->endDialogMessage();

        return $turnoverReults;
    }

    protected function _getSaldo()
    {
        // Check if we have an error, then don't continue
        if( $this->error == true )
            return;

        /** Check if we support this function */
        if( $this->HKSALVersion == 0 ) {
            $this->log("GET SALDO NOT SUPPORTED");
            return;
        }

        /** Init the dialog  */
        $this->initDialogMessage();

        if( $this->logLevel > 0 ) {
            $this->log("ACTION: GET SALDO");
        }

        /** create message **/
        $message = $this->createHNSHK();
        $message .= $this->createHKSAL();
        $message .= $this->createHNSHA();

        $answer = $this->sendMessage($message);

        // parsing regular expression
        $pattern = "|HISAL[^\+]*\+[^\+]*\+[^\+]*\+[^\+]*\+([CD]):([0-9]+,([0-9]+)?)|";
        $matches = array();
        preg_match($pattern, $answer, $matches);

        $returnValue = "";
        if( isset($matches[1]) && isset($matches[2]) ) {
            // Check if we had comas
            if( !isset($matches[3]) || empty($matches[3]) ) {
                $matches[2] .= '00';
            }

            $returnValue = str_replace(",", ".", $matches[2]);

            if( $matches[1] == "D" ) {
                $returnValue = -$returnValue;
            }
        }

        /** End dialog and clean up fields */
        $this->endDialogMessage();

        // return saldo if it has been read successfully, otherwise return null
        return $returnValue;
    }

    protected function _doBankOrder($classOrder, $tan = null, $auftragsreferenz = null, $dialogid = null)
    {
        switch( get_class($classOrder) ) {
            case 'Debit':
            case 'BusinessDebit':
                return $this->_doDebit($classOrder, $tan, $auftragsreferenz, $dialogid);

            case 'Credit':
            case 'ScheduledCredit':
            default:
                return $this->_doCredit($classOrder, $tan, $auftragsreferenz, $dialogid);
        }
    }

    private function _doCredit($classTransfer, $tan = null, $auftragsreferenz = null, $dialogid = null)
    {
        // Check if we have an error, then don't continue
        if( $this->error == true )
            return;

        // Basic, Plus, Pro
        if( $this->EDITION != 'Plus' && $this->EDITION != 'Pro' ) {
            $this->setError("Ihre Version unterstützt diesen Geschäftsvorfall nicht!");
            return;
        }

        // Check if we have transfer and its the right class
        if( (!is_a($classTransfer, 'Credit') && !is_a($classTransfer, 'ScheduledCredit')) || $classTransfer->getTotalCount() < 1 ) {
            $this->setError("Überweisung fehlerhaft oder enthielt keine Daten.");
            return;
        }

        /** Init the dialog  */
        if( $dialogid == null || empty($dialogid) || $auftragsreferenz == null && empty($auftragsreferenz) ) {
            $message = $this->initDialogMessage(true, 'HKIDN');

            if( $this->readAuthNeeded($message) ) {
                $auftragsreferenz = $this->messageNumber . $this->readAuftragsReferenz($message);
                $challenge = $this->readChallenge($message);
                $challengeData = $this->readChallengeData($message);

                $this->authNeeded = true;
                $this->authChallenge = [
                    'auftragsreferenz' => $auftragsreferenz,
                    'dialogid' => $this->dialogId,
                    'systemid' => $this->systemId,
                    'challenge' => $challenge,
                    'challenge_data' => $challengeData
                ];

                return $this->authChallenge;
            }
        }
        else {
            $this->resetDialogData();           // make sure we reset the dialog data
            $this->dialogId = $dialogid;        // Set our dialog ID

            // Regenerate the message number
            $this->messageNumber = substr($auftragsreferenz, 0, 1);
            $auftragsreferenz = substr($auftragsreferenz, 1);
        }

        if( $this->logLevel > 0 ) {
            $this->log("ACTION: DO TRANSFER");
        }

        /** create message **/
        // message
        $message = $this->createHNSHK();
        if( $tan == null && $auftragsreferenz == null ) {
            $message .= $this->createHKCCM($classTransfer);
            $message .= $this->createHKTAN(4);
        }
        else {
            $message .= $this->createHKTAN(2, $auftragsreferenz);
        }
        $message .= $this->createHNSHA($tan);

        $answer = $this->sendMessage($message);

        // Parse the results
        $auftragsreferenz = $this->messageNumber . $this->readAuftragsReferenz($answer);
        $challenge = $this->readChallenge($answer);
        $challengeData = $this->readChallengeData($answer);

        /** End dialog and clean up fields */
        if( $dialogid != null )
            $this->endDialogMessage();

        return array( 'auftragsreferenz' => $auftragsreferenz, 'dialogid' => $this->dialogId, 'systemid' => $this->systemId, 'challenge' => $challenge, 'challenge_data' => $challengeData );
    }

    private function _doDebit($classDebit, $tan = null, $auftragsreferenz = null, $dialogid = null)
    {
        // Check if we have an error, then don't continue
        if( $this->error == true )
            return;

        // Basic, Plus, Pro
        if( $this->EDITION != 'Pro' ) {
            $this->setError("Ihre Version unterstützt diesen Geschäftsvorfall nicht!");
            return;
        }

        // Check if we have transfer and its the right class
        if( !is_a($classDebit, 'Debit') || $classDebit->getTotalCount() < 1 ) {
            $this->setError("Auftrag fehlerhaft oder enthielt keine Daten.");
            return;
        }

        /** Init the dialog  */
        if( $dialogid == null || empty($dialogid) || $auftragsreferenz == null && empty($auftragsreferenz) ) {
            $this->initDialogMessage(true, 'HKIDN');

            if( $this->readAuthNeeded($message) ) {
                $auftragsreferenz = $this->messageNumber . $this->readAuftragsReferenz($message);
                $challenge = $this->readChallenge($message);
                $challengeData = $this->readChallengeData($message);

                $this->authNeeded = true;
                $this->authChallenge = [
                    'auftragsreferenz' => $auftragsreferenz,
                    'dialogid' => $this->dialogId,
                    'systemid' => $this->systemId,
                    'challenge' => $challenge,
                    'challenge_data' => $challengeData
                ];

                return $this->authChallenge;
            }
        }
        else {
            $this->resetDialogData();           // make sure we reset the dialog data
            $this->dialogId = $dialogid;        // Set our dialog IDz

            // Regenerate thr message number
            $this->messageNumber = substr($auftragsreferenz, 0, 1);
            $auftragsreferenz = substr($auftragsreferenz, 1);
        }

        if( $this->logLevel > 0 ) {
            $this->log("ACTION: DO DEBIT SUBMIT");
        }

        // message
        $message = $this->createHNSHK();
        if( $tan == null && $auftragsreferenz == null ) {
            $message .= $this->createHKDSE($classDebit);
            $message .= $this->createHKTAN(4);
        }
        else {
            $message .= $this->createHKTAN(2, $auftragsreferenz);
        }
        $message .= $this->createHNSHA($tan);

        $answer = $this->sendMessage($message);

        // Parse the results
        $auftragsreferenz = $this->messageNumber . $this->readAuftragsReferenz($answer);
        $challenge = $this->readChallenge($answer);
        $challengeData = $this->readChallengeData($answer);

        /** End dialog and clean up fields */
        if( $dialogid != null )
            $this->endDialogMessage();

        return array( 'auftragsreferenz' => $auftragsreferenz, 'dialogid' => $this->dialogId, 'systemid' => $this->systemId, 'challenge' => $challenge, 'challenge_data' => $challengeData );
    }

    private function HBCIEscape($value) {
        return str_replace('@', '?@', $value);
    }

    private function setError($errorMessage = "")
    {
        $this->error = true;
        $this->errorMessage = $errorMessage;
    }

    /**
     * This method is called after a message is sent, so that segement counter is reseted and
     * the message number incremented.
     */
    private function resetMessageData()
    {
        $this->segmentNumber = 2;
        $this->messageNumber++;
    }

    /** This method cleans the hole class, as we would start hole class from beginnging */
    private function resetDialogData()
    {
        $this->segmentNumber = 2;
        $this->messageNumber = 1;
        $this->dialogId = 0;
    }

    private function readAuftragsReferenz($message = null)
    {
        if ($message != null) {
            // First of all, get the full segment
            $pattern = "|HITAN:[0-9]+:[0-9]+(?::[0-9]+)?[^\']*\'|";
            preg_match($pattern, $message, $matches);

            if( empty($matches[0]) )
                return false;

            // Rename
            $hitanSegment = $matches[0];

            // We have only the segment, so split by DE
            $arrDEs = preg_split('|(?<!\?)\+|', $hitanSegment);

            // We should have the reference
            if (count($arrDEs) >= 4) {
                return $arrDEs[3];
            }
        }

        return false;
    }

    private function readChallenge($message = null)
    {
        if ($message != null) {
            $arrSegments = $this->parseSegmentsFromAnswer(utf8_encode($message));
            $hitanSegment = $arrSegments['HITAN'];

            $arrDEs = preg_split('|(?<!\?)\+|', $hitanSegment);

            if (count($arrDEs) >= 5) {
                $challenge = $arrDEs[4];
                // $matches[1] = preg_replace('|\?(.)|', '${1}', $matches[1]);
                return $challenge;
            }
        }

        return false;
    }

    private function readChallengeData($message = null)
    {
        if ($message != null) {
            $pattern = "|HITAN:[0-9]+:[0-9]+(?::[0-9]+)?\+4\+[^\+]{0,256}\+[^\+]{0,35}\+[^\+]*\+(@([1-9][0-9]*)@.*)?|ms";

            preg_match($pattern, $message, $matches);

            if (count($matches) == 3) {
                $binString = substr($matches[1], strlen($matches[2])+2, $matches[2]);

                // Extract challenge data
                $parseBinary = $this->supportedTanMethods[$this->pinTanMethod]['binary_hhduc'];

                if( $parseBinary == true ) {
                    // Get the first 2-bytes for having the length of MIME-Type
                    $mimeLength = base_convert(bin2hex($binString[0]), 2, 10);
                    $mimeLength += base_convert(bin2hex($binString[1]), 16, 10);

                    return substr($binString, 2 + $mimeLength + 2);
                }

                return $binString;
            }
        }

        return '';
    }

    private function readBPD($message = null)
    {
        if ($message != null) {
            // Search HIPINS! If it doesn't exist, we do not support PIN/TAN.

            $pattern = "|HIBPA:[^\+]*\+([0-9]*)|";

            preg_match($pattern, $message, $matches);

            if (count($matches) > 1) {
                if (is_numeric($matches[1])) {
                    if( $this->logLevel > 0 ) {
                        $this->log("BPD: " . $matches[1]);
                    }

                    $this->bpdVersion = $matches[1];
                }
                else {
                    if( $this->logLevel > 0 ) {
                        $this->log("BPD MISSMATCH: " . $matches[1]);
                    }
                }
            }
            else {
                if( $this->logLevel > 0 ) {
                    $this->log("BPD: NULL");
                }
            }

            /** Try to read BPD data */
            $pattern = "|HISALS:[0-9]+:([0-9]):[^\']+\'|";

            preg_match_all($pattern, $message, $matches);

            if (count($matches) > 1) {
                foreach($matches[1] as $HISSAL) {
                    if( $HISSAL > $this->HKSALVersion && in_array($HISSAL, $this->arrSupportedHKSALVersions) ) {
                        $this->HKSALVersion = $HISSAL;
                    }
                }

                if ($this->HKSALVersion > 0) {
                    if( $this->logLevel > 0 ) {
                        $this->log("HISALS/HKSAL: " . $this->HKSALVersion);
                    }
                }
                else {
                    if( $this->logLevel > 0 ) {
                        $this->log("HISALS/HKSAL VERSION NOT IMPLEMENTED");
                    }
                }
            }
            else {
                if( $this->logLevel > 0 ) {
                    $this->log("HISALS/HKSAL: FUNCTION NOT SUPPORTED");
                }
            }

            /////////////////////////////////////////////////////////////////////////////

            $pattern = "|HIKAZS:[0-9]+:([0-9]):[^\']+\'|";

            preg_match_all($pattern, $message, $matches);

            if (count($matches) > 1) {
                foreach($matches[1] as $HIKAZS) {
                    if( $HIKAZS > $this->HKKAZVersion && in_array($HIKAZS, $this->arrSupportedHKKAZVersions) ) {
                        $this->HKKAZVersion = $HIKAZS;
                    }
                }

                if ($this->HKKAZVersion > 0) {
                    if( $this->logLevel > 0 ) {
                        $this->log("HIKAZS/HKKAZ: " . $this->HKKAZVersion);
                    }
                }
                else {
                    if( $this->logLevel > 0 ) {
                        $this->log("HIKAZS/HKKAZ VERSION NOT IMPLEMENTED");
                    }
                }
            }
            else {
                if( $this->logLevel > 0 ) {
                    $this->log("HIKAZS/HKKAZ: FUNCTION NOT SUPPORTED");
                }
            }

            $pattern = "|HITABS:[0-9]+:([0-9]):[^\']+\'|";

            preg_match_all($pattern, $message, $matches);

            if (count($matches) > 1 && !empty($matches[1]) ) {
                foreach($matches[1] as $HITABS) {
                    if( $HITABS > $this->HKTABVersion && in_array($HITABS, $this->arrSupportedHKTABVersions) ) {
                        $this->HKTABVersion = $HITABS;
                    }
                }

                if ($this->HKTABVersion > 0) {
                    if( $this->logLevel > 0 ) {
                        $this->log("HITABS/HKTAB: " . $this->HKTABVersion);
                    }
                }
                else {
                    if( $this->logLevel > 0 ) {
                        $this->log("HITABS/HKTAB VERSION NOT IMPLEMENTED");
                    }
                }
            }
            else {
                if( $this->logLevel > 0 ) {
                    $this->log("HITABS/HKTAB: FUNCTION NOT SUPPORTED");
                }
            }

            // Read HISPS
            $currentVersion = 1;
            $pattern = "|HISPAS:[0-9]+:$currentVersion(?::[0-9]+)?\+[0-9]{0,3}\+[0-3]\+[0-4]\+([JN])|";
            preg_match($pattern, $message, $matchesHISPAS);

            if( !empty($matchesHISPAS) && isset($matchesHISPAS[1]) ) {
                if( $this->logLevel > 0 ) {
                    $this->log("NATIONAL ACCOUNT ALLOWED: " . $matchesHISPAS[1]);
                }

                if( $matchesHISPAS[1] == 'N' ) {
                    $this->nationalAccountAllowed = false;
                }
            }

            // Read the supported TAN methods
            // We always prefer higher values
            $currentVersion = 6;
            while( empty($matches2[2]) && $currentVersion > 0 ) {
                $pattern = "|HITANS:[0-9]+:($currentVersion)(?::[0-9]+)?\+[0-9]+\+[0-3]\+[0-4]\+[JN]:[JN]:[0-9]([^\']+)\'|";
                preg_match($pattern, $message, $matches2);

                $currentVersion--;
            }

            if( !empty($matches2[2]) ) {
                switch( $matches2[1] ) {
                    case 6:
                        $this->HKTANVersion = 6;
                        $pattern = "/(?::(?:(9[0-9][0-9]):[12]:([^:]+):[^:]{0,32}:([^:]{0,10}):([^:]{1,30}):[1-9]{1,2}:[0-9]:([^:]{1,30}):[0-9]{1,4}:[JN]:[0-9]:[JN]:[012]:[02]:([JN]):[JN]:0[012]:([012])(?::[0-9])?)+)/";
                        break;

                    default:
                    case 5:
                        $this->HKTANVersion = 5;
                        $pattern = "/(?::(?:(9[0-9][0-9]):[12]:([^:]+):[^:]{0,32}:([^:]{0,10}):([^:]{1,30}):[1-9]{1,2}:[0-9]:([^:]{1,30}):[0-9]{1,4}:[0-9]?:[JN]:[0-9]:[02]:[JN]:[012]:[02]:([JN]):[JN]:0[012]:([012])(?::[0-9])?)+)/";
                        break;

                    case 4:
                        $this->HKTANVersion = 4;
                        $pattern = "/(?::(?:(9[0-9][0-9]):[12]:([^:]+):[^:]{0,32}:([^:]{0,10}):([^:]{0,30}):[1-9]{1,2}:[0-9]:([^:]{1,30}):[0-9]{1,3}:[0-9]?:[JN]:[0-9]:[02]:[JN]:[JN]:([JN]):[JN]:[JN]:0[012]:([012])(?::[0-9])?)+)/";
                        break;

                    // ToDo: Add version grep
                    case 3:
                        $this->HKTANVersion = 3;
                        $pattern = "/(?::(?:(9[0-9][0-9]):[12]:([^:]+):([^:]{1,30}):[1-9]{1,2}:[0-9]:([^:]{1,30}):[0-9]{1,3}:[0-9]?:[JN]:[0-9]:[02]:[JN]:([JN]):[JN]:0[012]:([02])(?::[0-9])?)+)/";
                        break;

                    case 1:
                        $this->HKTANVersion = 1;
                        $matches2[2] = substr($matches2[2], 2);
                        $pattern = "/(?::(?:(9[0-9][0-9]):[12]:([^:]+):([^:]{1,30}):[1-9]{1,2}:[0-9]:([^:]{1,30}):[0-9]{1,3}:[0-9]?:[JN]:[JN])+)/";
                        break;
                }

                preg_match_all($pattern, $matches2[2], $tanMatches);

                if( $tanMatches && count($tanMatches) > 0 ) {
                    if( $this->logLevel > 0 ) {
                        $this->log("HITANS/HKTAN: " . $this->HKTANVersion);
                    }

                    // Read all allowed parameter into the array
                    for ($i = 0; $i < count($tanMatches[0]); $i++)
                    {
                        $binary_hhduc = false;

                        // Check if we have to look at the data as binary
                        $technical_id = $tanMatches[2][$i];
                        if( strtoupper(substr($technical_id, 0, 2)) == "MS" )
                            $binary_hhduc = true;

                        $this->supportedTanMethods[$tanMatches[1][$i]] = array(
                            'id' => $tanMatches[1][$i],
                            'name' => $tanMatches[4][$i],
                            'version' => $tanMatches[3][$i],
                            'text_to_show' => $tanMatches[5][$i],
                            'binary_hhduc' => $binary_hhduc,
                            'tanname_allowed' => (isset($tanMatches[7])&&$tanMatches[7][$i]==2?true:false)
                        );

                        if( $this->logLevel > 0 ) {
                            $this->log("TAN-VERFAHREN GEFUNDEN: " . $tanMatches[1][$i] . " " . $tanMatches[4][$i]);
                            $this->log("TECHNISCHES IDENTIFIKATIONSVERFAHREN: " . $tanMatches[2][$i]);
                            $this->log("TAN-NAME REQUIRED: " . $tanMatches[7][$i]);
                        }
                    }
                }
                else {
                    $this->setError("Es wird kein TAN Verfahren unterstützt oder es gab einen Fehler in den BPD.");
                }
            }
        }
    }

    private function readUPD($message = null)
    {
        // HIUPA:5:3:4+1479722057+8+0'
        if ($message != null) {
            $pattern = "|HIUPA:[^\+]*\+[^\+]*\+([0-9]*)|";

            preg_match($pattern, $message, $matches);

            if (count($matches) > 1) {
                if (is_numeric($matches[1])) {
                    if( $this->logLevel > 0 ) {
                        $this->log("UPD-VERSION: " . $matches[1]);
                    }

                    $this->updVersion = $matches[1];
                }
                else {
                    if( $this->logLevel > 0 ) {
                        $this->log("UPD MISSMATCH: " . $matches[1]);
                    }
                }
            }
            else {
                if( $this->logLevel > 0 ) {
                    $this->log("UPD-VERSION: NULL");
                }
            }

            // Get Kontoname from Data, and allowed Geschäftsvorfälle
            $pattern = "|HIUPD:[0-9]+:([56])(?::[0-9]+)?\+|";
            preg_match($pattern, $message, $versionMatches);

            if( empty($versionMatches) || count($versionMatches) <= 1  ) {
                // $this->setError("Fehler beim Auswerten der BPD. Version fehlerhaft?");
            }
            else {
                $version = $versionMatches[1];

                if( $version == 6 ) {
                    $pattern = "|HIUPD:[0-9]+:$version(?::[0-9]+)?\+([0]*" . $this->userAccountNumber . "):[^:]*:[^:]*:" . $this->userBlz . "\+[^\+]{0,35}\+[^\+]+\+[0-9]{0,2}\+[^\+]*\+([^\+]{1,27})\+[^\+]{0,27}\+[^\+]{0,30}\+(.*)|";
                }
                else {
                    $pattern = "|HIUPD:[0-9]+:$version(?::[0-9]+)?\+([0]*" . $this->userAccountNumber . "):[^:]*:[^:]*:" . $this->userBlz . "\+[^\+]+\+[0-9]{0,2}\+[^\+]*\+([^\+]{1,27})\+[^\+]{0,27}\+[^\+]{0,30}\+(.*)|";
                }

                preg_match($pattern, $message, $matches);

                if (count($matches) >= 3) {
                    $this->kontoUserName = utf8_encode($matches[2]);

                    // Check if useraccountnumber changed.
                    if( !empty($matches[1]) && $matches[1] !== strval($this->userAccountNumber) ) {
                        $this->log("ACCOUNT-NUMBER CHANGED: " . $this->userAccountNumber . " -> " . $matches[1]);
                        $this->userAccountNumber = $matches[1];
                    }

                    if ($this->logLevel > 0) {
                        $this->log("KONTOINHABER: " . $this->kontoUserName);
                    }

                    // Allowed jobs
                    $allowedJobs = $matches[3];

                    // Check for HKTAB
                    if( preg_match("|HKTAB:[1-9]|", $allowedJobs) ) {
                        $this->allowedHKTAB = true;
                    }
                }
                else {
                    $this->log("KONTO IN BPD NICHT GEFUNDEN");
                    $this->setError("Konto wurde in den Bankparameterdaten nicht gefunden.");
                }
            }
        }
    }

    private function readTANGeneratorList($message = null)
    {
        if ($message != null) {
            $pattern = "|HITAB:[0-9]+:" . $this->HKTABVersion . "(?::[0-9]+)?\+[012]\+([^\']+)+\'|";
            preg_match($pattern, $message, $matches);

            if (count($matches) > 1) {
                $arrGenerators = preg_split('|\+|', $matches[1]);
                $mediumnamePosition = 12;

                // Change position after version 5
                if( $this->HKTABVersion >= 5 ) $mediumnamePosition = 13;

                if( count($arrGenerators) == 0 ) {
                    $this->log("NO TAN MEDIUM SELECTED");
                    exit;
                }

                foreach($arrGenerators as $generator)
                {
                    // Get name and escape from HBCI masking
                    $mediumname = preg_split('/(?<!\?):/', $generator);

                    // Add medium to supported mediums
                    $mediumname[$mediumnamePosition] = str_replace("?:", ":", $mediumname[$mediumnamePosition]);
                    $this->arrTANMedia[] = $mediumname[$mediumnamePosition];

                    $this->supportedTanMethods[$this->pinTanMethod]['mediumname'] = $mediumname[$mediumnamePosition];

                    if ($this->logLevel > 0) {
                        $this->log("TAN MEDIUM: " . $mediumname[$mediumnamePosition]);
                    }
                }

                // Get first entry and select it
                // $this->supportedTanMethods[$this->pinTanMethod]['mediumname'] = $this->arrTANMedia[0];

                if ($this->logLevel > 0) {
                    $this->log("TAN MEDIUM SELECTED: " . $this->supportedTanMethods[$this->pinTanMethod]['mediumname']);
                }
            }
        }
    }

    private function readPINTANParameter($message = null)
    {
        if ($message != null) {
            $pattern = "{HIPINS:[0-9]+:[0-9]+(?::[0-9]+)?\+(1|0)\+(1|0)\+(1|0).*}";

            preg_match($pattern, $message, $matches);

            if (count($matches) > 1) {
                if ( $matches[1] ) {
                    if( $this->logLevel > 0 ) {
                        $this->log("DIALOG-ID: " . $matches[1]);
                    }
                    // ToDo: $this->dialogId = $matches[1];
                }
                else {
                    if( $this->logLevel > 0 ) {
                        $this->log("DIALOG-ID MISSMATCH: " . $matches[1]);
                    }
                }
            }
            else {
                $this->setError("PIN/TAN Methode wird nicht unterstützt.");
            }
        }
    }

    private function readDialogId($message = null)
    {
        if ($message != null) {
            $pattern = "{HNHBK:[0-9]*:[0-9]*\+[0-9]*\+[0-9]*\+([^\+]+)}";

            preg_match($pattern, $message, $matches);

            if (count($matches) > 1) {
                if ( $matches[1] ) {
                    if( $this->logLevel > 0 ) {
                        $this->log("DIALOG-ID: " . $matches[1]);
                    }
                    $this->dialogId = $matches[1];
                }
                else {
                    if( $this->logLevel > 0 ) {
                        $this->log("DIALOG-ID FEHLERHAFT: " . $matches[1]);
                    }
                }
            }
            else {
                if( $this->logLevel > 0 ) {
                    $this->log("DIALOG-ID: NULL");
                }
            }
        }
    }

    private function readSystemId($message = null)
    {
        if ($message != null) {
            $pattern = "{HISYN[^\+]+\+([^:\']{1,30})}";

            preg_match($pattern, $message, $matches);

            if (count($matches) > 1) {
                if( $this->logLevel > 0 ) {
                    $this->log("SYSTEM-ID: " . $matches[1]);
                }
                $this->systemId = $matches[1];
            }
            else {
                if( $this->logLevel > 0 ) {
                    $this->log("SYSTEM-ID: NULL");
                }
            }
        }
    }

    private function readAuthNeeded($message)
    {
        // remove escaped chars
        $message = str_replace("?:", "", $message);

        if ($message != null) {
            // First of all, we need to find part of HIRMS
            $pattern = "/HIRMS.+\+(0030::[^:]+(?::(?:[0-9]+))+).*/";
            preg_match($pattern, $message, $matches);

            // Test if we found HIRMS, otherwise continue
            if( count($matches) > 1 ) {
                return true;
            }
        }

        return false;
    }

    private function readPinTanMethod($message)
    {
        // remove escaped chars
        $message = str_replace("?:", "", $message);

        if ($message != null) {
            // First of all, we need to find part of HIRMS
            $pattern = "/HIRMS.+\+(3920::[^:]+(?::(?:[0-9]+))+).*\'/";
            preg_match($pattern, $message, $matches);

            // Test if we found HIRMS, otherwise continue
            if( !$matches || count($matches) < 1 ) {
                $this->log("ZUGELASSENE TAN-VERFAHREN WURDEN NICHT GEFUNDEN.");
                return;
            }

            $hirmsMessage = $matches[1];

            $pattern = "/:([0-9]+)+/";
            preg_match_all($pattern, $hirmsMessage, $matches);

            for( $i = 0; $i < count($matches[0]); $i++ ) {
                if( $this->pinTanMethod == 999 )
                    $this->pinTanMethod = $matches[1][$i];

                $this->allowedTanMethods[] = $matches[1][$i];
            }
        }
    }

    private function sendMessage($message, $encrypt = true)
    {
        // Encrypt message if needed
        if( $encrypt ) {
            $encryptedData = $this->createHNVSK();
            $encryptedData .= $this->createHNVSD($message);

            $message = $encryptedData;
        }

        // Add message footer because it has to count up
        $message .= $this->createHNHBS();

        // At the very end add the header with segmentnumber 1
        $message = $this->createHNHBK() . $message;

        // Set size of the total message
        $message = $this->setMessageSize($message);

        // Print the current data
        if( $this->logLevel > 0 ) {
            $this->log("CURRENT DIALOG-ID: $this->dialogId");
            $this->log("CURRENT SYSTEM-ID: $this->systemId");
            $this->log("CURRENT PIN/TAN-METHOD: $this->pinTanMethod");
            $this->log("CURRENT BPD: $this->bpdVersion");
            $this->log("CURRENT UPD: $this->updVersion");
        }

        if( $this->logLevel > 4 ) {
            $rawMessage = str_replace($this->userPin, 'XXXXXX', $message);
            $this->log("RAW MESSAGE REQUEST: $rawMessage");
        }

        $_message = base64_encode($message);

        if( $this->logLevel > 5 ) {
            $rawMessage = str_replace($this->userPin, 'XXXXXX', $message);
            $rawMessage = base64_encode($rawMessage);
            $this->log("RAW MESSAGE BASE64 REQUEST: $rawMessage");
        }

        $ch = curl_init($this->hbciServerUri);
        curl_setopt($ch, CURLINFO_HEADER_OUT, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'Content-Type: application/octet-stream',
            'Content-Length: ' . strlen($_message),
            'User-Agent: ' . $this->registrationNumber . ' ' . $this->product_name . $this->product_version
        ));
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $_message);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 100);
        curl_setopt($ch, CURLOPT_TIMEOUT, 600);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, $this->SSL_VERFIYPEER);

        // Execute request for Curl
        $returndata = curl_exec($ch);

        $httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

        if( $httpcode >= 400 )
        {
            $this->log('Server-Error: ' . $httpcode);
            $this->setError('Server-Error: ' . $httpcode);

            $this->log('ANSWER: ' . $returndata);

            return;
        }

        // Check if we had a CURL error
        if( $returndata === false || $httpcode != 200 )
        {
            $this->log('Curl-Fehler: ' . curl_error($ch));
            $this->setError('Curl-Fehler: ' . curl_error($ch));
            return;
        }

        if( $this->logLevel > 5 ) {
            $this->log("RAW MESSAGE BASE64 ANSWER: $returndata");
        }

        $receivedMessage = base64_decode($returndata);

        if( $this->logLevel > 4 ) {
            $this->log("RAW MESSAGE ANSWER: $receivedMessage");
        }

        if( $this->logLevel > 0 ) {
            $this->log("PARSING RETURNED MESSAGE");
        }

        if( !preg_match("|^HNHBK.*|", $receivedMessage) ) {
            $this->log("KEINE ODER UNGÜLTIGE ANTWORT VOM SERVER EMPFANGEN");
            $this->setError("Keine oder ungültige Antwort vom Server empfangen. Bitte überprüfen Sie die Server-URL.");
            return;
        }

        // Try to handle retunring errors
        $this->checkErrorHandler($receivedMessage);

        // prepare class for sending a new message
        $this->resetMessageData();

        // return answer
        return $receivedMessage;
    }

    private function parseSegmentsFromAnswer($answer) {
        $splitParts = preg_split("|(?<!\?)'|sm", $answer);
        $segments = [];

        foreach($splitParts as $segment) {
            // Ignore empty segments
            if( empty($segment) ) continue;

            $key = substr($segment, 0, 5);
            $segments[$key] = $segment;
        }

        return $segments;
    }

    private function parseElementsFromSegment($answer) {
        $splitParts = preg_split("|(?<!\?)\+|sm", $answer);
        return $splitParts;
    }

    private function checkErrorHandler($message)
    {
        if ($message != null) {
            // First of all, get the full HIRMG segment
            $pattern = "/HIRMG[^\']+\'/";
            preg_match($pattern, $message, $matches);

            if( empty($matches) )
                return;

            // Rename
            $hirmgSegment = $matches[0];

            // We have only the segment, so split by DE
            $arrDEs = preg_split('|(?<!\?)\+|', $hirmgSegment);

            // Create the error array
            $arrErrors = array();

            // $arrDEs[0] is header, the rest are messages
            for( $i = 1; $i < count($arrDEs); $i++ )
            {
                $matches = array();
                $DE = $arrDEs[$i];

                $pattern = "/(?:(?:(?:[9][0-9]{3}):[^:]*:([^\'\+]+))+)/";
                preg_match($pattern, $DE, $matches);

                if( count($matches) > 0 ) {
                    $arrErrors[] = $matches[1];
                }
            }

            if( count($arrErrors) > 0 ) {
                $this->log("Server antwortete mit folgender Fehlermeldung: " . implode("; ", $arrErrors));
                $this->setError("Server antwortete mit folgender Fehlermeldung: " . utf8_encode(implode("; ", $arrErrors)) );
            }
        }
        else {
            $this->log("HBCI MESSAGE LEER ODER FEHLERHAFT");
            $this->setError("HBCI Nachricht war leer oder enthielt Fehler.");
        }

        // Read further error messages
        $matches = array();

        // First of all, get the full HIRMS segment
        $pattern = "/HIRMS[^\']+\'/";
        preg_match($pattern, $message, $matches);

        if( empty($matches) )
            return;

        // Rename
        $hirmsSegment = $matches[0];

        // We have only the segment, so split by DE
        $arrDEs = preg_split('|(?<!\?)\+|', $hirmsSegment);

        // Create the error array
        $arrErrors = array();

        // $arrDEs[0] is header, the rest are messages
        for( $i = 1; $i < count($arrDEs); $i++ )
        {
            $DE = $arrDEs[$i];

            // Split by DEG
            $arrDEGs = preg_split('|(?<!\?):|', $DE);

            if( count($arrDEGs) >= 3 && (substr($arrDEGs[0], 0, 1) == 3 || substr($arrDEGs[0], 0 ,1) == 9) ) {
                $arrErrors[] = $arrDEGs[2];
            }
        }

        if( count($arrErrors) > 0 )
        {
            $this->log(" " . implode("; ", $arrErrors));
            $this->errorMessage .= "; " . utf8_encode(implode(" ", $arrErrors));
        }
    }

    private function setMessageSize($message, $fieldLength = 12)
    {
        $len = strlen($message);

        $lLength = strlen($len);
        $replaceStr = "";
        for ($i = 0; $i < ($fieldLength - $lLength); $i++) {
            $replaceStr .= "0";
        }
        $replaceStr .= $len;

        $message = str_replace("000000000000", $replaceStr, $message);

        return $message;
    }

    private function parseMT940Message($startDate, $endDate, $message, $returnType)
    {
        // Build result array
        $messageArray = array();
        $messageString = '';

        if ($message != null)
        {
            $pattern = "/HIKAZ[^\+]+\+@([0-9]+)@(.+)\'(HNSHA|HNHBS)/ims";
            preg_match($pattern, stripslashes($message), $matches);

            if( count($matches) == 0 )
                return false;

            // Get only MT940 part of the answer
            $mt940String = substr($matches[2], 0, $matches[1]);

            $mt940String = utf8_encode($mt940String);

            if( $returnType == 'mt940' ) {
                $messageString .= $mt940String;
            }
            else {
                // Split the string into bookings
                $pattern = "|\r\n-\r\n|";
                $mt940Messages = preg_split($pattern, $mt940String);

                for ($i = 0; $i < count($mt940Messages); $i++) {
                    $classMessage = new Mt940Message();
                    $mt940Message = $classMessage->getTurnovers($mt940Messages[$i]);
                    $messageArray = array_merge($messageArray, $mt940Message);
                }
            }
        }

        // Check if we have more
        $pattern = "/HIRMS[^+]+.*\+3040:[^\+\']*:[^\+\']*:(([^\?\+\']+(\?\+)?)*)(\'|\+)/i";
        preg_match($pattern, $message, $matches);

        if( isset($matches[1]) ) {
            if( $this->logLevel > 0 ) {
                $this->log("REMAINING TURNOVERS. REPEATING GET_TURNOVERS ACTION");
            }

            $newResult = $this->_getAccountTurnovers($startDate, $endDate, $matches[1], null, null, null, $returnType);

            if( $returnType == 'mt940' )
                $messageString .= $newResult;
            else
                $messageArray = array_merge($messageArray, $newResult);
        }

        if( $returnType == 'mt940' )
            return $messageString;
        else
            return $messageArray;
    }

    /*******************************************************************
     * functions for logical segment creation
     ******************************************************************/

    private function anonymMessage()
    {
        if( $this->logLevel > 0 ) {
            $this->log("--- ACTION: START ANONYM DIALOG ---");
        }

        // Set marker, that we are running anonym dialog
        $this->anonymDialogRunning = true;

        $message = $this->createHKIDN();
        $message .= $this->createHKVVB();

        // Send the message through HBCI
        $answer = $this->sendMessage($message, false);

        if( $this->error == false ) {
            // Parse the results
            $this->readDialogId($answer);
        }
        else {
            // Remove error flag, because this message is optional, and continue
            $this->error = false;
            $this->errorMessage = '';
            $this->resetDialogData();
            return;
        }

        if( $this->logLevel > 0 ) {
            $this->log("--- ACTION: END ANONYM DIALOG ---");
        }

        // Send end dialog
        $message = $this->createHKEND();
        $answer = $this->sendMessage($message, false);

        $this->anonymDialogRunning = false;

        $this->resetDialogData();
    }

    private function synchroniseMessage()
    {
        if( $this->logLevel > 0 ) {
            $this->log("--- ACTION: SENDING SYNCHRONISE MESSAGE ---");
        }

        $message =  $this->createHNSHK();
        $message .= $this->createHKIDN();
        $message .= $this->createHKVVB();
        $message .= $this->createHKSYN();
        $message .= $this->createHNSHA();

        // Send the message through HBCI
        $answer = $this->sendMessage($message);

        // Parse the results
        $this->readDialogId($answer);
        $this->readSystemId($answer);
        $this->readBPD($answer);
        $this->readPinTanMethod($answer);

        // Clear dialog
        $this->resetDialogData();

        // $this->endDialogMessage();
        $this->bpdVersion = 0;

        return;
    }

    private function synchroniseMessageSecure($tan = null, $auftragsreferenz = null, $dialogid = null)
    {
        if( $this->logLevel > 0 ) {
            $this->log("--- ACTION: SENDING SYNCHRONISE SECURE MESSAGE ---");
        }

        // Clear dialog
        $this->resetDialogData();

        // Start prepating message
        $message =  $this->createHNSHK();

        if( !empty($tan) && !empty($auftragsreferenz) && !empty($dialogid) ) {
            $this->dialogId = $dialogid;        // Set our dialog ID

            // Regenerate the message number
            $this->messageNumber = substr($auftragsreferenz, 0, 1);
            $auftragsreferenz = substr($auftragsreferenz, 1);

            $message .= $this->createHKTAN(2, $auftragsreferenz, 'HKSYN');
        }
        else {
            $message .= $this->createHKIDN();
            $message .= $this->createHKVVB();

            if( $this->HKTANVersion == 6 )
                $message .= $this->createHKTAN(4, '', 'HKSYN');

            $message .= $this->createHKSYN();
        }

        // Add last message
        $message .= $this->createHNSHA($tan);

        // Send the message through HBCI
        $answer = $this->sendMessage($message);
        $this->readDialogId($answer);

        // Stop here if auth needed
        if( $this->readAuthNeeded($answer) ) {
            $auftragsreferenz = $this->messageNumber . $this->readAuftragsReferenz($answer);
            $challenge = $this->readChallenge($answer);
            $challengeData = $this->readChallengeData($answer);

            $this->authNeeded = true;
            $this->authChallenge = [
                'auftragsreferenz' => $auftragsreferenz,
                'dialogid' => $this->dialogId,
                'systemid' => $this->systemId,
                'challenge' => $challenge,
                'challenge_data' => $challengeData
            ];

            return;
        }

        if( $this->error == false ) {
            // Check if PIN/TAN is supported
            $this->readPINTANParameter($answer);

            // Parse the results
            $this->readDialogId($answer);
            $this->readSystemId($answer);
            $this->readBPD($answer);
            $this->readUPD($answer);
            $this->readPinTanMethod($answer);
        }

        // Make sure after this all data is cleared
        $this->endDialogMessage();
    }

    private function getGeneratorList()
    {
        // Check if we have an error, then don't continue
        if( $this->error == true )
            return;

        if( $this->logLevel > 0 ) {
            $this->log("--- ACTION: GET TAN-GENERATOR ---");
        }

        /** Build the Dialog Init message */
        $message = $this->createHNSHK();
        $message .= $this->createHKTAB();
        $message .= $this->createHNSHA();

        /** Send the message */
        $answer = $this->sendMessage($message);

        // Parse the answer
        $this->readTANGeneratorList($answer);
    }

    private function initDialogMessage($encrypt = true, $segmentKennung = '')
    {
        // Check if we have an error, then don't continue
        if( $this->error == true )
            return;

        if( $this->logLevel > 0 ) {
            $this->log("--- ACTION: INIT NEW DIALOG ---");
        }

        /** Start a new dialog, so clear all old conversation flags */
        $this->resetDialogData();

        // Create message
        $message = $this->createHNSHK();
        $message .= $this->createHKIDN();
        $message .= $this->createHKVVB();

        if( $this->HKTANVersion == 6 )
            $message .= $this->createHKTAN(4, '', $segmentKennung);

        $message .= $this->createHNSHA();

        /** Send the message */
        $answer = $this->sendMessage($message, $encrypt);

        /** Parse results we are searching */
        $this->readDialogId($answer);
        $this->readUPD($answer);

        return $answer;
    }

    private function endDialogMessage($encrypt = true)
    {
        // Check if we have an error, then don't continue
        if( $this->error == true )
            return;

        if( $this->logLevel > 0 ) {
            $this->log("--- ACTION: SENDING \"ENDDIALOG\" MESSAGE ---");
        }

        // Create message
        $message = $this->createHNSHK();
        $message .= $this->createHKEND();
        $message .= $this->createHNSHA();

        /** Sneding the dialog */
        $this->sendMessage($message, $encrypt);

        if( $this->logLevel > 0 ) {
            $this->log("--- DIALOG ENDED ---");
        }
    }

    /*******************************************************************
     * end of functions for logical segment creation
     ******************************************************************/


    /*******************************************************************
     * functions for HBCI segment creation
     ******************************************************************/

    private function createSegmentHeader($type, $segmentVersion = "3")
    {
        // create segment header string
        $segmentHeaderStr = $type . ":" . $this->segmentNumber . ":" . $segmentVersion;

        // increment segment count
        $this->segmentNumber++;

        // return segment str
        return $segmentHeaderStr;
    }

    private function createHKEND()
    {
        // add segment header
        $segmentStr = $this->createSegmentHeader("HKEND", "1");

        // add dialog id
        $segmentStr .= "+" . $this->dialogId;

        // end segment
        $segmentStr .= "'";

        return $segmentStr;
    }

    private function createHKIDN()
    {
        $hkidnStr = $this->createSegmentHeader("HKIDN", "2");

        // add kik
        $hkidnStr .= "+280:" . $this->userBlz;

        if( $this->anonymDialogRunning === true ) {
            // add client id
            $hkidnStr .= "+9999999999";

            // add client system id
            $hkidnStr .= "+0";

            // add client status id
            $hkidnStr .= "+0";
        }
        else {
            // add client id
            $hkidnStr .= "+" . $this->userAccount;

            // add client system id
            $hkidnStr .= "+" . $this->systemId;

            // add client status id
            $hkidnStr .= "+1";
        }

        // end of segment
        $hkidnStr .= "'";

        return $hkidnStr;
    }

    private function createHKSYN()
    {
        // add segment header
        if( $this->hbciVersion == "220" )
            $hksynStr = $this->createSegmentHeader("HKSYN", "2");   // 2 in 3 geändert
        else
            $hksynStr = $this->createSegmentHeader("HKSYN", "3");   // 2 in 3 geändert

        // Neue Kundensystem-ID zurückmelden
        $hksynStr .= "+0";

        // end of segment
        $hksynStr .= "'";

        return $hksynStr;
    }

    private function getKTV() {
        return $this->userAccountNumber . "::280:" . $this->userBlz;
    }

    private function getKTI() {
        if( $this->nationalAccountAllowed == true ) {
            return $this->iban . ":" . $this->bic .":" . $this->userAccountNumber . "::280:" . $this->userBlz;
        }
        else {
            return $this->iban . ":" . $this->bic . "";
        }
    }

    private function createHKKAZ($startDate, $endDate, $aufsetzpunkt)
    {
        /** Version 5 and 6 are identical */
        // add segment header
        $segmentStr = $this->createSegmentHeader("HKKAZ", $this->HKKAZVersion);

        // account and user
        if( $this->HKKAZVersion >= 7 ) {
            $segmentStr .= "+" . $this->getKTI();
        }
        else {
            $segmentStr .= "+" . $this->getKTV();
        }

        // all accounts or only one
        $segmentStr .= "+N";

        // date from
        $segmentStr .= "+" . $startDate;

        // date to
        $segmentStr .= "+". $endDate;

        // Anzahl der Einträge
        $segmentStr .= "+";

        // Aufsetzpunkt
        $segmentStr .= "+" . $aufsetzpunkt;

        // end of segment
        $segmentStr .= "'";

        return $segmentStr;
    }

    private function createHKSAL()
    {
        // add segment header
        $segmentStr = $this->createSegmentHeader("HKSAL", $this->HKSALVersion);

        // account and user
        if( $this->HKSALVersion >= 7 ) {
            $segmentStr .= "+" . $this->getKTI();
        }
        else {  // Version 5 and 6
            $segmentStr .= "+" . $this->getKTV();
        }

        // all accounts or only one
        $segmentStr .= "+N";

        // limit for amount of responding entries
        // not necessary

        // aufsetzunkt
        // not necessary

        // end segment
        $segmentStr .= "'";

        return $segmentStr;
    }

    private function createHKVVB()
    {
        // add segment header
        $hkvvbStr = $this->createSegmentHeader("HKVVB", "3");

        // add bpd method
        $hkvvbStr .= "+" . $this->bpdVersion;

        // add upd method
        $hkvvbStr .= "+" . $this->updVersion;

        // add dialog language
        $hkvvbStr .= "+0";

        // add product name
        $hkvvbStr .= "+" . $this->registrationNumber;

        // add product version
        $hkvvbStr .= "+" . $this->product_name . $this->product_version;

        // end of segment
        $hkvvbStr .= "'";

        return $hkvvbStr;
    }

    private function createHNHBK()
    {
        // Set segmentnumber to one, because it is our header
        $this->segmentNumber = 1;

        // segment header
        $hnhbkStr = $this->createSegmentHeader("HNHBK");

        // segment size ( has to be calculated after the message was created)
        $hnhbkStr .= "+000000000000";

        // hbci version
        $hnhbkStr .= "+" . $this->hbciVersion;

        // dialog id
        $hnhbkStr .= "+" . $this->dialogId;

        // sending message number
        $hnhbkStr .= "+" . $this->messageNumber;

        // end of this segment
        $hnhbkStr .= "'";

        return $hnhbkStr;
    }

    private function createHNHBS()
    {
        // add segment header
        $segmentStr = $this->createSegmentHeader("HNHBS", "1");

        // add message number
        $segmentStr .= "+" . $this->messageNumber;

        // end of segment
        $segmentStr .= "'";

        return $segmentStr;
    }

    private function createHNSHA($tan = null)
    {
        // Get segment Version
        $segmentVersion = "2";

        // add segment header
        $segmentStr = $this->createSegmentHeader("HNSHA", $segmentVersion);

        // add security controll reference
        $segmentStr .= "+1546869659";

        // add validation result
        $segmentStr .= "+";

        // add pin and tan
        $segmentStr .= "+" . $this->userPin;
        if ($tan != null) {
            $segmentStr .= ":" . $tan;
        }

        // end of segment
        $segmentStr .= "'";

        return $segmentStr;
    }

    private function createHNSHK()
    {
        // Get segment Version
        $segmentVersion = "4";

        $hnshkStr = $this->createSegmentHeader("HNSHK", $segmentVersion);

        if( $this->hbciVersion == "300" ) {
            if( $this->pinTanMethod == "999" )
                $hnshkStr .= "+PIN:1";
            else
                $hnshkStr .= "+PIN:2";
        }

        // security function
        $hnshkStr .= "+" . $this->pinTanMethod;

        // security controll reference
        $hnshkStr .= "+1546869659";

        // area of security application
        $hnshkStr .= "+1";

        // role of security provider
        $hnshkStr .= "+1";

        // security identification
        $hnshkStr .= "+1::" . $this->systemId;

        // security reference number
        $hnshkStr .= "+1";

        // security date
        $hnshkStr .= "+1:" . date('Ymd:His');

        // hash algorithmus
        $hnshkStr .= "+1:999:1";

        // signature algorithmus
        $hnshkStr .= "+6:10:16";

        // key name
        $hnshkStr .= "+280:" . $this->userBlz . ":" . $this->userAccount . ":S:0:0";

        // end of this segment
        $hnshkStr .= "'";

        return $hnshkStr;
    }

    private function createHNVSD($data)
    {
        // Get the length of the "encrypted" message
        $messageLength = strlen($data);
        return "HNVSD:999:1+@" . $messageLength . "@" . $data . "'";
    }

    private function createHNVSK()
    {
        // add segment header
        $segmentStr = "HNVSK:998:2";

        if( $this->hbciVersion == "300" ) {
            $segmentStr = "HNVSK:998:3";
            $segmentStr .= "+PIN:1";
        }

        // add security function
        $segmentStr .= "+998";

        // add roll
        $segmentStr .= "+1";

        // security identification
        $segmentStr .= "+1::" . $this->systemId;

        // security date
        $segmentStr .= "+1:" . date('Ymd:His');

        // encryption algorithmus
        $segmentStr .= "+2:2:13:@8@00000000:5:1";

        // key name
        $segmentStr .= "+280:" . $this->userBlz . ":" . $this->userAccount . ":V:0:0";

        // compress function
        $segmentStr .= "+0";

        // end of segment
        $segmentStr .= "'";

        return $segmentStr;
    }

    private function createHKCCM($classTransfer)
    {
        // Check if we have some jobs and if its a multiple job
        if( $classTransfer->getTotalCount() == 1 )
            $multiple = false;
        else
            $multiple = true;

        // Check the class type, so we can differ which segment we will add
        $scheduled = false;
        if( get_class($classTransfer) == "ScheduledCredit" ) {
            $scheduled = true;
        }

        // segment header
        if( $scheduled == true ) {
            if ($multiple == true)
                $segmentStr = $this->createSegmentHeader("HKCME", 1);   // Terminierte Sammelüberweisung
            else
                $segmentStr = $this->createSegmentHeader("HKCSE", 1);   // Terminierte Einzelüberweisung
        }
        else {
            if ($multiple == true)
                $segmentStr = $this->createSegmentHeader("HKCCM", 1);   // Sammelüberweisung
            else
                $segmentStr = $this->createSegmentHeader("HKCCS", 1);   // Einzelüberweisung
        }

        // kti
        $segmentStr .= "+" . $this->getKTI();

        if( $multiple == true ) {
            // Summenfeld
            $segmentStr .= "+" . number_format($classTransfer->getTotalAmount(), 2, ',', '') . ":EUR";

            // Einzelbuchung
            $segmentStr .= "+";
        }

        // SEPA - Descriptor
        $segmentStr .= "+urn?:iso?:std?:iso?:20022?:tech?:xsd?:pain.001.001.03";

        // SEPA pain message
        $sepaMessageString = $classTransfer->getXML($this->kontoUserName, $this->iban, $this->bic);
        $sepaMessageString = preg_replace("/\s{2,}/", " ", $sepaMessageString);
        $sepaMessageString = preg_replace("/(\n|\r)+/", "", $sepaMessageString);
        $sepaMessageString = preg_replace("/> </", "><", $sepaMessageString);

        $segmentStr .= "+@" . strlen($sepaMessageString) .  "@" . $sepaMessageString;

        // end of this segment
        $segmentStr .= "'";

        return $segmentStr;
    }

    private function createHKDSE($classDebit)
    {
        // Check if we have some jobs and if its a multiple job
        if( $classDebit->getTotalCount() == 1 )
            $multiple = false;
        else
            $multiple = true;

        // Check the type
        $debitType = $classDebit->getType();

        if( $debitType == "COR1" ) {
            // segment header
            if ($multiple == true)
                $segmentStr = $this->createSegmentHeader("HKDMC", 1);   // Terminierte Sammel-Lastschrift (COR1)
            else
                $segmentStr = $this->createSegmentHeader("HKDSC", 1);   // Terminierte Einzel-Lastschrift (COR1)
        }
        else if( $debitType == "B2B" ) {
            if ($multiple == true)
                $segmentStr = $this->createSegmentHeader("HKBME", 1);   // Terminierte Sammel-FIRMEN-Lastschrift (B2B)
            else
                $segmentStr = $this->createSegmentHeader("HKBSE", 1);   // Terminierte Einzel-FIRMEN-Lastschrift (B2B)
        }
        else {
            // segment header
            if ($multiple == true)
                $segmentStr = $this->createSegmentHeader("HKDME", 1);   // Terminierte Sammel-Lastschrift (CORE)
            else
                $segmentStr = $this->createSegmentHeader("HKDSE", 1);   // Terminierte Einzel-Lastschrift (CORE)
        }

        // kti
        $segmentStr .= "+" . $this->getKTI();

        if( $multiple == true ) {
            // Summenfeld
            $segmentStr .= "+" . number_format($classDebit->getTotalAmount(), 2, ',', '') . ":EUR";

            // Einzelbuchung gewünscht, O: „Einzelbuchung erlaubt“ (BPD) = „J“, N: sonst
            $segmentStr .= "+";
        }

        // SEPA - Descriptor
        $segmentStr .= "+urn?:iso?:std?:iso?:20022?:tech?:xsd?:pain.008.001.02";

        // SEPA pain message
        $sepaMessageString = $classDebit->getXML($this->kontoUserName, $this->iban, $this->bic);
        $sepaMessageString = preg_replace("/\s{2,}/", " ", $sepaMessageString);
        $sepaMessageString = preg_replace("/(\n|\r)+/", "", $sepaMessageString);
        $sepaMessageString = preg_replace("/> </", "><", $sepaMessageString);

        $segmentStr .= "+@" . strlen($sepaMessageString) .  "@" . $sepaMessageString;

        // end of this segment
        $segmentStr .= "'";

        return $segmentStr;
    }

    private function createHKTAN($prozess, $auftragsreferenz = '', $segmentKennung = '')
    {
        // segment header
        $segmentStr = $this->createSegmentHeader("HKTAN", $this->HKTANVersion);

        // TAN Prozess
        $segmentStr .= "+" . $prozess;

        if( $this->HKTANVersion >= 5 ) {
            // Segmentenkennung
            $segmentStr .= "+" . $segmentKennung;

            // KTI
            $segmentStr .= "+";
        }

        // Auftrags-Hashwert
        $segmentStr .= "+";

        // Auftragsreferenz
        $segmentStr .= "+" . $auftragsreferenz;

        if( $this->HKTANVersion <= 5 ) {
            // TAN Listennummer
            $segmentStr .= "+";
        }

        // Weitere TAN folgt
        if( $prozess == 2 )
            $segmentStr .= "+N";
        else
            $segmentStr .= "+";

        // Read all TAN methods for further processing
        $tanMethods = $this->_getAllowedTanMethods();

        if( $tanMethods && $tanMethods[$this->pinTanMethod]['tanname_allowed'] ) {
            // Auftrag stornieren
            $segmentStr .= "+";

            if( $this->HKTANVersion >= 4 ) {
                // SMS-Abbuchungskonto
                $segmentStr .= "+";
            }

            // Challenge-Klasse
            $segmentStr .= "+";

            // Parameter Challenge-Klasse
            $segmentStr .= "+";

            // Bezeichnung des TAN Mediums
            if( isset($tanMethods[$this->pinTanMethod]['mediumname']) && !empty($tanMethods[$this->pinTanMethod]['mediumname']) ) {
                $tanMediumName = str_replace(":", "?:", $tanMethods[$this->pinTanMethod]['mediumname']);
                $segmentStr .= "+" . $tanMediumName;
            }
            else {
                $segmentStr .= "+" . $tanMethods[$this->pinTanMethod]['name'];
            }
        }

        // end of this segment
        $segmentStr .= "'";

        return $segmentStr;
    }

    private function createHKTAB()
    {
        // segment header
        $segmentStr = $this->createSegmentHeader("HKTAB", $this->HKTABVersion);

        $mediumArt = 1;
        if( $this->HKTABVersion == 1 )  $mediumArt = 2;
        $mediumArt = 0;

        // TAN-Medium-Art - alle
        $segmentStr .= "+" . $mediumArt;

        if( $this->HKTABVersion == 4 ) {
            $segmentStr .= "+A";
        }

        // end of this segment
        $segmentStr .= "'";

        return $segmentStr;
    }

    /*******************************************************************
     * end of functions for segment creation
     ******************************************************************/
}

class Mt940Message
{
    public function getTurnovers($message)
    {
        // Build result array
        $result = array();

        // Build string for temp fields
        $part60 = "";
        $part61 = "";
        $part86 = "";

        // state of parsing
        $state = 0;

        // Ersetze Trennzeichen
        $message = str_replace("@@", "\r\n", $message);

        // Trenne alle Zeilen
        $messageParts = preg_split("/\r\n/", $message);

        // Message counter
        $i = 0;

        if( is_array($messageParts) && isset($messageParts[0]) )
        {
            foreach($messageParts as $part)
            {
                // Increment counter
                $i++;

                if( preg_match("(^:60[F|M]:(.*))", $part, $data) ) {
                    $part60 .= $data[1];
                }

                if ( preg_match("(^:61:(.*))", $part, $data) && ($state == 0 || $state == 20) )
                {
                    if( $state == 20 ) {
                        if( !empty($part61) && !empty($part86) )
                        {
                            $result[] = $this->parseTurnover($part60, $part61, $part86);
                        }

                        $part61 = "";
                        $part86 = "";
                    }

                    $state = 10;
                    $part61 .= $data[1];
                }

                if( $state == 10 && !preg_match("(^:[0-9]+:(.*))", $part, $data))
                {
                    $part61 .= $part;
                }

                if ( preg_match("(^:86:(.*))", $part, $data) && $state == 10 )
                {
                    $state = 20;
                    $part86 .= $data[1];
                }

                if( $state == 20 && !preg_match("(^:[0-9]+.?:(.*))", $part, $data) )
                {
                    $part86 .= $part;
                }

                // Stop message
                if( preg_match("(^:62.*:(.*))", $part, $data) && ($state == 0 || $state == 20) )
                {
                    $state = 0;

                    if( !empty($part61) && !empty($part86) )
                    {
                        $result[] = $this->parseTurnover($part60, $part61, $part86);
                    }

                    $part61 = "";
                    $part86 = "";
                }

                // Check if last message
                if( $i == count($messageParts) && $state > 0 )
                {
                    if( !empty($part61) && !empty($part86) )
                    {
                        $result[] = $this->parseTurnover($part60, $part61, $part86);
                    }
                }
            }

            return $result;
        }
    }

    private function parseTurnover($part60, $part61, $part86)
    {
        preg_match("/^([0-9]{3})(.)/", $part86, $matches);

        // Hole das Trennzeichen
        $t = $matches[2];

        if( $t == '\\' || $t == '^' || $t == '$' || $t == '.' || $t == '|' || $t == '?' || $t == '*' || $t == '(' || $t == ')' || $t == '[' || $t == '{' ) {
            $t = '\\' . $t;
        }

        // Hole Geschäftsvorfallcode
        $geschaeftsvorfall = "";
        preg_match("/^([0-9]{3})($t|\n|\r)?/", $part86, $matches);
        if( isset($matches[1]) )
            $geschaeftsvorfall .= $matches[1];

        // Hole den Buchungstext
        $buchungstext = "";
        preg_match("/" . $t . "00([^$t]+)($t|\n|\r)?/", $part86, $matches);
        if( isset($matches[1]) )
            $buchungstext .= $matches[1];

        // Hole den gesamten Verwendungszweck
        $verwendungszweck = "";

        // Hole Verwendungszweck 20
        preg_match("/" . $t . "20([^$t]+)($t|\n|\r)?/", $part86, $matches);
        if( isset($matches[1]) ) $verwendungszweck .= $matches[1];

        preg_match("/" . $t . "21([^$t]+)($t|\n|\r)?/", $part86, $matches);
        if( isset($matches[1]) ) $verwendungszweck .= $matches[1];

        preg_match("/" . $t . "22([^$t]+)($t|\n|\r)?/", $part86, $matches);
        if( isset($matches[1]) ) $verwendungszweck .= $matches[1];

        preg_match("/" . $t . "23([^$t]+)($t|\n|\r)?/", $part86, $matches);
        if( isset($matches[1]) ) $verwendungszweck .= $matches[1];

        preg_match("/" . $t . "24([^$t]+)($t|\n|\r)?/", $part86, $matches);
        if( isset($matches[1]) ) $verwendungszweck .= $matches[1];

        preg_match("/" . $t . "25([^$t]+)($t|\n|\r)?/", $part86, $matches);
        if( isset($matches[1]) ) $verwendungszweck .= $matches[1];

        preg_match("/" . $t . "26([^$t]+)($t|\n|\r)?/", $part86, $matches);
        if( isset($matches[1]) ) $verwendungszweck .= $matches[1];

        preg_match("/" . $t . "27([^$t]+)($t|\n|\r)?/", $part86, $matches);
        if( isset($matches[1]) ) $verwendungszweck .= $matches[1];

        preg_match("/" . $t . "28([^$t]+)($t|\n|\r)?/", $part86, $matches);
        if( isset($matches[1]) ) $verwendungszweck .= $matches[1];

        preg_match("/" . $t . "29([^$t]+)($t|\n|\r)?/", $part86, $matches);
        if( isset($matches[1]) ) $verwendungszweck .= $matches[1];

        // Get Konto and BLZ
        $bankkennung = "";
        preg_match("/" . $t . "30([^$t]+)($t|\n|\r)?/", $part86, $matches);
        if( isset($matches[1]) )
            $bankkennung .= $matches[1];

        $kontonr = "";
        preg_match("/" . $t . "31([^$t]+)($t|\n|\r)?/", $part86, $matches);
        if( isset($matches[1]) )
            $kontonr .= $matches[1];

        $auftraggeber = "";
        preg_match("/" . $t . "32([^$t]+)($t|\n|\r)?/", $part86, $matches);
        if( isset($matches[1]) )
            $auftraggeber .= $matches[1];

        preg_match("/" . $t . "33([^$t]+)($t|\n|\r)?/", $part86, $matches);
        if( isset($matches[1]) )
            $auftraggeber .= $matches[1];

        // Get the saldo
        preg_match("/([0-9]{2})([0-9]{2})([0-9]{2})([0-9]{2})?([0-9]{2})?(C|D|RC|RD)([A-Za-z])?([0-9]{1,13},[0-9]?[0-9]?)(N\w{3})(\w{1,16})?/", $part61, $matches);

        if( $matches[6]== "D" )
            $matches[8] = "-" . $matches[8];

        $wertstellung = $matches[3] . "." . $matches[2] . ".20". $matches[1];
        if( $matches[5] && $matches[4] && $matches[1] )
            $buchung = $matches[5] . "." . $matches[4] . ".20". $matches[1];
        else
            $buchung = "";

        $betrag = isset($matches[8])?$matches[8]:'';

        // Prüfe Betrag und ergänze zero 0, falls nötig
        $kommaPosition = strpos($betrag, ',');

        if( $kommaPosition == strlen($kommaPosition)-1 ) {
            $betrag = $betrag . "00";
        }
        else   if( $kommaPosition == strlen($kommaPosition)-2 ) {
            $betrag = $betrag . "0";
        }

        // Get Buchungsdatum
        preg_match("/[C|D]([0-9]{2})([0-9]{2})([0-9]{2})(\w{3}).*/", $part60, $matches);

        if( !$buchung )
            $buchung = $matches[3] . "." . $matches[2] . ".20". $matches[1];
        $währung = isset($matches[4])?$matches[4]:'';

        return array(
            'buchung' => $buchung,
            'buchungstext' => $buchungstext,
            'geschaeftsvorfall' => $geschaeftsvorfall,
            'wertstellung' => $wertstellung,
            'betrag' => $betrag . ' ' . $währung,
            'verwendungszweck' => $verwendungszweck,
            'konto' => $kontonr,
            'blz' => $bankkennung,
            'auftraggeber' => $auftraggeber
        );
    }
}

/**
 * SEPA XML CLASS
 */
class PmtInf
{
    public $catPurpose, $datum, $seqType;
    private $positions, $sum;

    public function __construct($catPurpose, $aDatum, $seqType)
    {
        $this->catPurpose = $catPurpose;
        $this->datum = $aDatum;
        $this->seqType = $seqType;
        $this->positions = array();
        $this->sum = 0.00;
    }

    public function add($amount, $name, $iban, $bic=NULL, $aPurp=NULL, $e2eRef=NULL, $purpose=NULL, $mandateRef=NULL, $mandateDate=NULL,
                        $oldMandatRef=NULL, $oldName=NULL, $oldCreditorId=NULL, $oldIban=NULL, $oldBic=NULL)
    {
        $arrAccounting=array();

        $arrAccounting['BETRAG'] = $amount;
        $arrAccounting['NAME'] = $name;
        $arrAccounting['IBAN'] = $iban;
        $arrAccounting['BIC'] = $bic;
        $arrAccounting['PURP'] = $aPurp;
        $arrAccounting['REF'] = $e2eRef;
        $arrAccounting['VERWEND'] = $purpose;
        $arrAccounting['MANDATREF'] = $mandateRef;
        $arrAccounting['MANDATDATE'] = $mandateDate;
        $arrAccounting['OLDMANDATREF'] = $oldMandatRef;
        $arrAccounting['OLDNAME'] = $oldName;
        $arrAccounting['OLDCREDITORID'] = $oldCreditorId;
        $arrAccounting['OLDIBAN'] = $oldIban;
        $arrAccounting['OLDBIC'] = $oldBic;

        $this->positions[] = $arrAccounting;
        $this->sum += $amount;
    }

    public function get($pmtInfId, $type, $auftraggeber, $iban, $bic, $creditorId = null, $chargeBearer = 'SLEV')
    {
        $myLast=$type!='TRF';
        $result="    <PmtInf>\n";
        $myPmtInfId=$pmtInfId;

        if (!empty($this->catPurpose))
            $myPmtInfId.='-'.$this->catPurpose;
        if (!empty($this->seqType))
            $myPmtInfId.='-'.$this->seqType;

        $result.='      <PmtInfId>'.$myPmtInfId."</PmtInfId>\n";
        $result.='      <PmtMtd>'.($myLast?'DD':'TRF')."</PmtMtd>\n";
        $result.="      <BtchBookg>false</BtchBookg>\n";
        $result.='      <NbOfTxs>'.count($this->positions)."</NbOfTxs>\n";
        $result.='      <CtrlSum>'.sprintf('%.2F', $this->sum)."</CtrlSum>\n";
        $result.="      <PmtTpInf>\n";
        $result.="        <SvcLvl>\n";
        $result.="          <Cd>SEPA</Cd>\n";
        $result.="        </SvcLvl>\n";
        if ($myLast) {
            $result.="        <LclInstrm>\n";
            $result.='          <Cd>'.$type."</Cd>\n";
            $result.="        </LclInstrm>\n";
            $result.='        <SeqTp>'.$this->seqType."</SeqTp>\n";
        }
        if (!empty($this->catPurpose)) {
            $result.="        <CtgyPurp>\n";
            $result.='          <Cd>'.$this->catPurpose."</Cd>\n";
            $result.="        </CtgyPurp>\n";
        }
        $result.="      </PmtTpInf>\n";

        // Ausfuehrungsdatum
        $tag=$myLast?'ReqdColltnDt':'ReqdExctnDt';
        $result.='      <'.$tag.'>'.$this->datum.'</'.$tag.">\n";

        // Eigene Daten
        $tag=$myLast?'Cdtr':'Dbtr';
        $result.='      <'.$tag.">\n";
        $result.='        <Nm>'.$auftraggeber."</Nm>\n";
        $result.='      </'.$tag.">\n";
        $tag2=$tag.'Acct';
        $result.='      <'.$tag2.">\n";
        $result.="        <Id>\n";
        $result.='          <IBAN>'.$iban."</IBAN>\n";
        $result.="        </Id>\n";
        $result.='      </'.$tag2.">\n";
        $tag2=$tag.'Agt';
        $result.='      <'.$tag2.">\n";
        $result.="        <FinInstnId>\n";
        if (!empty($bic))
            $result.='          <BIC>'.$bic."</BIC>\n";
        else {
            $result.="          <Othr>\n";
            $result.="            <Id>NOTPROVIDED</Id>\n";
            $result.="          </Othr>\n";
        }
        $result.="        </FinInstnId>\n";
        $result.='      </'.$tag2.">\n";
        $result.="      <ChrgBr>$chargeBearer</ChrgBr>\n";
        if ($myLast) {
            $result.="      <CdtrSchmeId>\n";
            $result.="        <Id>\n";
            $result.="          <PrvtId>\n";
            $result.="            <Othr>\n";
            $result.='              <Id>'.$creditorId."</Id>\n";
            $result.="              <SchmeNm>\n";
            $result.="                <Prtry>SEPA</Prtry>\n";
            $result.="              </SchmeNm>\n";
            $result.="            </Othr>\n";
            $result.="          </PrvtId>\n";
            $result.="        </Id>\n";
            $result.="      </CdtrSchmeId>\n";
        }

        foreach ($this->positions as $position)
        {
            $result.=$myLast?"      <DrctDbtTxInf>\n":"        <CdtTrfTxInf>\n";
            $result.="        <PmtId>\n";
            $result.='          <EndToEndId>'.(empty($position['REF'])?'NOTPROVIDED':$position['REF'])."</EndToEndId>\n";
            $result.="        </PmtId>\n";
            if ($myLast) {
                $result.='        <InstdAmt Ccy="EUR">'.sprintf('%.2F', $position['BETRAG'])."</InstdAmt>\n";
                $result.="        <DrctDbtTx>\n";
                $result.="          <MndtRltdInf>\n";
                $result.='            <MndtId>'.$position['MANDATREF']."</MndtId>\n";
                $result.='            <DtOfSgntr>'.$position['MANDATDATE']."</DtOfSgntr>\n";
                $amendmentinfo=!empty($position['OLDMANDATREF']) || !empty($position['OLDNAME']) ||
                    !empty($position['OLDCREDITORID']) || !empty($position['OLDIBAN']) ||
                    !empty($position['OLDBIC']);
                $result.='            <AmdmntInd>'.($amendmentinfo?'true':'false')."</AmdmntInd>\n";
                if ($amendmentinfo) {
                    $result.="            <AmdmntInfDtls>\n";
                    if (!empty($position['OLDMANDATREF']))
                        $result.='              <OrgnlMndtId>'.$position['OLDMANDATREF']."</OrgnlMndtId>\n";
                    if (!empty($position['OLDNAME']) or !empty($position['OLDCREDITORID'])) {
                        $result.="              <OrgnlCdtrSchmeId>\n";
                        if (!empty($position['OLDNAME']))
                            $result.='                <Nm>'.$position['OLDNAME']."</Nm>\n";
                        if (!empty($position['OLDCREDITORID'])) {
                            $result.="                <Id>\n";
                            $result.="                  <PrvtId>\n";
                            $result.="                    <Othr>\n";
                            $result.='                      <Id>'.$position['OLDCREDITORID']."</Id>\n";
                            $result.="                      <SchmeNm>\n";
                            $result.="                        <Prtry>SEPA</Prtry>\n";
                            $result.="                      </SchmeNm>\n";
                            $result.="                    </Othr>\n";
                            $result.="                  </PrvtId>\n";
                            $result.="                </Id>\n";
                        }
                        $result.="              </OrgnlCdtrSchmeId>\n";
                    }
                    if (!empty($position['OLDIBAN'])) {
                        $result.="              <OrgnlDbtrAcct>\n";
                        $result.="                <Id>\n";
                        $result.='                  <IBAN>'.$position['OLDIBAN']."</IBAN>\n";
                        $result.="                </Id>\n";
                        $result.="              </OrgnlDbtrAcct>\n";
                    }
                    if (!empty($position['OLDBIC'])) {
                        $result.="              <OrgnlDbtrAgt>\n";
                        $result.="                <FinInstnId>\n";
                        $result.="                  <Othr>\n";
                        $result.='                    <Id>'.$position['OLDBIC']."</Id>\n";
                        $result.="                  </Othr>\n";
                        $result.="                </FinInstnId>\n";
                        $result.="              </OrgnlDbtrAgt>\n";
                    }
                    $result.="            </AmdmntInfDtls>\n";
                }
                $result.="          </MndtRltdInf>\n";
                $result.="        </DrctDbtTx>\n";
            }
            else {
                $result.="        <Amt>\n";
                $result.='          <InstdAmt Ccy="EUR">'.sprintf('%.2F', $position['BETRAG'])."</InstdAmt>\n";
                $result.="        </Amt>\n";
            }

            $tag=$myLast?'Dbtr':'Cdtr';
            $tag2=$tag.'Agt';

            if (!empty($position['BIC'])) {
                $result.='        <'.$tag2.">\n";
                $result.="          <FinInstnId>\n";
                $result.='            <BIC>'.$position['BIC']."</BIC>\n";
                $result.="          </FinInstnId>\n";
                $result.='        </'.$tag2.">\n";
            }
            else {
                if ($myLast) {
                    $result.='        <'.$tag2.">\n";
                    $result.="          <FinInstnId>\n";
                    $result.="            <Othr>\n";
                    $result.="              <Id>NOTPROVIDED</Id>\n";
                    $result.="            </Othr>\n";
                    $result.="          </FinInstnId>\n";
                    $result.='        </'.$tag2.">\n";
                }
            }

            $result.='        <'.$tag.">\n";
            $result.='          <Nm>'.$position['NAME']."</Nm>\n";
            $result.='        </'.$tag.">\n";
            $tag2=$tag.'Acct';
            $result.='        <'.$tag2.">\n";
            $result.="          <Id>\n";
            $result.='            <IBAN>'.$position['IBAN']."</IBAN>\n";
            $result.="          </Id>\n";
            $result.='        </'.$tag2.">\n";

            if (!empty($position['PURP'])) {
                $result.="        <Purp>\n";
                $result.='          <Cd>'.$position['PURP']."</Cd>\n";
                $result.="        </Purp>\n";
            }

            if (!empty($position['VERWEND'])) {
                $result.="        <RmtInf>\n";
                $result.='          <Ustrd>'.$position['VERWEND']."</Ustrd>\n";
                $result.="        </RmtInf>\n";
            }

            $result .= $myLast?"      </DrctDbtTxInf>\n":"        </CdtTrfTxInf>\n";
        }

        $result.="    </PmtInf>\n";

        return $result;
    }
}

class SEPAXML
{
    private $EXPIRE_TIMESTAMP = 0;
    private $version, $pmtInf, $totalCount, $totalAmount;
    private $pmtInfId = NULL;

    public function __construct($pmtInfId) {
        $this->version = '1';
        $this->pmtInf = array();
        $this->totalCount = 0;
        $this->totalAmount = 0.00;
        $this->pmtInfId = $pmtInfId;

        // Get the license information
        $license = ioncube_license_properties();
        $this->EXPIRE_TIMESTAMP = $license['expires']['value'];
    }

    private function getPmtInf($datum, $catPurpose, $seqType)
    {
        foreach ($this->pmtInf as $myPmtInf) {
            if ($myPmtInf->datum == $datum and $myPmtInf->catPurpose == $catPurpose and $myPmtInf->seqType == $seqType)
                return $myPmtInf;
        }

        $myPmtInf = new PmtInf($catPurpose, $datum, $seqType);
        $this->pmtInf[] = $myPmtInf;

        return $myPmtInf;
    }

    protected function _add($transactionDate, $amount, $name, $iban, $bic=NULL, $catPurpose=NULL, $aPurp=NULL, $e2eRef=NULL, $purpose=NULL,
                            $seqType=NULL, $mandateRef=NULL, $mandateDate=NULL, $oldMandatRef=NULL, $oldName=NULL, $oldCreditorId=NULL, $oldIban=NULL, $oldBic=NULL)
    {
        // Check the date! We do not allow anything greater then 7 days then our expire date
        $dtTransactionDate = new DateTime($transactionDate);

        if( !$dtTransactionDate || ($this->EXPIRE_TIMESTAMP > 0 && $dtTransactionDate->getTimestamp() > $this->EXPIRE_TIMESTAMP + 7 * 86400) ) {
            throw new Exception('Auftragsdatum ist ungültig.');
        }

        $myPmtInf = $this->getPmtInf($transactionDate, $catPurpose, $seqType);
        $myPmtInf->add($amount, $name, $iban, $bic, $aPurp, $e2eRef, $purpose, $mandateRef, $mandateDate, $oldMandatRef, $oldName, $oldCreditorId, $oldIban, $oldBic);

        $this->totalCount++;
        $this->totalAmount += $amount;
    }

    protected function _getXML($type, $auftraggeber, $iban, $bic, $creditorId = NULL, $chargeBearer = 'SLEV')
    {
        // replace german characters
        $auftraggeber = str_replace("ä", "ae", $auftraggeber);
        $auftraggeber = str_replace("ö", "oe", $auftraggeber);
        $auftraggeber = str_replace("ü", "ue", $auftraggeber);
        $auftraggeber = str_replace("Ä", "Ae", $auftraggeber);
        $auftraggeber = str_replace("Ö", "Oe", $auftraggeber);
        $auftraggeber = str_replace("Ü", "Ue", $auftraggeber);
        $auftraggeber = str_replace("ß", "ss", $auftraggeber);

        // Remove any other illegal char
        $auftraggeber = preg_replace('|[^a-zA-Z0-9\ \/\?\:\(\)\.\,\'\+\-]|', '', $auftraggeber);
        $auftraggeber = strtoupper($auftraggeber);

        $iban = strtoupper($iban);
        $bic = strtoupper($bic);

        // Initialise defaults
        $myLast = $type != 'TRF';
        $pain=$myLast?'pain.008.00'.$this->version.'.02':'pain.001.00'.$this->version.'.03';
        $urn='urn:iso:std:iso:20022:tech:xsd:'.$pain;
        $msgId = date("Y-m-d\TH:i:su", time());
        $creDtTm = date("Y-m-d\TH:i:s", time());

        // Header schreiben
        $result = "<?xml version=\"1.0\" encoding=\"UTF-8\" standalone=\"yes\"?>\n";
        $result .= '<Document xmlns="'.$urn."\"\n";
        $result .= "  xmlns:xsi=\"http://www.w3.org/2001/XMLSchema-instance\"\n";
        $result .= '  xsi:schemaLocation="'.$urn.' '.$pain.".xsd\">\n";
        $result .= $myLast?"  <CstmrDrctDbtInitn>\n":"  <CstmrCdtTrfInitn>\n";

        // Group Header
        $result .= "    <GrpHdr>\n";
        $result .= '      <MsgId>' . $msgId . "</MsgId>\n";
        $result .= '      <CreDtTm>' . $creDtTm . "</CreDtTm>\n";
        $result .= '      <NbOfTxs>' . $this->totalCount . "</NbOfTxs>\n";
        $result .= '      <CtrlSum>' . sprintf('%.2F', $this->totalAmount) . "</CtrlSum>\n";
        $result .= "      <InitgPty>\n";
        $result .= '        <Nm>' . $auftraggeber . "</Nm>\n";
        $result .= "      </InitgPty>\n";
        $result .= "    </GrpHdr>\n";

        // Payment Information(s)
        foreach ($this->pmtInf as $myPmtInf) {
            $result .= $myPmtInf->get(!empty($this->pmtInfId)?$this->pmtInfId:$msgId, $type, $auftraggeber, $iban, $bic, $creditorId, $chargeBearer);
        }

        // Ende
        $result .= $myLast?"  </CstmrDrctDbtInitn>\n":"  </CstmrCdtTrfInitn>\n";
        $result .= "</Document>\n";

        return $result;
    }

    public function getTotalCount() {
        return $this->totalCount;
    }

    public function getTotalAmount() {
        return $this->totalAmount;
    }
}

class BusinessDebitBase extends DebitBase
{
    public function __construct($transactionDate, $creditorId, $kundenRef=NULL)
    {
        parent::__construct($transactionDate, $creditorId, $kundenRef, 'B2B');
    }
}

class DebitBase extends SEPAXML
{
    private $creditorId;
    private $type = "CORE";
    private $transactionDate;

    public function __construct($transactionDate, $creditorId, $type="CORE", $kundenRef=NULL)
    {
        parent::__construct($kundenRef);

        $this->creditorId = $creditorId;
        $this->transactionDate = $transactionDate;

        switch($type) {
            case 'B2B':
                $this->type = "B2B";
                break;

            case 'COR1':
                $this->type = "COR1";
                break;

            default:
                $this->type = "CORE";
        }
    }

    public function add( $amount, $name, $iban, $bic=NULL, $purpose=NULL, $e2eRef=NULL, $mandateRef, $mandateDate, $seqType = 'OOFF' ) {
        $this->_add($this->transactionDate, $amount, $name, $iban, $bic, NULL, NULL, $e2eRef, $purpose, $seqType, $mandateRef, $mandateDate );
    }

    public function getXML($auftraggeber, $iban, $bic)
    {
        return $this->_getXML($this->type, $auftraggeber, $iban, $bic, $this->creditorId);
    }

    public function getType() {
        return $this->type;
    }
}

class CreditBase extends ScheduledCreditBase
{
    public function __construct($kundenRef = NULL, $chargeBearer = 'SLEV')
    {
        parent::__construct('1999-01-01', $kundenRef, $chargeBearer);
    }

    public function add($amount, $name, $iban, $bic=NULL, $purpose=NULL, $e2eRef=NULL) {
        parent::add($amount, $name, $iban, $bic, $purpose, $e2eRef);
    }
}

class ScheduledCreditBase extends SEPAXML
{
    private $transactionDate;
    private $_chargeBearer = 'SLEV';

    public function __construct($transactionDate, $kundenRef = NULL, $chargeBearer = 'SLEV')
    {
        parent::__construct($kundenRef);
        $this->transactionDate = $transactionDate;

        switch($chargeBearer) {
            case 'DEBT':
                $this->_chargeBearer = 'DEBT';
                break;

            case 'CRED':
                $this->_chargeBearer = 'CRED';
                break;

            case 'SHAR':
                $this->_chargeBearer = "SHAR";
                break;
        }
    }

    public function add( $amount, $name, $iban, $bic=NULL, $purpose=NULL, $e2eRef=NULL ) {
        $this->_add($this->transactionDate, $amount, $name, $iban, $bic, NULL, NULL, $e2eRef, $purpose);
    }

    public function getXML($auftraggeber, $iban, $bic)
    {
        return $this->_getXML('TRF', $auftraggeber, $iban, $bic, null, $this->_chargeBearer);
    }
}
