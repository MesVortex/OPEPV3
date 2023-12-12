    <?php
    include_once('./app/config/db.php');
    if(isset($_GET['theme'])) {
        $idtheme = $_GET['theme'];
}
    ?>

    <!doctype html>
    <html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Bootstrap demo</title>
        <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    </head>
    <body>

    <div class="flex flex-col gap-7 items-end">
   <div class="relative  mx-auto w-6/12 border border-green-800 rounded-xl overflow-hidden ">
    <input class="search w-full rounded-xl h-10 pl-10 pr-4 bg-slate-600 text-black text-xl " type="text" placeholder="Search for article">
    <svg class="absolute w-10 h-10 pt-5 text-gray-500 dark:text-gray-400 top-2 right-3 transition-transform duration-300 ease-in-out transform -translate-y-1/2" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 20">
        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m19 19-4-4m0-7A7 7 0 1 1 1 8a7 7 0 0 1 14 0Z"/>
    </svg>
</div>

<a href="./ADD_ARTICLE.php?theme=<?php echo $idtheme?>" class="w-44 text-center p-2 h-10 text-green-600 font-mono mr-5 rounded-xl border border-green-600 hover:bg-green-300 hover:text-white ">Add New Article</a> </div>




        <div class="HERO  flex mt-5 space-x-10">
      
            
            
            <div class="filter " style="height: 50vh;">

                <div class="filter flex flex-col gap-5 ">
                <?php
                $tag = $con->prepare("SELECT * FROM tag
                JOIN theme_tag ON theme_tag.tag_id = tag.tag_id
                JOIN theme ON theme.theme_id = theme_tag.theme_id
                WHERE theme.theme_id = $idtheme;
                ");
                $tag->execute();
                $result = $tag->get_result();
                while($row = $result->fetch_assoc()) {
                    ?>
                   <div>
                   <div class="w-36 ml-10 mt-3 border  border-green-400 rounded-xl h-8 flex justify-around hover:bg-green-300 duration-300 ease-in-out ">
                    <input type="checkbox" class="BOX"  name="BOX" value="<?php echo $row ['tag_id']?>">
                    <label class="pr-10 font-mono hover:text-white" for="BOX"><?php echo $row['tag_name']?></label>
                 
                   </div>
                   </div>
                   
                    <?php
                }
                ?>
            

        </div>
        </div>
        <div class="border border-green-800" ></div>
        <div class="left grid  grid-cols-2 gap-4 place-content-center  ">

            <!-- content here -->
            <?php 
            if(!isset($_GET['themesearch']) && !isset($_GET['search']) && !isset($_POST['page'])) {
                $selec = $con->prepare("SELECT * FROM article WHERE theme_id = $idtheme LIMIT 10");
                $selec->execute();
                $result = $selec->get_result();
                while($row = $result->fetch_assoc()) {
                    $articleID = $row['article_id'];
                    ?>
                    <div onclick="attachClickListeners(<?php echo $articleID?>)" class="card  ml-7 border border-green-500 rounded-xl transition-transform duration-300 ease-in-out transform hover:scale-105 hover:shadow-2xl mr-4" data-key="<?php echo $articleID?>">
                    <div class="ml-5 mr-5 mt-5 mb-5">
                        <h1 class="text-2xl text-center font-semibold mb-3 "><?php echo $row['article_title']?></h1>
                        <img class=" h-96 w-96 mb-5" src="./assets/imgs/uploads/<?php echo $row['article_img'] ?>" alt="">
                        <h3 class=" font-sans"><?php echo $row['article_text']?></h3>
                    </div>
                    </div>
                <?php    
        }
    }
            
            ?>
           


            </div>
        </div>
        

        <div class="pagination ">
        <?php
            

            $page = $con->prepare("SELECT COUNT(article_id) as totalarticle FROM article WHERE theme_id = '$idtheme'");
            $page->execute();
            $result =$page->get_result();
            $row = $result->fetch_assoc();
            $pagination = $row['totalarticle'];

            $totalpage = ceil($pagination/10);
            if($totalpage>1){
                ?>
                <div class="pagination  flex justify-center mt-7 mb-7">
                    <?php
                    for($i=1;$i<=$totalpage;$i++){
                        ?>
                        <button class="page w-14 rounded-xl border border-green-500 hover:bg-green-500 hover:text-white duration-200 ease-in-out " value="<?php echo $i?>"><?php echo $i?></button>
                        <?php
                    }
                    ?>
                </div>
                <?php
            }
        ?>
        </div>
        
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
           
           
           <script>




                var input =document.querySelector('.search');
                var article =document.querySelectorAll('.card');
                var pagebutton =document.querySelectorAll('.page');


                // article.forEach(element => {
                //     element.addEventListener('click' , function () {
                //             let cardvalue = this.getAttribute('data-key');
                //             window.open('ARTICLE_PAGE.php?articleid='+cardvalue)
                //     })
                // });

                function attachClickListeners(cardValue) {
            console.log(cardValue);
             if (cardValue) {
                 window.location.href = `ARTICLE_PAGE.php?articleid=${cardValue}`;
             } else {
                 console.error("No data-key attribute found on clicked card.");
             }
}

                


                function affichage() {
                    let XML = new XMLHttpRequest();

                            XML.onload =function () {
                                if(this.readyState==4 && this.status==200) {
                                    document.querySelector('.left').innerHTML = this.responseText;
                                }
                            }
                            XML.open('GET','AFFICHAGE.php?themesearch=<?php echo $idtheme?>');
                            XML.send();
                }




                input.addEventListener("input" ,inpt => {
                    let value = inpt.target.value;
                    console.log(value);
                    if(value === '') {
                              affichage();  
                    }
                    else {
                                let XML =new XMLHttpRequest();

                            XML.onload = function () {
                                if(this.readyState==4 && this.status==200){
                                    document.querySelector('.left').innerHTML = this.responseText;
                                }
                            }
                            XML.open('GET','SEARCH.php?search='+value + '&theme='+<?php echo $idtheme?>);
                            XML.send();
                    }
                    
                })



                var checkbox =document.querySelectorAll('.BOX');
                var checked = [];


                checkbox.forEach(check => {
                    check.addEventListener('change' , function () {
                        if(this.checked){
                        checked.push(this.value);  
                        }
                        else{
                            let index = checked.indexOf(this.value);
                            if(!index!==-1){
                                checked.splice(index,1);
                            }
                        }

                        let XM =new XMLHttpRequest();

                        XM.onload= function () {
                            if(this.status==200 && this.readyState==4) {
                               if(checked.length>0){
                                document.querySelector('.left').innerHTML = this.responseText;
                               }
                               else {
                                    affichage();
                               }
                            }
                        }

                        XM.open('GET','FILTER.php?array='+ JSON.stringify(checked));
                        XM.send();

                    })
                })


                pagebutton.forEach(BTNNM => {
                    BTNNM.addEventListener("click",function () {
                        let pagevalue = this.value;


                        let HTTP = new XMLHttpRequest();

                        HTTP.onreadystatechange =function () {
                            if(this.status==200){
                                document.querySelector('.left').innerHTML = this.responseText;
                            }
                        }

                        HTTP.open('POST','pagination.php');
                        HTTP.setRequestHeader('Content-type','application/x-www-form-urlencoded');
                        HTTP.send("page="+pagevalue + '&theme=' + <?php echo $idtheme?>);
                    })
                })

                console.log(checked);

                // var ADD =document.querySelector('#ADDARTICLE').addEventListener('click', function() {
                //     window.open('ADD_ARTICLE.php?theme=');
                // })

            </script>
    </body>
    </html>



