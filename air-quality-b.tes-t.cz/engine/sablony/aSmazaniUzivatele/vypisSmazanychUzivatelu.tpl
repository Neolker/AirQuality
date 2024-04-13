<?if(getget('m','')=='uzivatel-obnoven'){?><div class="m-wrap bg-light-green"> <em class="fa fa-check-circle"></em> Uživatel úspěšně obnoven - nyní se již zobrazuje v seznamu uživatelů. </div><?}?>
<?if(getget('m','')=='uzivatel-nenalezen'){?><div class="m-wrap bg-light-red"> <em class="fa fa-exclamation-triangle"></em> Uživatel nebyl nalezen, obnovení se nezdařilo.</div><?}?>
<?if(getget('m','')=='uzivatel-smazan'){?><div class="m-wrap bg-light-green"> <em class="fa fa-check-circle"></em> Uživatel úspěšně smazán ze systému.</div><?}?>
<?if(getget('m','')=='uzivatele-se-nepodarilo-smazat'){?><div class="m-wrap bg-light-red"> <em class="fa fa-exclamation-triangle"></em> Smazání uživatele se nezdařilo.</div><?}?>

<div class="wrap bg-cream main-wrap"> 
	<div class="row">       
	  <div class="col-xs-12 col-md-12 col-sm-12 pad-0 gap-0 align-left">
		  <h1 class="gap-0"><fa class="fa fa-trash"></fa> &nbsp; Smazaní uživatelé</h1>
	  </div>	  
  </div>
  <br />
  <form method="post" action="<?=Anchor(array('modul'=>'aSmazaniUzivatele','akce'=>'zmena-vyhledavani'));?>" class="isAjaxForm">
		<div class="row middle-xs">  		
			<div class="col-md-3 col-sm-12 col-xs-12 pad-y-8 pad-0 gap-0" ><h2 class="gap-0">Fulltextové vyhledávání:</h2></div>					
			<div class="col-md-2 col-sm-2 col-xs-4 pad-y-8 align-right bold" ><sup>1</sup>Hledaný výraz:</div>
			<div class="col-md-3 col-sm-4 col-xs-8 pad-y-8" ><input type="text" name="fulltext" maxlength="64" value="<?=$fulltext?>" class="w-100" placeholder="např. Ing. Jan Novák" /></div>					
			<div class="col-md-1 col-sm-2 col-xs-4 pad-y-8 bold">&nbsp;</div>				
			<div class="col-md-3 col-sm-4 col-xs-8 align-right " ><button type="submit" class="btn-fill btn-blue w-100"><em class="fa fa-magnifying-glass"></em> &nbsp; Vyhledat</button></div>				
		</div>
  </form>
  <?if(trim($fulltext)!=''){?>  		
		<form method="post" action="<?=Anchor(array('modul'=>'aSmazaniUzivatele','akce'=>'zmena-vyhledavani'));?>" class="isAjaxForm">
			<div class="row middle-xs"> 				
				<div class="col-md-9 col-sm-8 col-xs-4 pad-y-8 bold"> <input type="hidden" name="fulltext" value="" /> </div>				
				<div class="col-md-3 col-sm-4 col-xs-8 align-right " ><button type="submit" class="btn-fill negative w-100"><em class="fa fa-xmark"></em> &nbsp; Zrušit vyhledávání "<?=$fulltext?>"</button></div>
			</div>												
		</form>
	<?}?>
  <br />  
  <?if(isset($uzivatele)&&is_array($uzivatele)&&count($uzivatele)>0){?>
		<div class="table-wrap fixed-width">
			<table style="min-width:1200px;width:100%">
				<thead>	
					<tr>
						<th style="border-right:none;">&nbsp;</th>		
						<th><a href="<?=Anchor(array('modul'=>'aSmazaniUzivatele','akce'=>'zmena-razeni','typ'=>'login','stranka'=>$stranka));?>" class="<?=($razeni=='login'?'th-sorter-active':'th-sorter');?> isAjax" title="Seřadit dle Loginu">Login <em class="fa fa-arrow-down-a-z"></em></a></th>
						<th><a href="<?=Anchor(array('modul'=>'aSmazaniUzivatele','akce'=>'zmena-razeni','typ'=>'jmeno','stranka'=>$stranka));?>" class="<?=($razeni=='jmeno'?'th-sorter-active':'th-sorter');?> isAjax" title="Seřadit dle Příjmení, jména a titulu">Příjmení, jméno, titul <em class="fa fa-arrow-down-a-z"></em></a></th>					
						<th><a href="<?=Anchor(array('modul'=>'aSmazaniUzivatele','akce'=>'zmena-razeni','typ'=>'spolecnost','stranka'=>$stranka));?>" class="<?=($razeni=='spolecnost'?'th-sorter-active':'th-sorter');?> isAjax" title="Seřadit dle Společnosti, Příjmení, jména a titulu">Společnost <em class="fa fa-arrow-down-a-z"></a></th>																									
						<th>Práva</th>						
						<th colspan="2">Aktivní</th>													
					</tr>
				</thead>
				<?foreach($uzivatele as $u){?>
					<tr>
						<td width="50">
							<a href="<?=Anchor(array('modul'=>'aSmazaniUzivatele','akce'=>'obnovit-uzivatele','uid'=>$u->uid));?>" class="btn btn-icon btn-small btn-fill isAjax" title="Obnovit uživatele" onclick="return confirm('Opravdu si přejete obnovit uživatele <?=prepareGetDataSafely($u->login.' ('.trim($u->titul.' '.$u->jmeno.' '.$u->prijmeni).')');?>?');"><em class="fa fa-undo fa-1-4x"></em></a>
						</td>
						<td><?=$u->login?></td>
						<td><?=trim($u->prijmeni.' '.$u->jmeno.' '.$u->titul)?></td>
						<td><?=$u->spolecnost?></td>																			
						<td><?=$pravaUzivatelu[$u->prava]?></td>
						<td>
							<?if($u->aktivni_uzivatel==1){?>
								<span class="color-dark-green"><em class="fa fa-check-circle"></em> Aktivní</span>
							<?}else{?>
								<span class="color-dark-red"><em class="fa fa-times-circle"></em> Neaktivní</span>
							<?}?>
						</td>																								
						<td width="50">
							<a href="<?=Anchor(array('modul'=>'aSmazaniUzivatele','akce'=>'smaz-uzivatele','uid'=>$u->uid));?>" class="btn btn-icon btn-small btn-fill negative isAjax" title="Trvale smazat uživatele" onclick="return confirm('Opravdu si přejete trvale smazat uživatele <?=prepareGetDataSafely($u->login.' ('.trim($u->titul.' '.$u->jmeno.' '.$u->prijmeni).')');?>? Tato operace je již nevratná.');"><em class="fa fa-trash fa-1-4x"></em></a>
						</td>														
					</tr>
				<?}?>
			</table>
		</div>		
		<p>			
			<sup>1</sup> Hledaný výraz prohledává login, email, telefon a zbylé údaje spojené mezerou za sebe: "titul jméno příjmení společnost". Můžete tedy například vyhledávat stylem: "Ing. Jan Novák".
		</p>				
		<br />
		<?=strankovani($strankovac,((int)getget('stranka','0')));?>
  <?}else{?>
  	<h2><em class="fa fa-exclamation-triangle"></em> Žádní uživatelé nenalezeni, pokud máte zapnuté fulltextové vyhledávání, zkuste hledaný výraz zapsat jinak.</h2>  	
  <?}?>
  <br />
</div>
