<?php

require_once "controllers/Hydrator.php";

/**
 * User class based on the user table of the database
 * @author Wissam
 * @version 1
 */
class Image {
	
    use Hydrator;

    private const VISIBILITY = array(
        'private' => -1,
        'friend'  => 0,
        'public'  => 1
    );

    private $id;
    private $user_id;
    private $gallerie_id;
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

/* ------------------------------ class methods ----------------------------- */
    /**
     * Get a valid format for the visibility
     * @param string $visi The representative string of the visibility
     * @return int The corresponding integer or -1 if the value of $visi is
     *  invalid
     */
    public static function getVisibilityFromString(string $visi) : int {
        $result = -1;
        
        if(array_key_exists($visi, self::VISIBILITY)) {
            $result = self::VISIBILITY[$visi];
        }

        return $result;
    } 

/* -------------------------------- Accessors ------------------------------- */
    public function getId() : int
    { return $this->id; }

    public function getPath() : string
    { return $this->path;}
	
	public function getVisibility() : int
    { return $this->visibility;}

    public function getGallerie_Id() : int {
        return $this->gallerie_id;
    }

    public function getUser_Id() : int {
        return $this->user_id;
    }

/* -------------------------------- Mutators -------------------------------- */
    protected function setId(int $val) : void {
        if($val > 0) {
            $this->id = $val;
        }
    }

    public function setPath(string $val) : void {
        if(!empty($val)) {
            $this->path = htmlspecialchars($val);
        }
    }
	
	public function setVisibility(int $val) : void {
        if(in_array($val, self::VISIBILITY)) {
            $this->visibility = $val;
        }
    }

    public function setGallerie_Id(int $val) : void {
        if($val > 0) {
            $this->gallerie_id = $val;
        }
    }

    protected function setUser_Id(int $val) : void {
        if($val > 0) {
            $this->user_id = $val;
        }
    }
}