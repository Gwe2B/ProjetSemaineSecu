<?php

function checkCapctha(string $token, string $privateKey) : bool {
    $result = false;
    $response = file_get_contents('https://www.google.com/recaptcha/api/siteverify?secret='.$privateKey.'&response='.$token);
    $response = json_decode($response, true);
    var_dump($response);

    if(!empty($response)) {
        $result = $response['success'];
    }

    return $result;
}