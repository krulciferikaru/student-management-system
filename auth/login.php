<?php
require_once '../database/Database.php';
require_once '../models/User.php';

session_start(); 
$db = new Database();
$conn = $db->getConnection();
User::setConnection($conn);


if (isset($_SESSION['email'])) {
    header('Location: ../index.php');
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') { 
    $email = $_POST['email'];
    $user = User::findEmail($email); 
    if ($user) { 
        if (password_verify($_POST['password'], $user->password)) { 
            if ($user->status != 'Active') { 
                $_SESSION['error'] = 'User Account Inactive';
                header('Location: login.php');
                exit(); 
            }
            $_SESSION['email'] = $email;
            $_SESSION['role'] = $user->role;
            $_SESSION['name'] = $user->name;
            header('Location: ../index.php'); 
        
            exit();
        } else {
            $_SESSION['error'] = 'Invalid email or password'; //if mali password
        }
    } else {
        $_SESSION['error'] = 'Invalid email or password'; //if mali email 
    }
}

include '../layout/header.php';

?>
<!-- make sure na inside sa head ito -->
<link rel="stylesheet" href="../css/login.css">

</head>

<body>
    <div class="overlay"></div>

    <div class="container vh-100 d-flex align-items-center justify-content-center position-relative">
        <div class="glass-container d-flex flex-column flex-md-row mx-auto">
            <!-- Left Side -->
            <div class="left-panel d-flex flex-column justify-content-center text-center text-white">
                <h4>Welcome to EduTrack</h4>
                <p>EduTrack is a simple and efficient way to manage student and course records.</p>
            </div>

            <!-- Right Side -->
            <div class="right-panel d-flex flex-column justify-content-center align-items-center">
                <h1 class="fw-bold text-center text-white mb-4">Login</h1>
                <form method="POST" action="login.php">
                    <div class="mb-3">
                        <label for="email" class="form-label">Email</label>
                        <div class="input-group">
                            <span class="input-group-text">
                                <i class="fa fa-envelope" style="color: white;"></i>
                            </span>
                            <input type="email" id="email" name="email"
                                class="form-control no-focus <?= (isset($_SESSION['error']) ? 'is-invalid' : '') ?>"
                                placeholder="Enter Email" required>
                        </div>
                        <?php if (isset($_SESSION['error'])) : ?>
                            <div class="invalid-feedback d-block"> <?= $_SESSION['error'] ?> </div>
                            <?php unset($_SESSION['error']); ?>
                        <?php endif; ?>
                    </div>

                    <div class="mb-3">
                        <label for="password" class="form-label">Password</label>
                        <div class="input-group">
                            <span class="input-group-text">
                                <i class="fa fa-lock" style="color: white;"></i>
                            </span>
                            <input type="password" id="password" name="password" class="form-control no-focus" placeholder="Enter Password" required>
                        </div>
                    </div>
                    <div class="mt-4">
                        <button type="submit" class="btn login-button w-100">Login</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</body>

</html>

<?php include '../layout/footer.php'; ?>