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
            }
            else if($user->isAdmin()){
                $user->menuA();
            }
            else
            {
                $user->menuZ();
            }
            $user->dodajZaloganta();
    ?>

    </main>
</body>
</html>