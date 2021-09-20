<?php

/**
 * User class based on the user table of the database
 * TODO: Add __serialize & __unserialize
 * @author Gwenaël
 * @version 1
 */
class User {
    const PHONE_REGEX = "#^[+]*[(]{0,1}[0-9]{1,4}[)]{0,1}[-\s\./0-9]*$#";

    private int $id;
    private string $nom;
    private string $prenom;
    private ?string $adresse;
    private ?string $tel;
    private string $mail;
    private ?string $description;

    /**
     * Class constructor by hydratation
     * TODO: See to add another basic constructor
     * @param array $datas The datas to hydrate the class
     */
    public function __construct(array $datas) {
        foreach($datas as $key=>$val) {
            $methodName = "set".ucwords($key);
            if(method_exists($this, $methodName)) {
                $this->$methodName($val);
            }
        }
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
}