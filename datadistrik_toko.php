<?php
$id_provinsi_terpilih = $_POST["id_provinsi"];

// Data distrik manual untuk DI Yogyakarta
if ($id_provinsi_terpilih == "5") {
    $kota_jogja = [
        ["city_id" => "22", "province" => "DI Yogyakarta", "city_name" => "Yogyakarta", "type" => "Kota", "postal_code" => "55111"],
        ["city_id" => "23", "province" => "DI Yogyakarta", "city_name" => "Bantul", "type" => "Kabupaten", "postal_code" => "55711"],
        ["city_id" => "24", "province" => "DI Yogyakarta", "city_name" => "Gunung Kidul", "type" => "Kabupaten", "postal_code" => "55811"],
        ["city_id" => "25", "province" => "DI Yogyakarta", "city_name" => "Kulon Progo", "type" => "Kabupaten", "postal_code" => "55611"],
        ["city_id" => "26", "province" => "DI Yogyakarta", "city_name" => "Sleman", "type" => "Kabupaten", "postal_code" => "55511"]
    ];

    echo "<option value=''>-- Pilih Distrik --</option>";
    foreach ($kota_jogja as $tiap_distrik) {
        echo "<option value='' 
            id_distrik='".$tiap_distrik["city_id"]."'
            nama_provinsi='".$tiap_distrik["province"]."' 
            nama_distrik='".$tiap_distrik["city_name"]."' 
            tipe_distrik='".$tiap_distrik["type"]."' 
            kodepos='".$tiap_distrik["postal_code"]."'>";
        echo $tiap_distrik["type"]." ".$tiap_distrik["city_name"];
        echo "</option>";
    }
}
?>
