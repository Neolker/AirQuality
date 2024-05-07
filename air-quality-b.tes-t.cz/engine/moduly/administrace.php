<?php
class Administrace extends Modul{	
	public function hlavniFunkce(){		
		$akce=getget('akce','hlavni-strana');
		if($this->uzivatel->uid>0){
			if($akce=='hlavni-strana'){return $this->hlavniStrana();}	
			elseif($akce=='denni-rezim'){return $this->aktivujDenniRezim();}	
			elseif($akce=='nocni-rezim'){return $this->aktivujNocniRezim();}	
			elseif($akce=='chytre-nacitani'){return $this->aktivujChytreNacitani();}	
			elseif($akce=='klasicke-nacitani'){return $this->aktivujKlasickeNacitani();}
			elseif($akce=='mhm-info'){return $this->aktivujRezimMHMInfo();}
			elseif($akce=='mhm-vars'){return $this->aktivujRezimMHMVars();}
			elseif($akce=='mhm-error'){return $this->aktivujRezimMHMErrors();}				
			elseif($akce=='odhlas-me'){return $this->odhlasMe();}	
			else{Redirect(array('modul'=>'Administrace'));}
		}else{			
			if($akce=='hlavni-strana'){return $this->prihlasovaciFormular();}	
			elseif($akce=='prihlas-me'){return $this->prihlasMe();}	
			elseif($akce=='posli-zapomenute-heslo'){return $this->posliZapomenuteHeslo();}	
			else{Redirect(array('modul'=>'Administrace'));}
		}	
		return 'Administrace';
		}
	public function hlavniStrana(){
		$tpl=new Sablona();			
		$tpl->nastavDebugMod($this->debugVars);
		return $tpl->spustit('administrace/hlavniStrana.tpl');
		}
	public function prihlasovaciFormular(){
		$tpl=new Sablona();
		$tpl->nastavDebugMod($this->debugVars);
		$tpl->pridatPromennou('aPrihlasMe',Anchor(array('modul'=>'Administrace','akce'=>'prihlas-me')));
		$tpl->pridatPromennou('aPosliHeslo',Anchor(array('modul'=>'Administrace','akce'=>'posli-zapomenute-heslo')));						
		return $tpl->spustit('administrace/prihlasovaciFormular.tpl');	
		}
	public function aktivujNocniRezim(){
		if($this->uzivatel->uid>0){
			$this->modely->UzivateleDb->updateId($this->uzivatel->uid,array('darkmode'=>'1')); 
			}
		$returnUrl=strip_tags(trim(safeurlBase64Decode(getget('rurl'))));
		$returnUrl=str_replace('isAjax=','a=',$returnUrl);
		if($returnUrl==''){$returnUrl='/';}
		_redirectBasic($returnUrl);
		}
	public function aktivujDenniRezim(){
		if($this->uzivatel->uid>0){
			$this->modely->UzivateleDb->updateId($this->uzivatel->uid,array('darkmode'=>'0')); 
			}
		$returnUrl=strip_tags(trim(safeurlBase64Decode(getget('rurl'))));
		$returnUrl=str_replace('isAjax=','a=',$returnUrl);
		if($returnUrl==''){$returnUrl='/';}
		_redirectBasic($returnUrl);
		}
	public function aktivujChytreNacitani(){
		if($this->uzivatel->uid>0){
			$this->modely->UzivateleDb->updateId($this->uzivatel->uid,array('ajaxmode'=>'1')); 
			}
		$returnUrl=strip_tags(trim(safeurlBase64Decode(getget('rurl'))));
		$returnUrl=str_replace('isAjax=','a=',$returnUrl);
		if($returnUrl==''){$returnUrl='/';}
		_redirectBasic($returnUrl);
		}
	public function aktivujKlasickeNacitani(){
		if($this->uzivatel->uid>0){
			$this->modely->UzivateleDb->updateId($this->uzivatel->uid,array('ajaxmode'=>'0')); 
			}
		$returnUrl=strip_tags(trim(safeurlBase64Decode(getget('rurl'))));
		$returnUrl=str_replace('isAjax=','a=',$returnUrl);
		if($returnUrl==''){$returnUrl='/';}
		_redirectBasic($returnUrl);
		}	
	public function aktivujRezimMHMInfo(){
		if($this->uzivatel->uid>0){
			if($this->uzivatel->data->infomode==1){
				$this->modely->UzivateleDb->updateId($this->uzivatel->uid,array('infomode'=>'0')); 
			}else{
				$this->modely->UzivateleDb->updateId($this->uzivatel->uid,array('infomode'=>'1')); 
			}
		}
		$returnUrl=strip_tags(trim(safeurlBase64Decode(getget('rurl'))));
		$returnUrl=str_replace('isAjax=','a=',$returnUrl);
		if($returnUrl==''){$returnUrl='/';}
		_redirectBasic($returnUrl);	
		}
	public function aktivujRezimMHMVars(){
		if($this->uzivatel->uid>0){
			if($this->uzivatel->data->varsmode==1){
				$this->modely->UzivateleDb->updateId($this->uzivatel->uid,array('varsmode'=>'0')); 
			}else{
				$this->modely->UzivateleDb->updateId($this->uzivatel->uid,array('varsmode'=>'1')); 
			}
		}
		$returnUrl=strip_tags(trim(safeurlBase64Decode(getget('rurl'))));
		$returnUrl=str_replace('isAjax=','a=',$returnUrl);
		if($returnUrl==''){$returnUrl='/';}
		_redirectBasic($returnUrl);	
		}
	public function aktivujRezimMHMErrors(){
		if(isset($_SESSION['mhm-error'])){
      $error=(int)$_SESSION['mhm-error'];
    }else{
	    $error=0;
    }	
    if($error==1){
    	$_SESSION['mhm-error']=0;
    }else{
    	$_SESSION['mhm-error']=1;
    }    
		$returnUrl=strip_tags(trim(safeurlBase64Decode(getget('rurl'))));
		$returnUrl=str_replace('isAjax=','a=',$returnUrl);
		if($returnUrl==''){$returnUrl='/';}
		_redirectBasic($returnUrl);	
		}
	public function odhlasMe(){
		$odhlasen=$this->_LogoutUzivatele();		
		Redirect(array('modul'=>'Administrace','akce'=>'hlavni-strana','m'=>'odhlaseni-v-poradku'));			
		}
	private function _LoginUzivatele($login='',$heslo=''){    
    $login=prepareGetDataSafely($login);
    $heslo=prepareGetDataSafely($heslo);
    if($login!=''&&$heslo!=''){
      $data=$this->databaze->MqueryGetLine('SELECT uid,heslo,heslo_2,pocet_prihlaseni,registrace_ts FROM uzivatele WHERE login="'.trim($login).'" AND aktivni_uzivatel=1 limit 1');
      if(isset($data->uid)&&$data->uid>0){
        $login=false;
        if((saltHashSha($heslo,$data->uid,$data->registrace_ts,'SaltOfMHMcubeEnterprise')==$data->heslo)&&$data->heslo!=''&&$heslo!=''){$login=true;}
        if((saltHashSha($heslo,$data->uid,$data->registrace_ts,'SaltOfMHMcubeEnterprise')==$data->heslo_2)&&$data->heslo_2!=''&&$heslo!=''){$login=true;}
          if($login==true){
          	if(!empty($_SERVER['HTTP_X_FORWARDED_FOR'])){
							$ip=prepareGetDataSafely($_SERVER['HTTP_X_FORWARDED_FOR']);
						}else{
							$ip=prepareGetDataSafely($_SERVER['REMOTE_ADDR']);
						}
            $this->databaze->Mquery('UPDATE uzivatele SET session="'.session_id().'",posledni_prihlaseni_ts="'.time().'",posledni_aktivita_ts="'.time().'",posledni_prihlaseni_ip="'.$ip.'",pocet_prihlaseni="'.($data->pocet_prihlaseni+1).'" WHERE uid="'.$data->uid.'"');
           	$uzivatel=$this->databaze->MqueryGetLine('SELECT * FROM uzivatele WHERE uid="'.$data->uid.'" limit 1'); 
            if(isset($uzivatel->uid)&&$uzivatel->uid>0){												   
							return 1;     
						}else{							 
							return 0;    
						}								                     
          }else{
            return 0;
          }
        }
      return 0;
      }
    return 0;
    }
  private function _LogoutUzivatele(){
    if($this->uzivatel->uid>0){
      $this->databaze->Mquery('UPDATE uzivatele SET session="" WHERE uid="'.((int)$this->uzivatel->uid).'"');      
      return 1;
      }
    return 0;  
    }	  
	public function prihlasMe(){
		$login=getpost('login','');	
		$heslo=getpost('heslo','');	
		$prihlasen=$this->_LoginUzivatele($login,$heslo);
		if($prihlasen==1){
			Redirect(array('modul'=>'Administrace','akce'=>'hlavni-strana','m'=>'uspesne-prihlaseno'));
			}
		Redirect(array('modul'=>'Administrace','akce'=>'hlavni-strana','m'=>'prihlaseni-se-nezdarilo'));
		}	
	public function posliZapomenuteHeslo(){
		$login=trim(prepareGetDataSafely(getpost('login',''))); 
		$email=trim(prepareGetDataSafely(getpost('email',''))); 
		if($email!=''&&$login!=''){	
			$existuje_uzivatel=$this->modely->UzivateleDb->getLine('*','WHERE email="'.$email.'" AND login="'.$login.'"');
			if($existuje_uzivatel->uid>0){
				$newpass=substr(md5('h3slo-Cu8e5'.time().rand(10000,99999)),3,10);
		    $newpassHash=saltHashSha($newpass,$existuje_uzivatel->uid,$existuje_uzivatel->registrace_ts,'SaltOfMHMcubeEnterprise');
		    $this->modely->UzivateleDb->updateId($existuje_uzivatel->uid,array('heslo_2'=>$newpassHash));
		    $mailer=new MHMmailer(false);
		    $mailer->phpMailer->Subject='Zapomenuté heslo - AirQuality';              
		    $mailer->phpMailer->MsgHTML('<p><b>Vygenerovali jsme pro Vás nové přihlašovací heslo.</b></p>
		    <p>
		    	Nyní se můžete do Vašeho uživatelského účtu <b>'.$existuje_uzivatel->login.'</b> v systému AirQuality
		    	přihlásit se svým původním i s tímto novým heslem:</p>
		    <p><b>'.$newpass.'</b></p>
		    <p>Kvůli zabezpečení doporučujeme ihned po přihlášení Vaše heslo změnit a tento e-mail smazat.</p>
		    <p>Váš systém AirQuality.</p>
		    ');
		    $mailer->phpMailer->AddAddress($existuje_uzivatel->email);
		    $mailer->phpMailer->Send();  				    
				Redirect(array('modul'=>'Administrace','akce'=>'hlavni-strana','m'=>'nove-heslo-vygenerovano'));		
				}
			Redirect(array('modul'=>'Administrace','akce'=>'hlavni-strana','m'=>'spatne-login-email'));					
			}
		Redirect(array('modul'=>'Administrace','akce'=>'hlavni-strana','m'=>'spatne-login-email'));			
		}
  }
