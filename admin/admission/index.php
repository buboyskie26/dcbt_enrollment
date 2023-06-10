<?php  
 
    include('../../enrollment/classes/StudentEnroll.php');
    include('../../enrollment/classes/Enrollment.php');

    include('../registrar_enrollment_header.php');

    // require_once __DIR__ . '/../../vendor/autoload.php';
    // use Dompdf\Dompdf;
    // use Dompdf\Options;

    if(!AdminUser::IsRegistrarAuthenticated()){

        header("Location: /dcbt/adminLogin.php");
        exit();
    }

    $enroll = new StudentEnroll($con);

    $school_year_obj = $enroll->GetActiveSchoolYearAndSemester();

    $current_school_year_id = $school_year_obj['school_year_id'];
    $current_school_year_term = $school_year_obj['term'];
    $current_school_year_period = $school_year_obj['period'];

    if (isset($_SESSION['enrollment_form_id'])) {
        unset($_SESSION['enrollment_form_id']);
    }

    $enrollment = new Enrollment($con, null);
    $pendingEnrollment = $enrollment->PendingEnrollment();
    $waitingPaymentEnrollment = $enrollment->WaitingPaymentEnrollment($current_school_year_id);
    $waitingApprovalEnrollment = $enrollment->WaitingApprovalEnrollment($current_school_year_id);


    $pendingEnrollmentCount = count($pendingEnrollment);
    $waitingPaymentEnrollmentCount = count($waitingPaymentEnrollment);
    $waitingApprovalEnrollmentCount = count($waitingApprovalEnrollment);

    
?>

