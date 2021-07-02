<!-- gehort zu tabview -->
<script type='text/javascript' src='./js/lib/dateFormat.min.js'></script>
<script type='text/javascript' src='./js/lib/fullcalendar-1.6.7/fullcalendar.min.js?v=1'></script>
<script type='text/javascript' src='./js/nocie.js'></script>

<link rel='stylesheet' type='text/css' href='./js/lib/fullcalendar-1.6.7/fullcalendar.css' />
<link rel='stylesheet' type='text/css' href='./js/lib/fullcalendar-1.6.7/fullcalendar.print.css' media='print' />


<script type='text/javascript'>
$(document).ready(function() {
  var date = new Date();
  var d = date.getDate();
  var m = date.getMonth();
  var y = date.getFullYear();
	
  $('#calendar').fullCalendar({
    theme: true,
    header: {
      left: 'prev,next today tasks',
      center: 'title',
      right: 'month,agendaWeek,agendaDay'
    },
    allDayText: '{|Ganzt&auml;gig|}',
    firstDay: 1,
    dayNamesShort: ['{|Sonntag|}', '{|Montag|}', '{|Dienstag|}', '{|Mittwoch|}', '{|Donnerstag|}', '{|Freitag|}', '{|Samstag|}'],
    dayNames: ['{|Sonntag|}', '{|Montag|}', '{|Dienstag|}', '{|Mittwoch|}', '{|Donnerstag|}', '{|Freitag|}', '{|Samstag|}'],
    monthNames: ['{|Januar|}', '{|Februar|}', '{|März|}', '{|April|}', '{|Mai|}',
                              '{|Juni|}', '{|Juli|}', '{|August|}', '{|September|}', '{|Oktober|}',  '{|November|}', '{|Dezember|}'],
    monthNamesShort: ['{|Januar|}', '{|Februar|}', '{|März|}', '{|April|}', '{|Mai|}',
        '{|Juni|}', '{|Juli|}', '{|August|}', '{|September|}', '{|Oktober|}',  '{|November|}', '{|Dezember|}'],
    timeFormat: 'H:mm',
    buttonText: {
      prev: "<span class='fc-text-arrow'>&lsaquo;</span>",
      next: "<span class='fc-text-arrow'>&rsaquo;</span>",
      prevYear: "<span class='fc-text-arrow'>&laquo;</span>",
      nextYear: "<span class='fc-text-arrow'>&raquo;</span>",
      today: '{|Heute|}',
      month: '{|Monat|}',
      week: '{|Woche|}',
      day: '{|Tag|}'
    },
    axisFormat: 'HH:mm',
    columnFormat: {
      month: 'ddd',
      week: 'ddd d.M',
      day: 'dddd d.M'
    },
    weekNumbers: true,
    weekNumberTitle: 'W',
    selectable: false,
    loading: function(isLoading, view) {
      //var myView = $.cookie('currentView');
      //var myViewDate = $.cookie('currentViewDate');
	
      var myView = Nocie.Get('currentView')
      var myViewDate = Nocie.Get('currentViewDate')

      if(isLoading && myView!=null) {
        $('#calendar').fullCalendar('changeView', myView);
        if(myViewDate!=null){
          var mydate = Date.parse(myViewDate);
          var year = DateFormat.format.date(mydate, 'yyyy');
          var month = DateFormat.format.date(mydate, 'M') - 1;
          if(myView=='month')
            var day = 15;
          else var day = DateFormat.format.date(mydate, 'd');
          $('#calendar').fullCalendar( 'gotoDate', year, month, day);

          Nocie.Remove('currentViewDate');
          Nocie.Remove('currentView');
        }
      }
    },
    select: function(start, end, allDay) {
      var myView = $('#calendar').fullCalendar('getView');
      Nocie.Set('currentViewDate', myView.start);
      Nocie.Set('currentView', myView.name);
    },
    eventDrop: function (event, dayDelta, minuteDelta, allDay, revertFunc) 
    {
      var task = '';
      var task2 = '';

				
      if(event.id !="" && event.task==1){
        $.get('./index.php?module=managementboard_liquiditaet&action=kalender&cmd=update&id=[ID]&eid='+event.id+'&start='+$.fullCalendar.formatDate(event.start,'yyyy-MM-dd HH-mm-ss')
        +'&end='+$.fullCalendar.formatDate(event.end,'yyyy-MM-dd HH:mm:ss')+'&allDay='+event.allDay+task+task2,function() {$('#calendar').fullCalendar('updateEvent', event);});

        $('#calendar').fullCalendar('refetchEvents');
      } 
      else {
        alert("Eintrag kann nicht verschoben werden");
        revertFunc();
      }
    },
    eventClick: function(calEvent, jsEvent, view) {
      var myView = $('#calendar').fullCalendar('getView');
      Nocie.Set('currentView', myView.name);
      Nocie.Set('currentViewDate', calEvent.start);
      var str = calEvent.id;
      var res = str.split("_");
      if(res[0]=="auftrag" || res[0]=="bestellung" || res[0]=="rechnung" || res[0]=="gutschrift" || res[0]=="verbindlichkeit")
        if(res[1]){
          window.open('index.php?module='+res[0]+'&action=edit&id='+res[1],'_blank');
        }
      return false;
    },
    editable: true,
    events: "./index.php?module=managementboard_liquiditaet&action=kalender&cmd=data&id=[ID]" 
  });

});

