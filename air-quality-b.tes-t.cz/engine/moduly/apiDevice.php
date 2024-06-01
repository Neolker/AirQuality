<?php
class ApiDevice extends Modul{	
	public function hlavniFunkce(){		
		$akce=getget('akce','');			
		if($akce=='get'){return $this->getDevice();}		
		elseif($akce=='get-list'){return $this->getListOfDevices();}
		elseif($akce=='add'){return $this->addDevice();}	
		elseif($akce=='update'){return $this->updateDevice();}	
		elseif($akce=='delete'){return $this->deleteDevice();}	
		else{Redirect(array());}
		return 'Modul> API / Device';					
		}
	private function getDevice(){
		if($this->uzivatel->uid>0){
			$zid=(int)getget('device_id','0');	
			$zarizeni=$this->modely->ZarizeniDb->getLine('*','WHERE zid="'.$zid.'"');
			if(isset($zarizeni->zid)&&$zarizeni->zid>0){}else{
				$this->_returnData('KO','No device found.',array());	
				}
			if($zarizeni->id_uzivatele!=$this->uzivatel->uid){
				$this->_returnData('KO','Device belong to another user.',array());	
				}
			$this->_returnData('OK','Device has been loaded successfully.',array(
				'device_id'=>$zarizeni->zid,
				'user_id'=>$zarizeni->id_uzivatele,
				'serial_number'=>$zarizeni->vyrobni_cislo,
				'name'=>$zarizeni->nazev,
				'location'=>$zarizeni->lokalita,
				'co2_green'=>$zarizeni->nastaveni_co2_cervena,
				'co2_yellow'=>$zarizeni->nastaveni_co2_zluta,
				'co2_red'=>$zarizeni->nastaveni_co2_zelena				
				));						
			}
		$this->_returnData('KO','No user is logged-in.',array());	
		}	
	private function getListOfDevices(){
		if($this->uzivatel->uid>0){
			$devices=array();
			$zarizeni=$this->modely->ZarizeniDb->getLines('*','WHERE id_uzivatele="'.((int)$this->uzivatel->uid).'"');
			if(isset($zarizeni)&&is_array($zarizeni)&&count($zarizeni)>0){
				foreach($zarizeni as $z){					
					$devices[$z->zid]=array(
						'device_id'=>$z->zid,
						'user_id'=>$z->id_uzivatele,
						'serial_number'=>$z->vyrobni_cislo,
						'name'=>$z->nazev,
						'location'=>$z->lokalita,
						'co2_green'=>$z->nastaveni_co2_zelena,
						'co2_yellow'=>$z->nastaveni_co2_zluta,
						'co2_red'=>$z->nastaveni_co2_cervena
						);					
					}
				}			
			$this->_returnData('OK','Devices has been loaded successfully. Count: '.count($devices).'.',$devices);
			}
		$this->_returnData('KO','No user is logged-in.',array());			
		}	
	private function addDevice(){
		if($this->uzivatel->uid>0){
			$in=$this->_getJsonRequest();
			if($in==false){
				$this->_returnData('KO','No input data - serial_number, name, location, co2_green, co2_yellow and co2_red must be filled in.',array());
				}
			if(isset($in->serial_number)&&isset($in->name)&&isset($in->location)&&isset($in->co2_green)&&isset($in->co2_yellow)&&isset($in->co2_red)){}else{
				$this->_returnData('KO','No input data - serial_number, name, location, co2_green, co2_yellow and co2_red must be filled in.',array());
				}		
			$postdata=array(
				'id_uzivatele'=>((int)$this->uzivatel->uid),
				'nazev'=>prepareGetDataSafely($in->name),
				'vyrobni_cislo'=>prepareGetDataSafely($in->serial_number),
				'lokalita'=>prepareGetDataSafely($in->location),
				'nastaveni_co2_zelena'=>((int)$in->co2_green),
				'nastaveni_co2_zluta'=>((int)$in->co2_yellow),
				'nastaveni_co2_cervena'=>((int)$in->co2_red)
				);
			if(mb_strlen($postdata['vyrobni_cislo'],'UTF-8')<12){
				$this->_returnData('KO','Minimum length of serial_number is 14 characters.',array());			
				}
			if($postdata['nazev']==''){
				$this->_returnData('KO','Minimum length of name is 1 character.',array());			
				}
			$existuje=(int)$this->modely->ZarizeniDb->getOne('zid','WHERE vyrobni_cislo="'.$postdata['vyrobni_cislo'].'"');						
    	if($existuje>0){
    		$this->_returnData('KO','Serial_number is already used.',array());		
    		}   
			$zid=(int)$this->modely->ZarizeniDb->store(0,$postdata);   
			$device=$this->modely->ZarizeniDb->getLine('*','WHERE zid="'.$zid.'"');	
			if(isset($device->zid)&&$device->zid>0){
				$this->_returnData('OK','Device has been added successfully.',array(
					'device_id'=>$device->zid,
					'user_id'=>$device->id_uzivatele,
					'name'=>$device->nazev,
					'serial_number'=>$device->vyrobni_cislo,
					'location'=>$device->lokalita,
					'co2_green'=>$device->nastaveni_co2_zelena,
					'co2_yellow'=>$device->nastaveni_co2_zluta,
					'co2_red'=>$device->nastaveni_co2_cervena					
					));
				}		
			$this->_returnData('KO','Something get wrong in insert new device into DB.',array());
			}
		$this->_returnData('KO','No user is logged-in.',array());	
		}	
	private function updateDevice(){
		if($this->uzivatel->uid>0){
			$in=$this->_getJsonRequest();
			if($in==false){
				$this->_returnData('KO','No input data - device_id, name, location, co2_green, co2_yellow and co2_red must be filled in.',array());
				}
			if(isset($in->device_id)&&isset($in->name)&&isset($in->location)&&isset($in->co2_green)&&isset($in->co2_yellow)&&isset($in->co2_red)){}else{
				$this->_returnData('KO','No input data - device_id, name, location, co2_green, co2_yellow and co2_red must be filled in.',array());
				}		
			$postdata=array(				
				'nazev'=>prepareGetDataSafely($in->name),				
				'lokalita'=>prepareGetDataSafely($in->location),
				'nastaveni_co2_zelena'=>((int)$in->co2_green),
				'nastaveni_co2_zluta'=>((int)$in->co2_yellow),
				'nastaveni_co2_cervena'=>((int)$in->co2_red)
				);
			$zid=((int)$in->device_id);
			$device=$this->modely->ZarizeniDb->getLine('*','WHERE zid="'.$zid.'" AND id_uzivatele="'.((int)$this->uzivatel->uid).'"');	
			if(isset($device->zid)&&$device->zid>0){
				$this->modely->ZarizeniDb->updateId($zid,$postdata); 
				$deviceSaved=$this->modely->ZarizeniDb->getLine('*','WHERE zid="'.$zid.'"');
				if(isset($deviceSaved->zid)&&$deviceSaved->zid>0){
					$this->_returnData('OK','Device has been changed successfully.',array(
						'device_id'=>$deviceSaved->zid,
						'user_id'=>$deviceSaved->id_uzivatele,
						'name'=>$deviceSaved->nazev,
						'serial_number'=>$deviceSaved->vyrobni_cislo,
						'location'=>$deviceSaved->lokalita,
						'co2_green'=>$deviceSaved->nastaveni_co2_zelena,
						'co2_yellow'=>$deviceSaved->nastaveni_co2_zluta,
						'co2_red'=>$deviceSaved->nastaveni_co2_cervena					
						));
					}		
				$this->_returnData('KO','Something get wrong in insert new device into DB.',array());
				}
			$this->_returnData('KO','Device not found.',array());
			}
		$this->_returnData('KO','No user is logged-in.',array());	
		}	
	private function deleteDevice(){
		if($this->uzivatel->uid>0){
			$zid=(int)getget('device_id','0');
			$zarizeni=$this->modely->ZarizeniDb->getLine('*','WHERE zid="'.$zid.'" AND id_uzivatele="'.((int)$this->uzivatel->uid).'"');
			if(isset($zarizeni->zid)&&$zarizeni->zid>0){
				$this->modely->NamerenaDataDb->deleteWhere(' WHERE id_zarizeni="'.$zid.'"');
				$this->modely->ZarizeniDb->deleteWhere(' WHERE zid="'.$zid.'"');
				$this->_returnData('OK','Device has been deleted successfully.',array());
				}
			$this->_returnData('KO','Device not found.',array());
			}
		$this->_returnData('KO','No user is logged-in.',array());			
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
