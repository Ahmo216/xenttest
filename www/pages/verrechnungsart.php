<?php
/*
**** COPYRIGHT & LICENSE NOTICE *** DO NOT REMOVE ****
* 
* Xentral (c) Xentral ERP Sorftware GmbH, Fuggerstrasse 11, D-86150 Augsburg, * Germany 2019
*
* This file is licensed under the Embedded Projects General Public License *Version 3.1. 
*
* You should have received a copy of this license from your vendor and/or *along with this file; If not, please visit www.wawision.de/Lizenzhinweis 
* to obtain the text of the corresponding license version.  
*
**** END OF COPYRIGHT & LICENSE NOTICE *** DO NOT REMOVE ****
*/
?>
<?php
if(!class_exists('Verrechnungsart'))
{
  class Verrechnungsart
  {
    function __construct(&$app)
    {
      $this->app=&$app; 

      $this->app->ActionHandlerInit($this);

      $this->app->ActionHandler("list","VerrechnungsartList");
      $this->app->DefaultActionHandler("list");

      $this->app->ActionHandlerListen($app);
    }    
    function VerrechnungsartList()
    {
      $this->app->Tpl->Set('VERS','Enterprise');
      $this->app->Tpl->Set('MODUL','Enterprise');
      $this->app->Tpl->Parse('PAGE', "only_version.tpl");
    }
  }
}
?>
