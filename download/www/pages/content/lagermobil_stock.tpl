<style type="text/css">
/*#lagermobiltabelle {
	max-width:500px;
	width:100%
}
#lagermobiltabellefilter {
	width: 90vw;
	max-width: 490px;
}
@media only screen and (min-width: 600px) {
	#lagermobiltabelle {
		max-width: 1000px;
	}
	#lagermobiltabellefilter, #lagermobil_stock {
		font-size: 15px;
		font-weight: bold;
	}
	#lagermobil_stock td {
		font-size: 15px;
	}
	#lagermobil_stock img {
		width: 30px;
	}
}
@media only screen and (min-width: 800px) {
	#lagermobiltabellefilter, #lagermobil_stock {
		font-size: 16px;
	}
}
#lagermobiltabelle input[type="button"]
{
	width:100%;
	border-radius: 7px;
	font-size:2em;
	min-height:2em;
	cursor:pointer;
}*/
</style>

<div id="tabs" class="mobile-ui">
	 <ul>
		   <li><a href="#tabs-1"></a></li>
	 </ul>

	 <div id="tabs-1">
		   <form method="post" class="form form-horizontal">
				   <div class="form-group form-group-lg">
						   <div class="col-md-6 col-sm-12">
								   <a class="btn btn-block btn-lg btn-secondary" href="index.php?module=lagermobil&action=list">
						  			   zurück zur Übersicht
									 </a>
				       </div>
					 </div>
					 <div class="form-group form-group-lg">
					  	 <label for="storage" class="col-md-1 col-sm-2 control-label">{|Lager:|}</label>
						   <div class="col-md-5 col-sm-10">
								   <div class="input-autocomplete">
						    	     <input type="text" name="storage" id="storage" class="form-control" />
									 </div>
							 </div>
					 </div>
				   <div class="form-group form-group-lg">
					     <div class="col-sm-offset-2 col-sm-10 col-md-offset-1 col-md-5">
									 <input type="submit" name="submit" class="btn btn-primary btn-block btn-lg" value="scannen" />
							 </div>
					 </div>

					 [TAB1]
			 </form>
	 </div>
</div>
