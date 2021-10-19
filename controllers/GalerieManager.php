<?php

require_once ROOT."controllers".DS."User.php";
require_once ROOT."controllers".DS."UserManager.php";

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
			$images = $this->getImagesByGalerieId($result->getId());
			$result->setImages($images);
        }

        return $result;
    }
	
	public function getImagesByGalerieId(int $galId) : ?array {
        
		$images = null;

        $query = $this->db->prepare("SELECT * FROM image WHERE gallerie_id=?");
        $query->execute(array($galId));
        $datas = $query->fetchAll();
        $query->closeCursor();

        if($datas != null) {
			
			$images = array();
			
			foreach($datas as $row) {
				$img= new Image($row);
				array_push($images, $img);
			}
        }

        return $images;
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
            $gal->getName(),
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
			
	        $images = null;
			
			foreach($datas as $row) {
				
				$gal = new Galerie($row);
				$gal->call_setUser_Id($UserId);
				
				$images = $this->getImagesByGalerieId($gal->getId());
				
				if ($images !=null) {
					
					$gal->setImages($images);
				}
				
				array_push($galeries, $gal);
           
			}

        }

        return $galeries;
    }
	
	public function getEmptyGaleriesByUserId(int $UserId) : ?array {
        
		$emptyGaleries = null;

        $query = $this->db->prepare("SELECT g.id, g.name, g.user_id FROM gallerie g LEFT OUTER JOIN image i ON g.id = i.gallerie_id where g.user_id=? and i.id is null");
        $query->execute(array($UserId));
        $datas = $query->fetchAll();
        $query->closeCursor();
	 
	 
        if($datas != null) {
			
			$emptyGaleries = array();
			$gal = null;
			
			foreach($datas as $row) {
				
				$gal = new Galerie($row);
				$gal->call_setUser_Id($UserId);
				array_push($emptyGaleries, $gal);
           
			}

        }

        return $emptyGaleries;
    }
	
	 /**
     * Add a galerie to the database
     * @param Galerie $gal The galerie to insert into the database
     */
    public function addGalerie(Galerie $gal) : void {
        $query = $this->db->prepare(
            "INSERT INTO gallerie(name, user_id)
            VALUE(?, ?)"
        );

        $query->execute(array(
            $gal->getName(),
            $gal->getUser_Id()
        ));

        $query->closeCursor();
    }
	
	/**
     * Remove a galerie to the database and all its images
     * @param int $data The galerie id to remove from the database
     */
    public function removeGalerie(Galerie $gal) : void {
	
        $images = $gal->getImages();
		
		if($images !=null) {
	
	    foreach($images as $img) {
			
       // echo("***  ".$img->getPath());
		
		$query1 = $this->db->prepare("DELETE FROM image WHERE id=?");
        $query1->execute(array($img->getId()));
        $query1->closeCursor();
		unlink(ROOT."uploads".DS.$img->getPath());
          
		}
        }		
        
		$query2 = $this->db->prepare("DELETE FROM gallerie WHERE id=?");
        $query2->execute(array($gal->getId()));
        $query2->closeCursor();
		
    }
	
	/************************** PARTIE PROFIL AMI ou USER ******************************/
	
	public function getByOtherUserId(int $otherId, int $usrId) : ?array {
        
		$galeries = null;

        $query = $this->db->prepare("SELECT g.id, g.name FROM gallerie g INNER JOIN image i ON g.id = i.gallerie_id where i.user_id=? group by g.id");
        $query->execute(array($otherId));
        $datas = $query->fetchAll();
        $query->closeCursor();
	 
	 
        if($datas != null) {
			
			$galeries = array();
			$gal = null;
			
			/*récupérer les images de la galerie*/
			
	        $images = null;
			
			foreach($datas as $row) {
				
				$gal = new Galerie($row);
				$gal->call_setUser_Id($otherId);
				
				$images = $this->getOtherUserImagesByGalerieId($gal->getId(),$otherId,$usrId);
				
				if ($images !=null) {
					
					$gal->setImages($images);
					array_push($galeries, $gal);
				}
			}

        }

        return $galeries;
    }
	
	public function getOtherUserImagesByGalerieId(int $galId, int $otherId, int $usrId) : ?array {
        
		$images = null;
		$um = UserManager::getInstance($this->db);
		$areFriends = $um->areFriends($usrId, $otherId);

        $query = null;
        if($areFriends==true) {
			$query = $this->db->prepare("SELECT * FROM image WHERE gallerie_id=? and visibility!=-1");
		} else {
			$query = $this->db->prepare("SELECT * FROM image WHERE gallerie_id=? and visibility=1");
		}        
        $query->execute(array($galId));
        $datas = $query->fetchAll();
        $query->closeCursor();

        if($datas != null) {
			
			$images = array();
			
			foreach($datas as $row) {
				$img= new Image($row);
				array_push($images, $img);
			}
        }

        return $images;
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