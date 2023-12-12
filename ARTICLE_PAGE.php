<?php
include_once("./app/config/db.php");
require_once('./app/funcs/logout.php');



session_start();
if(isset($_SESSION["user_id"])){
$user_id = $_SESSION["user_id"];
}
else{
    header('location: index.php');
}
if(isset($_GET["articleid"])) {
    $articleID = $_GET["articleid"];
}
$uniqueID = bin2hex(random_bytes(8));
$uniqueID = preg_replace("/[^A-Za-z]/", '', $uniqueID);

$theme = $con->prepare("SELECT theme_ID FROM `article` WHERE article_id = ?;");
$theme->bind_param('i',$articleID);
$theme->execute();
$result = $theme->get_result();
$themeID_row = $result->fetch_assoc();
$idtheme = $themeID_row['theme_ID'];

?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <link rel="shortcut icon" href="assets/imgs/logoG.png" type="image/x-icon">

<link href="https://cdn.jsdelivr.net/npm/remixicon@2.5.0/fonts/remixicon.css" rel="stylesheet">
    <link rel="stylesheet" href="./assets/css/ratings.css">
    <link rel="stylesheet" href="./assets/css/articlepage.css">
    <link rel="stylesheet" href="./assets/css/home.css">
    <title>article</title>
</head>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    <script src="https://use.fontawesome.com/fe459689b4.js"></script>

