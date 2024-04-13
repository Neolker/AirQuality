<?php
class DatovaAktualizace extends Modul{	
	public function hlavniFunkce(){		
		$data=file_get_contents('php://input');
		//zkusime pasrovat retezec do json formatu
		try{
			$jsonData=json_decode($data);
		}catch(Exception $e) {
			http_response_code(400);
			header('HTTP/1.1 400 Bad Request');			
			echo 'Error: No data given.';
			echo 'Message: '.$e->getMessage()."\n";
			exit();
		}	
		//var_dump($jsonData);
		//json neexistuje ci nejsou zadna data?
		if($jsonData==null){
			http_response_code(400);
			header('HTTP/1.1 400 Bad Request');
			echo 'Error: No data given.';
			exit();
			}
		//neni zadano seriove cislo zarizeni?
		if(isset($jsonData->serial)){}else{
			http_response_code(400);
			header('HTTP/1.1 400 Bad Request');
			echo 'Error: No serial given.';
			exit();
			}	
		//nejsou zadana data ze senzoru?
		if(isset($jsonData->data)&&is_array($jsonData->data)&&count($jsonData->data)>0){}else{
			http_response_code(400);
			header('HTTP/1.1 400 Bad Request');
			echo 'Error: No data array given.';
			exit();
			}					
		$jsonData->serial=prepareGetDataSafely($jsonData->serial); // bezpecnostni opatreni kvuli SQL 
		$existujeZarizeni=$this->modely->ZarizeniDb->getLine(' * ',' WHERE vyrobni_cislo="'.$jsonData->serial.'" ');
		//neexistuje toto zarizeni v db?
		if(isset($existujeZarizeni->zid)&&$existujeZarizeni->zid>0){}else{
			http_response_code(404);
			header('HTTP/1.1 404 Not found');
			echo 'Error: Serial number not found.';
			exit();
			}
		//vse aktualizujeme
		$zaznamy=array();	
		foreach($jsonData->data as $jd){
			$d=$this->parsovatHodnotySenzoru($jd);			
			$existujiData=$this->modely->NamerenaDataDb->getLine(' * ',' WHERE id_zarizeni="'.$existujeZarizeni->zid.'" AND unix_ts="'.$d->unix_ts.'" ');
			if(isset($existujiData->ndid)&&$existujiData->ndid>0){
				$stav='1'; // zaznam jiz existuje
			}else{
				$nid=$this->modely->NamerenaDataDb->store(0,array(
					'id_zarizeni'=>$existujeZarizeni->zid,
					'id_uzivatele'=>$existujeZarizeni->id_uzivatele,
					'unix_ts'=>$d->unix_ts,
					'co2_prumer'=>$d->co2_prumer,
					'co2_trend'=>$d->co2_trend,
					'teplota_prumer'=>$d->teplota_prumer,
					'teplota_trend'=>$d->teplota_trend,
					'vlhkost_prumer'=>$d->vlhkost_prumer,
					'vlhkost_trend'=>$d->vlhkost_trend,
					'baterie_hodnota'=>$d->baterie_hodnota,
					'pozice'=>$d->pozice,
					));
    		if($nid>0){
    			$stav='2'; // zaznam vlozen
    		}else{
    			$stav='0'; // zaznam nevlozen
    		}			
			}
			$zaznamy[$d->unix_ts]=$stav;			
			}	
		//a vratime json zaznam o aktualizaci dat
		echo '{';
			echo '"serial":"'.$existujeZarizeni->vyrobni_cislo.'",';
			echo '"data":[';	
				if(count($zaznamy)>0){
					$i=0;
					$cnt=count($zaznamy);
					foreach($zaznamy as $id=>$stav){
						echo '{';
							echo '"unix-time":'.$id.',';
							echo '"status":"'.($stav==0?'KO':'OK').'",';
							echo '"type":';
							if($stav==0){echo '"not-insert"';}
							if($stav==1){echo '"already-exists"';}
							if($stav==2){echo '"insert"';}
						echo '}';
						$i++;
						if($i<$cnt){
							echo ',';
							}
						}
					}
			echo '],';
			echo '"co2-setting":{"red":'.$existujeZarizeni->nastaveni_co2_cervena.',"yellow":'.$existujeZarizeni->nastaveni_co2_zluta.',"green":'.$existujeZarizeni->nastaveni_co2_zelena.'}';
		echo '}';				
		//script ukoncime								
		exit();
		}		
	private function parsovatHodnotySenzoru($jsonData){
		$data=new stdClass();
		//integers:
		$data->unix_ts=(int)$jsonData->{'unix-time'};
		$data->co2_trend=(int)$jsonData->{'co2-trend'};
		$data->teplota_trend=(int)$jsonData->{'temp-trend'};
		$data->vlhkost_trend=(int)$jsonData->{'humi-trend'};
		$data->pozice=(int)$jsonData->{'position'};				
		//floats:
		$data->co2_prumer=str_replace(',','.',(float)str_replace(',','.',trim($jsonData->{'co2-avg'})));
		$data->teplota_prumer=str_replace(',','.',(float)str_replace(',','.',trim($jsonData->{'temp-avg'})));
		$data->vlhkost_prumer=str_replace(',','.',(float)str_replace(',','.',trim($jsonData->{'humi-avg'})));
		$data->baterie_hodnota=str_replace(',','.',(float)str_replace(',','.',trim($jsonData->{'batt-avg'})));
		//texts:
		$data->co2_jednotka=prepareGetDataSafely($jsonData->{'co2-unit'});
		$data->teplota_jednotka=prepareGetDataSafely($jsonData->{'temp-unit'});
		$data->vlhkost_jednotka=prepareGetDataSafely($jsonData->{'humi-unit'});
		$data->baterie_jednotka=prepareGetDataSafely($jsonData->{'batt-unit'});
		return $data;
		}
  }
  
