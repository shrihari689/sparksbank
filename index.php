<?php  
  require_once('dbHelper.php');
  $dbHelper = new DBHelper();

  setcookie('currentUserId', '1', time() + (86400 * 30), "/");

  $currentUserId =  $_COOKIE['currentUserId'];
  
?>


<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />

    <title>Sparks Bank - Net Banking - Quick Pay</title>
    <link rel="icon" href="favicon.png">
    <link
      rel="stylesheet"
      href="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/css/materialize.min.css"
    />
    <link
      rel="stylesheet"
      href="https://fonts.googleapis.com/icon?family=Material+Icons"
    />
    <link rel="stylesheet" href="style.css" />
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
            <li><a href="/customer">Customers</a></li>
            <li><a href="/transactions">Transactions</a></li>
            <li><a href="/" class="btn btn-small orange">Pay Now</a></li>
          </ul>
        </div>
      </nav>
    </div>

    <ul class="sidenav" id="mobile-menu">
      <li><a href="/customer">Customers</a></li>
      <li><a href="/transactions">Transactions</a></li>
      <li><a href="/" class="btn btn-small orange">Pay Now</a></li>
    </ul>

    <main>
      <?php 
        if(isset($_POST['option']) && $_POST['option'] == 'quickPay') {

          try {
            $customerId = $_POST['customerId'];
            $amount = $_POST['transferAmount'];
            
            $toCustomerId = intval(trim(str_replace('#', '', explode('-', $customerId)[0])));
            $amount = floatval($amount);
            
            $dbHelper->payNowToCustomer($toCustomerId, $amount);
            echo "<span class='home-pay-now-result'>Payment was Successful!</span>";
          }catch(Throwable $exp) {
            echo "<span class='home-pay-now-result' style='background: red;'>An Error Occured during Payment!</span>";
          }
        }        
      ?>

      <header>Your Balance 🥳</header>
      <section class="balance">
        <h6>Current Savings Account:</h6>
        <span class="balance-amount">₹ <?php $currentUserBalance = $dbHelper->getCurrentUserBalance(); echo $currentUserBalance; ?></span>
      </section>
      <header>Quick Pay ⚡</header>
      <section class="payment-mode">
        <form action="#" method="post" autocomplete="off" > 
          <div class="row">
            <div class="col s12 m8 offset-m2">
              <div class="row">
                <div class="input-field col s12">
                  <i class="material-icons prefix orange-text">people</i>
                  <input
                    type="text"
                    id="customerId"
                    name="customerId"
                    class="autocomplete validate"
                    required
                  />
                  <label class="teal-text" for="customerId">Customer</label>
                  <span
                    class="helper-text"
                    data-error="Choose any customer!"
                    data-success="Your Mate is Cool!"
                    >Customer to be paid</span
                  >
                </div>
              </div>
            </div>

            <div class="col s12 m8 offset-m2">
              <div class="row">
                <div class="input-field col s12">
                  <i class="material-icons prefix orange-text">attach_money</i>
                  <input
                    type="number"
                    class="validate"
                    min="10"
                    max="<?php echo $currentUserBalance; ?>"
                    id="transferAmount"
                    name="transferAmount"
                    required
                  />
                  <label class="teal-text" for="transferAmount">Amount</label>
                  <span
                    class="helper-text"
                    data-error="Amount must be less than <?php echo $currentUserBalance; ?> and must atleast 10"
                    data-success="Good! You have enough balance!"
                    >Amount to be paid</span
                  >
                </div>
              </div>
            </div>
          </div>

          <input type="hidden" name="option" value="quickPay" />

          <div class="container col s12">
            <div class="row">
              <input
                type="submit"
                class="btn btn-small orange col s12"
                value="Pay Now"
              />
            </div>
          </div>

        </form>
      </section>
    </main>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/js/materialize.min.js"></script>
    <script src="script.js"></script>
    <script>
      $("input.autocomplete").autocomplete({
        data: {
        <?php
          $customers = $dbHelper->getAllCustomers();
          if($customers) {
            foreach($customers as $row) {
              if($row['id'] == $currentUserId) continue;
              $id = "#{$row['id']} - {$row['name']}";
              $image = $row['photo'];              
              echo "'$id' : '$image' ,";                
            }
          }
        ?>
        }
      });
      
    </script>
  </body>
</html>
