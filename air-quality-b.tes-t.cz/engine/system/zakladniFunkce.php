<?php
function timestampToDate($time=0){
  if($time==0) return strftime("%d. %m. %Y",time());
  else return strftime("%d. %m. %Y",$time);
  }
function timestampToDateTime($time=0){
  if($time==0) return '-';
  else return strftime("%d. %m. %Y %H:%M",$time);
  }
function timestampToTime($time=0){
  if($time==0) return strftime("%H:%M",time());
  else return strftime("%H:%M",$time);
  } 
function timestampToTimeSec($time=0){
  if($time==0) return strftime("%H:%M:%S",time());
  else return strftime("%H:%M:%S",$time);
  }     
function dateToTimestampStart($date){
  $date=str_replace(' ','',$date);
  $xdate=explode('.',$date);        
  return mktime(0,0,0,$xdate[1],$xdate[0],$xdate[2]);
  }  
function dateToTimestampMiddle($date){
  $date=str_replace(' ','',$date);
  $xdate=explode('.',$date);        
  return mktime(12,0,0,$xdate[1],$xdate[0],$xdate[2]);
  }    
function dateToTimestampEnd($date){
  $date=str_replace(' ','',$date);
  $xdate=explode('.',$date);        
  return mktime(23,59,59,$xdate[1],$xdate[0],$xdate[2]);
  }      
function getget($name,$default=''){
  if(isset($_GET[$name])){return $_GET[$name];}else{return $default;}
  }   
function getpost($name,$default=''){
  if(isset($_POST[$name])){return $_POST[$name];}else{return $default;}
  } 
function prepareGetDataSafely($data){
  $data=trim($data);
  $data=strip_tags($data);
  $data=stripslashes($data);
  $data=str_replace(array('"',"'"),array('',''),$data);
  $data=stripphp($data);
  return $data;     
  }   
function prepareGetDataSafelyEditor($data){
  $data=trim($data);       
  $data=stripphp($data);
  return $data;     
  }     
function convertMemory($size){
  $unit=array('B','KB','MB','GB','TB','PB');
  return number_format(@round($size/pow(1024,($i=floor(log($size,1024)))),3),3,',','&nbsp;').' '.$unit[$i];
  }    
function microtimeFloat(){
  list($usec,$sec)=explode(" ",microtime());
  return ((float)$usec+(float)$sec);
  }  
function checkMail($mail){
  $xmail=explode('@',$mail);
  if(!isset($xmail[1])){return false;}
  $xxmail=explode('.',$xmail[1]);
  if(!isset($xxmail[1])){return false;}
  return true;
  } 
function stripDiacritics($string){
  $search1=array('á','č','ď','í','ň','ó','ř','š','ť','ý','ž','é','ě','ú','ů');
  $search2=array('Á','Č','Ď','Í','Ň','Ó','Ř','Š','Ť','Ý','Ž','É','Ě','Ú','Ů');
  $replace=array('a','c','d','i','n','o','r','s','t','y','z','e','e','u','u');       
  $string=str_replace($search1,$replace,$string);
  $string=str_replace($search2,$replace,$string);
  return $string;
  }
function stripSpecialChars($string){
  $search=array('{','}','[',']','(',')','<','>','?','!','*','\\','+','@','#','$','%','=','&','^','~',';','|','.',':');
  $replace=array('_','_','_','_','_','_','_','_','_','_','_','_','_','_','_','_','_','_','_','_','_','_','_','_','_','_');
  $string=str_replace($search,$replace,$string);
  return $string;
  }
function stripPhp($string){
  $search=array('<'.'?','<'.'?php','?'.'>');
  $replace=array('PHPSTART','PHPSTART','PHPEND');
  $string=str_replace($search,$replace,$string);    
  return $string;
  } 
function stripAllSlashes($string){
  $search=array('"',"'");
  $replace=array('','');
  $string=str_replace($search,$replace,$string);    
  return $string;
  } 
function stripForAllowedChars($string){
  $newstr='';
  $chars=array('a','b','c','d','e','f','g','h','i','j','k','l','m','n','o','p','q','r','s','t','u','v','w','x','y','z','0','1','2','3','4','5','6','7','8','9','-','/');
  $oldstr=str_split($string,1);
  foreach($oldstr as $s){
    if(in_array($s,$chars)){
      $newstr.=$s;
      }
    }
  return $newstr;
  }
function generateNiceUrl($string){
  $string=strip_tags(trim($string));
  $string=stripspecialchars($string);
  $string=stripdiacritics($string);
  $string=stripAllSlashes($string);
  $string=strtolower($string); 
  $string=str_replace(
    array(',',' ','_','-----','----','---','--','---','----','-----'),
    array('-','-','-','-','-','-','-','-','-'),
    $string);
  $string=stripForAllowedChars($string);     
  return $string;
  }
