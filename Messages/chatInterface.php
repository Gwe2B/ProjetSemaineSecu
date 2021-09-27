<!DOCTYPE html>
<html lang="fr">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Document</title>

  <link rel="stylesheet" href="../dist/semantic/semantic.min.css">
  <link rel="stylesheet" href="style.css">
</head>

<body onload="loader()">
  <div class="menu">
    <div class="back"><i class="fa fa-chevron-left"></i> <img src="https://i.imgur.com/DY6gND0.png" draggable="false"/></div>
    <div class="name">TODO</div>
  </div>
  <input type="hidden" id="from" value="<?php echo intval($_GET['fromUser']); ?>">
  <input type="hidden" id="to" value="<?php echo intval($_GET['toUser']); ?>">
  <ol class="chat" id="chat">
  </ol>
  <textarea type="text" class="textarea" id="input" placeholder="Type here!"></textarea>
  <button onclick="sendMessage()" class="submit">Envoyer</button>
  <div class="emojis"></div>

  <script src="https://code.jquery.com/jquery-3.1.1.min.js"
    integrity="sha256-hVVnYaiADRTO2PzUGmuLJr8BLUSjGIZsDYGmIJLv2b8=" crossorigin="anonymous"></script>
  <script src="./dist/semantic/semantic.min.js"></script>
  <script src="main.js"></script>
</body>

</html>