<?if(getget('m','')=='promenna-vytvorena'){?><div class="m-wrap bg-light-green"> <em class="fa fa-check-circle"></em> Proměnná úspěšně přidána do systému.</div><?}?>
<?if(getget('m','')=='promenna-jiz-existuje'){?><div class="m-wrap bg-light-red"> <em class="fa fa-exclamation-triangle"></em> Přidání proměnné se nezdařilo. Proměnná se stejným klíčem již existuje nebo byl zadaný klíč prázdný či neplatný.</div><?}?>
<?if(getget('m','')=='promenna-posunuta'){?><div class="m-wrap bg-light-green"> <em class="fa fa-check-circle"></em> Proměnná úspěšně posunuta.</div><?}?>
<?if(getget('m','')=='promenna-neposunuta'){?><div class="m-wrap bg-light-red"> <em class="fa fa-exclamation-triangle"></em> Posunutí proměnné se nezdařilo.</div><?}?>
<?if(getget('m','')=='promenna-smazana'){?><div class="m-wrap bg-light-green"> <em class="fa fa-check-circle"></em> Proměnná úspěšně smazána ze systému.</div><?}?>
<?if(getget('m','')=='promenna-nesmazana'){?><div class="m-wrap bg-light-red"> <em class="fa fa-exclamation-triangle"></em> Smazání proměnné se nezdařilo.</div><?}?>
<?if(getget('m','')=='promenna-nenalezena'){?><div class="m-wrap bg-light-red"> <em class="fa fa-exclamation-triangle"></em> Proměnná nebyla nalezena v systému.</div><?}?>
<div class="wrap bg-cream main-wrap"> 
	<div class="row">       
	  <div class="col-xs-12 col-md-6 col-sm-6 pad-0 gap-0 align-left">
		  <h1 class="gap-0"><fa class="fa fa-list-check"></fa> &nbsp; Proměnné pro nastavení</h1>
	  </div>
	  <div class="col-xs-12 col-md-6 col-sm-6 pad-0 gap-0 align-right">	  	
			<a class="button btn-fill btn-blue" onclick="$('#nova-promenna').toggle('slow');" >
				<fa class="fa fa-plus-circle"></fa> &nbsp; Přidat proměnnou
			</a>	  		  		  	
	  </div>
  </div>
  
	<div id="nova-promenna" <?if(trim($novaPromenna->klic)==''&&trim($novaPromenna->nazev)==''&&trim($novaPromenna->hodnota)==''&&trim($novaPromenna->popis)==''){?>style="display:none;"<?}?> >
		<br />
		<div class="row">    
			<div class="col-xs-12 col-md-3 col-sm-3 pad-0 gap-0 align-left"><h2 class="gap-8">Přidání proměnné:</h2></div>
			<div class="col-xs-12 col-md-9 col-sm-9 pad-0 end-sm end-md center-xs"><div class="gap-8"><i>* Vyplňte prosím všechny povinné údaje označené hvězdičkou (*).</i></div></div>
		</div>
		<form method="post" action="<?=Anchor(array('modul'=>'aPromenneNastaveni','akce'=>'pridej-promennou'));?>" class="isAjaxForm">
			<div class="row middle-xs">
				<div class="col-md-1 col-sm-2 col-xs-4 pad-y-8 align-right bold" >*Název:</div>
				<div class="col-md-3 col-sm-4 col-xs-8 pad-y-8" ><input type="text" name="nazev" maxlength="256" value="<?=$novaPromenna->nazev?>" class="w-100" placeholder="např.: Jméno projektu" required /></div>
				<div class="col-md-1 col-sm-2 col-xs-4 pad-y-8 align-right bold" >*Klíč:</div>
				<div class="col-md-3 col-sm-4 col-xs-8 pad-y-8" ><input type="text" name="klic" maxlength="128" value="<?=$novaPromenna->klic?>" class="w-100" placeholder="např.: jmeno_projektu" required /></div>
				<div class="col-md-1 col-sm-2 col-xs-4 pad-y-8 align-right bold" >*Typ:</div>
				<div class="col-md-3 col-sm-4 col-xs-8 pad-y-8" >
					<select name="typ" class="w-100">
						<option value="0" <?if(0==$novaPromenna->typ){?>selected<?}?> >Celé číslo</option>
						<option value="1" <?if(1==$novaPromenna->typ){?>selected<?}?> >Desetinné číslo</option>
						<option value="2" <?if(2==$novaPromenna->typ){?>selected<?}?> >Ano / Ne</option>
						<option value="3" <?if(3==$novaPromenna->typ){?>selected<?}?> >Krátký text</option>
						<option value="4" <?if(4==$novaPromenna->typ){?>selected<?}?> >Dlouhý text</option>
						<option value="5" <?if(5==$novaPromenna->typ){?>selected<?}?> >Text s formátováním</option>						
					</select>				
				</div>	
				<div class="col-md-1 col-sm-2 col-xs-4 pad-y-8 align-right bold" >*Zobrazovat:</div>
				<div class="col-md-11 col-sm-4 col-xs-8 pad-y-8" >
					<select name="zobrazovat" class="w-100">
						<option value="1" <?if(1==$novaPromenna->zobrazovat){?>selected<?}?> >Ano, administrátoři tuto proměnnou uvidí a budou ji upravovat</option>
						<option value="0" <?if(0==$novaPromenna->zobrazovat){?>selected<?}?> >Ne,  administrátoři tuto proměnnou neuvidí a nebudou ji upravovat</option>
					</select>
				</div>																												
			</div>
			<div class="row">
				<div class="col-md-1 col-sm-2 col-xs-4 pad-y-8 align-right bold" >&nbsp;<br />*Popis:</div>
				<div class="col-md-11 col-sm-10 col-xs-8 pad-y-8" ><textarea name="popis" class="w-100" rows="3" placeholder="např. Jméno projektu se zobrazuje ve zdrojovém kódu webu." required ><?=$novaPromenna->popis?></textarea></div>
			</div>
			<div class="row">
				<div class="col-md-1 col-sm-2 col-xs-4 pad-y-8 align-right bold" >&nbsp;<br />Hodnota:</div>
				<div class="col-md-11 col-sm-10 col-xs-8 pad-y-8" ><textarea name="hodnota" class="w-100" rows="3" placeholder="např. MHMCUBE"><?=$novaPromenna->hodnota?></textarea></div>
			</div>
			<div class="row middle-xs">							
				<div class="col-md-9 col-sm-8 col-xs-4 pad-y-8 bold" >&nbsp;</div>										
				<div class="col-md-3 col-sm-4 col-xs-8 align-right " ><button type="submit" class="btn-fill btn-blue w-100"><em class="fa fa-plus-circle"></em> &nbsp; Přidat proměnnou</button></div>				
			</div>			
		</form>
		<br />
	</div>		
 	<br />  
  <form method="post" action="<?=Anchor(array('modul'=>'aPromenneNastaveni','akce'=>'zmena-vyhledavani'));?>" class="isAjaxForm">
		<div class="row middle-xs">  		
			<div class="col-md-3 col-sm-12 col-xs-12 pad-y-8 pad-0 gap-0" ><h2 class="gap-0">Fulltextové vyhledávání:</h2></div>					
			<div class="col-md-2 col-sm-2 col-xs-4 pad-y-8 align-right bold" >Hledaný výraz:</div>
			<div class="col-md-3 col-sm-4 col-xs-8 pad-y-8" ><input type="text" name="fulltext" maxlength="64" value="<?=$fulltext?>" class="w-100" placeholder="např. Jméno projektu" /></div>					
			<div class="col-md-1 col-sm-2 col-xs-4 pad-y-8 bold">&nbsp;</div>				
			<div class="col-md-3 col-sm-4 col-xs-8 align-right " ><button type="submit" class="btn-fill btn-blue w-100"><em class="fa fa-magnifying-glass"></em> &nbsp; Vyhledat</button></div>				
		</div>
  </form>
  <?if(trim($fulltext)!=''){?>
		<form method="post" action="<?=Anchor(array('modul'=>'aPromenneNastaveni','akce'=>'zmena-vyhledavani'));?>" class="isAjaxForm">
			<div class="row middle-xs"> 				
				<div class="col-md-9 col-sm-8 col-xs-4 pad-y-8 bold"> <input type="hidden" name="fulltext" value="" /> </div>				
				<div class="col-md-3 col-sm-4 col-xs-8 align-right " ><button type="submit" class="btn-fill negative w-100"><em class="fa fa-xmark"></em> &nbsp; Zrušit vyhledávání "<?=$fulltext?>"</button></div>
			</div>				
		</form>
	<?}?>
  <br />  
  <?if(isset($promenne)&&is_array($promenne)&&count($promenne)>0){?>
		<div class="table-wrap fixed-width">
			<table style="min-width:850px;width:100%">
				<thead>	
					<tr>
						<th colspan="3" class="align-right">Pořadí</th>		
						<th>Název</th>
						<th>Klíč</th>					
						<th>Typ</th>																															
						<th colspan="3">Zobrazovat</th>												
					</tr>
				</thead>
				<?foreach($promenne as $p){?>
					<tr>						
						<td width="50">
							<a href="<?=Anchor(array('modul'=>'aPromenneNastaveni','akce'=>'editace-promenne','nid'=>$p->nid));?>" class="btn btn-icon btn-blue btn-small btn-fill isAjax" title="Upravit proměnnou"><em class="fa fa-pencil fa-1-4x"></em></a>
						</td>
						<td width="50">
							<?if($p->aNahoru!=''){?>
								<a href="<?=$p->aNahoru;?>" class="btn btn-icon btn-blue btn-small btn-fill isAjax" title="Posunout výš" ><em class="fa fa-caret-up fa-1-4x"></em></a>	
							<?}else{?>
								<button class="btn btn-icon btn-blue btn-small btn-fill" disabled=""><em class="fa fa-caret-up fa-1-4x"></em></button>
							<?}?>
						</td>
						<td width="50">
							<?if($p->aDolu!=''){?>
								<a href="<?=$p->aDolu;?>" class="btn btn-icon btn-blue btn-small btn-fill isAjax" title="Posunout níž" ><em class="fa fa-caret-down fa-1-4x"></em></a>	
							<?}else{?>
								<button class="btn btn-icon btn-blue btn-small btn-fill" disabled=""><em class="fa fa-caret-down fa-1-4x"></em></button>
							<?}?>
						</td>
						<td><?=$p->nazev?></td>
						<td><?=$p->klic?></td>
						<td>
							<?if($p->typ==0){?>Celé číslo<?}?>
							<?if($p->typ==1){?>Desetinné číslo<?}?>
							<?if($p->typ==2){?>Ano / Ne<?}?>
							<?if($p->typ==3){?>Krátký text<?}?>
							<?if($p->typ==4){?>Dlouhý text<?}?>
							<?if($p->typ==5){?>Text s formátováním<?}?>																
						</td>						
						<td>
							<?if($p->zobrazovat==1){?>
								<span class="color-dark-green"><em class="fa fa-check-circle"></em> Zobrazovat v&nbsp;administraci </span>
							<?}else{?>
								<span class="color-dark-red"><em class="fa fa-times-circle"></em> Nezobrazovat v&nbsp;administraci</span>
							<?}?>
						</td>																																																									
						<td width="50">
							<a href="<?=Anchor(array('modul'=>'aPromenneNastaveni','akce'=>'smaz-promennou','nid'=>$p->nid));?>" class="btn btn-icon btn-small btn-fill negative isAjax" title="Smazat proměnnou" onclick="return confirm('Opravdu si přejete smazat proměnnou <?=prepareGetDataSafely($p->nazev);?>?');"><em class="fa fa-trash fa-1-4x"></em></a>
						</td>															
					</tr>
				<?}?>
			</table>			
		</div>	
		
		<br />								
  <?}else{?>
  	<h2><em class="fa fa-exclamation-triangle"></em> Žádné proměnné pro nastavení nebyly nenalezeny. </h2>  	  	
  <?}?>
  <br />  
</div>
