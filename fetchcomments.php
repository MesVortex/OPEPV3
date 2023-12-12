<?php
include_once('./app/config/db.php');
$unique = bin2hex(random_bytes(8));
$unique = preg_replace("/[^A-Za-z]/", '', $unique);
if(isset($_GET['ARTICLE_ID'])) {
    $articleID = $_GET['ARTICLE_ID'];
    $user_id = $_GET['user'];
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
                    <button class="MODIFY btn btn-primary" data-bs-toggle="modal" data-bs-target="#<?php echo $unique?>">MODIFY</button>
                    <button onclick="DELETE(<?php echo $row['comment_id']?>)" value="<?php echo $row['comment_id']?>" name="DELETE" class="DELETE">DELETE</button>
                    <?php
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



        ?>
         <!-- Modal -->
<div class="modalmodifycomment modal fade" id="<?php echo $unique?>" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
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
    
}
            
        
        ?>
