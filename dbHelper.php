<?php

    class DBHelper {

    
        function __construct() {
            // $hostName = "sql303.epizy.com";
            // $databaseName = "epiz_27646930_sparksbank";
            // $username = "epiz_27646930";
            // $password = "1EXZHGb4427E2n";
            // $connectionUrl = "mysql:host=$hostName;dbname=$databaseName";
            
            $db = parse_url(getenv("DATABASE_URL"));

            $connection = new PDO("pgsql:" . sprintf(
                "host=%s;port=%s;user=%s;password=%s;dbname=%s",
                $db["host"],
                $db["port"],
                $db["user"],
                $db["pass"],
                ltrim($db["path"], "/")
            ));
            
            $connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);         
            $this->connection = $connection;                    
        }

        function __destruct() {
            $this->connection = null;            
        }

        public function getCurrentUserBalance() {
            $sql = "SELECT balance FROM customers WHERE id=:id";
            $id = $_COOKIE['currentUserId'];

            $stmt = $this->connection->prepare($sql);
            $stmt->bindParam(':id', $id ); 
            $stmt->execute();
            
            $data = $stmt->fetch();
            return $data['balance'];
        }


        public function payNowToCustomer($toCustomerId, $amount) {

            $this->connection->beginTransaction();
            $fromCustomerId = $_COOKIE['currentUserId'];

            // Get fromCustomer's Balance
            $sql = "SELECT balance FROM customers WHERE id = :id";
            
            $stmt = $this->connection->prepare($sql);
            $stmt->bindParam(':id', $fromCustomerId); 
            $stmt->execute();
            
            $availableAmount = intval($stmt->fetchColumn());
            if($availableAmount < $amount)
                throw new Exception("Insufficient Balance", 1);
            $stmt->closeCursor();

            // Deduct Amount of fromCustomer

            $sql = "UPDATE customers SET balance = balance - :amount WHERE id = :id";
            
            $stmt = $this->connection->prepare($sql);
            $stmt->bindParam(':id', $fromCustomerId); 
            $stmt->bindParam(':amount', $amount); 
            $stmt->execute();
            $stmt->closeCursor();
      
            // Add Amount to toCustomer

            $sql = "UPDATE customers SET balance = balance + :amount WHERE id = :id";
            
            $stmt = $this->connection->prepare($sql);
            $stmt->bindParam(':id', $toCustomerId); 
            $stmt->bindParam(':amount', $amount); 
            $stmt->execute();
            $stmt->closeCursor();


            // Write to Transaction Table 
            $sql = "INSERT INTO `transactions`(`senderCustomerId`, `receiverCustomerId`, `amount`) VALUES (:senderCustomerId, :receiverCustomerId, :amount)";
 
            $stmt = $this->connection->prepare($sql);
            $stmt->bindParam(':senderCustomerId', $fromCustomerId); 
            $stmt->bindParam(':receiverCustomerId', $toCustomerId); 
            $stmt->bindParam(':amount', $amount); 
            $stmt->execute();

            $this->connection->commit();
            
        }

        public function addNewCustomer($name) {
            $photo = 'https://randomuser.me/api/portraits/men/'.random_int(1,99).'.jpg';
            $balance = 0;
            $sql = "INSERT INTO `customers`(`name`, `photo`, `balance`) VALUES (:name, :photo, :balance)";

            $stmt = $this->connection->prepare($sql);
            $stmt->bindParam(':name', $name); 
            $stmt->bindParam(':photo', $photo); 
            $stmt->bindParam(':balance', $balance); 

            $stmt->execute();

            echo "<span class='new-customer-result'>New Customer created successfully!</span>";
        }
        
  
        public function getAllTransactions() {
            $sql = "SELECT transactions.id, fromCust.name AS 'fromCustName', fromCust.photo AS 'fromCustImage', toCust.name AS 'toCustName', toCust.photo AS 'toCustImage', transactions.amount FROM `transactions` JOIN customers fromCust ON fromCust.id = transactions.senderCustomerId JOIN customers toCust ON toCust.id = transactions.receiverCustomerId;";
            $stmt = $this->connection->prepare($sql);
            $stmt->execute();

            $result = $stmt->setFetchMode(PDO::FETCH_ASSOC);
            $data = $stmt->fetchAll();
            if(count($data) > 0) {
                return $data;
            }else{
                return false;
            }
        }
  


        public function getAllCustomers() {
            $sql = "SELECT * FROM customers";
            
            $stmt = $this->connection->prepare($sql);
            $stmt->execute();

            $result = $stmt->setFetchMode(PDO::FETCH_ASSOC);
            $data = $stmt->fetchAll();
            if(count($data) > 0) {
                return $data;
            }else{
                return false;
            }
        }
        
    }
    


?>