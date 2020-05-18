<?php
    session_start();
    if(!isset($_SESSION['zalogowany']))
    {
        $_SESSION['bladLogowania'] = "Najpierw sie zaloguj ;)";
        header('Location: index.php');
        exit();
    }
    $zalogowany = $_SESSION['zalogowany'];
    
    if(isset($_POST['email'])){

        $email = $_POST['email'];
        unset($_POST['email']);      
        require_once "apteczka/include/connect.php";
        try{
            $polaczenie = new mysqli($host, $db_user, $db_password, $db_name);
            if($polaczenie->connect_errno!=0){
                throw new Exception(mysqli_connect_errno());
            }
            else{
                $rezultat = $polaczenie->query("SELECT * FROM uzytkownicy WHERE email='$email'");
                if(!$rezultat){

                    throw new Exception($polaczenie->error);
                }
                $ilu_userow = $rezultat->num_rows;
                if($ilu_userow>0){
                    $wiersz = $rezultat->fetch_assoc();
                    $id_uzytkownika = $wiersz['id_uzytkownika'];
                    $id_apteczki = $_POST['apteczka'];

                    $rezultat = $polaczenie->query("SELECT * FROM apteczki_uzytkownicy WHERE id_uzytkownika='$id_uzytkownika' AND id_apteczki='$id_apteczki'");
                    if(!$rezultat){
                        throw new Exception($polaczenie->error);
                    }
                    if($rezultat->num_rows==0){
                        $polaczenie->query("INSERT INTO apteczki_uzytkownicy VALUES (NULL,$id_uzytkownika,$id_apteczki)");
                    }
                }
                else{
                    $_SESSION['bladWyszukania'] = "Nie znaleziono uzytkownika";
                }
                $polaczenie->close();
            }
        }
        catch(Exception $e){
        }

    }


    include 'apteczka/include/nagl.php';
    if(isset($_SESSION['bladWyszukania']))
    {
        echo $_SESSION['bladWyszukania'];
        unset($_SESSION['bladWyszukania']);
    }
?>





<form action ="zezwol.php" method = "post">
    Wpisz email uzytkownika: <input type="email" name="email" placeholder = "email@mail.com" required><br>
    <label for="apteczka">Wybierz apteczke do ktorej dostępu chcesz udzielic:</label>
    <select id="apteczka" name = "apteczka">

    <?php
        require_once 'apteczka/include/connect.php';
        mysqli_report(MYSQLI_REPORT_STRICT);

        try{  
            $polaczenie = new mysqli($host, $db_user,$db_password, $db_name);
            if($polaczenie->connect_errno!=0){
                throw new Exception(mysqli_connect_errno());
            }
            else{
                $rezultaty = $polaczenie->query("SELECT * FROM apteczki,apteczki_uzytkownicy WHERE apteczki.id_apteczki = apteczki_uzytkownicy.id_apteczki AND apteczki_uzytkownicy.id_uzytkownika='$zalogowany'");
                if(!$rezultaty) throw new Exception($polaczenie->error);
                else{
                    while($row = $rezultaty->fetch_row()){
                        echo '<option value="'.$row[0].'">'.$row[1].'</option>';
                    }
                }
                $rezultaty->free_result();
                $polaczenie->close();
            }
        }
        catch(Exception $e){
            echo "blad polaczenia z baza";
        }
    ?>
    <input type="submit" value="Dalej">
</form>



<a href = 'menu.php'>Wróć do menu</a><br>
<a href = 'logout.php'>Wyloguj</a>

<?php
    include 'apteczka/include/stopka.php';
?>