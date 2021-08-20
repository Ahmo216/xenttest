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

class appgeneric
{
    protected $app;

    public function __construct(&$app)
    {
        $this->app = $app;
    }

    protected function InitTemplate(array $nameValueParis)
    {
        foreach ($nameValueParis as $name => $value) {
            $this->app->Tpl->Set($name, $value);
        }
    }

    protected function InitTemplateAuftragValues(string $query, array $sqlNamesToTemplateNames)
    {
        $values = $this->app->DB->SelectArr($query);
        $queryResults = is_array($values) && count($values) === 1;
        foreach ($sqlNamesToTemplateNames as $sqlName => $templateName) {
            $value = $queryResults ? ($values[0][$sqlName] ?? null) : null;
            $this->app->Tpl->Set($templateName, $value);
        }
    }
}
