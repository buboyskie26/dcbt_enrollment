<script src="../assets/js/common.js"></script>


<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <style>
        .form-header h2{
            font-style: normal;
            font-weight: 700;
            font-size: 36px;
            line-height: 43px;
            display: flex;
            align-items: center;
            color: #BB4444;
        }   

        .header-content{
            display: flex;
            flex-direction: row;
            justify-content: space-between;
            margin-top: 50px;
            padding: 0px;
            gap: 10px;
            width: 90%;
            height: 43px;
            align-items: center;
        }


        .action{
            border: none;
            background: transparent;
            color: #E85959;
        }

        .action:hover{
            color: #9b3131;
        }

        .student-table{
            display: flex;
            flex-direction: row;
            align-items: flex-start;
            padding: 5px 0px;
            width: 100%;
            height: 58px;
        }

        table{
            table-layout: fixed;
            border-collapse: collapse;
            width: 100%;
            text-align: center;
        }

        tbody{
            font-style: normal;
            font-weight: 400;
            font-size: 17px;
            align-items: center;
        }

        .choices{
            display: flex;
            flex-direction: row;
            justify-content: center;
            align-items: center;
            margin-top: 80px;
            padding: 20px 53px 0px;
            gap: 1px;
            width: 100%;
            height: 74px;
            background: #1A0000;
            flex: none;
            order: 2;
            align-self: stretch;
            flex-grow: 0;
        }
        .selection-btn{
            display: flex;
            flex-direction: row;
            justify-content: center;
            align-items: center;
            padding: 5px 20px;
            gap: 10px;
            width: 340px;
            height: 54px;
            background: #EFEFEF;
            border: none;
            font-style: normal;
            font-weight: 400;
            font-size: 20px;
        }

        .bg-content{
            display: flex;
            flex-direction: column;
            align-items: center;
            padding: 50px 0px;
            width: 100%;
            height: auto;
            background: #EFEFEF;
        }

        .form-details{
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: flex-start;
            padding: 32px 26px;
            gap: 19px;
            width: 100%;
            height: auto;
            background: #FFFFFF;
            box-shadow: 0px 5px 20px rgba(0, 0, 0, 0.25);
            border-radius: 10px;
            margin-top: 30px;
        }

        .form-details h3{
            display: flex;
            align-items: center;
            font-style: normal;
            font-weight: 700;
            font-size: 36px;
            line-height: 43px;
            color: #BB4444;
        }

        form{
            flex: none;
            order: 1;
            align-self: stretch;
            flex-grow: 0;
        }
        .back-menu{
            display: flex;
            flex: row;
            align-items: center;
            padding: 8px 40px;
            gap: 8px;
            width: 100%;
            height: 46px;
        }
        .admission-btn{
            border: none;
            background: none;
            color: #BB4444;
            font-style: normal;
            font-weight: 700;
            font-size: 16px;
        }

        .admission-btn:hover{
            color: #863131;
        }

    </style>

</head>

