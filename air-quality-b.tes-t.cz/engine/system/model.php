<?php
Class Model{
  private $db;
  private $table;
  private $primary_key;
  public function __construct(){ 
    $this->db=null;
    $this->primary_key='';
    $this->table='';
    }
  public function setTable($table=''){$this->table=$table;}
  public function setPrimaryKey($key='id'){$this->primary_key=$key;}
  public function setDb($xdb=null){$this->db=&$xdb;}
  public function getTable(){return $this->table;}
  public function getPrimaryKey(){return $this->primary_key;}  
  public function Mquery($q=''){return $this->db->Mquery($q);}
  public function MqueryInsert($q=''){ return $this->db->MqueryInsert($q);}
  public function MqueryGetOne($q=''){ return $this->db->MqueryGetOne($q);}
  public function MqueryGetLine($q='',$type='object'){return $this->db->MqueryGetLine($q);}
  public function MqueryGetLines($q='',$type='object'){return $this->db->MqueryGetLines($q);}
  public function getOne($what='*',$where=''){return $this->MqueryGetOne('SELECT '.$what.' FROM '.$this->table.' '.$where);}
  public function getLine($what='*',$where='',$type='object'){return $this->MqueryGetLine('SELECT '.$what.' FROM '.$this->table.' '.$where,$type);}
  public function getLines($what='*',$where='',$type='object'){return $this->MqueryGetLines('SELECT '.$what.' FROM '.$this->table.' '.$where,$type);}
  public function insert($array=array()){return $this->MqueryInsert('INSERT INTO '.$this->table.' '.$this->Concat2($array));}
  public function updateId($id=0,$array=array()){if($id>0){return $this->Mquery('UPDATE '.$this->table.' SET '.$this->Concat($array).' WHERE '.$this->primary_key.'='.((int)$id));}}
  public function updateWhere($where='WHERE 1=1',$array=array()){return $this->Mquery('UPDATE '.$this->table.' SET '.$this->Concat($array).' '.$where);}
  public function deleteId($id=0){return $this->mquery('DELETE FROM '.$this->table.' WHERE '.$this->primary_key.'='.((int)$id));}
  public function deleteWhere($where='WHERE 1=1'){return $this->mquery('DELETE FROM '.$this->table.' '.$where);}
  public function store($id=0,$array=array()){    
    $id=(int)$id;        
    if($id>0){
      $this->updateId($id,$array);
      $id2=$id;                
    }else{
      $id2=$this->insert($array);
    }
    return $id2;
    }
  public function Concat($arr=array()){
    $s=array();
    foreach($arr as $a=>$b){$s[]=$a.'="'.addslashes($b).'"';}
    return implode(',',$s);
    }
  public function Concat2($arr=array()){
    $s=array();
    $q=array();
    foreach($arr as $a=>$b){
      $s[]=$a;
      $q[]='"'.addslashes($b).'"';
      }
    return '('.implode(',',$s).') VALUES ('.implode(',',$q).')';
    }
  }
