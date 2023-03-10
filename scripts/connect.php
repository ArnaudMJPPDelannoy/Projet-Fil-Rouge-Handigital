<?php
try {
    $pdo = new PDO("mysql:host=localhost;dbname=fil-rouge", "ArndelFilRouge", "2semaines");
} catch (PDOException $e) {
    echo $e->getMessage();
    die();
}
?>