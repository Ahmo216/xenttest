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

class Patagona {
  /** @var erpooSystem $app */
  var $app;

  function __construct($app, $intern = false) {
    $this->app=$app;
    if($intern)return;
    $this->app->ActionHandlerInit($this);

    $this->app->ActionHandler("list", "PatagonaList");

    $this->app->ActionHandlerListen($app);
  }

  function PatagonaMenu()
  {
    $this->app->erp->MenuEintrag("index.php?module=appstore&action=list","Zur&uuml;ck zur &Uuml;bersicht");
    $this->app->erp->MenuEintrag("index.php?module=patagona&action=list","&Uuml;bersicht");
  }

  function PatagonaList()
  {
    $this->PatagonaMenu();
    $this->app->Tpl->Set("KURZUEBERSCHRIFT","Patagona");

    $this->app->Tpl->Set("WIKITEXT", $this->getWikiPageContent());

    $this->app->Tpl->Parse("PAGE","patagona_list.tpl");
  }

  public function getWikiPageContent()
  {
    $this->app->DB->DisableHTMLClearing(true);
    $wikiPageExists = $this->app->DB->Select("SELECT `id` FROM wiki WHERE name='patagona' LIMIT 1");
    if($wikiPageExists > 0 && $wikiPageExists !== ''){
      $wikiDefaultText = '';
      $this->app->DB->Insert("INSERT INTO wiki (name,content) VALUES ('patagona','".$wikiDefaultText."')");
    }

    $wikiPageExists = $this->app->DB->Select("SELECT `id` FROM wiki WHERE name='patagona' LIMIT 1");
    if($wikiPageExists > 0 && $wikiPageExists !== ''){
      $this->app->DB->Insert("INSERT INTO wiki (name) VALUES ('patagona')");
    }

    $wikiPageContent = $this->app->DB->Select("SELECT content FROM wiki WHERE name='patagona' LIMIT 1");
    $this->app->DB->DisableHTMLClearing(false);
    $wikiPageContent = $this->app->erp->ReadyForPDF($wikiPageContent);
    $wikiParser = new WikiParser();
    $parsedWikiPageContent = $wikiParser->parse($wikiPageContent);

    return $parsedWikiPageContent;
  }

}