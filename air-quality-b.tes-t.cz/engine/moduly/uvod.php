<?php
class Uvod extends Modul{	
	public function hlavniFunkce(){		
		global $systemoveUrl;
		$this->nastavSeo('Vítejte','AirQuality','AirQuality','');
		$tpl=new Sablona();		
		$tpl->nastavDebugMod($this->debugVars);		
		$tpl->pridatPromennou('uzivatel',$this->uzivatel);		
		return $tpl->spustit('uvod/uvodniStranka.tpl');	
		}		
  }
