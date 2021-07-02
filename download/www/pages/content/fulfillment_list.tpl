<!-- gehort zu tabview -->
<div id="tabs">
    <ul>
        <li><a href="#tabs-1">[TABTEXT]</a></li>
    </ul>
<!-- ende gehort zu tabview -->

<!-- erstes tab -->
<div id="tabs-1">

  [MESSAGE]
    


  <div class="row">
  <div class="row-height">
  <div class="col-xs-12 col-md-5 col-md-height">
  <div class="inside inside-full-height">
    <fieldset>
      <legend>{|Was kann Xentral im Bereich Fulfillment?|}</legend>
      <p>Die Anbindung an einen Fulfillment Dienstleister ermöglicht das vollständige Outsourcing des Lagers und der Logistik. Das ist daher möglich da Xentral die Übertragung von XML Strukturen an einen Fulfiller und im Anschluss das Einlesen der vom Fulfiller zurückgemeldeten Daten wie z.B. Trackingnummern, Lagerbestände, Mindesthaltbarkeitsdaten etc. ermöglicht.</p>
  <p>Generell ist es möglich Daten als Datei auf einem Server abzulegen. Diese Daten werden dann vom Fulfiller abgeholt und weiterverarbeitet. Der Fulfiller meldet im Anschluss z.B. 1x Nachts die Trackingnummern, aktualisierte Lagerbestände und z.B. auch MHDs an den Server zurück. Diese Daten werden dann über einen Cronjob bzw. Prozessstarter von Xentral eingelesen und aktualisiert. Eine Rückmeldung an Shops zur Bestandsaktualisierung oder die Trackingnummer an den Shop als gleichzeitigen Trigger für eine Rechnungs-E-Mail sind parallel möglich.</p>
<p>Aus Xentral kann eine automatische Übertragung der Belege, wie Lieferscheine oder Aufträge, via XML-Datei zum Versanddienstleister hin, eingerichtet werden. Dabei kann die Übertragung via FTP, FTPS, Email oder in ein lokales Verzeichnis erfolgen.
</p>
<p>Bei Fragen können Sie gerne unseren Vertrieb kontaktieren. Gerne beraten oder helfen wir bei der Auswahl und Anbindung eines Fulfillment-Anbieters der bereits mit Xentral arbeitet.</p>
<br><br><i>P.S. Auch Fulfillment-Dienstleister können Xentral nutzen um wiederum die Dienstleistung für Kunden anbieten zu können</i>
    </fieldset>
        
  </div>
  </div>
  <div class="col-xs-12 col-md-5 col-md-height">
  <div class="inside inside-full-height">
    <fieldset>
      <legend>{|Funktionen|}</legend>
      <table class="mkTable">
        <thead>
        <tr>
          <th>{|Funktion|}</th>
          <th>{|Xentral zu Fulfillment|}</th>
          <th>{|Fulfillment zu Xentral|}</th>
        </tr>
        </thead>
        <tbody>
        <tr>
          <td>{|Belege &uuml;bertragen als XML, CSV oder PDF (Auftr&auml;ge, Lieferscheine, ...)|}</td>
          <td><center><img src="./themes/new/images/ok.png" border="0"></center></td>
          <td><center><img src="./themes/new/images/ok.png" border="0"></center></td>
        </tr>
        <tr>
          <td>{|Automatischer Lagerzahlenabgleich|}</td>
          <td><center><img src="./themes/new/images/ok.png" border="0"></center></td>
          <td><center><img src="./themes/new/images/ok.png" border="0"></center></td>
        </tr>
        <tr>
          <td>{|Artikel &uuml;bertragen|}</td>
          <td><center><img src="./themes/new/images/ok.png" border="0"></center></td>
          <td><center><img src="./themes/new/images/ok.png" border="0"></center></td>
        </tr>
        <tr>
          <td>{|Trackingnummern zurückmelden|}</td>
          <td><center><img src="./themes/new/images/ok.png" border="0"></center></td>
          <td><center><img src="./themes/new/images/ok.png" border="0"></center></td>
        </tr>
        <tr>
          <td>{|Versandmail an Kunden ausl&ouml;sen (inkl. Rechnung optional)|}</td>
          <td><center><img src="./themes/new/images/ok.png" border="0"></center></td>
          <td><center><img src="./themes/new/images/ok.png" border="0"></center></td>
        </tr>
        <tr>
          <td>{|Automatische Shop- bzw. Marktplatzr&uuml;ckmeldung|}</td>
          <td><center><img src="./themes/new/images/ok.png" border="0"></center></td>
          <td><center><img src="./themes/new/images/ok.png" border="0"></center></td>
        </tr>
        <tr>
          <td>{|Dropshipping (Aufteilung in Teilauftr&auml;ge)|}</td>
          <td><center><img src="./themes/new/images/ok.png" border="0"></center></td>
          <td><center><img src="./themes/new/images/ok.png" border="0"></center></td>
        </tr>
        <tr>
          <td>{|Dateianhang übertragen (für z.B. kundenspezifische Inhalte) (in Auftr&auml;gen, Lieferscheinen, ...)|}</td>
          <td><center><img src="./themes/new/images/ok.png" border="0"></center></td>
          <td><center><img src="./themes/new/images/ok.png" border="0"></center></td>
        </tr>
        <tr>
          <td>{|Preise f&uuml;r Zoll mit &uuml;bergeben|}</td>
          <td><center><img src="./themes/new/images/ok.png" border="0"></center></td>
          <td><center><img src="./themes/new/images/ok.png" border="0"></center></td>
        </tr>
        </tbody>
      </table>
    </fieldset>
  </div>
  </div>

  <div class="col-xs-12 col-md-2 col-md-height">
  <div class="inside inside-full-height">
    <fieldset>
      <legend>{|Aktionen|}</legend>
      <input type="button" class="btnGreenNew" value="Video zu Fulfillment" onclick="window.open('https://www.youtube.com/watch?v=IJTCSb2sXi0&feature=youtu.be','_blank');">
      <input type="button" class="btnBlueNew" value="Spezifikation" onclick="window.open('https://xentral.biz/helpdesk/fulfillment', '_blank');">
      <input type="button" class="btnBlueNew" value="Zur Anleitung" onclick="window.open('https://xentral.biz/helpdesk/edi-xml-pdf', '_blank');">
      <input type="button" class="btnBlueNew" name="uebertragenmodul" id="uebertragenmodul" value="Zum &Uuml;bertragen Modul" onclick="window.location.href='index.php?module=uebertragungen&action=list'">
    </fieldset>
  </div>
  </div>
  </div>
  </div>  

  <div class="row">
  <div class="row-height">
  <div class="col-xs-12 col-md-12 col-md-height">
  <div class="inside inside-full-height">
    <fieldset>
    <legend>{|Anbieter mit einer Anbindung an Xentral|}</legend>
    <table class="mkTable">
      <tr>
        <td></td>
        <td>{|Dienstleister|}</td>
        <td>{|Ansprechpartner|}</td>
        <td>{|Ort|}</td>
        <td>{|Telefon|}</td>
        <td>{|E-Mail|}</td>
        <td>{|Webseite|}</td>
      </tr>
      <tr>
        <td><input type="checkbox" name="email" value="flh@trendcom.biz"></td>
        <td>Versandmanufaktur GmbH</td>
        <td>Florian Hammermeister</td>
        <td>Witten</td>
        <td>+49 234 6060060</td>
        <td><a href="mailto:flh@trendcom.biz">flh@trendcom.biz</a></td>
        <td><a href="https://www.versandmanufaktur.de/" target="_blank">Zur Homepage</a></td>
      </tr>
      <tr>
        <td><input type="checkbox" name="email" value="service@prochannel.de"></td>
        <td>ProChannel GmbH</td>
        <td>Michael Storch</td>
        <td>Augsburg</td>
        <td></td>
        <td><a href="mailto:service@prochannel.de" target="_blank">service@prochannel.de</a></td>
        <td><a href="https://www.prochannel.de/" target="_blank">Zur Homepage</a></td>
      </tr>
    </table>
    <br><br>
    <div class="warning">Gutschein: Wenn Sie mit einem der hier aufgelisteten Partner in Zukunft arbeiten möchten, geben Sie bitte unserem Vertrieb Rückmeldung. Sie erhalten 25% Rabatt auf die Schnittstellen-Module inkl. Einrichtungspakete von Xentral.</div>
    <br><br>
    <input type="button" class="btnBlue" name="anfrage" id="anfrage" value="Anfrage senden" style="float:right" onclick="Anfragesenden();">
  </fieldset>

  </div>
  </div>
  </div>
  </div>  



  [TAB1]
  
  [TAB1NEXT]
