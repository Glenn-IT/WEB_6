

<?php 
foreach ($list as $key => $value) {

    $user_id = $_SESSION["user_type"] != 5 ?  1 : $_SESSION["user_id"];

    if($value["user_id"] != $user_id) {
        ?>
        <div class="d-flex flex-row justify-content-start">
            <div>
                <p class="small p-2 ms-3 mb-1 text-white rounded-3 bg-primary"><?=$value["description"]?></p>
                <p class="small ms-3 mb-3 rounded-3 text-muted float-end"><?=$value["time_ago"]?></p>
            </div>
        </div>
        <?php
    } else {
        ?>
        <div class="d-flex flex-row justify-content-end">
            <div>
                <p class="small p-2 me-3 mb-1 text-white rounded-3 bg-primary"><?=$value["description"]?></p>
                <p class="small me-3 mb-3 rounded-3 text-muted"><?=$value["time_ago"]?></p>
            </div>
        </div>
        <?php
    }

}
?>

<script>
    var $div = $('.getmessage_here');

    // Function to scroll to the bottom
    function scrollToBottom() {
        $div.scrollTop($div[0].scrollHeight);
    }

    // Call the function when the document is ready
    scrollToBottom();
</script>
