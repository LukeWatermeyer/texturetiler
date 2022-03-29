<?php

function getHash($email) {
    require 'conn.php';
    return $conn->query("SELECT id, username, password FROM users WHERE email = '" . $email . "'");
}

function checkEmailExists($email) {
    require 'conn.php';
    return $conn->query("SELECT * FROM users WHERE email = '" . $email . "' LIMIT 1");
}

?>