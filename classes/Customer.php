<?php

require_once '../helpers/helper.php';

class Customer {
    public $name = '';
    public $email = '';
    public $password = '';
    public $role = 'Customer';
    public $errors = [];

    public function __construct($name, $email, $password)
    {
        $this->name = $name;
        $this->email = $email;
        $this->password = $password;
    }

    public function registerCustomer()
    {
        $this->validateData();
        
        if (count($this->errors) == 0) {

            $this->storeData();

            if ($_SESSION['username'] === 'Admin') {
                flashMessage('register-success', 'Customer registered successfully.');
                header('Location:customers.php');
                exit;
            } else {
                flashMessage('register-success', 'User registered successfully.');
                header('Location:../login.php');
                exit;
            }
        }
    }

    public function validateData()
    {
        $this->errors = [];
        
        // Validation checking for user's name
        if (empty($this->name)) {
            $this->errors['name'] = 'You must enter your name';
        } else {
            $this->name = sanitize($this->name);
        
            if (strlen($this->name) < 3) {
                $this->errors['name'] = 'Your name should be atleast 3 characters.';
            }
        }
        
        // Validation checking for user's email
        if (empty($this->email)) {
            $this->errors['email'] = 'You must enter your email';
        } else {
            $this->email = sanitize($this->email);
        
            if (!filter_var($this->email, FILTER_VALIDATE_EMAIL)) {
                $this->errors['email'] = 'Enter a valid email';
            }
        }
        
        // Validation checking for user's password
        if (empty($this->password)) {
            $this->errors['password'] = 'You must enter your password';
        } else {
            $this->password = sanitize($this->password);
        
            if (strlen($this->password) < 8) {
                $this->errors['password'] = 'Your password should be atleast 8 characters.';
            }
        }
    }

    public function storeData()
    {
        $userId = uniqid();
        $myFile = fopen('../data/users.txt', 'a') or die('Unable to open file!');
        $userData = $userId . ',' . $this->name . ',' . $this->email . ',' . password_hash($this->password, PASSWORD_DEFAULT) . ',' . $this->role . PHP_EOL;
        fwrite($myFile, $userData);
        fclose($myFile);

        // Create an entry with zero balance associated with the user
        $myFile = fopen('../data/balances.txt', 'a') or die('Unable to open file!');
        $balanceData = $userId . ',' . $this->email . ',0.0'. PHP_EOL;
        fwrite($myFile, $balanceData);
        fclose($myFile);
    }
}

?>