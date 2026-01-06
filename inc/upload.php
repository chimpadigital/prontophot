<?php
session_start();
ini_set("log_errors", 1);
ini_set("error_log", __DIR__."/upload.log");
if (isset($_POST["json"])) {
    // do php stuff
    $obj = json_decode($_POST["json"]);
    $archivo=$obj->file;
    $data = base64_decode(preg_replace('#^data:image/\w+;base64,#i', '', $archivo));
    $dir=__DIR__.'/../img/tmp/';
    $id=uniqid();
    if($obj->type=='image/jpg'){ $filepath=$id.".jpg"; }
    if($obj->type=='image/png'){ $filepath = $id.".png"; }
    if($obj->type=='image/jpeg'){ $filepath = $id.".jpg"; }
    // Finalmente guarda la imágen en el directorio especificado y con la informacion dada
    file_put_contents($dir.$filepath,$data);
    $item=array();
    $item['archivo']='img/tmp/'.$filepath;
    $item['pos']=$obj->pos;
    $item['id']=$id;
    $item['name']=$obj->name;
    $item['type']=$obj->type;
    $_SESSION['archivostmp'][$id]=$item;
    $obj->name=$filepath;
    // call `json_encode` on `file` object
    $file = json_encode($item);
    // return `file` as `json` string
    echo $file;
};