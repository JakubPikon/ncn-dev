<?php
error_reporting(E_ALL|E_STRICT);
date_default_timezone_set('Europe/Warsaw');
define('API_ROOT', __DIR__ );

register_shutdown_function( function(){
    $e = error_get_last();
    if( $e['type'] === E_ERROR || $e['type'] === E_USER_ERROR || $e['type'] === E_COMPILE_ERROR ) {
        error_log ("\n[" . date('Y-m-d H:i:s') . "] - " . $e['file'] . ', line: ' . $e['line'] . ', msg: ' . $e['message'], 3, API_ROOT . '/log/' . date('Ymd') . '-api-error.log');
        throw new Exception($e['message']);
    }
});

require 'config.php';
require 'util/Enums.php';
require 'util/TextResources.php';
require 'util/TextHelper.php';
require 'core/Abstract.php';
require 'core/Session.php';
require 'core/Model.php';
require 'core/Context.php';
require 'core/BaseDao.php';
require 'dto/TermTaxonomyDto.php';

global $table_prefix;
try {
	$context = new Context();
	$context->config = new Config( $table_prefix  );// init with global WP variable
	$model = $context->model;
	$requestVars = array();
	$requestVars['class'] = (!empty($_GET['c'])) ? $_GET['c'] . 'Controller' : NULL;
	$requestVars['method'] = (!empty($_GET['m'])) ? $_GET['m'] : NULL;

	$response = $context->callMethod($requestVars);

} catch (Exception $e) {
    error_log ("\n[" . date('Y-m-d H:i:s') . "] - " . $e->getFile() . ', line: ' . $e->getLine() . ', msg: ' . $e->getMessage(), 3, __DIR__ . '/../log/' . date('Ymd') . '-www-error.log');
    throw new Exception($e);
}