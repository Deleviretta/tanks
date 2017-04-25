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
            function __autoload($class_name) {
                include $class_name . '.php' ;
            }
            session_start();
            $user = new Register_new;
            echo '<nav>';
            if (! $user->_is_logged() && !$user->isAdmin())
            {
                echo $user->_is_logged();
                $user->menuN();
                foreach ( $_SESSION as $key => $value )
                    print '$_SESSION['.$key.'] = '.$value.'<br>' ;
                echo "<form name='szukaj' action='uzytkownicy.php' method='get'>";
                echo "Podaj nick użytkownika:<input type='search' name='Nick'><br>";
                echo '<input type="submit" name = "szukaj" value="Wyszukaj">';
                echo '</form>';
                $user->displayUserStats();
            }
            else if($user->isAdmin()){
                $user->menuA();
            }
            else {
                $user->menuZ();
                echo "<form name='szukaj' action='uzytkownicy.php' method='get'>";
                echo "Podaj nick użytkownika:<input type='search' name='Nick'><br>";
                echo '<input type="submit" name = "szukaj" value="Wyszukaj">';
                echo '</form>';
                $user->displayUserStats();
            }

    ?>

    </main>

</body>
</html>