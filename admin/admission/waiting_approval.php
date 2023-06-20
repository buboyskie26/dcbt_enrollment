<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
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
        }
    </style>
</head>

<?php  
 
    include('../../enrollment/classes/StudentEnroll.php');
    include('../../enrollment/classes/Enrollment.php');

    include('../registrar_enrollment_header.php');
    ?>
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <!-- <link rel="stylesheet" href="./admission/evaluation.css"> -->
            <link rel="stylesheet" href="../../admin/assets/css/admission/evaluation.css">

        </head>
    <?php
  
    $enroll = new StudentEnroll($con);

    $school_year_obj = $enroll->GetActiveSchoolYearAndSemester();

    $current_school_year_id = $school_year_obj['school_year_id'];
    $current_school_year_term = $school_year_obj['term'];
    $current_school_year_period = $school_year_obj['period'];

    $enrollment = new Enrollment($con, $enroll);
    
    $waitingApprovalEnrollment = $enrollment->WaitingApprovalEnrollment($current_school_year_id);


    $enrollment = new Enrollment($con, null);
    $pendingEnrollment = $enrollment->PendingEnrollment();
    $waitingPaymentEnrollment = $enrollment->WaitingPaymentEnrollment($current_school_year_id);
    $waitingApprovalEnrollment = $enrollment->WaitingApprovalEnrollment($current_school_year_id);
    $enrolledStudentsEnrollment = $enrollment->EnrolledStudentsWithinSYSemester($current_school_year_id);
    $unionEnrollment = $enrollment->UnionEnrollment();


    $pendingEnrollmentCount = count($pendingEnrollment);
    $unionEnrollmentCount = count($unionEnrollment);
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
                            <button type="button" class="selection-btn" id="evaluation" onclick="evaluation_btn()" style="background: rgb(20,0,20); color: white;">
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
                            <button type="button" class="selection-btn" id="waiting-approval" onclick="waiting_approval_btn()" style="background: rgb(239, 239, 239); color: black;">
                                Waiting approval (<?php echo $waitingApprovalEnrollmentCount;?>)
                            </button>
                        </a>
                    </div>
                    <div class="enrolled">
                        <a href="enrolled.php">
                            <button type="button" class="selection-btn" id="enrolled" onclick="enrolled_btn()" style="background: rgb(2, 0, 28); color: white;">
                                Enrolled (<?php echo $enrolledStudentsEnrollmentCount;?>)
                            </button>
                        </a>
                    </div>
                </div>
            </div>
            
            <main class="table">

                <section class="table__header">
                    <h1  >Form Details</h1>
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
                                <th> Section <span class="icon-arrow">&UpArrow;</span></th>
                                <th> Date Submitted <span class="icon-arrow">&UpArrow;</span></th>
                                <th> Action <span class="icon-arrow">&UpArrow;</span></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php

                                foreach ($waitingApprovalEnrollment as $key => $row) {

                                    $enrollement_student_id = $row['student_id'];
                                    $fullname = $row['firstname'] . " " . $row['lastname'];
                                    $standing = $row['course_level'];
                                    $student_unique_id = $row['student_unique_id'];
                                    
                                    $course_id = $row['course_id'];
                                    $username = $row['username'];
                                    $student_id = $row['t2_student_id'];
                                    $program_section = $row['program_section'];
                                    $cashier_evaluated = $row['cashier_evaluated'];
                                    $registrar_evaluated = $row['registrar_evaluated'];
                                    $course_level = $row['course_level'];
                                    $student_status = $row['student_status'];
                                    $new_enrollee = $row['new_enrollee'];
                                    $is_tertiary = $row['is_tertiary'];
                                    $is_transferee = $row['is_transferee'];
                                    $enrollment_approve = $row['enrollment_approve'];
                                    
                                    $admission_status = $row['admission_status'];
                                    $student_statusv2 = $row['student_statusv2'];


                                    // $program_section_default = "";
                                    if($program_section === ""){
                                        $program_section = "NO SECTION";
                                    }

                                    // $course_level_default = "";
                                    if($course_level == ""){
                                        $course_level = "NO SECTION";
                                    }else{
                                        $course_level = "Grade $course_level";
                                    }
                                    
                                    $createUrl = "http://localhost/dcbt/admin/student/edit.php?id=$student_id";

                                    $transferee_insertion_url = "../student/transferee_insertion.php?enrolled_subjects=true&id=$student_id";

                                    $regular_insertion_url = "../enrollees/subject_insertion.php?enrolled_subjects=true&id=$student_id";

                                    
                                    $confirmButton  = "
                                            <button onclick='confirmValidation(" . $course_id . ", " . $enrollement_student_id . ")' name='confirm_validation_btn' class='btn btn-primary btn-sm'>Confirm</button>
                                    ";

                                    $evaluateBtn = "";

                                    $student_type_status = "";
                                    
                                    if($cashier_evaluated == "yes"
                                        && $registrar_evaluated == "yes"){

                                        if($student_statusv2 == "Regular"){
                                            $evaluateBtn = "
                                                <a href='$regular_insertion_url'>
                                                    <button class='button-style-success success'>
                                                        Approve
                                                    </button>
                                                </a>
                                            ";

                                            if($new_enrollee == 1 && $is_tertiary == 0){
                                                $student_type_status = "New Regular (SHS)";

                                            }else if($new_enrollee == 0 && $is_tertiary == 0){
                                                $student_type_status = "On Going SHS";

                                            }
                                            else if($new_enrollee == 0 && $is_tertiary == 1){
                                                $student_type_status = "O.S Tertiary (Regular)";
                                            }
                                           
                                        }else if($student_statusv2 == "Irregular"){

                                            if($new_enrollee == 1 && $is_tertiary == 1){
                                                $student_type_status = "New Tertiary (Irregular)";
                                            }
                                            else if($new_enrollee == 0 && $is_tertiary == 1){
                                                $student_type_status = "O.S Tertiary (Irregular)";
                                            }

                                            $evaluateBtn = "
                                                <a href='$transferee_insertion_url'>
                                                    <button class='button-style-success success'>
                                                        Evaluate
                                                    </button>
                                                </a>
                                            ";
                                        }

                                        # if Transferee
                                        if($student_statusv2 == "Transferee"){

                                                // if($new_enrollee == 0 || $new_enrollee == 1){
                                            // if($new_enrollee == 1 && $is_tertiary == 0 && $is_transferee == 1){

                                            //     $student_type_status = "New Transferee (SHS)";

                                            //     $evaluateBtn = "
                                            //         <a href='$transferee_insertion_url'>
                                            //             <button class='btn btn-outline-success btn-sm'>
                                            //                 Evaluate
                                            //             </button>
                                            //         </a>
                                            //     ";

                                            // }
                                            // if($admission_status == "Standard" 
                                            //     && $student_statusv2 == "Regular"){

                                            //     $student_type_status = "Standard Regular";

                                            //     $evaluateBtn = "
                                            //         <a href='$transferee_insertion_url'>
                                            //             <button class='btn btn-outline-success btn-sm'>
                                            //                 Evaluate
                                            //             </button>
                                            //         </a>
                                            //     ";

                                            // }
                                            // else if($admission_status == "Transferee" 
                                            //     && $student_statusv2 == "Regular"){

                                            //     // $student_type_status = "On Going Transferee (SHS)";
                                                
                                            //     $student_type_status = "O.S $admission_status (SHS $student_statusv2)";

                                               
                                            //     # PREVIOUS URL
                                            //     $trans_url = "transferee_process_enrollment.php?step3=true&st_id=$student_id&selected_course_id=$course_id";
                                            //     $regular_insertion_url = "../enrollees/subject_insertion.php?username=$username&id=$student_id";

                                            //     $evaluateBtn = "
                                            //         <a href='$regular_insertion_url'>
                                            //             <button class='btn btn-outline-success btn-sm'>
                                            //                 Evaluate
                                            //             </button>
                                            //         </a>
                                            //     ";

                                            // }


                                        }
                                    }


                                    echo "
                                        <tr class='text-center'>
                                            <td>$fullname</td>
                                            <td>$student_unique_id</td>
                                            <td>$student_type_status</td>
                                            <td>$program_section</td>
                                            <td>$enrollment_approve </td>
                                            <td>
                                                $evaluateBtn
                                            </td>
                                        </tr>
                                    ";

                                }
                            
                                
                            ?>
                        </tbody>
                    </table>
                </section>
            </main>

        </div>


        <div style="display: none;">
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
                    <button type="button" class="selection-btn" id="waiting-approval" onclick="waiting_approval_btn()" style="background: rgb(239,239,239); color: black;">
                        Waiting approval (<?php echo $waitingApprovalEnrollmentCount;?>)
                    </button>
                </a>
            </div>
            <div class="enrolled">
                <a href="enrolled.php">
                    <button type="button" class="selection-btn" id="enrolled" onclick="enrolled_btn()" style="background: rgb(2, 0, 28); color: white;">
                        Enrolled (<?php echo $enrolledStudentsEnrollmentCount;?>)
                    </button>
                </a>
            </div>
        </div>

        <div style="display: none;" class="row col-md-12">
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
                    <button class="btn btn  btn-primary">Waiting Approval <span class="text-white">(<?php echo $waitingApprovalEnrollmentCount;?>)</span></button>
                </a>
            </div>
            <div class="col-md-3">
                <a href="enrolled.php">
                    <button class="btn btn  btn-outline-primary">Enrolled <span class="text-white">(<?php echo $enrolledStudentsEnrollmentCount;?>)</span></button>
                </a>
            </div>
            <hr>
            <hr>
            <hr>

            </div>
            <?php 
                if(count($waitingApprovalEnrollment) > 0){
                ?>
                    <div class="row col-md-12 mt-3">
                        <h3 class="mb-2 text-center text-success">Waiting Enrollment Approval</h3>
                        <div class="table-responsive">			
                            <table id="dash-table" class="table table-striped table-bordered table-hover table-responsive" style="font-size:12px" cellspacing="0">
                                <thead>
                                    <tr class="text-center">
                                        <th>Id</th>
                                        <th>Name</th>
                                        <th>Standing</th>
                                        <th>Program-Section</th>
                                        <th>Type</th>
                                        <th style="width: 150px;;" class="text-center">Action</th>
                                    </tr>	
                                </thead> 
                                <tbody>
                                    <?php

                                        foreach ($waitingApprovalEnrollment as $key => $row) {

                                            $enrollement_student_id = $row['student_id'];
                                            $fullname = $row['firstname'] . " " . $row['lastname'];
                                            $standing = $row['course_level'];
                                            $course_id = $row['course_id'];
                                            $username = $row['username'];
                                            $student_id = $row['t2_student_id'];
                                            $program_section = $row['program_section'];
                                            $cashier_evaluated = $row['cashier_evaluated'];
                                            $registrar_evaluated = $row['registrar_evaluated'];
                                            $course_level = $row['course_level'];
                                            $student_status = $row['student_status'];
                                            $new_enrollee = $row['new_enrollee'];
                                            $is_tertiary = $row['is_tertiary'];
                                            $is_transferee = $row['is_transferee'];
                                            
                                            $admission_status = $row['admission_status'];
                                            $student_statusv2 = $row['student_statusv2'];


                                            // $program_section_default = "";
                                            if($program_section === ""){
                                                $program_section = "NO SECTION";
                                            }

                                            // $course_level_default = "";
                                            if($course_level == ""){
                                                $course_level = "NO SECTION";
                                            }else{
                                                $course_level = "Grade $course_level";
                                            }
                                            
                                            $createUrl = "http://localhost/dcbt/admin/student/edit.php?id=$student_id";

                                            // $transferee_insertion_url = "http://localhost/dcbt/admin/student/transferee_insertion.php?id=$student_id";
                                            $transferee_insertion_url = "../student/transferee_insertion.php?enrolled_subjects=true&id=$student_id";

                                            // $regular_insertion_url = "http://localhost/dcbt/admin/student/transferee_insertion.php?id=$student_id";

                                            // $regular_insertion_url = "../enrollees/subject_insertion.php?username=$username&id=$student_id";
                                            $regular_insertion_url = "../enrollees/subject_insertion.php?enrolled_subjects=true&id=$student_id";

                                            
                                            $confirmButton  = "
                                                    <button onclick='confirmValidation(" . $course_id . ", " . $enrollement_student_id . ")' name='confirm_validation_btn' class='btn btn-primary btn-sm'>Confirm</button>
                                            ";

                                            $evaluateBtn = "";

                                            $student_type_status = "";
                                            
                                            if($cashier_evaluated == "yes"
                                                && $registrar_evaluated == "yes"){

                                                if($student_statusv2 == "Regular"){
                                                    $evaluateBtn = "
                                                        <a href='$regular_insertion_url'>
                                                            <button class='button-style-success success btn-sm'>
                                                                Evaluate
                                                            </button>
                                                        </a>
                                                    ";

                                                    if($new_enrollee == 1 && $is_tertiary == 0){
                                                        $student_type_status = "New Regular (SHS)";

                                                    }else if($new_enrollee == 0 && $is_tertiary == 0){
                                                        $student_type_status = "On Going SHS";

                                                    }
                                                    else if($new_enrollee == 0 && $is_tertiary == 1){
                                                        $student_type_status = "O.S Tertiary (Regular)";
                                                    }
                                                
                                                }else if($student_statusv2 == "Irregular"){

                                                    if($new_enrollee == 1 && $is_tertiary == 1){
                                                        $student_type_status = "New Tertiary (Irregular)";
                                                    }
                                                    else if($new_enrollee == 0 && $is_tertiary == 1){
                                                        $student_type_status = "O.S Tertiary (Irregular)";
                                                    }

                                                    $evaluateBtn = "
                                                        <a href='$transferee_insertion_url'>
                                                            <button class='button-style-success success btn-sm'>
                                                                Evaluate
                                                            </button>
                                                        </a>
                                                    ";
                                                }

                                                # if Transferee
                                                if($student_statusv2 == "Transferee"){

                                                        // if($new_enrollee == 0 || $new_enrollee == 1){
                                                    // if($new_enrollee == 1 && $is_tertiary == 0 && $is_transferee == 1){

                                                    //     $student_type_status = "New Transferee (SHS)";

                                                    //     $evaluateBtn = "
                                                    //         <a href='$transferee_insertion_url'>
                                                    //             <button class='btn btn-outline-success btn-sm'>
                                                    //                 Evaluate
                                                    //             </button>
                                                    //         </a>
                                                    //     ";

                                                    // }
                                                    // if($admission_status == "Standard" 
                                                    //     && $student_statusv2 == "Regular"){

                                                    //     $student_type_status = "Standard Regular";

                                                    //     $evaluateBtn = "
                                                    //         <a href='$transferee_insertion_url'>
                                                    //             <button class='btn btn-outline-success btn-sm'>
                                                    //                 Evaluate
                                                    //             </button>
                                                    //         </a>
                                                    //     ";

                                                    // }
                                                    // else if($admission_status == "Transferee" 
                                                    //     && $student_statusv2 == "Regular"){

                                                    //     // $student_type_status = "On Going Transferee (SHS)";
                                                        
                                                    //     $student_type_status = "O.S $admission_status (SHS $student_statusv2)";

                                                    
                                                    //     # PREVIOUS URL
                                                    //     $trans_url = "transferee_process_enrollment.php?step3=true&st_id=$student_id&selected_course_id=$course_id";
                                                    //     $regular_insertion_url = "../enrollees/subject_insertion.php?username=$username&id=$student_id";

                                                    //     $evaluateBtn = "
                                                    //         <a href='$regular_insertion_url'>
                                                    //             <button class='btn btn-outline-success btn-sm'>
                                                    //                 Evaluate
                                                    //             </button>
                                                    //         </a>
                                                    //     ";

                                                    // }


                                                }
                                            }



                                            echo "
                                                <tr class='text-center'>
                                                    <td>$student_id</td>
                                                    <td>$fullname</td>
                                                    <td>$course_level </td>
                                                    <td>$program_section</td>
                                                    <td>$student_type_status</td>
                                                    
                                                    <td>
                                                        $evaluateBtn
                                                    </td>
                                                </tr>
                                            ";

                                        }
                                    
                                        
                                    ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                <?php
                }else{
                    echo "
                        <h3 class='text-info text-center'>No Waiting Approval found.</h3>
                    ";
                }

            ?>

        </div>
        
    </div>
