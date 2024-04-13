<?php
class aNastaveni extends Modul{	
	public function hlavniFunkce(){		
		$akce=getget('akce','vypis-nastaveni');			
		if($akce=='vypis-nastaveni'){return $this->vypisNastaveni();}
		elseif($akce=='editace-nastaveni'){return $this->editaceNastaveni();}	
		elseif($akce=='uloz-nastaveni'){return $this->ulozeniNastaveni();}
		elseif($akce=='zmena-vyhledavani'){return $this->zmenaVyhledavani();}	
		else{Redirect(array('isAjax'=>((int)getget('isAjax','0'))));}
		return 'Modul> Administrace / Nastavení';	
		}
	private function zmenaVyhledavani(){
		$_SESSION['nastaveni-vyhledavani']=trim(prepareGetDataSafely(getpost('fulltext','')));
		Redirect(array('modul'=>'aNastaveni','akce'=>'vypis-nastaveni','isAjax'=>((int)getget('isAjax','0')) )); 
		}	
	private function vypisNastaveni(){						
		if(isset($_SESSION['nastaveni-vyhledavani'])&&trim($_SESSION['nastaveni-vyhledavani'])!=''){
      $fulltext=trim(prepareGetDataSafely($_SESSION['nastaveni-vyhledavani']));
      $filtrQ.=' WHERE zobrazovat=1 AND (klic LIKE "%'.$fulltext.'%" OR nazev LIKE "%'.$fulltext.'%" OR hodnota LIKE "%'.$fulltext.'%" OR popis LIKE "%'.$fulltext.'%") ';
    }else{
    	$fulltext='';
    	$filtrQ=' WHERE zobrazovat=1 ';
    }				
    $stranka=(int)getget('stranka','0');
		$citac=$this->nastaveni['administrace-pocet-strankovani'];		
		if($citac<1){$citac=1;}
		if($citac>100){$citac=100;}
		$promenne=$this->modely->NastaveniDb->getLines('*',' '.$filtrQ.' ORDER BY poradi ASC LIMIT '.($stranka*$citac).', '.$citac);
		$pocet=$this->modely->NastaveniDb->getOne('count(nid)',' '.$filtrQ.' ');
		$strankovac=$this->strankovacNastaveni($stranka,$pocet,$citac);	
		$tpl=new Sablona();		
		$tpl->nastavDebugMod($this->debugVars);		
		$tpl->pridatPromennou('fulltext',$fulltext);	
		$tpl->pridatPromennou('promenne',$promenne);		
		$tpl->pridatPromennou('stranka',$stranka);
		$tpl->pridatPromennou('strankovac',$strankovac);										
		return $tpl->spustit('aNastaveni/vypisNastaveni.tpl');		
		}
	private function strankovacNastaveni($stranka=0,$pocet=0,$citac=0){        
    $stranky=array();$maxstranka=0;$citatDo=ceil($pocet/$citac);
    for($i=0;$i<$citatDo;$i++){$stranky[($i+1)]=Anchor(array('modul'=>'aNastaveni','akce'=>'vypis-nastaveni','stranka'=>$i),false);$maxstranka=$i;}
    if(($stranka-1)<0){$stranky['predchozi']=Anchor(array('modul'=>'aNastaveni','akce'=>'vypis-nastaveni','stranka'=>'0'),false);}else{$stranky['predchozi']=Anchor(array('modul'=>'aNastaveni','akce'=>'vypis-nastaveni','stranka'=>($stranka-1)),false);}
    if(($stranka+1)>$maxstranka){$stranky['dalsi']=Anchor(array('modul'=>'aNastaveni','akce'=>'vypis-nastaveni','stranka'=>$maxstranka),false);}else{$stranky['dalsi']=Anchor(array('modul'=>'aNastaveni','akce'=>'vypis-nastaveni','stranka'=>($stranka+1)),false);}
    $stranky['prvni']=Anchor(array('modul'=>'aNastaveni','akce'=>'vypis-nastaveni','stranka'=>0),false);
    $stranky['posledni']=Anchor(array('modul'=>'aNastaveni','akce'=>'vypis-nastaveni','stranka'=>($citatDo-1)),false);    
    return $stranky;          
    } 
  private function editaceNastaveni(){
		$nid=(int)getget('nid','0');		
		$promenna=$this->modely->NastaveniDb->getLine('*','WHERE nid="'.$nid.'" AND zobrazovat=1');
		if(isset($promenna->nid)&&$promenna->nid>0){			
			$stranka=(int)getget('stranka','0');
			$tpl=new Sablona();		
			$tpl->nastavDebugMod($this->debugVars);
			$tpl->pridatPromennou('promenna',$promenna);						
			$tpl->pridatPromennou('stranka',$stranka);			
			return $tpl->spustit('aNastaveni/editaceNastaveni.tpl');	
			}
		Redirect(array('modul'=>'aNastaveni','akce'=>'vypis-nastaveni','m'=>'nastaveni-nenalezeno','isAjax'=>((int)getget('isAjax','0')) )); 	
		}  
	private function ulozeniNastaveni(){
		$nid=(int)getget('nid','0');			
		$promenna=$this->modely->NastaveniDb->getLine('*','WHERE nid="'.$nid.'" AND zobrazovat=1');
		if(isset($promenna->nid)&&$promenna->nid>0){
			$stranka=(int)getget('stranka','0');
			$postdata=array();			
		  $postdata['hodnota']=$this->_validuj_promennou($promenna->typ,getpost('hodnota',''));  		  				  		  
	    $this->modely->NastaveniDb->store($nid,$postdata);
	    Redirect(array('modul'=>'aNastaveni','akce'=>'editace-nastaveni','m'=>'nastaveni-ulozeno','nid'=>$nid,'stranka'=>$stranka,'isAjax'=>((int)getget('isAjax','0')) ));
			}
		Redirect(array('modul'=>'aNastaveni','akce'=>'vypis-nastaveni','m'=>'nastaveni-nenalezeno','stranka'=>$stranka,'isAjax'=>((int)getget('isAjax','0')) ));   		
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
