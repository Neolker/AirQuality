<?php
Class Databaze{
  private $connector;
  private $log;
  static private $instance=NULL;  
  public function __construct(){
  	self::$instance=$this;
  	$this->log=array();
  	}  
  static function getInstance(){return self::$instance;} 
  public function Login($loginData=array()){
    $time_start=microtime(true);
    if(count($loginData)<1){return 'E_DB_NOLOGINDATAGET';}    
    $this->connector=new mysqli($loginData['host'],$loginData['user'],$loginData['password'],$loginData['db']);
    if(empty($this->connector)||mysqli_connect_errno()){return 'E_DB_CONNECTERROR';}
    $this->connector->set_charset($loginData['charset']);
    $this->connector->query("SET collation_connection = ".$loginData['collation']);
    $time_end=microtime(true);
    $this->AddLog($time_start,$time_end,'DB LOGIN + DB CHARSET');
    return 'E_SUCCESS';
    }  
  public function Logout(){
    $time_start=microtime(true);
    mysqli_close($this->connector);
    $time_end=microtime(true);
    $this->AddLog($time_start,$time_end,'DB LOGOUT');
    }
  public function ReturnConnector(){return $this->connector;}
  public function ReturnLog(){return $this->log;} 
  public function AddLog($time_start,$time_end,$query,$key='-'){
    $x=new stdClass();     
    $x->query=$query;
    $x->time_start=$time_start;
    $x->time_end=$time_end;
    $x->time_final=round((($time_end-$time_start)),6);  
    $x->inserted_key=$key;  
    $this->log[]=$x;
    unset($x);
    }
  public function Mquery($q=''){
    $time_start=microtime(true);
    try{$a=$this->connector->query($q);}catch(Exception $e){$a=null;}
    $time_end=microtime(true);
    $this->AddLog($time_start,$time_end,$q);
    return $a;
    }
  public function MqueryInsert($q=''){
    $time_start=microtime(true);
    try{      
      $this->connector->query($q);    
      $key=$this->connector->insert_id;      
    }catch(Exception $e){$key=0;}
    $time_end=microtime(true);
    $this->AddLog($time_start,$time_end,$q,$key);
    return $key;    
    } 
  public function MqueryGetOne($q=''){
    $time_start=microtime(true);
    try{
      $result=$this->connector->query($q);    
      $object=$result->fetch_array();
      $result->close();
      }catch(Exception $e){$object=array(0=>null);}
    $time_end=microtime(true);
    $this->AddLog($time_start,$time_end,$q);
    if(!isset($object)){
    	return null;
    	}
    return $object[0]; 
    }
  public function MqueryGetLine($q='',$type='object'){
    $time_start=microtime(true);
    try{      
      $result=$this->connector->query($q);      
      if($type=='object'){$object=$result->fetch_object();}else{$object=$result->fetch_row();}
      $result->close();
    }catch (Exception $e) {$object=null;}
    $time_end=microtime(true);
    $this->AddLog($time_start,$time_end,$q);
    return $object; 
    }
  public function MqueryGetLines($q='',$type='object'){    
    $time_start=microtime(true);
    $array=array();
    try{
      $result=$this->connector->query($q);
      if(isset($result)){
        if($type=='object'){
          while($x=$result->fetch_object()){$array[]=$x;}    
        }else{ 
          while($x=$result->fetch_row()){$array[]=$x;}             
        }
      }
    }catch (Exception $e) {}
    $time_end=microtime(true);    
    $this->AddLog($time_start,$time_end,$q);
    return $array;   
    }
  }
