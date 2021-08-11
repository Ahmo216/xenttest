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

class Tableau {
  /** @var erpooSystem $app */
  var $app;

  function __construct($app, $intern = false) {
    $this->app=$app;
    if($intern)return;
    $this->app->ActionHandlerInit($this);

    $this->app->ActionHandler("list", "TableauList");

    $this->app->ActionHandlerListen($app);
  }

  function TableauMenu()
  {
    $this->app->erp->MenuEintrag("index.php?module=tableau&action=list","Zur&uuml;ck zur &Uuml;bersicht");
    $this->app->erp->MenuEintrag("index.php?module=tableau&action=list","&Uuml;bersicht");
  }

  function TableauList()
  {
    $this->TableauMenu();
    $this->app->Tpl->Set("KURZUEBERSCHRIFT","Tableau");

    $this->app->Tpl->Set("WIKITEXT", $this->getWikiPageContent());

    $this->app->Tpl->Parse("PAGE","tableau_list.tpl");
  }

  public function getWikiPageContent()
  {
    $this->app->DB->DisableHTMLClearing(true);
    $wikiPageExists = $this->app->DB->Select("SELECT `id` FROM wiki WHERE name='tableau' LIMIT 1");
    if($wikiPageExists > 0 && $wikiPageExists !== ''){
      $wikiDefaultText = '';
      $this->app->DB->Insert("INSERT INTO wiki (name,content) VALUES ('tableau','".$wikiDefaultText."')");
    }

    $wikiPageExists = $this->app->DB->Select("SELECT `id` FROM wiki WHERE name='tableau' LIMIT 1");
    if($wikiPageExists > 0 && $wikiPageExists !== ''){
      $this->app->DB->Insert("INSERT INTO wiki (name) VALUES ('tableau')");
    }

    $wikiPageContent = $this->app->DB->Select("SELECT content FROM wiki WHERE name='tableau' LIMIT 1");
    $this->app->DB->DisableHTMLClearing(false);
    $wikiPageContent = $this->app->erp->ReadyForPDF($wikiPageContent);
    $wikiParser = new WikiParser();
    $parsedWikiPageContent = $wikiParser->parse($wikiPageContent);

    return $parsedWikiPageContent;
  }

}
