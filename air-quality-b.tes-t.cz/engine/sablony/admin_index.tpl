<?
/* --- START BEZ AJAXU --- */
if(((int)getget('isAjax','0'))==0){
?><!DOCTYPE html>
	<html lang="cs">
	<head>
		<meta charset="UTF-8" />
	  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
	  <meta name="viewport" content="width=device-width, user-scalable=1, initial-scale=1" />	  
	  <meta name="robots" content="noindex, nofollow"/>
	  <title><?=trim($nastaveniModulu->nazevModulu.' | '.$nastaveni['jmeno-projektu'].' | Admin');?></title>
		<link rel="stylesheet" type="text/css" href="/css/kernel_font.css" media="all" />    
		<link rel="stylesheet" type="text/css" href="/css/admin_style.css" media="all" />		
		<link rel="stylesheet" type="text/css" href="/css/admin_screen.css" media="all" />
	  <link rel="stylesheet" type="text/css" href="/css/admin_print.css" media="print" />
	  <?if($uzivatel->uid>0&&$uzivatel->data->darkmode==1){?><link rel="stylesheet" type="text/css" href="/css/admin_darkmode.css" media="all" /><?}?>
		<link rel="stylesheet" type="text/css" href="/css/kernel.css" media="screen" />
		<link rel="stylesheet" type="text/css" href="/plugins/summerNote/css/summernote-lite.min.css" media="screen" />		
	  <link rel="icon" type="image/ico" href="/img/favicon.ico" />
	  <link rel="shortcut icon" type="image/x-icon" href="/img/favicon.ico" />
	  <script type="text/javascript" src="/plugins/jquery/jquery-3.6.0.min.js"></script>
	  <script type="text/javascript" src="/plugins/summerNote/js/summernote-lite.min.js"></script>
	  <script type="text/javascript" src="/plugins/summerNote/translation/summernote-cs-CZ.js"></script>
	  <script type="text/javascript" src="/js/admin_script.js"></script>        
	</head>
	<body class="bg-ddd <?if($uzivatel->uid>0&&$uzivatel->data->prava>1){?>admin-kernel<?}?>">
<?}/* --- KONEC BEZ AJAXU --- */?>
		<header role="banner">
			<div class="logo">
	      &nbsp;
	  	</div>   	
	  	<div class="nav-shortcut toggle-class hide-md" data-target="menu-1"></div>
	  	<nav role="navigation" class="hide show-md" id="menu-1"><?=$menuModulu;?></nav>  				
			<div class="user-nav">				        
				<?if($uzivatel->uid>0){?>
					<div class="item align-left">  
						<?if($uzivatel->uid>0&&$uzivatel->data->darkmode==1){?>      	
							<a href="<?=Anchor(array('modul'=>'Administrace','akce'=>'denni-rezim','rurl'=>safeurlBase64Encode(str_replace('isAjax=1','isAjax=0',$_SERVER['REQUEST_URI']))));?>" title="Přepnout na denní režim">
				        <i class="fa fa-sun fa-big"></i>
				      </a>
						<?}else{?>
							<a href="<?=Anchor(array('modul'=>'Administrace','akce'=>'nocni-rezim','rurl'=>safeurlBase64Encode(str_replace('isAjax=1','isAjax=0',$_SERVER['REQUEST_URI']))));?>" title="Přepnout na noční režim">
				        <i class="fa fa-moon fa-big"></i>
				      </a>
						<?}?>
					</div>
					<div class="item align-center" id="ajaxDatumCas">       
						<?if($uzivatel->uid>0&&$uzivatel->data->ajaxmode==1){?>      	
							<a href="<?=Anchor(array('modul'=>'Administrace','akce'=>'klasicke-nacitani','rurl'=>safeurlBase64Encode(str_replace('isAjax=1','isAjax=0',$_SERVER['REQUEST_URI']))));?>" title="Přepnout na klasické načítání stránek (načítat celou stránku)">
				        <i class="fa fa-refresh fa-big"></i>
				        <input type="hidden" value="1" id="ajaxActivated" />
				      </a>
						<?}else{?>
							<a href="<?=Anchor(array('modul'=>'Administrace','akce'=>'chytre-nacitani','rurl'=>safeurlBase64Encode(str_replace('isAjax=1','isAjax=0',$_SERVER['REQUEST_URI']))));?>" title="Přepnout na chytré načítání stránek (načítat jen část stránky)">
				        <i class="fa fa-random fa-big"></i>
				        <input type="hidden" value="0" id="ajaxActivated" />
				      </a>
						<?}?> 	        	
		      </div>				
					<div class="item">
            <a title="Můj účet" href="<?=Anchor(array('modul'=>'aUzivatele','akce'=>'editace-uzivatele','uid'=>$uzivatel->data->uid));?>"><?=trim('<span class="username">'.$uzivatel->data->titul.' '.$uzivatel->data->jmeno.' </span>'.$uzivatel->data->prijmeni);?></a>
		      </div>
					<div class="item">
	          <a href="<?=Anchor(array('modul'=>'Administrace','akce'=>'odhlas-me'));?>" title="Odhlásit se">
	            <i class="fa fa-power-off fa-big"></i>
	          </a>
		      </div>		
		      	      		      
				<?}else{?>
					<div class="item">
						<i class="fa fa-user-times"></i>
					</div>
					<div class="item">
						Uživatel není přihlášen.
					</div>					
				<?}?>												        
	    </div>	    	    
		</header>
		<main role="main" class="bg-cream" id="main" >
			<?if($uzivatel->uid>0){?>
				<div class="wrap bg-menu-lighter pad-x-16 pad-y-8 row gap-0"> 					
					<div class="col-xs-1 col-md-12 pad-0 gap-0 pad-left-4 align-left">						
						<div class="bg-menu-lighter-hide">
							<?=$podmenuModulu?>
						</div>
					</div>													
				</div>
			<?}?> 			
			<?=$dataModulu?>			
		</main>		
		<footer role="contentinfo">
			<div class="footer-upper">
		    <div class="pad-y-8">
	        <div class="row col-xs-pad-y-0 center-xs middle-xs">
            <div class="col-xs-12 col-sm-4">
            	
            </div>
            <div class="col-xs-12 col-sm-4">
            	<small><b>Administrace verze <?=$verzeSystemu;?></b></small><br /><br />
            	<b>Copyright &copy; <?=strftime('%Y',time());?></b><br />
            	&nbsp;
            </div>
            <div class="col-xs-12 col-sm-4">
            	
            </div>
	        </div>
		    </div>
			</div>
		</footer>				
		<?if($uzivatel->uid>0){?>
			<div id="admin-bar">
				<div class="admin-bar-visiblemenu">				
					<a href="<?=Anchor(array('modul'=>'Administrace','akce'=>'odhlas-me'));?>" class="admin-bar-button" onclick="return confirm('Opravdu si přejete se odhlásit?');">
						<i class="fa fa-power-off fa-big"></i><span class="admin-bar-tooltip">Odhlásit&nbsp;se</span>
					</a>													
					
					<a href="<?=Anchor(array('modul'=>'Administrace'));?>" class="admin-bar-button admin-bar-jump active">
						<i class="fa fa-cube fa-big"></i><span class="admin-bar-tooltip">Přejít&nbsp;do&nbsp;administrace</span>
					</a>
					<a href="/<?=$menuJazyku->kodSoucasnehoJazyka;?>/" class="admin-bar-button">
						<i class="fa fa-house-chimney fa-big"></i><span class="admin-bar-tooltip">Přejít&nbsp;na&nbsp;web</span>
					</a>		
										
					<?if($uzivatel->data->prava==3){?>
						<a href="<?=Anchor(array('modul'=>'Administrace','akce'=>'mhm-info','rurl'=>safeurlBase64Encode(str_replace('isAjax=1','isAjax=0',$_SERVER['REQUEST_URI']))));?>" class="admin-bar-button admin-bar-jump <?if($uzivatel->data->infomode==1){?>active<?}?>">
							<i class="fa fa-lightbulb fa-big"></i><span class="admin-bar-tooltip"><?if($uzivatel->data->infomode==1){?>☑<?}else{?>☐<?}?>&nbsp;Ladící&nbsp;informace</span>
						</a>
						<a href="<?=Anchor(array('modul'=>'Administrace','akce'=>'mhm-vars','rurl'=>safeurlBase64Encode(str_replace('isAjax=1','isAjax=0',$_SERVER['REQUEST_URI']))));?>" class="admin-bar-button <?if($uzivatel->data->varsmode==1){?>active<?}?>">
							<i class="fa fa-chart-simple fa-big"></i><span class="admin-bar-tooltip"><?if($uzivatel->data->varsmode==1){?>☑<?}else{?>☐<?}?>&nbsp;Proměnné&nbsp;šablon</span>
						</a>
						<a href="<?=Anchor(array('modul'=>'Administrace','akce'=>'mhm-error','rurl'=>safeurlBase64Encode(str_replace('isAjax=1','isAjax=0',$_SERVER['REQUEST_URI']))));?>" class="admin-bar-button <?if($mhmError==1){?>active<?}?>">
							<i class="fa fa-bug fa-big"></i><span class="admin-bar-tooltip"><?if($mhmError==1){?>☑<?}else{?>☐<?}?>&nbsp;Debug&nbsp;mód</span>
						</a>
					<?}?>
				</div>
			</div>
		<?}?>
<?
/* --- START BEZ AJAXU --- */
if(((int)getget('isAjax','0'))==0){
?>
	</body>
	</html>
<?}/* --- KONEC BEZ AJAXU --- */?>
