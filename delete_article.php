<?php
include_once("./app/config/db.php");

if(isset($_POST['articleID'])){
  $articleID = $_POST['articleID'];

  $theme_query = $con->query("SELECT theme_ID FROM article WHERE article_id = $articleID");
  $theme = $theme_query->fetch_assoc();
  $themeID = $theme['theme_ID'];

  echo $themeID;

  $delete_query = $con->query("DELETE FROM article WHERE article_id = $articleID");

  header("location: ./articles.php?theme=".$themeID);
  
}else{
  echo "I didn't make it";
}