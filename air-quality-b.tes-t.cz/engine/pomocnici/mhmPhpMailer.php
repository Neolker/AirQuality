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
		$this->phpMailer->Host="wes1-smtp.wedos.net";
		$this->phpMailer->Username="info@tes-t.cz";
    $this->phpMailer->Password="$2q4AE]w0";
		$this->phpMailer->Port=587;
		$this->phpMailer->SMTPAuth=true;
    $this->phpMailer->SMTPAutoTLS=true;  
		$this->phpMailer->IsHTML(true); 
		$this->phpMailer->setFrom('info@tes-t.cz');
		$this->phpMailer->CharSet="UTF-8";
		if($debug==true){
			$this->phpMailer->SMTPDebug=1;
			}
		}
  }
