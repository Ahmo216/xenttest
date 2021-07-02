
<style>

#lagermobilwrapper input[type="button"] {
    box-sizing: border-box;
    height: 163px;
    width: 100%;
    border-radius: 0;
    margin: 0;
    font-size: 15px;

}

#lagermobilwrapper > a {
	width: 50%;
	float: left;
	padding: 5px;
	box-sizing: border-box;
}
@media only screen and (min-width: 600px) {
	#lagermobilwrapper input {
		font-size: 17px !important;
		font-weight: bold;
	}
}

</style>


<div id="lagermobilwrapper">
	[VOREINLAGERN]<a href="index.php?module=lagermobil&action=stockin&id=[ID]"><input type="button" value="{|Artikel einlagern|}" /></a>[NACHEINLAGERN]
	[VORAUSLAGERN]<a href="index.php?module=lagermobil&action=stockout&id=[ID]"><input type="button" value="{|Artikel auslagern|}" /></a>[NACHAUSLAGERN]
	[VORSTOCK]<a href="index.php?module=lagermobil&action=stock&id=[ID]"><input type="button" value="{|Lagerinhalt|}" /></a>[NACHSTOCK]
	[VORARTIKELUMLAGERN]<a href="index.php?module=lagermobil&action=umlagern&id=[ID]"><input type="button" value="{|Artikel umlagern|}" /></a>[NACHARTIKELUMLAGERN]
	[VORUMLAGERN]<a href="index.php?module=lagermobil&action=lpumlagern&id=[ID]"><input type="button" value="{|Lagerplatz umlagern|}" /></a>[NACHUMLAGERN]
	[VORSCHNELLEINLAGERN]<a href="index.php?module=lagermobil&action=schnell_einlagern&id=[ID]"><input type="button" value="{|Schnell-Einlagern|}" /></a>[NACHSCHNELLEINLAGERN]
	[VORSCHNELLAUSLAGERN]<a href="index.php?module=lagermobil&action=schnell_auslagern&id=[ID]"><input type="button" value="{|Schnell-Auslagern|}" /></a>[NACHSCHNELLAUSLAGERN]
	[VORINTERMEDIATE]<a href="index.php?module=lagermobil&action=intermediate&id=[ID]"><input type="button" value="{|Zwischenlager Umlagern|}" /></a>[NACHINTERMEDIATE]
	[HOOK_MAINMENU]
</div>
