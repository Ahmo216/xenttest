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
    #stepbefore {
        max-width:40px;
        float:left;
        /*text-align:center;
        background-color: var(--turquoise);
    }
    #nextstep {
        max-width:40px;
        float:right;
        /*text-align:center;
        background-color: var(--turquoise);*/
    }

    /*@media only screen and (min-width: 600px) {
        #lagermobiltabelle {
            width: 95vw;
            max-width: 1000px;
            font-size: 16px;
            font-weight: bold;
        }
    }*/
</style>
<div id="tabs" class="mobile-ui">
    <ul>
        <li><a href="#tabs-1"></a></li>
    </ul>
<!-- ende gehort zu tabview -->

<script type="application/javascript">
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

        <form method="POST" id="frmstockin" class="form form-horizontal" action="index.php?module=lagermobil&action=stockin">
            <input type="hidden" name="batchinfo" value="[BATCH]" />
            <input type="hidden" name="bestbeforeinfo" value="[BESTBEFORE]" />

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
                    <div class="input-autocomplete">
                        <input type="text" autofocus="autofocus" name="article" id="article" class="form-control" value="[ARTICLE]" />
                    </div>
                </div>
            </div>
            [AFTERARTICLE]

            [BEFOREARTICLEINFO]
            <div class="row">
                <div class="col-md-offset-1 col-md-5 col-sm-offset-2 col-sm-10">
                    <p>Nr. [ARTICLENUMBER]</p>
                </div>
            </div>
            [AFTERARTICLEINFO]
            [BEFOREARTICLEINFO]
            <div class="row">
                <input type="hidden" name="articleid" id="articleid" value="[ARTICLEID]" />
                <div class="col-md-offset-1 col-md-5 col-sm-offset-2 col-sm-10">
                    <p>[ARTICLENAME]</p>
                </div>
            </div>
            [AFTERARTICLEINFO]

            [BEFOREDEFAULTSTORAGE]
            <div class="row">
                <div class="col-md-offset-1 col-md-5 col-sm-offset-2 col-sm-10">
                    <p>{|Standardlager|}: [DEFAULTSTORAGE]</p>
                </div>
            </div>
            [AFTERDEFAULTSTORAGE]
            <div class="row">
                <div class="col-md-offset-1 col-md-5 col-sm-offset-2 col-sm-10">
                    <p>[ARTIKELLAGERPLATZ]</p>
                </div>
            </div>

            [BEFORELOCATIONSTORAGE]
            <div class="form-group form-group-lg">
                <label for="locationstorage" class="col-md-1 col-sm-2 control-label">{|Lagerplatz|}:</label>
                <div class="col-md-5 col-sm-10">
                    <div class="input-autocomplete">
                        <input type="text" autofocus="autofocus" name="locationstorage" id="locationstorage" class="form-control" value="[LOCALSTORAGE]" />
                    </div>
                </div>
            </div>
            [AFTERLOCATIONSTORAGE]

            [BEFOREAMOUNTINFO]
            <div class="form-group form-group-lg">
                <label for="amounthidden" class="col-md-1 col-sm-2 control-label">{|Menge|}:</label>
                <div class="col-md-5 col-sm-10">
                    <p>[AMOUNT]</p>
                    <input type="hidden" id="amounthidden" name="amounthidden" class="form-control" value="[AMOUNT]" />
                </div>
            </div>
            [AFTERAMOUNTINFO]

            [BEFORELOCATIONSTORAGEINFO]
            <div class="form-group form-group-lg">
                <label for="locationstoragehidden" class="col-md-1 col-sm-2 control-label">{|Lagerplatz|}:</label>
                <div class="col-md-5 col-sm-10">
                    <p>[LOCALSTORAGE]</p>
                    <input type="hidden" id="locationstoragehidden" name="locationstoragehidden" class="form-control" value="[LOCALSTORAGEID]" />
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
                    <input type="text" autofocus="autofocus" id="amount" name="amount" class="form-control" value="[AMOUNT]" />
                </div>
            </div>
            [AFTERAMOUNT]

            [BEFOREBESTBEFORE]
            <div class="form-group form-group-lg">
                <label for="bestbefore" class="col-md-1 col-sm-2 control-label">{|MHD|}:</label>
                <div class="col-md-5 col-sm-10">
                    <input type="text" name="bestbefore" id="bestbefore" class="form-control" value="[BESTBEFORE]" />
                </div>
            </div>
            [AFTERBESTBEFORE]
            [BEFOREBATCH]
            <div class="form-group form-group-lg">
                <label for="batch" class="col-md-1 col-sm-2 control-label">{|Charge|}:</label>
                <div class="col-md-5 col-sm-10">
                    <input type="text" name="batch" id="batch" class="form-control" value="[BATCH]" />
                </div>
            </div>
            [AFTERBATCH]
            [BEFOREBESTBEFOREINFO]
            <div class="form-group form-group-lg">
                <label class="col-md-1 col-sm-2 control-label">{|MHD|}:</label>
                <div class="col-md-5 col-sm-10">
                    <p>[BESTBEFORE]</p>
                </div>
            </div>
            [AFTERBESTBEFOREINFO]
            [BEFOREBATCHINFO]
            <div class="form-group form-group-lg">
                <label class="col-md-1 col-sm-2 control-label">{|Charge|}:</label>
                <div class="col-md-5 col-sm-10">
                    <p>[BATCH]</p>
                </div>
            </div>
            [AFTERBATCHINFO]


            [BEFORESR]
            <div class="row">
                <div class="col-md-offset-1 col-md-5 col-sm-offset-2 col-sm-10"><table id="trsr"></table></div>
            </div>
            [AFTERSR]
            <div class="row">
                <div class="col-md-offset-1 col-md-5 col-sm-offset-2 col-sm-10">
                    <p>[SRNTAB]</p>
                </div>
            </div>
            [BEFORESAVE]
            <div class="form-group form-group-lg">
                <label for="info" class="col-md-1 col-sm-2 control-label">{|Grund|}:</label>
                <div class="col-md-5 col-sm-10">
                    <input type="text" id="info" name="info" class="form-control" value="[INFO]" />
                </div>
            </div>
            <div class="form-group form-group-lg">
                <div class="col-sm-offset-2 col-sm-10 col-md-offset-1 col-md-5">
                    <input type="submit" name="save" id="save" class="btn btn-primary btn-block btn-lg" value="{|Buchen|}" />
                </div>
            </div>
            [AFTERSAVE]
            <div class="form-group form-group-lg">
                <div class="col-sm-offset-2 col-sm-10 col-md-offset-1 col-md-5">
                    <div class="row">
                        <div class="col-xs-6">
                            [BEFORESTEPBEFORE]
                            <input type="button" id="stepbefore" class="btn btn-secondary btn-block btn-lg" value="Zur&uuml;ck">
                            [AFTERSTEPBEFORE]
                        </div>

                        <div class="col-xs-6">
                            [BEFORENEXTSTEP]
                            <input type="button" id="nextstep" class="btn btn-primary btn-block btn-lg" value="Weiter">
                            [AFTERNEXTSTEP]
                        </div>
                    </div>
                </div>
            </div>

            <input type="hidden" name="step" id="step" value="[STEP]" />
            <input type="hidden" name="stepto" id="stepto" value="[STEPTO]" />
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
        $('#frmstockin').on('submit',function(event){
            //event.preventDefault();
            //console.log(event);
            $('#tabs-1').loadingOverlay('show');
            return true;
        });

        $('#nextstep').on('click',function(){
            $('#stepto').val('next');
            $('#frmstockin').trigger('submit');
            $('#tabs').loadingOverlay('show');
        });
        $('#stepbefore').on('click',function(){
            $('#stepto').val('before');
            $('#frmstockin').trigger('submit');
            $('#tabs').loadingOverlay('show');
        });

        $('#article').on('keypress',function(event){
            if($(this).val()+'' !== '' && (
                    typeof event.charCode != 'undefined' && event.charCode == 13 || typeof event.keyCode != 'undefined' && event.keyCode == 13 )
            ) {
                $('#stepto').val('next');
                $('#frmstockin').trigger('submit');
                $('#tabs').loadingOverlay('show');
                return true;
            }
        });

        $('#locationstorage').on('keypress',function(event){
            if($(this).val()+'' !== '' && (
                    typeof event.charCode != 'undefined' && event.charCode == 13 || typeof event.keyCode != 'undefined' && event.keyCode == 13 )
            ) {
                $('#stepto').val('next');
                $('#frmstockin').trigger('submit');
                $('#tabs').loadingOverlay('show');
                return true;
            }
        });

        $('#amount').on('keypress',function(event){
            if($(this).val()+'' !== '' && (
                    typeof event.charCode != 'undefined' && event.charCode == 13 || typeof event.keyCode != 'undefined' && event.keyCode == 13 )
            ) {
                if($('#trsr').length) {
                    return true;
                }
                if($('#trsr').length) {
                    return true;
                }
                if($('#bestbefore').length){
                    $('#bestbefore').focus();
                    return true;
                }
                if($('#bestbefore').length){
                    $('#batch').focus();
                    return true;
                }

                $('#stepto').val('next');
                $('#frmstockin').trigger('submit');
                $('#tabs').loadingOverlay('show');
                return true;
            }
        });

        $('span.storagelocation').on('click', function (){
            $('#locationstorage').val($(this).html());
        });
        if($('#trsr').length > 0) {
            $('#amount').on('change',function(){
                var menge = parseInt($(this).val());
                if(isNaN(menge) || menge < 1 ) {
                    menge = 1;
                }
                $('.trsn').each(function(){
                    var el = parseInt(this.id.split('-')[ 1 ]);
                    if(el > menge) {
                        $(this).parents('tr').first().remove();
                    }
                });
                for(var i = 1; i <= menge; i++) {
                    var tr = $('#trsn-'+i);
                    if(tr.length == 0)
                    {
                        if(i > 1)
                        {
                            var trbefore = $('#trsn-'+(i-1)).parents('tr').first();
                        }else{
                            var trbefore = $('#trsr');
                        }
                        $(trbefore).after('<tr><td><label for="trsn-'+i+'">SN '+i+'</label></td><td><input class="trsn" type="text" id="trsn-'+i+'" name="trsn[]" /></td></tr>');
                    }
                }
                $('#trsn-1').focus();
            });
        }
    });
</script>
