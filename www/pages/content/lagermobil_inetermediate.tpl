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
  $('#artikel').first().val($(el).html());
  $('#menge').val($(el).parent().parent().find('span.spanmenge').html());
  $('#menge').first().focus();
}
</script>
    <!-- erstes tab -->
    <div id="tabs-1">

        <div class="row">
            <div class="col-md-6 col-sm-12">
                [MESSAGE]
            </div>
        </div>
        <form method="POST" class="form form-horizontal">
            <div class="form-group form-group-lg">
                <div class="col-md-6 col-sm-12">
                    <a class="btn btn-block btn-lg btn-secondary" href="index.php?module=lagermobil&action=list">
                        zurück zur Übersicht
                    </a>
                </div>
            </div>
            [BEFOREARTICLE]
            <div class="form-group form-group-lg">
                <label for="article" class="col-md-1 col-sm-2 control-label">{|Artikel|}:</label>
                <div class="col-md-5 col-sm-10">
                    <input type="text" name="article" id="article" class="form-control" value="[ARTICLE]" />
                </div>
            </div>
            [AFTERARTICLE]

            [BEFOREARTICLEIINFO]
            <div class="row">
                <div class="col-md-offset-1 col-md-5 col-sm-offset-2 col-sm-10">
                    <input type="hidden" name="articleid" id="articleid" value="[ARTICLEID]" />
                    <p>[ARTICLENAME]</p>
                </div>
            </div>
            [AFTERARTICLEIINFO]

            [BEFOREARTICLEIINFO]
            <div class="row">
                <div class="col-md-offset-1 col-md-5 col-sm-offset-2 col-sm-10">
                    <p>Nr. [ARTICLENUMBER]</p>
                </div>
            </div>
            [AFTERARTICLEIINFO]
            <div class="row">
                <div class="col-md-offset-1 col-md-5 col-sm-offset-2 col-sm-10">
                    <p>[ARTIKELLAGERPLATZ]</p>
                </div>
            </div>

            [BEFORELOCATIONSTORAGE]
            <div class="form-group form-group-lg">
                <label for="locationstorage" class="col-md-1 col-sm-2 control-label">{|Lagerplatz|}:</label>
                <div class="col-md-5 col-sm-10">
                    <input type="text" name="locationstorage" id="locationstorage" class="form-control" value="[LOCALSTORAGE]" />
                </div>
            </div>
            [AFTERLOCATIONSTORAGE]

            [BEFOREAMOUNTINFO]
            <div class="form-group form-group-lg">
                <label for="amounthidden" class="col-md-1 col-sm-2 control-label">{|Menge|}:</label>
                <div class="col-md-5 col-sm-10">
                    <p>[AMOUNT]</p>
                    <input type="hidden" id="amounthidden" name="amounthidden" value="[AMOUNT]" />
                </div>
            </div>
            [AFTERAMOUNTINFO]

            [BEFORELOCATIONSTORAGEINFO]
            <div class="form-group form-group-lg">
                <label for="locationstoragehidden" class="col-md-1 col-sm-2 control-label">{|Lagerplatz|}:</label>
                <div class="col-md-5 col-sm-10">
                    <p>[LOCALSTORAGE]</p>
                    <input type="hidden" id="locationstoragehidden" name="locationstoragehidden" value="[LOCALSTORAGEID]" />
                </div>
            </div>
            [AFTERLOCATIONSTORAGEINFO]

            <div class="row">
                <div class="col-md-offset-1 col-md-5 col-sm-offset-2 col-sm-10">
                    <p>[STORAGEINFO]</p>
                </div>
            </div>

            [BEFOREAMOUNT]
            <div class="form-group form-group-lg">
                <label for="amount" class="col-md-1 col-sm-2 control-label">{|Menge|}:</label>
                <div class="col-md-5 col-sm-10">
                    <input type="text" id="amount" name="amount" class="form-control" value="[AMOUNT]" />
                </div>
            </div>
            [AFTERAMOUNT]
            <div class="form-group form-group-lg">
                <div class="col-sm-offset-2 col-sm-10 col-md-offset-1 col-md-5">
                    <input type="hidden" name="do" value="[DO]" />
                    <input type="submit" name="save" id="save" class="btn btn-primary btn-block btn-lg" value="[SAVETEXT]" />
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


<script type="application/javascript">
    $(document).ready(function() {
        $('span.lagerplatz').on('click', function (){
            $('#locationstorage').val($(this).html());
        });
    });
</script>