
<?php 

if(count($list) > 0 ) {
    foreach ($list as $key => $value) {
        ?>
        <div class="card mb-4" >
            <div class="card-body">
                <h5 class="card-title"><?=$value["title"]?></h5>
                <h6 class="card-subtitle mb-2 text-muted"><?=$value["created_at"]?></h6>
                <p class="card-text"><?=$value["message"]?></p>
            </div>
        </div>

        <?php
    }
}

?>