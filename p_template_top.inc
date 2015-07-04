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

echo '<div class="cart_display">';

// Displays shopping cart and checkout button if user is signed in.
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

      </div><!--end div.banner-->

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

if (!isset($_SESSION['sign_in'])) {
    print  '<div class="sign_in_form"><span class="require_auth">Sign in to Purchase Items</span></div>';

}
echo '</div><!--end div.sign_out-->
</div><!--end div.login-->';

// This if and the subsequent elseif statement toggle the "Enter admin area" and "leave admin area" link depending on user's location.
if ((isset($_SESSION['admin']) && !isset($_GET['admin'])) ||  isset($_GET['admin']) &&  intval($_GET['admin']) < 2) {
    echo '<div class="admin_button">
  <a href="http://ptwickle.userworld.com/phpsql1/final/index.php?admin=2">Enter Admin Area</a>
</div>';
}

elseif(isset($_GET['admin']) && $_GET['admin'] >= 2) {
    echo '<div class="admin_button"><a href="http://ptwickle.userworld.com/phpsql1/final/index.php">Leave Admin Area</a></div><!-- end .admin_button-->';
}



