<?php
session_start();
echo '<pre>';
print_r($_SESSION);
echo '<pre>';
//unset($_SESSION['archivostmp']);
echo count($_SESSION['pronto']['cart']);
