<?php
namespace PVAssistance;

enum Status {
  const IN_PROGRESS = "In Progress";
  const APPROVED = "Approved";
  const REJECTED = "Rejected";
}

enum Action {
  const ADDED_REQUEST = "added request";
  const INSERTED_WRONG_TOKEN = "wrong token inserted";
  const CHECKED_REQUEST = "checked request";
  const ADMIN_LOGIN = "admin logged in";
  const ADMIN_LOGIN_FAILED = "admin login failed";
  const APPROVED_REQUEST = 'approved request';
  const REJECTED_REQUEST = 'rejected request';
  const REQUEST_SET_IN_PROGRESS = 'request still in progress';
}

/**
 * Controller
 * 
 * class handles POST requests and redirects 
 * the client after processing
 * - demo of singleton pattern
 */
class Controller {
  // static strings used in views
  
  public const ACTION = 'action';
  public const PAGE = 'page';
  public const ACTION_LOGIN = 'login';
  public const ACTION_LOGOUT = 'logout';
  public const USER_NAME = 'userName';
  public const USER_PASSWORD = 'password';
  public const APPLY = 'newApplication';
  public const CHECK_STATUS = 'checkStatus';
  public const PROCESS_APPLICATION = 'processApplication';

  private static $instance = false;

  /**
   * 
   * @return Controller
   */
  public static function getInstance() : Controller {

    if (!self::$instance) {
      self::$instance = new Controller();
    }
    return self::$instance;
  }

  private function __construct() {
    
  }

  /**
   * add. helpers
   */

   private static function getCurrentTime() : string {
    $format = 'Y-m-d H:i:s';
    $sysTime = date($format);
    return $sysTime;
  }

  private function generateUUID(string $id) : string {
    return urlencode(sprintf('%04x%04x-%04x-%04x-%04x-%04x%04x%04x',

      // 32 bits for "time_low"
      mt_rand(0, 0xffff), mt_rand(0, 0xffff),

      // 16 bits for "time_mid"
      mt_rand(0, 0xffff),

      // 16 bits for "time_hi_and_version",
      // four most significant bits holds version number 4
      mt_rand(0, 0x0fff) | 0x4000,

      // 16 bits, 8 bits for "clk_seq_hi_res",
      // 8 bits for "clk_seq_low",
      // two most significant bits holds zero and one for variant DCE1.1
      mt_rand(0, 0x3fff) | 0x8000,

      // 48 bits for "node"
      mt_rand(0, 0xffff), mt_rand(0, 0xffff), mt_rand(0, 0xffff)
    ));
  }

  private function generateToken() : string{
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ!@#$%^&*()';
    $token = '';

    $characterCount = strlen($characters);

    for ($i = 0; $i < 5; $i++) {
        $randomIndex = mt_rand(0, $characterCount - 1);
        $token .= $characters[$randomIndex];
    }

    return $token;
  }

