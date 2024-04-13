<?if(getget('m','')=='nastaveni-ulozeno'){?><div class="m-wrap bg-light-green"> <em class="fa fa-check-circle"></em> Nastavení úspěšně uloženo.</div><?}?>

<div class="wrap bg-cream main-wrap"> 
	<div class="row">       
	  <div class="col-xs-12 col-md-6 col-sm-8 pad-0 gap-0 align-left">
		  <h1 class="gap-0"><fa class="fa fa-screwdriver-wrench"></fa> &nbsp; Nastavení - <?=$promenna->nazev?></h1>
	  </div>
	  <div class="col-xs-12 col-md-6 col-sm-4 pad-0 gap-0 <?if($promenna->typ<5){?>align-right<?}else{?>align-right<?}?>">
	  	<a href="<?=Anchor(array('modul'=>'aNastaveni','akce'=>'vypis-nastaveni','stranka'=>$stranka));?>" class="button btn-fill btn-blue isAjax" >
	  		<fa class="fa fa-chevron-circle-left"></fa> &nbsp; Zpět na výpis nastavení
	  	</a>
	  </div>
  </div> 
	<br />
	<form method="post" action="<?=Anchor(array('modul'=>'aNastaveni','akce'=>'uloz-nastaveni','nid'=>$promenna->nid,'stranka'=>$stranka));?>" class="isAjaxForm" <?/*onsubmit="return confirm('Opravdu si přejete uložit ...?');"*/?> >
		<div class="row middle-xs">
				<div class="col-md-1 col-sm-2 col-xs-4 pad-y-8 <?if($promenna->typ<5){?>align-right<?}else{?>align-left<?}?> bold" >Název:</div>
				<div class="col-md-5 col-sm-4 col-xs-8 pad-y-8" ><?=$promenna->nazev?></div>
				
				<div class="col-md-1 col-sm-2 col-xs-4 pad-y-8 <?if($promenna->typ<5){?>align-right<?}else{?>align-left<?}?> bold" >Typ:</div>
				<div class="col-md-5 col-sm-4 col-xs-8 pad-y-8" >
					<?if($promenna->typ==0){?>Celé číslo<?}?>
					<?if($promenna->typ==1){?>Desetinné číslo<?}?>
					<?if($promenna->typ==2){?>Ano / Ne<?}?>
					<?if($promenna->typ==3){?>Krátký text<?}?>
					<?if($promenna->typ==4){?>Dlouhý text<?}?>
					<?if($promenna->typ==5){?>Text s&nbsp;formátováním<?}?>						
				</div>																																
			</div>
			<div class="row">
				<div class="col-md-1 col-sm-2 col-xs-4 pad-y-8 <?if($promenna->typ<5){?>align-right<?}else{?>align-left<?}?> bold" >Popis:</div>
				<div class="col-md-11 col-sm-10 col-xs-8 pad-y-8" ><?=$promenna->popis?></div>
			</div>
			<div class="row">
				<?if($promenna->typ==0||$promenna->typ==1||$promenna->typ==2||$promenna->typ==3||$promenna->typ==4){?>
					<div class="col-md-1 col-sm-2 col-xs-4 pad-y-8 align-right bold" >Hodnota:</div>
					<div class="col-md-11 col-sm-10 col-xs-8 pad-y-8" >
						<?if($promenna->typ==0){?>
							<input type="number" name="hodnota" class="w-100" step="1" value="<?=$promenna->hodnota?>" />
						<?}?>
						<?if($promenna->typ==1){?>
							<input type="number" name="hodnota" class="w-100" step="0.00001" value="<?=$promenna->hodnota?>" />
						<?}?>
						<?if($promenna->typ==2){?>
							<label><input type="radio" name="hodnota" value="1" <?if($promenna->hodnota==1){?>checked<?}?> /> Ano</label> &nbsp;&nbsp;&nbsp;
							<label><input type="radio" name="hodnota" value="0" <?if($promenna->hodnota!=1){?>checked<?}?> /> Ne</label> 						
						<?}?>
						<?if($promenna->typ==3){?>
							<textarea name="hodnota" class="w-100" rows="3" ><?=$promenna->hodnota?></textarea>
						<?}?>
						<?if($promenna->typ==4){?>
							<textarea name="hodnota" class="w-100" rows="9" ><?=$promenna->hodnota?></textarea>
						<?}?>	
					</div>
				<?}else{?>					
					<div class="col-md-12 col-sm-12 col-xs-12 pad-y-8" >					
						<?if($promenna->typ==5){?>
							<textarea rows="10" cols="80" class="summernote" name="hodnota"><?=$promenna->hodnota?></textarea>					
						<?}?>						
					</div>
				<?}?>
			</div>			
			<?if($promenna->typ==6||$promenna->typ==7||$promenna->typ==8){}else{?>
				<div class="row middle-xs">							
					<div class="col-md-9 col-sm-8 col-xs-4 pad-y-8 bold" >&nbsp;</div>										
					<div class="col-md-3 col-sm-4 col-xs-8 align-right " ><button type="submit" class="btn-fill btn-blue w-100"><em class="fa fa-floppy-disk"></em> &nbsp; Uložit</button></div>				
				</div>										
			<?}?>
	</form>
	<br />									
	<br />
</div>
