'use strict'
var sideBarIsHide = false;
var ManuelSideBarIsHide = false;
var ManuelSideBarIsState = false;

var scrollerContent = null;

$(".openbtn").on("click", function() {
    ManuelSideBarIsHide = true;
    if (!ManuelSideBarIsState) {
        resizeSidebar("1");
        ManuelSideBarIsState = true;
    } else {
        resizeSidebar("0");
        ManuelSideBarIsState = false;
    }
});


$(window).resize(function() {
    if (ManuelSideBarIsHide == false) {
        if ($(window).width() <= 767) {
            if (!sideBarIsHide); {
                resizeSidebar("1");
                sideBarIsHide = true;
                $(".colhidden").addClass("displaynone");

            }
        } else {
            if (sideBarIsHide); {
                resizeSidebar("0");
                sideBarIsHide = false;

                $(".colhidden").removeClass("displaynone");

            }
        }
    }

  resizeScreen();

});

var isMobile = window.matchMedia("only screen and (max-width: 768px)");

if (isMobile.matches) {
    resizeSidebar("1");

    $(".computer.only").toggleClass("displaynone");
    $(".colhidden").toggleClass("displaynone");

} else {

  //var $ = document.querySelector.bind(document);
  var ps1 = new PerfectScrollbar('.sidebar');
  var ps2 = new PerfectScrollbar('.displaynone .menu');

}

function resizeSidebar(op) {

    if (op == "1") {

        $(".ui.sidebar.left").addClass("very thin icon");
        $(".navslide").addClass("marginlefting");
        $(".sidebar.left span").addClass("displaynone");
        $(".sidebar .accordion").addClass("displaynone");
        $(".ui.dropdown.item.displaynone").addClass("displayblock");
        $($(".logo img")[0]).addClass("displaynone");
        $($(".logo img")[1]).removeClass("displaynone");
        $(".hiddenCollapse").addClass("displaynone");


    } else {

        $(".ui.sidebar.left").removeClass("very thin icon");
        $(".navslide").removeClass("marginlefting");
        $(".sidebar.left span").removeClass("displaynone");
        $(".sidebar .accordion").removeClass("displaynone");
        $(".ui.dropdown.item.displaynone").removeClass("displayblock");
        $($(".logo img")[1]).addClass("displaynone");
        $($(".logo img")[0]).removeClass("displaynone");
        $(".hiddenCollapse").removeClass("displaynone");


    }

}



/*
// using context
$('.ui.right.sidebar')
    .sidebar({
        context: $('#contextWrap .pusher'),
        transition: 'slide out',
        silent: true
    })
    .sidebar('attach events', '.rightsidebar');
*/


function toggleFullScreen(elem) {
    // ## The below if statement seems to work better ## if ((document.fullScreenElement && document.fullScreenElement !== null) || (document.msfullscreenElement && document.msfullscreenElement !== null) || (!document.mozFullScreen && !document.webkitIsFullScreen)) {
    if ((document.fullScreenElement !== undefined && document.fullScreenElement === null) || (document.msFullscreenElement !== undefined && document.msFullscreenElement === null) || (document.mozFullScreen !== undefined && !document.mozFullScreen) || (document.webkitIsFullScreen !== undefined && !document.webkitIsFullScreen)) {
        if (elem.requestFullScreen) {
            elem.requestFullScreen();
        } else if (elem.mozRequestFullScreen) {
            elem.mozRequestFullScreen();
        } else if (elem.webkitRequestFullScreen) {
            elem.webkitRequestFullScreen(Element.ALLOW_KEYBOARD_INPUT);
        } else if (elem.msRequestFullscreen) {
            elem.msRequestFullscreen();
        }
    } else {
        if (document.cancelFullScreen) {
            document.cancelFullScreen();
        } else if (document.mozCancelFullScreen) {
            document.mozCancelFullScreen();
        } else if (document.webkitCancelFullScreen) {
            document.webkitCancelFullScreen();
        } else if (document.msExitFullscreen) {
            document.msExitFullscreen();
        }
    }
}


$('.ui.accordion').accordion({
    selector: {}
});


/* BMG */

function startRouter()
{

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


  $('#app_logoff').click(function(e){
    e.preventDefault();


    if(LOGOFF_SVC!='')
    {

      showConfirm('Sistema','Se cerrará el sistema, ¿desea continuar?' , 'Aceptar',function(){

        window.location=BASE_WEB_ROOT + LOGOFF_SVC;

      });
    }

  })


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

		  		var html=$.parseHTML(data.html,document,true);

		    	_showLogin(html);

		  	}else{


		    	var html=$.parseHTML(data.html,document,true);

		    	$('#app_content_body').html(html);


		  		startPage();

          resizeScreen();

		    	unfreeze();


		  	}

		  })
			/*
		  .fail(function(){

		  	unfreeze();

		  })
		  */
		  .always(function(){
		  	unfreeze();
		  })
		  ;
}


function loadDialogUi(url,postData,focusable=true)
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
		  	  var html=$.parseHTML(data.html,document,true);
		    	_showLogin(data.html);

		  	}else{

		  	  $('.ui.dimmer.modals').html('');

		    	$('#app_global_dialog_container').html(data.html);

		    	$('#app_global_dialog_container .modal').modal({
		    		closable:false,
		    		centered: true,
		    		autofocus:focusable,
		    		duration:100,

		    		onHidden    : function(){
				      $('.ui.dimmer.modals').html('');
				    }


		    	}).modal('show');

		     	unfreeze();

		  	}

		  })

		  .always(function(){
		  	unfreeze();
		  });

}

