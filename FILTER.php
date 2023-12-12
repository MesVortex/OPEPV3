<?php
include_once('./app/config/db.php');
if(isset($_GET['array']) && $_GET['array'] !=='') {
    $ids = json_decode($_GET['array']);


    $placeholders = rtrim(str_repeat('?,', count($ids)), ',');


    $filter = $con->prepare("SELECT DISTINCT article.* FROM article 
    JOIN article_tag ON article_tag.article_id=article.article_id
    JOIN tag ON tag.tag_id = article_tag.tag_id
    WHERE article_tag.tag_id IN ($placeholders);
    ");

    $filter->bind_param(str_repeat('i',count($ids)),...$ids);
    $filter->execute();
    $result = $filter->get_result();
    while($row = $result->fetch_assoc()) {
        ?>
        <div onclick="attachClickListeners(<?php echo $row['article_id']?>)" class="card ml-7 border border-green-500 rounded-xl transition-transform duration-300 ease-in-out transform hover:scale-105 hover:shadow-2xl mr-4" data-key="<?php echo $row['article_id']?>">
                    <div class="ml-5 mr-5 mt-5 mb-5">
                        <h1  class="text-2xl text-center font-semibold mb-3 "><?php echo $row['article_title']?></h1>
                        <img class=" h-96 w-96 mb-5" src="./assets/imgs/uploads/<?php echo $row['article_img'] ?>" alt="">
                        <h3 class=" font-sans"><?php echo $row['article_text']?></h3>
                    </div>  
                    </div>
        <?php
    }
}
?>