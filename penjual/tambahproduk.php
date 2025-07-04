<?php 
$datakategori = array();

$ambil = $koneksi->query("SELECT * FROM kategori");
while($tiap = $ambil->fetch(PDO::FETCH_ASSOC))
{
    $datakategori[] = $tiap;
}

$ambil = $koneksi->query("SELECT * FROM toko WHERE id_toko='".$_SESSION['penjual']."' ");
while ($pecah = $ambil->fetch(PDO::FETCH_ASSOC)) {
    $id_toko = $pecah['id_toko'];
?>
    <h2 class="text-center">Tambah Produk</h2>
    <br>
    <form method="post" enctype="multipart/form-data">
        <div class="row">
            <div class="input-field col s6">
                <i class="material-icons prefix">account_circle</i>
                <label>Nama Produk</label>
                <input type="text" name="nama" required autocomplete="off">
            </div>
            <div class="input-field col s6">
                <i class="material-icons prefix">local_offer</i>
                <select name="id_kategori" class="validate" required >
                    <option></option>
                    <?php foreach ($datakategori as $key => $value): ?>
                        <option value="<?php echo $value['id_kategori'] ?>"><?php echo $value['nama_kategori'] ?></option>
                    <?php endforeach ?>
                </select>
                <label>kategori Produk</label>
            </div>      
        </div>
        <div class="row">
            <div class="input-field col s4">
                <i class="material-icons prefix">attach_money</i>
                <label>Harga (Rp)</label>
                <input type="number" name="harga" class="validate" required min="1">
            </div>
            <div class="input-field col s4">
                <i class="material-icons prefix">gavel</i>
                <label>Berat (Gr)</label>
                <input type="number" name="berat" class="validate" required min="1">
            </div>
            <div class="input-field col s4">
                <i class="material-icons prefix">description</i>
                <label>Stok Produk</label>
                <input type="number" name="stok_produk" class="validate" required min="1">
            </div>
        </div>
        <div class="row">
            <div class="input-field col s12">
                <i class="material-icons prefix">description</i>
                <label>Deskripsi Produk</label>
                <textarea name="deskripsi" class="materialize-textarea validate" rows="5" required></textarea>
            </div>

        </div>
        <div class="row">
            <div class="file-field input-field col s11">
                <div class="btn indigo">
                    <span>File</span>
                    <input type="file" name="foto" multiple required>
                </div>
                <div class="file-path-wrapper">
                    <input class="file-path validate" type="text" required>
                </div>
            </div>
            <br>
            <button class="btn col s1 green waves-effect waves-dark" name="save">Simpan</button>
        </div>

    </form>
<?php   } ?>

<?php 
if (isset($_POST['save'])) 
{
    $namabukti=$_FILES["foto"]["name"];
    $lokasibukti=$_FILES["foto"]["tmp_name"];
    $fotop=date("YmdHis").$namabukti;

    $ekstensiGambarValid = ['jpg','jpeg','png'];
    $ekstensiGambar = explode('.', $fotop);
    $ekstensiGambar = strtolower(end($ekstensiGambar));
    if(!in_array($ekstensiGambar, $ekstensiGambarValid)){
        echo "<script>
        alert('yang anda upload bukan gambar!');
        </script>
        ";
    } else{
        move_uploaded_file($lokasibukti, "../assets/img/produk/$fotop");

        $stmt = $koneksi->prepare("INSERT INTO produk
            (id_toko,nama_produk,harga_produk,berat_produk,foto_produk,deskripsi_produk,stok_produk,id_kategori,stok_awal) 
            VALUES(:id_toko,:nama,:harga,:berat,:foto,:deskripsi,:stok,:id_kategori,:stok_awal)");
        $stmt->execute([
            ':id_toko' => $id_toko,
            ':nama' => $_POST['nama'],
            ':harga' => $_POST['harga'],
            ':berat' => $_POST['berat'],
            ':foto' => $fotop,
            ':deskripsi' => $_POST['deskripsi'],
            ':stok' => $_POST['stok_produk'],
            ':id_kategori' => $_POST['id_kategori'],
            ':stok_awal' => $_POST['stok_produk']
        ]);

        echo "<script>alert('Produk Berhasil Ditambahkan');</script>";
        echo "<meta http-equiv='refresh' content='1;url=index.php?halaman=produk'>";
    }
} 
?>

<script>
    //tambah foto
    // $(document).ready(function(){
    //  $(".btn-tambah").on("click",function(){
    //      $(".letak-input").append("<input type='file' class='form-control' name='foto[]'>");
    //  })
    // });
</script>