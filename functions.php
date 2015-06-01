<?php
date_default_timezone_set ( 'America/New_York' );

ini_set('display_errors', 1);

error_reporting(E_ALL);


if (!isset($_SESSION)) {
    session_start();
}

require_once('FirePHP.class.php');

if (!$firephp) {
    ob_start();

    $firephp = FirePHP::getInstance(true);

}


// Connects to the dB. Returns the connection object.
function db_connect(){
    $host = '127.0.0.1';
    $user = 'twickler';
    $pw = '123456';
    $database = 'cart';

    $db = new mysqli($host,$user,$pw,$database) or die("Cannot connect to MySQL.");

    return $db;

}


#----------------------#
# Functions  admin     #
#----------------------#


// Displays the accounts info of the admin area as well as the form that updates them.
function admin_accounts() {
    $db = db_connect();


    $accounts_display_command = "SELECT * FROM accounts;";

    $accounts_display_results = $db->query($accounts_display_command);

    // Starts out building the table html which will be filled in, row by row, by the below while loop.
    $accounts_display  = '<table><tbody>
                            <tr><th>userId</th><th>username</th><th>email</th><th>password</th><th>admin</th></tr>';

    //Iterates through the accounts table and concats in the data.
    while($accounts_display_data = $accounts_display_results->fetch_object()) {

        $accounts_display .= "<tr><td>".$accounts_display_data->userId."</td><td>".$accounts_display_data->username ."</td><td>".$accounts_display_data->user_email."</td><td>".$accounts_display_data->password."<td>".$accounts_display_data->admin ."</td>";

    }

    // Finishes up the table html after the rows have been added.
    $accounts_display .= '</tbody></table>

     <div class="account_edit">NOTE: userId must be filled.
     <form  class="account_edit_form" method="POST" action="functions.php?accts=1">
         <input type="text" name="userId"><label for="userId">userId</label><br/>
         <input type="text" name="username"><label for="username">username</label><br/>
         <input type="text" name="email"><label for="email">email</label><br/>
         <input type="text" name="password"><label for="password">password</label><br/>
         <input type="text" name="admin"><label for="admin">admin</label><br/>
         <input type="submit" value="Update Account">


       </form>

     </div>';

  return $accounts_display;


}

function admin_products() {
    $db = db_connect();


    $products_display_command = "SELECT * FROM products;";

    $products_display_results = $db->query($products_display_command);

    // Starts out building the table html which will be filled in, row by row, by the below while loop.
    $products_display  = '<table><tbody>
                            <tr><th>productId</th><th>name</th><th>img</th><th>weight</th><th>price</th></tr>';

    //Iterates through the accounts table and concats in the data.
    while($products_display_data = $products_display_results->fetch_object()) {

        $products_display .= "<tr><td>".$products_display_data->productId."</td><td>".$products_display_data->name ."</td><td>".$products_display_data->img."</td><td>".$products_display_data->weight."<td>".$products_display_data->price ."</td>";

    }

    // Finishes up the table html after the rows have been added.
    $products_display .= '</tbody></table>

     <div class="account_edit">NOTE: productId must be filled. ALSO: The img field contains the name of the image file. It is needed to build the html that displays the image. Changing this field, therefor, could break the html and display of products.
     <form  class="account_edit_form" method="POST" action="functions.php?products=1">
         <input type="text" name="productId"><label for="productId">productId</label><br/>
         <input type="text" name="name"><label for="name">name</label><br/>
         <input type="text" name="img"><label for="img">img</label><br/>
         <input type="text" name="weight"><label for="weight">weight</label><br/>
         <input type="text" name="price"><label for="price">price</label><br/>
         <input type="submit" value="Update Product">


       </form>

     </div>';

    return $products_display;


}

