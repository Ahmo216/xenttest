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

class Sellerlogic {
  /** @var erpooSystem $app */
  var $app;

  function __construct($app, $intern = false) {
    $this->app=$app;
    if($intern)return;
    $this->app->ActionHandlerInit($this);

    $this->app->ActionHandler("list", "SellerlogicList");

    $this->app->ActionHandlerListen($app);
  }

  function SellerlogicMenu()
  {
    $this->app->erp->MenuEintrag("index.php?module=appstore&action=list","Zur&uuml;ck zur &Uuml;bersicht");
    $this->app->erp->MenuEintrag("index.php?module=sellerlogic&action=list","&Uuml;bersicht");
  }

  function SellerlogicList()
  {
    $this->SellerlogicMenu();
    $this->app->Tpl->Set("KURZUEBERSCHRIFT","Sellerlogic");

    $this->app->Tpl->Set("WIKITEXT", $this->getWikiPageContent());

    $this->app->Tpl->Parse("PAGE","sellerlogic_list.tpl");
  }

  public function getWikiPageContent()
  {
    $this->app->DB->DisableHTMLClearing(true);
    $wikiPageExists = $this->app->DB->Select("SELECT `id` FROM wiki WHERE name='sellerlogic' LIMIT 1");
    if($wikiPageExists > 0 && $wikiPageExists !== ''){
      $wikiDefaultText = '';
      $this->app->DB->Insert("INSERT INTO wiki (name,content) VALUES ('sellerlogic','".$wikiDefaultText."')");
    }

    $wikiPageExists = $this->app->DB->Select("SELECT `id` FROM wiki WHERE name='sellerlogic' LIMIT 1");
    if($wikiPageExists > 0 && $wikiPageExists !== ''){
      $this->app->DB->Insert("INSERT INTO wiki (name) VALUES ('sellerlogic')");
    }

    $wikiPageContent = $this->app->DB->Select("SELECT content FROM wiki WHERE name='sellerlogic' LIMIT 1");
    $this->app->DB->DisableHTMLClearing(false);
    $wikiPageContent = $this->app->erp->ReadyForPDF($wikiPageContent);
    $wikiParser = new WikiParser();
    $parsedWikiPageContent = $wikiParser->parse($wikiPageContent);

    return $parsedWikiPageContent;
  }

}
