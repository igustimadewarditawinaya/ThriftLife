<?php
$database_url = getenv("DATABASE_URL");

if (!$database_url) {
    die("Database not configured. Please add database add-on to Heroku.");
}

$url = parse_url($database_url);

$server = $url["host"];
$username = $url["user"];
$password = $url["pass"];
$database = substr($url["path"], 1);
$port = isset($url["port"]) ? $url["port"] : 5432;

// Gunakan PDO untuk support MySQL dan PostgreSQL
try {
    if (strpos($database_url, 'postgres') !== false) {
        // PostgreSQL connection
        $dsn = "pgsql:host=$server;port=$port;dbname=$database";
        $pdo = new PDO($dsn, $username, $password);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        
        // Untuk compatibility dengan mysqli, buat wrapper
        $koneksi = $pdo;
    } else {
        // MySQL connection (jika menggunakan MySQL)
        $koneksi = new mysqli($server, $username, $password, $database, $port);
        if ($koneksi->connect_error) {
            throw new Exception("Connection failed: " . $koneksi->connect_error);
        }
        $koneksi->set_charset("utf8");
    }
} catch (Exception $e) {
    die("Database connection error: " . $e->getMessage());
}
?>