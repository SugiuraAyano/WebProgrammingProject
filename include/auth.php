<?php
session_start();
if (!isset($_SESSION["id"])||empty($_SESSION['id'])) {
    header("Location: ../web/timeout.php");
    exit();
}
?>