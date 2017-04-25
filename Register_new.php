<?php

/**
 * Klasa służaca do operacji na bazie
 */
class Register_new extends Register
{

    /**
     * Zmienne przechowujące informacje o połączeniu
     */
    $plik = fopen('config','r');

    $config=array();
    while(!feof($plik))
    {
	$linia = fgets($plik);
   	array_push($config, $linia);		
    }
     
    private $host = $config[0];
    private $base = $config[1];
    private $port = $config[2];
    private $credenials = $config[3];
    private $conn;

    function __construct()
    {
        parent::__construct();
        $this->conn = pg_connect("$this->host $this->port $this->base $this->credenials");
        if (!($this->conn)) {
            //echo "Error : Unable to open database\n";
        } else {
            //echo "Opened database successfully\n";
        }
    }


    /**
     * Funkcja do pobierania z bazy id zalogowanego uzytkownika
     * @return mixed id zalogowanego użytkownika
     */

    private function getIdU(){
        $user = $_SESSION['user']; //email
        $sql = "SELECT * FROM getIDUzytkownika($1);";
        pg_prepare($this->conn,"idU", $sql);
        $result = pg_execute($this->conn,"idU", array($user));
        $idU = pg_fetch_row($result);
        return $idU[0];
    }

    /**
     * Metoda do wyswietlanis informacji o koncie i osiągnięciach zalogowanego uzytkownika
     */
    function getOsiagniecia(){
        $idU = $this->getIdU();
        $sql = "SELECT * FROM getOsiagniecia($1);";
        pg_prepare($this->conn,"osiagniecia", $sql);
        $result = pg_execute($this->conn,"osiagniecia", array($idU));
        $row = pg_fetch_row($result);
        $sql = "SELECT * FROM getUserName($1);";
        pg_prepare($this->conn,"nick", $sql);
        $result = pg_execute($this->conn,"nick", array($idU));
        $nick = pg_fetch_row($result);
        echo "Witaj $nick[0]<br>";
        echo "Statystyki Twojego konta:<br>";
        echo "<table>";
        echo '<tr><td>Ilość bitew</td><td>Zadane obrażenia</td><td>Obrażenia przyjęte</td><td>Zniszczone czołgi</td>
                    <td>Wyspotowane czołgi</td><td>Doświadczenie za bitwy</td><td>Zwycięstwa</td><td>Przegrane</td>
                    <td>Remisy</td></tr>';
        echo "<tr>";
        foreach($row as $elem) {
            echo "<td>";
            echo $elem;
            echo "</td>";
        }
        echo "</tr></table><br>";
        echo "Informacje o ekonomii konta: <br>";
        $sql = "SELECT * FROM getZasoby($1);";
        pg_prepare($this->conn,"zasoby", $sql);
        $result = pg_execute($this->conn,"zasoby", array($idU));
        $row = pg_fetch_row($result);
        echo "<table>";
        echo '<tr><td>Ban</td><td>Czas konta premium</td><td>Dostępne złoto</td><td>Dostępne kredyty</td>
                    <td>Dostępne doświadczenie</td></tr>';
        echo "<tr>";
        if(empty($row[0])){
            echo "<td>brak</td>";
        }
        else{
            echo "<td>";
            echo $row[0];
            echo "</td>";
        }
        for($i=1; $i<count($row); $i++) {
            echo "<td>";
            echo $row[$i];
            echo "</td>";
        }
        echo "</tr></table><br>";
    }

    /**
     * Metoda pozwalająca na wyświelenie statystyk użytkownika po wyszukania jego nick w wyszukiwarce
     */
    function displayUserStats(){
        if(isset($_GET["Nick"])){
            $nick = $_GET["Nick"];
            $sql = "SELECT * FROM getIDU($1);";
            pg_prepare($this->conn,"IDU", $sql);
            $result = pg_execute($this->conn,"IDU", array($nick));
            $idU = pg_fetch_row($result);
            $sql = "SELECT * FROM getOsiagniecia($1);";
            pg_prepare($this->conn,"osiagniecia", $sql);
            $result = pg_execute($this->conn,"osiagniecia", array($idU[0]));
            $osiagniecia = pg_fetch_row($result);
            echo "<table>";
            echo '<tr><td>Nick</td><td>Ilość bitew</td><td>Zadane obrażenia</td><td>Obrażenia przyjęte</td><td>Zniszczone czołgi</td>
                    <td>Wyspotowane czołgi</td><td>Doświadczenie za bitwy</td><td>Zwycięstwa</td><td>Przegrane</td>
                    <td>Remisy</td></tr>';
            echo "<tr>";
            echo "<td>$nick</td>";
            foreach ($osiagniecia as $elem){
                echo "<td>$elem</td>";
            }
            $sql = "SELECT * FROM countTanks($1);";
            pg_prepare($this->conn,"countT", $sql);
            $result = pg_execute($this->conn,"countT", array($idU[0]));
            $czolgi = pg_fetch_row($result);
            echo "Czołgi w garażu: ".$czolgi[0]."\r\n";
            $sql = "SELECT * FROM maxTier($1);";
            pg_prepare($this->conn,"maxT", $sql);
            $result = pg_execute($this->conn,"maxT", array($idU[0]));
            $maxT = pg_fetch_row($result);
            if(empty($maxT[0])){
                $maxT = "Brak czołgów w garażu\r\n";
            }
            else{
                $maxT = $maxT[0];
            }
            echo "Max tier: ".$maxT."\r\n";
            $sql = "SELECT * FROM getClanName1($1);";
            pg_prepare($this->conn,"clan", $sql);
            $result = pg_execute($this->conn,"clan", array($idU[0]));
            $klan = pg_fetch_row($result);
            if(empty($klan[0])){
                $klan = "Uzytkownik nie posiada klanu\r\n";
            }
            else{
                $klan = $klan[0];
            }
            echo "Nazwa klanu: ". $klan."\r\n";
        }
    }

