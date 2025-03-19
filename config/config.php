<?php

ob_start();  // Agrega esta línea al inicio

// Sesión para Tienda
session_name('tienda_session');
session_start();

$path = dirname(__FILE__);

require_once $path . '/database.php';
require_once $path . '/../admin/clases/cifrado.php';

$db = new Database();
$con = $db->conectar();

$sql = "SELECT nombre, valor FROM configuracion";
$resultado = $con->query($sql);
$datosConfig = $resultado->fetchAll(PDO::FETCH_ASSOC);

$config = [];

foreach ($datosConfig as $datoConfig) {
    $config[$datoConfig['nombre']]=$datoConfig['valor'];
}

// Configuracion del sistema    
defined('SITE_URL') || define('SITE_URL', 'http://localhost/chedraui');
defined('CLIENT_ID') || define('CLIENT_ID', 'AfbKCkRuw0YL3NxqLfSYK-MlAp9YPE0NneWmsRyJbxjW6IzQOaKO5Tl-PHHStv0M6o0QtqknQv55dfAB');
defined('KEY_TOKEN') || define('KEY_TOKEN', 'APR.wqc-354*');

// Configuracion paypal
defined('CURRENCY') || define('CURRENCY', 'MXN');
defined('MONEDA') || define('MONEDA', '$');

// Datos para el envio de correo electronico.
defined('MAIL_HOST') || define('MAIL_HOST', $config['correo_smtp']);
defined('MAIL_USER') || define('MAIL_USER', $config['correo_email']);
defined('MAIL_PASS') || define('MAIL_PASS', descifrar($config['correo_password']));
defined('MAIL_PORT') || define('MAIL_PORT', $config['correo_puerto']);



if (!isset($_SESSION)) {
    session_start();
}

$num_cart = 0;
if (isset($_SESSION['carrito']['productos'])) {
    $num_cart = count($_SESSION['carrito']['productos']);
}
