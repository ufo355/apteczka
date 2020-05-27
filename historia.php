<?php
  session_start();
  if(!isset($_SESSION['zalogowany']))
  {
      $_SESSION['bladLogowania'] = "Najpierw sie zaloguj ;)";
      header('Location: index.php');
      exit();
  }
  $zalogowany = $_SESSION['zalogowany'];
  include 'include/nagl.php';
?>

<div class = "containter">
<div class = "row">
    <form method = 'post'>
        <div class = "form-group row">
            <label for="apteczka">Prześledź historię apteczki</label>
            <select id="apteczka" name = "apteczka">

                <?php
                    include 'include/wyswietlapteczki.php';
                ?>

            </select> 

            <input type="submit" value = "Sprawdź stan apteczki">    
        </div>
    </form>
</div>
    <?php

        if(isset($_POST['apteczka'])){
            $id_apteczki = $_POST['apteczka'];
            echo '<input type="text" id="myInput1" onkeyup="myFunction(0,id)" placeholder="Szukaj operacji.." title="Type in a name">';
            echo '<input type="text" id="myInput2" onkeyup="myFunction(1,id)" placeholder="Szukaj leku.." title="Type in a name">';
            echo '<input type="text" id="myInput3" onkeyup="myFunction(2,id)" placeholder="Szukaj uzytkownika.." title="Type in a name">';
            echo '<label for="data1">Od: </label>';
            echo '<input type="date" id = "data1" name="datawaznosci">';
            echo '<label for="data2">Do: </label>';
            echo '<input type="date" id = "data2" name="datawaznosci">';
            echo '<button type="button" id = "button1" onclick = "showDate()" >Filtruj datę</button>';
            echo '<table class = "table" id = "myTable"><tr class = "thead-dark"><th>Rodzaj operacji</th><th>Nazwa leku</th><th>Uzytkownik</th><th>Data</th></tr>';
            require_once 'include/connect.php';
            mysqli_report(MYSQLI_REPORT_STRICT);

            try{  
                $polaczenie = new mysqli($host, $db_user,$db_password, $db_name);
                if($polaczenie->connect_errno!=0){
                    throw new Exception(mysqli_connect_errno());
                }
                else{
                    $rezultaty = $polaczenie->query("SELECT operacje.rodzaj, leki.nazwa_leku, uzytkownicy.email, operacje.data FROM operacje,leki,uzytkownicy WHERE operacje.id_leku=leki.id_leku AND operacje.id_uzytkownika=uzytkownicy.id_uzytkownika AND operacje.id_apteczki=$id_apteczki ORDER BY operacje.data DESC");
                    if(!$rezultaty) throw new Exception($polaczenie->error);
                    else{
                        while($row = $rezultaty->fetch_row()){
                                echo '<tr><td>'.$row[0].'</td><td>'.$row[1].'</td><td>'.$row[2].'</td><td>'.$row[3].'</td></tr>';
                            
                        }
                    }
                    $rezultaty->free_result();
                    $polaczenie->close();
                    
                }
            }
            catch(Exception $e){
                echo $e->getMessage();
                echo "blad polaczeniiiiiia z baza";
            }
            echo '</table>';
            echo '<div id = "dupa"></div>';
        }


    ?>


    <div class = "row">
        <a class = "btn btn-outline-primary" href = 'menu.php'>Wróć do menu</a><br>
        <a class = "btn btn-outline-primary" href = 'logout.php'>Wyloguj</a>
    </div>
</div>

<script src = "js/skrypt.js"> </script>

<?php
    include 'include/stopka.php';
?>