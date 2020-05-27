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
            $_SESSION['e_haslo'] = '<div class="alert alert-danger" role="alert">Niepoprawy email</div>';
        }

        //Walidacja hasla
        if(strlen($haslo1)<5 || strlen($haslo1)>=20)
        {
            $wszystkoOK = false;
            $_SESSION['e_haslo'] = '<div class="alert alert-danger" role="alert">Haslo powinno liczyc od 5 do 20 znakow</div>';
        }


        if($haslo1!=$haslo2)
        {
            $wszystkoOK = false;
            $_SESSION['e_haslo'] = '<div class="alert alert-danger" role="alert">Hasla są różne</div>';
        }

        $haslo_hash = password_hash($haslo1,PASSWORD_DEFAULT);



        //polaczenie z baza
        require_once "include/connect.php";
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
    include 'include/nagl.php';
?>

<form method = "post">
    <h1 class="h3 mb-3 font-weight-normal">Rejestracja</h1>
    <div class ="form-group row">
        <label for="inputImie" class="sr-only">Imie</label>
        <input class="form-control" id = "inputImie" type="text" name="imie" placeholder = "Imię" required>
    </div>
    <div class ="form-group row">
    <label for="inputNazwisko" class="sr-only">Nazwisko</label>
    <input class="form-control" id="inputNazwisko" type="test" name="nazwisko" placeholder="Nazwisko">
    </div>

    <div class ="form-group row">
    <label for="inputEmail" class="sr-only">Email</label>
    <input class="form-control" id = "inputEmail" type="email" name="email" placeholder = "Email" required>
    </div>

    <?php
        displayError('e_mail');
    ?>

    <div class ="form-group row">
    <label for="inputPassword1" class="sr-only">Password1</label>
    <input class="form-control" id = "inputPassword1" type="password" name="haslo1" placeholder = "Hasło" required>
    </div>
    <div class ="form-group row">
    <label for = "inputPassword2" class="sr-only">Password2</label>
    <input class="form-control" id = "inputPassword2" type="password" name="haslo2" placeholder = "Powtórz hasło" required>
    </div>

    <?php
        displayError('e_haslo');
    ?>

    <div class ="form-group row">
    <input class = "btn btn-primary btn-block" type="submit" value="Rejestracja">
    </div>

    <div class = "form-group row">
        <a class="btn btn-primary btn-block"  href = "index.php">Powrót</a>
    </div>

</form>


<?php
    include 'include/stopka.php';   
?>