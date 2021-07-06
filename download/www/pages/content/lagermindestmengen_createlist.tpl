<div id="tabs">
    <ul>
        <li><a href="#tabs-1">Min-/Maxmengen</a></li>
        <li><a href="#tabs-2">CSV Import</a></li>
    </ul>

    <div id="tabs-1">
        <div class="lagermindestmengen">
            <fieldset>
                <legend>{|Anlegen|}</legend>
                <form action="" method="post" onsubmit="return lagermindesrmengenSave(this);">
                    <table>
                        <tr>
                            <td><label for="artikel">{|Artikel|}:</label></td>
                            <td><input type="text" name="artikel" id="artikel" size="20"></td>

                            <td><label for="lagerplatz">{|Lagerplatz|}:</label></td>
                            <td><input type="text" name="lager_platz" id="lagerplatz" size="20"></td>

<!--                            <td>Projekt / Filiale:</td>
                            <td><input type="text" name="projekt" id="projekt" size="30"></td>
-->
                            <td><label for="min_menge">{|Mindestmenge|}:</label></td>
                            <td><input type="text" name="menge" id="menge" size="8"></td>
                            <td><label for="max_menge">{|Maximalmenge|}:</label></td>
                            <td><input type="text" name="max_menge" id="max_menge" size="8"></td>
                            <td>
                                <input type="submit" name="speichern" value="{|Speichern|}">
                            </td>
                        </tr>
                    </table>
                </form>
            </fieldset>
            <div class="lagermindesrmengen_tabelle">
                [TABELLE]
            </div>
        </div>
    </div>


    <div id="tabs-2">
        <fieldset>
            <legend>{|CSV DATEI HOCHLADEN|}</legend>
            <form action="#" method="post" name="lagerminmaxcsv" enctype="multipart/form-data">
                <input type="file" name="lagerminmaxdatei">
                <input type="submit" name="lagerminmaxspeichern" value="Importieren">
                <br />
                <table>
                    <tr>
                        <td width="100">Kodierung: </td><td>UTF-8</td><td></td>
                    </tr>
                    <tr>
                        <td>Format: </td><td width="300">"artikelnummer";"lagerplatz";"mindestmenge";"maximalmenge";</td><td><i>(bei "lagerplatz" den Regalnamen angeben)</i></td>
                    </tr>
                </table>
                <br />
                <table>
                    <tr>
                        <td>Alle bestehenden Lagermindest- und maximalmengen vorher löschen:</td><td><input type="checkbox" name="lagerminmaxloeschen" id="lagerminmaxloeschen"></td><td></td>
                    </tr>
                </table>
            </form>
        </fieldset>
    </div>



</div>

<div id="editLagermindestmengen" style="display: none;">
    <input type="hidden" name="id" value="" id="edit_id">
    <table width="100%">
        <tr>
            <td><label for="edit_menge">{|Mindestmenge|}:</label></td>
            <td>
                <input type="text" id="edit_menge" value="">
            </td>
        </tr>
        <tr>
            <td><label for="edit_max_menge">{|Maximalmenge|}:</label></td>
            <td>
                <input type="text" id="edit_max_menge" value="">
            </td>
        </tr>
    </table>
</div>

<script type="text/javascript">

$(document).ready(function() {
    $('#artikel').focus();
});

$(document).ready(function() {

    $("#editLagermindestmengen").dialog({
    modal: true,
    bgiframe: true,
    closeOnEscape:false,
    autoOpen: false,
    buttons: {
      ABBRECHEN: function() {
        $(this).dialog('close');
      },
      SPEICHERN: function() {
        lagermindestmengenEditSave();
      }
    }
  });

});

function updateLiveTable(i) {
    var oTableL = $('#lagermindestmengen_createlist').dataTable();
    oTableL.fnFilter('%');
    oTableL.fnFilter('');
    //oTableL.fnFilterClear();
}

function lagermindesrmengenSave(formular) {
    var formularDatas = $(formular).serialize();
    $.ajax({
        url: 'index.php?module=lagermindestmengen&action=create',
        data: formularDatas,
                method: 'post',
        dataType: 'json',
        beforeSend: function() {
            App.loading.open();
        },
        success: function(data) {
            if (data.status == 1) {
                updateLiveTable();
                $(formular).find('input[type="text"]').val('');
            } else {
                alert(data.statusText);
            }
            App.loading.close();
        }
    });
    return false;
}

function lagermindestmengenDelete(lagermindestmengenId) {

    var loeschen = confirm('Wirklich löschen?');
    if (loeschen) {
        $.ajax({
            url: 'index.php?module=lagermindestmengen&action=delete',
            data: {
                id: lagermindestmengenId
            },
            dataType: 'json',
            beforeSend: function() {
                App.loading.open();
            },
            success: function(data) {
                updateLiveTable();
                App.loading.close();
            }
        });
    }
    return false;
}

function lagermindestmengenEdit(lagermindestmengenId) {
    $.ajax({
        url: 'index.php?module=lagermindestmengen&action=edit',
        data: {
            id: lagermindestmengenId
        },
        dataType: 'json',
        beforeSend: function() {
            App.loading.open();
        },
        success: function(data) {

            $('#editLagermindestmengen').find('#edit_id').val(data.id);
            $('#editLagermindestmengen').find('#edit_menge').val(data.menge);
            $('#editLagermindestmengen').find('#edit_max_menge').val(data.max_menge);
            $('#editLagermindestmengen').dialog('open');
            App.loading.close();
        }
    });
}

function lagermindestmengenEditSave() {

    var id = $('#editLagermindestmengen').find('#edit_id').val();
    var menge = $('#editLagermindestmengen').find('#edit_menge').val();
    var max_menge = $('#editLagermindestmengen').find('#edit_max_menge').val();
    $.ajax({
        url: 'index.php?module=lagermindestmengen&action=editsave',
        data: {
            id: id,
            menge: menge,
            max_menge: max_menge
        },
        dataType: 'json',
        beforeSend: function() {
            App.loading.open();
        },
        success: function(data) {
            if (data.status == 1) {
                updateLiveTable();
                $('#editLagermindestmengen').dialog('close');
            } else {
                alert(data.statusText);
            }
            App.loading.close();
        }
    });

}

</script>
