<?php
if(session_status() === PHP_SESSION_NONE) session_start();
ini_set("log_errors", 1);
ini_set("error_log", "seguridad.log");
require "/vendor/autoload.php";

use \Firebase\JWT\JWT;
//inicio seguridad
function creaToken($id,$nivel){
    
    $secret_key = "Pronto";
    $issuer_claim = "pronto"; // this can be the servername
    $audience_claim = "pronto";
    $issuedat_claim = time(); // issued at
    $notbefore_claim = $issuedat_claim; //not before in seconds
    $expire_claim = $issuedat_claim + 1800; // expire time in seconds
    $token = array(
        "iss" => $issuer_claim,
        "aud" => $audience_claim,
        "iat" => $issuedat_claim,
        "nbf" => $notbefore_claim,
        "exp" => $expire_claim,
        "data" => array(
            "id" => $id,
            "nivel" => $nivel
        ));
    $jwt = JWT::encode($token, $secret_key);
    return $jwt;
}
function updateToken($id,$nivel){
    
    $secret_key = "Pronto";
    $issuer_claim = "pronto"; // this can be the servername
    $audience_claim = "pronto";
    $issuedat_claim = time(); // issued at
    $notbefore_claim = $issuedat_claim; //not before in seconds
    $expire_claim = $issuedat_claim + 1800; // expire time in seconds
    $token = array(
        "iss" => $issuer_claim,
        "aud" => $audience_claim,
        "iat" => $issuedat_claim,
        "nbf" => $notbefore_claim,
        "exp" => $expire_claim,
        "data" => array(
            "id" => $id,
            "nivel" => $nivel
        ));
    $jwt = JWT::encode($token, $secret_key);
    $_SESSION['prontoFront']['token']=$jwt;
    return $expire_claim;
}
function verificarToken($token,$key){
    $respuesta=new stdClass();
    try {
        $data = JWT::decode($token, $key, array('HS256'));
        if (is_object($data)) {
            $respuesta->success=true;
            $respuesta->id=$data->data->id;
            $respuesta->nivel=$data->data->nivel;
            $respuesta->venc=updateToken($data->data->id, $data->data->nivel);
        }else{
            $respuesta->success=false;
        }
    } catch (\Firebase\JWT\ExpiredException $e) {
        $respuesta->success=false;
    }
    
    
    return $respuesta;
}
//fin seguridad