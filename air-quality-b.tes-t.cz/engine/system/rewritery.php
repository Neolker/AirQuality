<?php
// hlavni funkce rewriteru:
function Anchor($url=array(),$sharp='',$base='/'){ // odkaz
  $return=_rewritePrepare($url,$base); 
  return _anchor($return->url,$return->base,$sharp);
  }
function Redirect($url=array(),$sharp='',$base='/'){ // presmerovani
  $return=_rewritePrepare($url,$base);
  _redirect($return->url,$return->base,$sharp);
  }  
function GetRewritedData($db){
	global $systemoveUrl;
	$geturl=explode('?',trim(urldecode($_SERVER['REQUEST_URI'])));   
  $xget=array();           
  if($geturl[0][0]=='/'){
    $geturl[0][0]=' ';
    $geturl[0]=trim($geturl[0]);
    }     
  if($geturl[0]!=''){   
		  if(in_array($geturl[0],$systemoveUrl)){ // ulr z cache (systemova url)
		    $data_system_url=array_search($geturl[0],$systemoveUrl);
		   	$data2=explode('#',$data_system_url);    
		    $data3=str_replace(array('/?','?'),array('&','&'),$data2[0]);
				$data4=explode('&',$data3);
		    foreach($data4 as $d){      
		      $data5=explode('=',$d);
		      if($data5[0]!=''){
		        if(!isset($_GET[$data5[0]])){$xget[$data5[0]]=$data5[1];}
		        }
		      }            
    	}       
    }
  if(count($xget)>0){
	  $_GET=array_merge($xget,$_GET);   
	  }    
	}     
// podpurne funkce rewriteru:
function _unHtmlEntities($str){
  $trans=get_html_translation_table(HTML_ENTITIES);
  $trans=array_flip($trans);
  return strtr($str,$trans);
  }
function _anchor($url=array(),$base='/',$sharp=''){
  $i=0;
  foreach($url as $k=>$v){
    if($i==0){
      $base.='?'._unHtmlEntities($k).'='._unHtmlEntities($v);
    }else{
      $base.='&'._unHtmlEntities($k).'='._unHtmlEntities($v);
    }    
    $i++;
    }
  return $base.$sharp;
  }
function _redirect($url=array(),$base='/',$sharp=''){
  $url2=_anchor($url,$base);  
  header("Location: ".$url2.$sharp);
  exit();
  }
function _redirectBasic($url='/'){   
  header("Location: ".$url);
  exit();
  }
function _rewritePrepare($url=array(), $base='/'){
  global $systemoveUrl;  
  $return=new stdClass(); 
  $url2=$url;
  $testbase='/';
  $findbase='/';
  $datatounset=array();
  foreach($url2 as $ua=>$ub){
    if($testbase=='/'){
      $testbase.='?'.$ua.'='.$ub;  
    }else{
      $testbase.='&'.$ua.'='.$ub;
    }
    $datatounset[]=$ua;
    if(isset($systemoveUrl[$testbase])){
      $data=$systemoveUrl[$testbase];
    }else{        
      $data='';               
    }
    if($data!=''){
      $findbase='/'.$data;
      foreach($datatounset as $dtu){
        if(isset($url2[$dtu])){
          unset($url2[$dtu]);            
          }          
        }
      }
    }
  $return->base=$findbase;
  $return->url=$url2;    
  return $return;
  }
