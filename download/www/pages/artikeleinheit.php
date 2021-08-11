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
include ("_gen/artikeleinheit.php");

class Artikeleinheit extends GenArtikeleinheit {
  var $app;
  
  function __construct(&$app) {
    //parent::GenArtikeleinheit($app);
    $this->app=&$app;

    $this->app->ActionHandlerInit($this);

    $this->app->ActionHandler("create","ArtikeleinheitCreate");
    $this->app->ActionHandler("edit","ArtikeleinheitEdit");
   	$this->app->ActionHandler("list","ArtikeleinheitList");
   	$this->app->ActionHandler("delete","ArtikeleinheitDelete");

    $this->app->ActionHandlerListen($app);
  }


  function ArtikeleinheitCreate()
  {
    $this->ArtikeleinheitMenu();
    parent::ArtikeleinheitCreate();
  }

	function ArtikeleinheitDelete()
	{
		$id = $this->app->Secure->GetGET("id");
		if(is_numeric($id))
		{
			$this->app->DB->Delete("DELETE FROM artikeleinheit WHERE id='$id'");
		}

		$this->ArtikeleinheitList();
	}


  function ArtikeleinheitList()
  {
    $this->ArtikeleinheitMenu();
    parent::ArtikeleinheitList();
  }

  function ArtikeleinheitMenu()
  {
    $id = (int)$this->app->Secure->GetGET("id");
    $this->app->erp->MenuEintrag("index.php?module=artikeleinheit&action=create","Neu");
    if($this->app->Secure->GetGET("action")=="list")
      $this->app->erp->MenuEintrag("index.php?module=artikeleinheit&action=list","&Uuml;bersicht");  
    if($this->app->Secure->GetGET("action")=="list")
    $this->app->erp->MenuEintrag("index.php?module=einstellungen&action=list","Zur&uuml;ck zur &Uuml;bersicht");
    else
    $this->app->erp->MenuEintrag("index.php?module=artikeleinheit&action=list","Zur&uuml;ck zur &Uuml;bersicht");
  }

  function ArtikeleinheitEdit()
  {
    $this->ArtikeleinheitMenu();
    parent::ArtikeleinheitEdit();
  }





}
