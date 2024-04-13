<?if(getget('m','')=='nastaveni-nenalezeno'){?><div class="m-wrap bg-light-red"> <em class="fa fa-exclamation-triangle"></em> Nastavení nebylo nalezeno v systému.</div><?}?>
<div class="wrap bg-cream main-wrap"> 
	<div class="row">       
	  <div class="col-xs-12 col-md-12 col-sm-12 pad-0 gap-0 align-left">
		  <h1 class="gap-0"><fa class="fa fa-screwdriver-wrench"></fa> &nbsp; Nastavení</h1>
	  </div>	 
  </div>
  	
  <form method="post" action="<?=Anchor(array('modul'=>'aNastaveni','akce'=>'zmena-vyhledavani'));?>" class="isAjaxForm">
		<div class="row middle-xs">  		
			<div class="col-md-3 col-sm-12 col-xs-12 pad-y-8 pad-0 gap-0" ><h2 class="gap-0">Fulltextové vyhledávání:</h2></div>					
			<div class="col-md-2 col-sm-2 col-xs-4 pad-y-8 align-right bold" >Hledaný výraz:</div>
			<div class="col-md-3 col-sm-4 col-xs-8 pad-y-8" ><input type="text" name="fulltext" maxlength="64" value="<?=$fulltext?>" class="w-100" placeholder="např. Jméno projektu" /></div>					
			<div class="col-md-1 col-sm-2 col-xs-4 pad-y-8 bold">&nbsp;</div>				
			<div class="col-md-3 col-sm-4 col-xs-8 align-right " ><button type="submit" class="btn-fill btn-blue w-100"><em class="fa fa-magnifying-glass"></em> &nbsp; Vyhledat</button></div>				
		</div>
  </form>
  <?if(trim($fulltext)!=''){?>
		<form method="post" action="<?=Anchor(array('modul'=>'aNastaveni','akce'=>'zmena-vyhledavani'));?>" class="isAjaxForm">
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
						<th style="border-right:none;">&nbsp;</th>		
						<th>Název</th>												
						<th>Typ</th>
						<th>Délka</th>
						<th>Hodnota</th>							
					</tr>
				</thead>
				<?foreach($promenne as $p){?>
					<tr>						
						<td width="50">
							<a href="<?=Anchor(array('modul'=>'aNastaveni','akce'=>'editace-nastaveni','nid'=>$p->nid,'stranka'=>$stranka));?>" class="btn btn-icon btn-blue btn-small btn-fill isAjax" title="Upravit proměnnou"><em class="fa fa-pencil fa-1-4x"></em></a>
						</td>						
						<td><?=$p->nazev?></td>		
						<td>
							<?if($p->typ==0){?>Celé číslo<?}?>
							<?if($p->typ==1){?>Desetinné číslo<?}?>
							<?if($p->typ==2){?>Ano / Ne<?}?>
							<?if($p->typ==3){?>Krátký text<?}?>
							<?if($p->typ==4){?>Dlouhý text<?}?>
							<?if($p->typ==5){?>Text s&nbsp;formátováním<?}?>															
						</td>	
						<td>
							<?
								if($p->typ<3){
									echo '-';
								}elseif($p->typ==6||$p->typ==7||$p->typ==8){
									echo '-';
								}else{
									echo mb_strlen($p->hodnota,'UTF-8');
								}
							?>	
						</td>						
						<td>
							<?
								if($p->typ==0){
									echo (int)$p->hodnota;
								}elseif($p->typ==1){
									echo str_replace('.',',',$p->hodnota);
								}elseif($p->typ==2){
									if($p->hodnota==1){
										echo '<span class="color-dark-green"><em class="fa fa-check-circle"></em> Ano </span>';
									}else{
										echo '<span class="color-dark-red"><em class="fa fa-times-circle"></em> Ne</span>';
									}
								}else{
									if(mb_strlen($p->hodnota,'UTF-8')<128){
										echo str_replace(',',', ',trim(strip_tags($p->hodnota)));										
									}else{
										echo mb_substr(str_replace(',',', ',trim(strip_tags($p->hodnota))),0,128).'...';
									}
								}
							?>
						</td>						
																												
					</tr>
				<?}?>
			</table>			
		</div>	
		<br />
		<?=strankovani($strankovac,((int)getget('stranka','0')));?>
		<br />							
  <?}else{?>
  	<h2><em class="fa fa-exclamation-triangle"></em> Žádné nastavení nebylo nalezeno. </h2>  	  	
  <?}?>
  <br />  
</div>
