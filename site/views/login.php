<?php

use PVAssistance\AuthenticationManager;
use PVAssistance\Util;

if (AuthenticationManager::isAuthenticated()) {
    Util::redirect("index.php");
}
$userName = $_REQUEST[PVAssistance\Controller::USER_NAME] ?? null;

require_once('views/partials/header.php');

require_once('views/partials/errors.php');

?>


    <div class="page-header">
        <h2>Log In</h2>
    </div>

    <div class="panel panel-default">
        <div class="panel-heading">
            Please log in to process applications.
        </div>
        <div class="panel-body">

            <form class="form-horizontal" method="post" action="<?php echo Util::action(PVAssistance\Controller::ACTION_LOGIN, array('view' => $view)); ?>">
                <div class="form-group">
                    <label for="inputName" class="col-sm-2 control-label">Username:</label>
                    <div class="col-sm-6">
                        <input type="text" class="form-control" id="inputName" name="<?php print PVAssistance\Controller::USER_NAME; ?>" placeholder="try 'admin'" value="<?php echo htmlentities($userName ?? ''); ?>">
                    </div>
                </div>
                <div class="form-group">
                    <label for="inputPassword" class="col-sm-2 control-label">Password</label>
                    <div class="col-sm-6">
                        <input type="password" class="form-control" id="inputPassword" name="<?php print PVAssistance\Controller::USER_PASSWORD; ?>" placeholder="try 'admin'">
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-sm-offset-2 col-sm-6">
                        <button type="submit" class="btn btn-default">Login</button>
                    </div>
                </div>
            </form>

        </div>
    </div>

<?php
require_once('views/partials/footer.php');