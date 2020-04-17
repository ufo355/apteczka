<?php
    session_start();
    if(!isset($_SESSION['zalogowany']))
    {
        $_SESSION['bladLogowania'] = "Najpierw sie zaloguj ;)";
        header('Location: index.php');
        exit();
    }
    $zalogowany = $_SESSION['zalogowany'];
    include 'nagl.php';
?>

<a href = 'dodajlek.php'>Dodaj lek</a><br>
<a href = 'stan.php'>Sprawdź stan apteczki</a><br>
<a href = 'raport.php'>Wygeneruj raport</a><br>
<a href = 'zezwol.php'>Zapros innego uzytkownika do korzystania z twojej apteczki</a><br>
<a href = 'logout.php'>Wyloguj</a>
<?php
    include 'stopka.php';
?>


