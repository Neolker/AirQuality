<?php
class aNamerenaData extends Modul{	
	public function hlavniFunkce(){		
		$akce=getget('akce','vypis-dat');			
		if($akce=='vypis-dat'){return $this->vypisDat();}		
		elseif($akce=='zmena-vyhledavani'){return $this->zmenaVyhledavani();}	
		else{Redirect(array('isAjax'=>((int)getget('isAjax','0'))));}
		return 'Modul> Administrace / Naměřená data';	
		}
	private function zmenaVyhledavani(){
		$_SESSION['namerena-data-vyhledavani']=trim(prepareGetDataSafely(getpost('fulltext','')));
		Redirect(array('modul'=>'aNamerenaData','akce'=>'vypis-dat','isAjax'=>((int)getget('isAjax','0')) )); 
		}	
	private function vypisDat(){														
		if(isset($_SESSION['namerena-data-vyhledavani'])&&trim($_SESSION['namerena-data-vyhledavani'])!=''){
      $fulltext=trim(prepareGetDataSafely($_SESSION['namerena-data-vyhledavani']));
      $filtrQ=' AND (z.nazev LIKE "%'.$fulltext.'%" OR u.nazev LIKE "%'.$fulltext.'%" OR z.vyrobni_cislo LIKE "%'.$fulltext.'%" OR z.lokalita LIKE "%'.$fulltext.'%") ';
    }else{
    	$fulltext='';
    	$filtrQ=' ';
    }				
    $stranka=(int)getget('stranka','0');
		$citac=$this->nastaveni['administrace-pocet-strankovani'];		
		if($citac<1){$citac=1;}
		if($citac>100){$citac=100;}
		$data=$this->modely->NamerenaDataDb->MqueryGetLines('SELECT nd.*, z.nazev as nazev_zarizeni, z.vyrobni_cislo, z.lokalita, u.nazev as uzivatel FROM namerena_data as nd, zarizeni as z, uzivatele as u WHERE nd.id_zarizeni=z.zid AND nd.id_uzivatele=u.uid  '.$filtrQ.' ORDER BY unix_ts DESC LIMIT '.($stranka*$citac).', '.$citac);
		$pocet=$this->modely->NamerenaDataDb->MqueryGetOne('SELECT count(nd.ndid) FROM namerena_data as nd, zarizeni as z, uzivatele as u WHERE nd.id_zarizeni=z.zid AND nd.id_uzivatele=u.uid  '.$filtrQ.'');
		$strankovac=$this->strankovac($stranka,$pocet,$citac);	
		$tpl=new Sablona();		
		$tpl->nastavDebugMod($this->debugVars);		
		$tpl->pridatPromennou('fulltext',$fulltext);	
		$tpl->pridatPromennou('data',$data);		
		$tpl->pridatPromennou('stranka',$stranka);
		$tpl->pridatPromennou('strankovac',$strankovac);										
		return $tpl->spustit('aNamerenaData/vypisNamerenychDat.tpl');		
		}
	private function strankovac($stranka=0,$pocet=0,$citac=0){        
    $stranky=array();$maxstranka=0;$citatDo=ceil($pocet/$citac);
    for($i=0;$i<$citatDo;$i++){$stranky[($i+1)]=Anchor(array('modul'=>'aNamerenaData','akce'=>'vypis-dat','stranka'=>$i),false);$maxstranka=$i;}
    if(($stranka-1)<0){$stranky['predchozi']=Anchor(array('modul'=>'aNamerenaData','akce'=>'vypis-dat','stranka'=>'0'),false);}else{$stranky['predchozi']=Anchor(array('modul'=>'aNamerenaData','akce'=>'vypis-dat','stranka'=>($stranka-1)),false);}
    if(($stranka+1)>$maxstranka){$stranky['dalsi']=Anchor(array('modul'=>'aNamerenaData','akce'=>'vypis-dat','stranka'=>$maxstranka),false);}else{$stranky['dalsi']=Anchor(array('modul'=>'aNamerenaData','akce'=>'vypis-dat','stranka'=>($stranka+1)),false);}
    $stranky['prvni']=Anchor(array('modul'=>'aNamerenaData','akce'=>'vypis-dat','stranka'=>0),false);
    $stranky['posledni']=Anchor(array('modul'=>'aNamerenaData','akce'=>'vypis-dat','stranka'=>($citatDo-1)),false);    
    return $stranky;          
    }   
  }
