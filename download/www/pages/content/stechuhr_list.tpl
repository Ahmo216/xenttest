<script>



var run=0;
function getData() {


  if(run==0)
  {
    run = 1;
    $.ajax({
        url: "index.php?module=stechuhr&action=gewicht",
        type: "POST",
        data: {
//            "id": id
        },
        success: function (data) {
            run = 0;
            if(data==0.000) $("#stechuhr").css("color","black");
            else $("#stechuhr").css("color","blue");

            $("#stechuhr").html(data);
        }
    });
  } else {
    console.log("run");
  }
}
var last_button;

function sendData(produkt,button_call)
{
  last_button=button_call;
  $("#"+button_call).css("background-color","grey");
  $("#"+button_call).prop('disabled', true);

  window.clearInterval(intervall);

    /*$.ajax({
        url: 'index.php?module=stechuhr&action=create',
        data: {
          gewicht: $("#stechuhr").text(),
          produkt: produkt
        },
        type: "POST",
        beforeSend: function() {
            App.loading.open();
        },
        success: function(data) {
            App.loading.close();
            $("#"+button_call).css("background-color","");
            $("#"+button_call).prop('disabled', false);
            intervall = setInterval("getData()", 2000); //Polls in every 50 sec
        }
   });*/
}

var intervall;
$(document).ready(function () {
    /*intervall = setInterval("getData()", 2000); //Polls in every 50 sec
    getData(); //invoke on page load
    */
    
  if($("#servertime").length) {
    servertime = parseFloat( $("#servertime").val() ) * 1000;
  }
  var $stechuhr =  $("#stechuhr");
  if(stechuhr.length > 0) {
    $($stechuhr).clock({"timestamp":servertime,"format":"24","langSet":"de"});
  }

  $('#anz_mitarbeiter').on('change',function(){
    $.ajax({
      url: "index.php?module=stechuhr&action=list&cmd=changeanzmitarbeiter",
      type: "POST",
      data: {
        zid:$(this).data('zeiterfassung'),
        anz_mitarbeiter:$(this).val()
      },
      success: function (data) {

      }
    });

  });
  $('#anz_mitarbeiter_minus').on('click',function(){
    var val = parseInt($('#anz_mitarbeiter').val());
    if(isNaN(val))
    {
      val = 2;
    }
    val--;
    if(val < 1)
    {
      val = 1;
    }
    $('#anz_mitarbeiter').val(val);
    $('#anz_mitarbeiter').trigger('change');
  });
  $('#anz_mitarbeiter_plus').on('click',function(){
    var val = parseInt($('#anz_mitarbeiter').val());
    if(isNaN(val))
    {
      val = 0;
    }
    val++;
    if(val < 1)
    {
      val = 1;
    }
    $('#anz_mitarbeiter').val(val);
    $('#anz_mitarbeiter').trigger('change');
  });
});

function statuslogout()
{
if($('#neuerstatus').val() == $('#alterstatus').val())
{
  if(window.confirm("Status nicht geändert, wirklich Ausloggen?"))$('#fromsubmitstatus').submit();
} else $('#fromsubmitstatus').submit();

}

function setzealt()
{
  switch($('#alterstatus').val())
  {
    case 'kommen':
      $('#kommen').toggleClass('grey', true);
      $('#gehen').toggleClass('grey', false);
      $('#pausestart').toggleClass('grey', false);
      $('#pausestop').toggleClass('grey', true);
    break;
    case 'gehen':
      $('#kommen').toggleClass('grey', false);
      $('#gehen').toggleClass('grey', true);
      $('#pausestart').toggleClass('grey', true);
      $('#pausestop').toggleClass('grey', true);
    break;
    case 'pausestart':
      $('#kommen').toggleClass('grey', true);
      $('#gehen').toggleClass('grey', false);
      $('#pausestart').toggleClass('grey', true);
      $('#pausestop').toggleClass('grey', false);
    break;
    case 'pausestop':
      $('#kommen').toggleClass('grey', true);
      $('#gehen').toggleClass('grey', false);
      $('#pausestart').toggleClass('grey', true);
      $('#pausestop').toggleClass('grey', true);
    break;
  }

}

