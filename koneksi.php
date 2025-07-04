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

// Gunakan PDO untuk PostgreSQL
try {
    // PostgreSQL connection
    $dsn = "pgsql:host=$server;port=$port;dbname=$database";
    $pdo = new PDO($dsn, $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Create all necessary tables
    $createTables = [
        "CREATE TABLE IF NOT EXISTS admin (
            id_admin SERIAL PRIMARY KEY,
            username VARCHAR(100) NOT NULL,
            password VARCHAR(100) NOT NULL,
            nama_lengkap VARCHAR(100) NOT NULL,
            foto_admin VARCHAR(50) NOT NULL
        )",
        
        "CREATE TABLE IF NOT EXISTS kategori (
            id_kategori SERIAL PRIMARY KEY,
            nama_kategori VARCHAR(100) NOT NULL
        )",
        
        "CREATE TABLE IF NOT EXISTS pelanggan (
            id_pelanggan VARCHAR(50) PRIMARY KEY,
            email_pelanggan VARCHAR(100) NOT NULL UNIQUE,
            password_pelanggan VARCHAR(50) NOT NULL,
            nama_pelanggan VARCHAR(50) NOT NULL,
            jk_pelanggan VARCHAR(10) CHECK (jk_pelanggan IN ('pria', 'wanita')),
            tgl_lahir DATE NOT NULL,
            alamat_pelanggan TEXT NOT NULL,
            telepon_pelanggan VARCHAR(25) NOT NULL,
            foto_pelanggan VARCHAR(100) NOT NULL,
            status VARCHAR(10) DEFAULT 'pelanggan' CHECK (status IN ('pelanggan', 'penjual'))
        )",
        
        "CREATE TABLE IF NOT EXISTS pengiriman (
            id_pengiriman SERIAL PRIMARY KEY,
            status_pengiriman VARCHAR(30) NOT NULL
        )",
        
        "CREATE TABLE IF NOT EXISTS toko (
            id_toko VARCHAR(30) PRIMARY KEY,
            nama_toko VARCHAR(30) NOT NULL,
            telepon_toko VARCHAR(15) NOT NULL,
            email_toko VARCHAR(50) NOT NULL,
            nama_bank VARCHAR(10) CHECK (nama_bank IN ('bca', 'bni', 'bri', 'mandiri')),
            rek_bank VARCHAR(20) NOT NULL,
            foto_toko VARCHAR(100) NOT NULL,
            deskripsi_toko VARCHAR(255) NOT NULL,
            provinsi_toko VARCHAR(50) NOT NULL,
            distrik_toko VARCHAR(50) NOT NULL,
            alamat_toko VARCHAR(100) NOT NULL,
            bergabung DATE NOT NULL,
            FOREIGN KEY (id_toko) REFERENCES pelanggan(id_pelanggan)
        )",
        
        "CREATE TABLE IF NOT EXISTS produk (
            id_produk SERIAL PRIMARY KEY,
            id_kategori INTEGER NOT NULL,
            id_toko VARCHAR(30) NOT NULL,
            nama_produk VARCHAR(100) NOT NULL,
            harga_produk INTEGER NOT NULL,
            berat_produk INTEGER NOT NULL,
            foto_produk VARCHAR(100) NOT NULL,
            deskripsi_produk TEXT NOT NULL,
            stok_produk INTEGER NOT NULL,
            stok_awal INTEGER NOT NULL,
            FOREIGN KEY (id_kategori) REFERENCES kategori(id_kategori),
            FOREIGN KEY (id_toko) REFERENCES toko(id_toko)
        )",
        
        "CREATE TABLE IF NOT EXISTS pembelian (
            id_pembelian SERIAL PRIMARY KEY,
            id_pelanggan VARCHAR(30) NOT NULL,
            id_toko VARCHAR(30) NOT NULL,
            id_pengiriman INTEGER NOT NULL,
            distrik_toko VARCHAR(50) NOT NULL,
            tanggal_pembelian DATE NOT NULL,
            total_pembelian INTEGER NOT NULL,
            alamat_pengiriman TEXT NOT NULL,
            resi_pengiriman VARCHAR(50) NOT NULL,
            totalberat INTEGER NOT NULL,
            provinsi VARCHAR(255) NOT NULL,
            distrik VARCHAR(255) NOT NULL,
            tipe VARCHAR(255) NOT NULL,
            kodepos VARCHAR(255) NOT NULL,
            ekspedisi VARCHAR(255) NOT NULL,
            paket VARCHAR(255) NOT NULL,
            ongkir INTEGER NOT NULL,
            estimasi VARCHAR(255) NOT NULL,
            FOREIGN KEY (id_pelanggan) REFERENCES pelanggan(id_pelanggan),
            FOREIGN KEY (id_toko) REFERENCES toko(id_toko),
            FOREIGN KEY (id_pengiriman) REFERENCES pengiriman(id_pengiriman)
        )",
        
        "CREATE TABLE IF NOT EXISTS pembayaran (
            id_pembayaran SERIAL PRIMARY KEY,
            id_pembelian INTEGER NOT NULL,
            nama VARCHAR(255) NOT NULL,
            bank VARCHAR(10) CHECK (bank IN ('mandiri', 'bca', 'bri', 'bni')),
            jumlah INTEGER NOT NULL,
            tanggal DATE NOT NULL,
            bukti VARCHAR(255) NOT NULL,
            id_toko VARCHAR(30) NOT NULL,
            FOREIGN KEY (id_pembelian) REFERENCES pembelian(id_pembelian),
            FOREIGN KEY (id_toko) REFERENCES toko(id_toko)
        )",
        
        "CREATE TABLE IF NOT EXISTS pembelian_produk (
            id_pembelian_produk SERIAL PRIMARY KEY,
            id_pembelian INTEGER NOT NULL,
            id_produk INTEGER NOT NULL,
            id_toko VARCHAR(30) NOT NULL,
            jumlah INTEGER NOT NULL,
            nama VARCHAR(100) NOT NULL,
            harga INTEGER NOT NULL,
            berat INTEGER NOT NULL,
            subberat INTEGER NOT NULL,
            subharga INTEGER NOT NULL,
            FOREIGN KEY (id_pembelian) REFERENCES pembelian(id_pembelian),
            FOREIGN KEY (id_produk) REFERENCES produk(id_produk),
            FOREIGN KEY (id_toko) REFERENCES toko(id_toko)
        )"
    ];
    
    // Insert default data for pengiriman if not exists
    $defaultData = [
        "INSERT INTO pengiriman (id_pengiriman, status_pengiriman) 
         VALUES (0, 'belum dibayar'), (1, 'sedang diproses'), (2, 'barang dikirim') 
         ON CONFLICT (id_pengiriman) DO NOTHING",
         
        "INSERT INTO kategori (nama_kategori) 
         VALUES ('Komputer'), ('Handphone'), ('Laptop'), ('Kamera'), ('Game')
         ON CONFLICT DO NOTHING"
    ];
    
    // Execute table creation
    foreach ($createTables as $sql) {
        $pdo->exec($sql);
    }
    
    // Execute default data insertion
    foreach ($defaultData as $sql) {
        $pdo->exec($sql);
    }
    
    $koneksi = $pdo;
    
} catch (Exception $e) {
    die("Database connection error: " . $e->getMessage());
}
?>