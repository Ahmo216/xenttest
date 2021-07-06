<script type='text/javascript' src='./js/lib/dateFormat.min.js'></script>
<script type='text/javascript' src='./js/lib/fullcalendar-1.6.7/fullcalendar.min.js?v=1'></script>
<script type='text/javascript' src='./js/nocie.js'></script>

<link rel='stylesheet' type='text/css' href='./js/lib/fullcalendar-1.6.7/fullcalendar.css' />
<link rel='stylesheet' type='text/css' href='./js/lib/fullcalendar-1.6.7/fullcalendar.print.css' media='print' />


<script type='text/javascript'>
  var projektname = '[PROJEKTNAME]';
	$(document).ready(function() {
	$("#TerminDialog").dialog({
			autoOpen: false,
			height: 650,
			width: 600,
			modal: true,
			buttons: {
				[VORSPEICHERN]"Speichern": function() {
					var errMsg = '';

					if($("#datum").val()=="") errMsg = "Geben Sie bitte ein g&uuml;ltiges Datum ein (dd.mm.jjjj)"
					if($("#titel").val()=="") errMsg = "Geben Sie bitte einen Titel ein";
					
					if(errMsg!="")
						$("#submitError").html(errMsg);	
					else{
						$('#TerminForm').submit();
						$(this).dialog("close");
					}
				},[NACHSPEICHERN]
        /*
				"Kopieren": function() {
					$("#mode").val("copy");
          $('#TerminForm').submit();
				},
				"Löschen": function() {
					if(confirm("Soll der Termin wirklich gelöscht werden?"))
          {
							$("#mode").val("delete");
							$('#TerminForm').submit();
          }
				},*/
				"Abbrechen": function() {
					$(this).dialog("close");
				}
			},
			close: function() {
				$("#submitError").html("");
				$("#titel").val("");
				$("#ort").val("");
				$("#adresse").val("");
				$("#adresseintern").val("");
				$("#projekt").val("");
				$("#beschreibung").val("");
				$("#datum").val("");
				$("#datum_bis").val("");
				$("#von").val("");
				$("#bis").val("");
				$("#public").attr('checked', false);
				$("#erinnerung").attr('checked', false);
				//$("#colors option[value='']").attr('selected','selected');
				//$("#colors").css('background-color','#FFFFFF');
				$("#color").val("");
				$("#color").change();
				//$('#personen option').removeAttr('selected');	
				$("#eventid").val("");
				$("#mode").val("");
			}
		});
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
			selectable: true,
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

				$("#TerminDialog").SetFormData(-1, start, end, allDay);
        $('#TerminDialog').find('input, textarea').attr('disabled',false);
        $('#TerminDialog').find('#mode').val('new');
        $('#TerminDialog').find('#titel').val('Meilenstein');
				$("#TerminDialog").dialog("open");
			},


			eventDrop: function (event, dayDelta, minuteDelta, allDay, revertFunc) 
		//	eventDrop: function(event, element) 
			{
				var task = '';
				var task2 = '';
				
				if(event.task!=undefined)
					task = '&task='+event.task;
				if(event.task2!=undefined)
					task2 = '&task2='+event.task2;

				if(event.id > -1){
				$.get('./index.php?module=projekt&action=dashboard&cmd=update&id=[ID]&eid='+event.id+'&start='+$.fullCalendar.formatDate(event.start,'yyyy-MM-dd HH-mm-ss')
								+'&end='+$.fullCalendar.formatDate(event.end,'yyyy-MM-dd HH:mm:ss')+'&allDay='+event.allDay+task+task2,
							function() {$('#calendar').fullCalendar('updateEvent', event);});
				} 
	                        else if(event.task > -1){
				$.get('./index.php?module=projekt&action=dashboard&cmd=update&id=[ID]&eid='+event.id+'&start='+$.fullCalendar.formatDate(event.start,'yyyy-MM-dd HH-mm-ss')
								+'&end='+$.fullCalendar.formatDate(event.end,'yyyy-MM-dd HH:mm:ss')+'&allDay='+event.allDay+task+task2,
							function() {$('#calendar').fullCalendar('updateEvent', event);});
				} 

	                        else if(event.task2 > -1){
				$.get('./index.php?module=projekt&action=dashboard&cmd=update&id=[ID]&eid='+event.id+'&start='+$.fullCalendar.formatDate(event.start,'yyyy-MM-dd HH-mm-ss')
								+'&end='+$.fullCalendar.formatDate(event.end,'yyyy-MM-dd HH:mm:ss')+'&allDay='+event.allDay+task+task2,
							function() {$('#calendar').fullCalendar('updateEvent', event);});
				} 



                                else {
									alert("Eintrag kann nicht verschoben werden");
					        revertFunc();
				}
    	},
			eventResize: function (event, dayDelta, minuteDelta, revertFunc, jsEvent, ui, view ) {
			//eventResize: function(event, element) {
				if(event.id > -1){
        	$.get('./index.php?module=projekt&action=dashboard&cmd=update&id=[ID]&eid='+event.id+'&start='+$.fullCalendar.formatDate(event.start,'yyyy-MM-dd HH-mm-ss')
                +'&end='+$.fullCalendar.formatDate(event.end,'yyyy-MM-dd HH:mm:ss'),
              function() {$('#calendar').fullCalendar('updateEvent', event);});
				} else {
									alert("Eintrag kann nicht verlängert werden");
					        revertFunc();
				}
      },

			eventClick: function(calEvent, jsEvent, view) {
				var myView = $('#calendar').fullCalendar('getView');
				//$.cookie("currentView",myView.name);
				//$.cookie("currentViewDate", myView.visStart);
	
				Nocie.Set('currentView', myView.name);
        Nocie.Set('currentViewDate', calEvent.start);

				if($("#TerminDialog").SetFormData(calEvent.id, calEvent.start, calEvent.end, calEvent.allDay,calEvent.task,calEvent.task2))
					$("#TerminDialog").dialog("open");
			},
			editable: [EDITABLE],
			events: "./index.php?module=projekt&action=dashboard&cmd=data&id=[ID]" 
		});
		
	});

