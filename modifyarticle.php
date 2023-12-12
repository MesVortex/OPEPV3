<?php
include_once('./app/config/db.php');

if(isset($_POST['articleid']) && isset($_POST['articletitle']) && isset($_POST['articlemessage']) && isset($_FILES['articleimg'])) {
    $articleid = $_POST['articleid'];
    $articletitle = $_POST['articletitle'];
    $articlemessage =$_POST['articlemessage'];

    if ($_FILES['articleimg']['error'] === UPLOAD_ERR_OK) {
        $fileTmpPath = $_FILES['articleimg']['tmp_name'];
        $fileName = $_FILES['articleimg']['name'];

       
        $fileContent = file_get_contents($fileTmpPath);

        if ($articletitle != '' && $articlemessage != '') {
            
            $PREPARE = $con->prepare('UPDATE article SET article_title = ?, article_img = ? , article_text = ? WHERE article_id = ?');
            $PREPARE->bind_param('sssi', $articletitle, $fileContent, $articlemessage, $articleid);
            $PREPARE->execute();
        
        } else {
            echo "Article title or message is empty!";
        }
    }

}
?>


