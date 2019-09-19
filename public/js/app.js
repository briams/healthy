const DB_TRUE = 1;
const DB_FALSE = 0;

// var STATUS_LOGOUT     ='98';

const STATUS_OK = 2;
const STATUS_FAIL = 1;

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

function freeze() {
    $('.app__loader').show();
}

function unfreeze() {
    $('.app__loader').hide();
}