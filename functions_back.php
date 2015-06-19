<!DOCTYPE html>
<html lang="en">
<head>
    <title>
        Crystals, Charms, and Coffee
    </title>
    <meta charset="utf-8">
    <link rel="stylesheet" href="final.css">
</head>

<body>
<div class="wrapper">



<?php

if (!isset($_SESSION)) {
    session_start();

}

require_once('FirePHP.class.php');

if (!$firephp) {
    ob_start();

    $firephp = FirePHP::getInstance(true);

}

echo '<div class="cart_display">';

if (isset($_SESSION['sign_in']) && $_SESSION['sign_in'] == 1) {
    echo "Your Cart:<br>";
    print '<form name="checkout_button" action="index.php?checkout=1" method="POST">';

    if (isset($_SESSION['out_cart'])) {

        $display_cart = build_display_cart($_SESSION['out_cart']);

        echo $display_cart;

    }

    print '<input type="submit" value="Checkout" ></form>';
}
print '</div><!--end div.cart_display-->';


echo '<div class="banner">
       <img class="banner" src="img/banner.jpg">
<!--<h1>NOW EMAIL IS BROKEN AGAIN SENDS BUT NO ITEMS</h1>-->

      </div><!--end div.banner-->';
if ((isset($_SESSION['admin']) && !isset($_GET['admin'])) ||  isset($_GET['admin']) &&  intval($_GET['admin']) < 2) {
    echo '<div class="admin_button">
  <a href="http://localhost/sql_final/index.php?admin=2">Go To Admin</a>
</div><div class="login">
    <form class="login" action="index.php" method="POST">
        <label for="username">Name</label><br>
        <input class="login" type="text" size="20" name="username"/><br>
        <label for="password" class ="login">Password</label><br>
        <input type="password" class="login" name="password" size="20"><br>
        <input type="submit" value="Sign In">
    </form>

    <div class="sign_out">
       <form class="login" action="index.php?out=1" method="GET">
          <input type="text" name="out" value="1" hidden>
          <input type="submit" value="Sign Out">
       </form>';
}

if ((isset($_SESSION['admin']) && !isset($_GET['admin'])) ||  isset($_GET['admin']) &&  intval($_GET['admin']) < 2) {
    echo '<div class="admin_button">
  <a href="http://localhost/sql_final/index.php?admin=2">Go To Admin</a>
</div>
</div><div class="login">
    <form class="login" action="index.php" method="POST">
        <label for="username">Name</label><br>
        <input class="login" type="text" size="20" name="username"/><br>
        <label for="password" class ="login">Password</label><br>
        <input type="password" class="login" name="password" size="20"><br>
        <input type="submit" value="Sign In">
    </form>

    <div class="sign_out">
       <form class="login" action="index.php?out=1" method="GET">
          <input type="text" name="out" value="1" hidden>
          <input type="submit" value="Sign Out">
       </form>';
}


elseif(isset($_GET['admin'])) {
    echo '<a href="http://localhost/sql_final/index.php">Leave Admin Site</a>
<div class="login">
    <form class="login" action="index.php" method="POST">
        <label for="username">Name</label><br>
        <input class="login" type="text" size="20" name="username"/><br>
        <label for="password" class ="login">Password</label><br>
        <input type="password" class="login" name="password" size="20"><br>
        <input type="submit" value="Sign In">
    </form>

    <div class="sign_out">
       <form class="login" action="index.php?out=1" method="GET">
          <input type="text" name="out" value="1" hidden>
          <input type="submit" value="Sign Out">
       </form>';
}

else {
    echo '
    <div class="login">
    <form class="login" action="index.php" method="POST">
        <label for="username">Name</label><br>
        <input class="login" type="text" size="20" name="username"/><br>
        <label for="password" class ="login">Password</label><br>
        <input type="password" class="login" name="password" size="20"><br>
        <input type="submit" value="Sign In">
    </form>

    <div class="sign_out">
       <form class="login" action="index.php?out=1" method="GET">
          <input type="text" name="out" value="1" hidden>
          <input type="submit" value="Sign Out">
       </form>';}

if (!isset($_SESSION['sign_in'])) {
    print  '<div class="sign_in_form"><span class="require_auth">Sign in to Purchase Items</span></div>';

}
echo '</div><!--end div.sign_out-->
</div><!--end div.login-->';

/*if ((isset($_SESSION['admin']) && !isset($_GET['admin'])) ||  isset($_GET['admin']) &&  intval($_GET['admin']) < 2) {
    echo '<div class="admin_button">
  <a href="http://localhost/sql_final/index.php?admin=2">Go To Admin</a>
</div>';
}*/
/*
elseif(isset($_GET['admin'])) {
    echo '<a href="http://localhost/sql_final/index.php">Leave Admin Site</a>';
}*/


$firephp->log($_SESSION, 'session');
$firephp->log($_GET,'get');
