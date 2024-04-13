<?php
class NastaveniDb extends Model{
	public function __construct($db=null){		
		$this->setDb($db);		
		$this->setTable('nastaveni');
		$this->setPrimaryKey('nid');
		}	
	}
