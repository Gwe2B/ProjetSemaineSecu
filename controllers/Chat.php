<?php

/**
 * Instant messagerie system
 * @author Gwenaël
 * @version 1
 */
class Chat {
    private $expediteur;
    private $destinataire;
    private $db;

    public $usrMngr;

    public function __construct(int $fromUser, int $toUser, PDO $db) {
        if($toUser > 0) {
            $this->usrMngr = UserManager::getInstance($db);
            $this->db = $db;
            $this->expediteur = $fromUser;
            $this->destinataire = $toUser;
        }
    }

    public function getMessages() : array {
        $result = array();

        $query = $this->db->prepare(
            "SELECT `date`, contenue AS content, expediteur, destinataire
            FROM message
            WHERE
                (expediteur = ? AND destinataire = ?) OR
                (destinataire = ? AND expediteur = ?)
            ORDER BY `date` DESC
            LIMIT 20"
        );

        $query->execute(array(
            $this->expediteur, $this->destinataire,
            $this->expediteur, $this->destinataire
        ));
        $datas = $query->fetchall();
        $query->closeCursor();

        $datas = array_reverse($datas);
        foreach($datas as $mess) {
            $mess['expediteur'] = $this->usrMngr->getById($mess['expediteur']);
            $mess['destinataire'] = $this->usrMngr->getById($mess['destinataire']);
            array_push($result, new ChatMessage($mess));
        }

        return $result;
    }

    public function sendMessage(ChatMessage $message) : void {
        $query = $this->db->prepare(
            "INSERT INTO message(expediteur, destinataire, contenue) VALUE(?,?,?)"
        );
        $query->execute(array(
            $message->getExpediteur()->getId(),
            $message->getDestinataire()->getId(),
            $message->getContent()
        ));
        $query->closeCursor();
    }
}