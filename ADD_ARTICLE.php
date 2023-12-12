<?php
include_once('./app/config/db.php');
if(!empty($_SESSION['user_id'])) {
    header('location: blog.php');
}
session_start();

if (isset($_SESSION['user_id'])) {
    $userid = $_SESSION['user_id'];
}

if (isset($_GET['theme'])) {
    $theme = $_GET['theme'];
}

$counter = 0;

// if (isset($_POST['submit'])) {
//     $titles = $_POST['article_title'];
//     $texts = $_POST['article_text'];
//     $imgs = $_POST['article_image'];
//     $tags = $_POST['tags'];

//     $emptyInput = false; 

   
//     if (empty($titles) || empty($texts) || empty($imgs)) {
//         $emptyInput = true;
//     } else {
//         foreach ($titles as $title) {
//             if (empty($title)) {
//                 $emptyInput = true;
//                 break;
//             }
//         }

//         foreach ($texts as $text) {
//             if (empty($text)) {
//                 $emptyInput = true;
//                 break;
//             }
//         }

//         foreach ($imgs as $img) {
//             if (empty($img)) {
//                 $emptyInput = true;
//                 break;
//             }
//         }
//     }


//     if (!$emptyInput) {
//         $countTitles = count($titles); 
//         $countTags = count($tags); 
    
//         for ($i = 0; $i < $countTitles; $i++) {
//             if (isset($titles[$i]) && isset($texts[$i]) && isset($imgs[$i])) {
//                 $query = $con->prepare("INSERT INTO article (article_title, article_img, article_text, theme_ID, article_user) VALUES (?,?,?,?,?)");
//                 $query->bind_param("sssii", $titles[$i], $imgs[$i], $texts[$i], $theme, $userid);
//                 $query->execute();
    
//                 $lastid = $con->insert_id;
//                 if (isset($tags[$i]) && is_array($tags[$i])) {
//                     $countTagsForArticle = count($tags[$i]); 
//                     for ($j = 0; $j < $countTagsForArticle; $j++) {
//                     $insertTag = $con->prepare("INSERT INTO article_tag (article_id, tag_id) VALUES (?, ?)");
//                     $insertTag->bind_param("ii", $lastid, $tags[$i][$j]);
//                     $insertTag->execute();
//                     }
//                 }
//             }
//         }
//     } else {
//         echo "INPUTS EMPTY";
//     }
    




// }
?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
       <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">

    <title>Add articles</title>
</head>
<body>
  <div class="hero border border-green-600 max-w-5xl mx-auto rounded-xl mb-3 pl-40 pr-40  mt-10   "">
  <!-- <div class="preview h-50 w-50 d-flex align-items-center justify-content-center">
        <img class="previewimage w-50 h-50" src="" alt="Preview">
    </div> -->

    <form action="./app/funcs/massInsertion.php" method="POST" class="forma d-flex flex-column" enctype="multipart/form-data">
    <div class="flex flex-col gap-8 items-center mt-5">
        <input  type="file" id="upload_img" class="form-control" name="article_image[]" >
        <input  class="w-full h-10 border border-green-700 rounded-xl pl-10"  type="text" placeholder="TITLE...." class="form-control" name="article_title[]">
        <input class="w-full h-10 border border-green-700 rounded-xl pl-10 " type="text" placeholder="write your text ....." class="form-control" name="article_text[]">
        <input type="hidden" name="theme" value="<?php echo $theme ?>">
        <div class="checkboxes mr-96">
            <?php
            $tag=$con->prepare("SELECT * FROM theme_tag
            JOIN tag ON tag.tag_id = theme_tag.tag_id
            WHERE theme_tag.theme_id = ?
            ");
            $tag->bind_param('i',$theme);
            $tag->execute();
            $result = $tag->get_result();
            
            while($row = $result->fetch_assoc()) {
                
                ?>
                <div class=" border border-green-500 mb-5 rounded-xl pl-3 pr-4 h-7">
                <input type="checkbox" class="" value="<?php echo $row['tag_id']?>" name="tags[<?php echo $counter?>][]">
                <label for="checkbox"><?php echo $row['tag_name']?></label>
                </div>
                <?php
            }
            ?>
        </div>
        </div>


        <input type="hidden" value="0" name="counter" id="counter">

        <div class="max-w-3xl mx-auto pl-56 mt-12">
  <button type="submit" name="submit" class=" fixed bottom-64 right-0 mb-5  w-28 h-10 rounded-xl hover:bg-white hover:text-green-600 border border-green-700 bg-green-500 text-white duration-300 ease-in-out" id="submitAll">Submit all</button>
  <?php
  if(isset($_GET['LF_error'])){
    echo '<div><p class="text-red-400 mb-3">'.$_GET['LF_error'].'</p></div>';
  }
  if(isset($_GET['WF_error'])){
    echo '<div><p class="text-red-400 mb-3">'.$_GET['WF_error'].'</p></div>';
  }
  if(isset($_GET['error'])){
    echo '<div><p class="text-red-400 mb-3">'.$_GET['error'].'</p></div>';
  }
  if(isset($_GET['Empty_error'])){
    echo '<div><p class="text-red-400 mb-3">'.$_GET['Empty_error'].'</p></div>';
  }
  ?>

</div>
    </form>
    
 
  
 
  <button id="addmore" class=" ml-48 mb-10 mx-auto w-96 h-10 rounded-xl hover:text-green-600 border border-green-700 bg-green-500 text-white hover:bg-white duration-300 ease-in-out" onclick="add()">Add more</button>

 </div>









    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
    <script>
        // function previewImage(event) {
        //     var input = event.target;
        //     var preview = document.querySelector('.previewimage');
        //     if (input.files && input.files[0]) {
        //         var reader = new FileReader();
        //         reader.onload = function(e) {
        //             preview.src = e.target.result;
        //         }
        //         reader.readAsDataURL(input.files[0]);
        //     }
        // }
        var checkboxesdiv =document.querySelector('.checkboxes');




        let counterJS = 0;

        function add() {
            counterJS++;
    var form = document.querySelector('.forma');
    var inputGroup = document.createElement('div');
    
    let XM = new XMLHttpRequest();
    
    XM.onreadystatechange = function () {
        if (this.readyState === 4 && this.status === 200) {
            inputGroup.innerHTML = this.responseText;
            form.appendChild(inputGroup);
        }
    };

    
    
    XM.open('GET', `tags.php?theme=<?php echo $theme ?>&articleCounter=${counterJS}`);
    XM.send();

    let counter = document.getElementById('counter');

    counter.value = counterJS;

}

    


        function removeArticle(button) {
        var div = button.parentNode;
        div.parentNode.removeChild(div);
        counterJS--;
    }


    // let submit = document.getElementById('submitAll');

    // submt.addEventListener('click' , function () {
    //     window.document.close();
    // })


    </script>
</body>
</html>
