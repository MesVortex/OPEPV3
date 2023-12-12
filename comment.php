<?php
include_once('./app/config/db.php');
session_start();

 if(isset($_SESSION['user_id'])){
      $user_id = $_SESSION['user_id'];
     echo ''.$user_id.'';
  }
 if(isset($_POST['COMMENTUSER'])) {
    if(!empty($_POST['COMMENTUSER']) && !empty($_POST['COMMENTVALUE'])) {
        $user_id = $_POST['COMMENTUSER'];
    $article = $_POST['ARTICLE_ID'];
    $comment = $_POST['COMMENTVALUE'];
    $ADD = $con->prepare('INSERT INTO comments (article_id,user_id,comment_text) VALUES (?,?,?)');
    $ADD->bind_param('iis',$article,$user_id,$comment);
    $ADD->execute();
    }
 }

?>

