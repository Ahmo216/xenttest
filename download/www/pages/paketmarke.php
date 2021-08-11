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
class Paketmarke {
  /** @var Application $app */
  var $app;

  /**
   * Paketmarke constructor.
   *
   * @param Application $app
   * @param bool        $intern
   */
  public function __construct($app, $intern = false) {
    //parent::GenPaketmarke($app);
    $this->app=$app;
    if($intern) {
      return;
    }

    $this->app->ActionHandlerInit($this);

    $this->app->ActionHandler("create","PaketmarkeCreatePopup");
    $this->app->ActionHandler("tracking","PaketmarkeTracking");

    $this->app->ActionHandlerListen($app);

  }

  function PaketmarkeTracking()
  {
    $this->app->erp->Headlines('Paketmarken Drucker');

    $this->app->Tpl->Set('PAGE',"Tracking-Nummer: <input type=\"text\" id=\"tracking\"><script type=\"text/javascript\">document.getElementById(\"tracking\").focus(); </script>");
    //$this->app->BuildNavigation=false;
  }

  function PaketmarkeCreatePopup()
  {
    $this->app->erp->Headlines('Paketmarken Drucker');
      $this->app->erp->PaketmarkeDHLEmbedded('PAGE','lieferschein');
  }
}

