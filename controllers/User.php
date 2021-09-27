<?php

require_once "Hydrator.php";

/**
 * User class based on the user table of the database
 * @author Gwenaël
 * @version 3
 */
class User implements JsonSerializable {
    use Hydrator;
    const PHONE_REGEX = "#^[+]*[(]{0,1}[0-9]{1,4}[)]{0,1}[-\s\./0-9]*$#";

    private $id;
    private $nom;
    private $prenom;
    private $adresse = null;
    private $tel = null;
    private $mail;
    private $description = null;

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

    public function getNom() : string
    { return $this->nom;}

    public function getPrenom() : string
    { return $this->prenom; }

    public function getAdresse() : ?string 
    { return $this->adresse; }

    public function getTel() : ?string
    { return $this->tel; }

    public function getMail() : string
    { return $this->mail; }

    public function getDescription() : ?string
    { return $this->description; }

/* -------------------------------- Mutators -------------------------------- */
    protected function setId(int $val) {
        if($val > 0) {
            $this->id = $val;
        }
    }

    public function setNom(string $val) {
        if(!empty($val)) {
            $this->nom = htmlspecialchars($val);
        }
    }

    public function setPrenom(string $val) {
        if(!empty($val)) {
            $this->prenom = htmlspecialchars($val);
        }
    }

    public function setAdresse(?string $val) {
        if(!empty($val)) {
            $this->adresse = htmlspecialchars($val);
        }
    }

    public function setTel(?string $val) {
        if(!empty($val) & preg_match(self::PHONE_REGEX, $val)) {
            $this->tel = htmlspecialchars($val);
        } else {
            $this->tel = null;
        }
    }

    public function setMail(string $val) {
        if(filter_var($val, FILTER_VALIDATE_EMAIL)) {
            $this->mail = htmlspecialchars($val);
        }
    }

    public function setDescription(?string $val) {
        if(!empty($val)) {
            $this->description = htmlspecialchars($val);
        } else {
            $this->description = null;
        }
    }

/* ---------------------------- Instance methods ---------------------------- */
    /**
     * Serialize the class to put it in $_SESSION for example
     * @return array An array representing the class instance
     * @see https://www.php.net/manual/fr/language.oop5.magic.php#object.serialize
     */
    public function __serialize() : array {
        return array(
            'id' => $this->id,
            'nom' => $this->nom,
            'prenom' => $this->prenom,
            'adresse' => $this->adresse,
            'tel' => $this->tel,
            'mail' => $this->mail,
            'description' => $this->description
        );
    }

    /**
     * Unserialize the class and construct an instance from an array.
     * The opposite of `__serialize` function.
     * @param array $datas An array representing the instance.
     * @see https://www.php.net/manual/fr/language.oop5.magic.php#object.unserialize
     */
    public function __unserialize(array $datas) : void {
        $this->hydrate($datas);
    }

    /**
     * Permit to obtain a string representation of the instance.
     * TODO: Construct a string representation with the others.
     * @return string The human readable representation of the instance.
     * @see https://www.php.net/manual/fr/language.oop5.magic.php#object.toString
     */
    public function __toString() : string {
        return ucwords($this->prenom." ".$this->nom);
    }

/* ---------------------------- Redifined methods --------------------------- */
    public function JsonSerialize() : array {
        return array(
            'id' => $this->id,
            'name' => $this->nom.' '.$this->prenom,
            'mail' => $this->mail
        );
    }
}