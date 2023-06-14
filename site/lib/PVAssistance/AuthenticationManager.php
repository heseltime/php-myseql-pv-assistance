<?php

namespace PVAssistance;


SessionContext::create();
/**
 * AuthnticationManager
 * 
 * 
 * @package    
 * @subpackage 
 * @author     John Doe <jd@fbi.gov>
 */
class AuthenticationManager  {

  /**
   * check the credentials
   * 
   * note: sha1 encryption – no salt used!
   *
   * @param string $userName   name of the logging in user
   * @param string $password   password in clear text
   * @return boolean
   */
  public static function authenticate(string $name, string $password) : bool {
    $admin = \Data\DataManager::getAdminByName($name);
    if ($admin != null && $admin->getPassword() == hash('sha1', $name . '|' . $password)) {
      $_SESSION['admin'] = $admin->getId();
      return true;
    }
    self::signOut();
    return false;
  }

  /**
   * log out procedure
   *
   * destroy user-session object
   */
  public static function signOut() : void {
    unset($_SESSION['user']);
  }

  /**
   * check if a user is logged in or not
   *
   * @return boolean
   */
  public static function isAuthenticated() : bool {
    return isset($_SESSION['user']);
  }

  /**
   * get the logged in users data
   *
   * @return Bookshop\User
   */
  public static function getAuthenticatedUser() : ?User  {
    return self::isAuthenticated() ? \Data\DataManager::getUserById($_SESSION['user']) : null;
  }

}
