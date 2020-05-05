<?php

include 'disbursement.php';
include 'nextar.php';


function main(){
  global $argv;
  global $disbursement_db_service;
  global $nextar_disbursement;

  $MIGRATE = "migrate";
  $SEND = "send_disbursement";
  $UPDATE = "update_disbursement";

  if (count($argv) < 2){
    die(sprintf("Argument command is required. Option: [%s, %s, %s].\n", $MIGRATE, $SEND, $UPDATE));
  }
  $command = $argv[1];

  if ($command === $MIGRATE){
    $disbursement_db_service->create_db();
    $disbursement_db_service->create_disbursement_table();
  }

  elseif ($command === $SEND){
    if (count($argv) !== 6){
      die(sprintf("Invalid %s format. The following arguments should be '<bank_code> <account_number> <amount> <remark>'.\n", $SEND));
    }
    $bank_code = $argv[2];
    $account_number = $argv[3];
    $amount = $argv[4];
    $remark = $argv[5];

    $resp = $nextar_disbursement->create_disbursement($bank_code, $account_number, $amount, $remark);
    $disbursement_db_service->create_disbursement(
      $resp["id"], $resp["amount"], $resp["status"], $resp["timestamp"], $resp["bank_code"], $resp["account_number"],
      $resp["beneficiary_name"], $resp["remark"], $resp["receipt"], $resp["time_served"], $resp["fee"]
    );
    echo sprintf("Successfully send and store disbursement with ID %s.\n", $resp["id"]);
  }

  elseif ($command === $UPDATE){
    if (count($argv) !== 3){
      die(sprintf("Invalid %s format. The following arguments should be '<disbursement_id>'", $SEND));
    }
    $disbursement_id = $argv[2];
    $resp = $nextar_disbursement->get_disbursement($disbursement_id);
    $disbursement_db_service->update_disbursement($resp["id"], $resp["status"], $resp["receipt"], $resp["time_served"]);
    echo sprintf("Successfully update disbursement with ID %s.\n", $disbursement_id);
  }

  else{
      die(sprintf("Invalid command. Option: [%s, %s, %s].\n", $MIGRATE, $SEND, $UPDATE));
  }
}

main()
?>