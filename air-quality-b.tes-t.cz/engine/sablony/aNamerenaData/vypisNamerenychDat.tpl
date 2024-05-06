<div class="wrap bg-cream main-wrap"> 
	<div class="row">       
	  <div class="col-xs-12 col-md-12 col-sm-12 pad-0 gap-0 align-left">
		  <h1 class="gap-0"><fa class="fa fa-wind"></fa> &nbsp; Naměřená data</h1>
	  </div>	 
  </div>  	
  <form method="post" action="<?=Anchor(array('modul'=>'aNamerenaData','akce'=>'zmena-vyhledavani'));?>" class="isAjaxForm">
		<div class="row middle-xs">  		
			<div class="col-md-3 col-sm-12 col-xs-12 pad-y-8 pad-0 gap-0" ><h2 class="gap-0">Fulltextové vyhledávání:</h2></div>					
			<div class="col-md-2 col-sm-2 col-xs-4 pad-y-8 align-right bold" >Hledaný výraz:</div>
			<div class="col-md-3 col-sm-4 col-xs-8 pad-y-8" ><input type="text" name="fulltext" maxlength="64" value="<?=$fulltext?>" class="w-100" placeholder="např. Uživatel či Zařízení" /></div>					
			<div class="col-md-1 col-sm-2 col-xs-4 pad-y-8 bold">&nbsp;</div>				
			<div class="col-md-3 col-sm-4 col-xs-8 align-right " ><button type="submit" class="btn-fill btn-blue w-100"><em class="fa fa-magnifying-glass"></em> &nbsp; Vyhledat</button></div>				
		</div>
  </form>
  <?if(trim($fulltext)!=''){?>
		<form method="post" action="<?=Anchor(array('modul'=>'aNamerenaData','akce'=>'zmena-vyhledavani'));?>" class="isAjaxForm">
			<div class="row middle-xs"> 				
				<div class="col-md-9 col-sm-8 col-xs-4 pad-y-8 bold"> <input type="hidden" name="fulltext" value="" /> </div>				
				<div class="col-md-3 col-sm-4 col-xs-8 align-right " ><button type="submit" class="btn-fill negative w-100"><em class="fa fa-xmark"></em> &nbsp; Zrušit vyhledávání "<?=$fulltext?>"</button></div>
			</div>					
		</form>
	<?}?>
  <br />  
  <?if(isset($data)&&is_array($data)&&count($data)>0){?>
		<div class="table-wrap fixed-width">
			<table style="min-width:1050px;width:100%">
				<thead>	
					<tr>						
						<th class="align-center">Datum a&nbsp;čas</th>
						<th>Uživatel</th>												
						<th>Zařízení</th>						
						<th class="align-center" colspan="2">CO2 [ppm]</th>
						<th class="align-center" colspan="2">Teplota [°C]</th>							
						<th class="align-center" colspan="2">Vlhkost [%RH]</th>			
						<th class="align-center">Baterie [V]</th>		
						<th class="align-center">Pozice</th>														
					</tr>
				</thead>
				<?foreach($data as $d){?>
					<tr>																
						<td class="align-center" width="120"><?=strftime('%d.%m.%Y<br /><small>%H:%M:%S</small>',$d->unix_ts);?></td>		
						<td><?=$d->uzivatel;?></td>
						<td><?=$d->nazev_zarizeni;?><?if(trim($d->lokalita)!=""){?> (<?=$d->lokalita;?>)<?}?><br /><small><?=$d->vyrobni_cislo;?></small></td>
						<td class="align-right" width="80"><?=number_format($d->co2_prumer,2,',','&nbsp;');?></td>
						<td class="align-center" width="50">
							<?if($d->co2_trend==(-1)){?><em class="fa fa-arrow-trend-down"></em><?}?>
							<?if($d->co2_trend==(0)){?><em class="fa fa-arrow-right-long"></em><?}?>
							<?if($d->co2_trend==(1)){?><em class="fa fa-arrow-trend-up"></em><?}?>
						</td>		
						<td class="align-right" width="80"><?=number_format($d->teplota_prumer,2,',','&nbsp;');?></td>
						<td class="align-center" width="50">
							<?if($d->teplota_trend==(-1)){?><em class="fa fa-arrow-trend-down"></em><?}?>
							<?if($d->teplota_trend==(0)){?><em class="fa fa-arrow-right-long"></em><?}?>
							<?if($d->teplota_trend==(1)){?><em class="fa fa-arrow-trend-up"></em><?}?>
						</td>	
						<td class="align-right" width="80"><?=number_format($d->vlhkost_prumer,2,',','&nbsp;');?></td>
						<td class="align-center" width="50">
							<?if($d->vlhkost_trend==(-1)){?><em class="fa fa-arrow-trend-down"></em><?}?>
							<?if($d->vlhkost_trend==(0)){?><em class="fa fa-arrow-right-long"></em><?}?>
							<?if($d->vlhkost_trend==(1)){?><em class="fa fa-arrow-trend-up"></em><?}?>
						</td>		
						<td class="align-center" width="110"><?=number_format($d->baterie_hodnota,2,',','&nbsp;');?></td>				
						<td class="align-center" width="125">															
							<?if($d->pozice==(0)){?>Horní strana<?}?>
							<?if($d->pozice==(1)){?>Spodní strana<?}?>
							<?if($d->pozice==(2)){?>Levá strana<?}?>
							<?if($d->pozice==(3)){?>Pravá strana<?}?>
							<?if($d->pozice==(4)){?>Přední strana<?}?>
							<?if($d->pozice==(5)){?>Zadní strana<?}?>
						</td>
					</tr>
				<?}?>
			</table>			
		</div>	
		<br />
		<?=strankovani($strankovac,((int)getget('stranka','0')));?>
		<br />							
  <?}else{?>
  	<h2><em class="fa fa-exclamation-triangle"></em> Žádná data nebyla nalezena. </h2>  	  	
  <?}?>
  <br />  
</div>
