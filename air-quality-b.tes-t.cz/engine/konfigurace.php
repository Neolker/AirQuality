<?php
class Konfigurace{
  private $konfigurace;
  public function __construct(){
    $this->konfigurace=new stdClass(); 
    $this->konfigurace->stavba='5.24.4.12'; //generace.rok.mesic.den       
    $this->konfigurace->nazev=array('web'=>'air-quality-b.tes-t.cz');             
    $this->konfigurace->databaze=array(
      'host'=>'DOPLNIT',
      'user'=>'DOPLNIT',
      'password'=>'DOPLNIT',
      'db'=>'DOPLNIT',
      'charset'=>'utf8',
      'collation'=>'utf8_czech_ci'
      ); 
    $this->konfigurace->domena=$_SERVER['SERVER_NAME'];
    $this->konfigurace->domenaHttp='http://'.$_SERVER['SERVER_NAME'];
    $this->konfigurace->domenaHttps='https://'.$_SERVER['SERVER_NAME'];
    $this->konfigurace->maximalniMemmoryLimit='256M';
    $this->konfigurace->maximalniTimeLimit='300';         
    $this->konfigurace->kodovani=array(
      'charset'=>'utf8',
      'collation'=>'utf8_czech_ci'
      );             
    $this->konfigurace->pravaUzivatelu=array(
    	'0'=>'Neregistrovaný uživatel',
    	'1'=>'Registrovaný uživatel',
    	'2'=>'Administrátor',
    	'3'=>'Super administrátor'    	
    	);
    $this->konfigurace->systemy=array(
      'engine/system/zakladniFunkce.php',
      'engine/system/databaze.php',
      'engine/system/model.php',
      'engine/system/modul.php',			
      'engine/system/sablona.php',
      'engine/system/rewritery.php',
      'engine/system/menuModulu.php',           
      );            	    	
    $this->konfigurace->pomocnici=array(
      'engine/pomocnici/mhmPhpMailer.php',     
      );
    $this->konfigurace->modely=array(                     
      'engine/modely/namerenaDataDb.php'=>'NamerenaDataDb',           
      'engine/modely/nastaveniDb.php'=>'NastaveniDb',           			
			'engine/modely/uzivateleDb.php'=>'UzivateleDb',
			'engine/modely/zarizeniDb.php'=>'ZarizeniDb',           
      );        
    $this->konfigurace->moduly=array();
    $this->konfigurace->moduly['Uvod']=$this->vytvorModul('Uvod','Úvod','ROOT','Uvod','engine/moduly/uvod.php',0,'<fa class="fa fa-home"></fa>',array(0,1,2,3),0,'index.tpl');
    $this->konfigurace->moduly['Sitemapa']=$this->vytvorModul('Sitemapa','Mapa stránek','ROOT','Sitemapa','engine/moduly/sitemapa.php',0,'<fa class="fa fa-sitemap"></fa>',array(0,1,2,3),0,'index.tpl');
    $this->konfigurace->moduly['Administrace']=$this->vytvorModul('Administrace','Administrace','ROOT','Administrace','engine/moduly/administrace.php',1,'<fa class="fa-solid fa-cube"></fa>',array(0,1,2,3),0,'admin_index.tpl');    	    		
	    $this->konfigurace->moduly['aUzivatele']=$this->vytvorModul('aUzivatele','Uživatelé','ROOT','aUzivatele','engine/moduly/aUzivatele.php',1,'<fa class="fa fa-user-group"></fa>',array(2,3),1,'admin_index.tpl');	    
  		  $this->konfigurace->moduly['aSmazaniUzivatele']=$this->vytvorModul('aSmazaniUzivatele','Smazaní uživatelé','aUzivatele','aSmazaniUzivatele','engine/moduly/aUzivateleSmazaniUzivatele.php',1,'<fa class="fa fa-trash"></fa>',array(3),1,'admin_index.tpl');  	  
  		$this->konfigurace->moduly['aNastaveni']=$this->vytvorModul('aNastaveni','Nastavení','ROOT','aNastaveni','engine/moduly/aNastaveni.php',1,'<fa class="fa fa-screwdriver-wrench"></fa>',array(2,3),1,'admin_index.tpl');
  			$this->konfigurace->moduly['aPromenneNastaveni']=$this->vytvorModul('aPromenneNastaveni','Proměnné pro&nbsp;nastavení','aNastaveni','aPromenneNastaveni','engine/moduly/aNastaveniPromenneNastaveni.php',1,'<fa class="fa fa-list-check"></fa>',array(3),1,'admin_index.tpl');  		
    $this->konfigurace->vychoziModul='Uvod';                  	    
    }    
  private function vytvorModul($klic='modul',$nazevModulu='Modul',$klicNadrazenehoModulu='ROOT',$nazevTridy='Modul',$souborTridy='engine/moduly/modul.php',$aktivniVMenu=1,$ikonaVMenu='module-icon',$pravaProZobrazeni=array(),$musiBytPrihlasenUzivatel=0,$sablona='index.tpl'){
  	$modul=new stdClass();
  	$modul->klic=$klic;
  	$modul->nazevModulu=$nazevModulu;
  	$modul->klicNadrazenehoModulu=$klicNadrazenehoModulu;
  	$modul->nazevTridy=$nazevTridy;
  	$modul->souborTridy=$souborTridy;
  	$modul->aktivniVMenu=$aktivniVMenu;
  	$modul->ikonaVMenu=$ikonaVMenu;
  	$modul->pravaProZobrazeni=$pravaProZobrazeni;
  	$modul->musiBytPrihlasenUzivatel=$musiBytPrihlasenUzivatel;
  	$modul->sablona=$sablona;
		return $modul;  
  	}
  public function NavratData(){
  	return $this->konfigurace;
  	}
  }
