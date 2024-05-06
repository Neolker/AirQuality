<?php
class aZarizeni extends Modul{	
	public function hlavniFunkce(){		
		$akce=getget('akce','vypis-zarizeni');
		if($this->uzivatel->uid>0){
			if($akce=='vypis-zarizeni'){return $this->vypisZarizeni();}
			elseif($akce=='zmena-razeni'){return $this->zmenaRazeni();}			
			elseif($akce=='zmena-vyhledavani'){return $this->zmenaVyhledavani();}			
			elseif($akce=='editace-zarizeni'){return $this->editaceZarizeni();}					
			elseif($akce=='pridej-zarizeni'){return $this->pridejZarizeni();}
			elseif($akce=='uloz-zarizeni'){return $this->ulozZarizeni();}
			elseif($akce=='smaz-zarizeni'){return $this->smazZarizeni();}				
			else{Redirect(array('isAjax'=>((int)getget('isAjax','0'))));}
		}else{
			Redirect(array('isAjax'=>((int)getget('isAjax','0'))));
		}
		return 'Modul> Administrace / Zařízení';	
		}	
	private function zmenaRazeni(){
		$_SESSION['zarizeni-razeni']=prepareGetDataSafely(getget('typ',''));
		$stranka=(int)getget('stranka','0');
		Redirect(array('modul'=>'aZarizeni','akce'=>'vypis-zarizeni','stranka'=>$stranka,'isAjax'=>((int)getget('isAjax','0')) )); 
		}
	private function zmenaVyhledavani(){
		$_SESSION['zarizeni-vyhledavani']=trim(prepareGetDataSafely(getpost('fulltext','')));
		Redirect(array('modul'=>'aZarizeni','akce'=>'vypis-zarizeni','isAjax'=>((int)getget('isAjax','0')) )); 
		}
	private function vypisZarizeni(){			
		if(isset($_SESSION['zarizeni-razeni'])){
      $razeni=$_SESSION['zarizeni-razeni'];
    }else{
	    $razeni='nazev';
    }				
    if($razeni=='nazev'){
    	$razeniQ=' ORDER BY nazev ASC ';
    }elseif($razeni=='lokalita'){
    	$razeniQ=' ORDER BY lokalita ASC ';
    }else{
    	$razeniQ=' ORDER BY vyrobni_cislo ASC ';
    }   
    if(isset($_SESSION['zarizeni-vyhledavani'])&&trim($_SESSION['zarizeni-vyhledavani'])!=''){
      $fulltext=trim(prepareGetDataSafely($_SESSION['zarizeni-vyhledavani']));
      $filtrQ=' AND (z.nazev LIKE "%'.$fulltext.'%" OR z.vyrobni_cislo LIKE "%'.$fulltext.'%" OR z.lokalita LIKE "%'.$fulltext.'%" OR u.login LIKE "%'.$fulltext.'%" OR u.nazev LIKE "%'.$fulltext.'%") ';
    }else{
    	$fulltext='';
    	$filtrQ=' ';
    }
		$stranka=(int)getget('stranka','0');
		$citac=$this->nastaveni['administrace-pocet-strankovani'];		
		if($citac<1){$citac=1;}
		if($citac>100){$citac=100;}	
		$zarizeni=$this->modely->ZarizeniDb->MqueryGetLines('SELECT z.* FROM zarizeni as z, uzivatele as u WHERE z.id_uzivatele=u.uid '.$filtrQ.' '.$razeniQ.' LIMIT '.($stranka*$citac).', '.$citac);
		$pocet=$this->modely->ZarizeniDb->MqueryGetOne('SELECT count(z.zid) FROM zarizeni as z, uzivatele as u WHERE z.id_uzivatele=u.uid '.$filtrQ.' ');
		$strankovac=$this->strankovac($stranka,$pocet,$citac);		
    if(isset($_SESSION['zarizeni-pridej-zarizeni'])){
      $noveZarizeni=$_SESSION['zarizeni-pridej-zarizeni'];
    }else{
	    $noveZarizeni=$this->_prazdneNoveZarizeni();
    }				
    $uzivatele=array();
    $uzivateleQ=$this->modely->UzivateleDb->getLines(' login,uid ',' WHERE aktivni_uzivatel=1 AND odstraneny_uzivatel=0 ORDER BY login ASC ');
    if(isset($uzivateleQ)&&is_array($uzivateleQ)&&count($uzivateleQ)>0){
    	foreach($uzivateleQ as $uq){
    		$uzivatele[$uq->uid]=$uq->login;
    		}
    	}
    unset($uzivateleQ);
		$tpl=new Sablona();		
		$tpl->nastavDebugMod($this->debugVars);
		$tpl->pridatPromennou('noveZarizeni',$noveZarizeni);
		$tpl->pridatPromennou('stranka',$stranka);
		$tpl->pridatPromennou('zarizeni',$zarizeni);
		$tpl->pridatPromennou('uzivatele',$uzivatele);				
		$tpl->pridatPromennou('strankovac',$strankovac);
		$tpl->pridatPromennou('razeni',$razeni);
		$tpl->pridatPromennou('fulltext',$fulltext);				
		$tpl->pridatPromennou('prihlasenyUzivatel',$this->uzivatel->data);			
		return $tpl->spustit('aZarizeni/vypisZarizeni.tpl');	
		}
	private function strankovac($stranka=0,$pocet=0,$citac=0){        
    $stranky=array();$maxstranka=0;$citatDo=ceil($pocet/$citac);
    for($i=0;$i<$citatDo;$i++){$stranky[($i+1)]=Anchor(array('modul'=>'aZarizeni','akce'=>'vypis-zarizeni','stranka'=>$i),false);$maxstranka=$i;}
    if(($stranka-1)<0){$stranky['predchozi']=Anchor(array('modul'=>'aZarizeni','akce'=>'vypis-zarizeni','stranka'=>'0'),false);}else{$stranky['predchozi']=Anchor(array('modul'=>'aZarizeni','akce'=>'vypis-zarizeni','stranka'=>($stranka-1)),false);}
    if(($stranka+1)>$maxstranka){$stranky['dalsi']=Anchor(array('modul'=>'aZarizeni','akce'=>'vypis-zarizeni','stranka'=>$maxstranka),false);}else{$stranky['dalsi']=Anchor(array('modul'=>'aZarizeni','akce'=>'vypis-zarizeni','stranka'=>($stranka+1)),false);}
    $stranky['prvni']=Anchor(array('modul'=>'aZarizeni','akce'=>'vypis-zarizeni','stranka'=>0),false);
    $stranky['posledni']=Anchor(array('modul'=>'aZarizeni','akce'=>'vypis-zarizeni','stranka'=>($citatDo-1)),false);    
    return $stranky;          
    } 
	private function editaceZarizeni(){
		$zid=(int)getget('zid','0');			
		$stranka=(int)getget('stranka','0');	
		$zarizeni=$this->modely->ZarizeniDb->getLine('*','WHERE zid="'.$zid.'"');
		if(isset($zarizeni->zid)&&$zarizeni->zid>0){
			$uzivatele=array();
		  $uzivateleQ=$this->modely->UzivateleDb->getLines(' login,uid ',' WHERE aktivni_uzivatel=1 AND odstraneny_uzivatel=0 ORDER BY login ASC ');
		  if(isset($uzivateleQ)&&is_array($uzivateleQ)&&count($uzivateleQ)>0){
		  	foreach($uzivateleQ as $uq){
		  		$uzivatele[$uq->uid]=$uq->login;
		  		}
		  	}
		  unset($uzivateleQ);			
			$tpl=new Sablona();		
			$tpl->nastavDebugMod($this->debugVars);
			$tpl->pridatPromennou('zarizeni',$zarizeni);	
			$tpl->pridatPromennou('uzivatele',$uzivatele);
			$tpl->pridatPromennou('prihlasenyUzivatel',$this->uzivatel->data);		
			$tpl->pridatPromennou('stranka',$stranka);	
			return $tpl->spustit('aZarizeni/editaceZarizeni.tpl');	
			}
		Redirect(array('modul'=>'aZarizeni','akce'=>'vypis-zarizeni','m'=>'zarizeni-nenalezeno','isAjax'=>((int)getget('isAjax','0')) ));    
		}
	private function ulozZarizeni(){
		$zid=(int)getget('zid','0');
		$stranka=(int)getget('stranka','0');					
		$zarizeni=$this->modely->ZarizeniDb->getLine('*','WHERE zid="'.$zid.'"');
		if(isset($zarizeni->zid)&&$zarizeni->zid>0){
			$postdata=array();
			foreach($_POST as $k=>$v){     
		    $a=prepareGetDataSafely($k);
		    $b=prepareGetDataSafely($v);
		    if($a=='id_uzivatele'||$a=='nastaveni_co2_zelena'||$a=='nastaveni_co2_zluta'||$a=='nastaveni_co2_cervena'){
		    	$b=(int)$b;
		    	}
		    $postdata[$a]=prepareGetDataSafely($b);      		   
		    } 
		  $existuje=(int)$this->modely->ZarizeniDb->getOne('zid','WHERE vyrobni_cislo="'.$postdata['vyrobni_cislo'].'" AND zid!="'.$zid.'"');		
	    if($existuje>0){Redirect(array('modul'=>'aZarizeni','akce'=>'editace-zarizeni','m'=>'zarizeni-neulozeno','zid'=>$zid,'stranka'=>$stranka,'isAjax'=>((int)getget('isAjax','0')) ));}
		  if($postdata['vyrobni_cislo']==''){Redirect(array('modul'=>'aZarizeni','akce'=>'editace-zarizeni','m'=>'zarizeni-neulozeno','zid'=>$zid,'stranka'=>$stranka,'isAjax'=>((int)getget('isAjax','0')) ));}
		  if($postdata['nazev']==''){Redirect(array('modul'=>'aZarizeni','akce'=>'editace-zarizeni','m'=>'zarizeni-neulozeno','zid'=>$zid,'stranka'=>$stranka,'isAjax'=>((int)getget('isAjax','0')) ));}
    	if($postdata['id_uzivatele']<1){Redirect(array('modul'=>'aZarizeni','akce'=>'editace-zarizeni','m'=>'zarizeni-neulozeno','zid'=>$zid,'stranka'=>$stranka,'isAjax'=>((int)getget('isAjax','0')) ));} 
			$this->modely->ZarizeniDb->updateId($zid,$postdata);  
			Redirect(array('modul'=>'aZarizeni','akce'=>'editace-zarizeni','m'=>'zarizeni-ulozeno','zid'=>$zid,'stranka'=>$stranka,'isAjax'=>((int)getget('isAjax','0')) ));
			}
		Redirect(array('modul'=>'aZarizeni','akce'=>'vypis-zarizeni','m'=>'zarizeni-nenalezeno','isAjax'=>((int)getget('isAjax','0')) ));   
		}
	private function pridejZarizeni(){
		$postdata=array();
    $sesdata=new stdClass();
    foreach($_POST as $k=>$v){     
      $a=prepareGetDataSafely($k);
      $b=prepareGetDataSafely($v);
      if($a=='id_uzivatele'||$a=='nastaveni_co2_zelena'||$a=='nastaveni_co2_zluta'||$a=='nastaveni_co2_cervena'){
      	$b=(int)$b;
      	}
      $postdata[$a]=prepareGetDataSafely($b);      
      $sesdata->$a=prepareGetDataSafely($b);     
      } 
    $_SESSION['zarizeni-pridej-zarizeni']=$sesdata;
		$existuje=(int)$this->modely->ZarizeniDb->getOne('zid','WHERE vyrobni_cislo="'.$postdata['vyrobni_cislo'].'"');		
    if($existuje>0){Redirect(array('modul'=>'aZarizeni','akce'=>'vypis-zarizeni','m'=>'zarizeni-jiz-existuje','isAjax'=>((int)getget('isAjax','0')) ));}   
    if($postdata['nazev']==''){Redirect(array('modul'=>'aZarizeni','akce'=>'vypis-zarizeni','m'=>'nazev-je-kratky','isAjax'=>((int)getget('isAjax','0')) ));}
    if($postdata['id_uzivatele']<1){Redirect(array('modul'=>'aZarizeni','akce'=>'vypis-zarizeni','m'=>'zadejte-uzivatele','isAjax'=>((int)getget('isAjax','0')) ));}            
    $_SESSION['zarizeni-pridej-zarizeni']=$this->_prazdneNoveZarizeni();       
    $zid=$this->modely->ZarizeniDb->store(0,$postdata);    
    Redirect(array('modul'=>'aZarizeni','akce'=>'vypis-zarizeni','m'=>'zarizeni-vytvoreno','isAjax'=>((int)getget('isAjax','0')) ));    
		}
	private function smazZarizeni(){
		$zid=(int)getget('zid','0');
		$zarizeni=$this->modely->ZarizeniDb->getLine('*','WHERE zid="'.$zid.'"');
		if(isset($zarizeni->zid)&&$zarizeni->zid>0){
			$this->modely->NamerenaDataDb->deleteWhere(' WHERE id_zarizeni="'.$zid.'"');
			$this->modely->ZarizeniDb->deleteWhere(' WHERE zid="'.$zid.'"');
			Redirect(array('modul'=>'aZarizeni','akce'=>'vypis-zarizeni','m'=>'zarizeni-smazano','isAjax'=>((int)getget('isAjax','0')) ));    
			}
		Redirect(array('modul'=>'aZarizeni','akce'=>'vypis-zarizeni','m'=>'zarizeni-nenalezeno','isAjax'=>((int)getget('isAjax','0')) )); 
		}
	private function _prazdneNoveZarizeni(){
		$noveZarizeni=new stdClass();
		$noveZarizeni->id_uzivatele='0';		
		$noveZarizeni->nazev='';
		$noveZarizeni->vyrobni_cislo='';
		$noveZarizeni->lokalita='';
		$noveZarizeni->nastaveni_co2_cervena='1500';
		$noveZarizeni->nastaveni_co2_zluta='1000';
		$noveZarizeni->nastaveni_co2_zelena='0';					
		return $noveZarizeni;
		}
  }
  
