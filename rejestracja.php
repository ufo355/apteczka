<?php
    function displayError($kind){
        if(isset($_SESSION[$kind]))
        {   

            echo $_SESSION[$kind];
            echo '<br>';
            unset($_SESSION[$kind]);
        }
    }
    session_start();
    if(isset($_POST['imie']))
    {
        $wszystkoOK=true;

        $imie = htmlentities($_POST['imie'],ENT_QUOTES,"UTF-8");
        $nazwisko = htmlentities($_POST['nazwisko'],ENT_QUOTES,"UTF-8");
        $email = $_POST['email'];
        $haslo1 = $_POST['haslo1'];
        $haslo2 = $_POST['haslo2'];


        //Walidacja maila
        if(!filter_var($email, FILTER_VALIDATE_EMAIL))
        {
            $wszystkoOK = false;
            $_SESSION['e_haslo'] = "Niepoprawy email";
        }

        //Walidacja hasla
        if(strlen($haslo1)<5 || strlen($haslo1)>=20)
        {
            $wszystkoOK = false;
            $_SESSION['e_haslo'] = "Haslo powinno liczyc od 5 do 20 znakow";
        }


        if($haslo1!=$haslo2)
        {
            $wszystkoOK = false;
            $_SESSION['e_haslo'] = "Hasla są różne";
        }

        $haslo_hash = password_hash($haslo1,PASSWORD_DEFAULT);


        //Walidacja checkboxa
        if(!isset($_POST['regulamin']))
        {
            $wszystkoOK = false;
            $_SESSION['e_regulamin'] = "Musisz zaakceptowac regulamin!";
        }


        //Walidacja recaptchy

        $secretKey = "6LfQhukUAAAAAC3lj_pVKZ_5p5YqVNHxtjbHSwKj";
        $sprawdz = file_get_contents('https://www.google.com/recaptcha/api/siteverify?secret='.$secretKey.'&response='.$_POST['g-recaptcha-response']);
        $odpowiedz = json_decode($sprawdz);

        if($odpowiedz->success==false)
        {
            $wszystkoOK = false;
            $_SESSION['e_recaptcha'] = "Potwierdz ze nie jestes robotem";  
        }



        //polaczenie z baza
        require_once "connect.php";
        mysqli_report(MYSQLI_REPORT_STRICT);
        try{
            $polaczenie = new mysqli($host, $db_user,$db_password, $db_name);
            if($polaczenie->connect_errno!=0){
                throw new Exception(mysqli_connect_errno());
            }
            else{
                $rezultat = $polaczenie->query("SELECT id_uzytkownika FROM uzytkownicy WHERE email='$email'");

                if(!$rezultat) throw new Exception($polaczenie->error);
                $ile_nickow = $rezultat->num_rows;
                if($ile_nickow>0){
                    $wszystkoOK = false;
                    $_SESSION['e_mail'] = "E-mail znajduje sie juz w bazie";
                }
        
                if($wszystkoOK == true){
                    $prawa = 'uzytkownik';
                    $polaczenie->query("INSERT INTO uzytkownicy VALUES(NULL,'$imie','$nazwisko','$email','$haslo_hash','$prawa')");
                    $_SESSION['udanaRejestracja'] = true;
                    header('Location: index.php');
                }
                
                $polaczenie->close();
            }
        }
        catch(Exception $e){
            echo "Błąd serwera! Przepraszamy za niedogodności i prosimy o rejsetracje w innym terminie";
            echo '<br>Bład'.$e;
        }



       
    }
    include 'nagl.php';
?>
<h2>Zaloguj sie</h2>

<form method = "post">
    Imie: <br><input type="text" name="imie" placeholder = "imie" required><br>
    Nazwisko: <br><input type="test" name="nazwisko" placeholder="nazwisko"><br>
    E-mail: <br><input type="email" name="email" placeholder = "email" required><br>
    <?php
       displayError('e_mail');
    ?>
    Haslo: <br><input type="password" name="haslo1" placeholder = "haslo" required><br>
    Powtorz haslo: <br><input type="password" name="haslo2" placeholder = "haslo" required><br>
    <?php
        displayError('e_haslo');
    ?>
    <label>
        <input type="checkbox" name="regulamin">Akceptuję regulamin
    </label>
    <br>
    <?php
        displayError('e_regulamin');
    ?>
    <br>
    <div class="g-recaptcha" data-sitekey="6LfQhukUAAAAAKdbxp52ox7XoulPIMktv6i_r8Ve"></div>
    <br>
    <?php
        displayError('e_recaptcha');
    ?>

    <input type="submit" value="Dalej">
</form>


<?php
    include 'stopka.php';   
?>