<?php
class NamerenaDataDb extends Model{
	public function __construct($db=null){		
		$this->setDb($db);		
		$this->setTable('namerena_data');
		$this->setPrimaryKey('ndid');
		}	
	}
