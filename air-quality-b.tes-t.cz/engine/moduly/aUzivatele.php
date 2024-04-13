<?php
class aUzivatele extends Modul{	
	public function hlavniFunkce(){		
		$akce=getget('akce','vypis-uzivatelu');
		if($this->uzivatel->uid>0){
			if($akce=='vypis-uzivatelu'){return $this->vypisUzivatelu();}
			elseif($akce=='zmena-razeni'){return $this->zmenaRazeni();}			
			elseif($akce=='zmena-vyhledavani'){return $this->zmenaVyhledavani();}			
			elseif($akce=='editace-uzivatele'){return $this->editaceUzivatele();}					
			elseif($akce=='pridej-uzivatele'){return $this->pridejUzivatele();}
			elseif($akce=='uloz-uzivatele'){return $this->ulozUzivatele();}
			elseif($akce=='uloz-uzivatele-2'){return $this->ulozUzivatele2();}
			elseif($akce=='uloz-heslo-uzivatele'){return $this->ulozHesloUzivatele();}
			elseif($akce=='smaz-uzivatele'){return $this->smazUzivatele();}				
			else{Redirect(array('isAjax'=>((int)getget('isAjax','0'))));}
		}else{
			Redirect(array('isAjax'=>((int)getget('isAjax','0'))));
		}
		return 'Modul> Administrace / Uživatelé';	
		}	
	private function zmenaRazeni(){
		$_SESSION['uzivatele-razeni']=prepareGetDataSafely(getget('typ',''));
		$stranka=(int)getget('stranka','0');
		Redirect(array('modul'=>'aUzivatele','akce'=>'vypis-uzivatelu','stranka'=>$stranka,'isAjax'=>((int)getget('isAjax','0')) )); 
		}
	private function zmenaVyhledavani(){
		$_SESSION['uzivatele-vyhledavani']=trim(prepareGetDataSafely(getpost('fulltext','')));
		Redirect(array('modul'=>'aUzivatele','akce'=>'vypis-uzivatelu','isAjax'=>((int)getget('isAjax','0')) )); 
		}
	private function vypisUzivatelu(){			
		if(isset($_SESSION['uzivatele-razeni'])){
      $razeni=$_SESSION['uzivatele-razeni'];
    }else{
	    $razeni='login';
    }				
    if($razeni=='login'){
    	$razeniQ=' ORDER BY login ASC, spolecnost ASC, prijmeni ASC, jmeno ASC, titul DESC ';
    }elseif($razeni=='jmeno'){
    	$razeniQ=' ORDER BY prijmeni ASC, jmeno ASC, titul DESC ';
    }else{
    	$razeniQ=' ORDER BY spolecnost ASC, prijmeni ASC, jmeno ASC, titul DESC ';
    }
    $filtrQ=' WHERE odstraneny_uzivatel=0 ';
    if(isset($_SESSION['uzivatele-vyhledavani'])&&trim($_SESSION['uzivatele-vyhledavani'])!=''){
      $fulltext=trim(prepareGetDataSafely($_SESSION['uzivatele-vyhledavani']));
      $filtrQ.=' AND (login LIKE "%'.$fulltext.'%" OR email LIKE "%'.$fulltext.'%" OR nazev LIKE "%'.$fulltext.'%" OR telefon LIKE "%'.$fulltext.'%") ';
    }else{
    	$fulltext='';
    }
		$stranka=(int)getget('stranka','0');
		$citac=$this->nastaveni['administrace-pocet-strankovani'];		
		if($citac<1){$citac=1;}
		if($citac>100){$citac=100;}	
		$uzivatele=$this->modely->UzivateleDb->getLines('*',' '.$filtrQ.' '.$razeniQ.' LIMIT '.($stranka*$citac).', '.$citac);
		$pocet=$this->modely->UzivateleDb->getOne('count(uid)',' '.$filtrQ.' ');
		$strankovac=$this->strankovacUzivatelu($stranka,$pocet,$citac);
		$uzivateleUidLogin=array('0'=>' - Nepřiřazeno - ');
		if(isset($uzivatele)&&is_array($uzivatele)&&count($uzivatele)>0){
			foreach($uzivatele as $uk=>$u){
				unset($uzivatele[$uk]->session);
				unset($uzivatele[$uk]->heslo);
				unset($uzivatele[$uk]->heslo_2);
				$uzivateleUidLogin[$u->uid]=$u->login;
				}
			}		
    if(isset($_SESSION['uzivatele-pridej-uzivatele'])){
      $novyUzivatel=$_SESSION['uzivatele-pridej-uzivatele'];
    }else{
	    $novyUzivatel=$this->_prazdnyNovyUzivatel();
    }				
		$tpl=new Sablona();		
		$tpl->nastavDebugMod($this->debugVars);
		$tpl->pridatPromennou('novyUzivatel',$novyUzivatel);
		$tpl->pridatPromennou('stranka',$stranka);
		$tpl->pridatPromennou('uzivatele',$uzivatele);		
		$tpl->pridatPromennou('strankovac',$strankovac);
		$tpl->pridatPromennou('razeni',$razeni);
		$tpl->pridatPromennou('fulltext',$fulltext);				
		$tpl->pridatPromennou('uzivateleUidLogin',$uzivateleUidLogin);
		$tpl->pridatPromennou('pravaUzivatelu',$this->konfigurace->pravaUzivatelu);
		$tpl->pridatPromennou('prihlasenyUzivatel',$this->uzivatel->data);			
		return $tpl->spustit('aUzivatele/vypisUzivatelu.tpl');	
		}
	private function strankovacUzivatelu($stranka=0,$pocet=0,$citac=0){        
    $stranky=array();$maxstranka=0;$citatDo=ceil($pocet/$citac);
    for($i=0;$i<$citatDo;$i++){$stranky[($i+1)]=Anchor(array('modul'=>'aUzivatele','akce'=>'vypis-uzivatelu','stranka'=>$i),false);$maxstranka=$i;}
    if(($stranka-1)<0){$stranky['predchozi']=Anchor(array('modul'=>'aUzivatele','akce'=>'vypis-uzivatelu','stranka'=>'0'),false);}else{$stranky['predchozi']=Anchor(array('modul'=>'aUzivatele','akce'=>'vypis-uzivatelu','stranka'=>($stranka-1)),false);}
    if(($stranka+1)>$maxstranka){$stranky['dalsi']=Anchor(array('modul'=>'aUzivatele','akce'=>'vypis-uzivatelu','stranka'=>$maxstranka),false);}else{$stranky['dalsi']=Anchor(array('modul'=>'aUzivatele','akce'=>'vypis-uzivatelu','stranka'=>($stranka+1)),false);}
    $stranky['prvni']=Anchor(array('modul'=>'aUzivatele','akce'=>'vypis-uzivatelu','stranka'=>0),false);
    $stranky['posledni']=Anchor(array('modul'=>'aUzivatele','akce'=>'vypis-uzivatelu','stranka'=>($citatDo-1)),false);    
    return $stranky;          
    } 
	private function editaceUzivatele(){
		$uid=(int)getget('uid','0');			
		$stranka=(int)getget('stranka','0');	
		$uzivatel=$this->modely->UzivateleDb->getLine('*','WHERE uid="'.$uid.'"');
		if(isset($uzivatel->uid)&&$uzivatel->uid>0){
			if(($uzivatel->prava<3&&$uzivatel->prava<=$this->uzivatel->data->prava)||$uzivatel->uid==$this->uzivatel->data->uid){}else{
				Redirect(array('modul'=>'aUzivatele','akce'=>'vypis-uzivatelu','m'=>'nemate-prava-uzivatele','isAjax'=>((int)getget('isAjax','0')) )); 						
				}					
			unset($uzivatel->session);
			unset($uzivatel->heslo);
			unset($uzivatel->heslo_2);					
			$tpl=new Sablona();		
			$tpl->nastavDebugMod($this->debugVars);
			$tpl->pridatPromennou('uzivatel',$uzivatel);	
			$tpl->pridatPromennou('pravaUzivatelu',$this->konfigurace->pravaUzivatelu);
			$tpl->pridatPromennou('prihlasenyUzivatel',$this->uzivatel->data);		
			$tpl->pridatPromennou('stranka',$stranka);	
			return $tpl->spustit('aUzivatele/editaceUzivatele.tpl');	
			}
		Redirect(array('modul'=>'aUzivatele','akce'=>'vypis-uzivatelu','m'=>'uzivatel-nenalezen','isAjax'=>((int)getget('isAjax','0')) ));    
		}
	private function ulozUzivatele(){
		$uid=(int)getget('uid','0');
		$stranka=(int)getget('stranka','0');					
		$uzivatel=$this->modely->UzivateleDb->getLine('*','WHERE uid="'.$uid.'"');
		if(isset($uzivatel->uid)&&$uzivatel->uid>0){
			if(($uzivatel->prava<3&&$uzivatel->prava<=$this->uzivatel->data->prava)||$uzivatel->uid==$this->uzivatel->data->uid){}else{
				Redirect(array('modul'=>'aUzivatele','akce'=>'vypis-uzivatelu','m'=>'nemate-prava-uzivatele','isAjax'=>((int)getget('isAjax','0')) )); 						
				}			
			$postdata=array(
				'email'=>prepareGetDataSafely(getpost('email','')),
				'telefon'=>prepareGetDataSafely(getpost('telefon','')),
				'titul'=>prepareGetDataSafely(getpost('titul','')),
				'jmeno'=>prepareGetDataSafely(getpost('jmeno','')),
				'prijmeni'=>prepareGetDataSafely(getpost('prijmeni','')),
				'spolecnost'=>prepareGetDataSafely(getpost('spolecnost',''))
				);
			$postdata['nazev']=trim($postdata['titul'].' '.$postdata['jmeno'].' '.$postdata['prijmeni'].' '.$postdata['spolecnost'].' ');
			if(trim($postdata['email'])==''){
				Redirect(array('modul'=>'aUzivatele','akce'=>'editace-uzivatele','m'=>'uzivatel-neulozen','uid'=>$uid,'stranka'=>$stranka,'isAjax'=>((int)getget('isAjax','0')) ));
				}
			$this->modely->UzivateleDb->updateId($uid,$postdata);  
			Redirect(array('modul'=>'aUzivatele','akce'=>'editace-uzivatele','m'=>'uzivatel-ulozen','uid'=>$uid,'stranka'=>$stranka,'isAjax'=>((int)getget('isAjax','0')) ));
			}
		Redirect(array('modul'=>'aUzivatele','akce'=>'vypis-uzivatelu','m'=>'uzivatel-nenalezen','isAjax'=>((int)getget('isAjax','0')) ));   
		}
	private function ulozHesloUzivatele(){
		$uid=(int)getget('uid','0');	
		$stranka=(int)getget('stranka','0');				
		$uzivatel=$this->modely->UzivateleDb->getLine('*','WHERE uid="'.$uid.'"');
		if(isset($uzivatel->uid)&&$uzivatel->uid>0){
			if(($uzivatel->prava<3&&$uzivatel->prava<=$this->uzivatel->data->prava)||$uzivatel->uid==$this->uzivatel->data->uid){}else{
				Redirect(array('modul'=>'aUzivatele','akce'=>'vypis-uzivatelu','m'=>'nemate-prava-uzivatele','isAjax'=>((int)getget('isAjax','0')) )); 						
				}			
			$postdata=array(
				'heslo'=>prepareGetDataSafely(getpost('heslo','')),
				'heslo_nove'=>prepareGetDataSafely(getpost('heslo_nove','')),				
				);
			//////
			$data=$this->databaze->MqueryGetLine('SELECT uid,heslo,heslo_2,pocet_prihlaseni,registrace_ts FROM uzivatele WHERE uid="'.((int)$this->uzivatel->data->uid).'" AND aktivni_uzivatel=1 limit 1');
      if(isset($data->uid)&&$data->uid>0){
        $login=false;
        if((saltHashSha($postdata['heslo'],$data->uid,$data->registrace_ts,'SaltOfMHMcubeEnterprise')==$data->heslo)&&$data->heslo!=''&&$postdata['heslo']!=''){$login=true;}
        if((saltHashSha($postdata['heslo'],$data->uid,$data->registrace_ts,'SaltOfMHMcubeEnterprise')==$data->heslo_2)&&$data->heslo_2!=''&&$postdata['heslo']!=''){$login=true;}
        if($login==true&&$postdata['heslo_nove']!=''){
					//
					$newpassHash=saltHashSha($postdata['heslo_nove'],$uzivatel->uid,$uzivatel->registrace_ts,'SaltOfMHMcubeEnterprise');
		   		$this->modely->UzivateleDb->updateId($uzivatel->uid,array('heslo'=>$newpassHash,'heslo_2'=>''));
					Redirect(array('modul'=>'aUzivatele','akce'=>'editace-uzivatele','m'=>'heslo-zmeneno','uid'=>$uid,'stranka'=>$stranka,'isAjax'=>((int)getget('isAjax','0')) ));
					//
					}
				}
			///////
			Redirect(array('modul'=>'aUzivatele','akce'=>'editace-uzivatele','m'=>'heslo-nezmeneno','uid'=>$uid,'stranka'=>$stranka,'isAjax'=>((int)getget('isAjax','0')) ));				  			
			}
		Redirect(array('modul'=>'aUzivatele','akce'=>'vypis-uzivatelu','m'=>'uzivatel-nenalezen','isAjax'=>((int)getget('isAjax','0')) ));   
		}
	private function ulozUzivatele2(){
		$uid=(int)getget('uid','0');
		$stranka=(int)getget('stranka','0');					
		$uzivatel=$this->modely->UzivateleDb->getLine('*','WHERE uid="'.$uid.'"');
		if(isset($uzivatel->uid)&&$uzivatel->uid>0){			
			if(($uzivatel->prava<3&&$uzivatel->prava<=$this->uzivatel->data->prava)||$uzivatel->uid==$this->uzivatel->data->uid){}else{
				Redirect(array('modul'=>'aUzivatele','akce'=>'vypis-uzivatelu','m'=>'nemate-prava-uzivatele','isAjax'=>((int)getget('isAjax','0')) )); 						
				}							
			$postdata=array(
				'prava'=>prepareGetDataSafely(getpost('prava','')),				
				'aktivni_uzivatel'=>prepareGetDataSafely(getpost('aktivni_uzivatel',''))							
				);	
			if($postdata['prava']>$this->uzivatel->data->prava){$postdata['prava']=$this->uzivatel->data->prava;}		
			$this->modely->UzivateleDb->updateId($uid,$postdata);  
			Redirect(array('modul'=>'aUzivatele','akce'=>'editace-uzivatele','m'=>'uzivatel-ulozen','uid'=>$uid,'stranka'=>$stranka,'isAjax'=>((int)getget('isAjax','0')) ));
			}
		Redirect(array('modul'=>'aUzivatele','akce'=>'vypis-uzivatelu','m'=>'uzivatel-nenalezen','isAjax'=>((int)getget('isAjax','0')) ));   
		}
	private function pridejUzivatele(){
		$postdata=array();
    $sesdata=new stdClass();
    foreach($_POST as $k=>$v){     
      $a=prepareGetDataSafely($k);
      $b=prepareGetDataSafely($v);
      $postdata[$a]=prepareGetDataSafely($b);      
      $sesdata->$a=prepareGetDataSafely($b);     
      } 
    $_SESSION['uzivatele-pridej-uzivatele']=$sesdata;
		$existuje=(int)$this->modely->UzivateleDb->getOne('uid','WHERE login="'.$postdata['login'].'"');
		if($this->uzivatel->data->prava<2){Redirect(array('modul'=>'aUzivatele','akce'=>'vypis-uzivatelu','m'=>'nemate-prava-vytvorit','isAjax'=>((int)getget('isAjax','0')) ));}  
    if($existuje>0){Redirect(array('modul'=>'aUzivatele','akce'=>'vypis-uzivatelu','m'=>'login-jiz-existuje','isAjax'=>((int)getget('isAjax','0')) ));}   
    if($postdata['login']==''){Redirect(array('modul'=>'aUzivatele','akce'=>'vypis-uzivatelu','m'=>'login-je-kratky','isAjax'=>((int)getget('isAjax','0')) ));}
    if($postdata['heslo']==''){Redirect(array('modul'=>'aUzivatele','akce'=>'vypis-uzivatelu','m'=>'heslo-je-kratke','isAjax'=>((int)getget('isAjax','0')) ));}    
    $heslo=$postdata['heslo'];
    $postdata['heslo']=md5('xxxxx'.rand(10,1000).time());    
    $postdata['registrace_ts']=time();
    if($postdata['prava']>$this->uzivatel->data->prava){$postdata['prava']=$this->uzivatel->data->prava;}
    $_SESSION['uzivatele-pridej-uzivatele']=$this->_prazdnyNovyUzivatel();   
    $postdata['nazev']=trim($postdata['titul'].' '.$postdata['jmeno'].' '.$postdata['prijmeni'].' '.$postdata['spolecnost'].' ');
    $uid=$this->modely->UzivateleDb->store(0,$postdata);
    if($uid>0){
		  $uzivatel=$this->modely->UzivateleDb->getLine('*','WHERE uid="'.$uid.'"');    
		  $this->modely->UzivateleDb->updateId($uid,array('heslo'=>saltHashSha($heslo,$uzivatel->uid,$uzivatel->registrace_ts,'SaltOfMHMcubeEnterprise')));  
		  }
    Redirect(array('modul'=>'aUzivatele','akce'=>'vypis-uzivatelu','m'=>'uzivatel-vytvoren','isAjax'=>((int)getget('isAjax','0')) ));    
		}
	private function smazUzivatele(){
		$uid=(int)getget('uid','0');
		$uzivatel=$this->modely->UzivateleDb->getLine('*','WHERE uid="'.$uid.'"');
		if(isset($uzivatel->uid)&&$uzivatel->uid>0&&$uzivatel->prava<3&&$uzivatel->prava<=$this->uzivatel->data->prava&&$uzivatel->uid!=$this->uzivatel->data->uid){
			$this->modely->UzivateleDb->updateId($uid,array('aktivni_uzivatel'=>'0','odstraneny_uzivatel'=>'1','session'=>''));  						
			Redirect(array('modul'=>'aUzivatele','akce'=>'vypis-uzivatelu','m'=>'uzivatel-smazan','isAjax'=>((int)getget('isAjax','0')) ));    
			}
		Redirect(array('modul'=>'aUzivatele','akce'=>'vypis-uzivatelu','m'=>'uzivatele-se-nepodarilo-smazat','isAjax'=>((int)getget('isAjax','0')) )); 
		}
	private function _prazdnyNovyUzivatel(){
		$novyUzivatel=new stdClass();
		$novyUzivatel->login='';
		$novyUzivatel->heslo='';
		$novyUzivatel->email='';
		$novyUzivatel->titul='';
		$novyUzivatel->jmeno='';
		$novyUzivatel->prijmeni='';
		$novyUzivatel->spolecnost='';
		$novyUzivatel->telefon='';
		$novyUzivatel->prava='0';		
		$novyUzivatel->aktivni_uzivatel='1';		
		return $novyUzivatel;
		}
  }
  
