/********* fullcalendar.js *********/ 
$(document).ready(function () {
    var date = new Date();
    var d = date.getDate();
    var m = date.getMonth();
    var y = date.getFullYear();
    var clickedEvent;

    var dayNamesShort = ['Sonntag', 'Montag', 'Dienstag', 'Mittwoch', 'Donnerstag', 'Freitag', 'Samstag'];
    var dayNames = ['Sonntag', 'Montag', 'Dienstag', 'Mittwoch', 'Donnerstag', 'Freitag', 'Samstag'];
    var monthNames = ['Januar', 'Februar', 'März', 'April', 'Mai',
        'Juni', 'Juli', 'August', 'September', 'Oktober', 'November', 'Dezember'];
    var monthNamesShort = ['Januar', 'Februar', 'März', 'April', 'Mai',
        'Juni', 'Juli', 'August', 'September', 'Oktober', 'November', 'Dezember'];
    var today = 'Heute';
    var month = 'Monat';
    var week = 'Woche';
    var day = 'Tag';

    if ($('#calendar').length === 0) {
        return;
    }

    $("input#ansprechpartner").autocomplete({
        source: "index.php?module=ajax&action=filter&filtername=ansprechpartneradresse&adresse="+0,
    });

    $("input#adresse").autocomplete({
        source: "index.php?module=ajax&action=filter&filtername=adresse",
        select: function( event, ui ) {
            if(ui.item){
                $("input#ansprechpartner").autocomplete({
                    source: "index.php?module=ajax&action=filter&filtername=ansprechpartneradresse&adresse="+ui.item.value,
                });
            }
        }
    });

    var $calendarattributes = $('#calendarattributes');

    if ($calendarattributes.length !== 0) {
        try{
            var calendarattributes = JSON.parse($calendarattributes.html());

            dayNamesShort = calendarattributes.dayNames;
            dayNames = calendarattributes.dayNames;

            monthNames = calendarattributes.monthNames;
            monthNamesShort = calendarattributes.monthNames;

            today = calendarattributes.today;
            month = calendarattributes.month;
            week = calendarattributes.week;
            day = calendarattributes.day;

        }catch(e){
            //Nichts tun; Fallback wird verwendet
        }
    }

    $('#calendar').fullCalendar({
        theme: true,
        header: {
            left: 'tasks',
            center: 'prev,title,next',
            right: 'today,month,agendaWeek,agendaDay'
        },
        allDayText: 'Ganzt&auml;gig',
        firstDay: 1,
        dayNamesShort: dayNamesShort,
        dayNames: dayNames,
        monthNames: monthNames,
        monthNamesShort: monthNamesShort,
        timeFormat: 'H:mm',
        buttonText: {
            prev: "<span class='fc-text-arrow'>&lsaquo;</span>",
            next: "<span class='fc-text-arrow'>&rsaquo;</span>",
            prevYear: "<span class='fc-text-arrow'>&laquo;</span>",
            nextYear: "<span class='fc-text-arrow'>&raquo;</span>",
            today: today,
            month: month,
            week: week,
            day: day
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
        loading: function (isLoading, view) {
            //var myView = $.cookie('currentView');
            //var myViewDate = $.cookie('currentViewDate');

            var myView = Nocie.Get('currentView');
            var myViewDate = Nocie.Get('currentViewDate');

            // In Monatsansicht zum aktuellen Tag scrollen
            // (isLoading ist false wenn der AJAX-Request da ist)
            if (isLoading === false && view.name === 'month') {
                var autoscroll = parseInt($('#calendar').data('autoscroll'));
                if (autoscroll === 1) {
                    setTimeout(function () { // Timeout
                        $('.fc-today').attr('id', 'scrollTo'); // Set an ID for the current day..
                        $('html, body').animate({
                            scrollTop: $('#scrollTo').offset().top - 104 // Scroll to this ID
                        }, 1000);
                    }, 200);
                }
            }

            if (isLoading && myView != null) {
                $('#calendar').fullCalendar('changeView', myView);
                if (myViewDate != null) {
                    var mydate = Date.parse(myViewDate);
                    var year = $.format.date(mydate, 'yyyy');
                    var month = $.format.date(mydate, 'M') - 1;
                    var day = (myView === 'month') ? 15 : $.format.date(mydate, 'd');
                    $('#calendar').fullCalendar('gotoDate', year, month, day);

                    Nocie.Remove('currentViewDate');
                    Nocie.Remove('currentView');
                }
            }
        },
        select: function (start, end, allDay) {
            var myView = $('#calendar').fullCalendar('getView');

            Nocie.Set('currentViewDate', myView.start);
            Nocie.Set('currentView', myView.name);

            // Neuen Event anlegen
            $("#TerminDialog").SetFormData(-1, start, end, allDay);
            $("#TerminDialog").dialog("open");
            AllDay($('#allday'));
        },


        eventDrop: function (event, dayDelta, minuteDelta, allDay, revertFunc)
            //	eventDrop: function(event, element)
        {
            var task = '';

            if (event.task != undefined)
                task = '&task=' + event.task;
            if (event.id > -1) {
                $.get('./index.php?module=kalender&action=update&id=' + event.id + '&start=' + $.fullCalendar.formatDate(event.start, 'yyyy-MM-dd HH:mm:ss')
                    + '&end=' + $.fullCalendar.formatDate(event.end, 'yyyy-MM-dd HH:mm:ss') + '&allDay=' + event.allDay + task,
                    function () {
                        $('#calendar').fullCalendar('updateEvent', event);
                    });
            } else {
                alert("Eintrag kann nicht verschoben werden");
                revertFunc();
            }
        },
        eventResize: function (event, dayDelta, minuteDelta, revertFunc, jsEvent, ui, view) {
            //eventResize: function(event, element) {
            if (event.id > -1) {
                $.get('./index.php?module=kalender&action=update&id=' + event.id + '&start=' + $.fullCalendar.formatDate(event.start, 'yyyy-MM-dd HH:mm:ss')
                    + '&end=' + $.fullCalendar.formatDate(event.end, 'yyyy-MM-dd HH:mm:ss'),
                    function () {
                        $('#calendar').fullCalendar('updateEvent', event);
                    });
            } else {
                alert("Eintrag kann nicht verlängert werden");
                revertFunc();
            }
        },

        eventClick: function (calEvent, jsEvent, view) {
            clickedEvent = calEvent;

            var myView = $('#calendar').fullCalendar('getView');
            //$.cookie("currentView",myView.name);
            //$.cookie("currentViewDate", myView.visStart);

            Nocie.Set('currentView', myView.name);
            Nocie.Set('currentViewDate', calEvent.start);

            if ($("#TerminDialog").SetFormData(calEvent.id, calEvent.start, calEvent.end, calEvent.allDay, calEvent.task)) {
                console.log('open soon');
                $("#TerminDialog").dialog("open");
                AllDay($('#allday'));
            }
        },
        editable: true,
        events: "./index.php?module=kalender&action=data"
    });

    $(document).on('click', '#allday', function (e) {
        AllDay(this);
    });

    $.fn.SetFormData = function (id, start, end, allDay, task) {
        if (id == -2) {
            AufgabenEdit(task);
            //window.open('index.php?module=aufgaben&action=edit&id=' + task + '&back=kalender#tabs-3', '_blank');
            return false;
        }
        else if (id == -3) {
            // mindesthaltbarkeit
            //alert("Eintrag kann nicht editiert werden");
            window.open('index.php?module=artikel&action=mindesthaltbarkeitsdatum&id=' + task, '_blank');
            return false;
        }
        else if (id == -4) {
            // geburstag
            window.open('index.php?module=adresse&action=brief&id=' + task, '_blank');
            return false;
        }
        else if (id == -5) {
            // Projekt //heute mal wieder rein 4.08.18 BS
            window.open('index.php?module=projekt&action=dashboard&id=' + task, '_blank');
            return false;
        }
        else if (id == -6) {
            // Serviceauftrag
            window.open('index.php?module=serviceauftrag&action=edit&id=' + task, '_blank');
            return false;
        }
        else if (id == -7) {
            // Urlaub Mitarbeiterzeiterfassung
            return false;
        }
        else if (id > -1) {
            var formdata = null;
            // Edit
            $.ajax({
                url: './index.php?module=kalender&action=eventdata&id=' + id,
                success: function (data) {
                    EditMode(data);
                },
                error: function (request, statusCode, error) {
                    $("#submitError").html("Keine Event-Daten gefunden");
                }
            });
        } else {
            // Neuen Event anlegen

            console.log('new');

            // Zuletzt angeklickten Event vergessen
            clickedEvent = null;

            var defaultColor = $('#calendar').data('default-color');
            if (typeof defaultColor === 'undefined' || defaultColor === '') {
                defaultColor = '#0B8092';
            }

            // New
            $("#mode").val("new");
            $("#datum").val($.format.date(start, "dd.MM.yyyy"));
            $("#datum_bis").val($.format.date(end, "dd.MM.yyyy"));
            $('#googleStatus').html('');

            // Buttons
            $(":button:contains('Kopieren')").prop("disabled", true).addClass('ui-state-disabled');
            $(":button:contains('Löschen')").prop("disabled", true).addClass('ui-state-disabled');
            $(":button:has('Einladung')").prop("disabled", true).addClass('ui-state-disabled');

            $("input[name=public]").prop('checked', 'checked');
            $("input[name=erinnerung]").prop('checked', 'checked');
            $("input[name=allday]").prop('checked', 'checked');
            $("#personen option").prop('selected', false);
            $("#gruppenkalender option").prop('selected', false);
            $("#color").val(defaultColor).change();

            // Ganztags
            if (allDay) {
                $("#allday").attr('checked', true);
                $("#von").attr('disabled', true);
                $("#bis").attr('disabled', true);
            } else {
                $("#allday").attr('checked', false);
                $("#von").attr('disabled', false);
                $("#bis").attr('disabled', false);
            }

            // Von & Bis
            $("#von").val($.format.date(start, "HH:mm"));
            $("#bis").val($.format.date(end, "HH:mm"));

            // Haken bei Ganztags entfernen, wenn Uhrzeit eingetragen wird
            $("#von,#bis").on('keyup', function () {
                var $allday = $('#allday');
                if ($allday.prop('checked')) {
                    $allday.prop('checked', false);
                }
            });
        }
        return true;
    };

    var EditMode = function (data) {
        $("#mode").val("edit");
        $("#eventid").val(data.id);
        $("#titel").val(data.titel);
        $("#ort").val(data.ort);
        $("#adresse").val(data.adresse);
        $("#ansprechpartner").val(data.ansprechpartner);
        $("#adresseintern").val(data.adresseintern);
        $("#projekt").val(data.projekt);
        $("#beschreibung").val(data.beschreibung);
        $("#datum").val($.format.date(data.von, "dd.MM.yyyy"));
        $("#datum_bis").val($.format.date(data.bis, "dd.MM.yyyy"));

        // Buttons
        $(":button:contains('Kopieren')").prop("disabled", false).removeClass('ui-state-disabled');
        $(":button:contains('Löschen')").prop("disabled", false).removeClass('ui-state-disabled');
        $(":button:has('Einladung')").prop("disabled", false).removeClass('ui-state-disabled');

        $('#googleStatus').html('');
        if(data.googleEventLink !== '' && data.googleEventEdit === false) {
            $('#googleStatus').append('<i>Dieser Google Termin kann nur vom Besitzer bearbeitet werden.</i></br>');
            $(":button:contains('Löschen')").prop("disabled", true).addClass('ui-state-disabled');
            $(":button:contains('Speichern')").prop("disabled", true).addClass('ui-state-disabled');
            $(":button:contains('Einladung')").prop("disabled", true).addClass('ui-state-disabled');
            $(":button:contains('Kopieren')").prop("disabled", true).addClass('ui-state-disabled');
        }
        // Cannot edit an Event if it's a Google Event of another user
        if(data.googleEventLink !== '') {
            $('#googleStatus').append('In <a href="'+data.googleEventLink+'" target="_blank">Google Kalender öffnen<a>');
        }

        // Ganztags
        if (data.allDay) {
            $("#allday").prop('checked', true);
            $("#von").prop('disabled', true);
            $("#bis").prop('disabled', true);
        } else {
            $("#allday").prop('checked', false);
            $("#von").prop('disabled', false);
            $("#bis").prop('disabled', false);
        }

        // Öffentlich
        if (data.public)
            $("#public").prop('checked', true);
        else
            $("#public").prop('checked', false);

        // Erinnerung
        if (data.erinnerung)
            $("#erinnerung").prop('checked', true);
        else
            $("#erinnerung").prop('checked', false);


        // Von & Bis
        $("#von").val($.format.date(data.von, "HH:mm"));
        $("#bis").val($.format.date(data.bis, "HH:mm"));

        // Color
        //$("#colors option[value='"+data.color+"']").prop('selected','selected');
        //if($("#colors option[value='"+data.color+"']").prop('selected')=='selected')
        //	$("#colors").css("background-color", data.color);
        $("#color").val(data.color);
        $("#color").change();

        // Personen
        $('#personen option').removeAttr('selected');
        if (data.personen != null && data.personen !== undefined) {
            jQuery.each(data.personen, function (k, v) {
                $("#personen option[value='" + v.userid + "']").prop('selected', 'selected');
            });
        }

        // Gruppenkalender
        $('#gruppenkalender option').removeAttr('selected');
        if (data.gruppenkalender != null && data.gruppenkalender !== undefined) {
            jQuery.each(data.gruppenkalender, function (k, v) {
                $("#gruppenkalender option[value='" + v.kalendergruppe + "']").prop('selected', 'selected');
            });
        }

        $("input#ansprechpartner").autocomplete({
            source: "index.php?module=ajax&action=filter&filtername=ansprechpartneradresse&adresse="+data.adressid,
        });

    };

    // Standard-Verhalten der Multiple-Selectbox ändern:
    // - Beim Klick auf einen Eintrag wird nur der angeklickte Eintrag ausgewählt; bzw. abgewählt (wenn vorher aktiv).
    // - Das Halten der Strg-Taste zum Entfernen einer Auswahl ist dadurch nicht mehr notwendig.
    $('#personen option, #gruppenkalender option').on('mousedown', function (e) {
        e.preventDefault();
        $(e.target).prop('selected', function (i, value) {
            return !value;
        });
    });

    $('#TerminForm').submit(function (e) {
        var mode = $('#mode').val();
        e.preventDefault();

        var formData = $(this).serialize();
        $.ajax({
            url: 'index.php?module=kalender&action=list&ajax=true',
            method: 'post',
            data: formData,
            dataType: 'json',
            success: function (eventData) {

                // Event wurde gelöscht > Event aus Kalender entfernen
                if (mode === 'delete' && typeof eventData.deletedEventId !== 'undefined') {
                    $('#calendar').fullCalendar('removeEvents', eventData.deletedEventId);
                    $('#TerminDialog').dialog('close');
                    return;
                }

                // Termin wurde kopiert
                if (mode === 'copy' && clickedEvent !== null) {
                    $('#calendar').fullCalendar('refetchEvents');
                    $('#TerminDialog').dialog('close');
                    return;
                }

                // Neuer Termin wurde angelegt
                if (mode === 'new' && clickedEvent === null) {
                    $('#calendar').fullCalendar('refetchEvents');
                    $('#TerminDialog').dialog('close');
                    return;
                }

                // Termin wurde bearbeitet > Originalen Event aktualisieren
                if (mode === 'edit' && clickedEvent !== null) {
                    clickedEvent = updateExistingEvent(clickedEvent, eventData);
                    $('#calendar').fullCalendar('updateEvent', clickedEvent);
                }
            }
        });
    });

    $("#TerminDialog").dialog({
        autoOpen: false,
        height: 600,
        width: 1000,
        modal: true,
        buttons: {
            "Speichern": function () {
                var errMsg = '';

                if ($("#datum").val() == "") errMsg = "Geben Sie bitte ein g&uuml;ltiges Datum ein (dd.mm.jjjj)"
                if ($("#titel").val() == "") errMsg = "Geben Sie bitte einen Titel ein";

                if (errMsg != "")
                    $("#submitError").html(errMsg);
                else {
                    $('#TerminForm').submit();
                    $(this).dialog("close");
                }
            },
            "Kopieren": function () {
                $("#mode").val("copy");
                $('#TerminForm').submit();
            },
            "Löschen": function () {
                if (confirm("Soll der Termin wirklich gelöscht werden?")) {
                    $("#mode").val("delete");
                    $('#TerminForm').submit();
                }
            },
            "Einladung": function () {
                var errMsg = '';

                if ($("#datum").val() == "") errMsg = "Geben Sie bitte ein g&uuml;ltiges Datum ein (dd.mm.jjjj)"
                if ($("#titel").val() == "") errMsg = "Geben Sie bitte einen Titel ein";

                if (errMsg != "")
                    $("#submitError").html(errMsg);
                else {
                    $('#noRedirect').val('1');
                    // $('#TerminForm').submit();
                    $.ajax({
                        url: 'index.php?module=kalender&action=list',
                        method: 'post',
                        data: $('#TerminForm').serialize(),
                        dataType: 'json',
                        success: function (datatermin) {
                            $("#einladungeventid").val(datatermin.eventid);
                            $("#TerminDialog").dialog("close");
                            $("#TerminDialogEinladung").dialog("open");
                            // betreff und text holen und ansprechpartner list
                            $.ajax({
                                url: 'index.php?module=kalender&action=eventdata&cmd=getEinladung&id=' + $("#einladungeventid").val(),
                                method: 'post',
                                dataType: 'json',
                                success: function (data) {
                                    if (data.status == 1) {
                                        $('#noRedirect').val('0');
                                        $('#TerminDialogEinladung').find('#einladungbetreff').val(data.betreff);
                                        $('#TerminDialogEinladung').find('#einladungtext').val(data.text);
                                        $('#TerminDialogEinladung').find('#einladungcc').val(data.einladungcc);
                                    } else {
                                        alert(data.statusText);
                                    }
                                }
                            });


                        }
                    });
                }
            },
            // "Einladung": function () {
            // 	var errMsg = '';
            // 	if ($("#datum").val() == "") errMsg = "Geben Sie bitte ein g&uuml;ltiges Datum ein (dd.mm.jjjj)"
            // 	if ($("#titel").val() == "") errMsg = "Geben Sie bitte einen Titel ein";
            //
            // 	if (errMsg != "")
            // 		$("#submitError").html(errMsg);
            // 	else {
            // 		$("#TerminDialogEinladung").dialog("open");
            // 		// betreff und text holen und ansprechpartner list
            //
            // 		$.ajax({
            // 			url: 'index.php?module=kalender&action=einladung&cmd=get&id=' + $("#eventid").val(),
            // 			method: 'post',
            // 			dataType: 'json',
            // 			success: function (data) {
            // 				if (data.status == 1) {
            // 					$('#TerminDialogEinladung').find('#einladungbetreff').val(data.betreff);
            // 					$('#TerminDialogEinladung').find('#einladungtext').val(data.text);
            // 					$('#TerminDialogEinladung').find('#einladungcc').val(data.einladungcc);
            // 				} else {
            // 					alert(data.statusText);
            // 				}
            // 			}
            // 		});
            //
            // 	}
            //
            // },

            "Abbrechen": function () {
                $(this).dialog("close");
            }
        },
        close: function () {
            $("#submitError").html("");
            $("#titel").val("");
            $("#ort").val("");
            $("#adresse").val("");
            $("#ansprechpartner").val("");
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

            $("input#ansprechpartner").autocomplete({
                source: "index.php?module=ajax&action=filter&filtername=ansprechpartneradresse&adresse="+0,
            });
        }
    });

    $("#TerminDialogEinladung").dialog({
        modal: true,
        bgiframe: true,
        closeOnEscape: false,
        minWidth: 900,
        minHeight: 350,
        autoOpen: false,
        buttons: {
            ABBRECHEN: function () {
                $(this).dialog('close');
            },
            "Einladung SENDEN": function () {

                $.ajax({
                    url: 'index.php?module=kalender&action=eventdata&cmd=sendEinladung&id=' + $("#eventid").val(),
                    data: {
                        //Alle Felder die fürs editieren vorhanden sind
                        id: $('#einladungeventid').val(),
                        betreff: $('#einladungbetreff').val(),
                        text: $('#einladungtext').val(),
                        emailcc: $('#einladungcc').val()
                    },
                    method: 'post',
                    dataType: 'json',
                    beforeSend: function () {
                        App.loading.open();
                    },
                    success: function (data) {
                        App.loading.close();
                        if (data.status == 1) {
                            /*    $('#editKontorahmen').find('#editid').val('');
                                $('#editKontorahmen').find('#editkonto').val('');
                                $('#editKontorahmen').find('#editbeschriftung').val('');
                                $('#editKontorahmen').find('#editart').val('');
                                $('#editKontorahmen').find('#editbemerkung').val('');
                                $('#editKontorahmen').find('#editnichtsichtbar').val('');

                */
                            // alert(data.statusText);
                            $("#TerminDialogEinladung").dialog('close');
                        } else {
                            alert(data.statusText);
                            $('#calendar').fullCalendar('refetchEvents');
                        }
                    }
                });

                //alert($("#einladungeventid").val());
                /*
                              $.ajax({
                                url: 'index.php?module=kalender&action=einladung&id='+$("#eventid").val(),
                                data: {
                                  //Alle Felder die fürs editieren vorhanden sind
                                  //id: $('#editid').val(),
                                  id: $('#').val(),
                                  betreff: $('#einladungbetreff').val(),
                                  text: $('#einladungtext').val(),
                                  betreff: $('#einladungcc').val()
                                },
                                success: function(data) {
                                },
                                error: function (request, statusCode, error) {
                                });
                   */
            }
        }
    });

    $("#TerminDialogEinladung").dialog({
        close: function (event, ui) {
        }
    });
});

function AllDay(el) {
    if (el.checked) {
        $("#von").attr('disabled', true);
        $("#bis").attr('disabled', true);
    } else {
        $("#von").attr('disabled', false);
        $("#bis").attr('disabled', false);
    }
}

function getDialogButton(jqUIdialog, button_names) {
    if (typeof button_names == 'string')
        button_names = [button_names];
    var buttons = jqUIdialog.parent().find('.ui-dialog-buttonpane button');
    for (var i = 0; i < buttons.length; i++) {
        var jButton = $(buttons[i]);
        for (var j = 0; j < button_names.length; j++)
            if (jButton.text() == button_names[j])
                return jButton;
    }

    return null;
}

/**
 * Aktualisiert ein vorhandenes Event-Objekt
 *
 * @param {Event} event https://fullcalendar.io/docs/v1/event-object
 * @param {Array} data
 *
 * @return {Event}
 */
var updateExistingEvent = function(event, data) {
    event.title = data.title;
    event.beschreibung = data.beschreibung;
    event.color = data.color;
    event.farbe = data.color;
    event.projekt = data.projekt;
    event.adresse = data.adresse;
    event.ansprechpartner = data.ansprechpartner;
    event.adresseintern = data.adresseintern;
    event.allDay = (data.allDay === '1');
    event.public = (data.public === '1');
    event.erinnerung = (data.erinnerung === '1');
    event.start = new Date(data.start);
    event.end = new Date(data.end);

    return event;
};

/**
 * Erzeugt ein neues Event-Objekt
 *
 * @param {Array} data
 *
 * @return {Event} https://fullcalendar.io/docs/v1/event-object
 */
var createNewEvent = function (data) {
    return {
        id: data.id,
        title: data.title,
        color: data.color,
        projekt: data.projekt,
        adresse: data.adresse,
        ansprechpartner: data.ansprechpartner,
        adresseintern: data.adresseintern,
        allDay: (data.allDay === '1'),
        public: (data.public === '1'),
        erinnerung: (data.erinnerung === '1'),
        start: new Date(data.start),
        end: new Date(data.end)
    };
};


/********* calendar.js *********/ 
$(document).ready(function() {


   var $calendar = $('#calendar');
   var id = 10;
   if($calendar.weekCalendar !== undefined)
   {     
   $calendar.weekCalendar({
      displayOddEven:false,
      timeslotsPerHour : 4,
      allowCalEventOverlap : true,
      overlapEventsSeparate: true,
      firstDayOfWeek : 1,
      businessHours :{start: 8, end: 18, limitDisplay: false },
      daysToShow : 8,
      title: function(daysToShow) {
			return daysToShow == 1 ? '%date%' : '%start% - %end%';
      },
      height : function($calendar) {
         return $(window).height() - $("h1").outerHeight() - 1;
      },
      eventRender : function(calEvent, $event) {
         if (calEvent.end.getTime() < new Date().getTime()) {
            $event.css("backgroundColor", "#aaa");
            $event.find(".wc-time").css({
               "backgroundColor" : "#999",
               "border" : "1px solid #888"
            });
         }
      },
      draggable : function(calEvent, $event) {
         return calEvent.readOnly != true;
      },
      resizable : function(calEvent, $event) {
         return calEvent.readOnly != false;
      },
      eventNew : function(calEvent, $event) {
         var $dialogContent = $("#event_edit_container");
         resetForm($dialogContent);
         var startField = $dialogContent.find("select[name='start']").val(calEvent.start);
         var endField = $dialogContent.find("select[name='end']").val(calEvent.end);
         var titleField = $dialogContent.find("input[name='title']");
         var bodyField = $dialogContent.find("textarea[name='body']");
         var publicField = $dialogContent.find("input[name='public']");

         $dialogContent.dialog({
            modal: true,
            title: "Neuer Termin",
            close: function() {
               $dialogContent.dialog("destroy");
               $dialogContent.hide();
               $('#calendar').weekCalendar("removeUnsavedEvents");
            },
            buttons: {
               speichern : function() {
                  id++;
                  calEvent.id = id;
                  calEvent.start = new Date(startField.val());
                  calEvent.end = new Date(endField.val());
                  calEvent.title = titleField.val();
                  calEvent.body = bodyField.val();

									if(publicField.prop("checked"))
										calEvent.publicEvent='1';
									else
										calEvent.publicEvent='0';

									publicField.prop("checked", false);
                  $calendar.weekCalendar("removeUnsavedEvents");
                  $calendar.weekCalendar("updateEvent", calEvent);
                  $dialogContent.dialog("close");
									$calendar.weekCalendar("refresh");
               },
               abbrechen : function() {
                  $dialogContent.dialog("close");
               }
            }
         }).show();

         $dialogContent.find(".date_holder").text($calendar.weekCalendar("formatDate", calEvent.start));
         setupStartAndEndTimeFields(startField, endField, calEvent, $calendar.weekCalendar("getTimeslotTimes", calEvent.start));

      },
      eventDrop : function(calEvent, $event) {
      	$calendar.weekCalendar("updateEvent", calEvent);
	$calendar.weekCalendar("refresh");
      },

      eventResize : function(calEvent, $event) {
      $calendar.weekCalendar("updateEvent", calEvent);
        $calendar.weekCalendar("refresh");
      },
      eventClick : function(calEvent, $event) {

         if (calEvent.readOnly) {
            return;
         }

         var $dialogContent = $("#event_edit_container");
         resetForm($dialogContent);
         var startField = $dialogContent.find("select[name='start']").val(calEvent.start);
         var endField = $dialogContent.find("select[name='end']").val(calEvent.end);
         var titleField = $dialogContent.find("input[name='title']").val(calEvent.title);
         var bodyField = $dialogContent.find("textarea[name='body']").val(calEvent.body);
				 if(calEvent.publicEvent=='1') publicChecked = true; else publicChecked = false;
         var publicField = $dialogContent.find("input[name='public']").prop('checked', publicChecked);
         $dialogContent.dialog({
            modal: true,
            title: "Edit - " + calEvent.title,
            close: function() {
               $dialogContent.dialog("destroy");
               $dialogContent.hide();
               $('#calendar').weekCalendar("removeUnsavedEvents");
            },
            buttons: {
               save : function() {

                  calEvent.start = new Date(startField.val());
                  calEvent.end = new Date(endField.val());
                  calEvent.title = titleField.val();
                  calEvent.body = bodyField.val();
									if(publicField.attr("checked"))
                    calEvent.publicEvent='1';
                  else
                    calEvent.publicEvent='0';

                  $calendar.weekCalendar("updateEvent", calEvent);
                  $dialogContent.dialog("close");
									$calendar.weekCalendar("refresh");
               },
               "delete" : function() {
                  $calendar.weekCalendar("removeEvent", calEvent.id);
                  $dialogContent.dialog("close");
									$calendar.weekCalendar("refresh");
               },
               cancel : function() {
                  $dialogContent.dialog("close");
               }
            }
         }).show();

         var startField = $dialogContent.find("select[name='start']").val(calEvent.start);
         var endField = $dialogContent.find("select[name='end']").val(calEvent.end);
         $dialogContent.find(".date_holder").text($calendar.weekCalendar("formatDate", calEvent.start));
         setupStartAndEndTimeFields(startField, endField, calEvent, $calendar.weekCalendar("getTimeslotTimes", calEvent.start));
         $(window).resize().resize(); //fixes a bug in modal overlay size ??

      },
      eventMouseover : function(calEvent, $event) {
      },
      eventMouseout : function(calEvent, $event) {
      },
      noEvents : function() {

      },
	data : "./index.php?module=kalender&action=data"
   });
   }

   function resetForm($dialogContent) {
      $dialogContent.find("input").val("");
      $dialogContent.find("textarea").val("");
   }


   /*
    * Sets up the start and end time fields in the calendar event
    * form for editing based on the calendar event being edited
    */
   function setupStartAndEndTimeFields($startTimeField, $endTimeField, calEvent, timeslotTimes) {

      $startTimeField.empty();
      $endTimeField.empty();

      for (var i = 0; i < timeslotTimes.length; i++) {
         var startTime = timeslotTimes[i].start;
         var endTime = timeslotTimes[i].end;
         var startSelected = "";
         if (startTime.getTime() === calEvent.start.getTime()) {
            startSelected = "selected=\"selected\"";
         }
         var endSelected = "";
         if (endTime.getTime() === calEvent.end.getTime()) {
            endSelected = "selected=\"selected\"";
         }
         $startTimeField.append("<option value=\"" + startTime + "\" " + startSelected + ">" + timeslotTimes[i].startFormatted + "</option>");
         $endTimeField.append("<option value=\"" + endTime + "\" " + endSelected + ">" + timeslotTimes[i].endFormatted + "</option>");

         $timestampsOfOptions.start[timeslotTimes[i].startFormatted] = startTime.getTime();
         $timestampsOfOptions.end[timeslotTimes[i].endFormatted] = endTime.getTime();

      }
      $endTimeOptions = $endTimeField.find("option");
      $startTimeField.trigger("change");
   }

   var $endTimeField = $("select[name='end']");
   var $endTimeOptions = $endTimeField.find("option");
   var $timestampsOfOptions = {start:[],end:[]};

   //reduces the end time options to be only after the start time options.
   $("select[name='start']").change(function() {
      var startTime = $timestampsOfOptions.start[$(this).find(":selected").text()];
      var currentEndTime = $endTimeField.find("option:selected").val();
      $endTimeField.html(
            $endTimeOptions.filter(function() {
               return startTime < $timestampsOfOptions.end[$(this).text()];
            })
            );

      var endTimeSelected = false;
      $endTimeField.find("option").each(function() {
         if ($(this).val() === currentEndTime) {
            $(this).prop("selected", "selected");
            endTimeSelected = true;
            return false;
         }
      });

      if (!endTimeSelected) {
         //automatically select an end date 2 slots away.
         $endTimeField.find("option:eq(1)").prop("selected", "selected");
      }

   });


   var $about = $("#about");

   $("#about_button").click(function() {
      $about.dialog({
         title: "About this calendar demo",
         width: 600,
         close: function() {
            $about.dialog("destroy");
            $about.hide();
         },
         buttons: {
            close : function() {
               $about.dialog("close");
            }
         }
      }).show();
   });


});


/********* calendargroups.js *********/ 
$(document).ready(function () {
    $('#editbezeichnung').focus();

    $("#editKalendergruppen").dialog({
        modal: true,
        bgiframe: true,
        closeOnEscape: false,
        minWidth: 800,
        autoOpen: false,
        buttons: {
            ABBRECHEN: function () {
                $(this).dialog('close');
            },
            SPEICHERN: function () {
                kalendergruppenEditSave();
            }
        }
    });

    $("#editKalendergruppen").dialog({
        close: function (event, ui) {
            KalenderGruppeReset();
        }
    });

    // Kalendergruppen-Filter einmal auslösen
    $('#filterlid').trigger('change');

    // Kalendergruppe bearbeiten
    $(document).on('click', '.calendar-group-edit', function (e) {
        e.preventDefault();

        var groupID = $(this).data('calendar-group');
        KalenderGruppenEdit(groupID);
    });

    // Kalendergruppe löschen
    $(document).on('click', '.calendar-group-delete', function (e) {
        e.preventDefault();

        var groupID = $(this).data('calendar-group');
        KalenderGruppenDelete(groupID);
    });

    // Kalendergruppe anlgen
    $(document).on('click', '.calendar-group-create', function (e) {
        e.preventDefault();
        KalenderGruppenEdit(0);
    });

    // Kalendergruppen-Mitgliedschaft ändern
    $(document).on('click', '#kalender_gruppen_mitglieder input[type=checkbox]', function () {
        var groupID = $(this).data('calendar-group');
        grchange(groupID);
    });
});

function grchange(lid) {
    $.ajax({
            url: "index.php?module=kalender&action=gruppenzuordnung&cmd=change",
            type: 'POST',
            dataType: 'json',
            data: {
                lid: lid,
                kalendergruppe: $('#editid').val(),
                wert: $('#kg_' + lid).prop('checked') ? 1 : 0
            }
        }
    ).done(function (data) {
        var oTableL = $('#kalender_gruppen_mitglieder').DataTable();
        oTableL.ajax.reload();
    }).fail(function (jqXHR, textStatus) {
    });
}


function KalenderGruppeReset() {
    $('#editKalendergruppen').find('#editid').val('');
    $('#editKalendergruppen').find('#editbezeichnung').val('');
    $('#editKalendergruppen').find('#editfarbe').val('#0b8092');
    $('#editfarbe').trigger('change');
    $('#editKalendergruppen').find('#editausblenden').prop("checked", false);
}


function kalendergruppenEditSave() {

    $.ajax({
        url: 'index.php?module=kalender&action=gruppensave',
        data: {
            //Alle Felder die fürs editieren vorhanden sind
            editid: $('#editid').val(),
            editbezeichnung: $('#editbezeichnung').val(),
            editfarbe: $('#editfarbe').val(),
            editausblenden: $('#editausblenden').prop("checked") ? 1 : 0,
        },
        method: 'post',
        dataType: 'json',
        beforeSend: function () {
            App.loading.open();
        },
        success: function (data) {
            App.loading.close();
            if (data.status == 1) {
                updateLiveTable();
                $("#editKalendergruppen").dialog('close');
                KalenderGruppeReset();
            } else if (data.status == 2) {
                $('#editKalendergruppen').find('#editid').val(data.id);
                oMoreData1kalender_gruppen_mitglieder = data.id;
                document.getElementById("kalender_gruppen_mitglieder_reihe").style.display = "";
                updateLiveTable();
                updateLiveTable('#kalender_gruppen_mitglieder');
            } else {
                alert(data.statusText);
            }
        }
    });

}

function KalenderGruppenEdit(id) {
    if (id > 0) {
        oMoreData1kalender_gruppen_mitglieder = id;
        document.getElementById("kalender_gruppen_mitglieder_reihe").style.display = "";
        updateLiveTable('#kalender_gruppen_mitglieder');
        $.ajax({
            url: 'index.php?module=kalender&action=gruppenedit&cmd=get',
            data: {
                editid: id
            },
            method: 'post',
            dataType: 'json',
            beforeSend: function () {
                App.loading.open();
            },
            success: function (data) {
                $('#editKalendergruppen').find('#editid').val(data.id);
                $('#editKalendergruppen').find('#editbezeichnung').val(data.bezeichnung);
                $('#editKalendergruppen').find('#editfarbe').val(data.farbe);
                $('#editfarbe').trigger('change');
                $('#editKalendergruppen').find('#editausblenden').prop("checked", data.ausblenden == 1 ? true : false);
                App.loading.close();
                $("#editKalendergruppen").dialog('open');
            }
        });
    } else {
        document.getElementById("kalender_gruppen_mitglieder_reihe").style.display = "none";
        KalenderGruppeReset();
        $("#editKalendergruppen").dialog('open');
    }

}

function updateLiveTable(i) {
    if (typeof i == 'undefined' || i == '') {
        var oTableL = $('#kalender_gruppenlist').DataTable();
    } else {
        var oTableL = $(i).DataTable();
    }
    oTableL.ajax.reload();
}

function KalenderGruppenDelete(id) {

    var conf = confirm('Wirklich löschen?');
    if (conf) {
        $.ajax({
            url: 'index.php?module=kalender&action=gruppendelete',
            data: {
                editid: id
            },
            method: 'post',
            dataType: 'json',
            beforeSend: function () {
                App.loading.open();
            },
            success: function (data) {
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


