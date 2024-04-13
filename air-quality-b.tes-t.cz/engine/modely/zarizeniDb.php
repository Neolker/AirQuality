<?php
class ZarizeniDb extends Model{
	public function __construct($db=null){		
		$this->setDb($db);		
		$this->setTable('zarizeni');
		$this->setPrimaryKey('zid');
		}	
	}
