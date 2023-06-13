<?php

namespace Data;

use PVAssistance\Category;

use PVAssistance\User;
use PVAssistance\Application;

use PVAssistance\Book;
use PVAssistance\PagingResult;

enum Status {
  const IN_PROGRESS = "In Progress";
  const APPROVED = "Approved";
  const REJECTED = "Rejected";
}

/**
 * DataManager
 * PDO Version
 * 
 * 
 * @package    
 * @subpackage 
 * @author     John Doe <jd@fbi.gov>
 */
class DataManager implements IDataManager {

  private static $__connection;

  /**
   * connect to the database
   * 
   * note: alternatively put those in parameter list or as class variables
   * 
   * @return connection resource
   */
	private static function getConnection() {
		if (!isset(self::$__connection)) {

			$type = 'mysql';
			$host = 'db';
			$name = 'db';
			$user = 'db';
			$pass = 'db';

			self::$__connection = new \PDO($type . ':host=' . $host . ';dbname=' . $name . ';charset=utf8', $user,
				$pass);
		}
		return self::$__connection;
	}

	public static function exposeConnection() {
		return self::getConnection();
	}

  /**
   * place query
   * 
   * note: using prepared statements
   * see the filtering in bindValue()
   * 
   * @return mixed
   */
  private static function query($connection, $query, $parameters = []) {
		$connection->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
		try {
			$statement = $connection->prepare($query);
			$i = 1;
			foreach ($parameters AS $param) {
				if (is_int($param)) {
					$statement->bindValue($i, $param, \PDO::PARAM_INT);
				}
				if (is_string($param)) {
					$statement->bindValue($i, $param, \PDO::PARAM_STR);
				}
				$i++;
			}
			$statement->execute();
		}
		catch (\Exception $e) {
			die($e->getMessage());
//			die('Database Error ' . implode(' | ', $statement->errorInfo()));
		}
		return $statement;
	}

  /**
   * get the key of the last inserted item
   * 
   * @return integer
   */
  private static function lastInsertId($connection) {
    return $connection->lastInsertId();
  }

  /**
   * retrieve an object from the database result set
   * 
   * @param object $cursor result set
   * @return object
   */
  private static function fetchObject($cursor) {
    return $cursor->fetchObject();
  }

  /**
   * remove the result set
   * 
   * @param object $cursor result set
   * @return null
   */
  private static function close($cursor) {
    $cursor->closeCursor();
  }

  /**
   * close the database connection
   * 
   * note: in PDO, simply set the instance of PDO to null
   * 
   * @param object $cursor resource of current database connection
   * @return null
   */
  private static function closeConnection() {
      self::$__connection = null;
  }

