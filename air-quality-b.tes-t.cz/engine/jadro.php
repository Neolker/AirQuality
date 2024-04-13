<?php
session_start();  		
//inicializace chyb:
if(isset($_SESSION['mhm-error'])){
	$mhmError=(int)$_SESSION['mhm-error'];
}else{
	$mhmError=0;
}
if(isset($_GET['mhm-error'])||isset($_POST['mhm-error'])||$mhmError==1){
	if((isset($_GET['mhm-error'])&&$_GET['mhm-error']=='1')||(isset($_POST['mhm-error'])&&$_POST['mhm-error']=='1')||($mhmError==1)){
		ini_set('display_errors','1');
		error_reporting(E_ALL);
		$mhmError=1;
	}else{
		ini_set('display_errors','0');
		error_reporting(0);
		$mhmError=0;
	}
}else{
	ini_set('display_errors','0');
	error_reporting(0);
	$mhmError=0;
}
//vlozime konfiguraci:
if(file_exists('engine/konfigurace.php')){
	require_once('engine/konfigurace.php');
}else{
	die('KERNEL_ERROR_FL1: Unable to load config file "engine/konfigurace.php"!');
}
//vlozime systemove adresy:
if(file_exists('engine/systemovaUrl.php')){
	require_once('engine/systemovaUrl.php');
}else{
	die('KERNEL_ERROR_FL6: Unable to load config file "engine/systemovaUrl.php"!');
}
//hlavni trida systemu:
class Jadro{
	public $konfigurace;
	public $databaze; 
	public $uzivatel;  
	public $modely;	
	public $nastaveni;
	private $casStartu;
	private $casKonce;
	private $debugVars;
	static private $instance=NULL;  	
	public function __construct(){
    self::$instance=$this;
    $this->Inicializace();
    }  
	public function __destruct(){
		$this->Deinicializace();
		}  
	private function Inicializace(){
		$this->casStartu=microtime(true);				
    $konfigurace=new Konfigurace();
    $this->konfigurace=$konfigurace->NavratData();
    ini_set("memory_limit",$this->konfigurace->maximalniMemmoryLimit);    
    set_time_limit($this->konfigurace->maximalniTimeLimit);  
    foreach($this->konfigurace->systemy as $system){
    	if(file_exists($system)){
	    	require_once $system;
    	}else{
    		die('KERNEL_ERROR_FL2: Unable to load system file "'.$system.'"!');
    	}
    }  
    foreach($this->konfigurace->pomocnici as $pomocnik){
    	if(file_exists($pomocnik)){
	    	require_once $pomocnik;
    	}else{
    		die('KERNEL_ERROR_FL3: Unable to load helper file "'.$pomocnik.'"!');
    	}
    }   
    $this->databaze=new Databaze();
    $prihlaseno=$this->databaze->Login($this->konfigurace->databaze);
    if($prihlaseno!='E_SUCCESS'){
    	die('KERNEL_ERROR_DB1: Unable to connect to DB!');
    	}      
    $this->uzivatel=new stdClass();   
    $uzivatel=$this->databaze->MqueryGetLine('SELECT * FROM uzivatele WHERE session="'.session_id().'" AND session!="" limit 1');
    if(isset($uzivatel->uid)&&$uzivatel->uid>0){
      $this->uzivatel->uid=$uzivatel->uid;
      unset($uzivatel->heslo);
      unset($uzivatel->heslo_2);
      $this->uzivatel->data=$uzivatel;
      $this->databaze->Mquery('UPDATE uzivatele SET posledni_aktivita_ts="'.time().'" WHERE uid="'.((int)$this->uzivatel->uid).'"');        
    }else{
      $this->uzivatel->uid=0;
      $this->uzivatel->data=array();         
    }
   	$this->modely=new stdClass();
    foreach($this->konfigurace->modely as $modelK=>$modelH){
    	if(file_exists($modelK)){
	    	require_once $modelK;
	    	$this->modely->$modelH=new $modelH($this->databaze->getInstance());
    	}else{
    		die('KERNEL_ERROR_FL4: Unable to load model file "'.$modelK.'"!');
    	}
    }     
    foreach($this->konfigurace->moduly as $modulK=>$modulH){      	
    	if(file_exists('engine/url/url'.$modulK.'.php')){
	    	require_once 'engine/url/url'.$modulK.'.php';		    	
	    	}    
    	}
    if((getget('mhm-vars','')=='1'&&$this->uzivatel->uid>0&&$this->uzivatel->data->prava>1)||($this->uzivatel->uid>0&&$this->uzivatel->data->prava>1&&$this->uzivatel->data->varsmode==1)){   
			$this->debugVars=1;
		}else{
			$this->debugVars=0;
		}  
		$this->nastaveni=$this->nactiNastaveni();		
		GetRewritedData($this->databaze);  	
		}	
	private function Deinicializace(){
		$this->databaze->Logout();    
    $this->casKonce=microtime(true);    
    if((getget('mhm-info','')=='1'&&$this->uzivatel->uid>0&&$this->uzivatel->data->prava>1)||($this->uzivatel->uid>0&&$this->uzivatel->data->prava>1&&$this->uzivatel->data->infomode==1)){ 
    	$dblogy=$this->databaze->ReturnLog();	    
      $dbCas=0;
      foreach($dblogy as $dbl){
      	$dbCas+=$dbl->time_final;
      	}
      $vyuzitiRamek=convertMemory(memory_get_usage());         
      $casCelkem=round((($this->casKonce-$this->casStartu)),6);
      debugInfoBox($vyuzitiRamek,$casCelkem,$dbCas,$dblogy);      
      }  
		}
	public function spustModulDleURL(){
		global $mhmError;
		
		$getUrl=explode('?',trim(urldecode($_SERVER['REQUEST_URI'])));
		$getUrl2=$getUrl[0];
		$getUrl2=str_replace('/',' ',$getUrl2);
		$getUrl2=trim($getUrl2);		
		$urlModul=prepareGetDataSafely(getget('modul',''));
		
		if($urlModul!=''){
			if(isset($this->konfigurace->moduly[$urlModul])){
				$nastaveniModulu=$this->konfigurace->moduly[$urlModul];
			}else{
				Header("HTTP/1.1 301 Moved Permanently");
        _redirectBasic('/?modul='.$this->konfigurace->vychoziModul);
			}
		}else{
			$nastaveniModulu=$this->konfigurace->moduly[$this->konfigurace->vychoziModul];
		}
		if($this->uzivatel->uid>0){
			$prihlasenyUzivatel=1;
			$pravaUzivatele=(int)$this->uzivatel->data->prava;		
		}else{
			$prihlasenyUzivatel=0;
			$pravaUzivatele=0;
		}		
		if($nastaveniModulu->musiBytPrihlasenUzivatel==1){
			if($prihlasenyUzivatel==0){
				Redirect(array('modul'=>$this->konfigurace->vychoziModul));        
				}
			if(!in_array($pravaUzivatele,$nastaveniModulu->pravaProZobrazeni)){
				Redirect(array('modul'=>$this->konfigurace->vychoziModul));    
				}
			}
		if(file_exists($nastaveniModulu->souborTridy)){
			require_once $nastaveniModulu->souborTridy;
		}else{
  		die('KERNEL_ERROR_FL5: Unable to load module file "'.$nastaveniModulu->souborTridy.'"!');
  	}		
		$tridaModulu=$nastaveniModulu->nazevTridy;		
		$vlozenyModul=new $tridaModulu($this->konfigurace,$this->databaze,$this->modely,$this->uzivatel,$this->debugVars,$this->nastaveni);		
		$dataModulu=$vlozenyModul->hlavniFunkce();
		$seoModulu=$vlozenyModul->vratSeo();				
		$menu=generujMenuModulu($this->konfigurace->moduly,$nastaveniModulu->klic,$this->uzivatel);
		$index=new Sablona();		
		$index->nastavDebugMod($this->debugVars);		
		$index->pridatPromennou('verzeSystemu',$this->konfigurace->stavba);
		$index->pridatPromennou('dataModulu',$dataModulu);
		$index->pridatPromennou('seoModulu',$seoModulu);
		$index->pridatPromennou('nastaveniModulu',$nastaveniModulu);
		$index->pridatPromennou('uzivatel',$this->uzivatel);		
		$index->pridatPromennou('nastaveni',$this->nastaveni);								
		$index->pridatPromennou('menuModulu',$menu['menu']);
		$index->pridatPromennou('podmenuModulu',$menu['podmenu']);
		$index->pridatPromennou('mhmError',$mhmError);
		echo $index->spustit($nastaveniModulu->sablona);		
		}
	private function nactiNastaveni(){
		$nastaveni=array();		
		$nastaveniQ=$this->modely->NastaveniDb->getLines('klic,hodnota','ORDER BY poradi ASC, nid DESC');	
		if(isset($nastaveniQ)&&is_array($nastaveniQ)&&count($nastaveniQ)>0){
			foreach($nastaveniQ as $nQ){
				$nastaveni[prepareGetDataSafely($nQ->klic)]=$nQ->hodnota;
				}
			}
		unset($nastaveniQ);		
		return $nastaveni;
		}			
	}
