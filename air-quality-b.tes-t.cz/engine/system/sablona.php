<?php
class Sablona{
  private $promenne;  
  private $debug;   
  public function __construct($debug=0){
  	$this->promenne=array();
  	$this->debug=(int)$debug;
  	}
  public function nastavDebugMod($debug=1){
  	$this->debug=(int)$debug;
  	}
  public function pridatPromennou($klic='',$hodnota=''){
  	$this->promenne[$klic]=$hodnota;
  	}
  public function spustit($cestaSablony='',$slozkaSablon='engine/sablony/'){                
    $cesta=$slozkaSablon.$cestaSablony;
    $handle=fopen($cesta,'r');
    if(!$handle){return false;}
    $obsah=fread($handle,filesize($cesta));            
    fclose($handle);
    $obsah=trim($obsah);
    $obsah=str_replace('<?=','<? echo ',$obsah);
    $obsah=str_replace('<?','<?php ',$obsah);
    $obsah2=$this->vyhodnotitPHP($obsah);
    unset($obsah);
    if($this->debug==1){        
      $cesta2=str_replace(array('/','.'),array('',''),$cesta);
      $hash=rand().'_'.$cesta2.'_tpl';
      $obsah='<div class="kernel_templates"><a onclick="javascript:$(\'.'.$hash.'\').toggle();">'.$cesta.'</a><div style="display:none" class="'.$hash.'">';
      if(is_array($this->promenne)){
        foreach ($this->promenne as $klic => $hodnota){   
          if(is_string($hodnota)){                                               
            if(strpos($hodnota,'<div class="kernel_templates">')===false){}else{                                              
              $obsah.='<b>'.$klic.'</b> je typu ŠABLONA<br>';
              continue;
              }
            }                 
          $obsah.='<b>'.$klic.':</b> '.print_r($hodnota,true).'<br>';
          }
        }
      $obsah.='</div></div>';      
      $obsah2=$obsah2.$obsah; // pidat az nakonec kvuli ajaxu!
      }
    return $obsah2;
    }
  private function vyhodnotitPHP($retezec=''){
    ob_start();
    if(is_array($this->promenne)){foreach($this->promenne as $klic=>$hodnota){$$klic=$hodnota;}}
    $er=error_reporting(E_ERROR|E_PARSE); 
    eval('?'.'>'.$retezec.'<'.'?');
    error_reporting($er);
    $ret=ob_get_contents();
    ob_end_clean();
    return $ret;
    }
  }
