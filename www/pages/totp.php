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

use Xentral\Components\Http\JsonResponse;
use Xentral\Modules\TOTPLogin\TOTPLoginService;

class Totp
{
  /** @var Application */
  private $app;

  /**
   * Totp constructor.
   *
   * @param Application $app
   * @param bool $intern
   */
  public function __construct($app, $intern = false)
  {
    $this->app = $app;

    if($intern) return;

    $app->ActionHandlerInit($this);

    $app->ActionHandler('generate', 'TOTPGenerateSecretJSON');

    $app->ActionHandlerListen($app);
  }

  public function Install()
  {
    $tableName = 'user_totp';
    $this->app->erp->CheckTable($tableName);
    $this->app->erp->CheckColumn('id', 'UNSIGNED INT', $tableName, 'NOT NULL AUTO_INCREMENT');
    $this->app->erp->CheckColumn('user_id', 'INT', $tableName, 'UNSIGNED NOT NULL');
    $this->app->erp->CheckColumn('active', 'TINYINT(1)', $tableName, 'UNSIGNED DEFAULT 0');
    $this->app->erp->CheckColumn('secret', 'VARCHAR(100)', $tableName, 'NOT NULL');
    $this->app->erp->CheckColumn('created_at', 'TIMESTAMP', $tableName, 'DEFAULT NOW()');
    $this->app->erp->CheckColumn('modified_at', 'TIMESTAMP', $tableName);
    $this->app->erp->CheckIndex($tableName, 'user_id', true);

    $this->app->erp->RegisterHook('login_password_check_otp', 'totp', 'TOTPCheckLogin', 1, false, null, 3);
  }

  public function HandleTOTPDisableAjaxRequest(): JsonResponse
  {
    $action = $this->app->Secure->GetPOST('action');

    if($action !== 'disable'){
      return new JsonResponse(['status' => 'error', 'msg' => 'muss POST sein'], 400);
    }

    /** @var TOTPLoginService $totpLoginService */
    $totpLoginService = $this->app->Container->get('TOTPLoginService');

    $userId = $this->app->User->GetID();

    $totpLoginService->disableTotp($userId);

    return new JsonResponse(['status' => 'success']);
  }

  public function HandleTOTPEnableAjaxRequest(): JsonResponse
  {
    $secret = $this->app->Secure->GetPOST('secret');

    if(empty($secret)){
      return new JsonResponse(['status' => 'error', 'msg' => 'Secret Empty'], 400);
    }

    /** @var TOTPLoginService $totpLoginService */
    $totpLoginService = $this->app->Container->get('TOTPLoginService');

    $userId = $this->app->User->GetID();

    $totpLoginService->enableTotp($userId);
    $totpLoginService->setUserSecret($userId, $secret);

    return new JsonResponse(['status' => 'success']);
  }

  /**
   * @param $userID
   * @param $token
   * @param $passwordValid
   *
   * @throws Exception
   */
  public function TOTPCheckLogin($userID, $token, &$passwordValid)
  {
    /** @var TOTPLoginService $totpLoginService */
    $totpLoginService = $this->app->Container->get('TOTPLoginService');

    if(!$totpLoginService->isTOTPEnabled($userID)){
      return;
    }
    $passwordValid = $totpLoginService->isTokenValid($userID, $token);
  }

  public function TOTPGenerateSecretJSON(): JsonResponse
  {
    $cmd = $this->app->Secure->GetGET('cmd');
    if($cmd === 'enable') {
      return $this->HandleTOTPEnableAjaxRequest();
    }
    if($cmd === 'disable') {
      return $this->HandleTOTPDisableAjaxRequest();
    }

    /** @var TOTPLoginService $totpLoginService */
    $totpLoginService = $this->app->Container->get('TOTPLoginService');

    /** @var \Xentral\Components\Token\TOTPTokenManager $tokenManager */
    $tokenManager = $this->app->Container->get('TOTPTokenManager');

    $secret = $tokenManager->generateBase32Secret();

    $label = 'Xentral' . ' | ' . $this->app->erp->GetFirmaName();

    $qr = $totpLoginService->generatePairingQrCode($this->app->User->GetID(), $label, $secret);

    return new JsonResponse(
      [
        'secret' => $secret,
        'qr' => $qr->toHtml(4, 4)
      ]
    );
  }
}
