<!-- gehort zu tabview -->
<script type='text/javascript' src='./js/lib/dateFormat.min.js'></script>
<script type='text/javascript' src='./js/lib/fullcalendar-1.6.7/fullcalendar.min.js?v=1'></script>
<script type='text/javascript' src='./js/nocie.js'></script>

<link rel='stylesheet' type='text/css' href='./js/lib/fullcalendar-1.6.7/fullcalendar.css'/>
<link rel='stylesheet' type='text/css' href='./js/lib/fullcalendar-1.6.7/fullcalendar.print.css' media='print'/>

<style type="text/css">
	.icon-prio {
		position: absolute;
		top: 8px;
		right: 0;
		width: 20px;
		height: 20px;
		background-size: 100%;
		background-repeat: no-repeat;
		background-image: url("./themes/new/images/sales_achtung_gelb.png");
	}
	.stage-rank {
		height: 10px;
		text-align: left;
		letter-spacing: 3px;
		line-height: 10px;
		font-size: 18px;
		font-weight: bold;
                padding-bottom: 2px;
	}
	#calendar .fc-event .fc-event-inner {
		min-height: 20px;
		padding-top: 5px;
		padding-bottom: 5px;
	}
</style>

<script type='text/javascript'>
    $(document).ready(function () {
        var dateTodayStart = new Date();
        dateTodayStart.setHours(0, 0, 0);
        var dateTodayEnd = new Date();
        dateTodayEnd.setHours(23, 59, 59);
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
            monthNames: ['{|Januar|}', '{|Februar|}', '{|März|}', '{|April|}', '{|Mai|}', '{|Juni|}', '{|Juli|}', '{|August|}', '{|September|}', '{|Oktober|}', '{|November|}', '{|Dezember|}'],
            monthNamesShort: ['{|Januar|}', '{|Februar|}', '{|März|}', '{|April|}', '{|Mai|}', '{|Juni|}', '{|Juli|}', '{|August|}', '{|September|}', '{|Oktober|}', '{|November|}', '{|Dezember|}'],
            timeFormat: 'H:mm',
            buttonText: {
                prev: '<span class=\'fc-text-arrow\'>&lsaquo;</span>',
                next: '<span class=\'fc-text-arrow\'>&rsaquo;</span>',
                prevYear: '<span class=\'fc-text-arrow\'>&laquo;</span>',
                nextYear: '<span class=\'fc-text-arrow\'>&raquo;</span>',
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
            loading: function (isLoading, view) {
                var myView = Nocie.Get('currentView');
                var myViewDate = Nocie.Get('currentViewDate');

                if (isLoading && myView != null) {
                    $('#calendar').fullCalendar('changeView', myView);
                    if (myViewDate != null) {
                        var mydate = Date.parse(myViewDate);
                        var year = DateFormat.format.date(mydate, 'yyyy');
                        var month = DateFormat.format.date(mydate, 'M') - 1;
                        var day = (myView === 'month') ? 15: DateFormat.format.date(mydate, 'd');
                        $('#calendar').fullCalendar('gotoDate', year, month, day);

                        Nocie.Remove('currentViewDate');
                        Nocie.Remove('currentView');
                    }
                }
            },
            eventRender : function(calEvent, $event) {
                // Prio-Icon rendern
                if (calEvent.prio === true) {
                    $event.append('<div class="icon-prio" title="Priorität"></div>');
                }
                // Stages(-Dots) rendern
                if (typeof calEvent.stage_count === 'number' && typeof calEvent.stage_rank === 'number') {
                    var dots = '';
                    for (var currentRank = 1; currentRank <= calEvent.stage_count; currentRank++) {
                        var dotColor = calEvent.stage_rank_color;
                        if (calEvent.stage_rank < currentRank) {
                            dotColor = '#CCC';
                        }
                        if (calEvent.stage_rank > currentRank) {
                            dotColor = '#888'
                        }
                        dots += '<span style="color:' + dotColor + ';">&bull;</span>'
                    }
                    $event.find('.fc-event-inner').prepend('<div class="stage-rank" title="' + calEvent.stage_name + '">' + dots + '</div>')
                }
            },
            select: function (start, end, allDay) {
                var myView = $('#calendar').fullCalendar('getView');
                Nocie.Set('currentViewDate', myView.start);
                Nocie.Set('currentView', myView.name);
            },
            eventDrop: function (event, dayDelta, minuteDelta, allDay, revertFunc) {
                if (event.id !== '' && event.task === 1) {
                    $.get(
                        './index.php?module=wiedervorlage&action=calendar&cmd=update&id=' + event.id +
                        '&datum_abschluss=' + $.fullCalendar.formatDate(event.start, 'yyyy-MM-dd') +
                        '&allDay=' + event.allDay,
                        function () {
                            $('#calendar').fullCalendar('updateEvent', event);
                        }
                    );
                    $('#calendar').fullCalendar('refetchEvents');
                } else {
                    alert('Eintrag kann nicht verschoben werden');
                    revertFunc();
                }
            },
            eventClick: function (calEvent, jsEvent, view) {
                var myView = $('#calendar').fullCalendar('getView');
                Nocie.Set('currentView', myView.name);
                Nocie.Set('currentViewDate', calEvent.start);
                EditWiedervorlage(calEvent.id);
            },
            editable: true,
            events: './index.php?module=wiedervorlage&action=calendar&cmd=data&id=[ID]'
        });
    });
</script>
<div id="tabs">
	<ul>
		<li><a href="#tabs-1"></a></li>
	</ul>
	<!-- ende gehort zu tabview -->

	<!-- erstes tab -->
	<div id="tabs-1">
		<div class="rTabs">
			<ul>
				<li class=""><a href="index.php?module=wiedervorlage&action=dashboard&mitarbeiter=[MITARBEITER]">{|Dashboard|}</a></li>
				<li><a href="index.php?module=wiedervorlage&action=pipes&mitarbeiter=[MITARBEITER]">{|Pipelines|}</a></li>
				<li><a href="index.php?module=wiedervorlage&action=table&mitarbeiter=[MITARBEITER]">{|Liste|}</a></li>
				<li><a href="index.php?module=wiedervorlage&action=creationdate&mitarbeiter=[MITARBEITER]">{|Eingangsdatum|}</a></li>
				<li><a href="index.php?module=wiedervorlage&action=closingdate&mitarbeiter=[MITARBEITER]">{|Abschlussdatum|}</a></li>
				<li><a href="index.php?module=wiedervorlage&action=winsloses&mitarbeiter=[MITARBEITER]">{|Wins/Losses|}</a></li>
				<li class="aktiv"><a href="index.php?module=wiedervorlage&action=calendar&mitarbeiter=[MITARBEITER]">{|Kalender|}</a></li>
			</ul>
			<div class="rTabSelect">[ANSICHTSELECT]&nbsp;[MITARBEITERSELECT]</div>
			<div class="clear"></div>
		</div>

		[MESSAGE]
		[TAB1]

		[STARTSMALLKALENDER]
		<div class="row">
			<div class="row-height">
				<div class="col-xs-12 col-md-12 col-md-height">
					<div class="inside_white inside-full-height">
						<fieldset class="white">
							<legend>&nbsp;</legend>
							[ENDESMALLKALENDER]

							<div id='calendar'></div>

						</fieldset>
					</div>
				</div>
			</div>
		</div>

	</div>

	<!-- tab view schließen -->
</div>
