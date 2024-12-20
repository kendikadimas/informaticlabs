<?php
session_start();
require 'koneksi.php';

$message = ""; // Variabel untuk menyimpan pesan kesalahan

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST["email"];
    $password = $_POST["password"];
    $role = "Praktikan";

    // Menggunakan prepared statement untuk mencegah SQL Injection
    $stmt = $conn->prepare("SELECT * FROM user WHERE (email = ? OR username = ?) AND password = ? AND role = 'Praktikan'");
    $stmt->bind_param("sss", $email, $email, $password);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($role !== "Praktikan") {
        $message = "Anda bukan praktikan.";
        exit();
    } 

    if ($result->num_rows > 0) {
        // Ambil data pengguna
        $user = $result->fetch_assoc();

        // Simpan data ke dalam session
        $_SESSION['namalengkap'] = $user['namalengkap'];
        $_SESSION['nim'] = $user['nim'];
        $_SESSION['prodi'] = $user['prodi'];
        $_SESSION['email'] = $user['email'];
        $_SESSION['username'] = $user['username'];

        // Redirect ke halaman dashboard
        header("Location: dashboard.php");
        exit();
    } else {
        $message = "Email atau Password Anda salah. Silakan coba lagi.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="stylei.css" media="screen" title="no title">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.6.3/css/all.css">
    
    <title>Login Page</title>
    <style>
        /* Pop-up styling */
        .popup {
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            background: #fff;
            border-radius: 10px;
            padding: 20px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
            width: 90%;
            max-width: 400px;
            z-index: 1000;
            display: none;
            animation: fadeIn 0.3s ease-out;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translate(-50%, -60%);
            }
            to {
                opacity: 1;
                transform: translate(-50%, -50%);
            }
        }

        .popup .close-btn {
            text-align: right;
        }

        .popup .close-btn button {
            background: none;
            border: none;
            font-size: 1.5rem;
            cursor: pointer;
            color: #666;
            transition: color 0.3s;
        }

        .popup .close-btn button:hover {
            color: #000;
        }

        .popup p {
            margin: 10px 0;
            font-size: 1rem;
            text-align: center;
            color: #333;
        }

        /* Error message styling */
        .error-message {
            color: red;
            text-align: center;
            margin-top: 10px;
            font-size: 1rem;
        }

        /* Overlay styling */
        .overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            display: none;
            z-index: 999;
        }
    </style>
</head>
<body>
  
    <div class="input">
        <h1>LOGIN</h1>
        <form action="" method="POST">
            <div class="box-input">
                <i class="fas fa-envelope-open-text"></i>
                <input type="text" name="email" placeholder="Email or Username" required>
            </div>
            <div class="box-input">
                <i class="fas fa-lock"></i>
                <input type="password" name="password" placeholder="Password" required>
            </div>
            <button type="submit" name="login" class="btn-input">Login</button>
            <div class="bottom">
                <p>Belum punya akun kan?
                    <a href="register.php">Register disini</a>
                </p>
            </div>
        </form>
    </div>


    <!-- Overlay -->
    <div id="overlay" class="overlay" onclick="closePopup()"></div>

    <!-- Popup modal -->
    <div id="errorPopup" class="popup">
        <div class="close-btn">
            <button onclick="closePopup()">×</button>
        </div>

        <p><?= htmlspecialchars($message) ?></p>
    </div>

    <script>
        // Menampilkan pop-up jika ada pesan kesalahan
        const errorMessage = "<?= $message ?>";
        if (errorMessage) {
            document.getElementById('errorPopup').style.display = 'block';
            document.getElementById('overlay').style.display = 'block';
        }

        // Fungsi untuk menutup pop-up
        function closePopup() {
            document.getElementById('errorPopup').style.display = 'none';
            document.getElementById('overlay').style.display = 'none';
        }
    </script>
</body>

</html>
