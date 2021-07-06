
<style>

.auftraginfo_cell {
  color: #636363;border: 1px solid #ccc;padding: 5px; 
}

.auftrag_cell {
  color: #636363;border: 1px solid #fff;padding: 0px; margin:0px;
}

</style>


<div style="float:left; width:49%; padding-right:1%;">


<br><center><select data-id="[ID]" id="actionmenu"><option>{|bitte w&auml;hlen ...|}</option>[MENU]</select></center>
<br>


<table style="font-size: 8pt; background: white; color: #333333; border-collapse: collapse;" width="100%" cellspacing="10" cellpadding="10">
<tr><td class="auftraginfo_cell">Bezeichnung:</td><td colspan="4" class="auftraginfo_cell">[NAME]</td></tr>
<tr><td class="auftraginfo_cell" colspan="4" width="50%"><b>Allgemein</b></td></tr>
<tr><td class="auftraginfo_cell">Status:</td><td class="auftraginfo_cell">[STATUS]</td></tr>
<tr><td class="auftraginfo_cell">Projekt:</td><td class="auftraginfo_cell">[PROJEKT]</td></tr>
</table>

</div>
<div style="float:left; width:50%">

<div style="overflow:auto;max-height:550px;">
<div style="background-color:white;">
<h2 class="greyh2">Artikel</h2>
<div style="padding:10px;">
 [ARTICLES]
</div>
</div>

[MINIDETAILNACHARTIKEL]


<div style="background-color:white">
<h2 class="greyh2">Protokoll</h2>
<div style="padding:10px;">
  [PROTOKOLL]
</div>
</div>


<div style="background-color:white">
<h2 class="greyh2">PDF-Archiv</h2>
<div style="padding:10px;overflow:auto;">
  [PDFARCHIV]
</div>
</div>

[INTERNEBEMERKUNGEDIT]

</div>
</div>
