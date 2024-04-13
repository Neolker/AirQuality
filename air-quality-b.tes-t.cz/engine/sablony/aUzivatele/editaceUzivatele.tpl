<?if(getget('m','')=='uzivatel-ulozen'){?><div class="m-wrap bg-light-green"> <em class="fa fa-check-circle"></em> Uživatel úspěšně uložen.</div><?}?>
<?if(getget('m','')=='uzivatel-neulozen'){?><div class="m-wrap bg-light-red"> <em class="fa fa-exclamation-triangle"></em> Uživatele se nepodařilo uložit.</div><?}?>
<?if(getget('m','')=='heslo-zmeneno'){?><div class="m-wrap bg-light-green"> <em class="fa fa-check-circle"></em> Heslo uživatele bylo úspěšně změněno.</div><?}?>
<?if(getget('m','')=='heslo-nezmeneno'){?><div class="m-wrap bg-light-red"> <em class="fa fa-exclamation-triangle"></em> Heslo uživatele se nepodařilo změnit.</div><?}?>
<div class="wrap bg-cream main-wrap"> 
	<div class="row">       
	  <div class="col-xs-12 col-md-6 col-sm-8 pad-0 gap-0 align-left">
		  <h1 class="gap-0"><fa class="fa fa-user-group"></fa> &nbsp; Uživatelé - Editace uživatele <?=$uzivatel->login?></h1>
	  </div>
	  <div class="col-xs-12 col-md-6 col-sm-4 pad-0 gap-0 align-right">
	  	<a href="<?=Anchor(array('modul'=>'aUzivatele','akce'=>'vypis-uzivatelu','stranka'=>$stranka));?>" class="button btn-fill btn-blue isAjax" >
	  		<fa class="fa fa-chevron-circle-left"></fa> &nbsp; Zpět na výpis uživatelů
	  	</a>
	  </div>
  </div>
 
	<br />
	<h2>Základní údaje:</h2>
	<form method="post" action="<?=Anchor(array('modul'=>'aUzivatele','akce'=>'uloz-uzivatele','uid'=>$uzivatel->uid,'stranka'=>$stranka));?>" class="isAjaxForm" <?/*onsubmit="return confirm('Opravdu si přejete uložit základní údaje?');"*/?> >
		<div class="row middle-xs">						
			<div class="col-md-1 col-sm-2 col-xs-4 bold align-right pad-y-8" >Login:</div>
			<div class="col-md-3 col-sm-4 col-xs-8 bold pad-y-8" ><?=$uzivatel->login?></div>			
			<div class="col-md-1 col-sm-2 col-xs-4 bold align-right pad-y-8" >E-mail:</div>
			<div class="col-md-3 col-sm-4 col-xs-8 pad-y-8" ><input type="email" name="email" maxlength="128" value="<?=$uzivatel->email?>" class="w-100" required placeholder="např. jan@novak.cz" /></div>										
			<div class="col-md-1 col-sm-2 col-xs-4 bold align-right pad-y-8" >Telefon:</div>
			<div class="col-md-3 col-sm-4 col-xs-8 pad-y-8" ><input type="text" name="telefon" maxlength="32" value="<?=$uzivatel->telefon?>" class="w-100" placeholder="např. +420 123 456 789" /></div>
			
			<div class="col-md-1 col-sm-2 col-xs-4 bold align-right pad-y-8" >Titul:</div>
			<div class="col-md-3 col-sm-4 col-xs-8 pad-y-8" ><input type="text" name="titul" maxlength="32" value="<?=$uzivatel->titul?>" class="w-100" placeholder="např. Ing." /></div>
			<div class="col-md-1 col-sm-2 col-xs-4 bold align-right pad-y-8" >Jméno:</div>
			<div class="col-md-3 col-sm-4 col-xs-8 pad-y-8" ><input type="text" name="jmeno" maxlength="64" value="<?=$uzivatel->jmeno?>" class="w-100" placeholder="např. Jan" /></div>
			<div class="col-md-1 col-sm-2 col-xs-4 bold align-right pad-y-8" >Příjmení:</div>
			<div class="col-md-3 col-sm-4 col-xs-8 pad-y-8" ><input type="text" name="prijmeni" maxlength="64" value="<?=$uzivatel->prijmeni?>" class="w-100" placeholder="např. Novák" /></div>
			
			<div class="col-md-1 col-sm-2 col-xs-4 bold align-right pad-y-8" >Společnost:</div>
			<div class="col-md-3 col-sm-4 col-xs-8 pad-y-8" ><input type="text" name="spolecnost" maxlength="64" value="<?=$uzivatel->spolecnost?>" class="w-100" placeholder="např. Obchodní s.r.o." /></div>
			<div class="col-md-5 col-sm-2 col-xs-4 pad-y-8" ></div>
			<div class="col-md-3 col-sm-4 col-xs-8 pad-y-8 " ><button type="submit" class="btn-fill  btn-blue w-100"><em class="fa fa-floppy-disk"></em> &nbsp; Uložit základní údaje</button></div>		
		</div>
	</form>		
	<br />
	<h2>Změna hesla:</h2>
	<form method="post" action="<?=Anchor(array('modul'=>'aUzivatele','akce'=>'uloz-heslo-uzivatele','uid'=>$uzivatel->uid,'stranka'=>$stranka));?>" class="isAjaxForm">
		<div class="row middle-xs">						
			<div class="col-md-1 col-sm-2 col-xs-4 pad-y-8 bold align-right" ><?if($prihlasenyUzivatel->uid==$uzivatel->uid){?>Současné (vygenerované) heslo:<?}else{?>Vaše heslo:<?}?></div>
			<div class="col-md-3 col-sm-4 col-xs-8 pad-y-8" ><input type="password" name="heslo" maxlength="64" value="" class="w-100" required placeholder="např. ******" /></div>
			<div class="col-md-1 col-sm-2 col-xs-4 pad-y-8 bold align-right" ><?if($prihlasenyUzivatel->uid==$uzivatel->uid){?>Nové heslo:<?}else{?>Nové heslo uživatele:<?}?></div>
			<div class="col-md-3 col-sm-4 col-xs-8 pad-y-8" ><input type="password" name="heslo_nove" maxlength="64" value="" class="w-100" required placeholder="např. ******" /></div>
			<div class="col-md-1 col-sm-8 col-xs-4 pad-y-8" ></div>
			<div class="col-md-3 col-sm-4 col-xs-8 pad-y-8 " ><button type="submit" class="btn-fill  btn-blue w-100"><em class="fa fa-floppy-disk"></em> &nbsp; Změnit heslo</button></div>		
		</div>
	</form>			
	<br />
	<h2>Systémová nastavení:</h2>
	<form method="post" action="<?=Anchor(array('modul'=>'aUzivatele','akce'=>'uloz-uzivatele-2','uid'=>$uzivatel->uid,'stranka'=>$stranka));?>" class="isAjaxForm">
		<div class="row middle-xs">		
			<div class="col-md-1 col-sm-2 col-xs-4 pad-y-8 bold align-right" >Práva:</div>
			<div class="col-md-3 col-sm-4 col-xs-8 pad-y-8" >
				<select autocomplete="off" name="prava" class="w-100">
					<?foreach($pravaUzivatelu as $puK=>$puV){?>
						<?if($puK>$prihlasenyUzivatel->prava){continue;}?>
						<option value="<?=$puK;?>" <?if($puK==$uzivatel->prava){?>selected<?}?> ><?=$puV;?></option>
					<?}?>						
				</select>				
			</div>								
			<div class="col-md-1 col-sm-2 col-xs-4 pad-y-8 bold align-right" >Aktivní:</div>
			<div class="col-md-3 col-sm-4 col-xs-8 pad-y-8" >
				<select autocomplete="off" name="aktivni_uzivatel" class="w-100">
					<option value="1" <?if(1==$uzivatel->aktivni_uzivatel){?>selected<?}?> >Aktivní uživatel - může se přihlásit do systému</option>
					<option value="0" <?if(0==$uzivatel->aktivni_uzivatel){?>selected<?}?> >Neaktivní uživatel - nemůže se přihlásit do systému</option>
				</select>
			</div>
			<div class="col-md-1 col-sm-8 col-xs-4 pad-y-8" ></div>
			<div class="col-md-3 col-sm-4 col-xs-8 pad-y-8 " ><button type="submit" class="btn-fill btn-blue w-100"><em class="fa fa-floppy-disk"></em> &nbsp; Uložit systémová nastavení</button></div>		
		</div>
	</form>
	<br />
	<h2>Další informace:</h2>  
		<div class="row middle-xs">						
			<div class="col-md-2 col-sm-3 col-xs-6 pad-y-8 bold align-right" >Vytvoření uživatele:</div>
			<div class="col-md-2 col-sm-3 col-xs-6 pad-y-8 " ><?=timestampToDateTime($uzivatel->registrace_ts)?></div>	
			<div class="col-md-2 col-sm-3 col-xs-6 pad-y-8 bold align-right" >Poslední přihlášení:</div>
			<div class="col-md-2 col-sm-3 col-xs-6 pad-y-8 " ><?=timestampToDateTime($uzivatel->posledni_prihlaseni_ts)?></div>
			<div class="col-md-2 col-sm-3 col-xs-6 pad-y-8 bold align-right" >Poslední aktivita:</div>
			<div class="col-md-2 col-sm-3 col-xs-6 pad-y-8 " ><?=timestampToDateTime($uzivatel->posledni_aktivita_ts)?></div>										
			<div class="col-md-2 col-sm-3 col-xs-6 pad-y-8 bold align-right" >Počet přihlášení:</div>
			<div class="col-md-2 col-sm-3 col-xs-6 pad-y-8 " ><?=$uzivatel->pocet_prihlaseni?>x</div>	
			<div class="col-md-2 col-sm-3 col-xs-6 pad-y-8 bold align-right" >Poslední IP Adresa:</div>
			<div class="col-md-2 col-sm-3 col-xs-6 pad-y-8 " ><?=str_replace(":"," : ",$uzivatel->posledni_prihlaseni_ip)?></div>	
		</div>
	<br />
</div>
