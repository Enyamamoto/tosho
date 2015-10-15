<?php
session_start();
require('dbconnect.php');

$sql = sprintf('DELETE FROM requests WHERE id=%d',
		mysqli_real_escape_string($db,$_GET['request_id']));
		mysqli_query($db,$sql) or die(mysqli_error($db));


$url = "staff.php?id=".$_REQUEST['id'];
    header('Location:'.$url);
    exit();


?>