  /**
   * 
   * processes POST requests and redirects client depending on selected 
   * action
   * 
   * PHP 8: returns never because either redirect or exception
   * @throws Exception
   */
  public function invokePostAction() : never {

    if ($_SERVER['REQUEST_METHOD'] != 'POST') {
      throw new \Exception('Controller can only handle POST requests.');
    } 

    elseif (!isset($_REQUEST[self::ACTION])) {
      throw new \Exception(self::ACTION . ' not specified.');
    }

    $action = $_REQUEST[self::ACTION];
    
    switch ($action) {
      
      case self::APPLY :
        $constructionDate = $_REQUEST['constructionDate'];
        $requestDate = self::getCurrentTime();
        $ipAddress = $_SERVER['REMOTE_ADDR'];
        $id = $_REQUEST['id'];
        $address = $_REQUEST['address'];
        $kwP = $_REQUEST['kwP'];
        $pvType = $_REQUEST['pvType'];
        $sex = $_REQUEST['sex'];
        $firstname = $_REQUEST['firstname'];
        $lastname = $_REQUEST['lastname'];
        $dateOfBirth = $_REQUEST['dateOfBirth'];
        $email = $_REQUEST['email'];
        $telefon = $_REQUEST['telefon'];

        $errors = [];
        
        $_SESSION['form_data'] = $_POST;

        $validationError = false;
        
        if(!(strlen($id) === 20 && ctype_digit($id))) {
          $errors[] = "ID must be 20 digits long, please check your ID";
          $validationError = true;
        }

        if(\Data\DataManager::applicationIsInDB($id)){
          $errors[] = "This ID has already been requested, please check your ID";
          $validationError = true;
        }

        if(!preg_match('/^[a-zA-Z\s\-]+$/', $firstname)) {
          $errors[] = "Please enter a valid first name";
          $validationError = true;
        }
        $safeFirstName = htmlspecialchars($firstname, ENT_QUOTES, 'UTF-8');

        
        if(!preg_match('/^[a-zA-Z\s\-]+$/', $lastname)) {
          $errors[] = "Please enter a valid last name";
          $validationError = true;
        }
        $safeLasttName = htmlspecialchars($lastname, ENT_QUOTES, 'UTF-8');


        if(!filter_var($email, FILTER_VALIDATE_EMAIL)){
          $errors[] = " Please enter a valid email address";
          $validationError = true;
        }

        if(!(preg_match('/^(\+\d{2}\s)?(\d+\s*)*$/', $telefon))) {
          $errors[] = "Invalid Phone Number";
          $validationError = true;
        }


        if($validationError) {
          $_SESSION['errors'] = $errors;
          Util::redirect('index.php?view=apply');
        }
        
        $user = \Data\DataManager::getUserByEmail($email);

        if($user == null) {
          $user = \Data\DataManager::createUser($safeFirstName, $safeLasttName, $sex, $dateOfBirth, $email, $telefon);
        }

        $uuid = $this->generateUUID($id); // TODO

        $token = $this->generateToken();

        $application = \Data\DataManager::createApplication($id, $user, $address, $kwP, strtotime($constructionDate), 
          $pvType, strtotime($requestDate), $ipAddress, $token, $uuid, Status::IN_PROGRESS, '');

          \Data\DataManager::log($_SERVER['REMOTE_ADDR'], 'new application with uuid ' . $uuid . ' created', $_SESSION['user']);

        Util::redirect('index.php?view=success&id=' . rawurlencode($id));

        break;

      case self::CHECK_STATUS :
        $errors = [];

        $url = $_SERVER['REQUEST_URI'];

        $queryString = parse_url($url, PHP_URL_QUERY);

        $_SESSION['form_data'] = $_POST;

        $parts = explode('checkStatus', $queryString);

        $requestUrl = isset($parts[1]) ? $parts[1] : '';
        $uuid = $_POST['uuid'];

        $token = $_POST['token'];

        $application = \Data\DataManager::getApplicationByUUIDAndToken($uuid, $token);

        $format = 'Y-m-d H:i:s';
        $accessDate = date($format);

        if($application == null) {
          $errors[] = "Application not found, please check your ID and token";
          \Data\DataManager::log($_SERVER['REMOTE_ADDR'], 'application not found error', null);
        } else if ($application != null && $uuid == $application->getUuid() && $token == $application->getToken()) {
          $_SESSION['application'] = $application;
        } else {
          $errors[] = "There was a UUID and token mismatch, please check your token";
          \Data\DataManager::log($_SERVER['REMOTE_ADDR'], 'application token-uuid mismatch error', null);
        }

        $_SESSION['errors'] = $errors;

        Util::redirect('index.php?view=checkStatus' . $requestUrl);
        break;

      case self::PROCESS_APPLICATION :
        $status = $_POST['status'];
        $notes = $_POST['notes'];

        $id = $_POST['id'];
        $uuid = $_POST['uuid'];
        $token = $_POST['token'];

        $app = \Data\DataManager::processApplication($uuid, $token, $status, $notes);

        \Data\DataManager::log($_SERVER['REMOTE_ADDR'], 'application with uuid ' . $uuid . 'status changed to ' . $status, $_SESSION['user']);

        Util::redirect('index.php?view=list');
        break;

      case self::ACTION_LOGIN :
        $errors = [];
        if (!AuthenticationManager::authenticate($_REQUEST[self::USER_NAME], $_REQUEST[self::USER_PASSWORD])) {
          //$this->forwardRequest(array('Invalid user name or password.'));
          $errors[] = "Invalid user name or password.";
          $_SESSION['errors'] = $errors;
          $_SESSION['form_data'] = $_POST;
          \Data\DataManager::log($_SERVER['REMOTE_ADDR'], 'failed login attempt', $_SESSION['user']);
        }
        // authenticated
        Util::redirect('index.php?view=list' . '');
        break;

      case self::ACTION_LOGOUT :
        //sign out current user
        AuthenticationManager::signOut();
        Util::redirect();
        break;

      default : 
        throw new \Exception('Unknown controller action: ' . $action);
        break;
    }
  }

  /**
   * 
   * @param string $nameOnCard
   * @param integer $cardNumber
   * @return bool
   */
  protected function processCheckout(string $nameOnCard = null, string $cardNumber = null) : bool {

    $errors = [];
    $nameOnCard = trim($nameOnCard);
    if ($nameOnCard == null || strlen($nameOnCard) == 0) {
      $errors[] = 'Invalid name on card.';
    }
    if ($cardNumber == null || strlen($cardNumber) != 16 || !ctype_digit($cardNumber)) {
      $errors[] = 'Invalid card number. Card number must be sixteen digits.';
    }

    if (sizeof($errors) > 0) {
      $this->forwardRequest($errors);
      return false;
    }

    //check cart
    if (ShoppingCart::size() == 0) {
      $this->forwardRequest(['Shopping cart is empty.']);
      return false;
    }

    //try to place a new order
    $user = AuthenticationManager::getAuthenticatedUser();
    $orderId = \Data\DataManager::createOrder($user->getId(), ShoppingCart::getAll(), $nameOnCard, $cardNumber);
    if (!$orderId) {
      $this->forwardRequest(['Could not create order.']);
      return false;
    }
    //clear shopping card and redirect to success page
    ShoppingCart::clear();
    Util::redirect('index.php?view=success&orderId=' . rawurlencode($orderId));

    return true;
  }

  /**
   * 
   * @param array $errors : optional assign it to 
   * @param string $target : url for redirect of the request
   */
  protected function forwardRequest(array $errors = null, string $target = null) : never {
    //check for given target and try to fall back to previous page if needed
    if ($target == null) {
      if (!isset($_REQUEST[self::PAGE])) {
        throw new \Exception('Missing target for forward.');
      }
      $target = $_REQUEST[self::PAGE];
    }
    //forward request to target
    // optional - add errors to redirect and process them in view
    if (count($errors) > 0) {
      $target .= '&errors=' . urlencode(serialize($errors));
    }
    header('location: ' . $target);
    exit();
  }

}
