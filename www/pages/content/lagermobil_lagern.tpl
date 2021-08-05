<style type="text/css">
    /*#lagermobiltabelle {
        max-width:500px;
        width:100%;
    }
    #lagermobiltabelle input[type="button"],#lagermobiltabelle input[type="submit"]
    {
        width:100%;
        border-radius: 7px;
        font-size:2em;
        min-height:2em;
        cursor:pointer;

    }
    @media only screen and (min-width: 600px) {
        #lagermobiltabelle {
            font-size: 1.5em;
            width: 90vw;
            max-width:1000px;
        }
    }*/
</style>
<div id="tabs" class="mobile-ui">
    <ul>
        <li><a href="#tabs-1"></a></li>
    </ul>
<!-- ende gehort zu tabview -->

    <script>
    function artselect(el)
    {
      $('#artikel').val($(el).parent().parent().find('span').first().html());
      $('#menge').val($(el).parent().parent().find('span.spanmenge').html());
      $('#menge').first().focus();
    }
    $(document).on('ready',function(){
       $('#tabs-1 form').on('submit',function(){
           $('#tabs').loadingOverlay('show');
       });
    });
    </script>
<!-- erstes tab -->
    <div id="tabs-1">

        <div class="row">
            <div class="col-md-6 col-sm-12">
                [MESSAGE]
            </div>
        </div>

        <form method="POST" action="index.php?module=lagermobil&action=[ACTION]" class="form form-horizontal">
            <div class="form-group form-group-lg">
                <div class="col-md-6 col-sm-12">
                    <a class="btn btn-block btn-lg btn-secondary" href="index.php?module=lagermobil&action=list">
                        zurück zur Übersicht
                    </a>
                </div>
            </div>
            [VORLAGERVON]
            <div class="form-group form-group-lg">
                <label for="lager_platz_von" class="col-md-1 col-sm-2 control-label">{|Lagerplatz von|}:</label>
                <div class="col-md-5 col-sm-10">
                    <div class="input-autocomplete">
                        <input type="text" name="lager_platz_von" id="lager_platz_von" class="form-control" value="[LAGER_PLATZ_VON]" />
                    </div>
                </div>
            </div>
            [NACHLAGERVON]
            <div class="row">
                <div class="col-md-offset-1 col-md-5 col-sm-offset-2 col-sm-10">
                    <p>[ARTIKELLAGERPLATZ]</p>
                </div>
            </div>
            [VORLAGERZU]
            <div class="form-group form-group-lg">
                <label for="lager_platz_von" class="col-md-1 col-sm-2 control-label">{|Lagerplatz zu|}:</label>
                <div class="col-md-5 col-sm-10">
                    <div class="input-autocomplete">
                        <input type="text" name="lager_platz_zu" id="lager_platz_zu" class="form-control" value="[LAGER_PLATZ_ZU]" />
                    </div>
                </div>
            </div>
            [NACHLAGERZU]
            [VORARTIKEL]
            <div class="form-group form-group-lg">
                <label for="artikel" class="col-md-1 col-sm-2 control-label">{|Artikel|}:</label>
                <div class="col-md-5 col-sm-10">
                    <div class="input-autocomplete">
                        <input type="text" name="artikel" id="artikel" class="form-control" value="[ARTIKEL]" />
                    </div>
                </div>
            </div>
            [NACHARTIKEL]
            <div class="row">
                <div class="col-md-offset-1 col-md-5 col-sm-offset-2 col-sm-10">
                    <p>[ARTIKELMHD]</p>
                </div>
            </div>
            <div class="row">
                <div class="col-md-offset-1 col-md-5 col-sm-offset-2 col-sm-10">
                    <p>[ARTIKELSERIENNUMMERN]</p>
                </div>
            </div>
            [VORMENGE]
            <div class="form-group form-group-lg">
                <label for="artikel" class="col-md-1 col-sm-2 control-label">{|Menge|}:</label>
                <div class="col-md-5 col-sm-10">
                    <input type="text" id="menge" name="menge" class="form-control" value="[MENGE]" />
                </div>
            </div>
            [NACHMENGE]
            <div class="form-group form-group-lg">
                <div class="col-sm-offset-2 col-sm-10 col-md-offset-1 col-md-5">
                    <input type="submit" name="speichern" id="speichern" class="btn btn-primary btn-block btn-lg" value="[SPEICHERNTEXT]" />
                </div>
            </div>

            <div class="row">
                <div class="col-md-6 col-sm-12">
                    [WEITEREINFOSLAGER]
                </div>
            </div>
        </form>
        [TAB1]
    </div>
<!-- tab view schließen -->
</div>

