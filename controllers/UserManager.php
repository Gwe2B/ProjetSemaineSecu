<?php

/**
 * User manager class. It permit to authentified an user, to update it, add a
 * new one and more.
 * @author Gwenaël
 * @version 3
 */
class UserManager {
    const REGEX_PASSWORD = "#^(?=.*[A-Z])(?=.*[a-z])(?=.*\d)(?=.*[-+!*$@%_])([-+!*$@%_\w]{8,15})$#";

    private static $instance = null;
    private $db;

    /**
     * Constructor - Singleton
     * @access private
     * @param PDO $db The database connection
     */
    private function __construct(PDO $db) {
        $this->db = $db;
    }

    /**
     * Singleton pattern. The only mean to retrieve the only instance.
     * @param PDO $db The database connection
     * @return UserManager The only possible class instance
     */
    public static function getInstance(PDO $db) : UserManager {
        if(self::$instance == null) {
            self::$instance = new UserManager($db);
        }

        return self::$instance;
    }

    /**
     * Get an User by his id.
     * @param int $id The user id to retrieve
     * @return User If the user exist
     * @return null If there is no corresponding user in the database
     */
    public function getById(int $id) : ?User {
        $result = null;

        $query = $this->db->prepare("SELECT * FROM user WHERE id=?");
        $query->execute(array($id));
        $datas = $query->fetch();
        $query->closeCursor();

        if($datas != null) {
            $result = new User($datas);
        }

        return $result;
    }

    /**
     * Update the user into the database
     * @param User $usr The user to update into the database
     * @return void
     */
    public function updateUser(User $usr) : void {
        $query = $this->db->prepare(
            "UPDATE user SET
                nom=?, prenom=?, adresse=?, tel=?, mail=?, description=?
            WHERE id=?"
        );
        $query->execute(array(
            $usr->getNom(), $usr->getPrenom(), $usr->getAdresse(), $usr->getTel(),
            $usr->getMail(), $usr->getDescription(), $usr->getId()
        ));
        $query->closeCursor();
    }

    /**
     * Add an user to the database
     * @param User $usr The user to add into the database
     * @return void
     */
    public function createUser(User $usr) : void {
        $query = $this->db->prepare(
            "INSERT INTO user(mail, nom, prenom) VALUE(?,?,?)"
        );

        $query->execute(array(
            $usr->getMail(),
            $usr->getNom(),
            $usr->getPrenom()
        ));

        $usr->setId($this->db->lastInsertId());

        $query->closeCursor();
    }

    /**
     * Retrieve a user from his email if his password is correct
     * @param string $mail The user email
     * @param string $password The user password
     * @return User If the user exist
     * @return null If there is no corresponding user in the database or the
     * password is incorrect
     */
    public function connectUser(string $mail, string $password) : ?User {
        $result = null;

        $query = $this->db->prepare("SELECT * FROM user WHERE mail=?");
        $query->execute(array($mail));
        $datas = $query->fetch();
        $query->closeCursor();

        if($datas != null && password_verify($password, $datas['mdp'])) {
            $result = new User($datas);
        }

        return $result;
    }

    /**
     * Check if the user corresponding to the given e-mail exist
     * @param string $mail The e-mail address to check
     * @return User|null Return true if th user exist, otherwise return false
     */
    public function getByMail(string $mail) : ?User {
        $result = null;

        $query = $this->db->prepare("SELECT * FROM user WHERE mail=?");
        $query->execute(array($mail));
        $data = $query->fetch();
        $query->closeCursor();

        if($data != null) {
            $result = new User($data);
        }

        return $result;
    }

    public function addLink(User $usr, string $token) : void {
        $query = $this->db->prepare("UPDATE user SET hashRecup=?, validiteHash=DATE(NOW()) WHERE id=?");
        $query->execute(array($token, $usr->getId()));
    }

    public function getByLink(string $token) : ?User {
        $result = null;

        $query = $this->db->prepare("SELECT * FROM user WHERE hashRecup=? AND DATEDIFF(DATE(NOW()), validiteHash) < 1");
        $query->execute(array($token));
        $data = $query->fetch();
        $query->closeCursor();

        if($data != null) {
            $result = new User($data);
        }

        return $result;
    }

    public function changePassword(User $usr, string $newPassword) : bool {
        $result = false;

        if(preg_match(UserManager::REGEX_PASSWORD, $newPassword)) {
            $result = true;
            $newPassword = password_hash($newPassword, PASSWORD_DEFAULT);

            $query = $this->db->prepare("UPDATE user SET mdp=?, hashRecup=NULL, validiteHash=NULL WHERE id=?");
            $query->execute(array($newPassword, $usr->getId()));
            $query->closeCursor();
        }

        return $result;
    }

    public function isPasswordCorrect(User $usr, string $password) : bool {
        $result = false;

        $query = $this->db->prepare("SELECT mdp FROM user WHERE id=?");
        $query->execute(array($usr->getId()));
        $data = $query->fetch();
        $query->closeCursor();

        if($data != null && password_verify($password, $data['mdp'])) {
            $result = true;
        }

        return $result;
    }

    /* Recuperation des amis */

    public function getFriendsByUserId(int $userId) : ?array {
        $friends = null;

        $query = $this->db->prepare("SELECT CASE WHEN f.user_id=? THEN f.friend_id ELSE f.user_id END AS id
        FROM friends f WHERE
        (
            f.user_id=?
        ) OR (
            f.friend_id=?
        )
        GROUP by id");

        $query->execute(array($userId,$userId,$userId));
        $data = $query->fetchAll();
        $query->closeCursor();

        if($data != null) {

          $friends = array();
          $friend = null;

          $query1 = $this->db->prepare("SELECT * FROM user u WHERE u.id=?");

          foreach ($data as $row) {

            $query1->execute(array(implode($row)));
            $data1 = $query1->fetch();

            if($data1 != null) {
              $friend = new User($data1);
            }

            array_push($friends, $friend);
          }

          $query1->closeCursor();
        }

        return $friends;
    }
	
	
	public function areFriends(int $usr1Id, int $usr2Id) : bool {
        
		$areFriends = false;

        $query = $this->db->prepare("SELECT * FROM friends where (user_id=? and friend_id=?) or (user_id=? and friend_id=?)");
        $query->execute(array($usr1Id,$usr2Id,$usr2Id,$usr1Id));
        $data = $query->fetch();
        $query->closeCursor();

        if($data != null) {
			$areFriends=true;
          }

        return $areFriends;
    }
	
	public function getNoneFriendsUsers(int $usrId) : ?array {
        
		$users = null;

        $query = $this->db->prepare("SELECT * FROM user where id!=?");
        $query->execute(array($usrId));
        $data = $query->fetchAll();
        $query->closeCursor();

        if($data != null) {
			
			$users= array();
			
			foreach ($data as $row) {

              $newUser = new User($row);
			  $areFriends = $this->areFriends($usrId, $newUser->getId());
              
			  if($areFriends==false) {
				  array_push($users, $newUser);
			  }
    
            }
        }

        return $users;
    }

    public function addFriendship(int $usr1Id, int $usr2Id) : void {
		
		if($usr1Id != null and $usr2Id != null) {
			
			$areFriends = $this->areFriends($usr1Id, $usr2Id);
			
			if($areFriends==false) {
				
				 $query = $this->db->prepare("INSERT INTO friends VALUES (?,?)");
                 $query->execute(array($usr1Id,$usr2Id)); 
				  
			  }
			
		}

    }	

}
