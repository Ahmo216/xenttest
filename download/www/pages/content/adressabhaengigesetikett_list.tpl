<!-- gehort zu tabview -->
<div id="tabs">
    <ul>
        <li><a href="#tabs-1">[TABTEXT]</a></li>
    </ul>
<!-- ende gehort zu tabview -->

<!-- erstes tab -->
<div id="tabs-1">
<form method="post">
  [MESSAGE]
</form>
  
  [TAB1]
  
  [TAB1NEXT]
</div>

<!-- tab view schließen -->
</div>

<div id="editAdressetiketten" style="display:none;" title="Bearbeiten">
<form method="post">
  <input type="hidden" id="e_id">
  <fieldset>
  	<legend>{|Adressetikett|}</legend>
  	<table>
      <tr>
        <td width="150">{|Adresse|}:</td>
        <td><input type="text" name="e_adresse" id="e_adresse" size="40"></td>
      </tr>
      <tr>
        <td>{|Etikett|}:</td>
        <td><input type="text" name="e_etikett" id="e_etikett" size="40"></td>
      </tr>
      <tr>
        <td>{|Verwenden als|}:</td>
        <td><select name="e_verwenden_als" id="e_verwenden_als">
            <option value=""></option>
            <option value="artikel_klein">Artikel klein</option>
            <option value="lagerplatz_klein">Lager klein</option>
            <option value="etikettendrucker_einfach">Etikettendrucker 2-zeilig</option>
            <option value="kommissionieraufkleber">Kommissionieraufkleber</option>
            <option value="seriennummer">Seriennummer</option>
            <option value="spedition">Spedition Master / Palette</option>
            <option value="speditionslave">Spedition Slave / Packst&uuml;ck</option>
            <option value="lieferschein_position">Lieferschein Position</option>
            <option value="multiorder_artikel">Multiorder Picking Artikel</option>
            <option value="multiorder_lieferschein">Multiorder Picking Lieferschein</option>
            <option value="multiorder_trenner">Multiorder Picking Trenner</option>
            </select></td>
      </tr>
    </table>
  </fieldset>    
</div>
</form>


<script type="text/javascript">



</script>


