<?php
function isLoggedIn(){
    return isset($_SESSION['user']);
}

function login($username, $password){
    // Demo user (In real system use database)
    $users = [
        'risa' => password_hash('admin2323', PASSWORD_DEFAULT)
    ];

    if(isset($users[$username]) && password_verify($password, $users[$username])){
        $_SESSION['user'] = $username;
        return true;
    }

    return false;
}
?>