// Parses the incoming form data from the account update form and builds the query.
// The userId will be required, but this function will build the query with the
function acct_update($post) {

    $db = db_connect();


    $account_info = $post;
    $userId = $account_info['userId'];
    $username = $account_info['username'];
    $email = $account_info['email'];
    $password = $account_info['password'];
    $admin = $account_info['admin'];

    $account_command = "UPDATE accounts SET ";

    $string_bit = 0;


 // This set of if/else statements check the incoming form data and concats either a comma with a space followed by the new data,
 // if a preceding form input sends data, or just the new data if no other preceding field has data in it.
    if (isset($username) && $username != '') {
        $account_command .= " username='" . $username . "'";

    }

    if (isset($email) && $email != '') {
        if(isset($username) && $username !=null){
            $account_command .=", user_email='". $email . "'";
        }

        else {
            $account_command .= "user_email='". $email ."'";
        }

    }

    if (isset($password) && $password !=''){
        if ((isset($username) && $username != null) || (isset($email) && $email !=null)){
            $account_command .=", password='" . $password . "'";
        }

        else {
            $account_command .=" password='" . $password . "'";
        }
    }

    if (isset($admin) && $admin !='') {
        if ((isset($username) && $username !=null) || (isset($email) && $email !=null) || (isset($password) && $password !=null)) {
            $account_command .=", admin=" . $admin . "";
        }
        else {
            $account_command .=" admin=" . $admin;
        }
    }

    // The remainder of the query is concatted here.
    $account_command .= " WHERE userId=" . $userId . ";";


    $db->query($account_command);
    $db->close();

    $url = "http://" . $_SERVER['HTTP_HOST'] . "/sql_final/index.php?admin=3";
    header("Location: " . $url) or die("Didn't work");

}


function product_update($post) {

    $db = db_connect();


    $product_info = $post;
    $productId = $product_info['productId'];
    $name = $product_info['name'];
    $img = $product_info['img'];
    $weight = $product_info['weight'];
    $price = $product_info['price'];

    $products_command = "UPDATE products SET ";

    $string_bit = 0;


    // This set of if/else statements check the incoming form data and concats either a comma with a space followed by the new data,
    // if a preceding form input sends data, or just the new data if no other preceding field has data in it.
    if (isset($name) && $name != '') {
        $products_command .= " name='" . $name . "'";

    }

    if (isset($img) && $img != '') {
        if(isset($name) && $name !=null){
            $products_command .=", img='". $img . "'";
        }

        else {
            $products_command .= "img='". $img ."'";
        }

    }

    if (isset($weight) && $weight !=''){
        if ((isset($name) && $name != null) || (isset($img) && $img !=null)){
            $products_command .=", weight='" . $weight . "'";
        }

        else {
            $products_command .=" weight='" . $weight . "'";
        }
    }

    if (isset($price) && $price !='') {
        if ((isset($name) && $name !=null) || (isset($img) && $img !=null) || (isset($weight) && $weight !=null)) {
            $products_command .=", price=" . $price . "";
        }
        else {
            $products_command .=" price=" . $price;
        }
    }

    // The remainder of the query is concatted here.
    $products_command .= " WHERE productId=" . $productId . ";";

    /*echo $products_command;
    exit;*/

    $db->query($products_command);
    $db->close();

    $url = "http://" . $_SERVER['HTTP_HOST'] . "/sql_final/index.php?admin=4";
    header("Location: " . $url) or die("Didn't work");

}

