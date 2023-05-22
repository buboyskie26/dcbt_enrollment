<?php 
    require_once('../includes/config.php');
    require_once('../includes/classes/StudentNavigationMenu.php');
    require_once('../includes/classes/PendingStudentNavigationMenu.php');
    
    require_once('../admin/classes/ButtonProvider.php');
    require_once('../includes/classes/Student.php');
    require_once('../enrollment/classes/Pending.php');
    // require_once('../admin/classes/AdminUser.php');
    require_once('../../dcbt/admin/classes/AdminUser.php');

    $studentLoggedIn = isset($_SESSION["username"]) 
        ? $_SESSION["username"] : "";
    
    $studentUserLoggedInObj = new Student($con, $studentLoggedIn);

    $qwe = $con;
    // echo $teacherLoggedIn;

?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student</title>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.1/css/all.min.css">

    <!-- <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous"> -->
    <!-- <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script> -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.0/jquery.min.js"></script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>

    <!-- <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script> 
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous"> -->

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>

    <!-- <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css"> -->
    
    <link rel="stylesheet" href="../assets/css/style.css">
    
	<script src="../assets/js/bootbox.min.js"></script>
    <script src="../assets/js/common.js"></script>

    <!-- include summernote css/js -->
    <link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote.min.js"></script>
    
    <link rel="stylesheet" href="../assets/css/TimeCircles.css">
    <script src="../assets/js/TimeCircles.js"></script>

    <link rel="stylesheet" href="../assets/css/bootstrap-datetimepicker.css">
    <script src="../assets/js/bootstrap-datetimepicker.js"></script>

  	<script src="https://cdn.jsdelivr.net/gh/guillaumepotier/Parsley.js@2.9.1/dist/parsley.js"></script>
    
    <link href='https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css' rel='stylesheet' type='text/css'>
    <!-- <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script> -->
    <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/limonte-sweetalert2/11.4.24/sweetalert2.all.js"></script>

</head>
<body>
    
    <div id="pageContainer">
        <div id="mastHeadContainer">

            <button class="navShowHide">
                <img src="../assets/images/icons/menu.png">
            </button>

            <a class="logoContainer" href="index.php">
                <!-- <img src="assets/images/icons/VideoTubeLogo.png" 
                title="logo" alt="Site logo"> -->
            </a>

            <div class="searchBarContainer">
                <form action="search.php" method="GET">
                    <input type="text" class="searchBar" 
                        name="term" placeholder="Search...">

                    <button class="searchButton">
                        <img src="../assets/images/icons/search.png">
                    </button>
                </form>
            </div>

            <div class="rightIcons">
                <a href="upload.php">
                    <img class="upload" src="../assets/images/icons/upload.png">
                </a>

                <?php
                    if(isset($_SESSION['status']) 
                        && $_SESSION['status'] == "enrolled"){
                        echo ButtonProvider::studentProfileNav($con, $studentLoggedIn);

                    }else{
                        echo ButtonProvider::studentPendingProfileNav($con, $studentLoggedIn);
                    }
                ?>
   
            </div>
        </div>

        <div id="sideNavContainer" style="display: block;">
            <?php
                $nav = new StudentNavigationMenu($con, $studentUserLoggedInObj);

                $pendingNav = new PendingStudentNavigationMenu($con, $studentLoggedIn);

                if(isset($_SESSION['status']) 
                     && $_SESSION['status'] == "pending"){

                    echo $pendingNav->create();

                }else{
                    echo $nav->create();
                }

            ?>
        </div>

        <div id="mainSectionContainer">
            <div id="mainContentContainer">
                
            