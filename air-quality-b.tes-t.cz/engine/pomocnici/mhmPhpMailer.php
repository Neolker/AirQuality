<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
require 'phpMailer.6.1.7/Exception.php';
require 'phpMailer.6.1.7/PHPMailer.php';
require 'phpMailer.6.1.7/SMTP.php';
Class MHMmailer{
	public $phpMailer;
	public function __construct($debug=false){						
		$this->phpMailer=new PHPMailer();
		$this->phpMailer->IsSMTP();
		$this->phpMailer->Host="DOPLNIT";
		$this->phpMailer->Username="DOPLNIT";
    $this->phpMailer->Password="DOPLNIT";
		$this->phpMailer->Port=587;
		$this->phpMailer->SMTPAuth=true;
    $this->phpMailer->SMTPAutoTLS=true;  
		$this->phpMailer->IsHTML(true); 
		$this->phpMailer->setFrom('DOPLNIT');
		$this->phpMailer->CharSet="UTF-8";
		if($debug==true){
			$this->phpMailer->SMTPDebug=1;
			}
		}
  }
