<?php
include "koneksi.php";
?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <title>Register Ke Perpustakaan Digital</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://use.fontawesome.com/releases/v6.3.0/js/all.js" crossorigin="anonymous"></script>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary-color: #2C3E50;
            --secondary-color: #E74C3C;
            --accent-color: #3498DB;
            --background-color: #ECF0F1;
        }

        * {
            font-family: 'Poppins', sans-serif;
        }

        body {
            background: linear-gradient(150deg, #2C3E50, #3498DB);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 2rem 0;
        }

        .card {
            background: rgba(255, 255, 255, 0.95);
            border: none;
            border-radius: 20px;
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.2);
            backdrop-filter: blur(10px);
            max-width: 500px;
            width: 90%;
            margin: auto;
            overflow: hidden;
        }

        .card-header {
            background: #2C3E50;
            color: white;
            text-align: left;
            padding: 20px;
            border-radius: 20px 20px 0 0;
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .header-image {
            width: 60px;
            height: 60px;
            object-fit: cover;
            border-radius: 50%;
        }

        .card-body {
            padding: 30px;
        }

        .input-group {
            margin-bottom: 1.5rem;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        .input-group-text {
            background-color: var(--primary-color);
            border: none;
            color: white;
            padding: 0.8rem;
        }

        .form-control {
            border: none;
            padding: 0.8rem;
            font-size: 1rem;
            background: #fff;
        }

        .form-control:focus {
            box-shadow: none;
            border-color: var(--accent-color);
        }

        .btn {
            padding: 0.8rem 2rem;
            font-weight: 500;
            border-radius: 10px;
            letter-spacing: 0.5px;
            transition: all 0.3s ease;
        }

        .btn-primary {
            background: var(--accent-color);
            border: none;
            box-shadow: 0 4px 15px rgba(52, 152, 219, 0.3);
        }

        .btn-primary:hover {
            background: #2980b9;
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(52, 152, 219, 0.4);
        }

        .btn-danger {
            background: var(--secondary-color);
            border: none;
            box-shadow: 0 4px 15px rgba(231, 76, 60, 0.3);
        }
        .login-link {
            display: inline-block;
            margin-top: 1.5rem;
            color: var(--secondary-color);
            text-decoration: none;
            font-weight: 500;
            transition: all 0.3s ease;
        }

        .login-link:hover {
            color: #c0392b;
            text-decoration: underline;
        }

        .btn-danger:hover {
            background: #c0392b;
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(231, 76, 60, 0.4);
        }

        .error-message {
            color: var(--secondary-color);
            font-size: 0.8rem;
            margin-top: 0.25rem;
        }

        .floating-shapes {
            position: fixed;
            width: 100%;
            height: 100%;
            top: 0;
            left: 0;
            z-index: -1;
            pointer-events: none;
        }

        .shape {
            position: absolute;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.05);
        }

        .shape:nth-child(1) { width: 100px; height: 100px; top: 20%; left: 10%; }
        .shape:nth-child(2) { width: 150px; height: 150px; top: 60%; right: 15%; }
        .shape:nth-child(3) { width: 80px; height: 80px; bottom: 20%; left: 20%; }
    </style>
</head>
<body>
    <div class="floating-shapes">
        <div class="shape"></div>
        <div class="shape"></div>
        <div class="shape"></div>
    </div>
    <div class="card">
        <div class="card-header">
            <img src="assets/img/ebuk.jpg" alt="Logo" class="header-image">
            <div>
                <h4>Perpustakaan Digital</h4>
                <p>Silahkan register untuk melanjutkan</p>
            </div>
        </div>
        <div class="card-body">


                        <?php
                        if (isset($_POST['register'])) {
                            $nama = mysqli_real_escape_string($koneksi, $_POST['nama']);
                            $email = mysqli_real_escape_string($koneksi, $_POST['email']);
                            $no_telepon = mysqli_real_escape_string($koneksi, $_POST['no_telepon']);
                            $username = mysqli_real_escape_string($koneksi, $_POST['username']);
                            $password = $_POST['password'];
                            $level = "peminjam"; 

                            $cek_username = mysqli_query($koneksi, "SELECT * FROM user WHERE username = '$username'");
                            
                            if (mysqli_num_rows($cek_username) > 0) {
                                echo '<script>alert("Username sudah digunakan, silakan gunakan username lain.");</script>';
                            } 
                            else if (!ctype_digit($no_telepon) || strlen($no_telepon) < 10 || strlen($no_telepon) > 15) {
                                echo '<script>alert("Nomor telepon harus berupa angka dengan panjang 10-15 digit.");</script>';
                            } 
                            else if (strlen($password) < 8) {
                                echo '<script>alert("Password minimal harus 8 karakter.");</script>';
                            } 
                            else {
                                $password_md5 = md5($password);
                                
                                $insert = mysqli_query($koneksi, "INSERT INTO user (nama, email, no_telepon, username, password, level) 
                                    VALUES ('$nama', '$email',  '$no_telepon', '$username', '$password_md5', '$level')");

                                if ($insert) {
                                    echo '<script>alert("Registrasi berhasil! Silakan login."); location.href="login.php";</script>';
                                } else {
                                    echo '<script>alert("Registrasi gagal, silakan coba lagi.");</script>';
                                }
                            }
                        }
                        ?>
            <form method="post">
                <div class="input-group mb-3">
                    <span class="input-group-text"><i class="fas fa-user"></i></span>
                    <input class="form-control" type="text" name="nama" required placeholder="Nama Lengkap" />
                </div>
                <div class="input-group mb-3">
                    <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                    <input class="form-control" type="email" name="email" required placeholder="Email" />
                </div>
                <div class="input-group mb-3">
                    <span class="input-group-text"><i class="fas fa-phone"></i></span>
                    <input class="form-control" type="text" id="no_telepon" name="no_telepon" required placeholder="No. Telepon" maxlength="15" />
                </div>
                <div id="phoneError" class="error-message"></div>
                
                <div class="input-group mb-3">
                    <span class="input-group-text"><i class="fas fa-user-circle"></i></span>
                    <input class="form-control" type="text" id="username" name="username" required placeholder="Username" />
                </div>
                <div id="usernameError" class="error-message" style="display: none;"></div>
                
                <div class="input-group mb-3">
                    <span class="input-group-text"><i class="fas fa-lock"></i></span>
                    <input class="form-control" type="password" id="password" name="password" required placeholder="Password (minimal 8 karakter)" />
                </div>
                <div id="passwordError" class="error-message"></div>
                
                <div class="d-grid gap-2">
                    <button class="btn btn-primary" type="submit" name="register">
                        <i class="fas fa-user-plus me-2"></i>Register
                    </button>
                    <p align = "center">
                    <a href="login.php" class="login-link">
                            <i class="fas fa-user-plus me-1"></i>Sudah Punya Akun
                        </a></p>
                </div>
            </form>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="js/scripts.js"></script>
    <script>
        document.getElementById('username').addEventListener('input', function() {
            const username = this.value;
            const usernameError = document.getElementById('usernameError');
            
            if (username.length > 0) {
                usernameError.style.display = 'block'; 
                usernameError.textContent = 'Gunakan username yang unik.'; 

                const xhr = new XMLHttpRequest();
                xhr.open('GET', 'check_username.php?username=' + encodeURIComponent(username), true);
                xhr.onload = function() {
                    if (xhr.responseText === 'taken') {
                        usernameError.textContent = 'Username sudah digunakan.'; 
                    } else {
                        usernameError.textContent = 'Gunakan username yang unik.'; 
                    }
                };
                xhr.send();
            } else {
                usernameError.style.display = 'none'; 
            }
        });

        document.getElementById('password').addEventListener('input', function() {
            const passwordError = document.getElementById('passwordError');
            passwordError.textContent = ''; 

            if (this.value.length < 8) {
                passwordError.textContent = 'Password minimal 8 karakter.';
            }
        });

        document.getElementById('no_telepon').addEventListener('input', function() {
            const phoneError = document.getElementById('phoneError');
            phoneError.textContent = ''; 

            const no_telepon = this.value;

            if (no_telepon.length > 15) {
                this.value = no_telepon.slice(0, 15); 
                phoneError.textContent = 'Nomor telepon tidak boleh lebih dari 15 digit.';
            } else if (!/^\d*$/.test(no_telepon)) {
                phoneError.textContent = 'Nomor telepon harus berupa angka.';
            } else if (no_telepon.length < 10) {
                phoneError.textContent = 'Nomor telepon minimal 10 digit.';
            }
        });
    </script>
</body>
</html>