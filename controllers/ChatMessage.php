<?php

require_once "Hydrator.php";

/**
 * A instant message
 * @author Gwenaël
 * @version 1
 */
class ChatMessage implements JsonSerializable {
    use Hydrator;

    private $expediteur;
    private $destinataire;
    private $date;
    private $content;

    public function __construct(array $datas) {
        $this->hydrate($datas);
    }

/* -------------------------- Accessors & mutators -------------------------- */
    public function setExpediteur(User $user) : void {
        $this->expediteur = $user;
    }

    public function setDestinataire(User $user) : void {
        $this->destinataire = $user;
    }

    public function setDate(string $date) : void {
        if(!empty($date)) {
            $this->date = date("d/m/Y à H:i", strtotime($date));
        }
    }

    public function setContent(string $content) : void {
        if(!empty($content)) {
            $this->content = htmlspecialchars($content);
        }
    }

    public function getExpediteur() : User {
        return $this->expediteur;
    }

    public function getDestinataire() : User {
        return $this->destinataire;
    }

    public function getDate() : string {
        return $this->date;
    }

    public function getContent() : string {
        return $this->content;
    }

/* --------------------------- Surdefined methods --------------------------- */
    public function JsonSerialize() : array {
        return array(
            'user' => $this->expediteur,
            'date' => $this->date,
            'content' => $this->content
        );
    }
}