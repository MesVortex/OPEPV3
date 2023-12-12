<?php
session_start();
include_once('../config/db.php');

if (!empty($_POST['article_title']) && !empty($_POST['article_text']) && !empty($_FILES['article_image']) && !empty($_POST['tags']) && !empty($_POST['theme']) && isset($_POST['counter'])) {
  $titles = $_POST['article_title'];
  $texts = $_POST['article_text'];
  $tags = $_POST['tags'];
  $theme = $_POST['theme'];
  $articleCounter = $_POST['counter'];
  $userid = $_SESSION['user_id'];

  $img_name = $_FILES['article_image']['name'];
  $img_size = $_FILES['article_image']['size'];
  $tmp_name = $_FILES['article_image']['tmp_name'];
  $error = $_FILES['article_image']['error'];

  $tag = $con->prepare("SELECT * FROM theme_tag
  JOIN tag ON tag.tag_id = theme_tag.tag_id
  WHERE theme_tag.theme_id = ?
  ");
  $tag->bind_param('i',$theme);
  $tag->execute();
  $result = $tag->get_result();
  $count = 0;

  while($row = $result->fetch_assoc()){
    $count++;
  }

  //checking if any inputs are empty

  $emptyInput = false;

  foreach ($titles as $title) {
    if (empty($title)) {
      $emptyInput = true;
      break;
    }
  }
  foreach ($texts as $text) {
    if (empty($text)) {
      $emptyInput = true;
      break;
    }
  }
  foreach ($img_name as $img) {
    if (empty($img)) {
      $emptyInput = true;
      break;
    }
  }

  for ($i = 0; $i <= $articleCounter; $i++) {
    if ($emptyInput != true) {

      // image err handling and uplaod
      if($error[$i] === 0){
        if($img_size[$i] > 10000000){
    
          $largeFileErr = 'File is too large, Must be less than 10MB';
          header('location: ../../ADD_ARTICLE.php?theme='.$theme.'&LF_error='.$largeFileErr); 
          die();    
    
        }else{
          //checking file type
          $img_extention = pathinfo($img_name[$i], PATHINFO_EXTENSION);
          $lowerCaseImgExtention = strtolower($img_extention);
    
          $allowedTypes = ['jpg', 'png', 'jpeg'];
    
          if(in_array($lowerCaseImgExtention, $allowedTypes)){

            //creating path
            $new_img_name = uniqid('IMG-', true).'.'.$lowerCaseImgExtention;
            $img_local_path = '../../assets/imgs/uploads/'.$new_img_name;
            move_uploaded_file($tmp_name[$i], $img_local_path);

            // insert both the image and the other data into db
            $query = $con->prepare("INSERT INTO article (article_title, article_img, article_text, theme_ID, article_user) VALUES (?,?,?,?,?)");
            $query->bind_param("sssii", $titles[$i], $new_img_name, $texts[$i], $theme, $userid);
            $query->execute();
      
            $lastID_query = $con->query("SELECT article_id FROM `article` ORDER BY article_id DESC LIMIT 1");
            $lastID = $lastID_query->fetch_assoc();
          
            foreach($tags as $tag){
              for($j = 0; $j < $count; $j++){
                if(isset($tags[$i][$j])){
                  $insertTag = $con->prepare("INSERT INTO article_tag (article_id, tag_id) VALUES (?, ?)");
                  $insertTag->bind_param("ii", $lastID['article_id'], $tags[$i][$j]);
                  $insertTag->execute();
                }
              }
            }
      

          }else{
            $WrongFileErr = "Can't uplaod files of this type!";
            header('location: ../../ADD_ARTICLE.php?theme='.$theme.'&WF_error='.$WrongFileErr);     
            die(); 
          }
        } 
      }else{
        $fileErr = "Uknown Error";
        header('location: ../../ADD_ARTICLE.php?theme='.$theme.'&error='.$fileErr);
        die();       
      }
    }else{
      $emptyInputErr = "Please fill out all the empty fields before submitting";
      header('location: ../../ADD_ARTICLE.php?theme='.$theme.'&Empty_error='.$emptyInputErr);  
      die(); 
    }
  }

  header('location: ../../articles.php?theme='.$theme);

  die();
  
}else{
  if(!empty($_POST['theme'])){
    $theme = $_POST['theme'];
    $emptyInputErr = "Please fill out all the empty fields before submitting";
    header('location: ../../ADD_ARTICLE.php?theme='.$theme.'&Empty_error='.$emptyInputErr);
    die(); 
  }
}

