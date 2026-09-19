<?php

    session_start(); //start session para magamit yung session variable

    
    if(isset($_SESSION['email'])){

        include '../layout/header.php';

        session_unset(); //unsets all set session variables

        session_destroy(); //destroys the session
    
        echo "<script>
                Swal.fire({
                    title: 'Logged out!',
                    text: 'You have been logged out.',
                    icon: 'success',
                    confirmButtonText: 'Ok'
                }).then(function() {
                    window.location = 'login.php';
                });
            </script>";
    } else {
        echo "<script>
                Swal.fire({
                    title: 'Error!',
                    text: 'Failed to log out, try again.',
                    icon: 'error',
                    confirmButtonText: 'Ok'
                }).then(function() {
                    window.location = '/student-rms/student-rms/index.php';
                });
            </script>";
    }

    include '../layout/footer.php';