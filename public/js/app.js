const DB_TRUE = 1;
const DB_FALSE = 0;

const STATUS_OK = 2;
const STATUS_FAIL = 1;

var scrollerContent = null;

var activo = null;

function menuDinamico(){
    if(activo == 1){
        $('.ui.sidebar.vertical.left.menu').width('0px');
        // $('.ui.sidebar.vertical.left.menu').hide();
        $('#div_dad_container').css('margin-left','0px');
        $('#app_content_toolbar').width($(window).width());
        $('.itemmenu').hide();
        activo = 0;
    }else{
        $('.ui.sidebar.vertical.left.menu').width('190px');
        // $('.ui.sidebar.vertical.left.menu').show();
        $('#div_dad_container').css('margin-left','192px');
        setTimeout(() =>{$('.itemmenu').show(); }, 280);
        $('#app_content_toolbar').width($(window).width()-192);
        activo = 1;
    }
}

function dataSourceBinding(dataSourceOptions, url) {
    $.ajax({
        url: url,
        dataType: 'json',
        method: 'POST',
        data: dataSourceOptions.data,
        beforeSend: function (xhr) {
            freeze();
        }
    })
        .done(function (response) {
            dataSourceOptions.success(response.data);
            unfreeze();
        })
        .fail(function (response) {
            dataSourceOptions.error(response.data);
            unfreeze();
        })
        .always(function () {
            unfreeze();
        });
}



function resizeScreen()
{
    var gridElement = document.getElementById('grid');
    menuDinamico();
    if(gridElement === null) {

        if (scrollerContent) scrollerContent.destroy();
        scrollerContent = new PerfectScrollbar('#app__container_body');

    }else{

        var gridElement = $("#grid");

        var dataArea = gridElement.find(".k-grid-content");
        var newHeight = ($(window).height()-95);

        gridElement.height(newHeight);

        var diff = gridElement.innerHeight() - dataArea.innerHeight();
        dataArea.height(newHeight - diff);

        if(scrollerContent)
        {
            scrollerContent.destroy();
            scrollerContent=null;
        }

    }

    $('#app_content_toolbar').width($(window).width()-190);

}

function freeze() {
    $('.app__loader').show();
}

function unfreeze() {
    $('.app__loader').hide();
}

function toast(action, tiempo, mensaje) {
    var bgcolor;

    if (action == 'error') {
        bgcolor = '#db2828';

    } else if (action == 'success') {

        bgcolor = '#66BB6A';
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