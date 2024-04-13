<?if($uzivatel->uid>0&&$uzivatel->data->prava>1&&$uzivatel->data->rezim_uprav==1){?>
	<div class="align-right">
		<a class="admin-btn" href="<?=Anchor(array('modul'=>'aObsahWebuUvodniStranka'));?>" target="_blank" title="Otevřít administraci úvodní stránky"><fa class="fa fa-screwdriver-wrench"></fa> Administrace stránky</a>
	</div>
<?}?>
<?=$pluginy;?>
