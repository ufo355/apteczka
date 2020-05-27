<?php
    session_start();
    if(!isset($_SESSION['zalogowany']))
    {
        $_SESSION['bladLogowania'] = "Najpierw sie zaloguj ;)";
        header('Location: index.php');
        exit();
    }
    $zalogowany = $_SESSION['zalogowany'];
    if(isset($_POST['leki'])){

        
        $rodzaj = "dodanie";
        $id_uzytkownika = $_SESSION['zalogowany'];
        $id_leku = $_POST['leki'];
        $id_apteczki = $_POST['apteczka'];
        $ilosc = $_POST['ilosc'];
        $cenazasztuke = $_POST['cena'];
        $koszty = $cenazasztuke*$ilosc;
        $datawaznosci = $_POST['datawaznosci'];
        $data = date('Y-m-d H:i:s');
        unset($_POST['leki']);
        require_once "include/connect.php";
        mysqli_report(MYSQLI_REPORT_STRICT);
        try{
            $polaczenie = new mysqli($host, $db_user,$db_password, $db_name);
            if($polaczenie->connect_errno!=0){
                throw new Exception(mysqli_connect_errno());
            }
            else{
 
                $polaczenie->query("INSERT INTO operacje VALUES (NULL,'$rodzaj','$id_uzytkownika','$id_leku','$id_apteczki','$ilosc','$koszty','$data')");

                for($i = 0; $i<$ilosc; $i++){
                    $polaczenie->query("INSERT INTO leki_w_apteczkach VALUES (NULL,'$id_leku','$id_apteczki','$datawaznosci','$cenazasztuke')");
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


<form method = 'post'>

    <div class ="form-group row">
        <label for="leki">Wybierz lek: </label>
        <select id="leki" name = "leki">
    

    <?php
        require_once 'include/connect.php';
        mysqli_report(MYSQLI_REPORT_STRICT);

        try{  
            $polaczenie = new mysqli($host, $db_user,$db_password, $db_name);
            if($polaczenie->connect_errno!=0){
                throw new Exception(mysqli_connect_errno());
            }
            else{
                $rezultaty = $polaczenie->query("SELECT * FROM leki");
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
    </select>
    </div>

    <div class ="form-group row">
        <div class="form-group col-md-6">
            <label for="apteczka">Wybierz apteczkę lub <a class="text-decoration-none" href = "dodajapteczke.php">stwórz nową</a></label>
            <select id="apteczka" name = "apteczka">

            <?php
                include 'include/wyswietlapteczki.php';
            ?>

            </select> 
        </div>

        <div class="form-group col-md-6">
            <label>
            Wprowadź date ważności:
            <input type="date" name="datawaznosci">
            </label>
        </div>

    </div>

    <div class ="form-group row">
        <div class="form-group col-md-6">
            <label for="cenazasztuke">Cena za sztukę</label>
            <input id = "cenazasztuke" type="number" name="cena" min = "0.00" value = "0.00" step="0.01">
        </div>
        <div class="form-group col-md-6">
            <label for="ilosc">Ilość</label>
            <input id = "ilosc" type="number" name="ilosc" min = "1" value = "0" step="1">
        </div>
    </div>

    <div class ="form-group row">
        <div class="form-group col-md-4 my-auto">
            <input class = "btn btn-primary btn-block" type="submit" value = "Dodaj lek do apteczki">   
        </div>
    </div> 
    <div class ="form-group row">
        <button type="button" class="btn btn-outline-primary"><a href = 'menu.php'>Powrót</a></button>
        <button type="button" class="btn btn-outline-primary"><a href = 'logout.php'>Wyloguj</a></button>
    </div> 
</form>
<?php
    include 'include/stopka.php';
?>


