<?php
require_once '../Classes/UserAccount.php';

$user = new UserAccount(null, null);
$user->logout();