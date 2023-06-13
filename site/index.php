<?php

require_once('inc/bootstrap.php');

/* assigen the standard view value */
$view = $default_view;

/* if we have a view parameter and the view exists, set the view variable */
if (isset($_REQUEST['view']) &&
    file_exists(__DIR__ .'/views/' . $_REQUEST['view'] . '.php')) {
    
    /*
    eigentlich sollte hier noch gegen injection überprüft werden:
    /index.php?view=../inc/password
    */

	$view = $_REQUEST['view'];
}

/* if we have a form post, invoke the controller */
$postAction = $_REQUEST[PVAssistance\Controller::ACTION] ?? null;
if ($postAction != null) {
    PVAssistance\Controller::getInstance()->invokePostAction();
}

/**
 * no post action -  switch the views via GET parameter
 **/
// if (file_exists(__DIR__ .'/views/' . $view .'.php')) // not necessary if guaranteed, that $default_view file exists
require_once('views/' . $view . '.php');