#----------------------#
# Functions  checkout  #
#----------------------#
// finish the out_cart indexing to pull in the items.
function confirm_email($user,$products) {

    $message = "<html><head></head><body><br><br><br><br><br><br><br>" . $user.", thank you for buying this stuff.<br>Your Purchases:";

    $user_list = file('accounts.txt');
    for($i=0; $i < count($user_list);$i++) {
        $line = explode(",",$user_list[$i]);
        for ($c = 0; $c < count($line); $c++) {
            $user_match = preg_match('/^' . $user . '$/', $line[$c], $matches);

            if ($matches) {
                $user_email = $line[1]; //This is the index of the user info that stores the email address.
                $to = $user_email;

                $email_subject = $user . "-- Your Purchase from Crystals, Charms, and Coffee " . date("F d, Y h:i a");

                //$message = '';
                $total = 0;

                foreach($_SESSION['out_cart'] as $key=>$value) {
                    $product = $products[$key];

                    $message .= '<table><tbody><tr><td class="checkout_name">' . $product['name'] . '</td><td class="checkout_quantity">' . $_SESSION['out_cart'][$key]['quantity'] . '</td><td class="checkout_price">$' . $product['price'] * intval($_SESSION['out_cart'][$key]['quantity']) .'.00</td></tr>';
                    $total += $product['price'] * intval($_SESSION['out_cart'][$key]['quantity']);
                }

                $message .= '</tbody></table><div class="total_price"> Your Total: $' .$total . '.00</div></body></html>';

                $headers  = "From: peter.twickler@gmail.com" . "\r\n";
                $headers .= 'MIME-Version: 1.0' . "\n";
                $headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";

                $mail = mail($to, $email_subject, $message,$headers);

                // If the order went through and the email worked, display the confirmation message and unset the cart.
                if ($mail == true) {
                    $thanks =  "Thank you for your purchase, ". $user . ". An email with your purchase receipt has been sent to your email address.<br><br>
                    Your friends at Crystals, Charms, and Coffees";
                    unset($_SESSION['out_cart']);

                }

                elseif($mail != true) {
                    $thanks = "I'm sorry, something went wrong and we could not send your receipt to the email address on file.";
                }

            }
        }

    }
    return $thanks;
}


/*
 * Builds the string to display the products and info in the user's cart.
 */
function build_out_cart($cart = NULL, $products){

    $out_cart = '';
    $total = 0;

    if ($cart) {
        foreach ($cart as $key => $value) {
            $product = $products[$key];

            $out_cart .= '<tr><td class="checkout_name">' . $product['name'] . '</td><td class="checkout_quantity">' . $cart[$key]['quantity'] . '</td><td class="checkout_price">$' . $product['price'] * intval($cart[$key]['quantity']) . '.00</td></tr>';
            $total += $product['price'] * intval($cart[$key]['quantity']);
        }

        $out_cart .= '</tbody></table><div class="total_price"> Your Total: $' . $total . '.00</div>';
        return $out_cart;
    }
}

#----------------------#
# Functions  index     #
#----------------------#

/*
 * Pulls the "properties" of the product arrays into a string to build the html for the display of the products
 *
 *  $item is the product being processed and $products is the array of products in products.php.s
 */
