<?php

/**
 * User manager class. It permit to authentified an user, to update it, add a
 * new one and more.
 * @author Wissam
 * @version 1
 */
class ImageManager {

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
    public static function getInstance(PDO $db) : ImageManager {
        if(self::$instance == null) {
            self::$instance = new ImageManager($db);
        }

        return self::$instance;
    }

/* ------------------------------ Class methods ----------------------------- */
    /**
     * Add an image to the database
     * @param Image $img The image to insert into the database
     */
    public function addImage(Image $img) : void {
        $query = $this->db->prepare(
            "INSERT INTO image(path, visibility, user_id, gallerie_id)
            VALUE(?, ?, ?, ?)"
        );

        $query->execute(array(
            $img->getPath(),
            $img->getVisibility(),
            $img->getUser_Id(),
            $img->getGallerie_Id()
        ));

        $query->closeCursor();
    }
}