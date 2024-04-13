<?php
class aSmazaniUzivatele extends Modul{	
	public function hlavniFunkce(){		
		$akce=getget('akce','vypis-uzivatelu');
		if($this->uzivatel->uid>0&&$this->uzivatel->data->prava==3){
			if($akce=='vypis-uzivatelu'){return $this->vypisUzivatelu();}
			elseif($akce=='zmena-razeni'){return $this->zmenaRazeni();}			
			elseif($akce=='zmena-vyhledavani'){return $this->zmenaVyhledavani();}	
			elseif($akce=='obnovit-uzivatele'){return $this->obnovitUzivatele();}			
			elseif($akce=='smaz-uzivatele'){return $this->smazatUzivatele();}			
			else{Redirect(array('isAjax'=>((int)getget('isAjax','0'))));}
		}else{
			Redirect(array('isAjax'=>((int)getget('isAjax','0'))));
		}
		return 'Modul> Administrace / Uživatelé / Smazaní uživatelé';		
		}
	private function zmenaRazeni(){
		$_SESSION['smazani-uzivatele-razeni']=prepareGetDataSafely(getget('typ',''));
		$stranka=(int)getget('stranka','0');
		Redirect(array('modul'=>'aSmazaniUzivatele','akce'=>'vypis-uzivatelu','stranka'=>$stranka,'isAjax'=>((int)getget('isAjax','0')) )); 
		}
	private function zmenaVyhledavani(){
		$_SESSION['smazani-uzivatele-vyhledavani']=trim(prepareGetDataSafely(getpost('fulltext','')));
		Redirect(array('modul'=>'aSmazaniUzivatele','akce'=>'vypis-uzivatelu','isAjax'=>((int)getget('isAjax','0')) )); 
		}
	private function vypisUzivatelu(){	
		if(isset($_SESSION['smazani-uzivatele-razeni'])){
      $razeni=$_SESSION['smazani-uzivatele-razeni'];
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
    $filtrQ=' WHERE odstraneny_uzivatel=1 ';
    if(isset($_SESSION['smazani-uzivatele-vyhledavani'])&&trim($_SESSION['smazani-uzivatele-vyhledavani'])!=''){
      $fulltext=trim(prepareGetDataSafely($_SESSION['smazani-uzivatele-vyhledavani']));
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
		if(isset($uzivatele)&&is_array($uzivatele)&&count($uzivatele)>0){
			foreach($uzivatele as $uk=>$u){
				unset($uzivatele[$uk]->session);
				unset($uzivatele[$uk]->heslo);
				unset($uzivatele[$uk]->heslo_2);			
				}
			}	   						   		
		$tpl=new Sablona();		
		$tpl->nastavDebugMod($this->debugVars);
		$tpl->pridatPromennou('stranka',$stranka);
		$tpl->pridatPromennou('uzivatele',$uzivatele);		
		$tpl->pridatPromennou('pravaUzivatelu',$this->konfigurace->pravaUzivatelu);
		$tpl->pridatPromennou('strankovac',$strankovac);
		$tpl->pridatPromennou('razeni',$razeni);
		$tpl->pridatPromennou('fulltext',$fulltext);		
		return $tpl->spustit('aSmazaniUzivatele/vypisSmazanychUzivatelu.tpl');	
		}
	private function strankovacUzivatelu($stranka=0,$pocet=0,$citac=0){        
    $stranky=array();$maxstranka=0;$citatDo=ceil($pocet/$citac);
    for($i=0;$i<$citatDo;$i++){$stranky[($i+1)]=Anchor(array('modul'=>'aSmazaniUzivatele','akce'=>'vypis-uzivatelu','stranka'=>$i),false);$maxstranka=$i;}
    if(($stranka-1)<0){$stranky['predchozi']=Anchor(array('modul'=>'aSmazaniUzivatele','akce'=>'vypis-uzivatelu','stranka'=>'0'),false);}else{$stranky['predchozi']=Anchor(array('modul'=>'aSmazaniUzivatele','akce'=>'vypis-uzivatelu','stranka'=>($stranka-1)),false);}
    if(($stranka+1)>$maxstranka){$stranky['dalsi']=Anchor(array('modul'=>'aSmazaniUzivatele','akce'=>'vypis-uzivatelu','stranka'=>$maxstranka),false);}else{$stranky['dalsi']=Anchor(array('modul'=>'aSmazaniUzivatele','akce'=>'vypis-uzivatelu','stranka'=>($stranka+1)),false);}
    $stranky['prvni']=Anchor(array('modul'=>'aSmazaniUzivatele','akce'=>'vypis-uzivatelu','stranka'=>0),false);
    $stranky['posledni']=Anchor(array('modul'=>'aSmazaniUzivatele','akce'=>'vypis-uzivatelu','stranka'=>($citatDo-1)),false);    
    return $stranky;          
    } 
	private function obnovitUzivatele(){
		$uid=(int)getget('uid','0');
		$uzivatel=$this->modely->UzivateleDb->getLine('*','WHERE uid="'.$uid.'"');
		if(isset($uzivatel->uid)&&$uzivatel->uid>0&&$this->uzivatel->data->prava==3){
			$this->modely->UzivateleDb->updateId($uid,array('aktivni_uzivatel'=>'1','odstraneny_uzivatel'=>'0'));  			  			
			Redirect(array('modul'=>'aSmazaniUzivatele','akce'=>'vypis-uzivatelu','m'=>'uzivatel-obnoven','isAjax'=>((int)getget('isAjax','0')) ));    
			}
		Redirect(array('modul'=>'aSmazaniUzivatele','akce'=>'vypis-uzivatelu','m'=>'uzivatel-nenalezen','isAjax'=>((int)getget('isAjax','0')) )); 	
		}
	private function smazatUzivatele(){
		$uid=(int)getget('uid','0');
		$uzivatel=$this->modely->UzivateleDb->getLine('*','WHERE uid="'.$uid.'"');
		if(isset($uzivatel->uid)&&$uzivatel->uid>0&&$uzivatel->prava<3&&$uzivatel->prava<=$this->uzivatel->data->prava&&$uzivatel->uid!=$this->uzivatel->data->uid){
			$this->modely->UzivateleDb->deleteId($uid);  						
			Redirect(array('modul'=>'aSmazaniUzivatele','akce'=>'vypis-uzivatelu','m'=>'uzivatel-smazan','isAjax'=>((int)getget('isAjax','0')) ));    
			}
		Redirect(array('modul'=>'aSmazaniUzivatele','akce'=>'vypis-uzivatelu','m'=>'uzivatele-se-nepodarilo-smazat','isAjax'=>((int)getget('isAjax','0')) )); 
	
		}
  }