<div class="row col-md-12">

    <div class="row col-md-12">

        <div class="row">
            <div class="col-md-3">
                <a href="evaluation.php">
                    <button class="btn btn btn-primary">Evaluation 
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
                <button class="btn btn  btn-outline-primary">Enrolled</button>

                </a>
            </div>
            <hr>
            <hr>
            <hr>

        </div>

        <h3 class="mb-2 text-center text-primary">Non-Evaluated</h3>

        <table id="admission_evaluation" 
            class="table table-striped table-bordered table-hover "
            style="font-size:13px" cellspacing="0"  > 
            <thead>
                <tr class="text-center"> 
                    <th rowspan="2">Name</th>
                    <th rowspan="2">Type</th>
                    <th rowspan="2">Strand</th>
                    <th rowspan="2">Date Submitted</th>
                    <th rowspan="2">Status</th>
                    <th rowspan="2">Action</th>
                </tr>	
            </thead> 	

            <tbody>
                <?php 
                    $sql = $con->prepare("SELECT t1.*, t2.acronym 
                        FROM pending_enrollees as t1

                        LEFT JOIN program as t2 ON t2.program_id = t1.program_id
                        WHERE t1.student_status !='APPROVED'
                        AND t1.is_finished = 1

                        ");

                    $sql->execute();


                    if(count($pendingEnrollment) > 0){
                        foreach ($pendingEnrollment as $key => $row) {

                            $fullname = $row['firstname'] . " " . $row['lastname'];
                            $date_creation = $row['date_creation'];
                            $acronym = $row['acronym'];
                            $pending_enrollees_id = $row['pending_enrollees_id'];
                            $student_unique_id = "N/A";

                            $type = "";
                            $url = "";
                            $status = "Evaluation";
                            $button_output = "";
                            $process_url = "process_enrollment.php?step1=true&id=$pending_enrollees_id";

                            if($row['student_status'] == "Regular"){
                                $type = "New Regular";
                                // $url = "../enrollees/view_student_new_enrollment.php?id=$pending_enrollees_id";
                                $button_output = "
                                    <a href='$process_url'>
                                        <button class='btn btn-primary btn-sm'>View</button>
                                    </a>
                                ";
                                
                            }else  if($row['student_status'] == "Transferee"){
                                $type = "New Transferee";
                                // $url = "../enrollees/view_student_transferee_enrollment.php?id=$pending_enrollees_id";
                                $url_trans = "transferee_process_enrollment.php?step1=true&id=$pending_enrollees_id";

                                $button_output = "
                                    <a href='$url_trans'>
                                        <button class='btn btn-outline-primary btn-sm'>View</button>
                                    </a>
                                ";
                            }

                            echo "
                                <tr class='text-center'>
                                    <td>$fullname</td>
                                    <td>$type</td>
                                    <td>$acronym</td>
                                    <td>$date_creation</td>
                                    <td>$status</td>
                                    <td>
                                        $button_output
                                    </td>
                                </tr>
                            ";
                        }
                    }
                ?>
            </tbody>
        </table>

    </div>

    <div style="display: none;">

        <h3 class="mb-2 text-center text-primary">Non-Evaluated</h3>
        <table id="courseTable" class="table table-striped table-bordered table-hover "  style="font-size:13px" cellspacing="0"  > 
            <thead>
                <tr class="text-center"> 
                    <th rowspan="2">Name</th>
                    <th rowspan="2">Type</th>
                    <th rowspan="2">Strand</th>
                    <th rowspan="2">Date Submitted</th>
                    <th rowspan="2">Status</th>
                    <th rowspan="2">Action</th>
                </tr>	
            </thead> 	

            <tbody>
                <?php 
                    $sql = $con->prepare("SELECT t1.*, t2.acronym 
                    FROM pending_enrollees as t1

                    LEFT JOIN program as t2 ON t2.program_id = t1.program_id
                    WHERE t1.student_status !='APPROVED'
                    AND t1.is_finished = 1
                    ");
                    $sql->execute();

                    if($sql->rowCount() > 0){

                        // echo "we";
                        while($row = $sql->fetch(PDO::FETCH_ASSOC)){

                            $fullname = $row['firstname'] . " " . $row['lastname'];
                            $date_creation = $row['date_creation'];
                            $acronym = $row['acronym'];
                            $pending_enrollees_id = $row['pending_enrollees_id'];
                            $student_unique_id = "N/A";

                            $type = "";
                            $url = "";
                            $status = "Evaluation";
                            $button_output = "";
                            $process_url = "process_enrollment.php?step1=true&id=$pending_enrollees_id";

                            if($row['student_status'] == "Regular"){
                                $type = "New Regular";
                                // $url = "../enrollees/view_student_new_enrollment.php?id=$pending_enrollees_id";
                                $button_output = "
                                    <a href='$process_url'>
                                        <button class='btn btn-primary btn-sm'>View</button>
                                    </a>
                                ";
                                
                            }else  if($row['student_status'] == "Transferee"){
                                $type = "New Transferee";
                                // $url = "../enrollees/view_student_transferee_enrollment.php?id=$pending_enrollees_id";
                                $url_trans = "transferee_process_enrollment.php?step1=true&id=$pending_enrollees_id";

                                $button_output = "
                                    <a href='$url_trans'>
                                        <button class='btn btn-outline-primary btn-sm'>View</button>
                                    </a>
                                ";
                            }

                            echo "
                                <tr class='text-center'>
                                    <td>$fullname</td>
                                    <td>$type</td>
                                    <td>$acronym</td>
                                    <td>$date_creation</td>
                                    <td>$status</td>
                                    <td>
                                        $button_output
                                    </td>
                                </tr>
                            ";
                        }
                    }
                ?>
            </tbody>
        </table>
        <hr>
        <hr>
        <h3 class="mb-2 text-center text-primary">Transferee O.S</h3>
        <table id="courseTable" class="table table-striped table-bordered table-hover "  style="font-size:13px" cellspacing="0"  > 
        <thead>
            <tr class="text-center"> 
                <th rowspan="2">Name</th>
                <th rowspan="2">Type</th>
                <th rowspan="2">Strand</th>
                <th rowspan="2">Date Submitted</th>
                <th rowspan="2">Status</th>
                <th rowspan="2">Action</th>
            </tr>	
        </thead> 	

            <tbody>
                <?php 
                    
                    $sql = $con->prepare("SELECT t1.*, t4.*, t2.* 
                    
                        FROM student as t1

                        INNER JOIN enrollment as t2 ON t2.student_id = t1.student_id
                        AND t2.course_id=t1.course_id

                        LEFT JOIN course as t3 ON t3.course_id = t1.course_id
                        LEFT JOIN program as t4 ON t4.program_id = t3.program_id

                        -- WHERE t1.student_status='Transferee'
                        WHERE t1.admission_status='Transferee'
                        AND t2.registrar_evaluated='no'
                        AND t2.enrollment_status='tentative'
                        AND t2.is_new_enrollee='no'
                    ");

                    $sql->execute();

                    if($sql->rowCount() > 0){

                        // echo "we";
                        while($row = $sql->fetch(PDO::FETCH_ASSOC)){

                            $fullname = $row['firstname'] . " " . $row['lastname'];
                            $enrollment_date = $row['enrollment_date'];
                            $student_id = $row['student_id'];
                            $student_course_id = $row['course_id'];
                            $acronym = $row['acronym'];
                            // $pending_enrollees_id = $row['pending_enrollees_id'];
                            $student_unique_id = "N/A";

                            $type = "Ongoing Transferee (SHS)";
                            $url = "";
                            $status = "Evaluation";
                            $button_output = "";

                            // $process_url = "process_enrollment.php?step1=true&id=$pending_enrollees_id";
                            $process_url = "";
                            $trans_url = "transferee_process_enrollment.php?step3=true&st_id=$student_id&selected_course_id=$student_course_id";
                            

                            $evaluateBtn = "
                                <a href='$trans_url'>
                                    <button class='btn btn-outline-success btn-sm'>
                                        Evaluate
                                    </button>
                                </a>
                            ";
                            echo "
                                <tr class='text-center'>
                                    <td>$fullname</td>
                                    <td>$type</td>
                                    <td>$acronym</td>
                                    <td>$enrollment_date</td>
                                    <td>$status</td>
                                    <td>
                                        $evaluateBtn
                                    </td>
                                </tr>
                            ";
                        }
                    }

                ?>
            </tbody>
        </table>
            
        <div class="card">
            <div class="card-header">
                <h4 class="text-muted text-center">Waiting Payment List</h4>
            </div>
            <div class="card-body">
                <div class="table-responsive">			
                    <table id="dash-table" class="table table-striped table-bordered table-hover table-responsive" style="font-size:12px" cellspacing="0">
                        <thead>
                            <tr class="text-center">
                                <th>Id</th>
                                <th>Name</th>
                                <th>Standing</th>
                                <th>Course/Section</th>
                                <th>Type</th>
                                <th style="width: 150px;;" class="text-center">Action</th>
                            </tr>	
                        </thead> 
                        <tbody>
                            <?php
                                // Generate a random alphanumeric string as the enrollment form ID

                                $default_shs_course_level = 11;
                                $is_new_enrollee = 1;
                                $is_transferee = 1;
                                $regular_Status = "Regular";
                                $enrollment_status = "tentative";
                                $registrar_evaluated = "yes";

                                $registrar_side = $con->prepare("SELECT 

                                    t1.student_id, t1.cashier_evaluated,t1.registrar_evaluated,
                                    t1.is_transferee,

                                    t2.firstname,t2.username,
                                    t2.lastname,t2.course_level,
                                    t2.course_id, t2.student_id as t2_student_id,
                                    t2.course_id, t2.course_level,t2.student_status,
                                    t2.is_tertiary, t2.new_enrollee,
                                    
                                    t3.program_section

                                    FROM enrollment as t1

                                    INNER JOIN student as t2 ON t2.student_id = t1.student_id
                                    LEFT JOIN course as t3 ON t2.course_id = t3.course_id

                                    WHERE (t1.is_new_enrollee=:is_new_enrollee
                                        OR 
                                        t1.is_new_enrollee=:is_new_enrollee2)
                                    -- AND t1.is_transferee=:is_transferee

                                    AND (t1.is_transferee = :is_transferee 
                                        OR 
                                        t1.is_transferee = :is_transferee2)
                                    
                                    AND t1.enrollment_status=:enrollment_status
                                    AND t1.school_year_id=:school_year_id
                                    AND t1.registrar_evaluated=:registrar_evaluated
                                    AND t1.cashier_evaluated=:cashier_evaluated
                                    ");

                                $registrar_side->bindValue(":is_new_enrollee", $is_new_enrollee);
                                $registrar_side->bindValue(":is_new_enrollee2", 0);
                                $registrar_side->bindValue(":is_transferee", $is_transferee);
                                $registrar_side->bindValue(":is_transferee2", "0");
                                $registrar_side->bindValue(":enrollment_status", $enrollment_status);
                                $registrar_side->bindValue(":school_year_id", $current_school_year_id);
                                $registrar_side->bindValue(":registrar_evaluated", $registrar_evaluated);
                                $registrar_side->bindValue(":cashier_evaluated", "no");
                                $registrar_side->execute();
                            
                                if($registrar_side->rowCount() > 0){

                                    $transResult = "";
                                    $createUrl = "";

                                    while($row = $registrar_side->fetch(PDO::FETCH_ASSOC)){

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
                                        if($cashier_evaluated == "no"
                                            && $registrar_evaluated == "yes"){

                                            if($student_status == "Regular"){
                                                $evaluateBtn = "
                                                    <a href='$regular_insertion_url'>
                                                        <button class='btn btn-outline-success btn-sm'>
                                                            Check
                                                        </button>
                                                    </a>
                                                ";

                                                if($new_enrollee == 1 && $is_tertiary == 0){
                                                    $student_type_status = "New Regular (SHS)";

                                                }else if($new_enrollee == 0 && $is_tertiary == 0){
                                                    $student_type_status = "On Going (SHS)";
                                                }
                                            }

                                            # if Transferee
                                            if($student_status == "Transferee"){

                                                    // if($new_enrollee == 0 || $new_enrollee == 1){
                                                if($new_enrollee == 1 && $is_tertiary == 0 && $is_transferee == 1){
                                                    $student_type_status = "New Transferee (SHS)";

                                                    $evaluateBtn = "
                                                        <a href='$transferee_insertion_url'>
                                                            <button class='btn btn-outline-success btn-sm'>
                                                                Evaluate
                                                            </button>
                                                        </a>
                                                    ";

                                                }else if($new_enrollee == 0 && $is_tertiary == 0 && $is_transferee == 0){

                                                    $student_type_status = "On Going Transferee (SHS)";

                                                    // $evaluateBtn = "
                                                    //     <a href='cashier_process_enrollment.php?id=$student_id'>

                                                    //         <button class='btn btn-outline-primary btn-sm'>
                                                    //             Evaluate
                                                    //         </button>
                                                    //     </a>
                                                    // ";

                                                    // $asd = $course_id;

                                                    // $trans_url = "transferee_process_enrollment.php?step3=true&id=$student_id&selected_course_id=$course_id";

                                                    # PREVIOUS URL
                                                    $trans_url = "transferee_process_enrollment.php?step3=true&st_id=$student_id&selected_course_id=$course_id";

                                                    $evaluateBtn = "
                                                        <a href='$transferee_insertion_url'>
                                                            <button class='btn btn-outline-success btn-sm'>
                                                                Evaluate
                                                            </button>
                                                        </a>
                                                    ";
                                                }
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
                                }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <h3 class="mb-2 text-center text-success">Evaluated</h3>
        <div class="table-responsive">			
            <table id="dash-table" class="table table-striped table-bordered table-hover table-responsive" style="font-size:12px" cellspacing="0">
                <thead>
                    <tr class="text-center">
                        <th>Id</th>
                        <th>Name</th>
                        <th>Standing</th>
                        <th>Course/Section</th>
                        <th>Type</th>
                        <th style="width: 150px;;" class="text-center">Action</th>
                    </tr>	
                </thead> 
                <tbody>
                    <?php

                        // Generate a random alphanumeric string as the enrollment form ID

                        $default_shs_course_level = 11;
                        $is_new_enrollee = 1;
                        $is_transferee = 1;
                        $regular_Status = "Regular";
                        $enrollment_status = "tentative";
                        $registrar_evaluated = "yes";

                        $registrar_side = $con->prepare("SELECT 

                            t1.student_id, t1.cashier_evaluated,t1.registrar_evaluated,
                            t1.is_transferee,

                            t2.firstname,t2.username,
                            t2.lastname,t2.course_level,
                            t2.course_id, t2.student_id as t2_student_id,
                            t2.course_id, t2.course_level,t2.student_status,
                            t2.is_tertiary, t2.new_enrollee,
                            
                            t3.program_section

                            FROM enrollment as t1

                            INNER JOIN student as t2 ON t2.student_id = t1.student_id
                            LEFT JOIN course as t3 ON t2.course_id = t3.course_id

                            WHERE (t1.is_new_enrollee=:is_new_enrollee
                            OR t1.is_new_enrollee=:is_new_enrollee2)
                            -- AND t1.is_transferee=:is_transferee

                                AND (t1.is_transferee = :is_transferee OR t1.is_transferee = :is_transferee2)
                                
                            AND t1.enrollment_status=:enrollment_status
                            AND t1.school_year_id=:school_year_id
                            AND t1.registrar_evaluated=:registrar_evaluated
                            AND t1.cashier_evaluated=:cashier_evaluated
                            ");

                        $registrar_side->bindValue(":is_new_enrollee", $is_new_enrollee);
                        $registrar_side->bindValue(":is_new_enrollee2", 0);
                        
                        $registrar_side->bindValue(":is_transferee", $is_transferee);
                        $registrar_side->bindValue(":is_transferee2", "0");

                        $registrar_side->bindValue(":enrollment_status", $enrollment_status);
                        $registrar_side->bindValue(":school_year_id", $current_school_year_id);
                        $registrar_side->bindValue(":registrar_evaluated", $registrar_evaluated);
                        $registrar_side->bindValue(":cashier_evaluated", "yes");
                        $registrar_side->execute();
                    
                        if($registrar_side->rowCount() > 0){
                            $transResult = "";
                            $createUrl = "";

                            while($row = $registrar_side->fetch(PDO::FETCH_ASSOC)){

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

                                    if($student_status == "Regular"){
                                        $evaluateBtn = "
                                            <a href='$regular_insertion_url'>
                                                <button class='btn btn-success btn-sm'>
                                                    Evaluate
                                                </button>
                                            </a>
                                        ";

                                        if($new_enrollee == 1 && $is_tertiary == 0){
                                            $student_type_status = "New Regular (SHS)";

                                        }else if($new_enrollee == 0 && $is_tertiary == 0){
                                            $student_type_status = "On Going (SHS)";

                                        }
                                    }

                                    # if Transferee
                                    if($student_status == "Transferee"){

                                            // if($new_enrollee == 0 || $new_enrollee == 1){
                                        if($new_enrollee == 1 && $is_tertiary == 0 && $is_transferee == 1){
                                            $student_type_status = "New Transferee (SHS)";

                                            $evaluateBtn = "
                                                <a href='$transferee_insertion_url'>
                                                    <button class='btn btn-outline-success btn-sm'>
                                                        Evaluate
                                                    </button>
                                                </a>
                                            ";

                                        }else if($new_enrollee == 0 && $is_tertiary == 0 && $is_transferee == 0){

                                            $student_type_status = "On Going Transferee (SHS)";

                                            // $evaluateBtn = "
                                            //     <a href='cashier_process_enrollment.php?id=$student_id'>

                                            //         <button class='btn btn-outline-primary btn-sm'>
                                            //             Evaluate
                                            //         </button>
                                            //     </a>
                                            // ";

                                            // $asd = $course_id;

                                            // $trans_url = "transferee_process_enrollment.php?step3=true&id=$student_id&selected_course_id=$course_id";

                                            # PREVIOUS URL
                                            $trans_url = "transferee_process_enrollment.php?step3=true&st_id=$student_id&selected_course_id=$course_id";

                                            $evaluateBtn = "
                                                <a href='$transferee_insertion_url'>
                                                    <button class='btn btn-outline-success btn-sm'>
                                                        Evaluate
                                                    </button>
                                                </a>
                                            ";
                                        }
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
                        }
                    ?>
                </tbody>
            </table>
        </div>


    </div>



</div>
