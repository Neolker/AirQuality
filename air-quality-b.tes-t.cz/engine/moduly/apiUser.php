<?php
class ApiUser extends Modul{	
	public function hlavniFunkce(){		
		$akce=getget('akce','');			
		if($akce=='login'){return $this->loginUser();}		
		elseif($akce=='logout'){return $this->logoutUser();}
		elseif($akce=='get-logged-user'){return $this->getLoggedUser();}	
		elseif($akce=='update-settings'){return $this->updateSettings();}	
		elseif($akce=='update-password'){return $this->updatePassword();}	
		elseif($akce=='registrate'){return $this->registrate();}	
		elseif($akce=='forgot-password'){return $this->forgotPassword();}		
		else{Redirect(array());}
		return 'Modul> API / User';					
		}
	private function loginUser(){		
		$in=$this->_getJsonRequest();
		if($in==false){
			$this->_returnData('KO','No input data - login and password must be filled in.',array());
			}
		if(isset($in->login)&&isset($in->password)){}else{
			$this->_returnData('KO','No input data - login and password must be filled in.',array());
			}		
		$prihlasen=$this->_LoginUzivatele($in->login,$in->password);
		if($prihlasen>0){
			$uzivatel=$this->databaze->MqueryGetLine('SELECT * FROM uzivatele WHERE uid="'.((int)$prihlasen).'" limit 1'); 
			$this->_returnData('OK','User logged successfully.',array(
				'uid'=>$uzivatel->uid,
				'session'=>$uzivatel->session,
				'rights'=>$uzivatel->prava,
				'login'=>$uzivatel->login,
				'timestamp_registration'=>$uzivatel->registrace_ts,
				'timestamp_last_activity'=>$uzivatel->posledni_aktivita_ts,
				'timestamp_last_login'=>$uzivatel->posledni_prihlaseni_ts,
				'last_login_ip'=>$uzivatel->posledni_prihlaseni_ip,
				'number_of_login'=>$uzivatel->pocet_prihlaseni,
				'degree'=>$uzivatel->titul,
				'name'=>$uzivatel->jmeno,
				'surname'=>$uzivatel->prijmeni,
				'company'=>$uzivatel->spolecnost,
				'phone'=>$uzivatel->telefon,
				'email'=>$uzivatel->email,				
				'active_user'=>$uzivatel->aktivni_uzivatel,
				'delete_user'=>$uzivatel->odstraneny_uzivatel
				));
			}
		$this->_returnData('KO','Incorrect password or login.',array('login'=>$in->login));
		}	
	private function logoutUser(){
		if($this->uzivatel->uid>0){
      $this->databaze->Mquery('UPDATE uzivatele SET session="" WHERE uid="'.((int)$this->uzivatel->uid).'"');      
      $this->_returnData('OK','User was logged-out.',array());
      }
    $this->_returnData('OK','No user has been already logged-in.',array());
		}	
	private function getLoggedUser(){
		if($this->uzivatel->uid>0){
			$this->_returnData('OK','User data loaded.',array(
				'uid'=>$this->uzivatel->data->uid,
				'session'=>$this->uzivatel->data->session,
				'rights'=>$this->uzivatel->data->prava,
				'login'=>$this->uzivatel->data->login,
				'timestamp_registration'=>$this->uzivatel->data->registrace_ts,
				'timestamp_last_activity'=>$this->uzivatel->data->posledni_aktivita_ts,
				'timestamp_last_login'=>$this->uzivatel->data->posledni_prihlaseni_ts,
				'last_login_ip'=>$this->uzivatel->data->posledni_prihlaseni_ip,
				'number_of_login'=>$this->uzivatel->data->pocet_prihlaseni,
				'degree'=>$this->uzivatel->data->titul,
				'name'=>$this->uzivatel->data->jmeno,
				'surname'=>$this->uzivatel->data->prijmeni,
				'company'=>$this->uzivatel->data->spolecnost,
				'phone'=>$this->uzivatel->data->telefon,
				'email'=>$this->uzivatel->data->email,				
				'active_user'=>$this->uzivatel->data->aktivni_uzivatel,
				'delete_user'=>$this->uzivatel->data->odstraneny_uzivatel
				));						
			}
		$this->_returnData('KO','No user is logged-in.',array());	
		}	
	private function updateSettings(){
		if($this->uzivatel->uid>0){
			$in=$this->_getJsonRequest();
			if($in==false){
				$this->_returnData('KO','No input data - name, surname and email must be filled in.',array());
				}
			if(isset($in->name)&&isset($in->surname)&&isset($in->email)){}else{
				$this->_returnData('KO','No input data - name, surname and email must be filled in.',$in);
				}	
			$in->name=prepareGetDataSafely($in->name);
			$in->surname=prepareGetDataSafely($in->surname);	
			$in->email=prepareGetDataSafely($in->email);		
			if($in->name==''||$in->surname==''||$in->email==''){
				$this->_returnData('KO','No input data - name, surname and email must be filled in.',$in);
				}
			$postdata=array(
				'email'=>$in->email,
				'telefon'=>prepareGetDataSafely($in->phone),
				'titul'=>prepareGetDataSafely($in->degree),
				'jmeno'=>$in->name,
				'prijmeni'=>$in->surname,
				'spolecnost'=>prepareGetDataSafely($in->company)
				);
			$postdata['nazev']=trim($postdata['titul'].' '.$postdata['jmeno'].' '.$postdata['prijmeni'].' '.$postdata['spolecnost'].' ');
			$this->modely->UzivateleDb->updateId(((int)$this->uzivatel->uid),$postdata);  
			$uzivatel=$this->databaze->MqueryGetLine('SELECT * FROM uzivatele WHERE uid="'.((int)$this->uzivatel->uid).'" limit 1'); 
			$this->_returnData('OK','User has been changed successfully.',array(
				'uid'=>$uzivatel->uid,
				'session'=>$uzivatel->session,
				'rights'=>$uzivatel->prava,
				'login'=>$uzivatel->login,
				'timestamp_registration'=>$uzivatel->registrace_ts,
				'timestamp_last_activity'=>$uzivatel->posledni_aktivita_ts,
				'timestamp_last_login'=>$uzivatel->posledni_prihlaseni_ts,
				'last_login_ip'=>$uzivatel->posledni_prihlaseni_ip,
				'number_of_login'=>$uzivatel->pocet_prihlaseni,
				'degree'=>$uzivatel->titul,
				'name'=>$uzivatel->jmeno,
				'surname'=>$uzivatel->prijmeni,
				'company'=>$uzivatel->spolecnost,
				'phone'=>$uzivatel->telefon,
				'email'=>$uzivatel->email,				
				'active_user'=>$uzivatel->aktivni_uzivatel,
				'delete_user'=>$uzivatel->odstraneny_uzivatel
				));
			}
		$this->_returnData('KO','No user is logged-in.',array());		
		}	
	private function updatePassword(){
		if($this->uzivatel->uid>0){
			$in=$this->_getJsonRequest();
			if($in==false){
				$this->_returnData('KO','No input data - old_password, new_password and new_password_again must be filled in.',array());
				}
			if(isset($in->old_password)&&isset($in->new_password)&&isset($in->new_password_again)){}else{
				$this->_returnData('KO','No input data - old_password, new_password and new_password_again must be filled in.',array());
				}	
			$in->old_password=prepareGetDataSafely($in->old_password);
			$in->new_password=prepareGetDataSafely($in->new_password);	
			$in->new_password_again=prepareGetDataSafely($in->new_password_again);		
			if($in->old_password==''||$in->new_password==''||$in->new_password_again==''){
				$this->_returnData('KO','No input data - old_password, new_password and new_password_again must be filled in.',array());
				}
			if($in->new_password!=$in->new_password_again){
				$this->_returnData('KO','New_password and new_password_again must be same.',array());
				}	
			if(mb_strlen($in->new_password,'UTF-8')<4){
				$this->_returnData('KO','Minimum length of new_password is 4 characters.',array());
				}
			if(mb_strlen($in->old_password,'UTF-8')<4){
				$this->_returnData('KO','Minimum length of old_password is 4 characters.',array());
				}
			$login=false;
			$uzivatel=$this->databaze->MqueryGetLine('SELECT * FROM uzivatele WHERE uid="'.((int)$this->uzivatel->uid).'" limit 1'); // v promenne $this->uzivatel->data nejsou hesla z bezp. duvodu...
      if((saltHashSha($in->old_password,$this->uzivatel->data->uid,$this->uzivatel->data->registrace_ts,'SaltOfMHMcubeEnterprise')==$uzivatel->heslo)&&$uzivatel->heslo!=''){$login=true;}
      if((saltHashSha($in->old_password,$this->uzivatel->data->uid,$this->uzivatel->data->registrace_ts,'SaltOfMHMcubeEnterprise')==$uzivatel->heslo_2)&&$uzivatel->heslo_2!=''){$login=true;}
      if($login==true){				
				$newpassHash=saltHashSha($in->new_password,$this->uzivatel->data->uid,$this->uzivatel->data->registrace_ts,'SaltOfMHMcubeEnterprise');
	   		$this->modely->UzivateleDb->updateId($this->uzivatel->data->uid,array('heslo'=>$newpassHash,'heslo_2'=>''));				
				$this->_returnData('OK','Password changed successfully.',array());
				}			
			$this->_returnData('KO','Old password does not match.',array());
			}
		$this->_returnData('KO','No user is logged-in.',array());		
		}	
	private function registrate(){
		if($this->uzivatel->uid>0){
      $this->databaze->Mquery('UPDATE uzivatele SET session="" WHERE uid="'.((int)$this->uzivatel->uid).'"');  // odhlasime soucasneho uzivatele, registrovany uzivatel bude rovnou prihlasen           
      }
    $in=$this->_getJsonRequest();
		if($in==false){
			$this->_returnData('KO','No input data - login, degree, name, surname, company, email, phone, password, password_again. Login, email, name, surname, password and password_again must be not null.',array());
			}
		if(isset($in->login)&&isset($in->degree)&&isset($in->name)&&isset($in->surname)&&isset($in->company)&&isset($in->email)&&isset($in->phone)&&isset($in->password)&&isset($in->password_again)){}else{
			$this->_returnData('KO','No input data - login, degree, name, surname, company, email, phone, password, password_again. Login, email, name, surname, password and password_again must be not null.',array());
			}
		$in->login=prepareGetDataSafely($in->login);
		$in->degree=prepareGetDataSafely($in->degree);
		$in->name=prepareGetDataSafely($in->name);
		$in->surname=prepareGetDataSafely($in->surname);
		$in->company=prepareGetDataSafely($in->company);
		$in->email=prepareGetDataSafely($in->email);
		$in->phone=prepareGetDataSafely($in->phone);
		$in->password=prepareGetDataSafely($in->password);
		$in->password_again=prepareGetDataSafely($in->password_again);		
    if($in->password!=$in->password_again){
			$this->_returnData('KO','Password and password_again must be same.',array());
			}	
		if(mb_strlen($in->password,'UTF-8')<4){
			$this->_returnData('KO','Minimum length of password is 4 characters.',array());
			}
		if(mb_strlen($in->email,'UTF-8')<6){
			$this->_returnData('KO','Minimum length of email is 6 characters.',array());
			}
		if(mb_strlen($in->login,'UTF-8')<4){
			$this->_returnData('KO','Minimum length of login is 4 characters.',array());
			}
    if($in->name==''||$in->surname==''){
    	$this->_returnData('KO','Name and surname must be filled in.',array());
    	}
    $existuje=(int)$this->modely->UzivateleDb->getOne('uid','WHERE login="'.$in->login.'"');  
    if($existuje>0){
    	$this->_returnData('KO','Login is already used in application.',array());
    	}  
		$postdata=array(
			'login'=>$in->login,
			'titul'=>$in->degree,
			'jmeno'=>$in->name,
			'prijmeni'=>$in->surname,
			'spolecnost'=>$in->company,
			'email'=>$in->email,
			'telefon'=>$in->phone,
			'session'=>'',
			'prava'=>'1',
			'heslo'=>md5($in->login.'x'.$in->email),
			'heslo_2'=>'',
			'posledni_aktivita_ts'=>'0',
			'posledni_prihlaseni_ts'=>'0',
			'posledni_prihlaseni_ip'=>'',
			'pocet_prihlaseni'=>'0',
			'registrace_ts'=>time(),
			'darkmode'=>'0',
			'ajaxmode'=>'1',
			'infomode'=>'0',
			'varsmode'=>'0',
			'aktivni_uzivatel'=>'1',
			'odstraneny_uzivatel'=>'0',		
			'nazev'=>''	
			);
		$postdata['nazev']=trim($postdata['titul'].' '.$postdata['jmeno'].' '.$postdata['prijmeni'].' '.$postdata['spolecnost'].' ');
    $uid=$this->modely->UzivateleDb->store(0,$postdata);
    if($uid>0){
		  $uzivatel=$this->modely->UzivateleDb->getLine('*','WHERE uid="'.$uid.'"');    
		  $this->modely->UzivateleDb->updateId($uid,array('heslo'=>saltHashSha($in->password,$uzivatel->uid,$uzivatel->registrace_ts,'SaltOfMHMcubeEnterprise'))); 
		  $this->_LoginUzivatele($postdata['login'],$in->password); 
		  $uzivatel=$this->databaze->MqueryGetLine('SELECT * FROM uzivatele WHERE uid="'.$uid.'" limit 1'); 
			$this->_returnData('OK','User has been registered successfully.',array(
				'uid'=>$uzivatel->uid,
				'session'=>$uzivatel->session,
				'rights'=>$uzivatel->prava,
				'login'=>$uzivatel->login,
				'timestamp_registration'=>$uzivatel->registrace_ts,
				'timestamp_last_activity'=>$uzivatel->posledni_aktivita_ts,
				'timestamp_last_login'=>$uzivatel->posledni_prihlaseni_ts,
				'last_login_ip'=>$uzivatel->posledni_prihlaseni_ip,
				'number_of_login'=>$uzivatel->pocet_prihlaseni,
				'degree'=>$uzivatel->titul,
				'name'=>$uzivatel->jmeno,
				'surname'=>$uzivatel->prijmeni,
				'company'=>$uzivatel->spolecnost,
				'phone'=>$uzivatel->telefon,
				'email'=>$uzivatel->email,				
				'active_user'=>$uzivatel->aktivni_uzivatel,
				'delete_user'=>$uzivatel->odstraneny_uzivatel
				));
		  }
		$this->_returnData('KO','Something get wrong in insert new user into DB.',array());
		}	
	private function forgotPassword(){
		if($this->uzivatel->uid>0){
      $this->databaze->Mquery('UPDATE uzivatele SET session="" WHERE uid="'.((int)$this->uzivatel->uid).'"');  // odhlasime soucasneho uzivatele        
      }
    $in=$this->_getJsonRequest();
		if($in==false){
			$this->_returnData('KO','No input data - login and email must not be null.',array());
			}
		if(isset($in->login)&&isset($in->email)){}else{
			$this->_returnData('KO','No input data - login and email must not be null.',array());
			}
		$login=prepareGetDataSafely($in->login);
		$email=prepareGetDataSafely($in->email);	
		if($email!=''&&$login!=''){	
			$existuje_uzivatel=$this->modely->UzivateleDb->getLine('*','WHERE email="'.$email.'" AND login="'.$login.'"');
			if($existuje_uzivatel->uid>0){
				$newpass=substr(md5('h3slo-a1r-qual1ty'.time().rand(10000,99999)),3,10);
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
				$this->_returnData('OK','New password has been set and send into client email.',array());
				}			
			$this->_returnData('KO','Bad login or email.',array());
			}
		$this->_returnData('KO','No input data - login and email must not be null.',array());							
		}	
	private function _LoginUzivatele($login='',$heslo=''){    
		if($this->uzivatel->uid>0){
      $this->databaze->Mquery('UPDATE uzivatele SET session="" WHERE uid="'.((int)$this->uzivatel->uid).'"');  // odhlasime soucasneho uzivatele           
      }
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
							return $uzivatel->uid;     
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
	private function _returnData($status,$error,$data){
		echo '{';
			echo '"status":"'.$status.'",';
			echo '"error":"'.$error.'",';
			echo '"data":'.json_encode($data);								
		echo '}';				
		exit();	
		}
	private function _getJsonRequest(){
		$data=file_get_contents('php://input');
		if($data==null||$data==''){
			return false;
			}
		try{
			$jsonData=json_decode($data);
		}catch(Exception $e) {
			http_response_code(400);
			header('HTTP/1.1 400 Bad Request');			
			echo 'Error: No data given.';
			echo 'Message: '.$e->getMessage()."\n";
			exit();
		}	
		if($jsonData==null){
			return false;
			}
		return $jsonData;
		}
	}
