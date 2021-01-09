<?php
  require_once('./../dbHelper.php');
  $dbHelper = new DBHelper();
  setcookie('currentUserId', '1', time() + (86400 * 30), "/");

?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <title>Sparks Bank - Net Banking - Transactions</title>
   
    <link rel="icon" href="./../favicon.png">
    
    <link
      rel="stylesheet"
      href="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/css/materialize.min.css"
    />
    <link
      rel="stylesheet"
      href="https://fonts.googleapis.com/icon?family=Material+Icons"
    />
    <link rel="stylesheet" href="./style.css" />
  </head>
  <body>
    <div class="navbar-fixed">
      <nav class="blue">
        <div class="nav-wrapper">
          <a href="/" class="brand-logo center hide-on-med-and-up"
            >💰 Sparks Bank</a
          >
          <a href="/" class="brand-logo left hide-on-small-and-down"
            >💰 Sparks Bank</a
          >
          <a
            href="#"
            data-target="mobile-menu"
            class="sidenav-trigger hide-on-med-and-up"
            ><i class="material-icons">menu</i></a
          >
          <ul class="right hide-on-small-and-down">
            <li><a href="#!">Customers</a></li>
            <li class="active"><a href="#!">Transactions</a></li>
            <li><a href="#!" class="btn btn-small orange">Pay Now</a></li>
          </ul>
        </div>
      </nav>
    </div>

    <ul class="sidenav" id="mobile-menu">
      <li><a href="/customer">Customers</a></li>
      <li class="active"><a href="/transactions">Transactions</a></li>
      <li><a href="/" class="btn btn-small orange">Pay Now</a></li>
    </ul>

    <main>
      <header>Transaction History 🧾</header>
      <section class="transaction-list">
      <?php
            $transactions = $dbHelper->getAllTransactions();
            if($transactions) {
              foreach($transactions as $row) {
                echo "<div class='transaction-list-item'>
                        <div class='transaction-list-profile'>
                          <img
                            src='{$row['fromCustImage']}'
                            alt='Profile'
                          />
                          <span>{$row['fromCustName']}</span>
                        </div>
                        <div class='transaction-list-status'>
                          <span class='price'>₹{$row['amount']}</span>
                          <span class='symbol'>&rarr;</span>
                        </div>
                        <div class='transaction-list-profile'>
                          <img
                            src='{$row['toCustImage']}'
                            alt='Profile'
                          />
                          <span>{$row['toCustName']}</span>
                        </div>
                      </div>";
              }
            }else{
              echo "<h4 class='no-transaction'>No Transactions Found!</h4>";
            }
        ?>
      </section>
    </main>
    <!-- partial -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/js/materialize.min.js"></script>
    <script src="./script.js"></script>
  </body>
</html>