<?php 
    include('../registrar_enrollment_header.php');
    require_once('../../enrollment/classes/StudentEnroll.php');
    require_once('../../enrollment/classes/OldEnrollees.php');
    require_once('../../enrollment/classes/Pending.php');
    require_once('../../enrollment/classes/Enrollment.php');
    require_once('../../enrollment/classes/Section.php');
    require_once('../../includes/classes/Student.php');

    ?>
    <head>
        <link rel="stylesheet" href="../../admin//assets/css/enrollees/subject_insertion.css">
    </head>

    <?php

    // require '../../vendor/autoload.php';
    require_once __DIR__ . '/../../vendor/autoload.php';
    use Dompdf\Dompdf;
    use Dompdf\Options;

    require_once('../classes/Course.php');

    $studentEnroll = new StudentEnroll($con);
    $oldEnroll = new OldEnrollees($con, $studentEnroll);
    $pending = new Pending($con);

    $course = new Course($con, $studentEnroll);

    if(!isset($_SESSION['registrarLoggedIn'])){
        header("Location: " . base_url . "/index.php");
        exit();
    }

    if(isset($_GET['username']) && isset($_GET['id'])
        && !isset($_GET['inserted'])){

        $student_username = $_GET['username'];
        $old_student_status = $oldEnroll->GetOldStudentStatus($student_username);
        $studentNewEnrolee = $oldEnroll->DoesStudentNewEnrollee($student_username);
            
        $base_url = 'http://localhost/elms/admin';

        $student_id = $_GET['id'];

        // echo $student_id;

        $student_fullname = $studentEnroll->GetStudentFullName($student_id);
        $student_firstname = $studentEnroll->GetStudentFirstname($student_id);
        $student_lastname = $studentEnroll->GetStudentLastname($student_id);
        $student_middle_name = $studentEnroll->GetStudentMiddlename($student_id);

        $username = $studentEnroll->GetStudentUsername($student_id);

        $student = new Student($con, $username);

        $student_new_enrollee = $student->GetStudentNewEnrollee();
        $student_admission_status = $student->GetStudentAdmissionStatus();
        
        $student_status = "";

        if($student_new_enrollee == 1){
            $student_status = "New";
        }else if($student_new_enrollee == 0 && $student_admission_status == "Standard"){
            $student_status = "Ongoing Regular";
        }
        else if($student_new_enrollee == 0 && $student_admission_status == "Transferee"){
            $student_status = "Ongoing Transferee";
        }

        $pending_form_submission = $pending->GetSubmittedOn($student_firstname);

        $confirmStudent = "enrolledStudent(\"$student_username\")";

        // $username2 = "(\"$username\")";
        $recommendedSubject = $studentEnroll->GetSHSNewStudentSubjectProgramBased($student_username);
        $studentCourseYear = $studentEnroll->GetStudentCourseName($student_username);
        $school_year_obj = $studentEnroll->GetActiveSchoolYearAndSemester();

        $student_course_level = $studentEnroll->GetStudentCourseLevel($student_username);

        $student_course_id = $studentEnroll->GetStudentCourseId($student_username);
        $student_program_id = $studentEnroll->GetStudentProgramId($student_course_id);

        // echo $student_id;
        $student_program_section = $studentEnroll->GetStudentProgramSection($student_course_id);


        $current_school_year_term = $school_year_obj['term'];
        $current_school_year_semester = $school_year_obj['period'];
        $school_year_id = $school_year_obj['school_year_id'];
    
        $forget_establish_time_url = $base_url . '/schedule/create.php';

        $unscheduledSubjectLeft = false;

        $display = "block";
        
        $enrollment = new Enrollment($con, $studentEnroll);
        $section = new Section($con, $student_course_id);

        $isSectionFull = $section->CheckSectionIsFull($student_course_id);

        $unique_form_id = $enrollment->GenerateEnrollmentFormId();

        $get_student_enrollment_form_id = $enrollment->GetEnrollmentFormId($student_id, $student_course_id, $school_year_id);
        $student_unique_id = $student->GetStudentUniqueId();

        $enrollment_id = $enrollment->GetEnrollmentId($student_id,
            $student_course_id, $school_year_id);

        // echo "enrollment_id: $enrollment_id";

        $student_section_obj = $section->GetSectionObj($student_course_id);

        $student_curren_course_program_id = $student_section_obj['program_id'];
        $student_current_course_level = $student_section_obj['course_level'];
        $student_current_capacity = $student_section_obj['capacity'];

        $updatedTotalStudent = $section->GetTotalNumberOfStudentInSection($student_course_id,
            $school_year_id);

        $checkEnrollmentEnrolled = $enrollment->CheckEnrollmentEnrolled($student_id,
                $student_course_id, $school_year_id);

        $cashierEvaluated = $enrollment->CheckEnrollmentCashierApproved($student_id,
                $student_course_id, $school_year_id);

        $payment_status = "";

        if($checkEnrollmentEnrolled == true && $cashierEvaluated == true){
            $payment_status = "Enrolled";

        }else if($checkEnrollmentEnrolled == false && $cashierEvaluated == true){
            $payment_status = "Approved";
        }else{
            $payment_status = "Waiting";
        }

        $getEnrollmentNonEnrolledDate = $enrollment
            ->GetEnrollmentNonEnrolledDate($student_id, $student_course_id,$school_year_id);

        $getEnrollmentEnrolledDate = $enrollment
            ->GetEnrollmentEnrolledDate($student_id, $student_course_id,$school_year_id);

        $proccess_date = null;

        // echo $getEnrollmentNonEnrolledDate;

        if($checkEnrollmentEnrolled == true){
            $proccess_date = $getEnrollmentEnrolledDate;
        }else{
            $proccess_date = $getEnrollmentNonEnrolledDate;
        }

        // echo $updatedTotalStudent . " school ear";

        if(isset($_POST['section_full_btn'])){

            # Get Aaailable section next to the recently full section.
            $get_prev_section = $con->prepare("SELECT course_id FROM course
                WHERE program_id=:program_id
                AND active=:active
                AND course_level=:course_level
                AND school_year_term=:school_year_term
                ORDER BY course_id DESC
                LIMIT 1");
            
            $get_prev_section->bindValue(":program_id", $student_program_id);
            $get_prev_section->bindValue(":active", "yes");
            $get_prev_section->bindValue(":course_level", $student_course_level);
            $get_prev_section->bindValue(":school_year_term", $current_school_year_term);
            $get_prev_section->execute();

            if($get_prev_section->rowCount() > 0){

                $available_course_id = $get_prev_section->fetchColumn();

                $available_section_obj = $section->GetSectionObj($available_course_id);

                $available_section_name = $available_section_obj['program_section'];

                $update_enrollment_success = $enrollment->UpdateSHSStudentEnrollmentCourseId(
                    $enrollment_id, $available_course_id);
                
                $update_student_course_id = $student->UpdateStudentCourseId(
                    $student_course_id, $student_id, $available_course_id);

                if($update_enrollment_success == true && $update_student_course_id){

                    AdminUser::success("Student section moved to: $available_section_name",
                        "subject_insertion.php?username=$student_username&id=$student_id");
                    exit();
                }

            }                

            # Update enrollment student course_id
        }

        if(isset($_POST['subject_load_btn']) && isset($_POST['unique_enrollment_form_id'])){
            
            $array_success = [];

            $subject_program_id = 0;

            $unique_enrollment_form_id = $_POST['unique_enrollment_form_id'];

            $successInsertingSubjectLoad = false;

            // Update student + 1 to the total_student
            $active = "yes";
            $is_full = "no";
            $total_student = 1;

            $sql_insert = $con->prepare("INSERT INTO student_subject 
                (student_id, subject_id, school_year_id,
                    course_level, enrollment_id, subject_program_id)
                VALUES(:student_id, :subject_id, :school_year_id,
                    :course_level, :enrollment_id, :subject_program_id)");

            if($successInsertingSubjectLoad == true){
                $wasSuccess = $oldEnroll->UpdateSHSStudentStatus($student_username);
            }

            $isSuccess = false;

            $new_regular_shs_subjects = $studentEnroll->GetStudentsStrandSubjects($student_username);

            // print_r($new_regular_shs_subjects);
            
            $is_inserted_all = false;

            $subjectInitialized = false;

            foreach ($new_regular_shs_subjects as $key => $value) {
                # code...

                $subject_id = $value['subject_id'];
                $subject_program_id = $value['subject_program_id'];

                if($subject_id != 0){
                    $_SESSION['regular_subject_ids_v2'][] = array(
                        'subject_id' => $subject_id
                    );
                    $subjectInitialized = true;
                }

                // echo "redirect to receipt page";
                # Check if subjects already enrolled.

                # Insert all subjects
                $sql_insert->bindValue(":student_id", $student_id);
                $sql_insert->bindValue(":subject_id", $subject_id);
                $sql_insert->bindValue(":school_year_id", $school_year_id);
                $sql_insert->bindValue(":course_level", $student_course_level);
                $sql_insert->bindValue(":enrollment_id", $enrollment_id);
                $sql_insert->bindValue(":subject_program_id", $subject_program_id);

                if($sql_insert->execute()){

                    $is_inserted_all = true;
                }
            }
        
            if($is_inserted_all == true){

                $isSubjectCreated = false;

                # Enrolled Student.

                $wasSuccess = $oldEnroll->EnrolledStudentInTheEnrollmentv2($school_year_id,
                    $student_id, $get_student_enrollment_form_id);

                if($wasSuccess){

                    $section_obj = $section->GetSectionObj($student_course_id);

                    $sectionTotalStudent = $section->GetTotalNumberOfStudentInSection($student_course_id,
                        $school_year_id);

                    $capacity = $section_obj['capacity'];
                    $program_section = $section_obj['program_section'];
                    $course_program_id = $section_obj['program_id'];
                    $course_level = $section_obj['course_level'];
                    # Check if section is full

                    if($sectionTotalStudent >= $capacity 
                        && $current_school_year_semester == "First"){

                        # yes mark as full
                        # Update Previous Section into Is FULL.
                        $update_isfull = $section->SetSectionIsFull($student_course_id);
                        
                        $new_program_section = $section->AutoCreateAnotherSection($program_section);

                        $createNewSection = $section->CreateNewSection($new_program_section, 
                            $course_program_id, $course_level,
                            $current_school_year_term);

                        # Create Subject In that section
                        
                        if($createNewSection == true && $update_isfull == true){

                            $createNewSection_Id = $con->lastInsertId();

                            $get_subject_program = $con->prepare("SELECT * 
                            
                                FROM subject_program

                                WHERE program_id=:program_id
                                AND course_level=:course_level
                                ");

                            $get_subject_program->bindValue(":program_id", $course_program_id);
                            $get_subject_program->bindValue(":course_level", $course_level);
                            $get_subject_program->execute();

                            if($get_subject_program->rowCount() > 0){

                                $insert_section_subject = $con->prepare("INSERT INTO subject
                                    (subject_title, description, subject_program_id, unit, semester, program_id, course_level, course_id, subject_type, subject_code, pre_requisite)
                                    VALUES(:subject_title, :description, :subject_program_id, :unit, :semester, :program_id, :course_level, :course_id, :subject_type, :subject_code, :pre_requisite)");

                                while($row = $get_subject_program->fetch(PDO::FETCH_ASSOC)){

                                    $program_program_id = $row['subject_program_id'];
                                    $program_course_level = $row['course_level'];
                                    $program_semester = $row['semester'];
                                    $program_subject_type = $row['subject_type'];
                                    $program_subject_title = $row['subject_title'];
                                    $program_subject_description = $row['description'];
                                    $program_subject_unit = $row['unit'];
                                    $program_subject_pre_requisite = $row['pre_req_subject_title'];

                                    $program_subject_code = $row['subject_code'] ."-". $new_program_section; 
                                    // $program_subject_code = $row['subject_code'];

                                    $insert_section_subject->bindValue(":subject_title", $program_subject_title);
                                    $insert_section_subject->bindValue(":description", $program_subject_description);
                                    $insert_section_subject->bindValue(":subject_program_id", $program_program_id);
                                    $insert_section_subject->bindValue(":unit", $program_subject_unit);
                                    $insert_section_subject->bindValue(":semester", $program_semester);
                                    $insert_section_subject->bindValue(":program_id", $course_program_id);
                                    $insert_section_subject->bindValue(":course_level", $program_course_level);
                                    $insert_section_subject->bindValue(":course_id", $createNewSection_Id);
                                    $insert_section_subject->bindValue(":subject_type", $program_subject_type);
                                    $insert_section_subject->bindValue(":subject_code", $program_subject_code);
                                    $insert_section_subject->bindValue(":pre_requisite", $program_subject_pre_requisite);

                                    // $insert_section_subject->execute();
                                    if($insert_section_subject->execute()){
                                        $isSubjectCreated = true;
                                        // echo "New Section $new_program_section is created and student has confirmed.";
                                    }
                                }
                                // if($isSubjectCreated == true){
                                //     // echo "Successfully populated subjects in course_id $course_id";
                                // }
                            }
                        }
                    }

                    # Update student table
                    $newToOldSuccess = $oldEnroll->UpdateSHSStudentNewToOld($student_id);

                    if($newToOldSuccess && $isSubjectCreated == false){

                        # redirect to the receipt page.
                        if($subjectInitialized == true){
                            // echo "truee";
                            AdminUser::success("Successfully inserted the subjects, Student has been officially enrolled", "subject_insertion.php?enrolled_success=true&id=$student_id");
                            // header("Location: ");
                            exit();
                        }
                    }

                    if($newToOldSuccess && $isSubjectCreated == true){

                        # redirect to the receipt page.
                        if($subjectInitialized == true){
                            AdminUser::success("Successfully inserted the subjects, Student has been officially enrolled & Section is $program_section now full",
                                "subject_insertion.php?enrolled_success=true&id=$student_id");
                            // header("Location: ");
                            exit();
                        }
                    }

                }
            }
        }
        
        ?>
            <div style="display: none;" class="row col-md-12">

                <div class="card">
                    <div class="card-header">
                        <h4 class="text-center text-primary">Enrollment Form</h4>
                        <hr>
                    </div>

                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label class="mb-2" for="">Enrollment ID</label>
                                    <input readonly value="<?php echo $get_student_enrollment_form_id;?>" type="text" class="form-control">
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label class="mb-2" for="">Student Type</label>
                                    <input readonly value="New" type="text" class="form-control">
                                </div>
                            </div>

                            <div class="col-md-2">
                                <div class="form-group">
                                    <label class="mb-2" for="">Student no.</label>
                                    <input readonly value="<?php echo $student_unique_id;?>" type="text" class="form-control">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label class="mb-2" for="">Status Evaluation.</label>
                                    <input readonly value="Evaluation" type="text" class="form-control">
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label class="mb-2" for="">Submitted on.</label>
                                    <input readonly value="<?php echo $pending_form_submission;?>" type="text" class="form-control">
                                </div>
                            </div>
                        </div>
                    </div>

                </div>

                <!--  MY TABLE.  -->
                <div class="table-responsive" style="margin-top:5%;"> 
                    <form  method="POST">
                        <h4 style="font-weight: bold;" class="mb-3 mt-4 text-primary text-center"><?php echo $student_program_section; ?> Subjects Curriculum</h4>
                        <span style="font-size: 13px; font-weight: bold;">
                            Section Capacity:
                            <?php 
                                echo $updatedTotalStudent;
                            ?> / <?php echo $student_current_capacity;?>
                        </span>
                        
                        <table class="table table-striped table-bordered table-hover "  style="font-size:13px" cellspacing="0"  > 
                            <thead>
                                <tr class="text-center"> 
                                    <th rowspan="2">ID</th>
                                    <th rowspan="2">Code</th>
                                    <th rowspan="2">Description</th>  
                                    <th rowspan="2">Unit</th>
                                    <th colspan="4">Schedule</th> 
                                </tr>
                                <tr class="text-center">
                                    <th>Day</th>
                                    <th>Time</th>
                                    <th>Room</th> 
                                </tr>	
                            </thead> 
                                <tbody>
                                    <?php

                                        // echo $old_student_status;
                                        $listOfSubjects = $studentEnroll->GetStudentsStrandSubjects($student_username);
                                        $failed_subject_id = null;
                                        $unavaible_subject_id = null;
                                        // poll
                                        foreach ($listOfSubjects as $key => $value) {

                                            $subject_id = $value['subject_id'];
                                            $schedule_day = $value['schedule_day'] == "" ? '-' : $value['schedule_day'];
                                            $time_from = $value['time_from'] == "" ? '-' : $value['time_from'];
                                            $time_to = $value['time_to'] == "" ? '-' : $value['time_to'];
                                            $room = $value['room'] == "" ? '-' : $value['room'];

                                            // $pre_subject_id = $value['pre_subject_id'] != 0 ? $value['pre_subject_id'] : "";
                                            $failed_remark = "Failed";

                                            // echo $pre_subject_id . " ";
                                            // MUST BE REMOVED.
                                            $query_failed = $con->prepare("SELECT subject_id 

                                                FROM student_subject_grade

                                                -- AND subject_id=:subject_id
                                                WHERE remarks=:remarks
                                                AND student_id=:student_id

                                                LIMIT 1");
                                            
                                            $query_failed->bindValue(":remarks", $failed_remark);
                                            $query_failed->bindValue(":student_id", $student_id);
                                            // $query_failed->bindValue(":subject_id", $subject_id);
                                            $query_failed->execute();

                                            if($query_failed->rowCount() > 0){
                                                // echo "got " . $subject_id;
                                                $failed_subject_id = $query_failed->fetchColumn();
                                            }
                                        
                                            echo '<tr class="text-center">'; 
                                                    // echo 
                                                    // '<td  class="text-center">
                                                    //     <input name="subject_ids[]" class="checkbox"  value="'.$subject_id.'" type="checkbox">
                                                    // </td>';
                                                    echo '<td>'.$value['subject_id'].'</td>';
                                                    echo '<td>'.$value['subject_code'].'</td>';
                                                    echo '<td>'.$value['subject_title'].'</td>';
                                                    echo '<td>'.$value['unit'].'</td>';
                                                    echo '<td>'.$schedule_day.'</td>';
                                                    echo '<td>'.$time_from.' - '.$time_to.'</td>';
                                                    echo '<td>'.$room.'</td>';
                                            echo '</tr>';

                                        }
                                    ?>
                                </tbody> 
                        </table>

                        <input type="hidden" name="unique_enrollment_form_id" value="<?php echo $unique_form_id;?>">

                        <?php 

                            $checkIfCashierEvaluated = $enrollment->CheckEnrollmentCashierApproved($student_id,
                                $student_course_id, $school_year_id);
                            $checkIfRegistrarEvaluated = $enrollment->CheckEnrollmentRegistrarApproved($student_id,
                                $student_course_id, $school_year_id);
                                
                            // if($isSectionFull == true){

                            //     echo "
                            //         <button disabled class='btn btn-outline-success'>Enroll Subject</button>
                            //     ";
                            // }
                            if($isSectionFull == false 
                                && $checkIfCashierEvaluated == true
                                && $checkIfRegistrarEvaluated == true
                                ){
                                ?>
                                    <button type="submit" name="subject_load_btn" 
                                        class="btn btn-success btn-sm"
                                        onclick="return confirm('Are you sure you want to insert & enroll??')"
                                    >
                                        Approve Enrollment
                                    </button>
                                <?php
                            }
                            else if($isSectionFull == false 
                                && $checkIfRegistrarEvaluated == true
                                && $checkIfCashierEvaluated == false){
                                ?>
                                    <button type="button" class="btn btn-primary btn-sm">
                                        Waiting
                                    </button>
                                <?php
                            }

                            if($isSectionFull == true 
                            // && $current_school_year_semester != "Second"
                            ){
                                echo "
                                    <form method='POST'>
                                        <button type='submit' name='section_full_btn' class='btn btn-primary btn-sm'>
                                            Move to Available Section
                                        </button>
                                    </form>
                                ";
                            }
                        ?>

                    </form>
                </div>
            </div>
        <?php   
            
        ?>
            <head>
                <meta charset="UTF-8">
                <meta name="viewport" content="width=device-width, initial-scale=1.0">
                <!-- <link rel="stylesheet" href="../../admin//assets/css/enrollees/subject_insertion.css"> -->
                
            </head>
            <div class="col-md-12 row">

                <div class="back-menu">
                    <button type="button" class="admission-btn" onclick="find_form()">
                    <i class="bi bi-arrow-left-circle"></i> Admission
                    </button>
                </div>

                
                
                <div class="enrollment-form-container">

                    <div class="enrollment-form-upper">
                        <h3>Enrollment Form</h3>
                        <i class="fa-solid fa-ellipsis-vertical"></i>
                    </div>

                    <div class="form-upper-second-container">
                        <div class="form-inner">
                            <h5>Form ID</h5>
                            <p><?php echo $get_student_enrollment_form_id;?></p>
                        </div>
                        <div class="form-inner">
                            <h5>Admission Type</h5>
                            <p><?php echo $student_status;?></p>
                        </div>
                        <div class="form-inner">
                            <h5>Student No</h5>
                            <p><?php echo $student_unique_id;?></p>
                        </div>
                        <div class="form-inner">
                            <h5>Status</h5>
                            <p><?php echo $payment_status;?></p>
                        </div>
                        <div class="form-inner">
                            <h5>Submitted on</h5>
                            <p>
                                <?php
                                    $date = new DateTime($proccess_date);
                                    $formattedDate = $date->format('m/d/Y H:i');

                                    echo $formattedDate;
                                ?>
                            </p>
                        </div>
                    </div>

                    <div class="form-upper-third-container">
                        <div class="student-details-tab">
                            <a href="subject_insertion.php?username=<?php echo $student_username;?>&id=<?php echo $student_id;?>">
                                <button class="active">
                                    <i class="fas fa-check-circle"></i> Student Details
                                </button>
                            </a>

                        </div>

                        <div class="enrolled-subjects-tab ">
                            <a href="subject_insertion.php?enrolled_subjects=true&id=<?php echo $student_id;?>">
                                <button class="none-active">
                                        <i class="fas fa-book"></i> Enrolled Subjects
                                </button>
                            </a>
                        </div>
                    </div>

                </div>

                <div class="height-student-details">
                    <div class="student-details-container">
                        <!--student-detials-->
                        <div class="form-detailsx" id="student-form">
                            <h3>Student details</h3>
                            <table>
                                <tbody>
                                <tr>
                                    <th>Name</th>
                                    <td colspan="2"><?php echo $student_firstname;?></td>
                                    <td colspan="2"><?php echo $student_lastname;?></td>
                                    <td colspan="2"><?php echo $student_middle_name;?></td>
                                </tr>
                                <tr>
                                    <th>Birthdate</th>
                                    <td><?php 
                                        $date = new DateTime($student->GetStudentBirthdays());
                                        $formattedDate = $date->format('m/d/Y H:i');

                                        echo $formattedDate;
                                    ?></td>
                                    <th>Gender</th>
                                    <td><?php echo $student->GetStudentSex();?></td>
                                    <th>Contact no.</th>
                                    <td><?php echo $student->GetContactNumber();?></td>

                                </tr>
                                <tr>
                                    <th>Address</th>
                                    <td><?php echo $student->GetStudentAddress();?></td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                

                <div class="content">
                    <div class="back-menu">
                        <button type="button" class="admission-btn" onclick="find_form()">
                        <i class="bi bi-arrow-left-circle"></i> Find form
                        </button>
                    </div>

                    <div class="form-header ">
                        <div class="header-content">
                            <h2>Enrollment formx</h2>
                        </div>

                        <div class="student-table">
                            <table>
                                <tr>
                                <th>Form ID</th>
                                <th>Admission type</th>
                                <th>Student no</th>
                                <th>Status</th>
                                <th>Processed by system on:</th>
                                </tr>
                                <tr>
                                <td><?php echo $get_student_enrollment_form_id;?></td>
                                <td><?php echo $student_status;?></td>
                                <td><?php echo $student_unique_id;?></td>
                                    <td>
                                    <?php 
                                        echo $payment_status;
                                    ?>
                                </td>
                                <td><?php
                                    $date = new DateTime($proccess_date);
                                    $formattedDate = $date->format('m/d/Y H:i');

                                    echo $formattedDate;
                                ?></td>
                                </tr>
                            </table>
                        </div>
                    </div>

                    <div class="choices">
                        <div class="student-details">

                            <a href="subject_insertion.php?username=<?php echo $student_username;?>&id=<?php echo $student_id;?>">
                                <button style="background-color: palevioletred;"
                                    type="button"
                                    class="selection-btn"
                                    id="student-details"
                                    onclick="student_details()">
                                    <i class="bi bi-clipboard-check"></i>Student details
                                </button>
                            </a>
                        </div>
                        <div class="enrolled-subjects">

                            <a href="subject_insertion.php?enrolled_subjects=true&id=<?php echo $student_id;?>">
                                <button
                                    type="button"
                                    class="selection-btn"
                                    id="enrolled-subjects"
                                    onclick="enrolled_subjects()">
                                    <i class="bi bi-collection"></i>Enrolled subjects
                                </button>
                            </a>
                        </div>
                    </div>

                    <div class="bg-content">
                        <!--student-detials-->
                        <div class="form-details" id="student-form">
                            <h3>Student details</h3>
                            <table>
                                <tbody>
                                <tr>
                                    <th>Name</th>
                                    <td colspan="2"><?php echo $student_firstname;?></td>
                                    <td colspan="2"><?php echo $student_lastname;?></td>
                                    <td colspan="2"><?php echo $student_middle_name;?></td>
                                </tr>
                                <tr>
                                    <th>Birthdate</th>
                                    <td><?php 
                                        $date = new DateTime($student->GetStudentBirthdays());
                                        $formattedDate = $date->format('m/d/Y H:i');

                                        echo $formattedDate;
                                    ?></td>
                                    <th>Gender</th>
                                    <td><?php echo $student->GetStudentSex();?></td>
                                    <th>Contact no.</th>
                                    <td><?php echo $student->GetContactNumber();?></td>

                                </tr>
                                <tr>
                                    <th>Address</th>
                                    <td><?php echo $student->GetStudentAddress();?></td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        <?php

    }

    ## enrolled_subjects
    
    if (isset($_GET['enrolled_subjects']) 
        && $_GET['enrolled_subjects'] == true && isset($_GET['id'])) {

        $student_id = $_GET['id'];

        $student_username = $studentEnroll->GetStudentUsername($student_id);

        // $student_username = $_GET['username'];
        $old_student_status = $oldEnroll->GetOldStudentStatus($student_username);
        $studentNewEnrolee = $oldEnroll->DoesStudentNewEnrollee($student_username);
            
        $base_url = 'http://localhost/elms/admin';

        $student_fullname = $studentEnroll->GetStudentFullName($student_id);
        $student_firstname = $studentEnroll->GetStudentFirstname($student_id);
        $student_lastname = $studentEnroll->GetStudentLastname($student_id);
        $student_middle_name = $studentEnroll->GetStudentMiddlename($student_id);

        $username = $studentEnroll->GetStudentUsername($student_id);

        $student = new Student($con, $username);

        $student_new_enrollee = $student->GetStudentNewEnrollee();

        $student_admission_status = $student->GetStudentAdmissionStatus();
        
        $student_status = "";

        if($student_new_enrollee == 1){
            $student_status = "New";
        }else if($student_new_enrollee == 0 && $student_admission_status == "Standard"){
            $student_status = "Ongoing Regular";
        }
        else if($student_new_enrollee == 0 && $student_admission_status == "Transferee"){
            $student_status = "Ongoing Transferee";
        }
 

        $pending_form_submission = $pending->GetSubmittedOn($student_firstname);

        $confirmStudent = "enrolledStudent(\"$student_username\")";

        // $username2 = "(\"$username\")";
        $recommendedSubject = $studentEnroll->GetSHSNewStudentSubjectProgramBased($student_username);
        $studentCourseYear = $studentEnroll->GetStudentCourseName($student_username);
        $school_year_obj = $studentEnroll->GetActiveSchoolYearAndSemester();

        $student_course_level = $studentEnroll->GetStudentCourseLevel($student_username);


        // echo $student_course_level;
        
        $student_course_id = $studentEnroll->GetStudentCourseId($student_username);
        $student_program_id = $studentEnroll->GetStudentProgramId($student_course_id);

        // echo $student_id;
        $student_program_section = $studentEnroll->GetStudentProgramSection($student_course_id);

        $current_school_year_term = $school_year_obj['term'];
        $current_school_year_semester = $school_year_obj['period'];
        $school_year_id = $school_year_obj['school_year_id'];
    
        $forget_establish_time_url = $base_url . '/schedule/create.php';

        $unscheduledSubjectLeft = false;

        $display = "block";
        
    
        $enrollment = new Enrollment($con, $studentEnroll);
        $section = new Section($con, $student_course_id);

        $isSectionFull = $section->CheckSectionIsFull($student_course_id);


        $unique_form_id = $enrollment->GenerateEnrollmentFormId();
        $getEnrollmentNonEnrolledDate = $enrollment
            ->GetEnrollmentNonEnrolledDate($student_id, $student_course_id,$school_year_id);

        $get_student_enrollment_form_id = $enrollment->GetEnrollmentFormId($student_id, $student_course_id, $school_year_id);
        $student_unique_id = $student->GetStudentUniqueId();

        $enrollment_id = $enrollment->GetEnrollmentId($student_id,
            $student_course_id, $school_year_id);

        // echo $enrollment_id;

        // echo "enrollment_id: $enrollment_id";

        $student_section_obj = $section->GetSectionObj($student_course_id);

        $student_curren_course_program_id = $student_section_obj['program_id'];
        $student_current_course_level = $student_section_obj['course_level'];
        $student_current_capacity = $student_section_obj['capacity'];


        $program_id = $section->GetProgramIdBySectionId($student_course_id);
        $strand_name = $section->GetAcronymByProgramId($program_id);
        $track_name = $section->GetTrackByProgramId($program_id);

        $updatedTotalStudent = $section->GetTotalNumberOfStudentInSection($student_course_id,
            $school_year_id);

        $checkEnrollmentEnrolled = $enrollment->CheckEnrollmentEnrolled($student_id,
                $student_course_id, $school_year_id);

        $cashierEvaluated = $enrollment->CheckEnrollmentCashierApproved($student_id,
                $student_course_id, $school_year_id);

        $payment_status = "";

        if($checkEnrollmentEnrolled == true && $cashierEvaluated == true){
            $payment_status = "Enrolled";

        }else if($checkEnrollmentEnrolled == false && $cashierEvaluated == true){
            $payment_status = "Approved";
        }else{
            $payment_status = "Waiting";
        }

        $getEnrollmentNonEnrolledDate = $enrollment
            ->GetEnrollmentNonEnrolledDate($student_id, $student_course_id,$school_year_id);

        $getEnrollmentEnrolledDate = $enrollment
            ->GetEnrollmentEnrolledDate($student_id, $student_course_id,$school_year_id);

        $proccess_date = null;

        // echo $getEnrollmentNonEnrolledDate;

        if($checkEnrollmentEnrolled == true){
            $proccess_date = $getEnrollmentEnrolledDate;
        }else{
            $proccess_date = $getEnrollmentNonEnrolledDate;
        }

        if(isset($_POST['section_full_btn'])){

            # Get Aaailable section next to the recently full section.
            $get_prev_section = $con->prepare("SELECT course_id FROM course
                WHERE program_id=:program_id
                AND active=:active
                AND course_level=:course_level
                AND school_year_term=:school_year_term
                ORDER BY course_id DESC
                LIMIT 1");
            
            $get_prev_section->bindValue(":program_id", $student_program_id);
            $get_prev_section->bindValue(":active", "yes");
            $get_prev_section->bindValue(":course_level", $student_course_level);
            $get_prev_section->bindValue(":school_year_term", $current_school_year_term);
            $get_prev_section->execute();

            if($get_prev_section->rowCount() > 0){

                $available_course_id = $get_prev_section->fetchColumn();

                $available_section_obj = $section->GetSectionObj($available_course_id);

                $available_section_name = $available_section_obj['program_section'];

                $update_enrollment_success = $enrollment->UpdateSHSStudentEnrollmentCourseId(
                    $enrollment_id, $available_course_id);
                
                $update_student_course_id = $student->UpdateStudentCourseId(
                    $student_course_id, $student_id, $available_course_id);

                if($update_enrollment_success == true && $update_student_course_id){

                    AdminUser::success("Student section moved to: $available_section_name",
                        "subject_insertion.php?enrolled_subjects=true&id=$student_id");
                        // "subject_insertion.php?username=$student_username&id=$student_id");
                    exit();
                }

            }else{
                echo "not";
            }              

            # Update enrollment student course_id
        }

        if(isset($_POST['subject_load_btn']) 
            && isset($_POST['unique_enrollment_form_id']) 
            ){
            
            $array_success = [];

            $subject_program_id = 0;

            $unique_enrollment_form_id = $_POST['unique_enrollment_form_id'];

            $successInsertingSubjectLoad = false;

            // Update student + 1 to the total_student
            $active = "yes";
            $is_full = "no";
            $total_student = 1;

            $sql_insert = $con->prepare("INSERT INTO student_subject 
                (student_id, subject_id, school_year_id,
                    course_level, enrollment_id, subject_program_id)
                VALUES(:student_id, :subject_id, :school_year_id,
                    :course_level, :enrollment_id, :subject_program_id)");

            if($successInsertingSubjectLoad == true){
                $wasSuccess = $oldEnroll->UpdateSHSStudentStatus($student_username);
            }

            $isSuccess = false;

            $new_regular_shs_subjects = $studentEnroll->GetStudentsStrandSubjects($student_username);

            // print_r($new_regular_shs_subjects);
            
            $is_inserted_all = false;

            $subjectInitialized = false;

            foreach ($new_regular_shs_subjects as $key => $value) {
                # code...

                $subject_id = $value['subject_id'];
                $subject_program_id = $value['subject_program_id'];

                if($subject_id != 0){
                    $_SESSION['regular_subject_ids_v2'][] = array(
                        'subject_id' => $subject_id
                    );
                    $subjectInitialized = true;
                }

                // echo "redirect to receipt page";
                # Check if subjects already enrolled.

                # Insert all subjects
                $sql_insert->bindValue(":student_id", $student_id);
                $sql_insert->bindValue(":subject_id", $subject_id);
                $sql_insert->bindValue(":school_year_id", $school_year_id);
                $sql_insert->bindValue(":course_level", $student_course_level);
                $sql_insert->bindValue(":enrollment_id", $enrollment_id);
                $sql_insert->bindValue(":subject_program_id", $subject_program_id);

                if($sql_insert->execute()){

                    $is_inserted_all = true;
                }
            }
        
            if($is_inserted_all == true){

                $isSubjectCreated = false;

                # Enrolled Student.

                $wasSuccess = $oldEnroll->EnrolledStudentInTheEnrollmentv2($school_year_id,
                    $student_id, $get_student_enrollment_form_id);

                if($wasSuccess){

                    $section_obj = $section->GetSectionObj($student_course_id);

                    $sectionTotalStudent = $section->GetTotalNumberOfStudentInSection($student_course_id,
                        $school_year_id);

                    $capacity = $section_obj['capacity'];
                    $program_section = $section_obj['program_section'];
                    $course_program_id = $section_obj['program_id'];
                    $course_level = $section_obj['course_level'];
                    # Check if section is full

                    if($sectionTotalStudent >= $capacity 
                        && $current_school_year_semester == "First"){

                        # yes mark as full
                        # Update Previous Section into Is FULL.
                        $update_isfull = $section->SetSectionIsFull($student_course_id);
                        
                        $new_program_section = $section->AutoCreateAnotherSection($program_section);

                        $createNewSection = $section->CreateNewSection($new_program_section, 
                            $course_program_id, $course_level,
                            $current_school_year_term);

                        # Create Subject In that section
                        
                        if($createNewSection == true && $update_isfull == true){

                            $createNewSection_Id = $con->lastInsertId();

                            $get_subject_program = $con->prepare("SELECT * 
                            
                                FROM subject_program
                                WHERE program_id=:program_id
                                AND course_level=:course_level
                                ");

                            $get_subject_program->bindValue(":program_id", $course_program_id);
                            $get_subject_program->bindValue(":course_level", $course_level);
                            $get_subject_program->execute();

                            if($get_subject_program->rowCount() > 0){

                                $insert_section_subject = $con->prepare("INSERT INTO subject
                                    (subject_title, description, subject_program_id, unit, semester, program_id, course_level, course_id, subject_type, subject_code, pre_requisite)
                                    VALUES(:subject_title, :description, :subject_program_id, :unit, :semester, :program_id, :course_level, :course_id, :subject_type, :subject_code, :pre_requisite)");

                                while($row = $get_subject_program->fetch(PDO::FETCH_ASSOC)){

                                    $program_program_id = $row['subject_program_id'];
                                    $program_course_level = $row['course_level'];
                                    $program_semester = $row['semester'];
                                    $program_subject_type = $row['subject_type'];
                                    $program_subject_title = $row['subject_title'];
                                    $program_subject_description = $row['description'];
                                    $program_subject_unit = $row['unit'];
                                    $program_subject_pre_requisite = $row['pre_req_subject_title'];

                                    $program_subject_code = $row['subject_code'] ."-". $new_program_section; 
                                    // $program_subject_code = $row['subject_code'];

                                    $insert_section_subject->bindValue(":subject_title", $program_subject_title);
                                    $insert_section_subject->bindValue(":description", $program_subject_description);
                                    $insert_section_subject->bindValue(":subject_program_id", $program_program_id);
                                    $insert_section_subject->bindValue(":unit", $program_subject_unit);
                                    $insert_section_subject->bindValue(":semester", $program_semester);
                                    $insert_section_subject->bindValue(":program_id", $course_program_id);
                                    $insert_section_subject->bindValue(":course_level", $program_course_level);
                                    $insert_section_subject->bindValue(":course_id", $createNewSection_Id);
                                    $insert_section_subject->bindValue(":subject_type", $program_subject_type);
                                    $insert_section_subject->bindValue(":subject_code", $program_subject_code);
                                    $insert_section_subject->bindValue(":pre_requisite", $program_subject_pre_requisite);

                                    // $insert_section_subject->execute();
                                    if($insert_section_subject->execute()){
                                        $isSubjectCreated = true;
                                        // echo "New Section $new_program_section is created and student has confirmed.";
                                    }
                                }
                                // if($isSubjectCreated == true){
                                //     // echo "Successfully populated subjects in course_id $course_id";
                                // }
                            }
                        }
                    }

                    # Update student table
                    # itwill become old of the subject inserted was over.
                    // $newToOldSuccess = $oldEnroll->UpdateSHSStudentNewToOld($student_id);

                    if($isSubjectCreated == false){

                        # redirect to the receipt page.
                        if($subjectInitialized == true){
                            // echo "truee";

                            AdminUser::success("Successfully enrolled",
                                "subject_insertion.php?enrolled_subjects=true&id=$student_id");

                            // AdminUser::success("Successfully inserted the subjects, Student has been officially enrolled",
                            //     "subject_insertion.php?enrolled_success=true&id=$student_id");
                            // header("Location: ");
                            exit();
                        }
                    }

                    if($isSubjectCreated == true){

                        # redirect to the receipt page.
                        if($subjectInitialized == true){

                            AdminUser::success("Successfully inserted the subjects, Student has been officially enrolled & Section is $program_section now full",
                                "subject_insertion.php?enrolled_subjects=true&id=$student_id");

                            // AdminUser::success("Successfully inserted the subjects, Student has been officially enrolled & Section is $program_section now full",
                            //     "subject_insertion.php?enrolled_success=true&id=$student_id");
                            
                            // header("Location: ");
                            exit();
                        }
                    }

                }
            }
        }
        
        ?>
            <div class="col-md-12 row">
                
                <div class="back-menu">
                    <button type="button" class="admission-btn" onclick="find_form()">
                    <i class="bi bi-arrow-left-circle"></i> Admission
                    </button>
                </div>
                
                <div class="enrollment-form-container">

                    <div class="enrollment-form-upper">
                        <h3>Enrollment Form</h3>
                        <i class="fa-solid fa-ellipsis-vertical"></i>
                    </div>

                    <div class="form-upper-second-container">
                        <div class="form-inner">
                            <h5>Form ID</h5>
                            <p><?php echo $get_student_enrollment_form_id;?></p>
                        </div>
                        <div class="form-inner">
                            <h5>Admission Type</h5>
                            <p><?php echo $student_status;?></p>
                        </div>
                        <div class="form-inner">
                            <h5>Student No</h5>
                            <p><?php echo $student_unique_id;?></p>
                        </div>
                        <div class="form-inner">
                            <h5>Status</h5>
                            <p><?php echo $payment_status;?></p>
                        </div>
                        <div class="form-inner">
                            <h5>Submitted on</h5>
                            <p>
                                <?php
                                    $date = new DateTime($proccess_date);
                                    $formattedDate = $date->format('m/d/Y H:i');
                                    echo $formattedDate;
                                ?>
                            </p>
                        </div>
                    </div>

                    <div class="form-upper-third-container">
                        <div class="student-details-tab">
                            <a href="subject_insertion.php?username=<?php echo $student_username;?>&id=<?php echo $student_id;?>">
                                <button class="none-active">
                                    <i class="fas fa-check-circle"></i> Student Details
                                </button>
                            </a>

                        </div>

                        <div class="enrolled-subjects-tab ">
                            <a href="subject_insertion.php?enrolled_subjects=true&id=<?php echo $student_id;?>">
                                <button class="active">
                                        <i class="fas fa-book"></i> Enrolled Subjects
                                </button>
                            </a>
                        </div>
                    </div>

                </div>

                <div class="height-enrolled-details">
                    <div class="enrolled-details-container">

                        <!--enrolled-subjects-->
                        <div class="form-details-enrolled" id="enrollment-form">
                            <h3>Enrollment details</h3>

                            <div class="form-enrolled-details">
                                <div class="form-inner">
                                    <h4>S.Y</h4>
                                    <p><?php echo $current_school_year_term?></p>
                                </div>
                                <div class="form-inner">
                                    <h4>Track</h4>
                                    <p><?php echo $track_name;?></p>
                                </div>
                                <div class="form-inner">
                                    <h4>Strand</h4>
                                    <p><?php echo $strand_name; ?></p>
                                </div>
                                <div class="form-inner">
                                    <h4>Level</h4>
                                    <p><?php echo $student_course_level;?></p>
                                </div>
                                <div class="form-inner">
                                    <h4>Semester</h4>
                                    <p>
                                        <?php echo $current_school_year_semester?>
                                    </p>
                                </div>
                            </div>                          

                            <!-- <table>
                                <tbody>
                                <tr>
                                    <th>S.Y.</th>
                                    <td>2023-2024</td>
                                    <th>Track</th>
                                    <td colspan="2"><?php echo $track_name;?></td>
                                    <th>Strand</th>
                                    <td colspan="2"><?php echo $strand_name; ?></td>
                                    <th >Level</th>
                                    <td>Grade <?php echo $student_course_level;?></td>
                                    <th>Semester</th>
                                    <td><?php echo $current_school_year_semester;?></td>
                                </tr>
                                </tbody>
                            </table> -->
                        </div>

                        <div class="form-details-enrolled" id="subjects-details">
                            <h3><?php echo $student_program_section; ?> subjects</h3>

                                <span style="font-size: 13px; font-weight: bold;" class="">
                                    Section Capacity:
                                    <?php 
                                        echo $updatedTotalStudent;
                                    ?> / <?php echo $student_current_capacity;?>
                                </span>

                                <table style="font-size: 15px;"> 
                                    <thead>
                                        <tr class="text-center"> 
                                            <th style="background-color:#DCDCDC;" rowspan="2">ID</th>
                                            <th style="background-color:#DCDCDC;" rowspan="2">Code</th>
                                            <th style="background-color:#DCDCDC;" rowspan="2">Pre-Requisite</th>  
                                            <th style="background-color:#DCDCDC;" rowspan="2">Type</th>
                                            <th style="background-color:#DCDCDC;" rowspan="2">Total Units</th>
                                        </tr>
                                    </thead> 
                                        <tbody>
                                            <?php

                                                // echo $old_student_status;
                                                $listOfSubjects = $studentEnroll->GetStudentsStrandSubjects($student_username);
                                                $failed_subject_id = null;
                                                $unavaible_subject_id = null;
                                                // poll
                                                foreach ($listOfSubjects as $key => $value) {

                                                    $subject_id = $value['subject_id'];
                                                    $pre_requisite = $value['pre_requisite'];
                                                    $subject_type = $value['subject_type'];
                                                    $schedule_day = $value['schedule_day'] == "" ? '-' : $value['schedule_day'];
                                                    $time_from = $value['time_from'] == "" ? '-' : $value['time_from'];
                                                    $time_to = $value['time_to'] == "" ? '-' : $value['time_to'];
                                                    $room = $value['room'] == "" ? '-' : $value['room'];

                                                    // $pre_subject_id = $value['pre_subject_id'] != 0 ? $value['pre_subject_id'] : "";
                                                    $failed_remark = "Failed";

                                                    $query_failed = $con->prepare("SELECT subject_id 

                                                        FROM student_subject_grade

                                                        -- AND subject_id=:subject_id
                                                        WHERE remarks=:remarks
                                                        AND student_id=:student_id

                                                        LIMIT 1");
                                                    
                                                    $query_failed->bindValue(":remarks", $failed_remark);
                                                    $query_failed->bindValue(":student_id", $student_id);
                                                    // $query_failed->bindValue(":subject_id", $subject_id);
                                                    $query_failed->execute();

                                                    if($query_failed->rowCount() > 0){
                                                        // echo "got " . $subject_id;
                                                        $failed_subject_id = $query_failed->fetchColumn();
                                                    }
                                                
                                                    echo '<tr class="text-center">'; 
                                                            // echo 
                                                            // '<td  class="text-center">
                                                            //     <input name="subject_ids[]" class="checkbox"  value="'.$subject_id.'" type="checkbox">
                                                            // </td>';
                                                            echo '<td>'.$value['subject_id'].'</td>';
                                                            echo '<td>'.$value['subject_code'].'</td>';
                                                            echo '<td>'.$pre_requisite.'</td>';
                                                            echo '<td>'.$subject_type.'</td>';
                                                            echo '<td>'.$value['unit'].'</td>';
                                                    echo '</tr>';

                                                }
                                            ?>
                                        </tbody> 
                                    
                                
                                </table>
                                
                                <form method="POST">

                                    <input type="hidden" name="unique_enrollment_form_id" value="<?php echo $unique_form_id;?>">
                                    <?php 

                                        $checkIfCashierEvaluated = $enrollment->CheckEnrollmentCashierApproved($student_id,
                                            $student_course_id, $school_year_id);
                                            
                                            
                                        $checkIfRegistrarEvaluated = $enrollment->CheckEnrollmentRegistrarApproved($student_id,
                                            $student_course_id, $school_year_id);

                                        if($isSectionFull == false 
                                            && $checkIfCashierEvaluated == true 
                                            && $checkIfRegistrarEvaluated == true
                                            && $checkEnrollmentEnrolled == false){
                                            ?>

                                                <button type="submit" name="subject_load_btn" 
                                                    class="button-style-success success"
                                                    onclick="return confirm('I Agree to Approve the enrollment?')"
                                                >
                                                    Approve Enrollment
                                                </button>
                                            <?php
                                        }
                                        else if($isSectionFull == false 
                                            && $checkIfCashierEvaluated == false 
                                            && $checkIfRegistrarEvaluated == true
                                            && $checkEnrollmentEnrolled == false){
                                            ?>
                                                <button type="button" class="btn btn-primary btn-sm">
                                                    Waiting
                                                </button>
                                            <?php
                                        }
                                        else if(
                                            $checkIfCashierEvaluated == true 
                                            && $checkIfRegistrarEvaluated == true
                                            && $checkEnrollmentEnrolled == true
                                            ){
                                            ?>
                                                <button type="button" class="button-style-primary primary">
                                                    Print
                                                </button>
                                            <?php
                                        }

                                        if($isSectionFull == true 
                                            && $checkEnrollmentEnrolled == false
                                            // && $current_school_year_semester != "Second"
                                            ){
                                                echo "
                                                    <form method='POST'>
                                                        <button type='submit' name='section_full_btn' class='button-style-primary primary'>
                                                            Move to Available Section
                                                        </button>
                                                    </form>
                                                ";
                                        }
                                    ?>
                                </form>

                        </div>

                    </div>
                </div>


                <div class="content">
                    <div class="back-menu">
                        <button type="button" class="admission-btn" onclick="find_form()">
                        <i class="bi bi-arrow-left-circle"></i> Find form
                        </button>
                    </div>

                    <div class="form-header">
                        <div class="header-content">
                            <h2>Enrollment fxorm</h2>
                        </div>

                        <div class="student-table">
                            <table>
                                <tr>
                                <th>Form ID</th>
                                <th>Admission type</th>
                                <th>Student no</th>
                                <th>Status</th>
                                <th>Processed by system on:</th>
                                </tr>
                                <tr>
                                <td><?php echo $get_student_enrollment_form_id;?></td>
                                <td><?php echo $student_status;?></td>
                                <td><?php echo $student_unique_id;?></td>
                                <td>
                                    <?php 
                                        echo $payment_status;
                                    ?>
                                </td>
                                <td><?php
                                    $date = new DateTime($proccess_date);
                                    $formattedDate = $date->format('m/d/Y H:i');

                                    echo $formattedDate;
                                ?></td>
                                </tr>
                            </table>
                        </div>
                    </div>

                    <div class="choices">
                        <div class="student-details">

                            <a href="subject_insertion.php?username=<?php echo $student_username;?>&id=<?php echo $student_id;?>">
                                <button
                                    type="button"
                                    class="selection-btn"
                                    id="student-details"
                                    onclick="student_details()">
                                    <i class="bi bi-clipboard-check"></i>Student details
                                </button>
                            </a>

                        </div>
                        <div class="enrolled-subjects">

                            <a href="subject_insertion.php?enrolled_subjects=true&id=<?php echo $student_id;?>">
                                <button style="background-color: palevioletred;"
                                    type="button"
                                    class="selection-btn"
                                    id="enrolled-subjects"
                                    onclick="enrolled_subjects()">
                                    <i class="bi bi-collection"></i>Enrolled subjects
                                </button>
                            </a>
                        </div>
                    </div>

                    <div class="bg-content">
                        <!--enrolled-subjects-->
                        <div class="form-details" id="enrollment-form">
                            <h3>Enrollment details</h3>
                            <table>
                                <tbody>
                                <tr>
                                    <th>S.Y.</th>
                                    <td>2023-2024</td>
                                    <th>Track</th>
                                    <td colspan="2"><?php echo $track_name;?></td>
                                    <th>Strand</th>
                                    <td colspan="2"><?php echo $strand_name; ?></td>
                                    <th >Level</th>
                                    <td>Grade <?php echo $student_course_level;?></td>
                                    <th>Semester</th>
                                    <td><?php echo $current_school_year_semester;?></td>
                                </tr>
                                </tbody>
                            </table>
                        </div>

                        <div class="form-details" id="subjects-details">
                            <h3><?php echo $student_program_section; ?> subjects</h3>

                            
                                <span style="font-size: 13px; font-weight: bold;" class="">
                                    Section Capacity:
                                    <?php 
                                        echo $updatedTotalStudent;
                                    ?> / <?php echo $student_current_capacity;?>
                                </span>
                            

                                <table style="font-size: 15px;"> 
                                    <thead>
                                        <tr class="text-center"> 
                                            <th style="background-color:#DCDCDC;" rowspan="2">ID</th>
                                            <th style="background-color:#DCDCDC;" rowspan="2">Code</th>
                                            <th style="background-color:#DCDCDC;" rowspan="2">Pre-Requisite</th>  
                                            <th style="background-color:#DCDCDC;" rowspan="2">Type</th>
                                            <th style="background-color:#DCDCDC;" rowspan="2">Total Units</th>
                                        </tr>
                                    </thead> 
                                        <tbody>
                                            <?php

                                                // echo $old_student_status;
                                                $listOfSubjects = $studentEnroll->GetStudentsStrandSubjects($student_username);
                                                $failed_subject_id = null;
                                                $unavaible_subject_id = null;
                                                // poll
                                                foreach ($listOfSubjects as $key => $value) {

                                                    $subject_id = $value['subject_id'];
                                                    $pre_requisite = $value['pre_requisite'];
                                                    $subject_type = $value['subject_type'];
                                                    $schedule_day = $value['schedule_day'] == "" ? '-' : $value['schedule_day'];
                                                    $time_from = $value['time_from'] == "" ? '-' : $value['time_from'];
                                                    $time_to = $value['time_to'] == "" ? '-' : $value['time_to'];
                                                    $room = $value['room'] == "" ? '-' : $value['room'];

                                                    // $pre_subject_id = $value['pre_subject_id'] != 0 ? $value['pre_subject_id'] : "";
                                                    $failed_remark = "Failed";
    
                                                    $query_failed = $con->prepare("SELECT subject_id 

                                                        FROM student_subject_grade

                                                        -- AND subject_id=:subject_id
                                                        WHERE remarks=:remarks
                                                        AND student_id=:student_id

                                                        LIMIT 1");
                                                    
                                                    $query_failed->bindValue(":remarks", $failed_remark);
                                                    $query_failed->bindValue(":student_id", $student_id);
                                                    // $query_failed->bindValue(":subject_id", $subject_id);
                                                    $query_failed->execute();

                                                    if($query_failed->rowCount() > 0){
                                                        // echo "got " . $subject_id;
                                                        $failed_subject_id = $query_failed->fetchColumn();
                                                    }
                                                
                                                    echo '<tr class="text-center">'; 
                                                            // echo 
                                                            // '<td  class="text-center">
                                                            //     <input name="subject_ids[]" class="checkbox"  value="'.$subject_id.'" type="checkbox">
                                                            // </td>';
                                                            echo '<td>'.$value['subject_id'].'</td>';
                                                            echo '<td>'.$value['subject_code'].'</td>';
                                                            echo '<td>'.$pre_requisite.'</td>';
                                                            echo '<td>'.$subject_type.'</td>';
                                                            echo '<td>'.$value['unit'].'</td>';
                                                    echo '</tr>';

                                                }
                                            ?>
                                        </tbody> 
                                    
                                
                                </table>
                                <form method="POST">

                                    <input type="hidden" name="unique_enrollment_form_id" value="<?php echo $unique_form_id;?>">
                                    <?php 

                                        $checkIfCashierEvaluated = $enrollment->CheckEnrollmentCashierApproved($student_id,
                                            $student_course_id, $school_year_id);
                                            
                                            
                                        $checkIfRegistrarEvaluated = $enrollment->CheckEnrollmentRegistrarApproved($student_id,
                                            $student_course_id, $school_year_id);
    
                                        if($isSectionFull == false 
                                            && $checkIfCashierEvaluated == true 
                                            && $checkIfRegistrarEvaluated == true
                                            && $checkEnrollmentEnrolled == false){
                                            ?>
                                                <button type="submit" name="subject_load_btn" 
                                                    class="btn btn-success "
                                                    onclick="return confirm('I Agree to Approve the enrollment?')"
                                                >
                                                    Approve Enrollment
                                                </button>
                                            <?php
                                        }
                                        else if($isSectionFull == false 
                                            && $checkIfCashierEvaluated == false 
                                            && $checkIfRegistrarEvaluated == true
                                            && $checkEnrollmentEnrolled == false){
                                            ?>
                                                <button type="button" class="btn btn-primary btn-sm">
                                                    Waiting
                                                </button>
                                            <?php
                                        }
                                        else if(
                                            $checkIfCashierEvaluated == true 
                                            && $checkIfRegistrarEvaluated == true
                                            && $checkEnrollmentEnrolled == true
                                            ){
                                            ?>
                                                <button type="button" class="btn btn-primary">
                                                    Print
                                                </button>
                                            <?php
                                        }

                                        if($isSectionFull == true 
                                            && $checkEnrollmentEnrolled == false
                                            // && $current_school_year_semester != "Second"
                                            ){
                                                echo "
                                                    <form method='POST'>
                                                        <button type='submit' name='section_full_btn' class='btn btn-primary btn-sm'>
                                                            Move to Available Section
                                                        </button>
                                                    </form>
                                                ";
                                        }
                                    ?>
                                </form>

                        </div>
                    </div>
                
                </div>
            </div>
        <?php
    }

   
?>

<script>
        // window.addEventListener('load', function() {
        //     document.getElementById('select-all-checkbox').click();
        // });

        
        // document.getElementById('select-all-checkbox').addEventListener('click', function() {
        // var checkboxes = document.getElementsByClassName('checkbox');

        // for (var i = 0; i < checkboxes.length; i++) {
        //     checkboxes[i].checked = this.checked;
        // }
        // });


</script>
