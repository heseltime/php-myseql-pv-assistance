<?php
declare(strict_types=1);

// siehe auch werte in Core-section von phpinfo()
error_reporting(E_ALL);
ini_set('display_errors', 'On');
// setlocale(LC_MONETARY, 'de_AT');

/**
 * very simple class autoloader
 */
spl_autoload_register(function ($class) {
    $filename = __DIR__ . '/../lib/' . str_replace('\\', DIRECTORY_SEPARATOR, $class) . '.php';	// konsequenterweise sollte der DIRECTORY_SEPARATOR auch in den restlichen pfaden verwendung finden
    if (file_exists($filename)) {
        include($filename);
    }
});

// create session
PVAssistance\SessionContext::create();

// set default view
$default_view = 'welcome';

/**
 * 
 * DataManager 
 * change to switch between different implementations … 'mock' | 'pdo'
 */
$mode = 'pdo';
switch (mb_strtolower($mode)) {
	case 'mysqli':
		$class = 'mysqli';
		break;
	case 'pdo':
		$class = 'mysqlpdo';
		break;
	default:
		$class = 'mock';
		break;
}

/* ab php 8 möglich
$class = match (mb_strtolower($mode)) {
	'mysqli' => 'mysqli',
	'pdo' => 'mysqlpdo',
	default => 'mock'
}
*/

require_once(__DIR__ . '/../lib/Data/DataManager_' . $class . '.php');
