<?if(getget('m','')=='uzivatel-vytvoren'){?><div class="m-wrap bg-light-green"> <em class="fa fa-check-circle"></em> Uživatel úspěšně přidán do systému.</div><?}?>
<?if(getget('m','')=='uzivatel-smazan'){?><div class="m-wrap bg-light-green"> <em class="fa fa-check-circle"></em> Uživatel úspěšně smazán ze systému.</div><?}?>
<?if(getget('m','')=='login-jiz-existuje'){?><div class="m-wrap bg-light-red"> <em class="fa fa-exclamation-triangle"></em> Přidání uživatele se nezdařilo. Login již používá jiný uživatel.</div><?}?>
<?if(getget('m','')=='login-je-kratky'){?><div class="m-wrap bg-light-red"> <em class="fa fa-exclamation-triangle"></em> Přidání uživatele se nezdařilo. Musíte vyplnit login.</div><?}?>
<?if(getget('m','')=='heslo-je-kratke'){?><div class="m-wrap bg-light-red"> <em class="fa fa-exclamation-triangle"></em> Přidání uživatele se nezdařilo. Musíte vyplnit heslo.</div><?}?>
<?if(getget('m','')=='nemate-prava-vytvorit'){?><div class="m-wrap bg-light-red"> <em class="fa fa-exclamation-triangle"></em> Přidání uživatele se nezdařilo. Nemáte dostatečná oprávnění pro vytvoření uživatele.</div><?}?>
<?if(getget('m','')=='nemate-prava-uzivatele'){?><div class="m-wrap bg-light-red"> <em class="fa fa-exclamation-triangle"></em> Nemáte dostatečná oprávnění k tomuto uživateli.</div><?}?>
<?if(getget('m','')=='uzivatele-se-nepodarilo-smazat'){?><div class="m-wrap bg-light-red"> <em class="fa fa-exclamation-triangle"></em> Smazání uživatele se nezdařilo.</div><?}?>
<?if(getget('m','')=='uzivatel-nenalezen'){?><div class="m-wrap bg-light-red"> <em class="fa fa-exclamation-triangle"></em> Uživatel nebyl nalezen v systému.</div><?}?>
<div class="wrap bg-cream main-wrap"> 
	<div class="row">       
	  <div class="col-xs-12 col-md-6 col-sm-6 pad-0 gap-0 align-left">
		  <h1 class="gap-0"><fa class="fa fa-user-group"></fa> &nbsp; Uživatelé</h1>
	  </div>
	  <div class="col-xs-12 col-md-6 col-sm-6 pad-0 gap-0 align-right">
	  	<?if($prihlasenyUzivatel->prava==2||$prihlasenyUzivatel->prava==3){?>
				<a class="button btn-fill btn-blue" onclick="$('#novy-uzivatel').toggle('slow');" >
					<fa class="fa fa-plus-circle"></fa> &nbsp; Přidat uživatele
				</a>	  		  	
	  	<?}?>
	  </div>
  </div>
  <?if($prihlasenyUzivatel->prava==2||$prihlasenyUzivatel->prava==3){?>
		<div id="novy-uzivatel" <?if(trim($novyUzivatel->login)==''){?>style="display:none;"<?}?> >
			<br />
			<div class="row">    
				<div class="col-xs-12 col-md-3 col-sm-3 pad-0 gap-0 align-left"><h2 class="gap-8">Přidání uživatele:</h2></div>
				<div class="col-xs-12 col-md-9 col-sm-9 pad-0 end-sm end-md center-xs"><div class="gap-8"><i>* Vyplňte prosím všechny povinné údaje označené hvězdičkou (*).</i></div></div>
			</div>
			<form method="post" action="<?=Anchor(array('modul'=>'aUzivatele','akce'=>'pridej-uzivatele'));?>" class="isAjaxForm">
				<div class="row middle-xs">
					<div class="col-md-1 col-sm-2 col-xs-4 pad-y-8 align-right bold" >*Login:</div>
					<div class="col-md-3 col-sm-4 col-xs-8 pad-y-8" ><input type="text" name="login" maxlength="128" value="<?=$novyUzivatel->login?>" class="w-100" required placeholder="např. jan.novak" /></div>
					<div class="col-md-1 col-sm-2 col-xs-4 pad-y-8 align-right bold" >*Heslo:</div>
					<div class="col-md-3 col-sm-4 col-xs-8 pad-y-8" ><input type="password" name="heslo" maxlength="64" value="<?=$novyUzivatel->heslo?>" class="w-100" required placeholder="např. ******" /></div>
					<div class="col-md-1 col-sm-2 col-xs-4 pad-y-8 align-right bold" >*E-mail:</div>
					<div class="col-md-3 col-sm-4 col-xs-8 pad-y-8" ><input type="email" name="email" maxlength="128" value="<?=$novyUzivatel->email?>" class="w-100" required placeholder="např. jan@novak.cz" /></div>
									
					<div class="col-md-1 col-sm-2 col-xs-4 pad-y-8 align-right bold" >Titul:</div>
					<div class="col-md-3 col-sm-4 col-xs-8 pad-y-8" ><input type="text" name="titul" maxlength="32" value="<?=$novyUzivatel->titul?>" class="w-100" placeholder="např. Ing." /></div>
					<div class="col-md-1 col-sm-2 col-xs-4 pad-y-8 align-right bold" >Jméno:</div>
					<div class="col-md-3 col-sm-4 col-xs-8 pad-y-8" ><input type="text" name="jmeno" maxlength="64" value="<?=$novyUzivatel->jmeno?>" class="w-100" placeholder="např. Jan" /></div>
					<div class="col-md-1 col-sm-2 col-xs-4 pad-y-8 align-right bold" >Příjmení:</div>
					<div class="col-md-3 col-sm-4 col-xs-8 pad-y-8" ><input type="text" name="prijmeni" maxlength="64" value="<?=$novyUzivatel->prijmeni?>" class="w-100" placeholder="např. Novák" /></div>										
					
					<div class="col-md-1 col-sm-2 col-xs-4 pad-y-8 align-right bold" >Společnost:</div>
					<div class="col-md-3 col-sm-4 col-xs-8 pad-y-8" ><input type="text" name="spolecnost" maxlength="64" value="<?=$novyUzivatel->spolecnost?>" class="w-100" placeholder="např. Obchodní s.r.o." /></div>					
					<div class="col-md-1 col-sm-2 col-xs-4 pad-y-8 align-right bold" >Telefon:</div>
					<div class="col-md-3 col-sm-4 col-xs-8 pad-y-8" ><input type="text" name="telefon" maxlength="32" value="<?=$novyUzivatel->telefon?>" class="w-100" placeholder="např. +420 123 456 789" /></div>
					
					<div class="col-md-1 col-sm-2 col-xs-4 pad-y-8 align-right bold" >Práva:</div>
					<div class="col-md-3 col-sm-4 col-xs-8 pad-y-8" >
						<select name="prava" class="w-100">
							<?foreach($pravaUzivatelu as $puK=>$puV){
								if($puK>$prihlasenyUzivatel->prava){continue;}?>
								<option value="<?=$puK;?>" <?if($puK==$novyUzivatel->prava){?>selected<?}?> ><?=$puV;?></option>
							<?}?>						
						</select>
					</div>												
					<div class="col-md-1 col-sm-2 col-xs-4 pad-y-8 align-right bold" >Aktivní:</div>
					<div class="col-md-3 col-sm-4 col-xs-8 pad-y-8" >
						<select name="aktivni_uzivatel" class="w-100">
							<option value="1" <?if(1==$novyUzivatel->aktivni_uzivatel){?>selected<?}?> >Aktivní uživatel - může se přihlásit do systému</option>
							<option value="0" <?if(0==$novyUzivatel->aktivni_uzivatel){?>selected<?}?> >Neaktivní uživatel - nemůže se přihlásit do systému</option>
						</select>
					</div>
					<div class="col-md-1 col-sm-2 col-xs-4 pad-y-8 align-right bold" ></div>
					<div class="col-md-3 col-sm-4 col-xs-8 pad-y-8" >&nbsp;</div>
					<div class="col-md-1 col-sm-2 col-xs-4 pad-y-8 bold" >&nbsp;</div>										
					<div class="col-md-3 col-sm-4 col-xs-8 align-right " ><button type="submit" class="btn-fill btn-blue w-100"><em class="fa fa-plus-circle"></em> &nbsp; Přidat uživatele</button></div>				
				</div>
			</form>
			<br />
		</div>		
  <?}?> 
  <br />
  <form method="post" action="<?=Anchor(array('modul'=>'aUzivatele','akce'=>'zmena-vyhledavani'));?>" class="isAjaxForm">
		<div class="row middle-xs">  		
			<div class="col-md-3 col-sm-12 col-xs-12 pad-y-8 pad-0 gap-0" ><h2 class="gap-0">Fulltextové vyhledávání:</h2></div>					
			<div class="col-md-2 col-sm-2 col-xs-4 pad-y-8 align-right bold" ><sup>1</sup>Hledaný výraz:</div>
			<div class="col-md-3 col-sm-4 col-xs-8 pad-y-8" ><input type="text" name="fulltext" maxlength="64" value="<?=$fulltext?>" class="w-100" placeholder="např. Ing. Jan Novák" /></div>					
			<div class="col-md-1 col-sm-2 col-xs-4 pad-y-8 bold">&nbsp;</div>				
			<div class="col-md-3 col-sm-4 col-xs-8 align-right " ><button type="submit" class="btn-fill btn-blue w-100"><em class="fa fa-magnifying-glass"></em> &nbsp; Vyhledat</button></div>				
		</div>
  </form>
  <?if(trim($fulltext)!=''){?>  		
		<form method="post" action="<?=Anchor(array('modul'=>'aUzivatele','akce'=>'zmena-vyhledavani'));?>" class="isAjaxForm">
			<div class="row middle-xs"> 				
				<div class="col-md-9 col-sm-8 col-xs-4 pad-y-8 bold"> <input type="hidden" name="fulltext" value="" /> </div>				
				<div class="col-md-3 col-sm-4 col-xs-8 align-right " ><button type="submit" class="btn-fill negative w-100"><em class="fa fa-xmark"></em> &nbsp; Zrušit vyhledávání "<?=$fulltext?>"</button></div>
			</div>
		</form>
	<?}?>
  <br />  
  <?if(isset($uzivatele)&&is_array($uzivatele)&&count($uzivatele)>0){?>
		<div class="table-wrap fixed-width">
			<table style="min-width:950px;width:100%">
				<thead>	
					<tr>
						<th style="border-right:none;">&nbsp;</th>		
						<th><a href="<?=Anchor(array('modul'=>'aUzivatele','akce'=>'zmena-razeni','typ'=>'login','stranka'=>$stranka));?>" class="<?=($razeni=='login'?'th-sorter-active':'th-sorter');?> isAjax" title="Seřadit dle Loginu">Login <em class="fa fa-arrow-down-a-z"></em></a></th>
						<th><a href="<?=Anchor(array('modul'=>'aUzivatele','akce'=>'zmena-razeni','typ'=>'jmeno','stranka'=>$stranka));?>" class="<?=($razeni=='jmeno'?'th-sorter-active':'th-sorter');?> isAjax" title="Seřadit dle Příjmení, jména a titulu">Příjmení, jméno, titul <em class="fa fa-arrow-down-a-z"></em></a></th>					
						<th><a href="<?=Anchor(array('modul'=>'aUzivatele','akce'=>'zmena-razeni','typ'=>'spolecnost','stranka'=>$stranka));?>" class="<?=($razeni=='spolecnost'?'th-sorter-active':'th-sorter');?> isAjax" title="Seřadit dle Společnosti, Příjmení, jména a titulu">Společnost <em class="fa fa-arrow-down-a-z"></a></th>																									
						<th>Práva</th>						
						<th colspan="2"><sup>2</sup>Aktivní</th>													
					</tr>
				</thead>
				<?foreach($uzivatele as $u){?>
					<tr>
						<?if(($u->prava<3&&$u->prava<=$prihlasenyUzivatel->prava)||$u->uid==$prihlasenyUzivatel->uid){?>
							<td width="50">
								<a href="<?=Anchor(array('modul'=>'aUzivatele','akce'=>'editace-uzivatele','uid'=>$u->uid,'stranka'=>$stranka));?>" class="btn btn-icon btn-blue btn-small btn-fill isAjax" title="Upravit uživatele"><em class="fa fa-pencil fa-1-4x"></em></a>
							</td>
						<?}else{?>
							<td width="50"><button class="btn btn-icon btn-blue btn-small btn-fill" disabled><em class="fa fa-pencil fa-1-4x"></em></button></td>
						<?}?>
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
						<?if($u->prava<3&&$u->prava<=$prihlasenyUzivatel->prava&&$u->uid!=$prihlasenyUzivatel->uid){?>
							<td width="50">
								<a href="<?=Anchor(array('modul'=>'aUzivatele','akce'=>'smaz-uzivatele','uid'=>$u->uid));?>" class="btn btn-icon btn-small btn-fill negative isAjax" title="Smazat uživatele" onclick="return confirm('Opravdu si přejete smazat uživatele <?=prepareGetDataSafely($u->login.' ('.trim($u->titul.' '.$u->jmeno.' '.$u->prijmeni).')');?>?');"><em class="fa fa-trash fa-1-4x"></em></a>
							</td>
						<?}else{?>
							<td width="50"><button class="btn btn-icon btn-small btn-fill negative" disabled><em class="fa fa-trash fa-1-4x"></em></button></td>
						<?}?>												
					</tr>
				<?}?>
			</table>
		</div>				
		<p>			
			<sup>1</sup> Hledaný výraz prohledává login, email, telefon a zbylé údaje spojené mezerou za sebe: "titul jméno příjmení společnost". Můžete tedy například vyhledávat stylem: "Ing. Jan Novák".<br />
			<sup>2</sup> Pouze aktivní uživatel se může přihlašovat.<br />
		</p>				
		<br />
		<?=strankovani($strankovac,((int)getget('stranka','0')));?>
  <?}else{?>
  	<h2><em class="fa fa-exclamation-triangle"></em> Žádní uživatelé nenalezeni, pokud máte zapnuté fulltextové vyhledávání, zkuste hledaný výraz zapsat jinak. </h2>  	
  <?}?>
  <br />  
</div>
