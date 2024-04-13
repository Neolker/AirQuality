<?if(getget('m','')=='promenna-ulozena'){?><div class="m-wrap bg-light-green"> <em class="fa fa-check-circle"></em> Proměnná úspěšně uložena.</div><?}?>
<?if(getget('m','')=='promenna-jiz-existuje'){?><div class="m-wrap bg-light-red"> <em class="fa fa-exclamation-triangle"></em> Uložení proměnné se nezdařilo. Proměnná se stejným klíčem již existuje nebo byl zadaný klíč prázdný či neplatný.</div><?}?>

<div class="wrap bg-cream main-wrap"> 
	<div class="row">       
	  <div class="col-xs-12 col-md-6 col-sm-8 pad-0 gap-0 align-left">
		  <h1 class="gap-0"><fa class="fa fa-list-check"></fa> &nbsp; Proměnné pro nastavení - <?=$promenna->nazev?></h1>
	  </div>
	  <div class="col-xs-12 col-md-6 col-sm-4 pad-0 gap-0 align-right">
	  	<a href="<?=Anchor(array('modul'=>'aPromenneNastaveni','akce'=>'vypis-promennych'));?>" class="button btn-fill btn-blue isAjax" >
	  		<fa class="fa fa-chevron-circle-left"></fa> &nbsp; Zpět na výpis proměnných
	  	</a>
	  </div>
  </div> 
	<br />
	<form method="post" action="<?=Anchor(array('modul'=>'aPromenneNastaveni','akce'=>'uloz-promennou','nid'=>$promenna->nid));?>" class="isAjaxForm" <?/*onsubmit="return confirm('Opravdu si přejete uložit ...?');"*/?> >
		<div class="row middle-xs">
				<div class="col-md-1 col-sm-2 col-xs-4 pad-y-8 align-right bold" >Název:</div>
				<div class="col-md-3 col-sm-4 col-xs-8 pad-y-8" ><input type="text" name="nazev" maxlength="256" value="<?=$promenna->nazev?>" class="w-100" placeholder="např.: Jméno projektu" required /></div>
				<div class="col-md-1 col-sm-2 col-xs-4 pad-y-8 align-right bold" >Klíč:</div>
				<div class="col-md-3 col-sm-4 col-xs-8 pad-y-8" ><input type="text" name="klic" maxlength="128" value="<?=$promenna->klic?>" class="w-100" placeholder="např.: jmeno_projektu" required /></div>
				<div class="col-md-1 col-sm-2 col-xs-4 pad-y-8 align-right bold" >Typ:</div>
				<div class="col-md-3 col-sm-4 col-xs-8 pad-y-8" >
					<select name="typ" class="w-100">
						<option value="0" <?if(0==$promenna->typ){?>selected<?}?> >Celé číslo</option>
						<option value="1" <?if(1==$promenna->typ){?>selected<?}?> >Desetinné číslo</option>
						<option value="2" <?if(2==$promenna->typ){?>selected<?}?> >Ano / Ne</option>
						<option value="3" <?if(3==$promenna->typ){?>selected<?}?> >Krátký text</option>
						<option value="4" <?if(4==$promenna->typ){?>selected<?}?> >Dlouhý text</option>
						<option value="5" <?if(5==$promenna->typ){?>selected<?}?> >Text s formátováním</option>						
					</select>				
				</div>	
				<div class="col-md-1 col-sm-2 col-xs-4 pad-y-8 align-right bold" >Zobrazovat:</div>
				<div class="col-md-11 col-sm-4 col-xs-8 pad-y-8" >
					<select name="zobrazovat" class="w-100">
						<option value="1" <?if(1==$promenna->zobrazovat){?>selected<?}?> >Ano, administrátoři tuto proměnnou uvidí a budou ji upravovat</option>
						<option value="0" <?if(0==$promenna->zobrazovat){?>selected<?}?> >Ne,  administrátoři tuto proměnnou neuvidí a nebudou ji upravovat</option>
					</select>
				</div>																												
			</div>
			<div class="row">
				<div class="col-md-1 col-sm-2 col-xs-4 pad-y-8 align-right bold" >&nbsp;<br />Popis:</div>
				<div class="col-md-11 col-sm-10 col-xs-8 pad-y-8" ><textarea name="popis" class="w-100" rows="3" placeholder="např. Jméno projektu se zobrazuje ve zdrojovém kódu webu." required ><?=$promenna->popis?></textarea></div>
			</div>
			<div class="row">
				<div class="col-md-1 col-sm-2 col-xs-4 pad-y-8 align-right bold" >&nbsp;<br />Hodnota<?if($promenna->typ==2){echo ' (boolean)';}?>:</div>
				<div class="col-md-11 col-sm-10 col-xs-8 pad-y-8" ><textarea name="hodnota" class="w-100" rows="3" placeholder="např. MHMCUBE"><?=$promenna->hodnota?></textarea></div>
			</div>
			<div class="row middle-xs">							
				<div class="col-md-9 col-sm-8 col-xs-4 pad-y-8 bold" >&nbsp;</div>										
				<div class="col-md-3 col-sm-4 col-xs-8 align-right " ><button type="submit" class="btn-fill btn-blue w-100"><em class="fa fa-floppy-disk"></em> &nbsp; Uložit proměnnou</button></div>				
			</div>										
	</form>
	<br />									
	<br />
</div>
