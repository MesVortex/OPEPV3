<?php
include_once('./app/config/db.php');
if(isset($_GET["CMTID"]) && isset($_GET["CMTVALUE"])) {
    $commentid = $_GET['CMTID'];
    $commentvalue = $_GET['CMTVALUE'];

    $check = $con->prepare("UPDATE comments SET comment_text = ? WHERE comment_id = ?");
    $check->bind_param("si",$commentvalue ,$commentid);
    $check->execute();
}
?>