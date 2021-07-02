<!-- gehort zu tabview -->


<style>
tfoot {
    display: table-header-group;
    position:absolute;
    top:-42px;
    left:-2px;
    border:0px;
}   

 table.display tfoot th {
    border-top: 0px solid #DDD;
    border:0px; 
}   

</style>
<script>
$( document ).ready(function() {
$("#chargen_produktion > tfoot:nth-child(3) > tr:nth-child(1) > th:nth-child(7) > span:nth-child(1) > input:nth-child(1)").hide();
  $("#produktionszentrum_chargen_baugruppen2 > tfoot:nth-child(3) > tr:nth-child(1) > th:nth-child(7) > span:nth-child(1) > input:nth-child(1)").hide();
});
</script>

<div id="tabs">
 <ul>
   <li><a href="#tabs-1">{|Eingesetzte Chargen|}</a></li>
   <li><a href="#tabs-2">{|Produzierte Chargen|}</a></li>
 </ul>
<!-- ende gehort zu tabview -->

<!-- erstes tab -->
  <div id="tabs-1">
    [MESSAGE]

    <fieldset>
      <legend>{|Filter|}</legend>
      <table>
        <tr>
          <td height="25px"></td>
        </tr>
      </table>
    </fieldset>


    [TAB1]
    [TAB1NEXT]
  </div>
  <div id="tabs-2">
    <fieldset>
      <legend>{|Filter|}</legend>
      <table>
        <tr>
          <td height="25px"></td>
        </tr>
      </table>
    </fieldset>
    [MESSAGE]
    [TAB2]
    [TAB2NEXT]
  </div>
<!-- tab view schließen -->
</div>
