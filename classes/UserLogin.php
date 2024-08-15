<?php

require_once './helpers/helper.php';

class UserLogin {

    public $email = '';
    public $password = '';
    public $errors = [];

    public function __construct($email, $password)
    {
        $this->email = $email;
        $this->password = $password;
    }

    public function login()
    {
        $this->validateData();

        if (count($this->errors) == 0) {
            // Read data from file
            $myFile = fopen('data/users.txt', 'r') or die('Unable to read data!');
            $filename = "data/users.txt";
            $users = file($filename, FILE_IGNORE_NEW_LINES);

            fclose($myFile);

            if (count($users) === 0) {
                $this->errors['auth-error'] = 'Invalid email or password.';
            } else {
                foreach ($users as $user) {
                    $userInfo = explode(",", $user);
    
                    // For admin login
                    if ($this->email == "admin@admin.com" && $userInfo[2] == $this->email && password_verify($this->password, $userInfo[3])) {
                        $_SESSION['user_id'] = $userInfo[0];
                        $_SESSION['username'] = $userInfo[1];
                        $_SESSION['useremail'] = $userInfo[2];
                        flashMessage('login-success', 'Logged-in successfully.');
                        header('Location:admin/customers.php');
                        exit;
                    }    
                    // For customer login
                    else if ($userInfo[4] == "Customer" && $userInfo[2] == $this->email && password_verify($this->password, $userInfo[3])) {
                        $_SESSION['user_id'] = $userInfo[0];
                        $_SESSION['username'] = $userInfo[1];
                        $_SESSION['useremail'] = $userInfo[2];
                        flashMessage('login-success', 'Logged-in successfully.');
                        header('Location:customer/dashboard.php');
                        exit;
                    }
                    else {
                        $this->errors['auth-error'] = 'Invalid email or password.';
                    }
                }
            }
        }
    }

    public function validateData()
    {
        $this->errors = [];

        // Validation checking for user's email
        if (empty($this->email)) {
            $this->errors['email'] = 'You must enter your email';
        }
        else {
            $this->email = sanitize($this->email);
        
            if (!filter_var($this->email, FILTER_VALIDATE_EMAIL)) {
                $this->errors['email'] = 'Enter a valid email';
            }
        }
        
        // Validation checking for user's password
        if (empty($this->password)) {
            $this->errors['password'] = 'You must enter your password';
        }
        else {
            $this->password = sanitize($this->password);
        
            if (strlen($this->password) < 8) {
                $this->errors['password'] = 'Your password should be atleast 8 characters.';
            }
        }
    }
}


?>