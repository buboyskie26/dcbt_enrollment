
<?php  
    require_once('../admin/adminHeader.php');

?>

<div class="column">
    <?php
        echo "This is the admin main section of ". $_SESSION['adminLoggedIn'];
    ?>
</div>

<?php  include('../includes/footer.php');?>