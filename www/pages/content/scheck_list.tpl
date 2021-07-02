<!-- gehort zu tabview -->
<div id="tabs">
    <ul>
        <li><a href="#tab1">offene Schecks</a></li>
        <li><a href="#tab2">Archiv</a></li>
    </ul>
<!-- ende gehort zu tabview -->
<form method="post">
<!-- erstes tab -->
<div id="tab1">
  <div class="row">
  <div class="row-height">
  <div class="col-xs-12 col-md-10 col-md-height">
  <div class="inside_white inside-full-height">

    <fieldset class="white">
      <legend>&nbsp;</legend>
        [TAB1]
    </fieldset>

    <fieldset>
      <legend>Stapelverarbeitung</legend>
      [SAMMELDRUCK]
    </fieldset>
  
  </div>
  </div>
  <div class="col-xs-12 col-md-2 col-md-height">
  <div class="inside inside-full-height">
    <form method="post">
      <fieldset>
        <legend>{|Aktion|}</legend>
        <center><input type="submit" style="width:150px" class="btnGreen" name="blanko" id="blanko" value="Blanko Scheck"></center>

      </fieldset>
    </form>
  </div>
  </div>
  </div>
  </div>
  







</div>

<div id="tab2">
[TAB2]
</div>




<!-- tab view schließen -->
</div>
<!-- ende tab view schließen -->
</form>

<script type="text/javascript">

function alleauswaehlen()
{
  var wert = $('#auswahlalle').prop('checked');
  $('#scheck_list').find(':checkbox').prop('checked',wert);
}

function chscheck(sid)
{
  var status = 0;
  var el = '#gutschrift_'+sid;
  status = $(el).prop('checked');
  if(status)status = 1;
               
  if(sid)
  {
    $.ajax({
      url: 'index.php?module=scheck&action=chscheck',
      type: 'POST',
      dataType: 'json',
      data: {gutschrift : sid, wert : status},
      success: function(data) {
                 
      },
      beforeSend: function() {

      }
    });
   
  }   
          
}

function updateLiveTable(i) {
    var oTableL = $('#scheck_archiv').dataTable();
    oTableL.fnFilter('%');
    oTableL.fnFilter('');   
}

function updateLiveTable2(i) {
	var oTableL = $('#scheck_list').dataTable();
	oTableL.fnFilter('%');
	oTableL.fnFilter('');
}

function ScheckDelete(id)
{

  var conf = confirm('Wirklich löschen?');
  if (conf) {
    $.ajax({
      url: 'index.php?module=scheck&action=delete',
      data: {
        id: id
      },
      method: 'post',
      dataType: 'json',
      beforeSend: function() {
        App.loading.open();
      },
      success: function(data) {
        if (data.status == 1) {
          updateLiveTable();
          updateLiveTable2();
        } else {
          alert(data.statusText);
        }
          App.loading.close();
      }
    });
  }

  return false;
}

</script>









</script>
