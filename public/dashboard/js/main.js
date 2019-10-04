var DIALOG_DEFAULT = "global-dialog";
var DIALOG_LARGE = "large-global-dialog";
var DIALOG_SMALL = "small-global-dialog";
var DIALOG_XLARGE = "xlarge-global-dialog";

function startRouter()
{
  
  $('.app_nav__module_item').click(function(e){
    
    $('.app_nav__module_item').removeClass('active');
    
    $(this).addClass('active');
    
  });
  
	
	var AppRouter = Backbone.Router.extend({
			routes: {
      "*actions": "defaultRoute" // matches http://example.com/#anything-here
      }
    });
    
  // Initiate the router
  
  var app_router = new AppRouter();

  app_router.on('route:defaultRoute', function(actions) {
  	
  	
  	if(actions===null)
  	{
  		actions='dashboard';
  	}
  	
  	
  	loadPage(actions);
  
  })

  // Start Backbone history a necessary step for bookmarkable URL's
  Backbone.history.start();
  
  $( "#aui-confirm-dialog-close-button").click(function(e){
		e.preventDefault();
		AJS.dialog2("#confirm-dialog").hide();
	});
    
	//unfreeze();
}


function loadPage(actions)
{
	
  $.ajax({
			  url: ROOTPAGE + actions,
			  dataType :'json',
			  method:'POST',
			  beforeSend: function( xhr ) {
			  	$('#app__content-data').empty();
			    freeze();
			  }
			})
			
			.done(function( data ) {

		  	if(data.status==STATUS_LOGOUT)
		  	{
		  		unfreeze();
		  		html=$.parseHTML(data.html,document,true);
		    	$('#login-dialog').html(html);
		    	AJS.dialog2("#login-dialog").show();
		    	loginStartPage();
		    	
		  	}else{
		  	
		  	
		    	html=$.parseHTML(data.html,document,true);
		    	$('#app__content-data').html(html);
		      //componentHandler.upgradeAllRegistered();
		      
		      resizeScreen();
		      
		  		startPage();

		    	unfreeze();
		    	
		    	
		  	}
		  	
		  })
		  
		  .error(function(){
		  	
		  	unfreeze();
		  	
		  	//$('#content').html('<h1>Error cargando /' + actions + '</h1>');
		  	
		  });
}


function loadDialog(type,url,postData)
{
	// Type: large, 
	/*
	$('#large-global-dialog').load('/productos/svc/ajx-ficha',{pro_id:proid});
				// 	AJS.dialog2("#large-global-dialog").show();
        });
*/
	
	$.ajax({
			  url:url,
			  dataType :'json',
			  method:'POST',
			  data:postData,
			  beforeSend: function( xhr ) {
			    freeze();
			  }
			})
			.done(function( data ) {

		  	if(data.status==STATUS_LOGOUT)
		  	{
		  		//html=$.parseHTML(data.html,document,true);
		    	$('#login-dialog').html(data.html);
		    	AJS.dialog2("#login-dialog").show();
		    	loginStartPage();
					unfreeze();
		    	
		  	}else{

		    	html=$.parseHTML(data.html,document,true);
		    	$('#' + type).html(html);
		    	AJS.dialog2('#' + type).show();
		     	unfreeze();
		    		
		  	}
		  	
		  })
		  
		  .error(function(){
		  	
		  	unfreeze();
		  	
		  });

}

function loadDialogUi(url,postData)
{

	$.ajax({
			  url:url,
			  dataType :'json',
			  method:'POST',
			  data:postData,
			  beforeSend: function( xhr ) {
			    freeze();
			  }
			})
			.done(function( data ) {

		  	if(data.status==STATUS_LOGOUT)
		  	{
		    	$('#login-dialog').html(data.html);
		    	AJS.dialog2("#login-dialog").show();
		    	loginStartPage();
					unfreeze();
		    	
		  	}else{

		    	$('.ui.dimmer.modals').html('');
		    	
		    	$('#app_global_dialog_container').html(data.html);
		    	
		    	$('#app_global_dialog_container .modal').modal({
		    		closable:false,
		    		allowMultiple:true,
		    		duration:100,
		    		
		    		onHidden    : function(){
				      $('.ui.dimmer.modals').html('');
				    }
		    		
		    		
		    	}).modal('show');
		    	
		     	unfreeze();
		    		
		  	}
		  	
		  })
		  
		  .error(function(){
		  	
		  	unfreeze();
		  	
		  });

}




function callSvc(url,postData,callback)
{
  $.ajax({
			  url:url,
			  dataType :'json',
			  method:'POST',
				async:true,
			  data:postData,
			  beforeSend: function( xhr ) {
			    freeze();
			  }
			})
			.done(function( data ) {

		  	if(data.status==STATUS_LOGOUT)
		  	{
		  		html=$.parseHTML(data.html,document,true);
		    	$('#login-dialog').html(html);
		    	AJS.dialog2("#login-dialog").show();
		    	loginStartPage();
					unfreeze();
		    	
		  	}else{
					
					unfreeze();
					callback(data);
		    	//return data.status;
		     			    		
		  	}
		  	
		  })
		  
		  .error(function(){
		  	
		  	unfreeze();
		  	
		  });
}


