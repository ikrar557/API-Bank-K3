<?php
$storedUsername = "admin";
$storedPassword = "admin";

$providedUsername = $_SERVER['PHP_AUTH_USER'] ?? null;
$providedPassword = $_SERVER['PHP_AUTH_PW'] ?? null;

if ($providedUsername === $storedUsername && $providedPassword === $storedPassword) {
    header('HTTP/1.1 200 OK');
} else {
    header('WWW-Authenticate: Basic realm="My API"');
    header('HTTP/1.1 401 Unauthorized');
    echo "Authentikasi gagal! Mohon masukan kredensial valid.";
    exit;
}
