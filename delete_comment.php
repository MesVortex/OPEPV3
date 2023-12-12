<?php
include_once('./app/config/db.php');
if(isset($_GET['DELETEID'])) {
    $idcomment = $_GET['DELETEID'];
    $delete = $con->prepare('UPDATE comments SET comment_status = "ARCHIVED"  WHERE comment_id = ?');
    $delete->bind_param('i',$idcomment);
    $delete->execute();
 }

?>