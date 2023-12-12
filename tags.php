<?php
include_once('./app/config/db.php');
if(isset($_GET['theme'])) {
    $idtheme = $_GET['theme'];

    $select = $con->prepare("SELECT * FROM theme_tag
    JOIN tag ON tag.tag_id = theme_tag.tag_id
    WHERE theme_tag.theme_id = ?");
    $select->bind_param('i',$idtheme);
    $select->execute();
    $result = $select->get_result();
    ?>
    <div class="flex flex-col gap-8 items-center mt-5">
    <?php
    while($row = $result->fetch_assoc()) {
        ?>
        <input type="file" id="upload_img" class="form-control" name="article_image[]" onchange="previewImage(event)">
        <input type="text" placeholder="TITLE...." class="w-full h-10 border border-green-700 rounded-xl pl-10" name="article_title[]">
        <input type="text" placeholder="write your text ....." class="w-full h-10 border border-green-700 rounded-xl pl-10" name="article_text[]">
        <div class="checkboxes d-flex flex-column">
            <?php
            $tag=$con->prepare("SELECT * FROM theme_tag
            JOIN tag ON tag.tag_id = theme_tag.tag_id
            WHERE theme_tag.theme_id = ?
            ");
            $tag->bind_param('i',$idtheme);
            $tag->execute();
            $result = $tag->get_result();
            $number = $_GET['articleCounter'];
            while($row = $result->fetch_assoc()) {
                ?>
                <div class=" border border-green-500 mb-5 rounded-xl pl-3 pr-4 h-7">
                <input type="checkbox" class="form-check-input" value="<?php echo $row['tag_id']?>" name="tags[<?php echo $number?>][]">
                <label for="checkbox"><?php echo $row['tag_name']?></label>
                </div>
                <?php
            }
            
            ?>

            
        </div>
        <button type="button" class=" mb-5 border border-red-500 rounded-xl w-28 h-12 text-red-600 hover:bg-red-600 hover:text-white font-mono duration-200 ease-in" onclick="removeArticle(this)">DELETE</button>
        <?php
        
    }
    ?>


    </div>
    <?php
}


?>