/*
Volání backendu pro aktualizaci dat:

URL: 
https://air-quality-b.tes-t.cz/data-update/

Metoda: 
POST

POST data: 
{
    "serial": "364a-9569-d5b5-3ab3-044b",
    "data": [
        {
            "unix-time": 1712505476, //double int - unix time stamp
            "co2-avg": 800.0, //float
            "co2-trend": 1, //rise
            "co2-unit": "ppm",
            "temp-avg": 23.45, //float
            "temp-trend": 0, //const
            "temp-unit": "°C",
            "humi-avg": 50.12, //float
            "humi-trend": -1, //fall
            "humi-unit": "%RH",
            "batt-avg": 3.25, //float
            "batt-unit": "V",
            "position": 1 // 0=left, 1=right, 2=bottom, 3=top, 4=front, 5=back
        },
        {
            "unix-time": 1712505478, //double int
            "co2-avg": 800.0, //float
            "co2-trend": 1, //rise
            "co2-unit": "ppm",
            "temp-avg": 23.45, //float
            "temp-trend": 0, //const
            "temp-unit": "°C",
            "humi-avg": 50.12, //float
            "humi-trend": -1, //fall
            "humi-unit": "%RH",
            "batt-avg": 3.25, //float
            "batt-unit": "V",
            "position": 3 // 0=left, 1=right, 2=bottom, 3=top, 4=front, 5=back
        }
    ]
}

Return:
{
    "serial": "364a-9569-d5b5-3ab3-044b",
    "data": [
        {
            "unix-time": 1712505476, //double int - unix time stamp
            "status": "OK" //Smazat z Gateway DB
        },
        {
            "unix-time": 1712505478, //double int
            "status": "KO" //Poslat přístě z Gateway FB znova
        }
    ],
    "co2-setting": { //Nastaveni semaforu pro indikaci úrovně CO2
        "red": 1500,
        "yellow": 1000,
        "green": 0 //spodni limit pro zelenou LED
    }
}
*/  
