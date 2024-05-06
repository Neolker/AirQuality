<?php
class Sitemapa extends Modul{	
	public function hlavniFunkce(){		
		$akce=getget('akce','sitemapa');			
		if($akce=='robots'){return $this->vypisRobotsTxt();}		
		else{return $this->vypisPage404();}		
		return 'Modul> Sitemapa';	
		}
	private function vypisRobotsTxt(){
		header("Content-Type:text/plain");  
    echo $this->nastaveni['robots-txt'];    
    exit();
		}
	private function vypisPage404(){
		$this->nastavSeo('Stránka nenalezena','Vámi zadaná stránka nebyla nalezena v systému.','','');		
		header('HTTP/1.0 404 Not Found',true,404);		
		$tpl=new Sablona();		
		$tpl->nastavDebugMod($this->debugVars);
		return $tpl->spustit('sitemapa/strankaNenalezena.tpl');	
		}		
  }