function _showLogin(html)
{
  $('#app_global_dialog_container').html(html);

	$('#app_global_dialog_container .modal').modal({
		closable:false,
		allowMultiple:true,
		duration:100

	}).modal('show');

	loginStartPage();
	unfreeze();
}

function isFunction(functionToCheck) {
  return functionToCheck && {}.toString.call(functionToCheck) === '[object Function]';
}


function callSvc(url,postData,callback,async=true,showLoader=true)
{
  $.ajax({
			  url:url,
			  dataType :'json',
			  method:'POST',
				async:async,
			  data:postData,
			  beforeSend: function( xhr ) {
			    if(showLoader)
			    {
			      freeze();
			    }
			  }
			})
			.done(function( data ) {

		  	if(data.status==STATUS_LOGOUT)
		  	{
		  		var html=$.parseHTML(data.html,document,true);
		    	_showLogin(data.html);

		  	}else{

					if(showLoader)
					{
					  unfreeze();
					}

          if(isFunction(callback)){
            callback(data);
          }


		  	}

		  })

		  .always(function(){
		  	if(showLoader)
		  	{
		  	  unfreeze();
		  	}
		  })
		  ;
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
	  		var html=$.parseHTML(data.html,document,true);
		    _showLogin(data.html);

	  	}else{

				dataSourceOptions.success(response.data);
				unfreeze();

	  	}

	  })

	  .fail(function(response){

	  	dataSourceOptions.error(response.data);
	  	unfreeze();

	  })
	  .always(function(){

			unfreeze();

	  })
	  ;


}

function freeze()
{
	 $('.app__loader').show();
}

function unfreeze()
{
	$('.app__loader').hide();
}

function showConfirm(title, msg, buttonConfirm,callbackOk)
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


  var htmlDialog ='';
  htmlDialog +='<div class="ui tiny modal" id="app_message_alert">';
  htmlDialog +='<div class="header app_message_alert_title">' + title  +'</div>';
  htmlDialog +='<div class="content app_message_alert_content">' + msg + '</div>';

  htmlDialog +='<div class="actions">';
  
  if(callbackOk!==undefined)
  {
    htmlDialog +='<div class="ui cancel button app_message_close_button">Cancelar</div>';
  }
  
  htmlDialog +='<div class="ui approve primary button app_message_confirm_button">' + buttonConfirm  + '</div>';
  htmlDialog +='</div>';
  htmlDialog +='</div>';

  $('#app_global_dialog_alert').html(htmlDialog);

	$('#app_global_dialog_alert .modal')
    .modal({
      closable  : false,
      onDeny    : function(){

        return true;
      },
      onApprove : function() {

        if (typeof callbackOk !== 'undefined')
        {
          callbackOk();

        }

        return true;

      },
      duration:100
    })
    .modal('show')
  ;


}

function showAlert(title,msg,buttonConfirm,type,callbackOk)
{
  swal({
    title: title,
    text: msg,
    type: type,
    showCancelButton: true,
    confirmButtonColor: '#3085d6',
    cancelButtonColor: '#d33',
    confirmButtonText: buttonConfirm,
    allowOutsideClick:false,
    allowEnterKey:false
  }).then((result) => {
    if (result.value) {
      if (typeof callbackOk !== 'undefined')
      {
        callbackOk();

      }
    }
  })
}

function ajxUpd(el, ds,callback) {

  $(el).click(function (e) {
    e.preventDefault();
    var l = $(this).attr('href');
    var msg = $(this).attr('rel');

    if (msg !== '') {

    	showConfirm('Confirmar',msg,'Proceder',function(){
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
    	});


    } else {

      $.ajax({
        url: l,
        dataType: 'json',
        done: function (data) {
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
  /*
  var containerWidth = ($(window).width());
  var containerHeight = ($(window).height() - 90);

  $('#app__container_body').width(containerWidth);

  $('#app__container_body').height(containerHeight);
  $('#app__container_body').css('overflow','hidden');

  var gridElement =  document.getElementById('grid');

	if(gridElement === null) {

    if (scrollerContent) scrollerContent.destroy();

    scrollerContent = new PerfectScrollbar('#app__container_body');

	}else{


    //$('#app_content_body').height()


		var dataArea = gridElement.find(".k-grid-content");
		var newHeight = ($(window).height()-90);

		gridElement.height(newHeight);

		var diff = gridElement.innerHeight() - dataArea.innerHeight();
		//gridElement.height(newHeight);
		dataArea.height(newHeight - diff);

    if(scrollerContent)
    {
	    scrollerContent.destroy();
      scrollerContent=null;
    }


	}


	$('#app_content_toolbar').width($(window).width()-190);

  */

  var containerWidth = ($(window).width());
  var containerHeight = ($(window).height() - 90);

  $('#app__container_body').width(containerWidth);

  $('#app__container_body').height(containerHeight);
  $('#app__container_body').css('overflow','hidden');

  var gridElement = document.getElementById('grid');

  if(gridElement === null) {

    if (scrollerContent) scrollerContent.destroy();

    scrollerContent = new PerfectScrollbar('#app__container_body');

  }else{

    var gridElement = $("#grid");

    var dataArea = gridElement.find(".k-grid-content");
    var newHeight = ($(window).height()-90);

    gridElement.height(newHeight);

    var diff = gridElement.innerHeight() - dataArea.innerHeight();
    //gridElement.height(newHeight);
    dataArea.height(newHeight - diff);

    if(scrollerContent)
    {
      scrollerContent.destroy();
      scrollerContent=null;
    }

  }

  $('#app_content_toolbar').width($(window).width()-190);

}

function toast(action, tiempo,mensaje)
{
  var bgcolor;

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
    	  		var html=$.parseHTML(response.data.html,document,true);
		    	  _showLogin(response.data.html);

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
    }

    return this;
  };

})(jQuery);
