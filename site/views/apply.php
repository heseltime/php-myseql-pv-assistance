<?php require_once('views/partials/header.php'); ?>

<?php

use PVAssistance\Util;

$errors = isset($_SESSION['errors']) ? $_SESSION['errors'] : [];
$form_data = isset($_SESSION['form_data']) ? $_SESSION['form_data'] : [];
unset($_SESSION['errors']); // only display once
unset($_SESSION['form_data']); 

?>

<?php require_once('views/partials/errors.php'); ?>

<div class="page-header">
    <h2>Application</h2>
</div>

<div class="panel panel-default">
    <div class="panel-heading">
        Please use this form to fill in your application information
    </div>
    <div class="panel-body">
        <form class="form-horizontal" action="<?php echo Util::action(PVAssistance\Controller::APPLY); ?>" method="POST">
            <div class="form-group">
                <label for="id" class="col-sm-2 control-label">ID-Number:</label>
                <div class="col-sm-4">
                    <!-- add required -->
                    <input type="text" class="form-control" name="id" value="<?php echo isset($form_data['id']) ? htmlspecialchars($form_data['id']) : ''; ?>"><br>
                </div>
            </div>
            <div class="form-group">
                <label for="adresse" class="col-sm-2 control-label">Adress:</label>
                <div class="col-sm-4">
                    <!-- add required -->
                    <input type="text" class="form-control" name="address" value="<?php echo isset($form_data['address']) ? htmlspecialchars($form_data['address']) : ''; ?>"><br>
                </div>
            </div>
            <div class="form-group">
                <label for="leistung" class="col-sm-2 control-label">Output in kWP:</label>
                <div class="col-sm-2">
                    <!-- add required -->
                    <input type="number" class="form-control" name="kwP" step="0.05" min="0" value="<?php echo isset($form_data['kwP']) ? htmlspecialchars($form_data['kwP']) : ''; ?>"><br>
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-2 control-label" for="umsetzungsdatum">Intended Installation Date:</label>
                <div class="col-sm-2">
                    <!-- add required -->
                    <input type="date" class="form-control" name="constructionDate" value="<?php echo isset($form_data['constructionDate']) ? htmlspecialchars($form_data['constructionDate']) : ''; ?>"><br>
                </div>
            </div>
            <div class="form-group">
                <label for="type" class="col-sm-2 control-label">PV System Type:</label>
                <div class="col-sm-2">  
                    <!-- add required -->
                    <select name="pvType" class="form-control">
                    <option value="business" <?php echo isset($form_data['pvType']) && $form_data['pvType'] === 'business' ? 'selected' : ''; ?>>Business</option>
                    <option value="private" <?php echo isset($form_data['pvType']) && $form_data['pvType'] === 'private' ? 'selected' : ''; ?>>Private</option>
                    </select><br>
                </div>
            </div>
            <hr />
            <div class="form-group">
                <label for="sex" class="col-sm-2 control-label">Gender:</label>
                <div class="col-sm-2">
                    <!-- add required -->
                    <select name="sex" class="form-control" id="sex">
                    <option value="m" <?php echo isset($form_data['sex']) && $form_data['sex'] === 'm' ? 'selected' : ''; ?>>Male</option>
                    <option value="w" <?php echo isset($form_data['sex']) && $form_data['sex'] === 'w' ? 'selected' : ''; ?>>Female</option>
                    <option value="d" <?php echo isset($form_data['sex']) && $form_data['sex'] === 'd' ? 'selected' : ''; ?>>Diverse</option>
                    </select><br>
                </div>
            </div>
            <div class="form-group">
                <label for="vorname" class="col-sm-2 control-label">First Name:</label>
                <div class="col-sm-2">
                    <!-- add required -->
                    <input type="text" class="form-control" name="firstname" value="<?php echo isset($form_data['firstname']) ? htmlspecialchars($form_data['firstname']) : ''; ?>"><br>
                </div>
            </div>
            <div class="form-group">
                <label for="nachname" class="col-sm-2 control-label">Last Name:</label>
                <div class="col-sm-2">    
                    <!-- add required -->
                    <input type="text" class="form-control" name="lastname" value="<?php echo isset($form_data['lastname']) ? htmlspecialchars($form_data['lastname']) : ''; ?>"><br>
                </div>
            </div>
            <div class="form-group">
                <label for="geburtsdatum" class="col-sm-2 control-label">Date of Birth:</label>
                <div class="col-sm-2">
                    <!-- add required -->
                    <input type="date" class="form-control" name="dateOfBirth" value="<?php echo isset($form_data['dateOfBirth']) ? htmlspecialchars($form_data['dateOfBirth']) : ''; ?>"><br>
                </div>
            </div>
            <div class="form-group">
                <label for="email" class="col-sm-2 control-label">Email Address:</label>
                <div class="col-sm-3">    
                    <!-- add required -->
                    <input type="email" class="form-control" name="email" value="<?php echo isset($form_data['email']) ? htmlspecialchars($form_data['email']) : ''; ?>"><br>
                </div>
            </div>
            <div class="form-group">
                <label for="telefon" class="col-sm-2 control-label">Day Phone:</label>
                <div class="col-sm-3">  
                    <!-- add required -->  
                    <input type="tel" class="form-control" name="telefon" value="<?php echo isset($form_data['telefon']) ? htmlspecialchars($form_data['telefon']) : ''; ?>"><br>
                </div>
            </div>
            <input type="submit" class="btn btn-default" value="Apply now!">
        </form>
    </div>
</div>

<?php require_once('views/partials/footer.php'); ?>