  /**
   * get the categories
   * 
   * @return array of Category-items
   */
  public static function getCategories() : array {
    $categories = [];
    $con = self::getConnection();
    $res = self::query($con, "
      SELECT id, name 
      FROM categories;
      ");
    while ($cat = self::fetchObject($res)) {
      $categories[] = new Category($cat->id, $cat->name);
    }
    self::close($res);
    self::closeConnection();
    return $categories;
  }

  /**
   * get the books per category
   * 
   * note: see how prepared statements replace "?" with array element values
   *
   * @param integer $categoryId  numeric id of the category
   * @return array of Book-items
   */
  public static function getBooksByCategory($categoryId)  : array {
    $books = [];
    $con = self::getConnection();
    $res = self::query($con, "
      SELECT id, categoryId, title, author, price 
      FROM books 
      WHERE categoryId = ?;
      ", [$categoryId]);
    while ($book = self::fetchObject($res)) {
      $books[] = new Book($book->id, $book->categoryId, $book->title, $book->author, $book->price);
    }
    self::close($res);
    self::closeConnection($con);
    return $books;
  }

  /**
   * get the books per search term
   * 
   * note: search via LIKE
   *
   * @param string $term  search term: book title string match
   * @return array of Book-items
   */
  public static function getBooksForSearchCriteria(string $term) : array {
    $books = [];
    $con = self::getConnection();
    $res = self::query($con, "
      SELECT id, categoryId, title, author, price 
      FROM books 
      WHERE title LIKE ?;
      ", ["%" . $term . "%"]);
    while ($book = self::fetchObject($res)) {
      $books[] = new Book($book->id, $book->categoryId, $book->title, $book->author, $book->price);
    }
    self::close($res);
    self::closeConnection($con);
    return $books;
  }

  /**
   * get the books per search term – paginated set only
   *
   * @param string $term  search term: book title string match
   * @param integer $offset  start at the nth item
   * @param integer $numPerPage  number of items per page
   * @return array of Book-items
   */
  public static function getBooksForSearchCriteriaWithPaging($term, $offset, $numPerPage) {
    $con = self::getConnection();
    //query total count
    $res = self::query($con, "
      SELECT COUNT(*) AS cnt 
      FROM books 
      WHERE title LIKE ?;
      ", ["%" . $term . "%"]);
    $totalCount = self::fetchObject($res)->cnt;
    self::close($res);
    //query books to return
    $books = [];
    $res = self::query($con, "
      SELECT id, categoryId, title, author, price 
      FROM books 
      WHERE title 
      LIKE ? LIMIT ?, ?;
      ", ["%" . $term . "%", intval($offset), intval($numPerPage)]);
    while ($book = self::fetchObject($res)) {
      $books[] = new Book($book->id, $book->categoryId, $book->title, $book->author, $book->price);
    }
    self::close($res);
    self::closeConnection($con);
    return new PagingResult($books, $offset, $totalCount);
  }

  /**
   * get the User item by id
   * 
   * @param integer $userId  uid of that user
   * @return User | false
   */
  public static function getUserById($userId) : ?User {
    $user = null;
    $con = self::getConnection();
    $res = self::query($con, "
      SELECT id, userName, passwordHash 
      FROM users 
      WHERE id = ?;
      ", [$userId]);
    if ($u = self::fetchObject($res)) {
      $user = new User($u->id, $u->userName, $u->passwordHash);
    }
    self::close($res);
    self::closeConnection($con);
    return $user;
  }

  /**
   * get the User item by name
   * 
   * @param string $userName  name of that user - must be exact match
   * @return User | false
   */
  public static function getUserByUserName($userName) :?User {
    $user = null;
    $con = self::getConnection();
    $res = self::query($con, "
      SELECT id, userName, passwordHash 
      FROM users 
      WHERE userName = ?;
      ", [$userName]);
    if ($u = self::fetchObject($res)) {
      $user = new User($u->id, $u->userName, $u->passwordHash);
    }
    self::close($res);
    self::closeConnection($con);
    return $user;
  }

  /**
   * place to order with the shopping cart items
   * 
   * note: wrapped in a transaction
   *
   * @param integer $userId   id of the ordering user
   * @param array $bookIds    integers of book ids
   * @param string $nameOnCard  cc name
   * @param string $cardNumber  cc number
   * @return integer
   */
  public static function createOrder($userId, array $bookIds, $nameOnCard, $cardNumber) : int {
    $con = self::getConnection();

    $con->beginTransaction();

    try {

      self::query($con, "
        INSERT INTO orders (
          userId
          , creditCardNumber
          , creditCardHolder
        ) VALUES (
          ?
          , ?
          , ?
        );
        ", [$userId, $cardNumber, $nameOnCard]);
      $orderId = self::lastInsertId($con);
      foreach ($bookIds as $bookId) {
        self::query($con, "
          INSERT INTO orderedbooks (
            orderId
            , bookId
          ) VALUES (
            ?
            , ?
          );", [$orderId, $bookId]);
      }
      $con->commit();
    }
    catch (\Exception $e) {

      // one of the queries failed - complete rollback
      $con->rollBack();
      $orderId = null;
    }
    self::closeConnection($con);
    return $orderId;
  }

   /**
   * check if ID is in DB
   * 
   * note: wrapped in a transaction
   *
   * @param string $id   id of the PV to check
   */
  public static function applicationIsInDB(string $id) : bool{
    $con = self::getConnection();
    $res = self::query($con, "
      SELECT id 
      FROM application
      WHERE id = ?;
      ", [$id]);
    $pv = self::fetchObject($res);
    self::close($res);
    self::closeConnection($con);
    return $pv != null;
  }

  /**
   * get the User by email address: easy way to check for duplicates
   * when registering new user
   * 
   * @param string $email  email address of that user - must be exact match
   * @return User | false
   */
  public static function getUserByEmail($email) : ?User {
    $user = null;
    $con = self::getConnection();
    $res = self::query($con, "
      SELECT userId, firstName, lastName, sex, dateOfBirth, emailAddress, phoneNo
      FROM user
      WHERE emailAddress = ?;
      ", [$email]);
    if ($u = self::fetchObject($res)) {
      $user = new User($u->userId, $u->firstName, $u->lastName, $u->sex, $u->dateOfBirth, $u->emailAddress, $u->phoneNo);
    }
    self::close($res);
    self::closeConnection($con);
    return $user;
  }

  /**
   * get the User by userId
   * if userId is known, this is the best way to get the user
   * 
   * @param string $email  email address of that user - must be exact match
   * @return User | false
   */
  public static function getUserByUserId($userId) : ?User {
    $user = null;
    $con = self::getConnection();
    $res = self::query($con, "
      SELECT userId, firstName, lastName, sex, dateOfBirth, emailAddress, phoneNo
      FROM user
      WHERE userId = ?;
      ", [$userId]);
    if ($u = self::fetchObject($res)) {
      $user = new User($u->userId, $u->firstName, $u->lastName, $u->sex, $u->dateOfBirth, $u->emailAddress, $u->phoneNo);
    }
    self::close($res);
    self::closeConnection($con);
    return $user;
  }

  /**
   * create new user from required inputs, using email as unique identifier
   * 
   * @param string $firstName 
   * @param string $lastName
   * @param string $sex
   * @param string $dateOfBirth
   * @param string $emailAddress
   * @param string $phoneNo
   * 
   * @return User
   */
  public static function createUser($firstName, $lastName, $sex, $dateOfBirth, $emailAddress, $phoneNo) : User {
    $con = self::getConnection();
    $res = self::query($con, "
      INSERT INTO user (firstName, lastName, sex, dateOfBirth, emailAddress, phoneNo) VALUES (?, ?, ?, ?, ?, ?);
      ", [$firstName, $lastName, $sex, $dateOfBirth, $emailAddress, $phoneNo]);
    $res2 = self::query($con, "
      SELECT userId, firstName, lastName, sex, dateOfBirth, emailAddress, phoneNo
      FROM user
      WHERE emailAddress = ?;
      ", [$emailAddress]);
    if ($u = self::fetchObject($res)) {
      $user = new User($u->userId, $u->firstName, $u->lastName, $u->sex, $u->dateOfBirth, $u->emailAddress, $u->phoneNo);
    }
    self::close($res);
    self::close($res2);
    self::closeConnection($con);
    return $user;
  }

  /**
   * create new application from required inputs
   * 
   * @param string $id 
   * @param User $user, containing userId
   * @param string $address
   * @param string $outputInKWP
   * @param Date $constructionDate
   * @param string $PVType
   * @param DateTime $requestDate
   * @param string $IPAddress
   * @param string $token
   * @param string $uuid
   * @param string $status
   * @param string $notes
   * 
   * @return Application
   */
  public static function createApplication($id, $user, $address, $outputInKWP, $constructionDate, $PVType, 
  $requestDate, $IPAddress, $token, $uuid, $status, $notes) : Application {
    $con = self::getConnection();
    $res = self::query($con, "
      INSERT INTO application (id, userId, address, outputInKWP, constructionDate, PVType, requestDate, IPAddress, token, uuid, status, notes) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?);
      ", [$id, $user->getId(), $address, $outputInKWP, date('Y-m-d', $constructionDate), $PVType, date('Y-m-d H:i:s', $requestDate), $IPAddress, $token, $uuid, $status, $notes]);
    $application = new Application($id, $user, $address, $outputInKWP, new \DateTime(date('Y-m-d', $constructionDate)), $PVType, new \DateTime(date('Y-m-d', $requestDate)), $IPAddress, $token, $uuid, $status, $notes);
    self::close($res);
    self::closeConnection($con);
    return $application;
  }

  /**
   * get application, checking uuid/token combination is valid
   * 
   * @param string $uuid
   * @param string $token
   * @return Application | false
   */
  public static function getApplicationByUUIDAndToken($uuid, $token) : ?Application {
    $application = null;
    $con = self::getConnection();
    $res = self::query($con, "
      SELECT id, userId, address, outputInKWP, constructionDate, PVType, requestDate, IPAddress, token, uuid, status, notes
      FROM application
      WHERE uuid = ? AND token = ?;
      ", [$uuid, $token]);
    if ($a = self::fetchObject($res)) {
      $application = new Application($a->id, self::getUserByUserId($a->userId), $a->address, $a->outputInKWP, new \DateTime($a->constructionDate), $a->PVType, new \DateTime($a->requestDate), $a->IPAddress, $a->token, $a->uuid, $a->status, $a->notes);
    }
    self::close($res);
    self::closeConnection($con);
    return $application;
  }

}