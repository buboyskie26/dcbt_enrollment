
<?php 

    include('../admin_enrollment_header.php');
    include('../../enrollment/classes/StudentEnroll.php');
    include('../../enrollment/classes/Schedule.php');
    require_once('../../admin/classes/AdminUser.php');

    ?>
    <head>
        <!-- <link rel="stylesheet" href="teachers.css"> -->
        <link rel="stylesheet" href="../../admin/assets/css/teacher/index.css">
    </head>

    <?php

    $createUrl = base_url . "/create.php";

    // echo "im in subject enroll";
    $enroll = new StudentEnroll($con);

    $schedule = new Schedule($con, $enroll);
    $school_year_obj = $enroll->GetLatestSchoolYearAndSemester();

	$currentYear = $school_year_obj[0];
	$current_semester = $school_year_obj[1];
	$school_year_id = $school_year_obj[2];

    if(!AdminUser::IsAuthenticated()){
        header("Location: /dcbt/adminLoggedIn.php");
        exit();
    }
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


        <div class="content">
            
            <div class="choices">

                <div class="active" id="teacher-list-div">
                    <a href="index.php" id="teacher-list-a">Teacher List

                    </a>
                </div>
                <div class="none_active" id="subject-load-div">
                    <a href="subject_load.php" id="subject-load-a">Subject Load
                    </a>
                </div>

            </div>

            <main class="table">

                <section class="table__header">
                    <h1>Teacher</h1>
                    
                    <div class="input-group">
                        <!-- <input type="search" placeholder="Search for student...">
                        <img src="images/search.png" alt=""> -->
                        <button class='button-style-success success'>
                            <i class="fas fa-plus-circle"></i>  Add New
                        </button>
                    </div>
                    <!-- <div class="export__file">
                        <label for="export-file" class="export__file-btn" title="Export File"></label>
                        <input type="checkbox" id="export-file">
                        <div class="export__file-options">
                            <label>Export As &nbsp; &#10140;</label>
                            <label for="export-file" id="toPDF">PDF <img src="images/pdf.png" alt=""></label>
                            <label for="export-file" id="toJSON">JSON <img src="images/json.png" alt=""></label>
                            <label for="export-file" id="toCSV">CSV <img src="images/csv.png" alt=""></label>
                            <label for="export-file" id="toEXCEL">EXCEL <img src="images/excel.png" alt=""></label>
                        </div>
                    </div> -->
                </section>

                <section class="table__body">
                    <table>
                        <thead>
                            <tr>
                                <th> Id <span class="icon-arrow">&UpArrow;</span></th>
                                <th> Name. <span class="icon-arrow">&UpArrow;</span></th>
                                <th> Subject Load <span class="icon-arrow">&UpArrow;</span></th>
                                <th> Status <span class="icon-arrow">&UpArrow;</span></th>
                                <th> Date Added <span class="icon-arrow">&UpArrow;</span></th>
                                <th> Action <span class="icon-arrow">&UpArrow;</span></th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </section>
            </main>

        </div>
    </div>

<script>

   function confirmAsReturneeBtn(username) {

    $.ajax({
        url: '../ajax/enrollee/markAsReturned.php', // replace with your PHP script URL
        type: 'POST',
        data: {
            // add any data you want to send to the server here
            username
        },
        success: function(response) {
            // console.log(response);
            alert(response)
            location.reload();
        },
        error: function(xhr, status, error) {
        }
    });
}
</script>