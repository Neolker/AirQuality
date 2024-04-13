<?php
Class Modul{
  public $konfigurace;
	public $databaze; 
	public $modely;
	public $uzivatel;  
	public $debugVars; 
	public $nastaveni;	
	private $seo;
	private $preklady=array();
  public function __construct($konfigurace=null,$databaze=null,$modely=null,$uzivatel=null,$debugVars=0,$nastaveni=null){ 
    $this->konfigurace=$konfigurace;	
    $this->databaze=&$databaze;	
    $this->modely=$modely;	
    $this->uzivatel=$uzivatel;
    $this->debugVars=$debugVars;
    $this->nastaveni=$nastaveni;    
    $this->seo=new stdClass();
    $this->seo->titulek='';
    $this->seo->popis='';
    $this->seo->klicovaSlova='';
    $this->seo->uriObrazku='';
    }
  public function hlavniFunkce(){
  	return '';
  	}
  public function vratPreklady(){
  	return $this->preklady;
  	}    
  public function vratSeo(){
  	return $this->seo;
  	}
  public function nastavSeo($titulek='',$popis='',$klicovaSlova='',$uriObrazku=''){
  	$this->seo->titulek=prepareGetDataSafely($titulek);
    $this->seo->popis=prepareGetDataSafely($popis);
    $this->seo->klicovaSlova=prepareGetDataSafely($klicovaSlova);
    $this->seo->uriObrazku=prepareGetDataSafely($uriObrazku);
  	}	
  }
