<?php
if (getenv('JAWSDB_URL')) {
    // Koneksi Heroku JawsDB
    $url = parse_url(getenv('JAWSDB_URL'));
    $server = $url["host"];
    $username = $url["user"];
    $password = $url["pass"];
    $db = substr($url["path"], 1);
    try {
        $koneksi = new PDO("mysql:host=$server;dbname=$db;charset=utf8", $username, $password);
        $koneksi->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    } catch (PDOException $e) {
        die("Koneksi gagal: " . $e->getMessage());
    }
} else {
    // Koneksi lokal
    $server = "localhost";
    $username = "root";
    $password = "";
    $db = "final_marketplace";
    try {
        $koneksi = new PDO("mysql:host=$server;dbname=$db;charset=utf8", $username, $password);
        $koneksi->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    } catch (PDOException $e) {
        die("Koneksi gagal: " . $e->getMessage());
    }
}
?>