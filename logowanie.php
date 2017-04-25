<?php


function __autoload($class_name) {
    include $class_name . '.php' ;
}
session_start();
$user = new Register_new;
?>


<!DOCTYPE HTML>
<html>
<head>
    <meta charset="utf-8">
    <title>Witaj w świecie czołgów</title>
    <link rel="Stylesheet" type="text/css" href="style.css" />
    <link href='https://fonts.googleapis.com/css?family=Economica:400,700&subset=latin,latin-ext' rel='stylesheet' type='text/css'>
</head>
<body>
<main>
    <header>
        <?php
        echo "Tankomania";
        ?>
    </header>

    <?php

    echo '<nav>';
    if ( ! $user->_is_logged() && !$user->isAdmin())
    {

        echo $user->_is_logged();
        $user->menuN();
        foreach ( $_SESSION as $key => $value )
            print '$_SESSION['.$key.'] = '.$value.'<br>' ;
        echo "Przykładowe konto do testów: test@o2.pl, test. Również każde inne konto z bazy nadaje się do testowania";
        echo '<form name="test" method="post">';
        echo 'Podaj e-mail:<input type="email" name="email"><br/>';
        echo 'Podaj hasło:<input type="password" name="haslo"><br/>';
        echo '<input type="submit" name = "zaloguj" value="Zaloguj">';
        echo '</form>';
    }
    else if($user->isAdmin()){
        $user->menuA();
    }
    else
    {
        $user->menuZ();
    }
    if(isset($_POST['zaloguj'])){
        $reg = new Register_new ;
        echo $reg->_login();
        $_SESSION['ok'] = 'ok';
        $_SESSION['zalogowany'] = true;
    }

    ?>
    <main>
</body>
</html>