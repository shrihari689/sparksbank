<?php
  require_once('./../dbHelper.php');
  setcookie('currentUserId', '1', time() + (86400 * 30), "/");

?>
<!DOCTYPE html>
<html lang="en" >
<head>
  <meta charset="UTF-8">
  <title>Sparks Bank - Net Banking - Customers</title>
   
  <link rel='stylesheet' href='https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/css/materialize.min.css'>
  <link rel='stylesheet' href='https://fonts.googleapis.com/icon?family=Material+Icons'>
  <link rel="stylesheet" href="./style.css">
  <link rel="icon" href="../favicon.png">
</head>

<body>
<div class="navbar-fixed">
  <nav class="blue">
    <div class="nav-wrapper">
      <a href="/" class="brand-logo center hide-on-med-and-up">💰 Sparks Bank</a>
      <a href="/" class="brand-logo left hide-on-small-and-down">💰 Sparks Bank</a>
      <a href="#" data-target="mobile-menu" class="sidenav-trigger hide-on-med-and-up"><i class="material-icons">menu</i></a>
      <ul class="right hide-on-small-and-down">
        <li class="active"><a href="/customer">Customers</a></li>
        <li><a href="/transactions">Transactions</a></li>
        <li><a href="/" class="btn btn-small orange">Pay Now</a> </li>
      </ul>
    </div>
  </nav>
</div>

<ul class="sidenav" id="mobile-menu">
  <li class="active"><a href="/customer">Customers</a></li>
  <li><a href="/transactions">Transactions</a></li>
  <li> <a href="/" class="btn btn-small orange">Pay Now</a> </li>
</ul>

<main>
  <header>Customers 😎</header>

  <section class="new-customer-add">
    <?php
       if(isset($_POST['option'])) {
        if($_POST['option'] == 'addNewCustomer') {
          $customerName = $_POST['customerName'];
          $dbHelper = new DBHelper();
          $dbHelper->addNewCustomer($customerName);
          unset($_POST['option']);
        }
      }    
    ?>
    
    <form class="row" method="post" autocomplete="off">
      <div class="row new-customer-label">
          <div class="col s12 m8 offset-m1">
            <label for="customerName">Customer Name</label>
          </div>
      </div>
      <div class="col s12">
        <div class="row">
          <div class="col s12 m8 offset-m1">
            <input class="browser-default new-customer-input" id="customerName" name="customerName" type="text">
          </div>
          <input type="hidden" name="option" value="addNewCustomer">
          <div class="col s12 m2 customer-button">
            <button value="submit" class="btn btn-small orange">Add New</button>
          </div>
        </div>
      </div>
    </form>
  </section>
  <section class="customer-list">
    <table class="striped highlight centered">
      <thead>
        <tr>
          <th>Customer ID</th>
          <th>Customer Name</th>
          <th>Balance</th>
        </tr>
      </thead>
      <tbody>
        <?php
            $dbHelper = new DBHelper();
            $customers = $dbHelper->getAllCustomers();
            if($customers) {
              foreach($customers as $row) {
                echo '<tr>';
                echo "<td>{$row['id']}</td>";
                echo "<td>{$row['name']}</td>";
                echo "<td>{$row['balance']}</td>";
                echo '</tr>';                
              }
            }else{
              echo "<tr><td colspan='3'>No Users Found!</td></tr>";
            }
        ?>
      </tbody>
    </table>
  </section>

</main>
<!-- partial -->
  <script src='https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.min.js'></script>
<script src='https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/js/materialize.min.js'></script><script  src="./script.js"></script>

</body>
</html>
