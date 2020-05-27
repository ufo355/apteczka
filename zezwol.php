<div class = "container">
<?php
    session_start();
    if(!isset($_SESSION['zalogowany']))
    {
        $_SESSION['bladLogowania'] = '<div class = "row"><div class="alert alert-danger" role="alert">Najpierw sie zaloguj ;)</div></div>';
        header('Location: index.php');
        exit();
    }
    $zalogowany = $_SESSION['zalogowany'];
    
    if(isset($_POST['email'])){

        $email = $_POST['email'];
        unset($_POST['email']);      
        require_once "include/connect.php";
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
                    $_SESSION['bladWyszukania'] = '<div class = "row"><div class="alert alert-danger" role="alert">Nie znaleziono uzytkownika</div></div>';
                }
                $polaczenie->close();
            }
        }
        catch(Exception $e){
        }

    }


    include 'include/nagl.php';
    if(isset($_SESSION['bladWyszukania']))
    {
        echo $_SESSION['bladWyszukania'];
        unset($_SESSION['bladWyszukania']);
    }
?>




    <div class = "row">
        <form action ="zezwol.php" method = "post">
            <div class ="form-group row">
                    Wpisz email uzytkownika: <input type="email" name="email" placeholder = "email@mail.com" required>               
            </div>
            <div class = "form-group row">
            <label for="apteczka">Wybierz apteczke do ktorej dostępu chcesz udzielic:</label>
                    <select id="apteczka" name = "apteczka">
                        <?php
                            include 'include/wyswietlapteczki.php';
                        ?>
                    </select> 
            </div>
            <div class = "form-group row">       
                <input type="submit" value="Dalej">
            </div>
        </form>
    </div>

    <div class = "row">
        <a class="btn btn-outline-primary" href = 'menu.php'>Wróć do menu</a><br>
        <a class="btn btn-outline-primary" href = 'logout.php'>Wyloguj</a>
    </div>
</div>

<?php
    include 'include/stopka.php';
?>