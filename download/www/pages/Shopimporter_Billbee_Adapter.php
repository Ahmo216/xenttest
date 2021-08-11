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

/**
 * Class Shopimporter_Billbee_Adapter
 */
class Shopimporter_Billbee_Adapter {

  protected $cURL;

  /**
   * Shopimporter_Billbee_Adapter constructor.
   *
   * @param $user
   * @param $pass
   */
  public function __construct($user, $pass) {
    $this->cURL = curl_init();
    curl_setopt($this->cURL, CURLOPT_HTTPHEADER, array('Content-Type: application/json', 'charset=utf-8'));
    curl_setopt($this->cURL, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($this->cURL, CURLOPT_FOLLOWLOCATION, false);
    curl_setopt($this->cURL, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
    curl_setopt($this->cURL, CURLOPT_USERPWD, $user.':'.$pass);
  }

  /**
   * @param        $endpoint
   * @param string $params
   * @param null   $data
   * @param string $type
   *
   * @return mixed
   */
  public function call($endpoint, $params = '',$data=null, $type = 'GET') {
    $baseUrl = 'https://app01.billbee.de/api/v1/';
    if($params !== ''){
      $params = '?'.$params;
    }
    $uri = $baseUrl.$endpoint.$params;
    $headers = array(
      'Accept: application/json',
      'Content-Type: application/json',
      'X-Billbee-Api-Key: FAE82830-B41C-4C41-9742-F8050B173110');

    curl_setopt($this->cURL, CURLOPT_URL, $uri);
    curl_setopt($this->cURL, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($this->cURL, CURLOPT_CUSTOMREQUEST, $type);

    if($data !== null){
      $jsonData = json_encode($data);
      curl_setopt($this->cURL, CURLOPT_POSTFIELDS, $jsonData);
    }
    return json_decode(curl_exec($this->cURL), true);
  }

}
