<?php

session_start();
if($_SESSION["user"]){
    $user = $_SESSION["user"];
}else{
    $_SESSION["flash"] = "Connectez vous pour acceder à cette page";
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <h1>DETAILS DU COMPTE</h1>
    <div> 
        <span>Nom : <?=$user->name?></span><br>
        <span>Email : <?=$user->email?></span><br>
        <span><a href="logout.php">Déconnexion</a></span>
    </div>
</body>
</html>