document.addEventListener("DOMContentLoaded", function () {
  init();
});
function init() {
	initMinimalHeight(); // minimalni vyska adminu
	toggleClass(); // responzivni menu
  autoscroll(); // autoscroll - posunuti jako kotva
  inputValidationInit(); // validace vstupu      
  summerNoteInit();	// SummerNote editor
  ajaxInit(); //ajaxove funkce            
}
function minimalizeMaximalizeInfoBox(){
	let infobox=$('.kernel_info_box');
		if(infobox.hasClass('minimalize')){
			$('.kernel_info_box').removeClass('minimalize');
		}else{
			$('.kernel_info_box').addClass('minimalize');			
		}
	}
function initMinimalHeight(){
	let wh=$(window).height();
	let ww=$(window).width();
	let mh=$('#main').height();
		if(ww>700){
			if(wh>mh&&wh>150){$('#main').css('min-height',(wh-95)+"px");}
		}else{		
			if(wh>mh&&wh>315){$('#main').css('min-height',(wh-95)+"px");} 
		}
	}
function autoscroll() {
  let autoscroll = document.getElementsByClassName("autoscroll");
  if (exist(autoscroll) && autoscroll.length) {
    window.scroll({top: autoscroll[autoscroll.length - 1].offsetTop, left: 0, behavior: 'smooth'});
  }
}
function inputValidationInit() {
  let $input = $('input.validate:not(.initialized)');
  if (exist($input)) {
    if ($input.hasClass('number-only')) {
      setInputFilter($input[0], function (value) {
        return /^\d*\.?\d*$/.test(value);
      });
      $input.addClass('initialized');
    }
    if ($input.hasClass('text-only')) {
      setInputFilter($input[0], function (value) {
        return /^[a-z]*$/i.test(value);
      });
      $input.addClass('initialized');
    }
  }
}
function setInputFilter(textbox, inputFilter) {
  ["input", "keydown", "keyup", "mousedown", "mouseup", "select", "contextmenu", "drop"].forEach(function (event) {
    textbox.addEventListener(event, function () {
      if (inputFilter(this.value)) {
        this.oldValue = this.value;
        this.oldSelectionStart = this.selectionStart;
        this.oldSelectionEnd = this.selectionEnd;
      } else if (this.hasOwnProperty("oldValue")) {
        this.value = this.oldValue;
        this.setSelectionRange(this.oldSelectionStart, this.oldSelectionEnd);
      } else {
        this.value = "";
      }
    });
  });
}
function toggleClass() {
  let toggleBtn = document.getElementsByClassName("toggle-class");
  if (exist(toggleBtn)) {
    Array.prototype.forEach.call(toggleBtn, function (el) {
      if((" " + el.className + " ").replace(/[\n\t]/g, " ").indexOf(" initialized ") > -1 == false){
        el.classList.add('initialized');
        el.onclick = function () {
          var className = this.dataset.className ? this.dataset.className : 'active';
          document.getElementById(this.dataset.target).classList.toggle(className);
          var txt = this.textContent || this.innerText;
          if (this.dataset.textAfter && txt != this.dataset.textAfter) {
            this.innerText = this.dataset.textAfter;
          } else if (this.dataset.textBefore && txt == this.dataset.textAfter) {
            this.innerText = this.dataset.textBefore;
          }
        }
      }
    });
  }
}
function exist(el) {
  if (typeof (el) != 'undefined' && el != null) {
    return true;
  } else {
    return false;
  }
}
function summerNoteInit(){
	let $summernote = $('.summernote:not(.initialized)');
	if(typeof($summernote) != 'undefined' && $summernote.length){
		$.each($summernote, function(e){
			$(this).addClass('initialized');
			$(this).summernote({
				toolbar: [
					//[groupname, [button list]]
					['style', ['undo', 'redo', 'style']],
					['font', ['bold', 'italic', 'underline', 'strikethrough', 'superscript', 'subscript', 'clear']],
					['color', ['color']],
					['para', ['ul', 'ol', 'paragraph']],
					['table', ['table']],
					['insert', ['link', 'picture', 'video', 'hr']],
					['help', ['codeview', 'help']]
				],
				 styleTags: [
					'p', 'h1', 'h2', 'h3', 'h4', 'h5', 'h6'
				],
				lang: "cs-CZ",
				minHeight: 200
			});
			$('div.note-group-select-from-files').remove();
		});
	}
}
// AJAX
function ajaxInit() {
	if(exist($('#ajaxActivated'))){	
		if($('#ajaxActivated').val()==1){		
			//console.log('Ajax activated.');
			let $isAjaxA=$('.isAjax:not(.initialized)');
			if(typeof($isAjaxA)!='undefined'&&$isAjaxA.length){
		    $.each($isAjaxA,function(e){
		    	if(typeof($(this).attr('onclick'))!='undefined'&&$(this).attr('onclick').length>0){
		    		let confirm=$(this).attr('onclick');
		    		if(confirm.indexOf("confirm")){
				  		confirm=confirm.replace("return confirm('"," ");
				  		confirm=confirm.replace("');"," ");
				  		$(this).attr('ajax-question',confirm);
				  		$(this).attr('onclick','');		    	
				  		}
		    		}		    			    			    
		    	$(this).addClass('initialized').on('click',function(e){
		    		let el=this;	
		    		let datahref;		    		 
		    		$('.pending').removeClass('pending');
		        $(el).addClass('pending');   
		        if(typeof($(el).attr('ajax-question'))!='undefined'&&$(el).attr('ajax-question').length>0){
		        	let confirmation=confirm($(el).attr('ajax-question'));
		        	if(confirmation==false){
		        		return false;
		        		}
		        	}		        		
		    		if(typeof($(el).attr('href'))!='undefined'&&$(el).attr('href').length>0){
		    			datahref=$(el).attr('href');		    			
		    		}else{
		    			datahref='';
		    			return true; //neni odkaz, predame zpet prohlizeci k pokracovani
		    		}		    		
		    		datahref=datahref+"&isAjax=1";		    		
		    		datahref=datahref.replace("/&","/?");   				    		
		    		doAjax(datahref,'GET','');		    		
		    		return false;
		    		});
		    	});
		  	}
			let $isAjaxF=$('.isAjaxForm:not(.initialized)');
			if(typeof($isAjaxF)!='undefined'&&$isAjaxF.length){
		    $.each($isAjaxF,function(e){
		    	if(typeof($(this).attr('onsubmit'))!='undefined'&&$(this).attr('onsubmit').length>0){
		    		let confirm=$(this).attr('onsubmit');
		    		if(confirm.indexOf("confirm")){
				  		confirm=confirm.replace("return confirm('"," ");
				  		confirm=confirm.replace("');"," ");
				  		$(this).attr('ajax-question',confirm);
				  		$(this).attr('onsubmit','');		    	
				  		}
		    		}		
		    	$(this).addClass('initialized').on('submit',function(e){
		    		let el=this;	
		    		let datahref;		    		 
		    		$('.pending').removeClass('pending');
		        $(el).addClass('pending');   
		        if(typeof($(el).attr('ajax-question'))!='undefined'&&$(el).attr('ajax-question').length>0){
		        	let confirmation=confirm($(el).attr('ajax-question'));
		        	if(confirmation==false){
		        		return false;
		        		}
		        	}		
		        if(typeof($(el).attr('action'))!='undefined'&&$(el).attr('action').length>0){
		    			datahref=$(el).attr('action');		    			
		    		}else{
		    			datahref='';
		    			return true; //neni odkaz, predame zpet prohlizeci k pokracovani
		    		}	   
		    		datahref=datahref+"&isAjax=1";		    		
		    		datahref=datahref.replace("/&","/?");  		    		
		    		let dataForm=$(el).serialize();
		    		let methodForm='GET';
		    		if(typeof($(el).attr('method'))!='undefined'&&$(el).attr('method').length>0&&($(el).attr('method')=="POST"||$(el).attr('method')=="post")){
		    			methodForm='POST';
		    			}
		    		doAjax(datahref,methodForm,dataForm);
		    		return false;     		
		    		});
					});
		  	}
			}
		}
	}
function doAjax(url,method,data){
	$('body').addClass('ajaxWaiting');  
  	var xhr=$.ajax({
		  url:url,
		  method:method,
		  data:data
		}).done(function(data){
		  $('body').html(data);
		}).fail(function(jqXHR,textStatus){
		  console.log(jqXHR);
		  console.log(textStatus);
		}).always(function(data){
		  cancelPending();
		  init();		  
		});
  $(document).on("keyup",function(event){
    var keycode=(event.keyCode?event.keyCode:event.which);
    if(keycode=='27'||event.key==='Escape'||event.key==='Esc'){
      xhr.abort();
      cancelPending();            
    	}
  	});
	}
function cancelPending() {
  $('.pending').removeClass('pending');
 	$('body').removeClass('ajaxWaiting');
	}
