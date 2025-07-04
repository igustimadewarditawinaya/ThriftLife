<?php
// Konfigurasi database untuk Heroku
$url = parse_url(getenv("DATABASE_URL") ?: getenv("CLEARDB_DATABASE_URL"));

if ($url) {
    // Menggunakan database dari Heroku (ClearDB MySQL)
    $server = $url["host"];
    $username = $url["user"];
    $password = $url["pass"];
    $database = substr($url["path"], 1);
    $port = isset($url["port"]) ? $url["port"] : 3306;
} else {
    // Fallback untuk local development
    $server = "localhost";
    $username = "root";
    $password = "";
    $database = "thriftlife";
    $port = 3306;
}

try {
    $koneksi = new mysqli($server, $username, $password, $database, $port);
    
    if ($koneksi->connect_error) {
        throw new Exception("Connection failed: " . $koneksi->connect_error);
    }
    
    $koneksi->set_charset("utf8");
} catch (Exception $e) {
    die("Database connection error: " . $e->getMessage());
}
?>