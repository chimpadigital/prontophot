<?php
session_start();
require_once 'vendor/autoload.php';
include 'config.inc.php';
include_once("../conexion/conectar.inc.php");
include 'funciones.php';
MercadoPago\SDK::setAccessToken(ML_TOKEN);
global $conectar;
$get=serialize($_GET);
file_put_contents("./test.log", $get.'//r//n', FILE_APPEND);
$merchant_order = null;

switch($_GET["topic"]) {
    case "payment":
        $payment = MercadoPago\Payment::find_by_id($_GET["id"]);
        $merchant_order = MercadoPago\MerchantOrder::find_by_id($payment->order->id);
        break;
    case "merchant_order":
        $merchant_order = MercadoPago\MerchantOrder::find_by_id($_GET["id"]);
        break;
}

$paid_amount = 0;
foreach ($merchant_order->payments as $payment) {
    if ($payment['status'] == 'approved'){
        $paid_amount += $payment['transaction_amount'];
    }
}
file_put_contents("./test.log", $paid_amount.'//r//n', FILE_APPEND);
if($paid_amount >= $merchant_order->total_amount){
    if (count($merchant_order->shipments)>0) { // The merchant_order has shipments
        if($merchant_order->shipments[0]->status == "ready_to_ship") {
            print_r("Totally paid. Print the label and release your item.");
        }
    } else { // The merchant_order don't has any shipments
        // //
        $val=' realizar pago'.$merchant_order->id;
        file_put_contents("./test.log", $val.'//r//n', FILE_APPEND);
        $idt=$merchant_order->id;
        $idpedido=$merchant_order->external_reference;
        guardarPago($idt, '1', $idpedido);
        
        //
    }
} else {
    print_r("Not paid yet. Do not release your item.");
}

?>