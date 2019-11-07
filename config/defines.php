<?php
//CONSTANTES LOGICAS
define('DB_TRUE',1);
define('DB_FALSE',0);

//RESPUESTAS DE SERVICES
define('STATUS_OK',2);
define('STATUS_FAIL',1);

//CONTANTES DE ESTADO DE REGISTROS
define('ST_ELIMINADO',-1);
define('ST_NUEVO',1);
define('ST_ACTIVO',2);
define('ST_INACTIVO',3);

//CONTANTES DE TIPO DE USUARIO
define('TIPO_PERSONAL',1);
define('TIPO_CLIENTE',2);
define('TIPO_SISTEMA',3);

define('TIPO_USUARIO',[
    1 => 'Tipo Personal',
    2 => 'Tipo Cliente',
    3 => 'Tipo Sistema',
]);


//CONSTANTES DE DATE FORMATO DE MYSQL
define('MYSQL_TIME_FORMAT', 'H:i:s');
define('MYSQL_DATE_FORMAT', 'Y-m-d');
define('MYSQL_DATETIME_FORMAT', 'Y-m-d H:i:s');

//CONSTANTES DE DATE FORMATO DE UI
define('UI_TIME_FORMAT', 'H:i');
define('UI_DATE_FORMAT', 'd/m/Y');
define('UI_DATETIME_FORMAT', 'd/m/Y H:i');

define('ESTADO_CITA',[
    -1 => 'Eliminada',
    1 => 'Nueva',
    2 => 'Confirmada',
    3 => 'Cancelada',
    4 => 'Tomada',
]);


define('BASE_WEB_ROOT','localhost/laravel/healthy/public/');
