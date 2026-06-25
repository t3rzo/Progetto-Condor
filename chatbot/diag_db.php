<?php
ini_set('display_errors', '1');
error_reporting(E_ALL);
header('Content-Type: text/plain; charset=utf-8');
echo "Test connessione DB...\n";
try {
    $db = @mysqli_connect('127.0.0.1', 'root', '', 'accedi_condor');
    if (!$db) {
        echo "ERRORE mysqli_connect: " . mysqli_connect_error() . "\n";
    } else {
        echo "OK connesso al DB\n";
        $res = mysqli_query($db, "SELECT COUNT(*) as n FROM atleti");
        if ($res) {
            $row = mysqli_fetch_assoc($res);
            echo "Atleti presenti: " . $row['n'] . "\n";
        } else {
            echo "ERRORE query atleti: " . mysqli_error($db) . "\n";
        }
        mysqli_close($db);
    }
} catch (Throwable $e) {
    echo "Eccezione: " . $e->getMessage() . "\n";
}
