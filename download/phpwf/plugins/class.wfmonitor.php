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

class WFMonitor
{


  function __construct(&$app)
  {
    $this->app = &$app;
  }


  function Error($msg)
  {
    $this->ErrorBox($msg);
  }



  function ErrorBox($content)
  {
    $box .="
      <table border=\"1\" width=\"100%\" bgcolor=\"#ffB6C1\">
	<tr><td>phpWebFrame Error: $content</td></tr>
      </table>";

    echo $box;
  }
}
?>