    /**
     * Metoda wyświetlająca dane na stronie statystyki.php, obsługuje zapytania zwracające ogólne informacje o bazie
     */

    function stats(){
        $res = pg_query("SELECT * FROM uzytkownicy");
        $uN = pg_fetch_row($res);
        echo "W bazie znajduje się: $uN[0] użytkowników<br>";
        $res = pg_query("SELECT * FROM klanyIlosc");
        $kN = pg_fetch_row($res);
        echo "W bazie znajduje się: $kN[0] klanów<br>";
        $res = pg_query("SELECT * FROM sumaObrazen");
        $ob = pg_fetch_row($res);
        echo "Użytkownicy zadali łącznie: $ob[0] punktów obrażeń <br>";
        $res = pg_query("SELECT * FROM najDzialo");
        echo "Działo z największą ilością obrażeń: ";
        while($ob = pg_fetch_row($res)){
            foreach($ob as $elem)
                echo "$elem     \r\t";
        }
        echo "<br>";
        $res = pg_query("SELECT * FROM minDzialo");
        echo "Działo z najmniejszą ilością obrażeń: ";
        while($ob = pg_fetch_row($res)){
            foreach($ob as $elem)
                echo "$elem     \r\t";
        }
        echo "<br>";
    }

    /**
     * Metoda obsługująca formularze pozwalające na dodanie czołgu, silnika, działa i załoganta do bazy
     */
    function dodajAdmin(){
        if($this->isAdmin()){
            /****** CZOŁG ******/
            echo "Dodaj czołg do bazy<br>";
            echo '<form name="czolg" id="czolg" method="get" action="czolgBaza.php">';
            $result = pg_query($this->conn, "SELECT * FROM engineName");
            echo 'Silnik: <select name="silnik">';
            while($row = pg_fetch_row($result)) {
                echo "<option id='silnik' value = '$row[0]'>$row[0]</option>";
            }
            echo '</select><br>';
            $result = pg_query($this->conn, "SELECT * FROM gunName");
            echo 'Dzialo: <select name="dzialo">';
            while($row = pg_fetch_row($result)) {
                echo "<option id='dzialo' value = '$row[0]'>$row[0]</option>";
            }
            echo '</select><br>';
            echo 'Tier: <select name="tier">';
            for($i=1; $i<=10; $i++){
                echo "Tier:<option id='tier' value = '$i'>$i</option>";
            }
            echo '</select><br>';
            echo 'Nazwa czołgu:<input type="text" name="nazwa"/><br>';
            echo 'Pancerz:<input type="text" name="pancerz"/>mm<br>';
            echo 'Szybkość:<input type="text" name="szybkosc"/>km/h<br>';
            echo "Wytrzymałość:<input type='text' name='wytrzymalosc'/><br>";
            echo "Zasięg widzenia:<input type='text' name='zasieg'/>metrów<br>";
            echo 'Waga:<input type="text" name="waga"/>ton<br>';
            echo 'Koszt doświadczenia:<input type="text" name="doswiadczenie"/><br>';
            echo 'Koszt kredytów:<input type="text" name="kredyty"/><br>';
            echo 'Obrazek(url): <input type="url" name="obrazek"/><br>';
            $result = pg_query($this->conn, "SELECT * FROM zaloganciV");
            echo 'Załogant: <select name="zaloga">';
            while($row = pg_fetch_row($result)) {
                echo "<option id='zaloga' value = '$row[0]'>$row[1] $row[2] $row[3]</option>";
            }
            echo '</select><br>';
            echo "<input type='submit' name='kup' value='Dodaj czołg'/>";
            echo '</form>';
            /****** SILNIK ******/
            echo "Dodaj silnik do bazy<br>";
            echo '<form name="silnik" id="silnik" method="get" action="silnikBaza.php">';
            echo 'Moc:<input type="text" name="moc"/>KM<br>';
            echo 'Szansa na zapalenie:<input type="text" name="szansa"/>%<br>';
            echo 'Nazwa silnika:<input type="text" name="nazwa"/><br>';
            echo "<input type='submit' name='kup' value='Dodaj silnik'/>";
            echo '</form>';
            /****** DZIAŁO ******/
            echo "Dodaj działo do bazy<br>";
            echo '<form name="dzialo" id="dzialo" method="get" action="dzialoBaza.php">';
            echo 'Nazwa działa:<input type="text" name="nazwa"/><br>';
            echo 'Kaliber:<input type="text" name="kaliber"/>mm<br>';
            echo 'Zadawane obrażenia:<input type="text" name="obrazenia"/><br>';
            echo 'Czas przeładowania:<input type="text" name="czas"/>s<br>';
            echo "<input type='submit' name='kup' value='Dodaj działo'/>";
            echo '</form>';
            /****** ZAŁOGANCI *****/
            echo "Dodaj załoganta do bazy<br>";
            echo '<form name="zalogant" id="zalogant" method="get" action="zalogantBaza.php">';
            echo 'Imie:<input type="text" name="imie"/><br>';
            echo 'Nazwisko:<input type="text" name="nazwisko"/><br>';
            echo 'Funkcja: ';
            $result = pg_query($this->conn, "SELECT * FROM role");
            echo '<select name="funkcja">';
            while($row=pg_fetch_row($result)) {
                echo "<option id='funkcja' value = '$row[0]'>$row[0]</option>";
            }
            echo '</select><br>';
            echo "<input type='submit' name='kup' value='Dodaj zaloganta'/>";
            echo '</form>';
        }else{
            header('Location:index.php');
        }

    }
    /**
     * Metoda odpowiadająca za dodanie rekordu(czołgu) do bazy dla danych wprowadzonych przez użytkownika
     */
    function dodajCzolg(){
        if($this->isAdmin()& isset($_GET)){
            $silnik = $_GET["silnik"]; //nazwa
            $sql = "SELECT * FROM getEngineID($1);";
            pg_prepare($this->conn,"silnik", $sql);
            $result = pg_execute($this->conn,"silnik", array($silnik));
            $silnik = pg_fetch_row($result);
            $dzialo =$_GET["dzialo"]; //nazwa
            $sql = "SELECT * FROM getGunID($1);";
            pg_prepare($this->conn,"dzialo", $sql);
            $result = pg_execute($this->conn,"dzialo", array($dzialo));
            $dzialo = pg_fetch_row($result);
            $tier = $_GET["tier"];
            $nazwa = $_GET["nazwa"];
            $pancerz = $_GET["pancerz"];
            $szybkosc = $_GET["szybkosc"];
            $wytrzymalosc = $_GET["wytrzymalosc"];
            $zasieg = $_GET["zasieg"];
            $waga = $_GET["waga"];
            $doswiadczenie = $_GET["doswiadczenie"];
            $kredyty = $_GET["kredyty"];
            $obrazek = $_GET["obrazek"];
            $zalogant = $_GET["zaloga"];

            pg_query("BEGIN");
            $res1 = pg_query("INSERT INTO Czolg(IDSilnika, IDDziala, IDZaloganta, Tier, NazwaCzolgu, Pancerz, Szybkosc,
                  Wytrzymalosc, ZasiegWidzenia, Waga, KosztDoswiadczenia, KosztKredyty, Obrazek) 
                  VALUES($silnik[0], $dzialo[0], $zalogant ,$tier, '$nazwa', $pancerz, $szybkosc, $wytrzymalosc, $zasieg, $waga,
                  $doswiadczenie, $kredyty, '$obrazek')");
            if($res1){
                echo "Czołg dodał się poprawnie";
                pg_query("COMMIT") or die("Transaction commit failed\n");
            }else{
                echo "Coś się nie powiodło, rekord nie dodał się do bazy. 
                 Wpisałeś prawdopodobnie niepoprawne wartości, spróbuj jeszcze raz";
                pg_query("ROLLBACK") or die("Transaction rollback failed\n");
            }

        }else{
            header('Location:index.php');
        }

    }
    /**
     * Metoda odpowiadająca za dodanie rekordu(silnika) do bazy dla danych wprowadzonych przez użytkownika
     */
    function dodajSilnik(){
        if($this->isAdmin()& isset($_GET)){
            $moc = $_GET["moc"];
            $szansa = $_GET["szansa"];
            $nazwa = $_GET["nazwa"];
            pg_query("BEGIN");
            $res1 = pg_query("INSERT INTO Silnik(Moc, SzansaNaZapalenie, NazwaSilnika) 
                  VALUES($moc, $szansa, '$nazwa')");
            if($res1){
                echo "Silnik dodał się poprawnie";
                pg_query("COMMIT") or die("Transaction commit failed\n");
            }else{
                echo "Coś się nie powiodło, rekord nie dodał się do bazy. 
                 Wpisałeś prawdopodobnie niepoprawne wartości, spróbuj jeszcze raz";
                pg_query("ROLLBACK") or die("Transaction rollback failed\n");
            }

        }else{
            header('Location:index.php');
        }


}
    /**
     * Metoda odpowiadająca za dodanie rekordu(działa) do bazy dla danych wprowadzonych przez użytkownika
     */
    function dodajDzialo(){
        if($this->isAdmin()& isset($_GET)){
            $kaliber = $_GET["kaliber"];
            $obrazenia = $_GET["obrazenia"];
            $nazwa = $_GET["nazwa"];
            $czas = $_GET["czas"];
            pg_query("BEGIN");
            $res1 = pg_query("INSERT INTO Dzialo(NazwaDziala, Kaliber, ZadawaneObrazenia, CzasPrzeladowania) 
                  VALUES('$nazwa', $kaliber, $obrazenia, $czas)");
            if($res1){
                echo "Silnik dodał się poprawnie";
                pg_query("COMMIT") or die("Transaction commit failed\n");
            }else{
                echo "Coś się nie powiodło, rekord nie dodał się do bazy. 
                 Wpisałeś prawdopodobnie niepoprawne wartości, spróbuj jeszcze raz";
                pg_query("ROLLBACK") or die("Transaction rollback failed\n");
            }

        }else{
            header('Location:index.php');
        }
    }
    /**
     * Metoda odpowiadająca za dodanie rekordu(załoganta) do bazy dla danych wprowadzonych przez użytkownika
     */
    function dodajZaloganta(){
        if($this->isAdmin()& isset($_GET)){
            $imie = $_GET["imie"];
            $nazwisko = $_GET["nazwisko"];
            $funkcja = $_GET["funkcja"];
            $sql = "SELECT * FROM getRolaID($1);";
            pg_prepare($this->conn,"funkcja", $sql);
            $result = pg_execute($this->conn,"funkcja", array($funkcja));
            $funkcja = pg_fetch_row($result);
            pg_query("BEGIN");
            $res1 = pg_query("INSERT INTO Zalogant(Imie, Nazwisko, Rola) VALUES('$imie', '$nazwisko', $funkcja[0])");
            if($res1){
                echo "Załogant dodał się poprawnie";
                pg_query("COMMIT") or die("Transaction commit failed\n");
            }else{
                echo "Coś się nie powiodło, rekord nie dodał się do bazy. 
                 Wpisałeś prawdopodobnie niepoprawne wartości, spróbuj jeszcze raz";
                pg_query("ROLLBACK") or die("Transaction rollback failed\n");
            }

        }else{
            header('Location:index.php');
        }
    }


    /**
     * ----------------------------Sekcja metod o czolgach -------------------------------------------------
     */


    /**
     * Metoda odpowiadająca za wyświetlenie tabeli z czołgami dostępnymi w bazie
     */
    function displayTanks(){
        $result = pg_query($this->conn, "SELECT * FROM displayTanks");
        echo "<table>";
        echo '<tr><td>Nazwa czolgu</td><td>Tier</td>';
        while($row = pg_fetch_row($result)){
            $var = '"czolg.php?key='. $row[0] .'"';
            echo "<tr>";
            echo "<td id='$row[0]'>". "<a href=$var>". $row[0] ."</a>". "</td>";
            echo "<td>". $row[1] ."</td>";
            echo "</tr>";
        }
    }

    /**
     * Metoda pozwalająca na wyświetlenie informacji o czołgu wybranym przez użytkownika
     */
    function getTankInfo()
    {
        if(isset($_GET["key"])){
            $tankName = $_GET["key"];
        }
        else{
            $tankName = "MS-1";
        }
        //informacje o czolgach
        $sql = "SELECT * FROM getTankInfo($1);";
        pg_prepare($this->conn,"czolg", $sql);
        $tank = pg_execute($this->conn,"czolg", array($tankName));
        $row = pg_fetch_row($tank);
        $link = $row[count($row)-1];
        echo "Nazwa czołgu: ".$tankName;
        echo "<img src="."'".$link."'". "alt='tank' height='200' width='200'><br>";
        echo "<table>";
        echo '<tr><td>Tier</td><td>Pancerz</td><td>Szybkość</td><td>HP</td><td>Spot</td>
            <td>Waga</td><td>Doświadczenie</td><td>Kredyty</td><td>Silnik</td><td>Działo</td><td>Załogant</td></tr>';
        echo "<tr>";
        for ($x=0; $x<count($row)-1; $x++ ) {
            echo "<td>$row[$x] </td>";
        }
        //dziala i silniki - pobranie ID
        $sql = "SELECT * FROM getEngineGunCrewID($1);";
        pg_prepare($this->conn,"sd", $sql);
        $sd = pg_execute($this->conn,"sd", array($tankName));
        $row = pg_fetch_row($sd);
        $idS = $row[0];
        $idD = $row[1];
        $idZ = $row[2];
        //pobranie nazwy silnika
        $sql = "SELECT * FROM getEngineName($1);";
        pg_prepare($this->conn,"silnik", $sql);
        $silnik = pg_execute($this->conn,"silnik", array($idS));
        $row = pg_fetch_row($silnik);
        $linkS = '"silnik.php?key='. $row[0] .'"';
        echo "<td id='$row[0]' >". "<a href=$linkS>". $row[0] ."</a>". "</td>";
        //pobranie nazwy dziala
        $sql = "SELECT * FROM getGunName($1);";
        pg_prepare($this->conn,"dzialo", $sql);
        $dzialo = pg_execute($this->conn,"dzialo", array($idD));
        $row = pg_fetch_row($dzialo);
        $linkD = '"dzialo.php?key='. $row[0] .'"';
        echo "<td id='$row[0]' >". "<a href=$linkD>". $row[0] ."</a>". "</td>";
        //pobranie danych o zalogacie
        $sql = "SELECT * FROM getCrewName($1);";
        pg_prepare($this->conn,"zalogant", $sql);
        $zalogant = pg_execute($this->conn,"zalogant", array($idZ));
        $row = pg_fetch_row($zalogant);
        echo "<td id='$row[0]' > $row[0] $row[1] $row[2] </td>";
        echo "</tr>";
        echo "</table>";
    }

    /**
     * Metoda pozwalająca na wyświetlenie informacji o silniku wybranym przez użytkownika
     */
    function getEngineInfo(){
        if(isset($_GET["key"])){
            $en = $_GET["key"];
        }
        else{
            //$engine = "Maybach HL 230 TRM P45";
            header('Location:index.php');
        }
        $sql = "SELECT * FROM getEngineInfo($1);";
        pg_prepare($this->conn,"engine", $sql);
        $engine = pg_execute($this->conn,"engine", array($en));
        $row = pg_fetch_row($engine);
        echo "<table>";
        echo "<tr><td>Moc</td><td>$row[0]</td></tr><tr><td>Szansa na zapalenie</td><td>$row[1]</td></tr><tr>
                <td>Nazwa silnika</td><td>$en</td></tr>";
        echo "</table>";
    }

    /**
     * Metoda pozwalająca na wyświetlenie informacji o dziale wybranym przez użytkownika
     */
    function getGunInfo(){
        if(isset($_GET["key"])){
            $gunN = $_GET["key"];
        }
        else{
            //$gun = "12,8 cm Kw.K. 44 L/55";
            header('Location:index.php');
        }
        $sql = "SELECT * FROM getGunInfo($1);";
        pg_prepare($this->conn,"gun", $sql);
        $gun = pg_execute($this->conn,"gun", array($gunN));
        $row = pg_fetch_row($gun);
        echo "<table>";
        echo "<tr><td>Nazwa</td><td>$gunN</td></tr><tr><td>Kaliber</td><td>$row[0]</td></tr><tr>
                <td>Obrażenia</td><td>$row[1]</td></tr><tr><td>Czas przeładowania</td><td>$row[2]</td></tr>";
        echo "</table>";
    }

    /**
     * Metoda służąca do wyświetlenia czołgów, które użytkownik posiada w garażu, za pomocą wyświelanych przez nią pól
     * można dokonać zakupu oraz sprzedaży czołgu
     */
    function displayMyTanks(){
        echo "<script type=\"text/javascript\">
            function getId(s){
                var id = s[s.selectedIndex].id;
                window.location.href='mojeczolgi.php?key='+id;
            }
            
            function check(){
                var doc = document.getElementById('wartosc');
                var suma = Number(doc.elements['pancerz'].value)+Number(doc.elements['wytrzymalosc'].value) +
                            Number(doc.elements['zasieg'].value);
                if(suma==60){  
                    return true;
                }else{
                    alert('Wartości nie sumują się do 60');
                    return false;
                }
            }
        </script> ";
        if($this->_is_logged()) {
            $idU = $this->getIdU();
            $sql = "SELECT * FROM getTanksGarage($1);";
            pg_prepare($this->conn, "garaz", $sql);
            $res = pg_execute($this->conn, "garaz", array($idU));
            $garaz = pg_fetch_row($res);
            $sql = "SELECT * FROM getTankName($1);";
            pg_prepare($this->conn, "czolg", $sql);
            $result = pg_execute($this->conn, "czolg", array($garaz[0]));
            $nazwa = pg_fetch_row($result);
            if (!empty($garaz[0])) {
                echo "<table>";
                echo '<tr><td>Nazwa</td><td>Dodatkowy pancerz</td><td>Dodatkowa wytrzymałość</td><td>Dodatkowy zasięg</td></tr>';
                echo "<tr>";
                echo "<td>$nazwa[0]</td>";
                echo "<td>$garaz[1]</td>";
                echo "<td>$garaz[2]</td>";
                echo "<td>$garaz[3]</td>";
                $var = '"sprzedaj.php?idC='.$garaz[0].'"';
                echo "<td><input type=button onclick='location.href=$var;' value='Sprzedaj czołg'/></td>";
                //echo "<br>";
                echo "</tr>";
                while($garaz = pg_fetch_row($res)) {
                    $result = pg_execute($this->conn, "czolg", array($garaz[0]));
                    $nazwa = pg_fetch_row($result);
                    echo "<tr><td>$nazwa[0]</td>";
                    $var = "";
                    for ($i=1; $i<count($garaz); $i++) {
                        echo "<td>$garaz[$i]</td>";
                    }
                    $var = '"sprzedaj.php?idC='.$garaz[0].'"';
                    echo "<td><input type=button onclick='location.href=$var;' value='Sprzedaj czołg'/></td>";
                }
                echo "</table></tr>";
            }else {
                echo "Brak czolgow w garazu";
            }
            $sql = "SELECT * FROM tanksToBuy($1);";
            pg_prepare($this->conn, "kup", $sql);
            $sql = "SELECT * FROM creditsAndExpU($1);";
            pg_prepare($this->conn, "kdU", $sql);
            $res = pg_execute($this->conn, "kdU", array($idU));
            $kontoU = pg_fetch_row($res);
            $kredytyU = $kontoU[0];
            $doswiadczenieU = $kontoU[1];
            $result = pg_execute($this->conn, "kup", array($idU));
            $var = "kup.php";
            echo "<br>Kup czołg<br>";
            echo '<select onchange="getId(this)">';
            while($doKupienia = pg_fetch_row($result)){
                if(($kredytyU>=$doKupienia[2])&($doswiadczenieU>=$doKupienia[3])) {
                    echo "<option value='$doKupienia[1]' id='$doKupienia[0]' onclick='getID(this.id)'>$doKupienia[1]</option>";
                }
            }
            echo '</select><br>';
            if(isset($_GET["key"])){
                echo "Zanim dokonasz zakupu możesz rozdać 60 puntków rozdzielone między pancerz, wytrzymałość oraz zasięg 
                widzenia czołgu<br>";
                echo '<form name="wartosci" id="wartosc" method="get" action="zakup.php">';
                echo '<input type="hidden" name="czolg" value = '.'"'.$_GET["key"].'"'.'/>';
                echo 'Pancerz:<input type="text" name="pancerz"/><br>';
                echo "Wytrzymałość:<input type='text' name='wytrzymalosc'/><br>";
                echo "Zasięg widzenia:<input type='text' name='zasieg'/><br>";
                echo "<input type='submit' name='kup' value='Kup czołg' onclick='return check()';/>";
                echo '</form>';
            }
        }else{
            header('Location:index.php');
        }
    }

    /**
     * Metoda obsługująca zakup czołgu - przypisanie go w bazie do użytkownika oraz uszczuplenie konta użytkownika o
     * wartość czołgu
     */

    function buyTank(){
        if($this->_is_logged()&&isset($_GET["czolg"])&&isset($_GET["pancerz"])&&isset($_GET["wytrzymalosc"])&&
            isset($_GET["zasieg"])) {
            $idU= $this->getIdU();
            $idC= $_GET["czolg"];
            $sql = "SELECT * FROM isInGarage($1,$2);";
            pg_prepare($this->conn, "czyWGarazu", $sql);
            $sql = "SELECT * FROM creditsAndExpT($1);";
            pg_prepare($this->conn, "kdT", $sql);
            $result = pg_execute($this->conn, "kdT", array($idC));
            $kontoU = pg_fetch_row($result);
            $kredytyT = - $kontoU[0];
            $doswiadczenieT = - $kontoU[1];
            $res = pg_execute($this->conn, "czyWGarazu", array($idU, $idC));
            $garaz = pg_fetch_row($res);
            if(!empty($garaz[0])) {
                echo "Wybrany czołg znajduje się już w garażu";
            }else{
                pg_query("BEGIN");
                $pancerz = $_GET["pancerz"];
                $wytrzymalosc = $_GET["wytrzymalosc"];
                $zasieg = $_GET["zasieg"];
                $res1 = pg_query("INSERT INTO CzolgiWGarazu(IDCzolgu, IDUzytkownika, PancerzU, WytrzymaloscU, 
                    ZasiegWidzeniaU) VALUES($idC, $idU, $pancerz, $wytrzymalosc, $zasieg)");
                if($res1){
                    echo "Czołg dodał się poprawnie";
                    pg_query("COMMIT") or die("Transaction commit failed\n");
                    pg_query("UPDATE UzytkownicyW SET DostepneKredyty=$kredytyT, DostepneDoswiadczenie=$doswiadczenieT
                    WHERE IDUzytkownika=$idU");
                }else{
                    echo "Coś się nie powiodło";
                    pg_query("ROLLBACK") or die("Transaction rollback failed\n");
                }
            }
        }else{
            header('Location:index.php');
        }
    }

    /**
     * Metoda usuwająca rekord z bazy - przypisanie czołgu do użytkownika oraz uaktualnia stan konta użytkownika
     */

    function sellTank(){
        if($this->_is_logged()&&isset($_GET["idC"])){
            $idU= $this->getIdU();
            $idC= $_GET["idC"];
            $sql = "SELECT * FROM sellTank($1,$2);";
            pg_prepare($this->conn, "sprzedaz", $sql);
            $res = pg_execute($this->conn, "sprzedaz", array($idU, $idC));
            $garaz = pg_fetch_row($res);
            if($garaz[0]){
                echo "Sprzedaż czołgu powiodła się";
                $sql = "SELECT * FROM creditsAndExpT($1);";
                pg_prepare($this->conn, "kdT", $sql);
                $result = pg_execute($this->conn, "kdT", array($idC));
                $kontoU = pg_fetch_row($result);
                $kredytyT = $kontoU[0];
                $doswiadczenieT = $kontoU[1];
                pg_query("UPDATE UzytkownicyW SET DostepneKredyty=$kredytyT, DostepneDoswiadczenie=$doswiadczenieT
                    WHERE IDUzytkownika=$idU");

            }
        }else{
            header('Location:index.php');
        }
    }


    /*
     * ----------------------------Sekcja metod o klanach --------------------------------------------------
     */

    /**
     * Funkcja pobierajaca z bazy idKlanu dla zalogowanego uzytkownika
     */
    private function getIdK()
    {
        $idU = $this->getIdU();
        $sql = "SELECT * FROM getClanID($1);";
        pg_prepare($this->conn, "idK", $sql);
        $result = pg_execute($this->conn, "idK", array($idU));
        $idK = pg_fetch_row($result);
        return $idK[0];
    }

    /**
     * Metoda wyświetlacją tabele z klanami
     */
    function displayClans(){
        $result = pg_query($this->conn, "SELECT * FROM displayClans");
        $sql = "SELECT * FROM countClanPart($1);";
        pg_prepare($this->conn,"czlonkowie", $sql);
        $sql = "SELECT * FROM getClanID($1);";
        pg_prepare($this->conn,"idKlanu", $sql);
        if($this->_is_logged()) {
            $user = $_SESSION['user']; //email
            $sql = "SELECT * FROM getIDUzytkownika($1);";
        }
        pg_prepare($this->conn,"idU", $sql);
        echo "<table>";
        echo '<tr><td>Nazwa klanu</td><td>Poziom twierdzy</td><td>Data założenia</td><td>Ilość członków</td>';
        while($row = pg_fetch_row($result)){
            echo "<tr>";
            echo "<td>". $row[1] ."</td>";
            echo "<td>". $row[2] ."</td>";
            echo "<td>". $row[3] ."</td>";
            $r = pg_execute($this->conn,"czlonkowie", array($row[0]));
            $czlonkowie = pg_fetch_row($r);
            echo "<td>".$czlonkowie[0]."</td>";
            if($this->_is_logged()){
                $re = pg_execute($this->conn,"idU", array($user));
                $idU = pg_fetch_row($re);
                $res = pg_execute($this->conn,"idKlanu", array($idU[0]));
                $idK = pg_fetch_row($res);
                //!!!!!!!!!!!!!!!!!! Moment który moze krzaczyc wszystko
                //echo "<td>". $idK ."</td>";
                if($idK[0]==null) {
                    $var = '"dolacz.php?key='. $row[0] .'"';
                    //echo "<td><a href=$var class='button'>Dołącz do klanu</a></td>";
                    //<input type="button" onclick="location.href='http://google.com';" value="Go to Google" />
                    echo "<td><input type=button onclick='location.href=$var;' value='Dołącz do klanu'/></td>";

                }
            }
            echo "</tr>";
        }
    }

    /**
     * Metoda pozwalająca na dołączenie do klanu
     */
    function joinClan(){
        if(isset($_GET["key"])){
            $clanID = $_GET["key"];
            $sql = "SELECT * FROM updateClan($1, $2);";
            pg_prepare($this->conn,"klan", $sql);
            $idU = $this->getIdU();
            $r = pg_execute($this->conn,"klan", array($clanID, $idU));
            echo "Dołączyłeś do klanu możesz teraz sparawdzić statystyki klanu w zakładce Mój klan";
        }
        else{
            header('Location:index.php');
        }

    }

    /**
     * Wyswietlanie strony z klanu dla zalogowanych uzytkownikow - mojklan.php
     */
    function displayClansLogged()
    {
        if($this->_is_logged()) {
            $idK = $this->getIdK();
            if (is_null($idK)) {
                echo "Nie jesteś w tej chwili członkiem żadnego klanu";
            } else {
                $sql = "SELECT * FROM getClanName($1);";
                pg_prepare($this->conn, "nazwaKlanu", $sql);
                $res = pg_execute($this->conn, "nazwaKlanu", array($idK));
                $nazwa = pg_fetch_row($res);
                echo "Nazwa klanu:".$nazwa[0]."<br>";
                $sql = "SELECT * FROM zasobyKlanu($1);";
                pg_prepare($this->conn, "daneKlanu", $sql);
                $r = pg_execute($this->conn, "daneKlanu", array($idK));
                $data = pg_fetch_row($r);
                echo "Zgromadzone zasoby: ".$data[0]."<br>";
                echo "Ilość bonusów do zarabiania: ".$data[1]."<br>";
                echo "Ilość bonusów do doświadczenia: ".$data[2]."<br>";
                $var = '"opusc.php?key='.$idK.'"';
                echo "<br><input type=button onclick='location.href=$var;' value='Opuść klan'/>";
            }
        }
        else{
            header('Location:logowanie.php');
        }
    }

    /**
     * Metoda do opuszczania klanu
     */

    function leaveClan(){
        if(isset($_GET["key"])){
            $sql = "SELECT * FROM updateClan($1, $2);";
            pg_prepare($this->conn,"klan", $sql);
            $idU = $this->getIdU();
            $r = pg_execute($this->conn,"klan", array(null, $idU));
            echo "Poprawnie opuściłeś klan";
        }
        else{
            header('Location:index.php');
        }

    }

    /*
     * ---------------------------- Sekcja funkcji o logowaniu i rejetracji --------------------------------
    */

    /**
     * Sprawdza czy uzytkownik jest zalogowany
     * @return bool wynik sprawdzenia 1 - zalogowany, 0 - niezalogowany
     */

    function _is_logged()
    {
        if (isset ($_SESSION['auth'])) {
            $ret = $_SESSION['auth'] == 'OK' ? true : false;
        } else {
            $ret = false;
        }
        return $ret;
    }

    /**
     * Wylogowanie uzytkownika
     * @return string info
     */

    function _logout()
    {
        unset($_SESSION);
        session_destroy();
        $text = 'Uzytkownik wylogowany ';
        return $text;
    }

    /**
     * Metoda pozwalająca na dodanie nowego użytkownika do bazy, tworzy również wszystkie inne tabele związane z kontem
     * użytkownika
     * @return string
     */
    function _save ()
    {
        $nflag = true;
        $emflag = true;
        $sql = "SELECT * FROM selectNick($1);";
        $text = "";
        pg_prepare($this->conn, "nick", $sql);
        $nick = pg_execute($this->conn, "nick", array($this->data['nick']));
        $result = pg_fetch_result($nick, 0);
        if ($result == 't') {
            $nflag = false;
            echo "
            <script type=\"text/javascript\">
            alert('Podany nick istnieje w bazie. Spróbuj z innym.');
            </script> ";
        }
        $sql = "SELECT * FROM selectEmail($1);";
        pg_prepare($this->conn, "email", $sql);
        $email = pg_execute($this->conn, "email", array($this->data['email']));
        $result = pg_fetch_result($email, 0);
        if ($result == 't') {
            $emflag = false;
            echo "
            <script type=\"text/javascript\">
            alert('Podany email istnieje w bazie. Spróbuj z innym.');
            </script> ";
        }
        if ($nflag && $emflag) {
            pg_query("BEGIN");
            $res1 = pg_query($this->conn, "INSERT INTO UzytkownicyP(Nick) VALUES ('" . $this->data['nick'] . "');");
            if ($res1) {
                pg_query("COMMIT");
            } else {
                echo "Coś się nie powiodło, błędny nick";
                pg_query("ROLLBACK");
            }
            pg_query("BEGIN");
            $res2 = pg_query($this->conn, "SELECT IDUzytkownika FROM UzytkownicyP WHERE Nick ='" . $this->data['nick'] . "';");
            $id = pg_fetch_result($res2, 0);
            if ($res2) {
                pg_query("COMMIT");
            } else {
                echo "Coś się nie powiodło";
                pg_query("ROLLBACK");
            }
            pg_query("BEGIN");
            $res3 = pg_query($this->conn, "INSERT INTO UzytkownicyW(IDUzytkownika, EMail, Haslo) VALUES ($id,'" . $this->data['email'] . "',
            '" . $this->data['haslo'] . "');");
            if ($res3) {
                pg_query("COMMIT");
            } else {
                echo "Coś się nie powiodło";
                pg_query("ROLLBACK");
            }
            pg_query("BEGIN");
            $res4 = pg_query($this->conn, "INSERT INTO OsiagnieciaU(IDUzytkownika) VALUES ($id)");
            if ($res4) {
                pg_query("COMMIT");
            } else {
                echo "Coś się nie powiodło";
                pg_query("ROLLBACK");
            }
            if ($res1 && $res2 && $res3 && $res4) {
                $text = "Twoje konto zostało poprawnie stworzone. Możesz się zalogować za pomocą adresu email i podanego hasła";
            } else{
                $text = "Konto nie zostało poprawnie utworzone";
            }
        }

        return $text;
    }


    /**
     * Metoda służąca do zalogowania użytkownika do bazy
     * @return string
     */
    function _login() {
        echo "logowanie";
        $email = $_POST['email'] ;
        $pass  = $_POST['haslo'] ;
        $access = false ;

        $sql = "SELECT * FROM selectEmail($1);";
        pg_prepare($this->conn,"email", $sql);
        $select = pg_execute($this->conn,"email", array($email));
        $result = pg_fetch_result($select,0);
        //echo "result ".$result."<br>";
        if($result=='t') {
            $sql = "SELECT * FROM selectPassword($1);";
            pg_prepare($this->conn,"emailLog", $sql);
            $select = pg_execute($this->conn,"emailLog", array($email));
            $haslo = pg_fetch_result($select,0);
            //echo "haslo ".$haslo."<br>";
            if ( $haslo == $pass ){
                $_SESSION['auth'] = 'OK' ;
                $_SESSION['user'] = $email ;
                $access = true ;
            }
        }
        if($access=="true"){
            $text = "Użytkownik poprawnie zalogowany";
            header('Location:index.php');
        }
        else{
            $text = "Błędny login lub hasło. Spróbuj zalogować się jeszcze raz.";
        }
        return $text ;
    }

    /**
     * Metoda sprawdzająca czy użytkownik ma uprawnienia administratora do strony
     * @return bool 1 - admin
     */
    function isAdmin(){
        if (isset ($_SESSION['admin'])) {
            $ret = $_SESSION['admin'] == 'true' ? true : false;
        } else {
            $ret = false;
        }
        return $ret;
    }

    /**
     * Metoda odpowiadająca za logowanie się go panelu administratora
     * @return string
     */

    function logAdmin(){
        $nick = $_POST['nick'] ;
        $pass  = $_POST['haslo'] ;
        $access = false;
        $sql = "SELECT * FROM selectAdmin($1);";
        pg_prepare($this->conn,"nick", $sql);
        $select = pg_execute($this->conn,"nick", array($nick));
        $result = pg_fetch_result($select,0);
        echo "result ".$result."<br>";
        if($result=='t') {
            $sql = "SELECT * FROM selectAdminPass($1);";
            pg_prepare($this->conn,"adminPass", $sql);
            $select = pg_execute($this->conn,"adminPass", array($nick));
            $haslo = pg_fetch_result($select,0);
            if ( $haslo == $pass ){
                $_SESSION['user'] = $nick ;
                $access = true ;
            }
        }
        if($access=="true"){
            $text = "Udało ci się poprawnie zalogować";
            header('Location:admin.php');
        }else{
            $text = "Błędny login lub hasło. Spróbuj zalogować się jeszcze raz.";
        }
        return $text;
    }

    /**
     * Metoda do wyświetlania menu użytkownia niezalogowanego
     */

    function menuN(){
        echo '<span class="option"><a href="rejestracja.php">Zarejestruj się</a></span>';
        echo '<span class="option"><a href="logowanie.php">Logowanie</a></span>';
        echo '<span class="option"><a href="czolgopedia.php">Człogopedia</a></span>';
        echo '<span class="option"><a href="klany.php">Klany</a></span>';
        echo '<span class="option"><a href="uzytkownicy.php">Użytkownicy</a></span>';
        echo '<span class="option"><a href="statystyki.php">Statystyki</a></span>';
        echo '<span class="option"><a href="logAdmin.php">Admin panel</a></span>';
        echo '<div style="clear:both;"></div>';
        echo '</nav>';
    }

    /**
     * Metoda służaca do wyświetlania menu admina
     */
    function menuA(){
        echo '<span class="option"><a href="dodajdane.php">Dodaj dane</a></span>';
        echo '<span class="option"><a href="wyloguj.php">Wyloguj</a></span>';
        echo '<div style="clear:both;"></div>';
        echo '</nav>';
    }

    /**
     * Metoda do wyświetlania menu użytkowników zalegowanych
     */
    function menuZ(){
        echo '<span class="option"><a href="czolgopedia.php">Człogopedia</a></span>';
        echo '<span class="option"><a href="klany.php">Klany</a></span>';
        echo '<span class="option"><a href="uzytkownicy.php">Użytkownicy</a></span>';
        echo '<span class="option"><a href="mojeczolgi.php">Moje Czolgi</a></span>';
        echo '<span class="option"><a href="osiagniecia.php">Stan konta</a></span>';
        echo '<span class="option"><a href="mojklan.php">Mój klan</a></span>';
        echo '<span class="option"><a href="wyloguj.php">Wylogowanie z serwisu</a></span>';
        echo '<div style="clear:both;"></div>';
        echo '</nav>';
    }
}
?>
