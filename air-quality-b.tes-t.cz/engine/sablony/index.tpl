<?
/* --- START BEZ AJAXU --- */
if(((int)getget('isAjax','0'))==0){
?><!DOCTYPE html>
<html <?if(isset($jazyky)&&is_array($jazyky)&&count($jazyky)>0){foreach($jazyky as $j){if($j->jid==$pouzityJazyk){?>lang="<?=$j->kod_jazyka;?>"<?}}}?> >
	<head>
		<meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, user-scalable=1, initial-scale=1" />   
    <meta name="robots" content="noindex, nofollow"/>
    <title><?if($nastaveni['titulek-pred']!=''){echo $nastaveni['titulek-pred'].' | ';}?><?=$seoModulu->titulek;?><?if($nastaveni['titulek-za']!=''){echo ' | '.$nastaveni['titulek-za'];}?></title>
    <meta name="description" content="<?=$seoModulu->popis;?>" />
    <meta name="keywords" content="<?=$seoModulu->klicovaSlova;?>" />
    <?if($seoModulu->uriObrazku!=''){?><meta property="og:image" content="<?=$seoModulu->uriObrazku?>" /><?}?>           
   	<?/*favicons,theme:*/?>
    <link rel="icon" type="image/png" sizes="32x32" href="/img/favicon.ico">
    <link rel="icon" type="image/png" sizes="16x16" href="/img/favicon.ico">
    <link rel="shortcut icon" type="image/x-icon" href="/img/favicon.ico" />    
    <link rel="mask-icon" href="/img/favicons/safari-pinned-tab.svg" color="#5bbad5">
    <meta name="msapplication-TileColor" content="#de1835">
    <meta name="theme-color" content="#ffffff">    
    <?/*css:*/?>
  	<link rel="stylesheet" type="text/css" href="/css/style.css" media="all">  	
    <link rel="stylesheet" type="text/css" href="/css/screen.css" media="all">
    <link rel="stylesheet" type="text/css" href="/css/responsive.css" media="all">
    <link rel="stylesheet" type="text/css" href="/css/print.css" media="all">
  	<link rel="stylesheet" type="text/css" href="/css/kernel.css" media="all" />
	  <link rel="stylesheet" type="text/css" href="/css/kernel_font.css" media="all" />    	  
	  <?/*js:*/?>
    <script type="text/javascript" src="/plugins/jquery/jquery-3.6.0.min.js"></script>
    <script type="text/javascript" src="/js/frontend_script.js"></script>          
    <?if($uzivatel->uid>0&&$uzivatel->data->prava>1){?>	
    	<?/*admin js a css:*/?> 
    	<link rel="stylesheet" type="text/css" href="/plugins/summerNote/css/summernote-lite.min.css" media="screen" />			
		  <script type="text/javascript" src="/plugins/summerNote/js/summernote-lite.min.js"></script>
			<script type="text/javascript" src="/plugins/summerNote/translation/summernote-cs-CZ.js"></script>
		  <script type="text/javascript" src="/js/kernel_script.js"></script>       
    <?}?>        
	</head>
	<body>
<?}/* --- KONEC BEZ AJAXU --- */?>	
		
			<header role="banner" id="page-header">
		  	<div class="wrap full mw-1920">
					<div class="logo no-print">
					  <a href="<?=Anchor(array('modul'=>'Uvod','idj'=>$pouzityJazyk));?>" title="<?=$preklady['prejit-na-uvod'];?>"><img src="/img/logos/logo.svg" alt="<?=$nastaveni['jmeno-projektu'];?>" /></a>
					</div>   					
					
																									
				</div>    	    
			</header>
		
   
		<main role="main" <?if($uzivatel->uid>0){?>class="bg-cream"<?}?> id="main" >
			<div class="wrap">
				<?=$dataModulu?>
			</div>
		</main>					
		<footer role="contentinfo" class="bg-gray">
		
			<div class="wrap full pad-0 gap-0-a pad-top-16 mw-1920">
				<div class="grid footer-bottom">
					<div class="col-xs-12 col-sm-10 col-sm-offset-1 col-md-6 col-md-offset-3 color-white pad-left-32 pad-right-32">
						<div class="flex justify-content-space-between align-items-center footer-copyright">
							
							<div class="size-12">
								<em class="fa-regular fa-copyright"></em>
								
							</div>							
							
						</div>
					</div>
				</div>
			</div>
		</footer>
		<?if($uzivatel->uid>0&&$uzivatel->data->prava>1){?>			
			<div id="admin-bar" class="no-print">
				<div class="admin-bar-visiblemenu">				
					<a href="<?=Anchor(array('modul'=>'Administrace','akce'=>'odhlas-me'));?>" class="admin-bar-button" onclick="return confirm('Opravdu si přejete se odhlásit?');">
						<i class="fa fa-power-off fa-big"></i><span class="admin-bar-tooltip">Odhlásit&nbsp;se</span>
					</a>													
					
					<a href="<?=Anchor(array('modul'=>'Administrace'));?>" class="admin-bar-button admin-bar-jump">
						<i class="fa fa-cube fa-big"></i><span class="admin-bar-tooltip">Přejít&nbsp;do&nbsp;administrace</span>
					</a>
					<?if(isset($jazyky)&&is_array($jazyky)&&count($jazyky)>0){foreach($jazyky as $j){if($j->vychozi==1){?>
						<a href="/<?=$j->kod_jazyka;?>/" class="admin-bar-button active">
							<i class="fa fa-house-chimney fa-big"></i><span class="admin-bar-tooltip">Přejít&nbsp;na&nbsp;web</span>
						</a>			
					<?}}}?>					
									
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
				<input type="hidden" value="1" id="ajaxActivated" />
			</div>
		<?}?>		
<?
/* --- START BEZ AJAXU --- */
if(((int)getget('isAjax','0'))==0){
?>				
	</body>
</html>
<?}/* --- KONEC BEZ AJAXU --- */?>
