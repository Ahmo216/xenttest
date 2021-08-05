
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
</style>


<div id="lagermobilwrapper">
	[VOREINLAGERN]<a href="index.php?module=lagermobilold&action=einlagern&id=[ID]"><input type="button" value="{|Artikel einlagern|}" /></a>[NACHEINLAGERN]
	[VORAUSLAGERN]<a href="index.php?module=lagermobilold&action=auslagern&id=[ID]"><input type="button" value="{|Artikel auslagern|}" /></a>[NACHAUSLAGERN]
	[VORARTIKELUMLAGERN]<a href="index.php?module=lagermobilold&action=umlagern&id=[ID]"><input type="button" value="{|Artikel umlagern|}" /></a>[NACHARTIKELUMLAGERN]
	[VORUMLAGERN]<a href="index.php?module=lagermobilold&action=lpumlagern&id=[ID]"><input type="button" value="{|Lagerplatz umlagern|}" /></a>[NACHUMLAGERN]
	[VORSCHNELLEINLAGERN]<a href="index.php?module=lagermobilold&action=schnell_einlagern&id=[ID]"><input type="button" value="{|Schnell-Einlagern|}" /></a>[NACHSCHNELLEINLAGERN]
	[VORSCHNELLAUSLAGERN]<a href="index.php?module=lagermobilold&action=schnell_auslagern&id=[ID]"><input type="button" value="{|Schnell-Auslagern|}" /></a>[NACHSCHNELLAUSLAGERN]
</div>