function dataSourceBinding(dataSourceOptions, url)
{
  
  $.ajax({
		  url:url,
		  dataType :'json',
		  method:'POST',
		  data:dataSourceOptions.data,
		  beforeSend: function( xhr ) {
		    freeze();
		  }
		})
		.done(function( response ) {

	  	if(response.status==STATUS_LOGOUT)
	  	{
	  		html=$.parseHTML(response.html,document,true);
	    	$('#login-dialog').html(html);
	    	AJS.dialog2("#login-dialog").show();
	    	loginStartPage();
				unfreeze();
	    	
	  	}else{
				
				dataSourceOptions.success(response.data);
				unfreeze();
				
	  	}
	  	
	  })
		  
	  .error(function(response){
	  	
	  	dataSourceOptions.error(response.data);
	  	unfreeze();
	  	
	  });
  
}

function freeze()
{
	 $('.app__loader').show();
}

function unfreeze()
{
	$('.app__loader').hide();
}

function showConfirm(title, msg, buttonConfirm)
{
	//$( "#aui-confirm-dialog-close-button").unbind( "click" );
	
	
	if(title===undefined)
	{
		title='';
	}
	
	if(buttonConfirm===undefined)
	{
		buttonConfirm='Aceptar';
	}
	
	$('#aui-confirm-dialog-content').html(msg);
	$('#aui-confirm-dialog-title').html(title);
	$('#aui-confirm-dialog-accept-button').html(buttonConfirm);
	
	AJS.dialog2("#confirm-dialog").show();
	
	
	
}

function ajxUpd(el, ds,callback) {
	
  $(el).click(function (e) {
    e.preventDefault();
    var l = $(this).attr('href');
    var msg = $(this).attr('rel');

    if (msg !== '') {
    	
    	showConfirm('Confirmar',msg,'Proceder');
    	
    	$( "#aui-confirm-dialog-accept-button").unbind( "click" );
	
			$( "#aui-confirm-dialog-accept-button").click(function(e){
				e.preventDefault();
				
				$.ajax({
            url: l,
            dataType: 'json',
            success: function (data) {
              
              if(ds!='')
              {
                ds.read();
              }
              
              
              if (typeof callback !== 'undefined') 
              {
                callback(data);
              }
                            
              
              
            }
          });
          
          AJS.dialog2("#confirm-dialog").hide();

			})
    
    } else {

      $.ajax({
        url: l,
        dataType: 'json',
        success: function (data) {
          if (data.status == 2) {
						ds.read();
          } else {
            ds.read();
          }
        }
      });
    }


  })
}



function resizeScreen()
{
	if($("#grid").length === 0) {
		//$('#content').css('padding','10px 14px');
		//$('.fixed-ribbon #content').css('padding-top','40px');
		
		$('body').css('overflow','scroll');
		
	}else{
		
	
		
		//$('#content').css('padding','10px 0');
		//$('.fixed-ribbon #content').css('padding-top','40px');
		
		var gridElement = $("#grid");
		
		if(gridElement!=null)
		{
			var dataArea = gridElement.find(".k-grid-content");
			var newHeight = ($(window).height()-90);
		
			gridElement.height(newHeight);
		
			var diff = gridElement.innerHeight() - dataArea.innerHeight();
			//gridElement.height(newHeight);
			dataArea.height(newHeight - diff);

      //$('body').getNiceScroll().remove();
      $('body').css('overflow','hidden');
      
      
		}
		
	}
	
	//$('#app__content-data').width(($(window).width()-190))
	$('.bmg__toolbar-row').width(($(window).width()-240))
	$('#app-nav__main-menu').height(($(window).height()-50))
	
}

function toast(action, tiempo,mensaje)
{
  
  if(action=='error')
  {
    bgcolor='#db2828';  
    
  }else if(action=='success'){
    
    bgcolor='#66BB6A';  
  }
  
  
  $.toast({
    text: mensaje, // Text that is to be shown in the toast
    
    showHideTransition: 'fade', // fade, slide or plain
    allowToastClose: false, // Boolean value true or false
    hideAfter: tiempo, // false to make it sticky or number representing the miliseconds as time after which toast needs to be hidden
    stack: false, // false if there should be only one toast at a time or a number representing the maximum number of toasts to be shown at a time
    position: 'top-center', // bottom-left or bottom-right or bottom-center or top-left or top-right or top-center or mid-center or an object representing the left, right, top, bottom values
    textAlign: 'center',  // Text alignment i.e. left, right or center
    loader: false,
    bgColor: bgcolor

  });
}


;(function ($) {
  var defaults = { };

  $.fn.bmgForm = function (options) {

    this.each(function (el) {
      el = $(this);
      setOptions(el);
      $(el).ajaxForm({
        dataType: 'json',
        success: function (response) {
          
          if(response.status==STATUS_LOGOUT)
    	  	{
    	  		html=$.parseHTML(response.html,document,true);
    	    	$('#login-dialog').html(html);
    	    	AJS.dialog2("#login-dialog").show();
    	    	loginStartPage();
    				unfreeze();
    	    	
    	  	}else{
            el.trigger('success',response)
    				
    	  	}
    	  	
    	  	unfreeze();

        },
        
        error:function(){
          unfreeze();
        },

        beforeSubmit: function () {
          
          freeze();
          el.trigger('beforesend');
        }

      });

    });

    function setOptions(el) {
      $.each(options, function (event, fn) {
        if (typeof(fn) == 'function') {
          el.unbind(event);
          el.bind(event, fn);
        }
      });
      options = $.extend({}, defaults, el.data('bmgForm:options'), options);
      el.data('bmgForm:options', options);
    };

    return this;
  };
})(jQuery);
