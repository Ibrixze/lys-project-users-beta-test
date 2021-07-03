<?php
session_start();
function generate_token($length){
    $letters = "0123456789azertyuiopqsdfghjklmwxcvbnAZERTYUIOPQSDFGHJKLMWXCVBN";
    return substr(str_shuffle(str_repeat($letters, $length)), 0, $length);
}

try{
    $pdo = new PDO("mysql:host=localhost;dbname=lys;charset=utf8","root","");
}catch(Exception $e){
    Die("Message d'erreur ".$e->getMessage());
}

$errors = [];

if(isset($_POST["send"])){

    if(isset($_POST['first_name']) && isset($_POST["last_name"]) && !empty($_POST["last_name"]) && !empty($_POST["last_name"])){
        $name = htmlspecialchars($_POST['first_name'])." ".htmlspecialchars($_POST["last_name"]);
    }else{
        $errors["name"] = "Remplissez correctement les champs First Name et Last Name";
    }
    if(isset($_POST['email']) && !empty($_POST["email"])){
        if(filter_var($_POST["email"], FILTER_VALIDATE_EMAIL)){
            $email = htmlspecialchars($_POST['email']);
        }else{
            $errors["email"] = "Entrez une adresse email correcte";
        }
    }else{
        $errors["email"] = "Renseigner correctement l'email";
    }
    if(isset($_POST['password']) && !empty($_POST["password"])){
        $password = htmlspecialchars($_POST['password']);
        $password = password_hash($password, PASSWORD_BCRYPT);
    }else{
        $errors["password"] = "Renseigner correctement le mot de passe";
    }
    if(isset($_POST['telephone']) && !empty($_POST["telephone"]) && strlen($_POST["telephone"]) == 10){
        $telephone = htmlspecialchars($_POST['telephone']);
    }else{
        $errors["telephone"] = "Renseigner correctement le votre numero de téléphone";
    }
    if(isset($_POST['birth']) && !empty($_POST["birth"])){
        $birth = htmlspecialchars($_POST['birth']);
    }else{
        $errors["date"] = "Choisissez votre annee de naissance";
    }
    if(isset($_POST['gender']) && !empty($_POST["gender"])){
        $gender = strtoupper(htmlspecialchars($_POST['gender']));
    }else{
        $errors["gender"] = "Choisissez votre genre";
    }
    if(empty($errors)){
        $token = generate_token(60);
        $prepare = $pdo->prepare("INSERT INTO users(name, email, telephone, password, confirmation_token, birth, gender) VALUES(?, ?, ?, ?, ?, ?, ?)");
        $reponse = $prepare->execute(array($name, $email, $telephone, $password, $token, $birth, $gender));
        $user_id = $pdo->lastInsertId();
        mail($email, "Confirmation de votre compte", "Pour activer votre compte vous devez cliquer sur ce lien http://localhost/lys%20project/confirm.php?token=$token&id=$user_id");
        $_SESSION["flash"] = "Un e-amil vous a été envoyé afin que vous puissiez activer votre compte";
        header("Location:login.php");
    }
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
    <h1>INSCRIPTION</h1>
    <form action="" method="post">
        <input type="text" name="first_name" placeholder="First name"/><br><br>
        <input type="text" name="last_name" placeholder="Last name"/><br><br>
        <input type="email" name="email" placeholder="email"/><br><br>
        <input type="password" name="password" placeholder="password"/><br><br>
        <input type="text" name="telephone" placeholder="telephone"/><br><br>
        <select name="gender">
            <option value="H">Homme</option>
            <option value="F">Femme</option>
        </select><br><br>
        <input type="date" name="birth"/><br><br>
        <button type="submit" name="send">Envoyer</button>
    </form>
    <ul>
       <?php foreach($errors as $error):?>
        <li><?=$error?></li>
       <?php endforeach ?> 
    </ul>
</body>
</html>