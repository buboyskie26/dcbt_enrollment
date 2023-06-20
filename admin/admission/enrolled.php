<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- <style>
        .selection-btn {
        display: flex;
        flex-direction: row;
        justify-content: center;
        align-items: center;
        padding: 5px 10px;
        gap: 10px;
                width: 180px;

        height: 54px;
        background: #EFEFEF;
        border: none;
        font-style: normal;
        font-weight: 400;
        font-size: 16px;
        margin-bottom: 45px;;
        }
    </style> -->
    
</head>

<?php 

    include('../registrar_enrollment_header.php');
    include('../../enrollment/classes/StudentEnroll.php');
    include('../../enrollment/classes/OldEnrollees.php');
    include('../../enrollment/classes/Enrollment.php');

    ?>
    <head>
        <link rel="stylesheet" href="../../admin/assets/css/admission/evaluation.css">
    </head>
    <?php

    $createUrl = base_url . "/create.php";
    $manualCreateUrl = base_url . "/manual_create.php";

    $enroll = new StudentEnroll($con);
    $old_enrollee = new OldEnrollees($con, $enroll);
    $enrollment = new Enrollment($con, $enroll);
    
    // echo "im in subject enroll";

    $school_year_obj = $enroll->GetActiveSchoolYearAndSemester();

    $current_school_year_id = $school_year_obj['school_year_id'];
    $current_school_year_term = $school_year_obj['term'];
    $current_school_year_period = $school_year_obj['period'];


    $enroll = new StudentEnroll($con);
    $enrollment = new Enrollment($con, $enroll);
    
    $waitingApprovalEnrollment = $enrollment->WaitingApprovalEnrollment($current_school_year_id);
    $unionEnrollment = $enrollment->UnionEnrollment();


    $enrollment = new Enrollment($con, null);
    $pendingEnrollment = $enrollment->PendingEnrollment();
    $waitingPaymentEnrollment = $enrollment->WaitingPaymentEnrollment($current_school_year_id);
    $waitingApprovalEnrollment = $enrollment->WaitingApprovalEnrollment($current_school_year_id);
    $enrolledStudentsEnrollment = $enrollment->EnrolledStudentsWithinSYSemester($current_school_year_id);

    $unionEnrollmentCount = count($unionEnrollment);
    $pendingEnrollmentCount = count($pendingEnrollment);
    $waitingPaymentEnrollmentCount = count($waitingPaymentEnrollment);
    $waitingApprovalEnrollmentCount = count($waitingApprovalEnrollment);
    $enrolledStudentsEnrollmentCount = count($enrolledStudentsEnrollment);