function setzealtenbutton()
{
  $('#kommen').toggleClass('grey', true);
  $('#gehen').toggleClass('grey', true);
  $('#pausestart').toggleClass('grey', true);
  $('#pausestop').toggleClass('grey', true);
  switch($('#alterstatus').val())
  {
    case 'kommen':
      $('#kommen').toggleClass('grey', false);
    break;
    case 'gehen':
      $('#gehen').toggleClass('grey', false);
    break;
    case 'pausestart':
      $('#pausestart').toggleClass('grey', false);
    break;
    case 'pausestop':
      $('#pausestop').toggleClass('grey', false);
    break;
  }
}

/*
$(document).ready(function () {
  setzealt();
});*/


function chstechuhrstatus(status)
{
  if($('#'+status).hasClass('grey'))return false;

  if($('#neuerstatus').val() == $('#alterstatus').val())
  {
    $('#neuerstatus').val(status);
    setzealtenbutton();
  } else {
    $('#neuerstatus').val($('#alterstatus').val());
    setzealt();
  }
}

</script>

<style>
input.grey, input.statusbutton{
cursor:default;
}
.stechuhr {
  font-size:16pt;
  font-weight:bold;
  width:100%;
  /*height:40px;
  background-color:#ccc;*/
  padding:10px;
  text-align:center;
}
.statusbutton
{
  background-color:#7A0 !important;
}
.button_stechuhr {
  width:100%;
  height:37px;
  color:red;
}

.grey{
background-color:#ECECEC !important;
}

  div.stechuhrdiv > a > input {
    box-sizing: border-box;
    height: 113px;
    width: 100%;
    border-radius: 0;
    margin: 0;
    cursor:pointer;
  }
  
  div.stechuhrdiv > a > input.grey {
    cursor:default;
  }
div.stechuhrdiv > a {
	width: 50%;
	float: left;
	padding: 5px;
	box-sizing: border-box;
  
}
  table#produktiontable td
  {
    margin:0;
    padding:0;
  }
  table#produktiontable td div.stechuhrdiv a:first-of-type
  {
    padding-left:0;
  }
  table#produktiontable td div.stechuhrdiv a:last-of-type
  {
    padding-right:0;
  }

  div.stechuhrdiv > a > input.button_stechuhr
  {
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
  }
  
  @media screen and  (max-width: 959px){
    div.stechuhrdiv > a > input.button_stechuhr
    {
      font-size:15pt;
      height:60px;
    }
  }
  @media screen and (min-width: 960px){
    div.stechuhrdiv > a > input.button_stechuhr
    {
      font-size:20pt;
    }
  }
  a.statusa
  {
    margin:0;
    padding-right:15px;
    width:100% !important;
    display:inline-block;
  }
  
  a.statusa > input
  {
    
    margin-right:-10px;
    padding-left:0;
    padding-right:0;
  }
  
  #stechuhr > span.clocktime
  {
    margin-left:10px;
  }
  
</style>

<!-- gehort zu tabview -->
<div id="tabs">
  <ul>
    <li><a href="#tabs-1">[TABTEXT]</a></li>
  </ul>
