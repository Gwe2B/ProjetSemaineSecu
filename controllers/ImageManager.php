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
    public function getById(int $id) : ?Image {
        $result = null;

        $query = $this->db->prepare("SELECT * FROM image WHERE id=?");
        $query->execute(array($id));
        $datas = $query->fetch();
        $query->closeCursor();

        if($datas != null) {
            $result = new Image($datas);
        }

        return $result;
    }

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

    /**
     * Remove an image to the database
     * @param int $data The image id to remove from the database
     */
    public function removeImage(Image $img) : void {
        $query = $this->db->prepare("DELETE FROM image WHERE id=?");
        $query->execute(array($img->getId()));
        $query->closeCursor();

        unlink(ROOT."uploads".DS.$img->getPath());
    }
}