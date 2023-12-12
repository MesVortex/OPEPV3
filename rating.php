<?php
session_start();
$user_id = $_SESSION["user_id"];

if(isset($_POST['like']) &&  isset($_POST['articleID'])){
    $rating = $_POST['like'];
    $articleID = $_POST['articleID'];

    include_once("./app/config/db.php");


    if($rating  == 1){

        $check_query = "SELECT * FROM ratings WHERE user_id = $user_id  AND article_id = $articleID";
        $result_check = mysqli_query($con, $check_query);
        $row_check = mysqli_fetch_assoc($result_check);
        if(!empty($row_check)){
            echo $row_check['status'];
            if($row_check['status'] == 'like'){

                $delete_query = "DELETE FROM ratings WHERE user_id = $user_id  AND article_id = $articleID";
                $result_delete = mysqli_query($con,  $delete_query);
                
            }else{

                $add_query ="UPDATE ratings SET status = 'like' WHERE user_id = $user_id  AND article_id = $articleID";
                $result = mysqli_query($con , $add_query);
            }
        }else{

            $add_query ="INSERT INTO ratings values ('', $articleID, $user_id, 'like')";
            $result = mysqli_query($con , $add_query);

        }
        
    }else{

        $check_query = "SELECT * FROM ratings WHERE user_id = $user_id  AND article_id = $articleID";
        $result_check = mysqli_query($con, $check_query);
        $row_check = mysqli_fetch_assoc($result_check);
        if(!empty($row_check)){
            if($row_check['status'] == 'dislike'){
                  
                $delete_query = "DELETE FROM ratings WHERE user_id = $user_id  AND article_id = $articleID";
                $result_delete = mysqli_query($con,  $delete_query);

            }else{

                $add_query ="UPDATE ratings SET status = 'dislike' WHERE user_id = $user_id  AND article_id = $articleID";
                $result = mysqli_query($con , $add_query); 
                
            }
        }else{
            $query ="INSERT INTO ratings values ('', $articleID, $user_id, 'dislike')";
            $result = mysqli_query($con , $query);
        }
    }

     header('location: ./ARTICLE_PAGE.php?articleid='.$articleID);

    exit();



}