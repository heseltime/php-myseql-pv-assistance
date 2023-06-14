<!--display error messages-->

<?php

use PVAssistance\Util;

$errors = isset($_SESSION['errors']) ? $_SESSION['errors'] : [];
$form_data = isset($_SESSION['form_data']) ? $_SESSION['form_data'] : [];
unset($_SESSION['errors']); 
unset($_SESSION['form_data']); 

if (isset($errors) && is_array($errors) && !empty($errors)): ?>
    <div class="errors alert alert-danger">
      <ul>
        <?php foreach ($errors as $errMsg): ?>
          <li><?php echo(Util::escape($errMsg)); ?></li>
        <?php endforeach; ?>
      </ul>
    </div>
<?php endif; ?>