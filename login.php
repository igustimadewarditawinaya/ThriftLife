<?php 	
session_start();
include 'koneksi.php';
?>
<!DOCTYPE html>
<html>
<head>
	<?php 	include "header.php" ;?>
	<title>LOGIN | Thriftlife</title>
	<body>
		<!--Navbar-->
		<?php include"navbar.php" ?>
		<br>	
		<div class="container">	
			<div class="row">
				<div class="col s12">
					<div class="card-panel hoverable" style="margin:150px 275px 0px 275px; padding-bottom: 50px;">
						<span class="card-title"><h3><center>LOGIN</center></h3></span>
						<span class="white-text">
							<form method="post">
								<div class="input-field">
									<i class="material-icons prefix">email</i>
									<input id="icon_email" type="email" class="validate" name="email" required autocomplete="off">
									<label for="icon_email">Email</label>
								</div>
								<div class="input-field">
									<i class="material-icons prefix">lock</i>
									<input id="icon_lock" type="password" required class="validate" name="password">
									<label for="icon_lock">Password</label>
								</div>	

								<button class="btn right" name="login">Login</button>
							</form>
						</span>
					</div>
				</div>
			</div>
		</div>
		<?php 	
// jika ada tombol login (tombol login ditekan)
		if (isset ($_POST["login"]))
		{
			$email = $_POST["email"];
			$password = sha1($_POST["password"]);
			
			try {
				// PostgreSQL uses PDO
				$stmt = $koneksi->prepare("SELECT * FROM pelanggan WHERE email_pelanggan = ? AND password_pelanggan = ?");
				$stmt->execute([$email, $password]);
				$akun = $stmt->fetch(PDO::FETCH_ASSOC);
				$akunyangcocok = $akun ? 1 : 0;

				//jika 1 akun yang cocok, maka diloginkan
				if ($akunyangcocok == 1)
				{
					//anda sudah login
					//mendapatkan akun dalam array
					// simpern di session pelanggan
					$_SESSION['pelanggan'] = $akun;
					
					// Check if user is a seller (only if status column exists)
					if (isset($_SESSION['pelanggan']['status']) && $_SESSION['pelanggan']['status'] == 'penjual') {
						$_SESSION['penjual'] = $_SESSION['pelanggan']['id_pelanggan'];
					}
					
					echo "<script>alert('anda berhasil login');</script>";

					// jika sudah belanja
					if (isset($_SESSION["keranjang"]) && !empty($_SESSION["keranjang"])) 
					{
						echo "<script>location='checkout.php';</script>";
					}
					else{
						echo "<script>location='index.php';</script>";
					}
				}
				else
				{
					//anda gagal login
					echo "<script>alert('anda gagal login, periksa akun anda');</script>";
					echo "<script>location='login.php';</script>";
				}
			} catch (Exception $e) {
				echo "<script>alert('Error: " . $e->getMessage() . "');</script>";
				echo "<script>location='login.php';</script>";
			}
		}
		?>


	</body>
	</html>