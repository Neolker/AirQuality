document.addEventListener("DOMContentLoaded", function () {
  kernelInit();
});
function kernelInit() {
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
		  kernelInit();
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
