<?php
require_once ROOT."controllers".DS."User.php";
require_once ROOT."controllers".DS."UserManager.php";

$um = UserManager::getInstance($db);

	$pageContent = array(
    'friends'=> $um->getFriendsByUserId($user->getId()),
	'utilisateurs'=> $um->getNoneFriendsUsers($user->getId()),
);

// if(isset($_POST['mail']) && !empty($_POST['mail'])) {
		
		// $mail = $_POST['mail'];

        // $users = $um->getUsersByMail($user, $mail);
		// var_dump($users);
		
		// array_push($pageContent,['utilisateurs' => $users]);
		
		//echo "<script>parent.window.location.reload();</script>";
        
    // }
	
//var_dump($um->getNoneFriendsUsers($user->getId()));
//var_dump($um->getFriendsByUserId($user->getId()));