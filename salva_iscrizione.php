<?php
$db = mysqli_connect("localhost", "root", "", "accedi_condor");

if (isset($_POST['atleti'])) {
    $id_gara = $_POST['id_gara'];
    $allenatore = mysqli_real_escape_string($db, $_POST['allenatore']);

    foreach ($_POST['atleti'] as $atleta) {
        $atleta_safe = mysqli_real_escape_string($db, $atleta);
        mysqli_query($db, "INSERT INTO iscrizioni_gare (id_gara, nome_atleta, allenatore) VALUES ('$id_gara', '$atleta_safe', '$allenatore')");
    }
}
header("Location: visualizza_iscritti.php?id_gara=$id_gara");