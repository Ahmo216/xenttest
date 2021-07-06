var ResubmissionPopupUi = (function ($) {
    'use strict';

    var me = {
        selector: {
            projectInput: '#editprojekt',
            projectLink: '#project_link',
            subProjectInput: '#editsubproject',
            subProjectLink: '#subproject_link'
        },
        init: function () {
            $('input#editprojekt').autocomplete({
                source: 'index.php?module=ajax&action=filter&filtername=projektname'
            });

            $('input#editansprechpartner').autocomplete({
                source: 'index.php?module=ajax&action=filter&filtername=ansprechpartneradresse&adresse=' + 0
            });

            $('input#editadresse').autocomplete({
                source: 'index.php?module=ajax&action=filter&filtername=adresse',
                select: function (event, ui) {
                    if (ui.item) {
                        $('input#editansprechpartner').autocomplete({
                            source: 'index.php?module=ajax&action=filter&filtername=ansprechpartneradresse&adresse='
                                + ui.item.value
                        });
                    }
                }
            });


            $('input#editbearbeiter').autocomplete({
                source: 'index.php?module=ajax&action=filter&filtername=' + window.employeeAutocomplete
            });


            $('input#editadresse_mitarbeiter').autocomplete({
                source: 'index.php?module=ajax&action=filter&filtername=' + window.employeeAutocomplete
            });


            $('input#editstages').autocomplete({
                source: 'index.php?module=ajax&action=filter&filtername=wiedervorlage_stages'
            });

            $(me.selector.projectInput).on('blur', function () {
                me.linkProject();
            });
            $(me.selector.projectInput).on('change', function () {
                me.linkProject();
            });
            $(me.selector.subProjectInput).on('change', function () {
                me.linkSubProject();
            });
            $(me.selector.subProjectInput).on('blur', function () {
                me.linkSubProject();
            });
            $('#editdatum_abschluss').datepicker({
                dateFormat: 'dd.mm.yy',
                dayNamesMin: ['SO', 'MO', 'DI', 'MI', 'DO', 'FR', 'SA'],
                firstDay: 1,
                showWeek: true,
                monthNames: [
                    'Januar',
                    'Februar',
                    'März',
                    'April',
                    'Mai',
                    'Juni',
                    'Juli',
                    'August',
                    'September',
                    'Oktober',
                    'November',
                    'Dezember']
            });
            $('#editdatum_erinnerung').datepicker({
                dateFormat: 'dd.mm.yy',
                dayNamesMin: ['SO', 'MO', 'DI', 'MI', 'DO', 'FR', 'SA'],
                firstDay: 1,
                showWeek: true,
                monthNames: [
                    'Januar',
                    'Februar',
                    'März',
                    'April',
                    'Mai',
                    'Juni',
                    'Juli',
                    'August',
                    'September',
                    'Oktober',
                    'November',
                    'Dezember']
            });
            $('#editzeit_erinnerung').timepicker();
            me.linkProject();
            me.linkSubProject();
        },
        linkProject: function () {
            if ($(me.selector.projectInput).val() + '' === '') {
                $(me.selector.projectLink).attr(
                    'href', '#'
                );
                $(me.selector.projectLink).attr(
                    'target', ''
                );
                return;
            }
            $(me.selector.projectLink).attr(
                'href',
                'index.php?module=wiedervorlage&action=table&cmd=projectlink&project='
                + $(me.selector.projectInput).val().split(' ')[0]
            );
            $(me.selector.projectLink).attr(
                'target', '_blank'
            );
        },

        linkSubProject: function () {
            if ($(me.selector.subProjectInput).val() + '' === '') {
                $(me.selector.subProjectLink).attr(
                    'href', '#'
                );
                $(me.selector.subProjectLink).attr(
                    'target', ''
                );
                return;
            }
            $(me.selector.subProjectLink).attr(
                'href',
                'index.php?module=wiedervorlage&action=table&cmd=subprojectlink&subproject='
                + $(me.selector.subProjectInput).val().split(' ')[0]
            );
            $(me.selector.subProjectLink).attr(
                'target', '_blank'
            );
        }
    };

    return {
        init: me.init,
        linkProject: me.linkProject,
        linkSubProject: me.linkSubProject
    };
})(jQuery);

$(document).ready(function () {
    if ($('#editResubmissionTask').length > 0) {
        ResubmissionPopupUi.init();
    }
});
