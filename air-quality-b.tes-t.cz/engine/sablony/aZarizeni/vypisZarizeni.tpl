<?if(getget('m','')=='zarizeni-jiz-existuje'){?><div class="m-wrap bg-light-red"> <em class="fa fa-exclamation-triangle"></em> Přidání zařízení se nezdařilo. Výrobní číslo již je použito u jiného zařízení.</div><?}?>
<?if(getget('m','')=='nazev-je-kratky'){?><div class="m-wrap bg-light-red"> <em class="fa fa-exclamation-triangle"></em> Přidání zařízení se nezdařilo. Musíte vyplnit název.</div><?}?>
<?if(getget('m','')=='zadejte-uzivatele'){?><div class="m-wrap bg-light-red"> <em class="fa fa-exclamation-triangle"></em> Přidání zařízení se nezdařilo. Musíte vyplnit uživatele.</div><?}?>
<?if(getget('m','')=='zarizeni-vytvoreno'){?><div class="m-wrap bg-light-green"> <em class="fa fa-check-circle"></em> Zařízení úspěšně přidáno do systému.</div><?}?>
<?if(getget('m','')=='zarizeni-smazano'){?><div class="m-wrap bg-light-green"> <em class="fa fa-check-circle"></em> Zařízení úspěšně smazáno ze systému.</div><?}?>
<?if(getget('m','')=='zarizeni-nenalezeno'){?><div class="m-wrap bg-light-red"> <em class="fa fa-exclamation-triangle"></em> Zařízení nebylo nalezeno v systému.</div><?}?>
<style>
.limit-red{color:darkred;}
.limit-yellow{color:#ab740c;}
.limit-green{color:darkgreen;}
</style>
<div class="wrap bg-cream main-wrap"> 
	<div class="row">       
	  <div class="col-xs-12 col-md-6 col-sm-6 pad-0 gap-0 align-left">
		  <h1 class="gap-0"><fa class="fa fa-microchip"></fa> &nbsp; Zařízení</h1>
	  </div>
	  <div class="col-xs-12 col-md-6 col-sm-6 pad-0 gap-0 align-right">
	  	<?if($prihlasenyUzivatel->prava==2||$prihlasenyUzivatel->prava==3){?>
				<a class="button btn-fill btn-blue" onclick="$('#nove-zarizeni').toggle('slow');" >
					<fa class="fa fa-plus-circle"></fa> &nbsp; Přidat zařízení
				</a>	  		  	
	  	<?}?>
	  </div>
  </div>
  <?if($prihlasenyUzivatel->prava==2||$prihlasenyUzivatel->prava==3){?>
		<div id="nove-zarizeni" <?if(trim($noveZarizeni->vyrobni_cislo)==''){?>style="display:none;"<?}?> >
			<br />
			<div class="row">    
				<div class="col-xs-12 col-md-3 col-sm-3 pad-0 gap-0 align-left"><h2 class="gap-8">Přidání zařízení:</h2></div>
				<div class="col-xs-12 col-md-9 col-sm-9 pad-0 end-sm end-md center-xs"><div class="gap-8"><i>* Vyplňte prosím všechny povinné údaje označené hvězdičkou (*). Limity pro&nbsp;rozsvícení barev do&nbsp;semaforu CO2 zadávejte v&nbsp;ppm.</i></div></div>
			</div>
			<form method="post" action="<?=Anchor(array('modul'=>'aZarizeni','akce'=>'pridej-zarizeni'));?>" class="isAjaxForm">
				<div class="row middle-xs">
					<div class="col-md-1 col-sm-2 col-xs-4 pad-y-8 align-right bold" >*Výrobní číslo:</div>
					<div class="col-md-3 col-sm-4 col-xs-8 pad-y-8" ><input type="text" name="vyrobni_cislo" maxlength="256" value="<?=$noveZarizeni->vyrobni_cislo?>" class="w-100" required placeholder="např. 1234-5678-9ABC-DEF0-1234" /></div>
					<div class="col-md-1 col-sm-2 col-xs-4 pad-y-8 align-right bold" >*Název:</div>
					<div class="col-md-3 col-sm-4 col-xs-8 pad-y-8" ><input type="text" name="nazev" maxlength="256" value="<?=$noveZarizeni->nazev?>" class="w-100" required placeholder="např. Můj detektor" /></div>
					<div class="col-md-1 col-sm-2 col-xs-4 pad-y-8 align-right bold" >*Uživatel:</div>
					<div class="col-md-3 col-sm-4 col-xs-8 pad-y-8" >
						<select name="id_uzivatele" class="w-100">
							<option value="0" <?if(0==$noveZarizeni->id_uzivatele){?>selected<?}?> >- Vyberte uživatele -</option>
							<?foreach($uzivatele as $uk=>$uv){?>
								<option value="<?=$uk;?>" <?if($uk==$noveZarizeni->id_uzivatele){?>selected<?}?> ><?=$uv;?></option>
							<?}?>						
						</select>
					</div>																			
					<div class="col-md-1 col-sm-2 col-xs-4 pad-y-8 align-right bold" >Limit zelené:</div>
					<div class="col-md-3 col-sm-4 col-xs-8 pad-y-8" ><input type="number" name="nastaveni_co2_zelena" step="1" min="0" max="100000" value="<?=$noveZarizeni->nastaveni_co2_zelena?>" class="w-100" placeholder="např. 0" /></div>						
					<div class="col-md-1 col-sm-2 col-xs-4 pad-y-8 align-right bold" >Limit žluté:</div>
					<div class="col-md-3 col-sm-4 col-xs-8 pad-y-8" ><input type="number" name="nastaveni_co2_zluta" step="1"  min="0" max="100000" value="<?=$noveZarizeni->nastaveni_co2_zluta?>" class="w-100" placeholder="např. 1000" /></div>					
					<div class="col-md-1 col-sm-2 col-xs-4 pad-y-8 align-right bold" >Limit červené:</div>
					<div class="col-md-3 col-sm-4 col-xs-8 pad-y-8" ><input type="number" name="nastaveni_co2_cervena" step="1"  min="0" max="100000" value="<?=$noveZarizeni->nastaveni_co2_cervena?>" class="w-100" placeholder="např. 1500" /></div>
					<div class="col-md-1 col-sm-2 col-xs-4 pad-y-8 align-right bold" >Lokalita:</div>
					<div class="col-md-3 col-sm-4 col-xs-8 pad-y-8" ><input type="text" name="lokalita" maxlength="256" value="<?=$noveZarizeni->lokalita?>" class="w-100" placeholder="např. chata, obývák, apod." /></div>
					<div class="col-md-5 col-sm-2 col-xs-4 pad-y-8 bold" >&nbsp;</div>										
					<div class="col-md-3 col-sm-4 col-xs-8 align-right " ><button type="submit" class="btn-fill btn-blue w-100"><em class="fa fa-plus-circle"></em> &nbsp; Přidat zařízení</button></div>				
				</div>
			</form>
			<br />
		</div>		
  <?}?> 
  <br />
  <form method="post" action="<?=Anchor(array('modul'=>'aZarizeni','akce'=>'zmena-vyhledavani'));?>" class="isAjaxForm">
		<div class="row middle-xs">  		
			<div class="col-md-3 col-sm-12 col-xs-12 pad-y-8 pad-0 gap-0" ><h2 class="gap-0">Fulltextové vyhledávání:</h2></div>					
			<div class="col-md-2 col-sm-2 col-xs-4 pad-y-8 align-right bold" ><sup>1</sup>Hledaný výraz:</div>
			<div class="col-md-3 col-sm-4 col-xs-8 pad-y-8" ><input type="text" name="fulltext" maxlength="64" value="<?=$fulltext?>" class="w-100" placeholder="např. Můj detektor" /></div>					
			<div class="col-md-1 col-sm-2 col-xs-4 pad-y-8 bold">&nbsp;</div>				
			<div class="col-md-3 col-sm-4 col-xs-8 align-right " ><button type="submit" class="btn-fill btn-blue w-100"><em class="fa fa-magnifying-glass"></em> &nbsp; Vyhledat</button></div>				
		</div>
  </form>
  <?if(trim($fulltext)!=''){?>  		
		<form method="post" action="<?=Anchor(array('modul'=>'aZarizeni','akce'=>'zmena-vyhledavani'));?>" class="isAjaxForm">
			<div class="row middle-xs"> 				
				<div class="col-md-9 col-sm-8 col-xs-4 pad-y-8 bold"> <input type="hidden" name="fulltext" value="" /> </div>				
				<div class="col-md-3 col-sm-4 col-xs-8 align-right " ><button type="submit" class="btn-fill negative w-100"><em class="fa fa-xmark"></em> &nbsp; Zrušit vyhledávání "<?=$fulltext?>"</button></div>
			</div>
		</form>
	<?}?>
  <br />  
  <?if(isset($zarizeni)&&is_array($zarizeni)&&count($zarizeni)>0){?>
		<div class="table-wrap fixed-width">
			<table style="min-width:1000px;width:100%">
				<thead>	
					<tr>
						<th style="border-right:none;">&nbsp;</th>		
						<th><a href="<?=Anchor(array('modul'=>'aZarizeni','akce'=>'zmena-razeni','typ'=>'vyrobni_cislo','stranka'=>$stranka));?>" class="<?=($razeni=='vyrobni_cislo'?'th-sorter-active':'th-sorter');?> isAjax" title="Seřadit dle Výrobního čísla">Výrobní číslo <em class="fa fa-arrow-down-a-z"></em></a></th>
						<th><a href="<?=Anchor(array('modul'=>'aZarizeni','akce'=>'zmena-razeni','typ'=>'nazev','stranka'=>$stranka));?>" class="<?=($razeni=='nazev'?'th-sorter-active':'th-sorter');?> isAjax" title="Seřadit dle Názvu">Název <em class="fa fa-arrow-down-a-z"></em></a></th>					
						<th><a href="<?=Anchor(array('modul'=>'aZarizeni','akce'=>'zmena-razeni','typ'=>'lokalita','stranka'=>$stranka));?>" class="<?=($razeni=='lokalita'?'th-sorter-active':'th-sorter');?> isAjax" title="Seřadit dle Lokality">Lokalita <em class="fa fa-arrow-down-a-z"></a></th>			
						<th>Uživatel zařízení</th>													
						<th colspan="4" class="align-center">CO2 limity pro&nbsp;semafor [ppm]</th>																																												
					</tr>
				</thead>
				<?foreach($zarizeni as $z){?>
					<tr>						
						<td width="50">
							<a href="<?=Anchor(array('modul'=>'aZarizeni','akce'=>'editace-zarizeni','zid'=>$z->zid,'stranka'=>$stranka));?>" class="btn btn-icon btn-blue btn-small btn-fill isAjax" title="Upravit zařízení"><em class="fa fa-pencil fa-1-4x"></em></a>
						</td>					
						<td><?=$z->vyrobni_cislo?></td>
						<td><?=$z->nazev?></td>
						<td><?=$z->lokalita?></td>
						<td><?=$uzivatele[$z->id_uzivatele]?></td>
						<td width="60" class="align-right limit-green"><?=$z->nastaveni_co2_zelena?></td>
						<td width="60" class="align-right limit-yellow"><?=$z->nastaveni_co2_zluta?></td>
						<td width="60" class="align-right limit-red"><?=$z->nastaveni_co2_cervena?></td>																												
						<td width="50">
							<a href="<?=Anchor(array('modul'=>'aZarizeni','akce'=>'smaz-zarizeni','zid'=>$z->zid));?>" class="btn btn-icon btn-small btn-fill negative isAjax" title="Smazat zařízení" onclick="return confirm('Opravdu si přejete smazat zařízení <?=prepareGetDataSafely($z->vyrobni_cislo.' ('.trim($z->nazev.' '.$z->lokalita.' '.$uzivatele[$z->id_uzivatele]).')');?>? Tímto krokem se smaží i veškerá naměřená data na daném zařízení.');"><em class="fa fa-trash fa-1-4x"></em></a>
						</td>															
					</tr>
				<?}?>
			</table>
		</div>				
		<p>			
			<sup>1</sup> Hledaný výraz prohledává výrobní číslo, název, lokalitu zařízení a login jméno příjmení uživatele.<br />
		</p>				
		<br />
		<?=strankovani($strankovac,((int)getget('stranka','0')));?>
  <?}else{?>
  	<h2><em class="fa fa-exclamation-triangle"></em> Žádná zařízení nenalezena, pokud máte zapnuté fulltextové vyhledávání, zkuste hledaný výraz zapsat jinak. </h2>  	
  <?}?>
  <br />  
</div>
