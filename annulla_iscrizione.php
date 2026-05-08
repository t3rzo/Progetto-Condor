<?php
require_once 'utils.php';

richiediLogin();

$db = connessioneDb();
$id = (int) ($_GET['id'] ?? 0);
$id_gara = (int) ($_GET['id_gara'] ?? 0);

if ($db && $id > 0) {
    mysqli_query($db, "DELETE FROM gara_iscrizioni WHERE id = $id");
    mysqli_close($db);
}

header("Location: visualizza_iscritti.php?id_gara=$id_gara");
exit;
