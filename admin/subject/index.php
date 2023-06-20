<?php 

    include('../admin_enrollment_header.php');
    $createUrl = directoryPath . "create.php";
    $templateUrl = directoryPath . "template.php";

    // echo "im in subject enroll";

   
    ?>
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="../../admin/assets/css/subject/index.css">
       
    </head>
    <?php
?>

 
<div class="row col-md-12">
    <div class="content">
        <div class="dashboard">
            <h5>Department</h3>

            <div class="form-box">
                <div class="button-box">
                <div id="btn"></div>
                <button type="button" class="toggle-btn" >
                    SHS
                </button>
                <button type="button" class="toggle-btn">
                    Tertiary
                </button>
                </div>
            </div>
        </div>
    
    </div>


    
    <div class="container">
        <h3>Menu</h3>
        <div class="container-subjects">
            <div class="subject_container">
                <p>View Subjects</p>

                <a style="  all: initial;" href="list.php">
                    <i class="fas fa-arrow-circle-right"></i>
                </a>

            </div>
            <div class="subject_container">
                <p>View Strand Subjects</p>
                <a href="strand.php">
                    <i class="fas fa-arrow-circle-right"></i>
                </a>
            </div>
        </div>
    </div>

</div>