<body>

  <header>
  <nav class="nav container">
      <a href="#" class="nav__logo">
        <img src="./assets/imgs/logoG.png" alt="logo">
      </a>
      <div class="search">
        <form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
          <input name="plant_name" type="text" placeholder="Search here">
          <i class="ri-search-2-line"></i>
        </form>
      </div>
      <div class="nav__menu" id="nav-menu">
        <ul class="nav__list">
          <li class="nav__item">
            <a href="#home" class="nav__link active-link">Home</a>
          </li>
          <li class="nav__item">
            <a href="#products" class="nav__link">Products</a>
          </li>
          <li class="nav__item">
            <a href="./articles.php?theme=<?php echo $idtheme?>" class="nav__link">Articles</a>
          </li>
          <!-- log out -->
          <li>
            <form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
              <button type="submit" name="logout" class="button--flex navbar__button">
                <i class="ri-logout-box-r-line"></i>
              </button>
            </form>
          </li>
        </ul>


      </div>
    </nav>
  </header>

    <?php
    $selection = $con->prepare("SELECT * FROM article,users WHERE  article.article_id = ? AND  users.user_id = ?");
    $selection->bind_param('ii',$articleID,$user_id);
    $selection->execute();
    $result = $selection->get_result();
    if($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        ?>

        
        <div class="">
        <H1 class="text-center"><?php echo $row['article_title']?></h1>
       <?php
       if($row['article_user'] == $user_id || $row['role_id'] == 2){
        ?>
        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#<?php echo $uniqueID?>">Modify</button>
        <form action="./delete_article.php" method="post" class="d-inline">
            <input type="hidden" name="articleID" value="<?php echo $articleID ?>">
            <button type="submit" class="btn btn-danger" value="<?php echo $row['article_id']?>">DELETE ARTICLE</button>
        </form>
        <?php
       }
       ?>
        </div>


        <?php
        $articles = mysqli_query($con, "SELECT * FROM article WHERE article_id = '$articleID'");
        $articles_rows = mysqli_fetch_assoc($articles);
        
        foreach ($articles as $article):
            $article_id = $articleID;
            $likes_count = mysqli_fetch_assoc(mysqli_query($con,
                "SELECT COUNT(*) AS likes FROM ratings WHERE article_id = $article_id AND status = 'like'"))['likes'];
            $dislikes_count = mysqli_fetch_assoc(mysqli_query($con,
                "SELECT COUNT(*) AS dislikes FROM ratings WHERE article_id = $article_id AND status = 'dislike'"))['dislikes'];
            $status = mysqli_query($con, "SELECT status FROM ratings WHERE article_id = $article_id AND user_id = $user_id ");
            if (mysqli_num_rows($status) > 0) {
                $status = mysqli_fetch_assoc($status)['status'];
            } else {
                $status = 0;
            }
            ?>
<div class="modal fade" id="<?php echo $uniqueID?>" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="exampleModalLabel">ARTICLE ID : <?php echo $row['article_id']?></h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <label for="article_title">article_title</label>
        <input type="text" class="w-100 article_title" value="<?php echo $row['article_title']?>">
        <label for="textarea" class="mt-1">article_text</label>
        <textarea class="form-control mt-2 message-text"><?php echo $row['article_text']?></textarea>
        <form id="uploadForm" enctype="multipart/form-data">
            <input type="file" name="article_image" class="form-control article_image">
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        <button type="button" class="SAVEARTICLE btn btn-primary" value="<?php echo $row['article_id']?>">Save changes</button>
      </div>
    </div>
  </div>
</div>
        <div>
            <div class="w-25">
                <img src="./assets/imgs/uploads/<?php echo $row['article_img'] ?>" alt="">
            </div>
            <div>
                <p><?php echo $row['article_text']?></p>
            </div>
        </div>
<div class="like-dislike-btn">
            <form action="./rating.php" method="post">
               <input type="hidden" name="articleID" value="<?php echo $articleID; ?>">
               <button name="like" value="1" class="like <?php if($status == 'like') echo "selected"; ?>" data-article-id = <?php echo $article_id ?> >
                   <i class="fa fa-thumbs-up"></i>
                   <span class="like_count <?php $article_id; ?> data_count = <?php echo $likes_count;  ?>"> <?php echo $likes_count; ?></span>
               </button>
            </form>
            <form action="./rating.php" method="post">
                <input type="hidden" name="articleID" value="<?php echo $articleID; ?>"  >
                <button name="like" value="0" class="dislike <?php if($status == 'dislike') echo "selected"; ?>" data-article-id = <?php echo $article_id ?> >
                    <i class="fa fa-thumbs-down"></i>
                    <span class="dislike_count <?php $article_id; ?> data_count = <?php echo $dislikes_count;  ?>"> <?php echo $dislikes_count; ?></span>
                </button>
            </form>
<?php endforeach; ?>
</div>
        <!-- Modal -->
<!-- Modal -->



        <h2>COMMENTS</h2>
            <div class="cmntinput">
            <input type="text" id="COMMENT" placeholder="Add comment..." name="ADDCOMMENT">
            <button id="ADDCOMMENT">COMMENT</button>
            </div>
       <div class="comments">

         <!-- HERE CONTENT -->


         <?php 
        if(!isset($_GET['ARTICLE_ID'])) {
            $comment = $con->prepare('SELECT * FROM comments WHERE  article_id = ? AND comment_status = "COMMENTED"');
            $comment->bind_param('i',$articleID);
            $comment->execute();
            $resultcomments = $comment->get_result();
            $n = 0;
            while ($row = $resultcomments->fetch_assoc()) {
                $commentID = $row['comment_id'];
            
                $selected = $con->prepare("SELECT *
                    FROM comments
                    JOIN users ON comments.user_id = users.user_id
                    WHERE comments.comment_id = ?");
                $selected->bind_param('i', $commentID);
                $selected->execute();
                $resultselected = $selected->get_result();
                $rowselected = $resultselected->fetch_assoc();
            
                if ($rowselected) {
                    ?>
                    <div>
                        <h1><?php echo $rowselected['user_name']?></h1>
                        <p><?php echo $rowselected['comment_text']?></p>
                        <?php
                        if ($row['user_id'] == $user_id || $rowselected['role_id'] == 2) {
                            ?>
                            <button class="MODIFY btn btn-primary" data-bs-toggle="modal" data-bs-target="#<?php echo $row['comment_text']?>" >MODIFY</button>
                            <button onclick="DELETE(<?php echo $row['comment_id']?>)" value="<?php echo $row['comment_id']?>" name="DELETE" class="DELETE">DELETE</button>
                            
                            <!-- Modal -->
<div class="modalmodifycomment modal fade" id="<?php echo $row['comment_text']?>" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">COMMENT ID : <?php echo $row['comment_id']?></h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <input type="text" placecomment="new comment ..." class="modifycomment" value="<?php echo $row['comment_text']?>">
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        <button onclick="modify(this,<?php echo $n?>)" type="button" class="SAVE btn btn-primary" value="<?php echo $row['comment_id']?>" data-bs-dismiss="modal">Save changes</button>
      </div>
    </div>
  </div>
</div>
                            
                            
                            <?php
                            $n++;
                        }
                        ?>
                    </div>
                    <?php
                } else {
                    ?>
                    <h1><?php echo $rowselected['user_name']?></h1>
                    <p><?php echo $row['comment_text']?></p>
                    <?php
                }
            }
            
        }
   

         ?>

       </div>


        <?php
    
 }
    
    ?>
   
    
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
    <script>
        var sendcomment =document.querySelector('#ADDCOMMENT');
        var inputcomment = document.querySelector('#COMMENT');
        var placecomment =document.querySelector('.comments');


        // BOTTON SEND COMMENTS
        sendcomment.addEventListener('click' ,function () {
            let commentvalue = inputcomment.value;
            
            let XML = new XMLHttpRequest();

            XML.onreadystatechange =function () {
             if(this.status == 200) {
                fetchUpdatedComments();
                commentvalue = '';
             }
            }

            XML.open('POST', 'comment.php');
    XML.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
    XML.send(
        'COMMENTUSER=<?php echo $user_id?>&ARTICLE_ID=<?php echo $articleID?>&COMMENTVALUE=' + commentvalue
    );
        } )


            // fetching DATAA FROM COMMENTS
        function fetchUpdatedComments() {
        let commentFetch = new XMLHttpRequest();

        commentFetch.onreadystatechange = function () {
            if (this.readyState == 4 && this.status == 200) {
                placecomment.innerHTML = this.responseText; 
            }
        }

        
        commentFetch.open('GET', 'fetchcomments.php?ARTICLE_ID=<?php echo $articleID?>' + '&user=<?php echo $user_id?>');
        commentFetch.send();
    }



    // DELETING COMMENTS
            function DELETE(id) {
                                Swal.fire({
                title: "Are you sure?",
                text: "You won't be able to revert this!",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Yes, delete it!"
                }).then((result) => {
                if (result.isConfirmed) {
                    let XML = new XMLHttpRequest();
                    XML.onreadystatechange=function() {
                        if(this.status==200) {
                            
                            fetchUpdatedComments();
                            Swal.fire({
                    title: "Deleted!",
                    text: "Your file has been deleted.",
                    icon: "success"
                    });
                        }
                    }
                    

                    XML.open('GET','delete_comment.php?DELETEID='+id);
                    XML.send();
                    
                }
                });
        
            }




            // modify comment 
            var COMMENTMODIFY =document.querySelectorAll('.modifycomment');
            var SAVE =document.querySelectorAll('.SAVE');


                    function modify (btn,i) {
                        let valuesave =COMMENTMODIFY[i].value;
                    let btnvalue = btn.value;
                    console.log(valuesave);
                    console.log(btnvalue);
                    let XML = new XMLHttpRequest();
                    XML.onreadystatechange = function () {
                        if(this.status==200) {
                            fetchUpdatedComments();
                            location.reload();
                        }
                    }
                    XML.open('GET','MODIFY.php?CMTID='+btnvalue + '&CMTVALUE='+valuesave);
                    XML.send();
                    }
            


                        var save = document.querySelectorAll('.SAVEARTICLE');
            var articletitle = document.querySelectorAll('.article_title');
            var messagetext = document.querySelectorAll('.message-text');
            var articleimage = document.querySelectorAll('.article_image');

            save.forEach((btn, index) => {
                btn.addEventListener('click', function () {
                    let message = messagetext[index].value;
                    let articletitletest = articletitle[index].value;
                    let articleImageFile = articleimage[index].files[0];

                    let savebtn = btn.value;

                    let formData = new FormData(); 
                    formData.append('articleid', savebtn);
                    formData.append('articletitle', articletitletest);
                    formData.append('articlemessage', message);
                    formData.append('articleimg', articleImageFile); 

                    let XML = new XMLHttpRequest();

                    XML.onreadystatechange = function () {
                        if (this.status == 200) {
                            console.log("Successfully updated!");
                            location.reload();
                        }
                    }

                    XML.open('POST', 'modifyarticle.php');
                    XML.send(formData); 
                })
            })


            

   


    </script>
</body>
</html>
