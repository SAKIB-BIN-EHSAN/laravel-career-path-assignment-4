<?php

session_start();

function sanitize(string $value)
{
    return htmlspecialchars(stripslashes(trim($value)));
}

/*
* Get current balance
*/
function getCurrentBalanceOfLoggedInUser(): ?float
{
    $userEmail = $_SESSION['useremail'];

    // Read data from file
    $fileName = "../data/balances.txt";
    $myFile = fopen($fileName, "r") or die('Unable to open file');
    $balances = file($fileName, FILE_IGNORE_NEW_LINES);

    foreach ($balances as $balance) {
    $balanceInfo = explode(",", $balance);

    if ($balanceInfo[1] == $userEmail) {
        $currentBalance = floatval($balanceInfo[2]);
        break;
    }
    }
    fclose($myFile);

    return $currentBalance;
}

function flashMessage($key, $message = null)
{
    if (isset($message)) {
        $_SESSION['flash'][$key] = $message;
    } else {
        $message = $_SESSION['flash'][$key];
        unset($_SESSION['flash'][$key]);

        return $message;
    }
}

?>