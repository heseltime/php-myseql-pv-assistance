<?php


$id = $_REQUEST['id'] ?? null;

require_once('views/partials/header.php');
?>

    <div class="page-header">
       <h2>Success!</h2>
    </div>

<div class="alert alert-success" role="alert">
  <h4>Thank you for your application: Please note the following important information.</h4>

  <?php if ($id != null) : ?>
    <p>Your application id is <b><?php echo PVAssistance\Util::escape($id); ?></b>, the same as your PV-system ID.</p>
  <?php endif; ?>

</div>

<?php require_once('views/partials/footer.php');
