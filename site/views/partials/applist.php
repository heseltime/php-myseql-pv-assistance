<?php 

use PVAssistance\Util; 
use Data\DataManager; 

$page = isset($_GET['page']) ? $_GET['page'] : 1;
if ($page < 1) $page = 1;

$applicationCount = DataManager::getApplicationCount();
$resultsPerPage = 10;  
$pageFirstResult = ($page-1) * $resultsPerPage;  

// pages links
$numberOfPages = ceil($applicationCount / $resultsPerPage); 

// reset applications to only the specified page
$applications = DataManager::getApplicationsDelimited($pageFirstResult, $resultsPerPage);

?>

<table class="table">
  <thead>
  <tr>
    <th>
      Status
    </th>
    <th>
      Request Date
    </th>
    <th>
      Applicant
    </th>
    <th>
      
    </th>
  </tr>
  </thead>
  <tbody>
  <?php
  foreach ($applications as $app):

    //$inCart = ShoppingCart::contains($book->getId());
    ?>
    <tr>

      <td>
        <strong>
            <?php echo Util::escape($app->getStatus()); ?>
        </strong>
      </td>
      <td>
        <?php echo $app->getRequestDate()->format('d.m.Y H:i:s'); ?>
      </td>
      <td>
      <?php echo $app->getUser()->getUserName(); ?>
      </td>
      <td class="add-remove">
	      <?php if (Util::escape($app->getStatus()) == "In Progress"): ?>
            <form method="post" action="<?php echo Util::action(PVAssistance\Controller::CHECK_STATUS); ?>">
              <input type="hidden" class="form-control" name="token" value="<?php echo htmlspecialchars($app->getToken()); ?>">
              <input type="hidden" class="form-control" name="uuid" value="<?php echo htmlspecialchars($app->getUUID()); ?>">
              <button type="submit" role="button" class="btn btn-default btn-xs btn-info">
                <span class="glyphicon glyphicon-pencil"></span>
              </button>
            </form>
	      <?php else: ?>
            <form method="post" action="<?php echo Util::action(PVAssistance\Controller::CHECK_STATUS); ?>">
              <input type="hidden" class="form-control" name="token" value="<?php echo htmlspecialchars($app->getToken()); ?>">
              <input type="hidden" class="form-control" name="uuid" value="<?php echo htmlspecialchars($app->getUUID()); ?>">
              <button type="submit" role="button" class="btn btn-default btn-xs btn-success">
                <span class="glyphicon glyphicon-wrench"></span>
              </button>
            </form>
	      <?php endif; ?>
      </td>

    </tr>

  <?php endforeach; ?>

  </tbody>
  
  <p style="color: #fff; font-size: 1.5em; padding: 10px;">Go to page: 
  <?php for($page = 1; $page <= $numberOfPages; $page++) {  
        echo '<b><a href="index.php?view=list&page=' . $page . '" style="padding: 5px;">' . $page . '</a></b>';  
  }  ?></p>
</table>
