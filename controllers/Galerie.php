<?php

require_once "controllers/Hydrator.php";
require_once "Image.php";

/**
 * User class based on the user table of the database
 * @author Wissam
 * @version 1
 */
 
class Galerie {
	
    use Hydrator;

    private $id;
    private $name;
	private $images;

    /**
     * Class constructor by hydratation
     * TODO: See to add some basic constructor
     * @param array $datas The datas to hydrate the class
     */
    public function __construct(array $datas) {
        $this->hydrate($datas);
    }

/* -------------------------------- Accessors ------------------------------- */
    public function getId() : int
    { return $this->id; }

    public function getName() : string
    { return $this->name;}
	
	public function getImages() : ?array
    { return $this->images;}

/* -------------------------------- Mutators -------------------------------- */
    protected function setId(int $val) {
        if($val > 0) {
            $this->id = $val;
        }
    }

    public function setName(string $val) {
        if(!empty($val)) {
            $this->name = htmlspecialchars($val);
        }
    }
	
	public function setImages(array $val) {
        if(!empty($val)) {
            $this->images = $val;
        }
    }


}