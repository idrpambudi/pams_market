<?php

class DisbursementDatabaseService {
  private $username;
  private $password;
  private $db_name;
  private $servername;

  function __construct() {
    $this->username = getenv("DB_USERNAME");
    $this->password = getenv("DB_PASSWORD");
    $this->db_name = getenv("DB_NAME");
    $this->servername = "localhost";
  }

  function get_db_connection(){
    $conn = new mysqli($this->servername, $this->username, $this->password, $this->db_name);
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
    return $conn;
  }

  function create_db(){
    $conn = $this->get_db_connection();
    $query = sprintf("CREATE DATABASE IF NOT EXISTS %s;", $this->db_name);
    $conn->query($query);
  }

  function create_disbursement_table(){ 
    $conn = $this->get_db_connection();
    $query = "CREATE TABLE IF NOT EXISTS disbursement (
      `id` VARCHAR(64) PRIMARY KEY,
      `amount` INT NOT NULL,
      `status` VARCHAR(32) NOT NULL,
      `timestamp` VARCHAR(32) NOT NULL,
      `bank_code` VARCHAR(16) NOT NULL,
      `account_number` VARCHAR(32) NOT NULL,
      `beneficiary_name` VARCHAR(32) NOT NULL,
      `remark` VARCHAR(256) DEFAULT '',
      `receipt` TEXT DEFAULT NULL,
      `time_served` VARCHAR(32) NOT NULL,
      `fee` INT NOT NULL)";

    $conn->query($query);
  }

  function create_disbursement(
    $id, $amount, $status, $timestamp, $bank_code, $account_number, $beneficiary_name, $remark, $receipt, $time_served, $fee){
    $conn = $this->get_db_connection();
    $query = sprintf(
      "INSERT INTO disbursement 
      (`id`,`amount`,`status`,`timestamp`,`bank_code`,`account_number`,`beneficiary_name`,`remark`,`receipt`,`time_served`,`fee`)
      VALUES ('%s',%d,'%s','%s','%s','%s','%s','%s','%s','%s',%d)",
      $id, $amount, $status, $timestamp, $bank_code, $account_number, $beneficiary_name, $remark, $receipt, $time_served, $fee);

    $res = $conn->query($query);
    if ($res === FALSE) {
      echo "Error: " . $query . "<br>" . $conn->error;
    }
  }

  function update_disbursement($id, $status, $receipt, $time_served){
    $conn = $this->get_db_connection();
    $query = sprintf(
      "UPDATE disbursement SET `status`='%s',`receipt`='%s',`time_served`='%s' WHERE `id`='%s'",
      $status, $receipt, $time_served, $id);

    $res = $conn->query($query);
    if ($res === FALSE) {
      echo "Error: " . $query . "<br>" . $conn->error;
    }
  }
}

$disbursement_db_service = new DisbursementDatabaseService();

?>