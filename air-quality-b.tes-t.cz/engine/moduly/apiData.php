<?php
class ApiData extends Modul{	
	public function hlavniFunkce(){		
		$akce=getget('akce','');			
		if($akce=='get-data'){return $this->getData();}		
		elseif($akce=='get-state'){return $this->getState();}
		elseif($akce=='get-all-states'){return $this->addAllStates();}		
		else{Redirect(array());}
		return 'Modul> API / Device';					
		}
	private function getData(){
		if($this->uzivatel->uid>0){
			$zid=(int)getget('device_id','0');	
			$datum=prepareGetDataSafely(urldecode(getget('date','')));		
			$datumEx=explode('.',$datum);	
			if(isset($datumEx)&&is_array($datumEx)&&count($datumEx)>2){}else{
				$this->_returnData('KO','Date must be in format DD.MM.YYYY for right working.',array());								
				}
			$datumOd=dateToTimestampStart($datum);
			$datumDo=dateToTimestampEnd($datum);							
			$data=array();
			$dataQ=$this->modely->NamerenaDataDb->getLines('*','WHERE id_zarizeni="'.$zid.'" AND id_uzivatele="'.((int)$this->uzivatel->uid).'" AND unix_ts>="'.$datumOd.'" AND unix_ts<="'.$datumDo.'" ORDER BY unix_ts ASC LIMIT 86400');	
			if(isset($dataQ)&&is_array($dataQ)&&count($dataQ)>0){
				foreach($dataQ as $d){					
					$data[$d->unix_ts]=array(
						'data_id'=>$d->ndid,
						'device_id'=>$d->id_zarizeni,
						'user_id'=>$d->id_uzivatele,
						'unix_time'=>$d->unix_ts,
						'co2_avg'=>$d->co2_prumer,
						'co2_trend'=>$d->co2_trend,
						'temp_avg'=>$d->teplota_prumer,
						'temp_trend'=>$d->teplota_trend,
						'humi_avg'=>$d->vlhkost_prumer,
						'humi_trend'=>$d->vlhkost_trend,
						'batt_avg'=>$d->baterie_hodnota,
						'position'=>$d->pozice						
						);					
					}
				}					
			$this->_returnData('OK','Data has been loaded successfully. Date: '.timestampToDate($datumOd).', Count: '.count($data).'.',$data);						
			}
		$this->_returnData('KO','No user is logged-in.',array());	
		}	
	private function getState(){
		if($this->uzivatel->uid>0){
			$zid=(int)getget('device_id','0');
			$jeOnline=(int)$this->modely->NamerenaDataDb->getOne('count(ndid)','WHERE id_zarizeni="'.$zid.'" AND id_uzivatele="'.((int)$this->uzivatel->uid).'" AND unix_ts>="'.(time()-(10*60)).'"');
			//echo 'SELECT count(ndid) FROM namerena_data WHERE id_zarizeni="'.$zid.'" AND id_uzivatele="'.((int)$this->uzivatel->uid).'" AND unix_ts>="'.(time()-(10*60)).'"   '; // for debug
			if($jeOnline==0){
				$this->_returnData('OK','Device does not sent data in last 10 minutes. ',array('is_online'=>false));
				}
			$this->_returnData('OK','Device sent data in last 10 minutes. ',array('is_online'=>true));	
			}
		$this->_returnData('KO','No user is logged-in.',array());			
		}	
	private function addAllStates(){
		if($this->uzivatel->uid>0){
			$data=array();
			$zarizeni=$this->modely->ZarizeniDb->MqueryGetLines('SELECT * FROM zarizeni WHERE id_uzivatele="'.((int)$this->uzivatel->uid).'" ');
			if(isset($zarizeni)&&is_array($zarizeni)&&count($zarizeni)>0){
				foreach($zarizeni as $z){					
					$jeOnline=(int)$this->modely->NamerenaDataDb->getOne('count(ndid)','WHERE id_zarizeni="'.$z->zid.'" AND id_uzivatele="'.((int)$this->uzivatel->uid).'" AND unix_ts>="'.(time()-(10*60)).'"');	
					if($jeOnline==0){
						$data[$z->zid]='offline';
					}else{
						$data[$z->zid]='online';
					}						
				}					
			}						
			$this->_returnData('OK','List of devices loaded. Count: '.count($data).'.',$data);
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
