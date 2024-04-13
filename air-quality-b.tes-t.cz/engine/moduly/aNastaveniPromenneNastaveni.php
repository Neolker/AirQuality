<?php
class aPromenneNastaveni extends Modul{	
	public function hlavniFunkce(){		
		$akce=getget('akce','vypis-promennych');
		if($this->uzivatel->uid>0&&$this->uzivatel->data->prava==3){
			if($akce=='vypis-promennych'){return $this->vypisPromennych();}
			elseif($akce=='editace-promenne'){return $this->editacePromenne();}	
			elseif($akce=='zmena-vyhledavani'){return $this->zmenaVyhledavani();}	
			elseif($akce=='pridej-promennou'){return $this->pridaniPromenne();}
			elseif($akce=='uloz-promennou'){return $this->ulozeniPromenne();}
			elseif($akce=='posun-promennou'){return $this->posunPromennou();}
			elseif($akce=='smaz-promennou'){return $this->smazPromennou();}
			else{Redirect(array('isAjax'=>((int)getget('isAjax','0'))));}
		}else{
			Redirect(array('isAjax'=>((int)getget('isAjax','0'))));
		}			
		return 'Modul> Administrace / Nastavení / Proměnné nastavení';	
		}
	private function zmenaVyhledavani(){
		$_SESSION['nastaveni-promenne-nastaveni-vyhledavani']=trim(prepareGetDataSafely(getpost('fulltext','')));
		Redirect(array('modul'=>'aPromenneNastaveni','akce'=>'vypis-promennych','isAjax'=>((int)getget('isAjax','0')) )); 
		}	
	private function vypisPromennych(){						
		if(isset($_SESSION['nastaveni-promenne-nastaveni-vyhledavani'])&&trim($_SESSION['nastaveni-promenne-nastaveni-vyhledavani'])!=''){
      $fulltext=trim(prepareGetDataSafely($_SESSION['nastaveni-promenne-nastaveni-vyhledavani']));
      $filtrQ.=' WHERE (klic LIKE "%'.$fulltext.'%" OR nazev LIKE "%'.$fulltext.'%" OR hodnota LIKE "%'.$fulltext.'%" OR popis LIKE "%'.$fulltext.'%") ';
    }else{
    	$fulltext='';
    	$filtrQ=' ';
    }				
		$promenne=$this->modely->NastaveniDb->getLines('*',' '.$filtrQ.' ORDER BY poradi ASC');
		if(isset($promenne)&&is_array($promenne)&&count($promenne)>0){			
			$prevk=(-1); 
			foreach($promenne as $pk=>$pv){
				$promenne[$pk]->aNahoru='';
				$promenne[$pk]->aDolu='';
				if($prevk>=0){
					$promenne[$pk]->aNahoru=Anchor(array('modul'=>'aPromenneNastaveni','akce'=>'posun-promennou','id1'=>$pv->nid,'id2'=>$promenne[$prevk]->nid));
					$promenne[$prevk]->aDolu=Anchor(array('modul'=>'aPromenneNastaveni','akce'=>'posun-promennou','id1'=>$promenne[$prevk]->nid,'id2'=>$pv->nid));
					}						  	
        $prevk=$pk; 
      	}
			}		
		if(isset($_SESSION['nastaveni-promenne-nova'])){
      $novaPromenna=$_SESSION['nastaveni-promenne-nova'];
    }else{
	    $novaPromenna=$this->_prazdnaNovaPromenna();
    }		
		$tpl=new Sablona();		
		$tpl->nastavDebugMod($this->debugVars);		
		$tpl->pridatPromennou('fulltext',$fulltext);	
		$tpl->pridatPromennou('promenne',$promenne);										
		$tpl->pridatPromennou('novaPromenna',$novaPromenna);			
		return $tpl->spustit('aNastaveniPromenne/vypisPromennych.tpl');		
		}
	private function editacePromenne(){
		$nid=(int)getget('nid','0');		
		$promenna=$this->modely->NastaveniDb->getLine('*','WHERE nid="'.$nid.'"');
		if(isset($promenna->nid)&&$promenna->nid>0){			
			$tpl=new Sablona();		
			$tpl->nastavDebugMod($this->debugVars);
			$tpl->pridatPromennou('promenna',$promenna);				
			return $tpl->spustit('aNastaveniPromenne/editacePromenne.tpl');	
			}
		Redirect(array('modul'=>'aPromenneNastaveni','akce'=>'vypis-promennych','m'=>'promenna-nenalezena','isAjax'=>((int)getget('isAjax','0')) )); 	
		}
	private function pridaniPromenne(){
		$postdata=array();
    $sesdata=new stdClass();
    foreach($_POST as $k=>$v){     
      $a=prepareGetDataSafely($k);
      $b=prepareGetDataSafely($v);
      $postdata[$a]=prepareGetDataSafely($b);      
      $sesdata->$a=prepareGetDataSafely($b);     
      } 
    $postdata['klic']=generateNiceUrl($postdata['klic']);
    $postdata['hodnota']=$this->_validuj_promennou($postdata['typ'],getpost('hodnota',''));        
    $_SESSION['nastaveni-promenne-nova']=$sesdata; 
    $existuje=(int)$this->modely->NastaveniDb->getOne('nid','WHERE klic="'.$postdata['klic'].'"');
    if($existuje>0||trim($postdata['klic'])==''){Redirect(array('modul'=>'aPromenneNastaveni','akce'=>'vypis-promennych','m'=>'promenna-jiz-existuje','isAjax'=>((int)getget('isAjax','0')) ));}   
   	$mp=(int)$this->modely->NastaveniDb->getOne('max(poradi)');
    $postdata['poradi']=($mp+1);    
    $idn=$this->modely->NastaveniDb->store(0,$postdata);
    $_SESSION['nastaveni-promenne-nova']=$this->_prazdnaNovaPromenna();   
    Redirect(array('modul'=>'aPromenneNastaveni','akce'=>'vypis-promennych','m'=>'promenna-vytvorena','isAjax'=>((int)getget('isAjax','0')) ));       
		}	
	private function ulozeniPromenne(){
		$nid=(int)getget('nid','0');			
		$promenna=$this->modely->NastaveniDb->getLine('*','WHERE nid="'.$nid.'"');
		if(isset($promenna->nid)&&$promenna->nid>0){
			$postdata=array();
			$sesdata=new stdClass();
			foreach($_POST as $k=>$v){     
		    $a=prepareGetDataSafely($k);
		    $b=prepareGetDataSafely($v);
		    $postdata[$a]=prepareGetDataSafely($b);      
		    $sesdata->$a=prepareGetDataSafely($b);     
		    } 
		  $postdata['hodnota']=$this->_validuj_promennou($postdata['typ'],getpost('hodnota',''));  		  		
		  $postdata['klic']=generateNiceUrl($postdata['klic']);  
		  $existuje=(int)$this->modely->NastaveniDb->getOne('nid','WHERE klic="'.$postdata['klic'].'" AND nid!="'.$nid.'"');
	    if($existuje>0||trim($postdata['klic'])==''){Redirect(array('modul'=>'aPromenneNastaveni','akce'=>'editace-promenne','m'=>'promenna-jiz-existuje','nid'=>$nid, 'isAjax'=>((int)getget('isAjax','0')) ));}  
	    $this->modely->NastaveniDb->store($nid,$postdata);
	    Redirect(array('modul'=>'aPromenneNastaveni','akce'=>'editace-promenne','m'=>'promenna-ulozena','nid'=>$nid,'isAjax'=>((int)getget('isAjax','0')) ));
			}
		Redirect(array('modul'=>'aPromenneNastaveni','akce'=>'vypis-promennych','m'=>'promenna-nenalezena','isAjax'=>((int)getget('isAjax','0')) ));   		
		}
	private function posunPromennou(){
		$id='nid';
		$model='NastaveniDb';
		$id1=(int)getget('id1');
    $id2=(int)getget('id2');
    $pa=$this->modely->$model->getLine('*','WHERE '.$id.'="'.$id1.'"');
    $pb=$this->modely->$model->getLine('*','WHERE '.$id.'="'.$id2.'"');
    if($pa->$id>0&&$pb->$id>0){
      $this->modely->$model->store($pa->$id,array('poradi'=>$pb->poradi));
      $this->modely->$model->store($pb->$id,array('poradi'=>$pa->poradi));
      Redirect(array('modul'=>'aPromenneNastaveni','akce'=>'vypis-promennych','m'=>'promenna-posunuta','isAjax'=>((int)getget('isAjax','0')) )); 
      }
		Redirect(array('modul'=>'aPromenneNastaveni','akce'=>'vypis-promennych','m'=>'promenna-neposunuta','isAjax'=>((int)getget('isAjax','0')) )); 
		}
	private function smazPromennou(){
		$nid=(int)getget('nid','0');
		if($nid>0){
			$this->modely->NastaveniDb->deleteId($nid);  					
			Redirect(array('modul'=>'aPromenneNastaveni','akce'=>'vypis-promennych','m'=>'promenna-smazana','isAjax'=>((int)getget('isAjax','0')) ));
			}
		Redirect(array('modul'=>'aPromenneNastaveni','akce'=>'vypis-promennych','m'=>'promenna-nesmazana','isAjax'=>((int)getget('isAjax','0')) ));
		}
	private function _prazdnaNovaPromenna(){
		$novaPromenna=new stdClass();
		$novaPromenna->klic='';
		$novaPromenna->nazev='';
		$novaPromenna->hodnota='';
		$novaPromenna->popis='';
		$novaPromenna->typ='4';			
		$novaPromenna->zobrazovat='1';						
		return $novaPromenna;
		}
	private function _validuj_promennou($typ,$hodnota){
		$navratovaHodnota='';
		if($typ==5){ // HTML
    	$navratovaHodnota=prepareGetDataSafelyEditor($hodnota);
    }else{
    	$navratovaHodnota=prepareGetDataSafely($hodnota);
    	if($typ==0){ // Integer
		  	$navratovaHodnota=(int)$navratovaHodnota;
		  	}
		  if($typ==1){ // Float		  	
		  	$navratovaHodnota=str_replace(',','.',$navratovaHodnota);		  
		  	$navratovaHodnota=(float)$navratovaHodnota;
		  	$navratovaHodnota=str_replace(',','.',$navratovaHodnota);		  	
		  	}
			if($typ==2){ // Bolean
				$navratovaHodnota=str_replace(array('true','false','True','False','TRUE','FALSE','T','F'),array('1','0','1','0','1','0','1','0'),$navratovaHodnota);
				$navratovaHodnota=(int)$navratovaHodnota;
				if($navratovaHodnota>1){$navratovaHodnota=1;}
				if($navratovaHodnota<0){$navratovaHodnota=0;}
				}		 	
    }
    return $navratovaHodnota;
		}
  }
