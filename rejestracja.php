<?php
    function __autoload($class_name) {
        include $class_name . '.php' ;
    }
    session_start();
    $user = new Register_new;
    if(isset($_POST['email'])){
        $reg = new Register_new ;
        $reg->_read();
        if($_POST['haslo1']==$_POST['haslo2']){
            $_SESSION['ok'] = 'ok';
        }
        else{
            $_SESSION['errorHaslo'] = "Podane hasła nie są takie same";
        }
    }

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
            if (! $user->_is_logged() && !$user->isAdmin())
            {
                echo $user->_is_logged();
                $user->menuN();
                //foreach ( $_SESSION as $key => $value )
                //    print '$_SESSION['.$key.'] = '.$value.'<br>' ;

                echo "<form class='formularz' method='post'>";
                echo "Podaj nick:<input type='text' name='nick'><br/>";
                echo "Podaj e-mail:<input type='email' name='email'><br/>";
                echo "Podaj hasło:<input type='password' name='haslo1'><br/>";

                    if(isset($_SESSION['errorHaslo'])){
                        echo '<div class="error">'.$_SESSION['errorHaslo'].'</div>';
                        unset($_SESSION['errorHaslo']);
                    }

                echo "Powtórz hasło:<input type='password' name='haslo2'><br/>";
                echo "<input type='submit' value='Zarejestruj'>";
                echo "</form>";

                if(isset($_SESSION['ok'])){
                    echo $reg->_save();
                    $_SESSION = array() ;
                    session_destroy();
                }
            }
            else if($user->isAdmin()){
                $user->menuA();
            }
            else
            {
                $user->menuZ();
            }

        ?>

    </main>
</body>
</html>