<!-- ende gehort zu tabview -->

  <!-- erstes tab -->
  <div id="tabs-1">
    <table width="100%" border="0" cellpadding="0" cellspacing="0">
    <tr><td valign="top" colspan="2">
    [MESSAGE]
      <div style="padding-left:5px;padding-right:5px;">
    <table width="100%" align="center" style="background-color:#cfcfd1;">
    <tr>
    <td align="center"><b style="font-size: 14pt">{|Stechuhr|} <font color="blue">[USERNAME]</font></b>&nbsp;[MITARBEITERNUMMER]<span class="stechuhr" id="stechuhr">0.000</span></td>
    </tr>
    </table>
        </div>
      <div class="stechuhrdiv">
    <a href="#" class="statusa"><input type="button" id="statusbutton" [STATUSSTYLE] name="kommen" class="button_stechuhr statusbutton" value="[STATUSBUTTON]"  /></a>
        </div>
        [VORNICHTPRODUKTION]
        <div class="stechuhrdiv">
          <a href="[AKOMMEN]"><input type="button" id="kommen" name="kommen" class="button_stechuhr[CLASSKOMMEN]" value="Kommen"  /></a>
          <a href="[APAUSESTART]"><input type="button" name="pausestart" id="pausestart" class="button_stechuhr[CLASSPAUSESTART]" value="Pause Start" ></a>
          <a href="[APAUSESTOP]"><input type="button" name="pausestop" id="pausestop" class="button_stechuhr[CLASSPAUSESTOP]" value="Pause Stop" ></a>
          <a href="[AGEHEN]"><input type="button" name="gehen" id="gehen" class="button_stechuhr[CLASSGEHEN]" value="Gehen"></a>
        </div>
        [NACHNICHTPRODUKTION]
        
      
      
    <table width="100%" id="produktiontable">
      <tr><td colspan="2"></td></tr>
      <tr>
      <td  colspan="2">
        <table width="100%" style="vertical-align:top;">
        <tr><td></td></tr>
        [VORPRODUKTION]
        <tr><td>&nbsp;</td></tr>
        <tr><td style="font-size:20pt">{|Produktion Arbeitsschritt|}:&nbsp;<form method="POST"><input type="text" name="produktion" id="produktion" value="" style="float:right;width:40%;"></form>
        <script>$('#produktion').focus();</script>
        </td></tr>
        [NACHPRODUKTION]
        [VORPRODUKTIONANZMITARBEITER]
          <tr><td>&nbsp;</td></tr>
          <tr><td style="font-size:20pt">{|Anzahl Mitarbeiter|}:&nbsp;<img id="anz_mitarbeiter_minus" src="./themes/new/images/loeschen.png" border="0"> <input type="text" data-zeiterfassung="[ZID]" name="anz_mitarbeiter" id="anz_mitarbeiter" value="[ANZ_MITARBEITER]" size="5"> <img src="./themes/new/images/einlagern.png" id="anz_mitarbeiter_plus" border="0">
              <script>$('#anz_mitarbeiter').focus();</script>
            </td></tr>
        [NACHPRODUKTIONANZMITARBEITER]
        [VORPRODUKTIONABSCHLUSS]
        <tr><td>[PRODUKTIONABSCHLUSS]</td></tr>

        [NACHPRODUKTIONABSCHLUSS]
        [VORPRODUKTIONSBUTTON]
        [NACHPRODUKTIONSBUTTON]
        </table>
      </td></tr>
    </table>
    <div class="stechuhrdiv">
      [VORPRODUKTIONSBUTTON]
      <a href="index.php?module=stechuhr&action=list&prodcmd=arbeitsschritt" [ARBEITSSCHRITTESTYLE]><input type="button" name="arbeitsschritt" id="arbeitsschritt" class="button_stechuhr" value="{|Arbeitsschritte|}"></a>
      [NACHPRODUKTIONSBUTTON]
      [VORNICHTPRODUKTION][VORBUCHUNGENBUTTON]
      <a href="index.php?module=stechuhr&action=list&cmd=letztebuchungen"><input type="button" name="letztebuchungen" id="letztebuchungen" class="button_stechuhr" value="{|Buchungen|}"></a>
      [NACHBUCHUNGENBUTTON][NACHNICHTPRODUKTION]
      
      [VORZURUECK]<a href="index.php?module=stechuhr&action=list&prodcmd=list"><input type="button" class="button_stechuhr" value="{|zur&uuml;ck|}"></a>[NACHZURUECK]
      
      <a href="index.php?module=welcome&action=logout" [LOGOUTSTYLE] ><input type="button" name="logout" id="logout" class="button_stechuhr" value="Logout"></a>
    </div>
    [TAB1]
    [TAB1NEXT]
        </td></tr></table>
  </div>
<!-- tab view schließen -->
</div>

