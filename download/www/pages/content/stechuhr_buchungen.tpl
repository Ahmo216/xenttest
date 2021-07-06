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
    

  servertime = parseFloat( $("#servertime").val() ) * 1000;
  $("#stechuhr").clock({"timestamp":servertime,"format":"24","langSet":"de"});
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
  height:47px;
  color:red;
}

.grey{
background-color:#ECECEC !important;
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
    <table width="100%" align="center" style="background-color:#cfcfd1;">
    <tr>
    <td align="center"><b style="font-size: 14pt">{|Stechuhr|} <font color="blue">[USERNAME]</font></b>&nbsp;[MITARBEITERNUMMER]<span class="stechuhr" id="stechuhr">0.000</span></td>
    </tr>
    </table>

    <table width="100%">
      <tr><td colspan="2"><input type="button" id="statusbutton" [STATUSSTYLE] name="kommen" class="button_stechuhr statusbutton" value="[STATUSBUTTON]" style="font-size:20pt" /></td></tr>
      <tr>
      <td colspan="2">
        [PDFS]
        <!-- <center><h2>letzte Buchungen</h2></center> -->
        [LETZTEBUCHUNGEN]
        <tr><td><a href="index.php?module=stechuhr&action=list&prodcmd=list"><input type="button" class="button_stechuhr" value="{|zur&uuml;ck|}" style="font-size:20pt"></a></td></tr>  
      </td></tr>
    </table>
    [TAB1]
    [TAB1NEXT]
        </td></tr></table>
  </div>
<!-- tab view schließen -->
</div>

