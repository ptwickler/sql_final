<?php

ini_set('display_errors', 1);

error_reporting(E_ALL);



/*
 * The index page controls the user's path through the site. There are three different "modes" which will cause the
 * page to display differently. First, the "normal" mode which is a user is either signed in, or not, but has not
 * selected that they want to check out (products are displayed). Second, "checkout" mode. The user must be signed in to
 * click the checkout button. If selected, the products are not displayed, but the user's items for purchase are.
 * Thirdly, "register_new" mode. It displays the products, but also displays the registration form.
 */

ob_start();


require_once('functions.php');

if (isset($_GET['out']) && $_GET['out']==1){
    session_start();

    // I'll be honest. I had to poach the code in this if statement. I couldn't get the session ID to regenerate any
    // other way. It would wipe out the data in the session, but it wouldn't renew the session ID. I was having some
    // other problems and wanted to get this piece nailed down before I went on to look for another cause.
    if (ini_get("session.use_cookies")) {
        $params = session_get_cookie_params();
        setcookie(session_name(), '', time() - 42000,
            $params["path"], $params["domain"],
            $params["secure"], $params["httponly"]
        );
    }
// END POACHED CODE

    // Finally, destroy the session.
    session_destroy();

    //Add in a page reload so that the session_destroy() will take effect

    $url = "http://" . $_SERVER['HTTP_HOST'] . "/phpsql1/final/index.php";

    header("Location: ".$url) or die("Didn't work");
}

if(!isset($_SESSION)) {
    session_start();
}

$_SESSION['cart'] = array();

// The valid property of the session will be used to store values for form validation. If it's set, don't change it,
// but if it's not set, set it.
if (!isset($_SESSION['valid'])) {
    $_SESSION['valid'] = array();
}

include_once("template_top.inc");

if (isset($_GET['admin']) && $_GET['admin'] == 2) {
    echo '<div class="admin_wrapper">
            <a href="http://ptwickle.userworld.com/phpsql1/final/index.php?admin=3">Edit Accounts</a><br />
            <a href="http://ptwickle.userworld.com/phpsql1/final/index.php?admin=4">Edit Products</a><br />
            <a href="http://ptwickle.userworld.com/phpsql1/final/index.php?admin=5">View Purchases</a>
          </div>';
}

if (isset($_GET['admin']) && $_GET['admin'] == 3) {

    $accounts_display= admin_accounts();
    echo '<div class="admin_wrapper">
            <a href="http://ptwickle.userworld.com/phpsql1/final/index.php?admin=3">Edit Accounts</a><br />
            <a href="http://ptwickle.userworld.com/phpsql1/final/index.php?admin=4">Edit Products</a><br />
            <a href="http://ptwickle.userworld.com/phpsql1/final/index.php?admin=5">View Purchases</a>
          </div>
          <div class="accounts_display">
             '. $accounts_display .'
          </div>';

}


if (isset($_GET['admin']) && $_GET['admin'] ==4){

    $products_display = admin_products();
    echo '<div class="admin_wrapper">
            <a href="http://ptwickle.userworld.com/phpsql1/final/index.php?admin=3">Edit Accounts</a><br />
            <a href="http://ptwickle.userworld.com/phpsql1/final/index.php?admin=4">Edit Products</a><br />
            <a href="http://ptwickle.userworld.com/phpsql1/final/index.php?admin=5">View Purchases</a>
          </div>
          <div class="accounts_display">
             '. $products_display .'
          </div>';

}

// If the user is in the purchases admin area, but hasn't viewed a particular order.
if ((isset($_GET['admin']) && $_GET['admin'] ==5) && !isset($_GET['order'])){

    $purchases_display = admin_purchases_display();
    echo '<div class="admin_wrapper">
            <a href="http://ptwickle.userworld.com/phpsql1/final/index.php?admin=3">Edit Accounts</a><br />
            <a href="http://ptwickle.userworld.com/phpsql1/final/index.php?admin=4">Edit Products</a><br />
            <a href="http://ptwickle.userworld.com/phpsql1/final/index.php?admin=5">View Purchases</a>
          </div>
          <div class="accounts_display">
             '. $purchases_display .'
          </div>';

}

// If the user is in the display purchases admin area and HAS viewed a particular order, display
// the same as above, but add the order info, too.
if (isset($_GET['order']) && $_GET['order'] == 1){
    $order_display = display_order($_POST);
    $purchases_display = admin_purchases_display();
    echo '<div class="admin_wrapper">
            <a href="http://ptwickle.userworld.com/phpsql1/final/index.php?admin=3">Edit Accounts</a><br />
            <a href="http://ptwickle.userworld.com/phpsql1/final/index.php?admin=4">Edit Products</a><br />
            <a href="http://ptwickle.userworld.com/phpsql1/final/index.php?admin=5">View Purchases</a>
          </div>
          <div class="accounts_display">
             '. $purchases_display .'
          </div>
          <div>'. $order_display . '</div>';
}


// This if statement tests for the username and passwords in the POST variable. If they are there, it activates the
// login.
if (isset($_POST['username']) && isset($_POST['password'])) {

    user_cred($_POST);

}

// Activates the register form if a user has submitted an username not in the accounts.txt list. As of now, blank
// submissions of the login form also trigger the register form display.
if (isset($_GET['register_new']) && $_GET['register_new'] == 1) {

    $register_display = register_display($_SESSION);

    echo $register_display;
}

// This puts the site into "checkout mode".
if (isset($_GET['checkout']) && $_GET['checkout'] ==1) {
    $items = $_SESSION['out_cart'];

    $out_table = build_out_cart($items);

    echo '
<div class="cart_display">
          <h2>Your Order:</h2>
          <hr>
          <br>
          <br>
            <table><tbody><th>Item</th><th>Quantity</th><th>Price</th>' . $out_table . '</div><br><br><br><br>
<form name = "purchase" action="index.php?checkout=1&close=1" method="POST">
  <input type="text" hidden name="mail" value="1">
  <input type="submit" value="complete purchase">
</form>
</div>
<div>
  <a href="index.php?close=1">Continue Shopping!</a>

</div>';


    // If the post variable "mail" is set and equals 1, send the confirmation email and display the confirmation message.
    if ((isset($_POST['mail']) && $_POST['mail'] == 1)) {



        $thanks = confirm_email($_SESSION['username']);


        if ($thanks){
            echo $thanks;
        }

        if (!$thanks) {
            echo "There was a problem and we could not send your confirmation email";
        }

    }
    echo '</body></html>';
}

// If none of the other "special case" query strings are set, the script displays the products. That is, the site
// is in "shopping mode".
elseif (!isset($_GET['admin']) && !isset($_GET['order'])){
    $product_list = display();
    for ($i = 0; $i < count($product_list); $i++){
        echo $product_list[$i];
    }

    echo "</div><!--end div.wrapper-->";

    echo '</body>
</html>';
}




