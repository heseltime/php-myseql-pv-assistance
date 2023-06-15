<?php

use Data\AcceptedStatus;
use Data\DataManager;

use PVAssistance\AuthenticationManager;
use PVAssistance\Util;

$application = isset($_SESSION['application']) ? $_SESSION['application'] : [];

$uuid = isset($_GET['uuid']) ? $_GET['uuid'] : [];

unset($_SESSION['application']);

$adminMode = false;

if (AuthenticationManager::isAuthenticated()) {
    $adminMode = true;
}

require_once('views/partials/header.php');

require_once('views/partials/errors.php');

?>

<?php if(empty($application)) : ?>

<div class="page-header">
    <h2>Check the Status of Your Application </h2>
    <?php if(empty($uuid)) : ?>
        <div class="errors alert alert-warning"><b>No UUID provided! Please make sure to follow the link given to you.</b></div>
    <?php else : ?>
        <div class="alert alert-info">UUID provided: <?php echo $uuid; ?></div>
    <?php endif; ?>
</div>

<div class="panel panel-default">
    <div class="panel-heading">
        Token-Access-Control
    </div>
    <div class="panel-body">

    <div class="panel-body">

        <form class="form-horizontal" action="<?php echo Util::action(PVAssistance\Controller::CHECK_STATUS); ?>" method="POST">
            <div class="form-group mr-2">
                <input type="text" class="form-control" name="token" placeholder="Your Token" value="<?php echo isset($form_data['token']) ? htmlspecialchars($form_data['token']) : ''; ?>">
                <input type="hidden" class="form-control" name="uuid" value="<?php echo empty($uuid) ? '' : htmlspecialchars($uuid); ?>">
            </div>
            <button type="submit" class="btn btn-primary">Get Status Now</button>
        </form>

    </div>
</div>

<?php else : ?>

    <?php if($adminMode) : ?>
        <div class="alert alert-info" role="alert"><b>Admin Mode:</b> You are viewing a particular application with accept/reject privileges.</div>

        <div class="panel panel-default">
            <div class="panel-heading">
                Application Status: this is how the application notification will look like to the applicant. See below the line for <b>processing options</b>.
            </div>
            <div class="panel-body">

            <div class="panel-body">

                <?php if($application->getStatus() == "In Progress") : ?>
                    <img class="card-img-top" src="../assets/img/solar-panel-dark-fill.png" width="32" alt="PV Logo">
                <?php else: ?>
                    <img class="card-img-top" src="../assets/img/solar-panel-purple-orange.png" width="32" alt="PV Logo">
                <?php endif; ?>

                
                <h3>Thank you for applying! See application details and decision below.</h3>
                <h4>Address: <b><?php echo $application->getAddress()?></b></h4>
                <h4>Construction Date: <b><?php echo $application->getConstructionDate()->format('d.m.Y')?></b></h4>
                <strong>Application Result: </strong>
                <span class="badge"><h4><?php echo $application->getStatus()?></h4></span>
                <?php if($application->getStatus() == "Rejected") : ?>
                    <p><strong>Further Notes: </strong><?php echo $application->getNotes()?></p>
                <?php endif; ?>

                <hr/>

                <h4><b>Process</b> this application now <span class="glyphicon glyphicon-wrench"></span></h4>

                <form method="POST" action="<?php echo Util::action(PVAssistance\Controller::PROCESS_APPLICATION); ?>">
                    <div class="form-group">
                    <label for="status">Application Status</label>
                        <select class="form-control" name="status">
                            <option value="In Progress" <?php echo $application->getStatus() === 'In Progress' ? 'selected' : ''; ?>>In Progress</option>
                            <option value="Approved" <?php echo $application->getStatus() === 'Approved' ? 'selected' : ''; ?>>Approved</option>
                            <option value="Rejected" <?php echo $application->getStatus() === 'Rejected' ? 'selected' : ''; ?>>Rejected</option>
                        </select>
                            <label for="notes">Notes</label>
                            <input type="text" class="form-control" name="notes" value="<?php echo $application->getNotes(); ?>" placeholder="Further notes to applicant" optional>
                    </div>
                    <input type="hidden" class="form-control" name="uuid" value="<?php echo empty($application->getUUID()) ? '' : htmlspecialchars($application->getUUID()); ?>">
                    <input type="hidden" class="form-control" name="token" value="<?php echo empty($application->getToken()) ? '' : htmlspecialchars($application->getToken()); ?>">
                    <button type="submit" class="btn btn-primary" name="id" value=<?php echo $application->getID(); ?>>Submit Result</button>
                </form>

                <hr/>

                <h4>More <b>Details</b> about this application:</h4>

                <p><strong>ID:</strong> <?php echo $application->getID(); ?></p>
                <p><strong>Output: </strong> <?php echo $application->getOutputInKWP(); ?> kWp</p>
                <p><strong>Adress:</strong> <?php echo $application->getAddress(); ?></p>
                <p><strong>Construction Date:</strong> <?php echo $application->getConstructionDate()->format('d.m.Y'); ?></p>
                <p><strong>Application Date:</strong> <?php echo $application->getRequestDate()->format('d.m.Y H:i:s');; ?></p>
                <p><strong>IP Address under which application was made:</strong> <?php echo $application->getIPAddress(); ?></p>
                <p><strong>Generated UUID:</strong> <?php echo $application->getUUID(); ?></p>
                <p><strong>Generated Token:</strong> <?php echo $application->getToken(); ?></p>
                <p>Token submitted to applicant with decision email in separate step.</p>

            </div>
        </div>

    <?php else : ?>

        <div class="panel panel-default">
            <div class="panel-heading">
                Your Application Status
            </div>
            <div class="panel-body">

            <div class="panel-body">

                <?php if($application->getStatus() == "In Progress") : ?>
                    <img class="card-img-top" src="../assets/img/solar-panel-dark-fill.png" width="32" alt="PV Logo">
                <?php else: ?>
                    <img class="card-img-top" src="../assets/img/solar-panel-purple-orange.png" width="32" alt="PV Logo">
                <?php endif; ?>

                
                <h3>Thank you for applying! See application details and decision below.</h3>
                <h4>Address: <b><?php echo $application->getAddress()?></b></h4>
                <h4>Construction Date: <b><?php echo $application->getConstructionDate()->format('d.m.Y')?></b></h4>
                <strong>Application Result: </strong>
                <span class="badge"><h4><?php echo $application->getStatus()?></h4></span>
                <?php if($application->getStatus() == "Rejected") : ?>
                    <p><strong>Further Notes: </strong><?php echo $application->getNotes()?></p>
                <?php endif; ?>

            </div>
        </div>

    <?php endif; ?>

<?php endif; ?>

<?php require_once('views/partials/footer.php'); ?>

