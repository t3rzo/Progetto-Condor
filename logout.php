<?php
require_once 'utils.php';

avviaSessione();
session_destroy();

header('Location: index.php');
exit;
