<?php
include 'koneksi.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="description" content="" />
    <meta name="author" content="" />
    <title>Login Ke Perpustakaan Digital</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://use.fontawesome.com/releases/v6.3.0/js/all.js" crossorigin="anonymous"></script>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary-color: #2C3E50;    /* Navy Blue */
            --secondary-color: #E74C3C;   /* Coral Red */
            --accent-color: #3498DB;      /* Sky Blue */
            --background-color: #ECF0F1;  /* Light Gray */
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
            background: var(--primary-color);
            padding: 1rem 1.5rem;
            text-align: center;
            border: none;
            position: relative;
            overflow: hidden;
        }

        .card-header::before {
            content: '';
            position: absolute;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: linear-gradient(45deg, transparent, rgba(255, 255, 255, 0.1), transparent);
            transform: rotate(45deg);
            animation: shine 3s infinite;
        }

        @keyframes shine {
            0% { transform: translateX(-100%) rotate(45deg); }
            100% { transform: translateX(100%) rotate(45deg); }
        }

        .header-image {
    width: 100px;
    height: 100px;
    object-fit: cover;
    border-radius: 50%;
    margin-bottom: 10px;
}

        .card-header h3 {
            color: #fff;
            font-size: 1.8rem;
            font-weight: 600;
            margin: 0;
            text-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .card-body {
            padding: 2.5rem;
        }

        .form-label {
            color: var(--primary-color);
            font-weight: 500;
            margin-bottom: 0.5rem;
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

        .register-link {
            display: inline-block;
            margin-top: 1.5rem;
            color: var(--secondary-color);
            text-decoration: none;
            font-weight: 500;
            transition: all 0.3s ease;
        }

        .register-link:hover {
            color: #c0392b;
            text-decoration: underline;
        }
        .login-link {
            display: inline-block;
            
            color: var(--primary-color);;
            text-decoration: none;
            font-weight: 500;
            transition: all 0.3s ease;
        }
        .login-link:hover {
            color:rgb(10, 4, 4);
            text-decoration: underline;
        }

        .card-footer {
            background: var(--background-color);
            padding: 1.5rem;
            text-align: center;
            border: none;
        }

        .card-footer .small {
            color: var(--primary-color);
            font-weight: 500;
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
            <p>Silahkan login untuk melanjutkan</p>
        </div>
    </div>
    <div class="card-body">
        <form method="POST"> 
            <div class="mb-3">
                <?php
                if(isset($_POST['login'])) {
                    $email = $_POST['email'];
                    $password = md5($_POST['password']);
                    $query = "SELECT * FROM user WHERE email='$email' AND password='$password'";
                    $data = mysqli_query($koneksi, $query);

                    if (!$data) {
                        die("Query Error: " . mysqli_error($koneksi));
                    }

                    $cek = mysqli_num_rows($data);
                    if($cek > 0 ){
                        $_SESSION['user'] = mysqli_fetch_assoc($data);
                        echo '<script>alert("Selamat Datang, Login Berhasil"); location.href="index.php";</script>';
                    }else{
                        echo '<div class="alert alert-danger alert-dismissible fade show" role="alert">
                                Username atau Password salah!
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                              </div>';
                    }        
                }        
                ?>
                <form method="POST">
                    <div class="mb-4">
                        <label class="form-label" for="email">Email</label>
                        <div class="input-group">
                            <span class="input-group-text">
                                <i class="fas fa-user"></i>
                            </span>
                            <input class="form-control" id="email" type="email" name="email" 
                                placeholder="Masukkan email" required />
                        </div>
                    </div>
                    <div class="mb-4">
                        <label class="form-label" for="password">Password</label>
                        <div class="input-group">
                            <span class="input-group-text">
                                <i class="fas fa-lock"></i>
                            </span>
                            <input class="form-control" id="password" type="password" name="password" 
                                placeholder="Masukkan password" required />
                        </div>
                        <p  align ="right">
                        <a  href="forget_password.php" class="login-link">
                            <i class="fas fa-user-plus me-2"></i>Lupa Password
                        </a>
            </p>
                    <div class="text-center">
                        <button class="btn btn-primary w-100" type="submit" name="login" value="login">
                            <i class="fas fa-sign-in-alt me-2"></i>Masuk
                        </button>
                        <a href="register.php" class="register-link" >
                            <i class="fas fa-user-plus me-1"></i>Daftar Akun Baru
                        </a>
                    </div>
                </form>
            </div>
           
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>