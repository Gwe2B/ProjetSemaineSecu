<?php

/**
 * User manager class. It permit to authentified an user, to update it, add a
 * new one and more.
 * @author Gwenaël
 * @version 1
 */
class UserManager {
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
}