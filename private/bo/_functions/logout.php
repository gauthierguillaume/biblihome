<?php

session_start();
unset($_SESSION['auth']);

echo "<script language='javascript'>
    document.location.replace('../_views/login.php')
    </script>";

?>