$(document).ready(function() {

    $("#editdatum").datepicker({ dateFormat: "dd.mm.yy" });
    $("#editenddatum").datepicker({ dateFormat: "dd.mm.yy" });

    $("#editKosten").dialog({
    modal: true,
    bgiframe: true,
    closeOnEscape:false,
    minWidth:650,
    maxHeight:700,
    autoOpen: false,
    buttons: {
      ABBRECHEN: function() {
        KostenReset();
        $(this).dialog('close');
      },
      SPEICHERN: function() {
        KostenSave();
      }
    }
  });

  $("#editKosten").dialog({
    close: function( event, ui ) { KostenReset();}
  });

});


function KostenReset()
{
  $('#editKosten').find('#editid').val('');
  $('#editKosten').find('#editbezeichnung').val('');
  $('#editKosten').find('#editbetrag').val('');
  $('#editKosten').find('#editart').val(0);
}

function KostenSave() {
        $.ajax({
        url: 'index.php?module=managementboard_liquiditaet&action=save',
        data: {
            //Alle Felder die fürs editieren vorhanden sind
            id: $('#editid').val(),
            art: $('#editart').val(),
            bezeichnung: $('#editbezeichnung').val(),
            betrag: $('#editbetrag').val(),
            datum: $('#editdatum').val(),
            enddatum: $('#editenddatum').val()
        },
        method: 'post',
        dataType: 'json',
        beforeSend: function() {
            App.loading.open();
        },
        success: function(data) {
            App.loading.close();
            if (data.status == 1) {
                KostenReset();
                updateLiveTable();
                $("#editKosten").dialog('close');
            } else {
                alert(data.statusText);
            }
        }
    });
}

function KostenEdit(id) {

  if(id > 0)
  {
    $.ajax({
        url: 'index.php?module=managementboard_liquiditaet&action=edit&cmd=get',
        data: {
            id: id
        },
        method: 'post',
        dataType: 'json',
        beforeSend: function() {
            App.loading.open();
        },
        success: function(data) {
            $('#editKosten').find('#editid').val(data.id);
            $('#editKosten').find('#editbezeichnung').val(data.bezeichnung);
            $('#editKosten').find('#editdatum').val(data.datum);
            $('#editKosten').find('#editenddatum').val(data.enddatum);
            $('#editKosten').find('#editbetrag').val(data.betrag);
            if(data.art=="" || data.art <=0 )
              $('#editKosten').find('#editart').val('0');
            else
              $('#editKosten').find('#editart').val(data.art);
            App.loading.close();
            $("#editKosten").dialog('open');
        }
    });

  } else {
    $("#editKosten").dialog('open');
  }
}

function KostenDelete(id) {

    var conf = confirm('Wirklich löschen?');
    if (conf) {
        $.ajax({
            url: 'index.php?module=managementboard_liquiditaet&action=delete',
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
                } else {
                    alert(data.statusText);
                }
                App.loading.close();
            }
        });
    }

    return false;

}

function updateLiveTable(i) {
    var oTableL = $('#managementboard_liquiditaet_kosten').dataTable();
    var tmp = $('.dataTables_filter input[type=search]').val();
    oTableL.fnFilter('%');
    //oTableL.fnFilter('');
    oTableL.fnFilter(tmp);  
}




</script>
<div id="tabs">
    <ul>
        <li><a href="#tabs-1">{|&Uuml;bersicht|}</a></li>
        <li><a href="#tabs-2">{|Sonstige Kosten|}</a></li>
    </ul>
<!-- ende gehort zu tabview -->

<!-- erstes tab -->
<div id="tabs-1">
[MESSAGE]
[TAB1]

