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
    defaultView: 'agendaWeek',
    header: {
      left: 'prev,next today tasks',
      center: 'title',
      right: 'month,agendaWeek,agendaDay'
    },
    allDayText: 'Ganzt&auml;gig',
    firstDay: 1,
    dayNamesShort: ['Sonntag', 'Montag', 'Dienstag', 'Mittwoch', 'Donnerstag', 'Freitag', 'Samstag'],
    dayNames: ['Sonntag', 'Montag', 'Dienstag', 'Mittwoch', 'Donnerstag', 'Freitag', 'Samstag'],
    monthNames: ['Januar', 'Februar', 'März', 'April', 'Mai',
                              'Juni', 'Juli', 'August', 'September', 'Oktober',  'November', 'Dezember'],	
    monthNamesShort: ['Januar', 'Februar', 'März', 'April', 'Mai',
        'Juni', 'Juli', 'August', 'September', 'Oktober',  'November', 'Dezember'],	
    timeFormat: 'H:mm',
    buttonText: {
      prev: "<span class='fc-text-arrow'>&lsaquo;</span>",
      next: "<span class='fc-text-arrow'>&rsaquo;</span>",
      prevYear: "<span class='fc-text-arrow'>&laquo;</span>",
      nextYear: "<span class='fc-text-arrow'>&raquo;</span>",
      today: 'Heute',
      month: 'Monat',
      week: 'Woche',
      day: 'Tag'
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
				
      if(event.task!=undefined)
        task = '&task='+event.task;
      if(event.task2!=undefined)
        task2 = '&task2='+event.task2;
      if(event.id !=""){
        $.get('./index.php?module=artikel_verfuegbarkeit&action=list&cmd=update&id=[ID]&eid='+event.id+'&start='+$.fullCalendar.formatDate(event.start,'yyyy-MM-dd HH-mm-ss')
        +'&end='+$.fullCalendar.formatDate(event.end,'yyyy-MM-dd HH:mm:ss')+'&allDay='+event.allDay+task+task2,function() {$('#calendar').fullCalendar('updateEvent', event);});
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
      window.open('index.php?module=artikel_verfuegbarkeit&action=list&cmd='+res[0]+'&id='+res[1],'_blank');
      return false;
    },
    editable: false,
    events: "./index.php?module=artikel_verfuegbarkeit&action=list&cmd=data&id=[ID]" 
  });

});
</script>
<div id="tabs">
    <ul>
        <li><a href="#tabs-1"><!--[TABTEXT]--></a></li>
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
<input type="checkbox" name="angebot" id="angebot" value="1" [CHECKEDANGEBOT]>
    <div style="width:15px;height:15px;border: 1px solid #656565; border-radius: 2px;  background:[COLORANGEBOT];display: inline-block;">&nbsp;</div>&nbsp;Offene Angebote<br>
<input type="checkbox" name="auftrag" id="auftrag" value="1" [CHECKEDAUFTRAG]>
    <div style="width:15px;height:15px;border: 1px solid #656565; border-radius: 2px;  background:[COLORAUFTRAG];display: inline-block;">&nbsp;</div>&nbsp;Offene Aufträge<br>
<input type="checkbox" name="bestellung" id="bestellung" value="1" [CHECKEDBESTELLUNG]>
    <div style="width:15px;height:15px;border: 1px solid #656565; border-radius: 2px;  background:[COLORBESTELLUNG];display: inline-block;">&nbsp;</div>&nbsp;Bestellung<br>
</fieldset>

</div>
</div>
</div>
</div>

</div>

<!-- tab view schließen -->
</div>