function AllDay(el) {
	if(el.checked) {
		$("#von").attr('disabled', true);
    $("#bis").attr('disabled', true);
	}else{
		$("#von").attr('disabled', false);
    $("#bis").attr('disabled', false);
	}
}


$(function() {
	$.fn.SetFormData = function(id, start, end, allDay,task,task2) {
		if(task > 0) {
			$.ajax({
				url: './index.php?module=projekt&action=kalender&cmd=getkalender&id='+id,
        dataType: 'json',
				success: function(data) {

          $('#TerminDialog').find('input, textarea').attr('disabled',true);
          if(data.write == 1)
          {
            
            $('#TerminDialog').find('input, textarea').attr('disabled',false);
          }else{
            $('#TerminDialog').find('#titel').attr('disabled',true);
          }
          
          $('#TerminDialog').dialog('open');
          EditMode(data);
        },
				error: function (request, statusCode, error) { $("#submitError").html("Keine Event-Daten gefunden"); }
			});
			//window.open('index.php?module=projekt&action=edit&id='+task+'&back=kalender','_blank');
			return false;
		}
		else if(task2 > 0) {
			//alert("Eintrag kann nicht editiert werden");
			//window.open('index.php?module=projekt&action=edit&id='+task2+'&back=kalender','_blank');
			return false;
		}
                else if(id > -1) {
			//window.open('index.php?module=projekt&action=edit&id='+id,'_blank');
                        return false;
		}else{
//			//window.open('index.php?module=projekt&action=edit&id='+id,'_blank');
			$("#mode").val("new");
			$("#datum").val(DateFormat.format.date(start, "dd.MM.yyyy"));
			$("#datum_bis").val(DateFormat.format.date(end, "dd.MM.yyyy"));
      $("#projekt").val(projektname);
			
			// Buttons
			//$(":button:contains('Kopieren')").prop("disabled",true).addClass( 'ui-state-disabled' );
			//$(":button:contains('Löschen')").prop("disabled",true).addClass( 'ui-state-disabled' );

			$("#public").attr('checked', true);
			$("#erinnerung").attr('checked', true);

			// Ganztags
    	if(allDay) {
      	$("#allday").attr('checked', true);
      	$("#von").attr('disabled', true);
      	$("#bis").attr('disabled', true);
    	}else{
      	$("#allday").attr('checked', false);
      	$("#von").attr('disabled', false);
      	$("#bis").attr('disabled', false);
    	}

			// Von & Bis
			$("#von").val(DateFormat.format.date(start, "HH:mm"));
    	$("#bis").val(DateFormat.format.date(end, "HH:mm"));
			return false;
		}
		return true;
	}


});
	var EditMode = function(data) {
 
		$("#mode").val("edit");
		$("#eventid").val(data.id);
		$("#titel").val(data.titel);
		$("#ort").val(data.ort);
		$("#adresse").val(data.adresse);
		$("#adresseintern").val(data.adresseintern);
		$("#projekt").val(data.projekt);
		$("#beschreibung").val(data.beschreibung);
		$("#datum").val(DateFormat.format.date(data.von, "dd.MM.yyyy"));
		$("#datum_bis").val(DateFormat.format.date(data.bis, "dd.MM.yyyy"));

		// Buttons
    //$(":button:contains('Kopieren')").prop("disabled",false).removeClass( 'ui-state-disabled' );
    //$(":button:contains('Löschen')").prop("disabled",false).removeClass( 'ui-state-disabled' );

		// Ganztags
    if(data.allDay) {
    	$("#allday").prop('checked', true);
      $("#von").prop('disabled', true);
      $("#bis").prop('disabled', true);
    }else{
      $("#allday").prop('checked', false);
      $("#von").prop('disabled', false);
      $("#bis").prop('disabled', false);
    }

		// Öffentlich
		if(data.public) 
			$("#public").prop('checked', true);
		else
			$("#public").prop('checked', false);
		// Erinnerung
		if(data.erinnerung) 
			$("#erinnerung").prop('checked', true);
		else
			$("#erinnerung").prop('checked', false);
		// Von & Bis
    $("#von").val(DateFormat.format.date(data.von, "HH:mm"));
    $("#bis").val(DateFormat.format.date(data.bis, "HH:mm"));

		// Color
		//$("#colors option[value='"+data.color+"']").prop('selected','selected');
		//if($("#colors option[value='"+data.color+"']").prop('selected')=='selected')
		//	$("#colors").css("background-color", data.color);
                $("#color").val(data.color);
                $("#color").change();

		// Personen
		$('#personen option').removeAttr('selected');
		if(typeof data.personen != 'undefined' && data.personen != null)
    {
      jQuery.each(data.personen, function(k,v) {
        $("#personen option[value='"+v.userid+"']").prop('selected','selected');
      });		
    }
	};
