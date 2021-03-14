<?php

    $conn = new mysqli("localhost", "root", "", "4ic1");
    if($conn -> connect_error) die('Nie można połączyć się z serwerem');

    $res = $conn->query('DELETE FROM users WHERE username="'.$_GET['username'].'"');
    
?>