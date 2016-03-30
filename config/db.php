<?php

require_once(__DIR__ . '/passwords.php');

return [
        'class' => 'yii\db\Connection',
        'dsn' => 'sqlsrv:Server=10.2.8.6;Database=ADUser',
        'username' => $db_login,
        'password' => $db_password,
    ];