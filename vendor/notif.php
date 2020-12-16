<?php
// include '/midtrans-php/Midtrans.php';
require_once(dirname(__FILE__).'/midtrans/midtrans-php/Midtrans.php');
\Midtrans\Config::$isProduction = false;
\Midtrans\Config::$serverKey = 'SB-Mid-server-UKzXcJnOMqOHikHEQHEqTwQI';
$notif = new \Midtrans\Notification();

$transaction = $notif->transaction_status;
$type = $notif->payment_type;
$order_id = $notif->order_id;
$fraud = $notif->fraud_status;

include '../includes/dbconfig.php';

$token = '1608104376265';

$ref = '/Transaksi%20Pembayaran'.$token;

// $fetchData = $database->getReference($ref)->getValue();

// var_dump($fetchData);
// die;
$status_code = 20;
$data = [
  'status_code' => $status_code
];
$postdata = $database->getReference($ref)->update($data);


if ($transaction == 'capture') {
  // For credit card transaction, we need to check whether transaction is challenge by FDS or not
  if ($type == 'credit_card'){
    if($fraud == 'challenge'){
      // TODO set payment status in merchant's database to 'Challenge by FDS'
      // TODO merchant should decide whether this transaction is authorized or not in MAP
      echo "Transaction order_id: " . $order_id ." is challenged by FDS";
      }
      else {
      // TODO set payment status in merchant's database to 'Success'
      echo "Transaction order_id: " . $order_id ." successfully captured using " . $type;
      }
    }
  }

else if ($transaction == 'settlement'){
  // TODO set payment status in merchant's database to 'Settlement'
 
  echo "Transaction order_id: " . $order_id ." successfully transfered using " . $type;
  }
  else if($transaction == 'pending'){
  // TODO set payment status in merchant's database to 'Pending'
  echo "Waiting customer to finish transaction order_id: " . $order_id . " using " . $type;
  }
  else if ($transaction == 'deny') {
  // TODO set payment status in merchant's database to 'Denied'
  echo "Payment using " . $type . " for transaction order_id: " . $order_id . " is denied.";
  }
  else if ($transaction == 'expire') {
  // TODO set payment status in merchant's database to 'expire'
  echo "Payment using " . $type . " for transaction order_id: " . $order_id . " is expired.";
  }
  else if ($transaction == 'cancel') {
  // TODO set payment status in merchant's database to 'Denied'
  echo "Payment using " . $type . " for transaction order_id: " . $order_id . " is canceled.";
}