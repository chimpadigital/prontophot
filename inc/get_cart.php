<?php 
session_start();
if (isset($_SESSION['pronto']['cart'])) {
    if ($_SESSION['pronto']['cart_expira']<=time()) {
        unset($_SESSION['pronto']['cart']);
        $count='';
    }else{
        $count=count($_SESSION['pronto']['cart']);
    }
    
}else{
    $count='';
}
echo $count;
?>