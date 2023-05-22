<?php 

    require_once('../includes/config.php');
    // require_once('./classes/HomePageEnroll.php');

?>
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DCBT Enrollment Home Page</title>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.1/css/all.min.css">
    
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.0/jquery.min.js"></script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>

    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>

    <link rel="stylesheet" href="../assets/css/style.css">
    
    <script src="../assets/js/common.js"></script>

    
</head>

<body>

    <div class="row">

        <div class="col-lg-10 offset-md-1">
                
            <button class='btn btn-success'>Btn</button>

            <!-- <ul class="nav">
                <li class="active"><a href="#New" data-toggle="tab">New</a></li> 
                <li><a href="#Old" data-toggle="tab">Old</a></li>
                <li><a href="#Transferees" data-toggle="tab">Transferees</a></li>
            </ul> -->


            <!-- <?php
                // $createPage = new HomePageEnroll($con);
                // echo $createPage;
            ?> -->

            <ul class="nav">
                <li class="nav-item">
                    <a class="nav-link active" aria-current="page" href="#New">New</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" data-toggle="tab" href="#Old">Old</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#">Transferees</a>
                </li>
            </ul>

            <div class="tab-content"><br/>
                <div class="tab-pane active" id="New">

                <?php include "regular_form.php"; ?> 
            </div>

            <div class="tab-pane" id="Old"><br/>
                <h1>ISDOP</h1>
            </div>

            <div>
                <?php include "login_enrollment.php"; ?> 
               
            </div>

        </div>
    </div>


</body>