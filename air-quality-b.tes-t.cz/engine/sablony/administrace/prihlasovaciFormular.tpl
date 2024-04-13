<?if(getget('m','')=='odhlaseni-v-poradku'){?><div class="m-wrap bg-light-green"> <em class="fa fa-check-circle"></em> Uživatel úspěšně odhlášen ze systému.</div><?}?>
<?if(getget('m','')=='nove-heslo-vygenerovano'){?><div class="m-wrap bg-light-green"> <em class="fa fa-check-circle"></em> Nové heslo bylo úspěšně vygenerováno a odesláno na E-mail přihlašované osoby.</div><?}?>
<?if(getget('m','')=='spatne-login-email'){?><div class="m-wrap bg-light-red"> <em class="fa fa-exclamation-triangle"></em> Nové heslo se nepodařilo vygenerovat - špatně zadané přihlašovací jméno a nebo E-mail přihlašované osoby.</div><?}?>
<?if(getget('m','')=='prihlaseni-se-nezdarilo'){?><div class="m-wrap bg-light-red"> <em class="fa fa-exclamation-triangle"></em> Přihlášení uživatele se nezdařilo.</div><?}?>
<div class="wrap main-wrap">        
  <div class="grid">
    <div class="col-xs-12 col-md-6 col-md-offset-3 center-xs pad-32 gap-top-32 black-shadow bg-fff">        
      <h1>Přihlášení do systému</h1>
     	<form method="post" action="<?=$aPrihlasMe;?>">   
		    <div class="col-md-6 col-xs-12 col-md-offset-3 align-left pad-y-8 gap-top-32">
		      <label>
		        Přihlašovací jméno:<br />
		        <input type="text" name="login" class="w-100" required />
		      </label>
		    </div>
		    <div class="col-md-6 col-xs-12 col-md-offset-3 align-left pad-y-8">
		      <label>
				    Heslo:<br />
				    <input type="password" name="heslo" class="w-100" required />
		      </label>
		    </div>
		    <button type="submit" class="btn-fill btn-large btn-blue gap-top-32">Přihlásit se</button>							   
			</form>
      <hr />
        <a onclick="$('.zapomenuteHeslo').toggle('slow');">Zapomněli jste heslo?</a>
        <div class="zapomenuteHeslo" style="display:none;">
        	<form method="post" action="<?=$aPosliHeslo;?>">   
	        	<h2>Zapomenuté heslo</h2>
	        	<div class="col-md-6 col-xs-12 col-md-offset-3 align-left pad-y-8 gap-top-32">
						  <label>
						    Přihlašovací jméno:<br />
						    <input type="text" name="login" class="w-100" required />
						  </label>
						</div>
						<div class="col-md-6 col-xs-12 col-md-offset-3 align-left pad-y-8">
						  <label>
						    E-mail přihlašované osoby:<br />
						    <input type="email" name="email" class="w-100" required />
						  </label>
						</div>
						<button type="submit" class="btn-fill btn-large btn-blue gap-top-32">Vygenerovat nové heslo</button>						
        	</form>
        </div>
    </div>
  </div>
  <br /><br />
</div>
