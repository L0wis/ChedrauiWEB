<?php

// Sesión para panel de personal
session_name('personal_session');
session_start();

// Configuracion del sistema
define("PEARSON_URL", "http://localhost/chedraui/personal/");

// Configuracion del sistema    
defined('CLIENT_ID') || define('CLIENT_ID', 'AfbKCkRuw0YL3NxqLfSYK-MlAp9YPE0NneWmsRyJbxjW6IzQOaKO5Tl-PHHStv0M6o0QtqknQv55dfAB');
defined('KEY_TOKEN') || define('KEY_TOKEN', 'APR.wqc-354*');

// Configuracion paypal
defined('CURRENCY') || define('CURRENCY', 'MXN');
defined('MONEDA') || define('MONEDA', '$');