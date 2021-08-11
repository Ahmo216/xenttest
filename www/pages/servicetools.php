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

class Servicetools {
  var $app;
  
  function __construct(&$app) {
    $this->app=&$app;
    $this->app->ActionHandlerInit($this);

    $this->app->ActionHandler("list","ServicetoolsList");

    $this->app->DefaultActionHandler("list");

    $this->app->ActionHandlerListen($app);
  }



  function ServicetoolsList()
  {

    $this->app->erp->MenuEintrag("index.php?module=servicetools&action=list","&Uuml;bersicht");

    $this->app->Tpl->Parse('TAB1',"servicetools_list.tpl");
    $this->app->Tpl->Parse('PAGE',"tabview.tpl");
  }




}

?>
