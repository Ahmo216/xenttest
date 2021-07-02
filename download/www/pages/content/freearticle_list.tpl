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
			[TAB1]
			[TAB1NEXT]
		</form>
	</div>

	<!-- tab view schließen -->
</div>

<div id="editFreeArticle" style="display:none;" title="Bearbeiten">
	<div class="row">
		<div class="row-height">
			<div class="col-xs-12 col-md-12 col-md-height">
				<div class="inside inside-full-height">
					<form method="post">
						<input type="hidden" id="free_article_entry_id">
						<fieldset>
							<legend>{|Kostenloser Artikel|}</legend>
							<table>
								<tr>
									<td width="150">{|Artikel|}:</td>
									<td><input type="text" name="free_article_article" id="free_article_article" size="40"></td>
								</tr>
								<tr>
									<td width="150">{|Projekt|}:</td>
									<td><input type="text" name="free_article_project" id="free_article_project" size="40"></td>
								</tr>
								<tr>
									<td>{|Menge|}:</td>
									<td><input type="text" name="free_article_amount" id="free_article_amount"></td>
								</tr>
								<tr>
									<td>{|Hinzuf&uuml;gen bei|}:</td>
									<td><select name="free_article_condition" id="free_article_condition" onchange="changeVisibiltyOfStockOption()">
											<option value="never">Nie</option>
											<option value="everyone">Jeder Auftrag</option>
											<option value="new_customer">Neukunden-Aufträge</option>
										</select></td>
								</tr>
								<tr id="free_article_stock_option">
									<td>{|Solange Vorrat reicht|}:</td>
									<td><input type="checkbox" name="free_article_while_stocks_last" id="free_article_while_stocks_last"></td>
								</tr>
							</table>
						</fieldset>
					</form>
				</div>
			</div>
		</div>
	</div>
</div>