function display(){
    $db = db_connect();

    $product_command = "SELECT name, img, weight, price FROM products";

    $products_results = $db->query($product_command);

    $product_display = array();

    while ($product_data = $products_results->fetch_object()){

        array_push($product_display,'<form  class="display_form" method="GET" action="functions.php?add_cart=1">
                         <div class = "product_display">
                         <input class="disp_name" type="text" value = "'. $product_data->name .'" name="prod_name" readonly>
                         <div class ="prod_img" ><img src = "./img/' . $product_data->img .'.jpg"></div>
                         <div class = "prod_weight">'. $product_data->weight . '</div>
                         <div class = "prod_price">$'. $product_data->price .'</div>

                         <input type="text" size="5" name="quantity">
                         <input class="add_to_cart"  type="submit" value="Add to Cart" >
                         <input type="text" name ="item" value="'.$product_data->name .'" readonly hidden="true">
                         </form>

    </div>');
    }


    return $product_display;

};

// Grabs the items out of the cart and gets their relevant details from the array in products.php which it then pushes
// into the "out cart" which will be used to create the shopping cart page.

function add_to_cart($item,$quantity){

    $item = $item;
    //$products = $products;
    $_SESSION['out_cart'][$item]['name'] = $item;
    $_SESSION['out_cart'][$item]['quantity'] = $quantity;
    $url = "http://" . $_SERVER['HTTP_HOST'] . "/sql_final/index.php";
    header("Location: " . $url) or die("Didn't work");
}

// This bit calls the above function. I wanted to put it on the index.php page, so it would be cleaner, but I couldn't
// figure it out in time. So, you get the below kludge.
if (isset($_GET['prod_name']) && $_GET['prod_name'] != 1) {

    $item = $_GET['item'];
    $quantity = $_GET['quantity'];
    $cart = $_SESSION['cart'];

    if ($_SESSION['sign_in'] == 1) {
        add_to_cart($products, $item, $quantity);
        $url = "http://" . $_SERVER['HTTP_HOST'] . "/sql_final/index.php";
        header("Location: " . $url) or die("Didn't work");
    } else {

        $url = "http://" . $_SERVER['HTTP_HOST'] . "/sql_final/index.php?signed=0";
        header("Location: " . $url) or die("Didn't work");
    }
}

#----------------------#
# Functions login      #
#----------------------#

// This function inserts a new "account" into the accounts.txt file. This is how I keep track of login credentials.
// Basically, it implodes the values into a string and then writes it to the file accounts.txt.
function new_user($user,$email,$pass) {

    $db = db_connect();

    $n_user = $user;

    $n_pass = $pass;
    $n_email = $email;

    $register_command = "INSERT INTO accounts (username, user_email, password) VALUES ('". $n_user ."', '". $n_email . "', '". $n_pass ."');";



    $db->query($register_command);

    $db->close();

}

// Builds the new user registration form. Different states are for form validation. If any of the session variable
// "valid" properties are set,it displays the correct error message.
function register_display($session) {

    if (isset($session['valid']['name']) && $session['valid']['name'] == 'name_error' ) {

        $register_display =  '<form name="register" action="index.php?new_user=1" method="POST">
             <input type="text" size="20" name="username">
              <label for="username"><span class="form_error">Please enter a valid username.</span></label><br />
             <input type="text" size="20" name="email">
             <label for="email">Enter Your email address</label><br />
             <input type="text" size="20" name="password">
             <label for="password">Enter your password</label><br/>
             <input type="submit" value="Click to register!">
           </form>';
    }

    elseif(isset($session['valid']['email']) && $session['valid']['email'] == 'email_error' ) {
        $register_display =  '<form name="register" action="index.php?new_user=1" method="POST">
             <input type="text" size="20" name="username">
              <label for="username">Enter your name</label><br />
             <input type="text" size="20" name="email">
             <label for="email"><span class="form_error">Please enter a valid email address.</span></label><br />
             <input type="text" size="20" name="password">
             <label for="password">Enter your password</label><br/>
             <input type="submit" value="Click to register!">
           </form>';

    }

    elseif(isset($session['valid']['password']) && $session['valid']['password'] == 'password_error' ) {
        $register_display =  '<form name="register" action="index.php?new_user=1" method="POST">
             <input type="text" size="20" name="username">
              <label for="username">Enter your name</label><br />
             <input type="text" size="20" name="email">
             <label for="email">Enter your email address</label><br />
             <input type="text" size="20" name="password">
             <label for="password"><span class="form_error">Please enter a valid password.</span></label><br/>
             <input type="submit" value="Click to register!">
           </form>';
    }

    else {
        $register_display = '<form name="register" action="index.php?new_user=1" method="POST">
             <input type="text" size="20" name="username">
              <label for="name">Enter your name</label><br />
             <input type="text" size="20" name="email">
             <label for="email">Enter your email address</label><br />
             <input type="text" size="20" name="password">
             <label for="password">Enter a password</label><br />
             <input type="submit" value="Click to register!">
           </form>';
    }
    return $register_display;
}

/*
 * @param Takes $_POST['username'] and $_POST['password']
 */
// If the user has submitted the login form, iterate through the records in accounts.txt.
// The nested for loops iterate first through the file, line by line, and then the first nested for loop
// iterates through the line looking first for the username submitted. If it finds the username,
// it then iterates through the same line again looking for the password. If both are found, the user is
// logged in. If the password isn't found, it suggests you try again. If the user isn't found, it displays
// the registration form.
function user_cred($query=array()) {

    $db = db_connect();


    $user_info = $query;

    // New account form validation and processing. If the new_user variable is set, test the form inputs and then process.
    if(isset($_GET['new_user']) && $_GET['new_user'] ==1){
        $name_test = $user_info['username'];

        if ($name_test != null && $name_test != '') {
            $user_name = $name_test;
        }

        // I have set $user_info to the query (POST) and so now I pass that along instead of the $_POST. I hope to
        // avoid confusion by doing so. Whether or not the $_POST['email'] property is set is the test for distinguishing
        // the regular non-validation path from the form validation path.
        elseif (($name_test == '' || $name_test == null) && isset($_POST['email'])) {
            $_SESSION['valid']['name'] = 'name_error';
            $url = "http://" . $_SERVER['HTTP_HOST'] . "/sql_final/index.php?register_new=1";
            header("Location: " . $url) or die("didn't redirect from login");
        }

        $user_email = $_POST['email'];
        if ($user_email && $user_email != null) {
            $email_check = filter_var($user_email, FILTER_VALIDATE_EMAIL);
        }

        if($user_email == null || $email_check != true){
            $_SESSION['valid']['email'] = 'email_error';

            $url = "http://" . $_SERVER['HTTP_HOST'] . "/sql_final/index.php?register_new=1";
            header("Location: " . $url) or die("didn't redirect from login");

        }

        $user_pw = $user_info['password'];
        if ($user_pw == null or !isset($user_pw)){
            $_SESSION['valid']['password'] = 'password_error';

            $url = "http://" . $_SERVER['HTTP_HOST'] . "/sql_final/index.php?register_new=1";
            header("Location: " . $url) or die("didn't redirect from login");
        }

        new_user($user_name,$user_email,$user_pw);

        ob_clean();
        $url = "http://" . $_SERVER['HTTP_HOST'] . "/sql_final/index.php";
        header("Location: " . $url) or die("didn't redirect from login");
    }

    $username = $_POST['username'];

    $pw = $_POST['password'];

    $reg_link = 0; // Counter to limit the display of the "register here" verbiage.
    $pass_error = 0;

    $user_list = file('accounts.txt');

    // Iterates through the file testing each line for the username and password combo.

    $cred_command = "SELECT * FROM accounts WHERE username = '". $username . "';";

    $cred_results = $db->query($cred_command);

    $cred_data = $cred_results->fetch_object();

    if (isset($cred_data->username)  && $cred_data->username == $username) {

        if ($cred_data->password == $pw) {
            if (isset($cred_data->admin)) {
                $_SESSION['admin'] = 1;
            }
            $_SESSION['sign_in'] = 1;
            $_SESSION['username'] = $username;
            $url = "http://" . $_SERVER['HTTP_HOST'] . "/sql_final/index.php";
            ob_clean();
            header("Location: " . $url) or die("didn't redirect from login");

        }
        elseif (($cred_data->username == $username) && $cred_data->password != $pw) {
            if($pass_error == 1)
                echo '<span class="form_error">The password you entered is not correct</span>';
            $pass_error++;
        }
    }

    elseif ( !(isset($cred_data->username))) {

        echo '<div>Not registered? Click <a href="index.php?register_new=1">here</a> to register.</div>';


    }




}

if (isset($_GET['accts']) && $_GET['accts'] ==1){
    acct_update($_POST);
}

if (isset($_GET['products']) && $_GET['products'] == 1){
    product_update($_POST);
}
$firephp->log($_SESSION, 'session');