?>

    <div class="row col-md-12">


        <div class="content">
            <div class="back-menu">
                <button type="button" class="admission-btn" onclick="admission()">
                    <i class="fas fa-arrow-left"></i> Admission
                </button>
            </div>
            
            <div style="color: #fff;" class="head">
                <h3 class="mt-2">Enrollment form finder (SHS)</h3>
                <p>Note: Numbers on tabs only count current school year and semester</p>
                
                <div class="button-container">
                    <div class="evaluation">
                        <a href="evaluation.php">
                            <button type="button" class="selection-btn" id="evaluation" onclick="evaluation_btn()" style="background: rgb(2,0,20); color: white;">
                                Evaluation (<?php echo $unionEnrollmentCount;?>)
                            </button>
                        </a>

                    </div>
                    <div class="waiting-payment">
                        <a href="waiting_payment.php">
                            <button type="button" class="selection-btn" id="waiting-payment" onclick="waiting_payment_btn()" style="background: rgb(2, 0, 28); color: white;">
                                Waiting payment (<?php echo $waitingPaymentEnrollmentCount;?>)
                            </button>
                        </a>

                    </div>
                    <div class="waiting-approval">
                        <a href="waiting_approval.php">
                            <button type="button" class="selection-btn" id="waiting-approval" onclick="waiting_approval_btn()" style="background: rgb(2, 0, 28); color: white;">
                                Waiting approval (<?php echo $waitingApprovalEnrollmentCount;?>)
                            </button>
                        </a>
                    </div>
                    <div class="enrolled">
                        <a href="enrolled.php">
                            <button type="button" class="selection-btn" id="enrolled" onclick="enrolled_btn()" style="background: rgb(239, 239, 239); color: black;">
                                Enrolled (<?php echo $enrolledStudentsEnrollmentCount;?>)
                            </button>
                        </a>
                    </div>
                </div>
            </div>
            
            <main class="table">

                <section class="table__header">
                    <h1 >Form Details</h1>
                    <div class="input-group">
                        <input type="search" placeholder="Search for student...">
                        <img src="images/search.png" alt="">
                    </div>
                </section>

                <section class="table__body">
                    <table>
                        <thead>
                            <tr>
                                <th> Name <span class="icon-arrow">&UpArrow;</span></th>
                                <th> Student No. <span class="icon-arrow">&UpArrow;</span></th>
                                <th> Type <span class="icon-arrow">&UpArrow;</span></th>
                                <th> Strand <span class="icon-arrow">&UpArrow;</span></th>
                                <th> Date Submitted <span class="icon-arrow">&UpArrow;</span></th>
                                <th> Action <span class="icon-arrow">&UpArrow;</span></th>
                            </tr>
                        </thead>
            <tbody>
                <?php 
                    $active = 1;

                    $sql = $con->prepare("SELECT 
                        t1.*, t2.program_section, t2.course_id

                    FROM student as t1

                    LEFT JOIN course as t2 ON t2.course_id = t1.course_id

                    WHERE t1.active =:active
                    AND t1.is_tertiary !=:is_tertiary
                    ORDER BY t1.course_level DESC
                    ");

                    $sql->bindValue(":active", $active);
                    $sql->bindValue(":is_tertiary", 1);
                    $sql->execute();

                    if($sql->rowCount() > 0){

                    
                        // while($row = $sql->fetch(PDO::FETCH_ASSOC)){

                        //     $fullName = $row['firstname']." ". $row['lastname']; 
                        //     $student_id = $row['student_id'];
                        //     $course_level = $row['course_level'];
                        //     $course_id = $row['course_id'];
                        //     $status = $row['student_status'];
                        //     $program_section = $row['program_section'];

                        //     $gradeUrl = "http://localhost/dcbt/admin/enrollees/student_grade_report.php?id=$student_id";

                        //     $view_url = directoryPath . "../student/view_details.php?profile=show&id=$student_id";

                        //     $trans_url = directoryPath . "../student/shs_view_transferee_details.php?profile=show&id=$student_id";

                        //     $section_url = "http://localhost/dcbt/admin/section/strand_show.php?id=$course_id";


                        //     $view_btn = "
                        //         <a href='$view_url'>
                        //             <button class='btn btn-secondary btn-sm'>
                        //                 View Details
                        //             </button>
                        //         </a>
                        //     ";

                        //     if($status == "Transferee"){

                        //         $view_btn = "
                        //             <a href='$trans_url'>
                        //                 <button class='btn btn-outline-secondary btn-sm'>
                        //                     View Details
                        //                 </button>
                        //             </a>
                        //         ";
                        //     }

                        //     echo '<tr class="text-center">'; 
                        //             echo '<td>'.$student_id.'</td>';
                        //             echo '<td>
                        //                 <a style= "color: whitesmoke;" href="edit.php?id='.$student_id.'">
                        //                     '.$fullName.'
                        //                 </a>
                        //             </td>';
                        //             echo '<td>'.$status.'</td>';
                        //             echo '<td>'.$course_level.'</td>';
                        //             echo '<td>
                        //                 <a href="'.$section_url.'">
                        //                     '.$program_section.'</td>
                        //                 </a>
                        //             ';
                        //             echo '
                        //                 <td> 
                        //                     <a href="'.$gradeUrl.'">
                        //                         <button class="btn btn-primary btn-sm">Check Grade</button>
                        //                     </a>
                        //                     '.$view_btn.'
                        //                 </td>
                        //             ';
                        //     echo '</tr>';
                        // }
                    }


                    if(count($enrolledStudentsEnrollment) > 0){

                        foreach ($enrolledStudentsEnrollment as $key => $row) {
                            
                            $fullName = $row['firstname']. " ". $row['lastname']; 
                            $student_id = $row['student_id'];
                            $student_unique_id = $row['student_unique_id'];
                            $course_level = $row['course_level'];
                            $firstname = $row['firstname'];
                            $course_id = $row['course_id'];
                            $status = $row['student_status'];
                            $student_unique_id = $row['student_unique_id'];
                            $program_section = $row['program_section'];

                            $gradeUrl = "http://localhost/dcbt/admin/enrollees/student_grade_report.php?id=$student_id";

                            $view_url = directoryPath . "../student/view_details.php?profile=show&id=$student_id";

                            $trans_url = directoryPath . "../student/shs_view_transferee_details.php?profile=show&id=$student_id";

                            // $section_url = "http://localhost/dcbt/admin/section/strand_show.php?id=$course_id";
                            $section_url = "../section/section_show.php?id=$course_id";

                            $view_btn = "
                                <a href='$view_url'>
                                    <button class='button-style-primary primary'>
                                        View
                                    </button>
                                </a>
                            ";

                            if($status == "Transferee"){

                                $view_btn = "
                                    <a href='$view_url'>
                                        <button class='btn btn-outline-secondary btn-sm'>
                                            View Details
                                        </button>
                                    </a>
                                ";
                            }

                            echo '<tr class="text-center">'; 
                                    echo '<td>
                                        <a  href="edit.php?id='.$student_id.'">
                                            '.$fullName.'
                                        </a>
                                    </td>';
                                    echo '<td>'.$student_unique_id.'</td>';

                                    echo '<td>'.$status.'</td>';
                                    echo '<td>'.$course_level.'</td>';
                                    echo '<td>
                                        <a href="'.$section_url.'">
                                            '.$program_section.'</td>
                                        </a>
                                    ';
                                    echo '
                                        <td> 
                                            '.$view_btn.'
                                        </td>
                                    ';
                            echo '</tr>';
                        }
                    }else{
                        echo "
                            <div class='col-md-12'>
                                <h4 class='text-info text-center'>No Enrolled in this Semester.</h4>
                            </div>
                        ";
                    }

                ?>
            </tbody>
                    </table>
                </section>
            </main>
        </div>
        

        <hr>
        <hr>

        <div style="display: flex;
            flex-direction: row;
            flex-wrap: wrap;
            justify-content: center;
            align-items: center;
            padding: 20px 20px 0px;
            gap: 1px;
            width: 100%;
            height: 74px;
            background: #02001C;
            margin-bottom: 15px;
            "
            class="">
            <div class="evaluation">
                <a href="evaluation.php">
                    <button type="button" class="selection-btn" id="evaluation" onclick="evaluation_btn()" style="background: rgb(0,2, 20); color: white;">
                        Evaluation (<?php echo $pendingEnrollmentCount;?>)
                    </button>
                </a>

            </div>
            <div class="waiting-payment">
                <a href="waiting_payment.php">
                    <button type="button" class="selection-btn" id="waiting-payment" onclick="waiting_payment_btn()" style="background: rgb(0,2,20); color: white;">
                        Waiting payment (<?php echo $waitingPaymentEnrollmentCount;?>)
                    </button>
                </a>

            </div>
            <div class="waiting-approval">
                <a href="waiting_approval.php">
                    <button type="button" class="selection-btn" id="waiting-approval" onclick="waiting_approval_btn()" style="background: rgb(2, 0, 28); color: white;">
                        Waiting approval (<?php echo $waitingApprovalEnrollmentCount;?>)
                    </button>
                </a>
            </div>
            <div class="enrolled">
                <a href="enrolled.php">
                    <button type="button" class="selection-btn" id="enrolled" onclick="enrolled_btn()" style="background: rgb(239,239,239); color: black;">
                        Enrolled (<?php echo $enrolledStudentsEnrollmentCount;?>)
                    </button>
                </a>
            </div>
        </div>

        <div style="display: none;" class="row">
            <div class="col-md-3">
                <a href="evaluation.php">
                    <button class="btn btn btn-outline-primary">Evaluation 
                        <span class="text-white">(<?php echo $pendingEnrollmentCount;?>)</span></button>
                    
                </a>
            </div>
            <div class="col-md-3">
                <a href="waiting_payment.php">
                <button class="btn btn  btn-outline-primary">Waiting Payment <span class="text-white">
                    (<?php echo $waitingPaymentEnrollmentCount;?>)</span></button>

                </a>
            </div>
            <div class="col-md-3">
                <a href="waiting_approval.php">
                    <button class="btn btn  btn-outline-primary">Waiting Approval <span class="text-white">(<?php echo $waitingApprovalEnrollmentCount;?>)</span></button>
                </a>
            </div>
            
            <div class="col-md-3">
                <a href="enrolled.php">
                <button class="btn btn  btn-primary">Enrolled <span class="text-white">(<?php echo $enrolledStudentsEnrollmentCount;?>)</span></button>
                </a>
            </div>

            <hr>

        </div>

        <div class="container mb-4">
            <h2 class="text-center text-success">Enrolled Student</h2>
            
        </div>
        <table  class="table table-striped table-bordered table-hover "  style="font-size:13px" cellspacing="0"  > 
            <thead>
                <tr class="text-center"> 
                    <th rowspan="2">Id</th>
                    <th rowspan="2">Name</th>
                    <th rowspan="2">Status</th>
                    <th rowspan="2">Grade Level</th>
                    <th rowspan="2">Section</th>
                    <th rowspan="2">Action</th>
                </tr>	
            </thead> 	 
            <tbody>
                <?php 
                    $active = 1;

                    $sql = $con->prepare("SELECT 
                        t1.*, t2.program_section, t2.course_id

                    FROM student as t1

                    LEFT JOIN course as t2 ON t2.course_id = t1.course_id

                    WHERE t1.active =:active
                    AND t1.is_tertiary !=:is_tertiary
                    ORDER BY t1.course_level DESC
                    ");

                    $sql->bindValue(":active", $active);
                    $sql->bindValue(":is_tertiary", 1);
                    $sql->execute();

                    if($sql->rowCount() > 0){

                    
                        // while($row = $sql->fetch(PDO::FETCH_ASSOC)){

                        //     $fullName = $row['firstname']." ". $row['lastname']; 
                        //     $student_id = $row['student_id'];
                        //     $course_level = $row['course_level'];
                        //     $course_id = $row['course_id'];
                        //     $status = $row['student_status'];
                        //     $program_section = $row['program_section'];

                        //     $gradeUrl = "http://localhost/dcbt/admin/enrollees/student_grade_report.php?id=$student_id";

                        //     $view_url = directoryPath . "../student/view_details.php?profile=show&id=$student_id";

                        //     $trans_url = directoryPath . "../student/shs_view_transferee_details.php?profile=show&id=$student_id";

                        //     $section_url = "http://localhost/dcbt/admin/section/strand_show.php?id=$course_id";


                        //     $view_btn = "
                        //         <a href='$view_url'>
                        //             <button class='btn btn-secondary btn-sm'>
                        //                 View Details
                        //             </button>
                        //         </a>
                        //     ";

                        //     if($status == "Transferee"){

                        //         $view_btn = "
                        //             <a href='$trans_url'>
                        //                 <button class='btn btn-outline-secondary btn-sm'>
                        //                     View Details
                        //                 </button>
                        //             </a>
                        //         ";
                        //     }

                        //     echo '<tr class="text-center">'; 
                        //             echo '<td>'.$student_id.'</td>';
                        //             echo '<td>
                        //                 <a  href="edit.php?id='.$student_id.'">
                        //                     '.$fullName.'
                        //                 </a>
                        //             </td>';
                        //             echo '<td>'.$status.'</td>';
                        //             echo '<td>'.$course_level.'</td>';
                        //             echo '<td>
                        //                 <a href="'.$section_url.'">
                        //                     '.$program_section.'</td>
                        //                 </a>
                        //             ';
                        //             echo '
                        //                 <td> 
                        //                     <a href="'.$gradeUrl.'">
                        //                         <button class="btn btn-primary btn-sm">Check Grade</button>
                        //                     </a>
                        //                     '.$view_btn.'
                        //                 </td>
                        //             ';
                        //     echo '</tr>';
                        // }
                    }


                    if(count($enrolledStudentsEnrollment) > 0){

                        foreach ($enrolledStudentsEnrollment as $key => $row) {
                            
                            $fullName = $row['firstname']." ". $row['lastname']; 
                            $student_id = $row['student_id'];
                            $student_unique_id = $row['student_unique_id'];
                            $course_level = $row['course_level'];
                            $course_id = $row['course_id'];
                            $status = $row['student_status'];
                            $program_section = $row['program_section'];

                            $gradeUrl = "http://localhost/dcbt/admin/enrollees/student_grade_report.php?id=$student_id";

                            $view_url = directoryPath . "../student/view_details.php?profile=show&id=$student_id";

                            $trans_url = directoryPath . "../student/shs_view_transferee_details.php?profile=show&id=$student_id";

                            // $section_url = "http://localhost/dcbt/admin/section/strand_show.php?id=$course_id";
                            $section_url = "../section/section_show.php?id=$course_id";

                            $view_btn = "
                                <a href='$view_url'>
                                    <button class='btn btn-secondary btn-sm'>
                                        View
                                    </button>
                                </a>
                            ";

                            if($status == "Transferee"){

                                $view_btn = "
                                    <a href='$view_url'>
                                        <button class='btn btn-outline-secondary btn-sm'>
                                            View Details
                                        </button>
                                    </a>
                                ";
                            }

                            echo '<tr class="text-center">'; 
                                    echo '<td>'.$student_unique_id.'</td>';
                                    echo '<td>
                                        <a href="edit.php?id='.$student_id.'">
                                            '.$fullName.'
                                        </a>
                                    </td>';
                                    echo '<td>'.$status.'</td>';
                                    echo '<td>'.$course_level.'</td>';
                                    echo '<td>
                                        <a href="'.$section_url.'">
                                            '.$program_section.'</td>
                                        </a>
                                    ';
                                    echo '
                                        <td> 
                                            '.$view_btn.'
                                        </td>
                                    ';
                            echo '</tr>';
                        }
                    }else{
                        echo "
                            <div class='col-md-12'>
                                <h4 class='text-info'>No Enrolled in this Semester.</h4>
                            </div>
                        ";
                    }

                ?>
            </tbody>
        </table>

        <div>
            <button class="btn btn-primary">Hello</button>
        </div>
        <hr>
        <hr>

        <?php
        
            $recordsPerPageOptions = [5,10,15]; // Define the available options

            // Get the selected records per page from the URL parameter 'per_page'
            $selectedRecordsPerPage = isset($_GET['per_page']) ? $_GET['per_page'] : $recordsPerPageOptions[0]; // Set default value to the first option


            // Generate the dropdown options
            $recordsPerPageDropdown = '<select class="form-control" name="per_page" onchange="this.form.submit()">';

            foreach ($recordsPerPageOptions as $option) {

                $recordsPerPageDropdown .= '<option value="' . $option . '"';
                if ($option == $selectedRecordsPerPage) {
                    $recordsPerPageDropdown .= ' selected';
                }

                $recordsPerPageDropdown .= '>' . $option . ' per page</option>';
            }

            $recordsPerPageDropdown .= '</select>';

            // Set the number of results per page
            $resultsPerPage = $selectedRecordsPerPage;

            // Get the current page number from the URL parameter 'page'
            $currentPage = isset($_GET['page']) ? $_GET['page'] : 1;

            // Calculate the offset
            $offset = ($currentPage - 1) * $resultsPerPage;

            // Query to retrieve the data with pagination
            $sql = $con->prepare(" SELECT *  FROM subject
                ORDER BY subject_id DESC
                LIMIT :offset, :resultsPerPage
            ");
            $sql->bindValue(":offset", $offset, PDO::PARAM_INT);
            $sql->bindValue(":resultsPerPage", $resultsPerPage, PDO::PARAM_INT);
            $sql->execute();

            ?>

            <!-- Display the records per page dropdown -->
            <div class="text-right mb-3">
                <form method="GET" class="form-inline">
                    <label for="per_page">Records per page:</label>
                    <?php echo $recordsPerPageDropdown; ?>
                </form>
            </div>

            <table class="table table-striped table-bordered table-hover" style="font-size:13px" cellspacing="0">
                <thead>
                    <tr class="text-center">
                        <th rowspan="2">Id</th>
                        <th rowspan="2">Title</th>
                    </tr>
                </thead>
                <tbody>
                    <?php

                    $sql = $con->prepare(" SELECT *  FROM subject
                        ORDER BY subject_id DESC
                        LIMIT :offset, :resultsPerPage
                    ");
                    $sql->bindValue(":offset", $offset, PDO::PARAM_INT);
                    $sql->bindValue(":resultsPerPage", $resultsPerPage, PDO::PARAM_INT);
                    $sql->execute();
                    if ($sql->rowCount() > 0) {
                        while ($row = $sql->fetch(PDO::FETCH_ASSOC)) {
                            $subject_id = $row['subject_id'];
                            $subject_title = $row['subject_title'];

                            echo '<tr class="text-center">';
                            echo '<td>' . $subject_id . '</td>';
                            echo '<td>' . $subject_title . '</td>';
                            echo '</tr>';
                        }
                    }
                    ?>
                </tbody>
            </table>

            <?php
                $totalResults = $con->query("SELECT COUNT(*) FROM subject")->fetchColumn();

                $totalPages = ceil($totalResults / $resultsPerPage);

                $pagination = '<ul class="pagination">';
                for ($i = 1; $i <= $totalPages; $i++) {
                    $pagination .= '<li class="page-item';
                    if ($i == $currentPage) {
                        $pagination .= ' active';
                    }
                    $pagination .= '"><a class="page-link" href="?page=' . $i . '&per_page=' . $selectedRecordsPerPage . '">' . $i . '</a></li>';
                }
                $pagination .= '</ul>';

                // Calculate the range of displayed entries
                $startEntry = ($currentPage - 1) * $resultsPerPage + 1;
                $endEntry = min($startEntry + $resultsPerPage - 1, $totalResults);

                // Generate the "Showing X to Y of Z entries" message
                $showingEntries = "Showing $startEntry to $endEntry of $totalResults entries";
            ?>

            <!-- Display the "Showing X to Y of Z entries" message -->
            <div class="text-center"><?php echo $showingEntries; ?></div>

            <!-- Display the pagination links -->
            <div class="text-center"><?php echo $pagination; ?></div>


    </div>





