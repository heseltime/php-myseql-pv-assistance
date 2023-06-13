<?php

use Data\AcceptedStatus;
use Data\DataManager;
use PVAssistance\Util;

$errors = isset($_SESSION['errors']) ? $_SESSION['errors'] : [];
$form_data = isset($_SESSION['form_data']) ? $_SESSION['form_data'] : [];
$application = isset($_SESSION['application']) ? $_SESSION['application'] : [];

$uuid = isset($_GET['uuid']) ? $_GET['uuid'] : [];

unset($_SESSION['errors']); 
unset($_SESSION['form_data']); 
unset($_SESSION['application']);

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

<?php require_once('views/partials/footer.php'); ?>