function getDialogButton( jqUIdialog, button_names )
{
    if (typeof button_names == 'string')
        button_names = [button_names];
    var buttons = jqUIdialog.parent().find('.ui-dialog-buttonpane button');
    for (var i = 0; i < buttons.length; i++)
    {
        var jButton = $( buttons[i] );
        for (var j = 0; j < button_names.length; j++)
            if ( jButton.text() == button_names[j] )
                return jButton;
    }

    return null;
}
</script>
<div class="info">Neue Termine müssen im <a href="index.php?module=kalender&action=list" target="_blank">Hauptkalender</a> angelegt werden.</div>
<div id='calendar'></div>
<div id="TerminDialog" title="Termin erstellen / bearbeiten">
	<form id="TerminForm" action="" method="POST">
	<table width="100%" border="0">
		<tr><td></td><td colspan="3"><div id="submitError" style="color:red;"></div></td></tr>
		<tr>
			<td>Titel:</td>
			<td colspan="3"><input type="text" name="titel" id="titel" size="40"></td>
		</tr>
		<tr>
			<td>Beschreibung:</td>
			<td colspan="3"><textarea name="beschreibung" id="beschreibung" cols="40" rows="4"></textarea></td>
		</tr>
		<tr>
			<td>Ort:</td>
			<td colspan="3"><input type="text" name="ort" id="ort" size="40"></td>
		</tr>
			<tr>
			<td>Termin mit:</td>
			<td colspan="3"><input type="text" name="adresse" id="adresse" size="40">[LINKADRESSE]</td>
		</tr>
		<tr>
			<td>Verantwortlicher:</td>
			<td colspan="3"><input type="text" name="adresseintern" id="adresseintern" size="40"></td>
		</tr>
		<tr>
			<td>Projekt:</td>
			<td colspan="3"><input type="text" name="projekt" id="projekt" size="40"></td>
		</tr>
		
		<tr><td colspan="4">&nbsp;</td></tr>
		<tr>
			<td>Datum:</td><td><input type="text" name="datum" id="datum" size="10"></td>
			<td>Bis:</td><td><input type="text" name="datum_bis" id="datum_bis" size="10"></td>
		</tr>
		<tr>
			<td>Ganztags:</td>
			<td><input type="checkbox" name="allday" id="allday" value="1" onclick="AllDay(this)"></td>
			<td>Erinnerung:</td><td><input type="checkbox" name="erinnerung" id="erinnerung" value="1"></td>
		</tr>
		<tr>
      <td>Von:</td><td><input type="text" name="von" id="von" size="10"></td>
			<td>Bis:</td><td><input type="text" name="bis" id="bis" size="10"></td>
    </tr>
		<tr><td colspan="4">&nbsp;</td></tr>
		<tr>
			<td>Farbe:</td>
			<td><input type="text" name="color" id="color" value="#0b8092"></td>
		</tr>
		<tr>
			<td>&Ouml;ffentlich:</td>
			<td><input type="checkbox" name="public" id="public" value="1"></td>
		</tr>

		<tr>
			<td valign="top">Personen:</td>
			<td colspan="3">
				<select name="personen[]" id="personen" size="15" style="width: 250px" multiple>
					[PERSONEN]
				</select><br>
				<span style="font-size: 10px; color: #A0A0A0">Strg-Taste gedrückt halten um mehrere Personen auszuw&auml;hlen</span> 
			</td>
		</tr>
	</table>
	<input type="hidden" name="submitForm" value="1">
	<input type="hidden" name="mode" id="mode" value="">
	<input type="hidden" name="eventid" id="eventid" value="">
	</form>
</div>
