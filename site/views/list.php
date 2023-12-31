<?php

use Data\DataManager;
use PVAssistance\Util;
use PVAssistance\AuthenticationManager;

if (!AuthenticationManager::isAuthenticated()) {
  Util::redirect('index.php?');
}

$applications = DataManager::getApplications();

require_once('views/partials/header.php');
?>

<div class="page-header">
    <h2>PV-Assistance Applications</h2>
</div>

<?php if (isset($applications)) : ?>
  <?php
  if (sizeof($applications) > 0) : 
    require('views/partials/applist.php');?>
  <?php else : ?>
    <div class="alert alert-info" role="alert">No unprocessed applicatios.</div>
  <?php endif; ?>
<?php endif; ?>

<?php require_once('views/partials/footer.php');