function saltHashSha($password,$id=0,$registrationTimestamp='',$salt=''){  
  $stringToConvert='DCM0LUsvbG'.$password.'ZLER0oddxb'.$id.'5dJ3PV40gY'.$registrationTimestamp.'JP9ZrEjiSL'.$salt.'88fOKnuEQC';  
  $result='';
  $subStrings=str_split($stringToConvert,6);  
  $i=0;
  foreach($subStrings as $sS){
    $i++;
    if($i%3==0){$subHash='H4GnfO7IWO'.$id.'KVAJyO9owh'.$sS.'CGUXsBtbpr'.$registrationTimestamp.'OyxrvTnkdc';}
    if($i%3==1){$subHash='EO34esF0N6'.$registrationTimestamp.'p6AfNla2qW'.$sS.'Fcx1rTsnP7'.$id.'JI8ejGFTMm';}
    if($i%3==2){$subHash='OoumXo6T7Y'.$sS.'R6cM5Gek2o'.$registrationTimestamp.'kR72nqd8qD'.$id.'MkHM1pMZBw';}    
    $result.=hash('sha512',$subHash);               
    }  
  $resultHashed=hash('sha512',$salt.'u8Gx2zc0Ly'.$result.'42AiOjuLDy'.($registrationTimestamp%99));   
  return $resultHashed; 
  }
function debugInfoBox($memusage='',$totaltime=0,$dbTotalTime=0,$dblogs=array()){
	echo '<div class="kernel_info_box minimalize"><table width="100%">';
  echo '<tr>';  
  echo '<td colspan="2" class="align-left align-top"><a onclick="minimalizeMaximalizeInfoBox();"><i class="fa fa-up-down"></i></a> <a onclick="minimalizeMaximalizeInfoBox();">Zobrazení laděnky</a></td>';
  echo '<th width="150" class="align-top"><b>Celkový čas:</b></th><td width="100" class="align-top">'.number_format($totaltime,6,',',' ').' sec</td><td>&nbsp;</td></tr>';
  echo '<tr><td width="180" class="align-left align-top"><b>Operační paměť systému:</b></td><td width="85">'.str_replace(' ','&nbsp;',$memusage).'</td>';                  
  echo '<th><b>Čas systému:</b></th><td>'.number_format(($totaltime-$dbTotalTime),6,',',' ').' sec</td><td>&nbsp;</td></tr>';
  echo '<tr><td class="align-left align-top"><b>Počet databázových dotazů:</td><td>'.count($dblogs).'&nbsp;ks</td>';
  echo '<th><b>Čas databáze:</b></b></th><td>'.number_format($dbTotalTime,6,',',' ').' sec</td><td></td></tr>';  
  echo '<tr><td class="align-left align-top" colspan="5"><hr /><b>Použité databázové dotazy:</b></td></tr>';  
  foreach($dblogs as $dbl){echo '<tr><td colspan="5" class="align-left kernel_grey"><b>'.number_format($dbl->time_final,6,',',' ').'&nbsp;s.</b> | '.$dbl->query.'</td></tr>';}
  echo '</table></div>';
	}
function inicializujEditor($name='undefined',$value=''){
	return "\n".'<div style="width:100%;min-width:100%"><textarea rows="10" cols="80" class="editor" name="'.$name.'" id="editor_'.$name.'">'.$value.'</textarea></div>'."\n";
	}
function safeurlBase64Encode($url){
	return str_replace(array('=','+','/'),array('_','-','.'),base64_encode($url));
	}
function safeurlBase64Decode($url){
	return base64_decode(str_replace(array('_','-','.'),array('=','+','/'),$url));
	}
function strankovani($strankovac=array(),$stranka=0){
	if(count($strankovac)>5){
    echo ' <div class="align-center"> ';
    if($stranka==0){
      echo ' <button class="btn btn-icon btn-blue btn-small btn-fill" disabled=""><em class="fa fa-caret-left"></em></button> &nbsp; ';
		}else{
			echo ' <a class="btn btn-icon btn-blue btn-small btn-fill isAjax" title="Předchozí strana" href="'.$strankovac['predchozi'].'" ><i class="fa fa-caret-left"></i></a> &nbsp; '; 
		}
    echo ' <a class="btn btn-icon btn-small btn-fill '.(($stranka==0)?'':'btn-blue').' isAjax" title="První strana" href="'.$strankovac['prvni'].'" >1</a> ';
    if(count($strankovac)>8){echo ' &nbsp; ';}
    foreach($strankovac as $kp=>$vp){
    	if($kp!='predchozi'&&$kp!='dalsi'&&$kp!='prvni'&&$kp!='posledni'&&$kp!='1'&&$kp!=(count($strankovac)-4)){    	
        if($kp>($stranka-5)&&$kp<($stranka+7)){        
          echo ' <a class="btn btn-icon btn-small btn-fill '.(($stranka==($kp-1))?'':'btn-blue').' isAjax" href="'.$vp.'">'.$kp.'</a> ';
          }
        }
      }
    if(count($strankovac)>8){echo ' &nbsp; ';}
   	echo ' <a class="btn btn-icon btn-small btn-fill '.(($stranka==(count($strankovac)-5))?'':'btn-blue').' isAjax" title="Poslední strana" href="'.$strankovac['posledni'].'" >'.(count($strankovac)-4).'</a> &nbsp; ';       
		if($stranka==(count($strankovac)-5)){
    	echo ' <button class="btn btn-icon btn-blue btn-small btn-fill" disabled=""><em class="fa fa-caret-right"></em></button> ';
    }else{
      echo ' <a class="btn btn-icon btn-blue btn-small btn-fill isAjax" title="Následující strana" href="'.$strankovac['dalsi'].'"><i class="fa fa-caret-right"></i></a> ';
    }
    echo ' </div> ';
		}
	}
