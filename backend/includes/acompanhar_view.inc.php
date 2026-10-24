<?php
declare(strict_types=1);

function check_acomp_errors()
{
    if (isset($_SESSION['errors_acomp'])) {
        $errors = $_SESSION['errors_acomp'];
        foreach ($errors as $error) {
            echo "<p>$error</p>";
        }
        unset($_SESSION['errors_acomp']);
    }
} 
