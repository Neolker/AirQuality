<?php
class UzivateleDb extends Model{
	public function __construct($db=null){		
		$this->setDb($db);		
		$this->setTable('uzivatele');
		$this->setPrimaryKey('uid');
		}	
	}