</div>

<!-- tab view schließen -->
</div>



<div id="editMail" style="display:none;" title="Bearbeiten">
  <input type="hidden" id="e_mid">
  <fieldset>
    <legend>{|Mail|}</legend>
    <table>
      <tr>
        <td>{|Betreff|}:</td>
        <td><input type="text" name="e_betreff" id="e_betreff" size="80"></td>
      </tr>
      <tr>
        <td>{|Nachricht|}:</td>
        <td><textarea name="e_mailtext" id="e_mailtext" rows="10" cols="10"></textarea></td>
      </tr>
    </table>    
  </fieldset>
</div>


<script type="text/javascript">

  $(document).ready(function() {
    $('#e_betreff').focus();

    $('#editMail').dialog({
      modal: true,
      bgiframe: true,
      closeOnEscape:false,
      minWidth:700,
      autoOpen: false,
      buttons: {
        ABBRECHEN: function(){
          MailReset();
          $(this).dialog('close');
        },
        SENDEN: function(){
          AnfragesendenSave();
        }
      }
    });

    $("#editMail").dialog({
      close: function( event, ui ) { MailReset();}
    });
    
  });

  function MailReset(){
    $('#editMail').find('#e_betreff').val('Anfrage bzgl. Fulfillment mit Software Xentral');
    $('#editMail').find('#e_mailtext').val('');
  }

  function Anfragesenden(){
    MailReset();

    empfaenger = [];
    $("input:checkbox[name=email]:checked").each(function(){
      empfaenger.push($(this).val());
    });

    if(empfaenger <= 0){
      alert("Bitte mindestens einen Anbieter auswählen");
    }else{
      $('#editMail').dialog('open');
    }
    
  }

  function AnfragesendenSave(){

    empfaenger = [];
    var checkboxen = $(":input[id^='cb']:checked");
    $("input:checkbox[name=email]:checked").each(function(){
      empfaenger.push($(this).val());
    });

    var $dialog = $('#editMail').parent();
    $.ajax({
      url: 'index.php?module=fulfillment&action=mail&cmd=senden',
      data: {
        //Alle Felder die fürs editieren vorhanden sind
        betreff: $('#e_betreff').val(),
        mailtext: $('#e_mailtext').val(),
        empfaenger: empfaenger

      },
      method: 'post',
      dataType: 'json',
      beforeSend: function() {
        $dialog.loadingOverlay();
      },
      success: function(data) {
        alert(data.statusText);
        MailReset();
        $("#editMail").dialog('close');         
      },
      complete: function () {
        $dialog.loadingOverlay('remove');
      }
    });
    
  }

</script>
