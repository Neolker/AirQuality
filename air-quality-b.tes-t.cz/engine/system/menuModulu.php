<?php
function generujMenuModulu($moduly=array(),$aktivni='',$uzivatel=null){
	if($uzivatel->uid>0){
		$prihlasenyUzivatel=1;
		$pravaUzivatele=(int)$uzivatel->data->prava;		
	}else{
		$prihlasenyUzivatel=0;
		$pravaUzivatele=0;
	}
	$aktivni=trim($aktivni);
	$menu='<ul class="no-print">';
	$aktivniPodmenu='';
	foreach($moduly as $m){
		if($m->aktivniVMenu==0){
			continue;
			}
		if($m->klicNadrazenehoModulu!='ROOT'){
			continue;
			}
		if($m->musiBytPrihlasenUzivatel==1&&$prihlasenyUzivatel==0){
			continue;
			}
		if($prihlasenyUzivatel==1&&in_array($pravaUzivatele,$m->pravaProZobrazeni)==false){
			continue;
			}
		$podmoduly=array();
		$aktivniPodmodul=0;
		foreach($moduly as $m2){
			if($m2->klicNadrazenehoModulu=='ROOT'){
				continue;
				}
			if($m2->aktivniVMenu==0){
				continue;
				}
			if($m2->klicNadrazenehoModulu!=$m->klic){
				continue;
				}
			if($m2->musiBytPrihlasenUzivatel==1&&$prihlasenyUzivatel==0){
				continue;
				}
			if($prihlasenyUzivatel==1&&in_array($pravaUzivatele,$m2->pravaProZobrazeni)==false){
				continue;
				}
			$podmoduly[$m2->klic]=$m2;	
			if($m2->klic==$aktivni&&$aktivni!=''){
				$aktivniPodmodul=1;
				}
			}
		$menu.='<li ';
		if($aktivni!=''){
			if($aktivniPodmodul==1||$aktivni==$m->klic){
				$menu.=' class="active" ';
				$aktivniPodmenu.='<li ';
				if($aktivni==$m->klic){
					$aktivniPodmenu.=' class="active" ';
					}
				$aktivniPodmenu.='>';
				$aktivniPodmenu.='<a href="'.Anchor(array('modul'=>$m->klic)).'" title="'.str_replace('"','',$m->nazevModulu).'">';				
				$aktivniPodmenu.=''.$m->ikonaVMenu.' &nbsp;'.$m->nazevModulu;
				$aktivniPodmenu.='</a>';
				$aktivniPodmenu.='</li>';
				}
			}
		$menu.='>';
			$menu.='<a href="'.Anchor(array('modul'=>$m->klic)).'" title="'.str_replace('"','',$m->nazevModulu).'">';
				$menu.=$m->ikonaVMenu.' &nbsp;'.$m->nazevModulu;
			$menu.='</a>';
			if(count($podmoduly)>0){
				$menu.='<ul>';
				foreach($podmoduly as $pm){
					$menu.='<li ';
					if($aktivni!=''){
						if($aktivni==$pm->klic){
							$menu.=' class="active" ';
							}
						}
					$menu.='>';
						$menu.='<a href="'.Anchor(array('modul'=>$pm->klic)).'" title="'.str_replace('"','',$pm->nazevModulu).'">';
							$menu.=$pm->ikonaVMenu.' &nbsp;'.$pm->nazevModulu;
						$menu.='</a>';
					$menu.='</li>';
					if($aktivniPodmodul==1||$aktivni==$m->klic){
						$aktivniPodmenu.='<li ';
						if($aktivni!=''){
							if($aktivni==$pm->klic){
								$aktivniPodmenu.=' class="active" ';
								}
							}
						$aktivniPodmenu.='>';
						$aktivniPodmenu.='<a href="'.Anchor(array('modul'=>$pm->klic)).'" title="'.str_replace('"','',$pm->nazevModulu).'">';						
						$aktivniPodmenu.=''.$pm->ikonaVMenu.' &nbsp;'.$pm->nazevModulu;
						$aktivniPodmenu.='</a>';
						$aktivniPodmenu.='</li>';
						}					
					}
				$menu.='</ul>';
				}
		$menu.='</li>';
		}
	$menu.='</ul>';
	if(trim($aktivniPodmenu)!=''){$aktivniPodmenu='<ul class="activeSubMenu">'.$aktivniPodmenu.'</ul>';}
	return array('menu'=>$menu,'podmenu'=>$aktivniPodmenu);
	}
