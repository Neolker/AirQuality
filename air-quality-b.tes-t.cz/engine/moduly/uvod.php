<?php
class Uvod extends Modul{	
	public function hlavniFunkce(){		
		global $systemoveUrl;
		$this->nastavSeo('úvodní strana','seo popis','seo kw','');
		$tpl=new Sablona();		
		$tpl->nastavDebugMod($this->debugVars);		
		$tpl->pridatPromennou('uzivatel',$this->uzivatel);		
		return $tpl->spustit('uvod/uvodniStranka.tpl');	
		}		
  }
