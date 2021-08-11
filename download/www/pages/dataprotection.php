<?php
/*
**** COPYRIGHT & LICENSE NOTICE *** DO NOT REMOVE ****
*
* Xentral (c) Xentral ERP Software GmbH GmbH, Fuggerstrasse 11, D-86150 Augsburg, * Germany 2019 
*
**** END OF COPYRIGHT & LICENSE NOTICE *** DO NOT REMOVE ****
*/
?>
<?php

use Xentral\Components\Http\RedirectResponse;
use Xentral\Modules\SystemConfig\SystemConfigModule;

class Dataprotection
{
  /** @var Application $app */
  protected $app;

  /** @var SystemConfigModule $systemConfigModule */
  protected $systemConfigModule;

  /**
   * Dataprotection constructor.
   *
   * @param Application $app
   * @param bool        $intern
   */
  public function __construct($app, $intern = false)
  {
    $this->app = $app;
    $this->systemConfigModule = $this->app->Container->get('SystemConfigModule');
    if($intern) {
      return;
    }

    $this->app->ActionHandlerInit($this);
    $this->app->ActionHandler('list', 'DataProtectionList');
    $this->app->ActionHandler('services', 'DataServices');
    $this->app->DefaultActionHandler('list');
    $this->app->ActionHandlerListen($app);
  }

  public function DataProtectionList()
  {
    $this->app->erp->Headlines('Datenschutz');

    $this->app->erp->MenuEintrag('index.php?module=dataprotection&action=list', 'Datenschutzerklärung');
    $this->app->erp->MenuEintrag('index.php?module=dataprotection&action=services', 'Dienste');
    $this->app->Tpl->Parse('PAGE', 'dataprotection_list.tpl');
  }

  public function HandleSaveRequest(): RedirectResponse
  {
    $this->systemConfigModule->setValue(
      'dataprotection',
      'googleanalytics',
      (string)(int)!empty($this->app->Secure->GetPOST('dataprotection_googleanalytics'))
    );
    $this->systemConfigModule->setValue(
      'dataprotection',
      'improvement',
      (string)(int)!empty($this->app->Secure->GetPOST('dataprotection_improvement'))
    );
    $this->systemConfigModule->setValue(
      'dataprotection',
      'hubspot',
      (string)(int)!empty($this->app->Secure->GetPOST('dataprotection_hubspot'))
    );
    $this->systemConfigModule->setValue(
      'dataprotection',
      'zendesk',
      (string)(int)!empty($this->app->Secure->GetPOST('dataprotection_zendesk'))
    );
    $this->systemConfigModule->setValue(
      'dataprotection',
      'userlane',
      (string)(int)!empty($this->app->Secure->GetPOST('dataprotection_userlane'))
    );

    return RedirectResponse::createFromUrl('index.php?module=dataprotection&action=services');
  }

  public function DataServices()
  {
      $this->app->erp->Headlines('Datenschutz');
      if($this->app->Secure->GetPOST('save')) {
          return $this->HandleSaveRequest();
      }
      $isDemo = !empty(erpAPI::Ioncube_Property('testlizenz'))
        && !empty(erpAPI::Ioncube_Property('iscloud'));
      $google = $this->isGoogleAnalyticsActive();
      $improvement = $this->isImprovementProgramActive();
      $hubspot = $this->isHubspotActive();
      $zendesk = $this->isZenDeskActive();
      $userlane = $this->isUserlaneActive();
      $this->systemConfigModule->setValue('dataprotection', 'googleanalytics', (string)(int)$google);
      $this->systemConfigModule->setValue('dataprotection', 'improvement', (string)(int)$improvement);
      $this->systemConfigModule->setValue('dataprotection', 'hubspot', (string)(int)$hubspot);
      $this->systemConfigModule->setValue('dataprotection', 'zendesk', (string)(int)$zendesk);
      $this->systemConfigModule->setValue('dataprotection', 'userlane', (string)(int)$userlane);
      $this->app->Tpl->Set('DATAPROTECTION_GOOGLEANALYTICS', $google ? ' checked="checked" ' : '');
      $this->app->Tpl->Set('DATAPROTECTION_IMPROVEMENT', $improvement ? ' checked="checked" ' : '');
      $this->app->Tpl->Set('DATAPROTECTION_HUBSPOT', $hubspot ? ' checked="checked" ' : '');
      $this->app->Tpl->Set('DATAPROTECTION_ZENDESK', $zendesk ? ' checked="checked" ' : '');
      $this->app->Tpl->Set('DATAPROTECTION_USERLANE', $userlane ? ' checked="checked" ' : '');
      $this->app->Tpl->Set('DISABLED_HUBSPOT', !$isDemo ? ' disabled="disabled" ' : '');

      $this->app->erp->MenuEintrag('index.php?module=dataprotection&action=list', 'Datenschutzerklärung');
      $this->app->erp->MenuEintrag('index.php?module=dataprotection&action=services', 'Dienste');
      $this->app->Tpl->Parse('PAGE', 'dataprotection_services.tpl');
  }

  /**
   * @return bool
   */
  public function isGoogleAnalyticsActive(): bool
  {
    $google = $this->systemConfigModule->tryGetValue('dataprotection', 'googleanalytics');
    if($google === null) {
      $this->systemConfigModule->setValue('dataprotection', 'googleanalytics', '1');

      return true;
    }

    return $google === '1';
  }

  /**
   * @return bool
   */
  public function isUserlaneActive(): bool
  {
    $userlane = $this->systemConfigModule->tryGetValue('dataprotection', 'userlane');
    if($userlane === null) {
      $this->systemConfigModule->setValue('dataprotection', 'userlane', '1');

      return true;
    }

    return $userlane === '1';
  }

  /**
   * @return bool
   */
  public function isZenDeskActive(): bool
  {
    $zendesk = $this->systemConfigModule->tryGetValue('dataprotection', 'zendesk');
    if ($zendesk === null) {
      $this->systemConfigModule->setValue('dataprotection', 'zendesk', '1');

      return true;
    }

    return $zendesk === '1';
  }

  /**
   * @return bool
   */
  public function isImprovementProgramActive(): bool
  {
    $improvement = $this->systemConfigModule->tryGetValue('dataprotection', 'improvement');
    if($improvement === null) {
      $this->systemConfigModule->setValue('dataprotection', 'improvement', '1');

      return true;
    }

    return $improvement === '1';
  }

  /**
   * @return bool
   */
  public function isHubspotActive(): bool
  {
    $hubspot = $this->systemConfigModule->tryGetValue('dataprotection', 'hubspot');

    $isDemo = !empty(erpAPI::Ioncube_Property('testlizenz'))
      && !empty(erpAPI::Ioncube_Property('iscloud'));
    if($hubspot === null) {
      return $isDemo;
    }

    return $hubspot === '1';
  }
}
