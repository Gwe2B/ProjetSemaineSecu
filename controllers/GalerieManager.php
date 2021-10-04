<?php

/**
 * User manager class. It permit to authentified an user, to update it, add a
 * new one and more.
 * @author Wissam
 * @version 1
 */
class GalerieManager {

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
    public static function getInstance(PDO $db) : GalerieManager {
        if(self::$instance == null) {
            self::$instance = new GalerieManager($db);
        }

        return self::$instance;
    }

    /**
     * Get a galerie by its id.
     * @param int $id The galerie id to retrieve
     * @return Galerie If the galerie exists
     * @return null If there is no corresponding galerie in the database
     */
    public function getById(int $id) : ?Galerie {
        $result = null;

        $query = $this->db->prepare("SELECT * FROM gallerie WHERE id=?");
        $query->execute(array($id));
        $datas = $query->fetch();
        $query->closeCursor();

        if($datas != null) {
            $result = new Galerie($datas);
        }

        return $result;
    }

    /**
     * Update the galerie into the database
     * @param Galerie $gal The galerie to update into the database
     * @return void
     */
    public function updateGalerie(Galerie $gal) : void {
        $query = $this->db->prepare(
            "UPDATE gallerie SET
                name=?
            WHERE id=?"
        );
        $query->execute(array(
            $gal->getNom(),
            $gal->getId()
        ));
        $query->closeCursor();
    }
	
	/**
     * Get a galerie by its user id.
     * @param int $UserId The galerie user_id to retrieve
     * @return array(Galerie) If the galeries exist
     * @return null If there is no corresponding galerie in the database
     */
    public function getByUserId(int $UserId) : ?array {
        
		$galeries = null;

        $query = $this->db->prepare("SELECT g.id, g.name FROM gallerie g INNER JOIN image i ON g.id = i.gallerie_id where i.user_id=? group by g.id");
        $query->execute(array($UserId));
        $datas = $query->fetchAll();
        $query->closeCursor();
	 
	 
        if($datas != null) {
			
			$galeries = array();
			$gal = null;
			
			/*récupérer les images de la galerie*/
			
			$query1 = $this->db->prepare("SELECT * FROM Image i where i.gallerie_id=?");
			
			foreach($datas as $row) {
				
				$gal = new Galerie($row);
						
                $query1->execute(array($gal->getId()));
                $datas1 = $query1->fetchAll();                
				
				if($datas1 != null) {
			
			       $images = array();
			
			       foreach($datas1 as $row1) {
				   array_push($images, new Image($row1));  
			       }
                }
				
				$gal->setImages($images);
				
				array_push($galeries, $gal);
           
			}
			
			$query1->closeCursor();
        }

        return $galeries;
    }
	
	/**
     * Get an image by its galerie id.
     * @param int $GalerieId The Image galerie_id to retrieve
     * @return array(Images) If the images exist
     * @return null If there is no corresponding image in the database
     */
    /* public function getImageByGalerieId(int $GalerieId) : ?array {
        
		$result = null;

        $query = $this->db->prepare("SELECT * FROM Image i where i.gallerie_id=?");
        $query->execute(array($GalerieId));
        $datas = $query->fetchAll();
        $query->closeCursor();
	 
	 
        if($datas != null) {
			
			$result = array();
			
			foreach($datas as $row) {
				array_push($result, new Image($row));  
			}
        }

        return $result;
    } */
	
	/**
     * Get path of first image of a galerie by its galerie id.
     * @param int $GalerieId The Image galerie_id to retrieve
     * @return array(Images) If the images exist
     * @return null If there is no corresponding image in the database
     */
	 
    /* public function getFirstImagePath(int $GalerieId) : ?array {

        $query = $this->db->prepare("SELECT i.path FROM image i where i.gallerie_id=? ORDER BY i.id ASC LIMIT 1;");
        $query->execute(array($GalerieId));
        $result = $query->fetch();
        $query->closeCursor();

        return $result;
    } */

}