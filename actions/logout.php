<?php
    unset($_SESSION['uid']);
    unset($_SESSION['user']);
    unset($_SESSION['uname']);
    unset($_SESSION['perm']);
    unset($_SESSION['cart']);
    unset($_SESSION['selclient']);
    redirect(url('login'));
?>