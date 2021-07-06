<?php

if (empty($app->DB)) {
    $app->DB = new DB($conf->WFdbhost, $conf->WFdbname, $conf->WFdbuser, $conf->WFdbpass, null, $conf->WFdbport);
}
if (class_exists('erpAPICustom')) {
    $erp = new erpAPICustom($app);
} else {
    $erp = new erpAPI($app);
}
if (empty($app->erp)) {
    $app->erp = $erp;
}
if (empty($app->conf)) {
    $app->Conf = $conf;
}
if (empty($app->remote)) {
    $app->remote = new Remote($app);
}
if(!$app->erp->ModulVorhanden('shopimporter_spryker')) {
  return;
}

$app->DB->Update("UPDATE `prozessstarter` SET `mutexcounter` = `mutexcounter` + 1 WHERE `mutex` = 1 AND `parameter` = 'spryker_task' AND `aktiv` = 1");

if ($app->DB->Select("SELECT `mutex` FROM `prozessstarter` WHERE `parameter` = 'spryker_task' LIMIT 1") == 1) {
    return;
}

$shops = $app->DB->SelectArr("SELECT * FROM `shopexport` WHERE `modulename` = 'shopimporter_spryker' AND `aktiv`='1'");
if(empty($shops)) {
  return;
}
foreach ($shops as $shop) {
    if(empty($shop['einstellungen_json'])) {
      continue;
    }
    $customSettings = [];
    if (!empty($shop['einstellungen_json'])) {
        $customSettings = json_decode($shop['einstellungen_json'], true);
        $customSettings = !empty($customSettings['felder'])?$customSettings['felder']:[];
    }

    $ftpurl = empty($customSettings['ftpurl'])? null: trim($customSettings['ftpurl']);
    if(empty($ftpurl)) {
        continue;
    }
    $app->DB->Update(
        "UPDATE `prozessstarter` 
        SET `letzteausfuerhung` = NOW(), `mutex` = 1, `mutexcounter` = 0 
        WHERE `parameter` = 'spryker_task'"
    );
    if (!empty($ftpurl)) {
        $ftpuser = trim($customSettings['ftpuser']);
        $ftppass = $customSettings['ftppass'];
        $ftppassive = $customSettings['ftppassive'];
        if (empty($ftpuser) || empty($ftppass)) {
            $app->erp->LogFile('FTP Zugangsdaten sind nicht ausreichend für:' . $ftpurl);
            continue;
        }
    }

    /** @var Shopimporter_Spryker $importer */
    $importer = $app->loadModule('shopimporter_spryker');
    if($importer === null) {
      break;
    }
    $importer->getKonfig($shop['id'],'');
    $importer->projectId = $shop['projekt'];

    $filesToSend = [];

    $query = sprintf("SELECT COUNT(`id`) FROM `spryker_data` WHERE `shop_id` = %d AND `type`='product' LIMIT 1",
        $shop['id']);
    $productsNeedToSync = (int)$app->DB->Select($query) > 0;
    if ($productsNeedToSync) {
        if(!empty($shop['artikelexport'])){
            $filesToSend = array_merge($importer->createProductDataFiles(), $filesToSend);
        }
        if(!empty($shop['lagerexport'])){
            $filesToSend = array_merge($importer->createProductStockFile(), $filesToSend);
        }
    }

    if(!empty($shop['auftragabgleich'])){
        $query = sprintf("SELECT COUNT(`id`) FROM `spryker_data` WHERE `shop_id` = %d AND (`type`='export' OR `type`='ship' OR `type`='return')",
            $shop['id']);
        $ordersNeedToSync = !empty($app->DB->Select($query));
        if ($ordersNeedToSync) {
            $fileInfo = $importer->createOrderStatusFiles();
            $filesToSend = array_merge($filesToSend, $fileInfo);
        }
    }

    if (empty($filesToSend)) {
        continue;
    }

    $importer->ftpWrapper->connect();
    if(!$importer->ftpWrapper->isValid()){
        $app->erp->LogFile('Cronjob Spryker konnte keine FTP Verbindung herstellen');
        continue;
    }

    foreach ($filesToSend as $fileName => $filePath) {
        $success = $importer->ftpWrapper->sendFile($filePath, $importer->getOutgoingFolder().'/'.$fileName);
        if (!$success) {
            $app->erp->LogFile('Cronjob Spryker konnte Datei nicht per FTP übertragen');
        }
        unlink($filePath);
    }

    $importer->ftpWrapper->disconnect();

    $app->DB->Update("UPDATE `prozessstarter` SET `letzteausfuerhung` = NOW(), `mutex` = 1, `mutexcounter` = 0 WHERE `parameter` = 'spryker_task'");

    $query = sprintf("DELETE FROM `spryker_data`
            WHERE `shop_id` = %d AND type != 'product' AND `time_of_validity` < DATE_SUB(NOW(), INTERVAL 24 HOUR)",
        $shop['id']);
    $app->DB->Delete($query);
}

$app->DB->Update("UPDATE `prozessstarter` SET `letzteausfuerhung` = NOW(), `mutex` = 0, `mutexcounter` = 0 WHERE `parameter` = 'spryker_task'");