[STARTSMALLKALENDER]
<div class="row">
<div class="row-height">
<div class="col-xs-12 col-md-10 col-md-height">
<div class="inside_white inside-full-height">
<fieldset class="white"><legend>&nbsp;</legend>
[ENDESMALLKALENDER]

<div id='calendar'></div>

</fieldset>
</div>
</div><div class="col-xs-12 col-md-2 col-md-height">
<div class="inside inside-full-height">
  <fieldset><legend>{|Belege|}</legend>
    <input type="checkbox" name="rechnung" id="rechnung" value="1" [CHECKEDRECHNUNG]>
    <div style="width:15px;height:15px;border: 1px solid #656565; border-radius: 2px;  background:[COLORRECHNUNG];display: inline-block;">&nbsp;</div>&nbsp;<label for="rechnung">{|Rechnungen|}</label><br>
    <input type="checkbox" name="auftrag" id="auftrag" value="1" [CHECKEDAUFTRAG]>
    <div style="width:15px;height:15px;border: 1px solid #656565; border-radius: 2px;  background:[COLORAUFTRAG];display: inline-block;">&nbsp;</div>&nbsp;<label for="auftrag">{|Auftrag ohne Rechnung|}</label><br>
    <input type="checkbox" name="gutschrift" id="gutschrift" value="1" [CHECKEDGUTSCHRIFT]>
    <div style="width:15px;height:15px;border: 1px solid #656565; border-radius: 2px;  background:[COLORGUTSCHRIFT];display: inline-block;">&nbsp;</div>&nbsp;<label for="gutschrift">{|Gutschriften|}</label><br>
    <input type="checkbox" name="verbindlichkeit" id="verbindlichkeit" value="1" [CHECKEDVERBINDLICHKEIT]>
    <div style="width:15px;height:15px;border: 1px solid #656565; border-radius: 2px;  background:[COLORVERBINDLICHKEIT];display: inline-block;">&nbsp;</div>&nbsp;<label for="verbindlichkeit">{|Verbindlichkeiten ohne Bestellung|}</label><br>
    <input type="checkbox" name="bestellung" id="bestellung" value="1" [CHECKEDBESTELLUNG]>
    <div style="width:15px;height:15px;border: 1px solid #656565; border-radius: 2px;  background:[COLORBESTELLUNG];display: inline-block;">&nbsp;</div>&nbsp;<label for="bestellung">{|Bestellungen|}</label><br>
    <input type="checkbox" name="sonstige" id="sonstige" value="1" [CHECKEDSONSTIGE]>
    <div style="width:15px;height:15px;border: 1px solid #656565; border-radius: 2px;  background:[COLORSONSTIGE];display: inline-block;">&nbsp;</div>&nbsp;<label for="sonstige">{|Sonstige Kosten|}</label><br>
    <br>
    <input type="checkbox" name="gruppieren" id="gruppieren" value="1" [CHECKEDGRUPPIEREN]>
    <label for="gruppieren">{|Kosten gruppieren|}</label><br>

  </fieldset>

</div>
</div>
</div>
</div>

</div>

<div id="tabs-2">

<div id="editKosten" style="display:none;" title="Bearbeiten">
  <input type="hidden" id="editid">
  <table>
    <tr>
      <td>{|Bezeichnung|}:</td>
      <td><input type="text" name="editbezeichnung" id="editbezeichnung" size="40"></td>
    </tr>
    <tr>
      <td>Art:</td>
      <td><select name="editart" id="editart">
            <option value="0">{|Termin|}</option>
            <option value="1">{|Monatlich|}</option>
            <option value="2">{|alle 2 Monate|}</option>
            <option value="3">{|Vierteljährlich|}</option>
            <option value="4">{|Halbjährlich|}</option>
            <option value="5">{|Jährlich|}</option>
          </select>
      </td>
    </tr>
    <tr>
      <td>{|Datum|}:</td>
      <td><input type="text" size="12" name="editdatum" id="editdatum"></td>
    </tr>
   
    <tr>
      <td>{|Betrag|}:</td>
      <td><input type="text" size="12" name="editbetrag" id="editbetrag"></td>
    </tr>
<!--
    <tr>
      <td>Enddatum:</td>
      <td><input type="text" size="12" name="editenddatum" id="editenddatum"></td>
    </tr>
-->
  </table>
</div>

  <center><input style="width:20em" type="button" name="anlegen" value="Neuen Eintrag anlegen" onclick="KostenEdit(0);"></center>
[TAB2]
</div>



<!-- tab view schließen -->
</div>


