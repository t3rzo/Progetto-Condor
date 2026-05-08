<?php
require_once 'utils.php';

richiediLogin();

$db = connessioneDb();
$id_gara = (int) ($_POST['id_gara'] ?? 0);

if ($db && $id_gara > 0 && !empty($_POST['atleti'])) {
    $allenatore = mysqli_real_escape_string($db, $_POST['allenatore'] ?? '');

    foreach ($_POST['atleti'] as $atleta) {
        $atleta_safe = mysqli_real_escape_string($db, $atleta);
        mysqli_query($db, "INSERT INTO gara_iscrizioni (id_gara, nome_atleta, allenatore) VALUES ($id_gara, '$atleta_safe', '$allenatore')");
    }

    mysqli_close($db);
}

header("Location: visualizza_iscritti.php?id_gara=$id_gara");
exit;
