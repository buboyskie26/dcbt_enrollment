<?php 
    require_once('../../includes/config.php');
    require_once('../../admin/classes/RegistrarNavigationMenu.php');
    require_once('../../admin/classes/RegistrarEnrollmentNavigationMenu.php');
    require_once('../../admin/classes/ButtonProvider.php');
    require_once('../../admin/classes/AdminUser.php');
    require_once('../../admin/classes/Alert.php');
   
    $registrarLoggedIn = isset($_SESSION["registrarLoggedIn"]) 
        ? $_SESSION["registrarLoggedIn"] : "";

    $registrarLoggedIn = isset($_SESSION["adminLoggedIn"]) 
        ? $_SESSION["adminLoggedIn"] : "";
        
    $registrarLoggedInObj = new AdminUser($con, $registrarLoggedIn);

    // if (
    // !isset($_SESSION['registrarLoggedIn']) || $_SESSION['registrarLoggedIn'] == '' 
    // // && $_SESSION['registrarLoggedIn'] == ''
    // ) {
    //     header("location: /dcbt/registrarLogin.php");
    //     exit();
    // }

?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Enrollment DCBT (REGISTRAR)</title>
    
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.1/css/all.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>


    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>



    <!-- include summernote css/js -->
    <link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote.min.js"></script>
    
	<script src="../../assets/js/bootbox.min.js"></script>
    <script src="../../assets/js/common.js"></script>


    <link rel="stylesheet" href="../../assets/css/bootstrap-datetimepicker.css">
    <script src="../../assets/js/bootstrap-datetimepicker.js"></script>

  	<script src="https://cdn.jsdelivr.net/gh/guillaumepotier/Parsley.js@2.9.1/dist/parsley.js"></script>
    <link rel="stylesheet" href="//cdn.jsdelivr.net/npm/alertifyjs@1.13.1/build/css/alertify.min.css"/>
 
    <link href='https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css' rel='stylesheet' type='text/css'>
    <!-- <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script> -->
    <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/limonte-sweetalert2/11.4.24/sweetalert2.all.js"></script>

    <!--DCBT-2-->
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link
      href="https://fonts.googleapis.com/css2?family=Chakra+Petch:ital,wght@0,300;0,400;0,500;0,600;0,700;1,300;1,400;1,500;1,700&family=Lato:wght@100;300;700&display=swap"
      rel="stylesheet"
    />
    <link
      rel="stylesheet"
      href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css"
      integrity="sha384-xOolHFLEh07PJGoPkLv1IbcEPTNtaed2xpHsD9ESMhqIYd0nLMwNLD69Npy4HI+N"
      crossorigin="anonymous"
    />
    <link
      rel="stylesheet"
      href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css"
    />
    <script
      src="https://cdn.jsdelivr.net/npm/jquery@3.5.1/dist/jquery.slim.min.js"
      integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj"
      crossorigin="anonymous"
    ></script>
    <script
      src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"
      integrity="sha384-Fy6S3B9q64WdZWQUiU+q4/2Lc9npb8tCaSX9FK7E8HnRr0Jz8D6OP9dO5Vg3Q9ct"
      crossorigin="anonymous"
    ></script>
</head>
<body>

    <div class="sidebar">
      <div class="top">
        <div class="logo">
          <span>DCBT</span>
        </div>
        <i class="bi bi-list" id="tab-btn"></i>
      </div>
      <div class="user">
        <img src="/DCBT-2/img/DCBT-Logo.jpg" />
        <div>
          <p class="bold">User</p>
          <p>user@dcbt</p>
          <p>Registrar</p>
        </div>
      </div>
      <ul>
        <li>
          <a href="#">
            <i class="bi bi-clipboard-data"></i>
            <span class="nav-item">Dashboard</span>
          </a>
          <span class="tooltip">Dashboard</span>
        </li>
        <li>
          <a href="/DCBT-2/admission-registrar/Admission.html" class="active">
            <i class="bi bi-calendar4"></i>
            <span class="nav-item">Admission</span>
          </a>
          <span class="tooltip">Admission</span>
        </li>
        <li>
          <a href="#">
            <i class="bi bi-person"></i>
            <span class="nav-item">Students</span>
          </a>
          <span class="tooltip">Students</span>
        </li>
        <li>
          <a href="#">
            <i class="bi bi-person-plus-fill"></i>
            <span class="nav-item">Section</span>
          </a>
          <span class="tooltip">Section</span>
        </li>
        <li>
          <a href="#">
            <i class="bi bi-box-arrow-right"></i>
            <span class="nav-item">Log-out</span>
          </a>
          <span class="tooltip">Log-out</span>
        </li>
      </ul>
    </div>
    
    <div id="pageContainer">
        

        

        <div id="mainSectionContainer">
            <div id="mainContentContainer">
                