<?if(getget('m','')=='zarizeni-ulozeno'){?><div class="m-wrap bg-light-green"> <em class="fa fa-check-circle"></em> Zařízení úspěšně uloženo.</div><?}?>
<?if(getget('m','')=='zarizeni-neulozeno'){?><div class="m-wrap bg-light-red"> <em class="fa fa-exclamation-triangle"></em> Zařízení se nepodařilo uložit. Musíte vyplnit všechny povinné údaje. Výrobní číslo nemůže být použito stejné u více zařízení.</div><?}?>
<div class="wrap bg-cream main-wrap"> 
	<div class="row">       
	  <div class="col-xs-12 col-md-6 col-sm-8 pad-0 gap-0 align-left">
		  <h1 class="gap-0"><fa class="fa fa-microchip"></fa> &nbsp; Zařízení - Editace zařízení <?=$zarizeni->vyrobni_cislo;?></h1>
	  </div>
	  <div class="col-xs-12 col-md-6 col-sm-4 pad-0 gap-0 align-right">
	  	<a href="<?=Anchor(array('modul'=>'aZarizeni','akce'=>'vypis-zarizeni','stranka'=>$stranka));?>" class="button btn-fill btn-blue isAjax" >
	  		<fa class="fa fa-chevron-circle-left"></fa> &nbsp; Zpět na výpis zařízení
	  	</a>
	  </div>
  </div>
	<br />
	<form method="post" action="<?=Anchor(array('modul'=>'aZarizeni','akce'=>'uloz-zarizeni','zid'=>$zarizeni->zid,'stranka'=>$stranka));?>" class="isAjaxForm" <?/*onsubmit="return confirm('Opravdu si přejete uložit údaje?');"*/?> >
		<div class="row middle-xs">
			<div class="col-md-1 col-sm-2 col-xs-4 pad-y-8 align-right bold" >*Výrobní číslo:</div>
			<div class="col-md-3 col-sm-4 col-xs-8 pad-y-8" ><input type="text" name="vyrobni_cislo" maxlength="256" value="<?=$zarizeni->vyrobni_cislo?>" class="w-100" required placeholder="např. 1234-5678-9ABC-DEF0-1234" /></div>
			<div class="col-md-1 col-sm-2 col-xs-4 pad-y-8 align-right bold" >*Název:</div>
			<div class="col-md-3 col-sm-4 col-xs-8 pad-y-8" ><input type="text" name="nazev" maxlength="256" value="<?=$zarizeni->nazev?>" class="w-100" required placeholder="např. Můj detektor" /></div>
			<div class="col-md-1 col-sm-2 col-xs-4 pad-y-8 align-right bold" >*Uživatel:</div>
			<div class="col-md-3 col-sm-4 col-xs-8 pad-y-8" >
				<select name="id_uzivatele" class="w-100">					
					<?foreach($uzivatele as $uk=>$uv){?>
						<option value="<?=$uk;?>" <?if($uk==$zarizeni->id_uzivatele){?>selected<?}?> ><?=$uv;?></option>
					<?}?>						
				</select>
			</div>																			
			<div class="col-md-1 col-sm-2 col-xs-4 pad-y-8 align-right bold" >Limit zelené:</div>
			<div class="col-md-3 col-sm-4 col-xs-8 pad-y-8" ><input type="number" name="nastaveni_co2_zelena" step="1" min="0" max="100000" value="<?=$zarizeni->nastaveni_co2_zelena?>" class="w-100" placeholder="např. 0" /></div>						
			<div class="col-md-1 col-sm-2 col-xs-4 pad-y-8 align-right bold" >Limit žluté:</div>
			<div class="col-md-3 col-sm-4 col-xs-8 pad-y-8" ><input type="number" name="nastaveni_co2_zluta" step="1"  min="0" max="100000" value="<?=$zarizeni->nastaveni_co2_zluta?>" class="w-100" placeholder="např. 1000" /></div>					
			<div class="col-md-1 col-sm-2 col-xs-4 pad-y-8 align-right bold" >Limit červené:</div>
			<div class="col-md-3 col-sm-4 col-xs-8 pad-y-8" ><input type="number" name="nastaveni_co2_cervena" step="1"  min="0" max="100000" value="<?=$zarizeni->nastaveni_co2_cervena?>" class="w-100" placeholder="např. 1500" /></div>
			<div class="col-md-1 col-sm-2 col-xs-4 pad-y-8 align-right bold" >Lokalita:</div>
			<div class="col-md-3 col-sm-4 col-xs-8 pad-y-8" ><input type="text" name="lokalita" maxlength="256" value="<?=$zarizeni->lokalita?>" class="w-100" placeholder="např. chata, obývák, apod." /></div>
			<div class="col-md-5 col-sm-2 col-xs-4 pad-y-8 bold" >&nbsp;</div>										
			<div class="col-md-3 col-sm-4 col-xs-8 align-right " ><button type="submit" class="btn-fill btn-blue w-100"><em class="fa fa-floppy-disk"></em> &nbsp; Uložit zařízení</button></div>				
		</div>
	</form>		
	<br />	
	<p><i>* Vyplňte prosím všechny povinné údaje označené hvězdičkou (*). Limity pro&nbsp;rozsvícení barev do&nbsp;semaforu CO2 zadávejte v&nbsp;ppm.</i></p>
	<br />
	<br />
</div>
