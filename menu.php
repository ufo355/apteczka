<?php
    session_start();
    if(!isset($_SESSION['zalogowany']))
    {
        $_SESSION['bladLogowania'] = '<div class="alert alert-danger" role="alert">Najpierw sie zaloguj ;)</div>';
        header('Location: index.php');
        exit();
    }
    $zalogowany = $_SESSION['zalogowany'];
    include 'include/nagl.php';
?>
<ul class="list-group">
    <li class="list-group-item"><a href = 'dodajlek.php'>Dodaj lek</a></li>
    <li class="list-group-item"><a href = 'stan.php'>Sprawdź stan apteczki</a></li>
    <li class="list-group-item"><a href = 'historia.php'>Śledź historię leków</a></li>
    <li class="list-group-item"><a href = 'zezwol.php'>Zapros innego uzytkownika do korzystania z twojej apteczki</a></li>
    <li class="list-group-item"><a href = 'logout.php'>Wyloguj</a></li>
</ul>
<?php
    include 'include/stopka.php';
?>


