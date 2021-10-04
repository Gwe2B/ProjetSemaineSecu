<?php

require_once "controllers/Hydrator.php";

/**
 * User class based on the user table of the database
 * @author Wissam
 * @version 1
 */
class Image {
	
    use Hydrator;

    private $id;
    private $path;
	private $visibility;
	

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

    public function getPath() : string
    { return $this->path;}
	
	public function getVisibility() : int
    { return $this->visibility;}

/* -------------------------------- Mutators -------------------------------- */
    protected function setId(int $val) {
        if($val > 0) {
            $this->id = $val;
        }
    }

    public function setPath(string $val) {
        if(!empty($val)) {
            $this->path = htmlspecialchars($val);
        }
    }
	
	protected function setVisibility(int $val) {
        if($val==0 or $val==1) {
            $this->visibility = $val;
        }
    }


}