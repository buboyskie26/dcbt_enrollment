
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
            /* table-layout: fixed; */
            /* border-collapse: collapse; */
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
            width: 85%;
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
    include('../../enrollment/classes/StudentEnroll.php');
    include('../../enrollment/classes/OldEnrollees.php');
    include('../../enrollment/classes/Section.php');
    include('../../enrollment/classes/Pending.php');
    include('../../enrollment/classes/Transferee.php');
    include('../../enrollment/classes/Enrollment.php');
    include('../../admin/classes/Subject.php');

    include('../../includes/classes/Student.php');
    include('../classes/Course.php');

    ?>
        <head>
            <link rel="stylesheet" href="../../admin//assets/css/enrollees/transferee_insertion.css">
        </head>
    <?php

    $enroll = new StudentEnroll($con);
    $oldEnroll = new OldEnrollees($con, $enroll);
    $transferee = new Transferee($con, $enroll);
    $enrollment = new Enrollment($con, $enroll);

    $school_year_obj = $enroll->GetActiveSchoolYearAndSemester();
    $school_year_id = $school_year_obj['school_year_id'];
    $current_school_year_term = $school_year_obj['term'];
    $current_school_year_period = $school_year_obj['period'];

    if(isset($_GET['id']) 
        && isset($_GET['details']) 
        && $_GET['details'] == "true"
        && !isset($_GET['success'])
        && !isset($_GET['enrolled_subjects'])
        
        ){
        
        $student_id = $_GET['id'];
        # For the second form
        unset($_SESSION['subject_ids']);


        $username = $enroll->GetStudentUsername($student_id);
        $student_fullname = $enroll->GetStudentFullName($student_id);

        $student = new Student($con, $username);
        
        $student_address = $student->GetStudentAddress();
        $student_contact = $student->GetGuardianNameContact();
        $admission_status = $student->GetStudentAdmissionStatus();

        $student_fullname = $enroll->GetStudentFullName($student_id);
        $student_firstname = $student->GetFirstName();
        $student_lastname = $student->GetLastName();
        $student_middle_name = $student->GetMiddleName();


        $student_course_level = $enroll->GetStudentCourseLevel($username);
        $student_course_id = $enroll->GetStudentCourseId($username);

        $section = new Section($con, $student_course_id);

        // echo $student_id;
        $student_course_level = $enroll->GetStudentCourseLevel($username);
        $student_course_id = $enroll->GetStudentCourseId($username);
        $student_program_section = $enroll->GetStudentProgramSection($student_course_id);
        $student_program_id = $enroll->GetStudentProgramId($student_course_id);

        $get_student_form_id = $enrollment->GetEnrollmentFormId($student_id, $student_course_id,$school_year_id);

        $unique_form_id = $enrollment->GenerateEnrollmentFormId();

        $section_name = $section->GetSectionName();

        $subject = new Subject($con, $registrarLoggedIn, null);
        $pending = new Pending($con);


        $checkIfCashierEvaluated = $enrollment->CheckEnrollmentCashierApproved($student_id,
            $student_course_id, $school_year_id);

        $checkIfRegisrarEvaluated = $enrollment->CheckEnrollmentRegistrarApproved($student_id,
            $student_course_id, $school_year_id);

        if(isset($_POST['transferee_subject_load_btn'])){

            // $subject_ids = isset($_POST['transferee_credited_subject_ids']) ? $_POST['transferee_credited_subject_ids'] : null;

            $is_transferee = "yes";
            $not_transferee = "no";

            $subject_ids = isset($_POST['transferee_credited_subject_ids']) ? $_POST['transferee_credited_subject_ids'] : array();

            // $asd = $_POST['transferee_subject'];
            // echo $asd;

            $insert_transferee = $con->prepare("INSERT INTO student_subject
                    (student_id, subject_id, school_year_id, course_level, is_transferee)
                    VALUES(:student_id, :subject_id, :school_year_id, :course_level, :is_transferee)
                    ");

            $passed_remark = "Passed";

            $mark_passed_transferee_subject = $con->prepare("INSERT INTO student_subject_grade
                    (student_id, subject_id, remarks, student_subject_id, is_transferee, subject_title, course_id)
                    VALUES(:student_id, :subject_id, :remarks, :student_subject_id, :is_transferee, :subject_title, :course_id)
                    ");
                
            $enrolled_transferee = $con->prepare("UPDATE enrollment
                SET  enrollment_status=:enrollment_status
                    -- course_id=:course_id
                WHERE course_id=:course_id
                AND student_id=:student_id
                ");
            
         
            // If transferee transferred as Grade 11 1st sem (FRESH)
            // It becomes Regular
            $regular_status = "Regular";
            $current_status = "Transferee";
            $old_enrollee = 0;

            $array_error = [];

            $properSubjectsInserted = false;

            $lastInsertId = null;
            $isTransfereeSubj = false;

            $studentSemesterSubjects = $oldEnroll->GetStudentCurrentSemesterSubjects($username, $current_school_year_period);

            $transfereeList = $transferee->GetTransfereeSubjectSemesterv2($username);

            // exit; // Important to exit after the redirect.

            $subject_ids = isset($_POST['transferee_credited_subject_ids']) 
                ? $_POST['transferee_credited_subject_ids'] : array();
            
            # REF
            if(!empty($subject_ids)){

                foreach ($transfereeList as $subject) {

                    $subject_id = $subject['subject_id'];
                    
                    // $_SESSION['subject_ids'][] = $subject_id;

                    $get_sub_title = $con->prepare("SELECT subject_title, course_id 

                            FROM subject
                            WHERE subject_id=:subject_id
                            LIMIT 1");

                    $get_sub_title->bindValue(":subject_id", $subject_id);
                    $get_sub_title->execute();

                    if($get_sub_title->rowCount() > 0){

                        $row = $get_sub_title->fetch(PDO::FETCH_ASSOC);
                        $subject_title = $row['subject_title'];

                        $subject_course_id = $row['course_id'];
                        echo $subject_title;
                    }

                    if (in_array($subject_id, $subject_ids)) {

                        // Checked as Credited Subjects
                        // echo "Subject ID: $subject_id is checked.<br>";
                        // echo "<br>";

                        $insert_transferee->bindValue(":student_id", $student_id);
                        $insert_transferee->bindValue(":subject_id", $subject_id);
                        $insert_transferee->bindValue(":school_year_id", $school_year_id);
                        $insert_transferee->bindValue(":course_level", $student_course_level);
                        $insert_transferee->bindValue(":is_transferee", $is_transferee);

                        if(false){
                        // if($insert_transferee->execute()){

                            // echo "Subject ID: $subject_id is checked.<br>";
                            // echo "<br>";

                            $lastInsertId = $con->lastInsertId();

                            $mark_passed_transferee_subject->bindValue(":student_id", $student_id);
                            $mark_passed_transferee_subject->bindValue(":subject_id", $subject_id);
                            $mark_passed_transferee_subject->bindValue(":remarks", $passed_remark);
                            $mark_passed_transferee_subject->bindValue(":student_subject_id", $lastInsertId);
                            $mark_passed_transferee_subject->bindValue(":is_transferee", "yes");
                            $mark_passed_transferee_subject->bindValue(":subject_title", $subject_title);
                            $mark_passed_transferee_subject->bindValue(":course_id", $subject_course_id);
                            
                            $mark_passed_transferee_subject->execute();
                        }

                    } else {
                        // The subject is unchecked
                        // Checked as not Credited Subjects

                        // echo "Subject ID: $subject_id is unchecked.<br>";
                        // echo "<br>";

                        $isTransfereeSubj = false;

                        // echo "$subject_title is now inserted.";
                        //
                        $insert_transferee->bindValue(":student_id", $student_id);
                        $insert_transferee->bindValue(":subject_id", $subject_id);
                        $insert_transferee->bindValue(":school_year_id", $school_year_id);
                        $insert_transferee->bindValue(":course_level", $student_course_level);
                        $insert_transferee->bindValue(":is_transferee", "no");

                        if(false){
                        // if($insert_transferee->execute()){

                            // Enrolled
                            $enrollment_status = "enrolled";

                            $enrolled_transferee->bindValue(":enrollment_status", $enrollment_status);
                            $enrolled_transferee->bindValue(":course_id", $student_course_id);
                            $enrolled_transferee->bindValue(":student_id", $student_id);
                            
                            if($enrolled_transferee->execute()){
                                // echo "$subject_title subject were inserted.";
                                // echo "<br>";
                                $properSubjectsInserted = true;
                            }
                        }

                    }
                }

                if($properSubjectsInserted == true){

                    $update_transferee_into_regular = $con->prepare("UPDATE student
                        SET new_enrollee=:new_enrollee
                        WHERE student_id=:student_id
                        AND student_status=:student_status");

                    $update_transferee_into_regular->bindValue(":new_enrollee", $old_enrollee);
                    $update_transferee_into_regular->bindValue(":student_id", $student_id);
                    $update_transferee_into_regular->bindValue(":student_status", "Transferee");

                    if($update_transferee_into_regular->execute()){
                        
                        // echo "Transferee student becomes Regular.";
                        // echo "New Transferee enrolee becomes Old student.";

                        echo "<br>";
                    }
                }
            }

            $subject_ids = isset($_POST['transferee_credited_subject_ids']) ? $_POST['transferee_credited_subject_ids'] : array();

            # For Next Proceed.
            if(!empty($subject_ids)){
                $subjectInitialized = false;

                foreach ($transfereeList as $subject) {

                    $subject_id = $subject['subject_id'];
                    
                    if (in_array($subject_id, $subject_ids)) {

                        $_SESSION['subject_ids'][] = array(
                            'subject_id' => $subject_id,
                            'status' => 'checked'
                        );

                        $subjectInitialized = true;
                    } else {
                        // Checked as not Credited Subjects
                        // echo "Subject ID: $subject_id is unchecked.<br>";
                        // echo "<br>";

                        $_SESSION['subject_ids'][] = array(
                            'subject_id' => $subject_id,
                            'status' => 'unchecked'
                        );

                        $subjectInitialized = true;
                    }
                }
                if($subjectInitialized == true && $properSubjectsInserted == true){

                    // header("Location: transferee_insertion.php?inserted=success&id=" . $student_id);
                    // exit();
                }

            }
           
        }


        $my_course_subjects = [];

        $course_section_subjects = $enroll->
            GetStudentsStrandSubjectsPerLevelSemester($username);


        $get_student_enrollment_form_id = $enrollment->GetEnrollmentFormId($student_id, $student_course_id, $school_year_id);

        $pending_form_submission = $pending->GetSubmittedOn($student_firstname);

        $student_section_obj = $section->GetSectionObj($student_course_id);

        $student_curren_course_program_id = $student_section_obj['program_id'];
        $student_current_course_level = $student_section_obj['course_level'];
        $student_current_capacity = $student_section_obj['capacity'];
        $student_unique_id = $student->GetStudentUniqueId();

        $updatedTotalStudent = $section->GetTotalNumberOfStudentInSection($student_course_id,
            $school_year_id);

        $isSectionFull = $section->CheckSectionIsFull($student_course_id);
        
        $checkEnrollmentEnrolled = $enrollment->CheckEnrollmentEnrolled($student_id,
                $student_course_id, $school_year_id);

        $cashierEvaluated = $enrollment->CheckEnrollmentCashierApproved($student_id,
                $student_course_id, $school_year_id);


        $registrarEvaluated = $enrollment->CheckEnrollmentRegistrarApproved($student_id,
                $student_course_id, $school_year_id);

        $payment_status = "";

        if($checkEnrollmentEnrolled == true 
            && $cashierEvaluated == true && $registrarEvaluated == true){

            $payment_status = "Enrolled";

        }else if($checkEnrollmentEnrolled == false 
            && $cashierEvaluated == true && $registrarEvaluated == true){

            $payment_status = "Approved";
            
        }else if($checkEnrollmentEnrolled == false 
            && $cashierEvaluated == false && $registrarEvaluated == true){

            $payment_status = "Waiting Payment";
        }

        else if($checkEnrollmentEnrolled == false 
            && $registrarEvaluated == false
            && $cashierEvaluated == false){

            $payment_status = "Evaluation";

        }

        $getEnrollmentNonEnrolledDate = $enrollment
            ->GetEnrollmentNonEnrolledDate($student_id, $student_course_id,$school_year_id);

        $getEnrollmentEnrolledDate = $enrollment
            ->GetEnrollmentEnrolledDate($student_id, $student_course_id,$school_year_id);

        $proccess_date = null;

        if($checkEnrollmentEnrolled == true){
            $proccess_date = $getEnrollmentEnrolledDate;
        }else{
            $proccess_date = $getEnrollmentNonEnrolledDate;
        }

        
        ?>
            <div class="col-md-12 row">


            <!--  -->

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
                        <p><?php echo $admission_status;?></p>
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

                        <a href="transferee_insertion.php?details=true&id=<?php echo $student_id?>">
                            <button class="active">
                                <i class="fas fa-check-circle"></i> Student Details
                            </button>
                        </a>

                    </div>

                    <div class="enrolled-subjects-tab ">
                        <a href="transferee_insertion.php?enrolled_subjects=true&id=<?php echo $student_id?>">

                        <!-- <a href="subject_insertion.php?enrolled_subjects=true&id=<?php echo $student_id;?>"> -->
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
                

            <!--  -->


                <div class="content">
                    <div class="form-header ">
                        <div class="header-content">
                            <h2>Enrollment form</h2>
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
                            <td><?php echo $admission_status;?></td>
                            <td><?php echo $student_unique_id; ?></td>
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

                            <a href="transferee_insertion.php?details=true&id=<?php echo $student_id?>">
                            
                                <button style="background-color: palevioletred;"
                                    type="button"
                                    class="selection-btn"
                                    id="student-details">
                                    <i class="bi bi-clipboard-check"></i>Student details
                                </button>
                            </a>

                        </div>
                        <div class="enrolled-subjects">
                            <a href="transferee_insertion.php?enrolled_subjects=true&id=<?php echo $student_id?>">
                                <button
                                    type="button"
                                    class="selection-btn"
                                    id="enrolled-subjects">
                                    <i class="bi bi-collection"></i>Enrolled subjects
                                </button>
                            </a>
                        </div>
                    </div>

                    <div class="bg-content">

                        <div style="
                            display: flex;
                            flex-direction: column;
                            justify-content: center;
                            text-align: center;
                            align-items: center;
                            " class="student_details_nav">
                            <div class="form-details" id="student-form">
                                <h3>Student Details</h3>

                                <table>
                                    <tbody>
                                    <tr>
                                        <th></th>
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

                    <div style="display: none;" class="container">
                        <h4 class="text-center text-primary">Enrollment Details</h4>
                        <hr>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-2 form-group">
                                    <label class="mb-2" for="">Enrollment ID</label>
                                    <input readonly value="<?php echo $get_student_form_id;?>" type="text" class="form-control">
                                </div>
                                <div class="mb-2 form-group">
                                    <label class="mb-2" for="">Name</label>
                                    <input readonly value="<?php echo $student_fullname; ?>" type="text" class="form-control">
                                </div>
                             
                                <div class="mb-2 form-group">
                                    <label class="mb-2" for="">Status</label>
                                    <input readonly value="Transferee" type="text" class="form-control">
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="mb-2 form-group">
                                    <label class="mb-2" for="">Program & Section</label>
                                    <input readonly value="<?php echo $student_program_section ?>" type="text" class="form-control">
                                </div>
                                <div class="mb-2 form-group">
                                    <label class="mb-2" for="">Semester</label>
                                    <input readonly value="<?php echo $current_school_year_period ?>" type="text" class="form-control">
                                </div>
                                <div class="mb-2 form-group">
                                    <label class="mb-2" for="">Academic Year</label>
                                    <input readonly value="<?php echo $current_school_year_term ?>" type="text" class="form-control">
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        <?php
    }

    if (isset($_GET['enrolled_subjects']) 
        && $_GET['enrolled_subjects'] == "true" && isset($_GET['id'])) {

        $student_id = $_GET['id'];
        # For the second form
        unset($_SESSION['subject_ids']);


        $username = $enroll->GetStudentUsername($student_id);
        $student_fullname = $enroll->GetStudentFullName($student_id);


        $student = new Student($con, $username);
        
        $student_address = $student->GetStudentAddress();
        $student_contact = $student->GetGuardianNameContact();
        $admission_status = $student->GetStudentAdmissionStatus();

        $student_fullname = $enroll->GetStudentFullName($student_id);
        $student_firstname = $student->GetFirstName();
        $student_lastname = $student->GetLastName();
        $student_middle_name = $student->GetMiddleName();
        $student_unique_id = $student->GetStudentUniqueId();


        $student_course_level = $enroll->GetStudentCourseLevel($username);
        $student_course_id = $enroll->GetStudentCourseId($username);

        $section = new Section($con, $student_course_id);

        // echo $student_id;
        $student_course_level = $enroll->GetStudentCourseLevel($username);
        $student_course_id = $enroll->GetStudentCourseId($username);
        $student_program_section = $enroll->GetStudentProgramSection($student_course_id);
        $student_program_id = $enroll->GetStudentProgramId($student_course_id);

        $get_student_form_id = $enrollment->GetEnrollmentFormId($student_id, $student_course_id,$school_year_id);

        $unique_form_id = $enrollment->GenerateEnrollmentFormId();
        
        $getEnrollmentNonEnrolledDate = $enrollment
            ->GetEnrollmentNonEnrolledDate($student_id, $student_course_id,$school_year_id);

        $section_name = $section->GetSectionName();

        $subject = new Subject($con, $registrarLoggedIn, null);
        $pending = new Pending($con);


        $checkIfCashierEvaluated = $enrollment->CheckEnrollmentCashierApproved($student_id,
            $student_course_id, $school_year_id);

        $checkIfRegisrarEvaluated = $enrollment->CheckEnrollmentRegistrarApproved($student_id,
            $student_course_id, $school_year_id);



        $my_course_subjects = [];

        $course_section_subjects = $enroll->
            GetStudentsStrandSubjectsPerLevelSemester($username);


        $get_student_enrollment_form_id = $enrollment->GetEnrollmentFormId($student_id, $student_course_id, $school_year_id);
        $pending_form_submission = $pending->GetSubmittedOn($student_firstname);


        $student_section_obj = $section->GetSectionObj($student_course_id);

        $student_curren_course_program_id = $student_section_obj['program_id'];
        $student_current_course_level = $student_section_obj['course_level'];
        $student_current_capacity = $student_section_obj['capacity'];

        $updatedTotalStudent = $section->GetTotalNumberOfStudentInSection($student_course_id,
            $school_year_id);

        $isSectionFull = $section->CheckSectionIsFull($student_course_id);

        
        $program_id = $section->GetProgramIdBySectionId($student_course_id);
        $strand_name = $section->GetAcronymByProgramId($program_id);
        $track_name = $section->GetTrackByProgramId($program_id);

        $checkEnrollmentEnrolled = $enrollment->CheckEnrollmentEnrolled($student_id,
                $student_course_id, $school_year_id);

        $cashierEvaluated = $enrollment->CheckEnrollmentCashierApproved($student_id,
                $student_course_id, $school_year_id);

        $registrarEvaluated = $enrollment->CheckEnrollmentRegistrarApproved($student_id,
                $student_course_id, $school_year_id);

        $payment_status = "";

        if($checkEnrollmentEnrolled == true 
            && $cashierEvaluated == true && $registrarEvaluated == true){

            $payment_status = "Enrolled";

        }else if($checkEnrollmentEnrolled == false 
            && $cashierEvaluated == true && $registrarEvaluated == true){

            $payment_status = "Approved";
            
        }else if($checkEnrollmentEnrolled == false 
            && $cashierEvaluated == false && $registrarEvaluated == true){

            $payment_status = "Waiting Payment";
        }

        else if($checkEnrollmentEnrolled == false 
            && $registrarEvaluated == false
            && $cashierEvaluated == false){

            $payment_status = "Evaluation";

        }


        $getEnrollmentNonEnrolledDate = $enrollment
            ->GetEnrollmentNonEnrolledDate($student_id, $student_course_id,$school_year_id);

        $getEnrollmentEnrolledDate = $enrollment
            ->GetEnrollmentEnrolledDate($student_id, $student_course_id,$school_year_id);

        $proccess_date = null;

        if($checkEnrollmentEnrolled == true){
            $proccess_date = $getEnrollmentEnrolledDate;
        }else{
            $proccess_date = $getEnrollmentNonEnrolledDate;
        }

        if(isset($_POST['inserted_transferee_subject_btn'])){

            $transfereeSubjects = $con->prepare("SELECT 
                t1.is_transferee, t1.is_final,
                t1.student_subject_id,
                t1.school_year_id,

                t2.* 
                FROM student_subject as t1

                INNER JOIN subject as t2 ON t2.subject_id = t1.subject_id
                LEFT JOIN student_subject_grade as t3 ON t3.student_subject_id = t1.student_subject_id

                WHERE t1.student_id=:student_id
                AND t1.is_final=0
                AND t1.school_year_id=:school_year_id");

            $transfereeSubjects->bindValue(":student_id", $student_id);
            $transfereeSubjects->bindValue(":school_year_id", $school_year_id);
            $transfereeSubjects->execute();

            if($transfereeSubjects->rowCount() > 0){
                $final = 1;
                $isSuccess = false;

                $update = $con->prepare("UPDATE student_subject
                    SET is_final=:is_final
                    WHERE student_subject_id=:student_subject_id
                    AND student_id=:student_id
                    AND school_year_id=:school_year_id");

                while($row = $transfereeSubjects->fetch(PDO::FETCH_ASSOC)){

                    $student_subject_id = $row['student_subject_id'];

                    $update->bindParam(":is_final", $final);
                    $update->bindParam(":student_subject_id", $student_subject_id);
                    $update->bindParam(":student_id", $student_id);
                    $update->bindParam(":school_year_id", $school_year_id);
                    $update->execute();
                    $isSuccess = true;
                }


                if($isSuccess == true){

                    $enrolledSuccess = $oldEnroll->EnrolledStudentInTheEnrollment($school_year_id,
                        $student_id);

                    // $newToOld = $oldEnroll->UpdateSHSStudentNewToOld($student_id);

                    if($enrolledSuccess){

                        $section_obj = $section->GetSectionObj($student_course_id);

                        $sectionTotalStudent = $section->GetTotalNumberOfStudentInSection($student_course_id,
                            $school_year_id);

                        $capacity = $section_obj['capacity'];

                        $isSubjectCreated = false;

                        if($sectionTotalStudent >= $capacity 
                            && $current_school_year_period == "First"){

                            $program_section = $section_obj['program_section'];
                            $course_program_id = $section_obj['program_id'];
                            $course_level = $section_obj['course_level'];


                            $update_isfull = $section->SetSectionIsFull($student_course_id);
                            
                            $new_program_section = $section->AutoCreateAnotherSection($program_section);

                            $createNewSection = $section->CreateNewSection($new_program_section, 
                                $course_program_id, $course_level,
                                $current_school_year_term);

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

                        if($isSubjectCreated == true){

                            AdminUser::success("Successfully inserted the subjects, Student has been officially enrolled & Section is $program_section now full",
                                "transferee_insertion.php?enrolled_subjects=true&id=$student_id");

                            exit();
                        }

                        if($isSubjectCreated == false){

                            AdminUser::success("Student successfully enrolled",
                                "transferee_insertion.php?enrolled_subjects=true&id=$student_id");
                            exit();
                        }

                        // AdminUser::success("Student successfully enrolled", "transferee_insertion.php?enrolled_success=true&id=$student_id");
                    }   
                }
            }
        }


        ?>
            <div class="col-md-12 row">
                 
                <!--  -->
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
                            <p><?php echo $admission_status;?></p>
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

                            <a href="transferee_insertion.php?details=true&id=<?php echo $student_id?>">
                                <button class="none-active">
                                    <i class="fas fa-check-circle"></i> Student Details
                                </button>
                            </a>

                        </div>

                        <div class="enrolled-subjects-tab ">
                            <a href="transferee_insertion.php?enrolled_subjects=true&id=<?php echo $student_id?>">
                            <!-- <a href="subject_insertion.php?enrolled_subjects=true&id=<?php echo $student_id;?>"> -->
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
                            <h3>Enrollment Details</h3>

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
                                        <?php echo $current_school_year_period?>
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
                             

                            <?php

                                if($checkEnrollmentEnrolled == false){
                                    ?>
                                        <!-- SELECTED SUBJECTS -->

                                            <h3><?php echo $section_name; ?> Subjects</h3>
                                            <span class="">
                                                Section Capacity:
                                                <?php 
                                                    echo $updatedTotalStudent;
                                                ?> / <?php echo $student_current_capacity;?>
                                            </span>
                                            <table>
                                                <thead>
                                                    <tr class="text-center"> 
                                                        <th rowspan="2">ID</th>
                                                        <th rowspan="2">Section</th>
                                                        <th rowspan="2">Code</th>
                                                        <th rowspan="2">Description</th>  
                                                        <th rowspan="2">Type</th>
                                                        <th rowspan="2">Unit</th>
                                                </thead> 
                                                <tbody>
                                                    <?php

                                                        # For Pending Grade 11 1st Semester Only
                                                        $semester = "First";

                                                        $transfereeSubjects = $con->prepare("SELECT 
                                                            t1.is_transferee, t1.is_final,
                                                            t1.student_subject_id as t2_student_subject_id, 
                                                            t3.student_subject_id as t3_student_subject_id,
                                                            t2.*,
                                                            t4.program_section
                                                            
                                                            
                                                            FROM student_subject as t1

                                                            INNER JOIN subject as t2 ON t2.subject_id = t1.subject_id
                                                            LEFT JOIN student_subject_grade as t3 ON t3.student_subject_id = t1.student_subject_id
                                                            LEFT JOIN course as t4 ON t4.course_id = t2.course_id

                                                            WHERE t1.student_id = :student_id
                                                            AND t1.is_final = 0
                                                            AND t1.school_year_id = :school_year_id
                                                            AND t2.course_id = :course_id");


                                                            $transfereeSubjects->bindValue(":student_id", $student_id);
                                                            $transfereeSubjects->bindValue(":school_year_id", $school_year_id);
                                                            $transfereeSubjects->bindValue(":course_id", $student_course_id);
                                                            $transfereeSubjects->execute();

                                                            $totalUnits = 0;

                                                            if($transfereeSubjects != null){
                                                                $applyTabIsAvailable = true;

                                                                foreach ($transfereeSubjects as $key => $row) {

                                                                    $subject_course_id = $row['course_id'];

                                                                    $unit = $row['unit'];
                                                                    $subject_id = $row['subject_id'];
                                                                    $program_section = $row['program_section'];
                                                                    // $program_section = "";

                                                                    if($subject_course_id == $student_course_id){
                                                                        array_push($my_course_subjects, $subject_id);
                                                                    }

                                                                    $subject_title = $row['subject_title'];
                                                                    $course_id = $row['course_id'];
                                                                    $subject_code = $row['subject_code'];
                                                                    $subject_type = $row['subject_type'];
                                                                    $semester = $row['semester'];
                                                                    $course_level = $row['course_level'];
                                                                    $is_transferee = $row['is_transferee'];

                                                                    // $is_final = $row['is_final'];

                                                                    $totalUnits += $unit;

                                                                    $text = "";

                                                                    $student_subject_id = $row['t2_student_subject_id'];
                                                                
                                                                    echo "
                                                                        <tr class='text-center'>
                                                                            <td>$subject_id</td>
                                                                            <td>$program_section</td>
                                                                            <td>$subject_code</td>
                                                                            <td>$subject_title</td>
                                                                            <td>$subject_type</td>
                                                                            <td>$unit</td>
                                                                        </tr>
                                                                    ";
                                                                }
                                                            } 
                                                    ?>
                                                </tbody>
                                                <?php
                                                    if($totalUnits != 0){
                                                        ?>
                                                        <tr class="text-center">
                                                            <td colspan="5"  style="font-weight:bold;text-align: right;" >Total Units</td>
                                                            <td><?php echo $totalUnits;?></td>
                                                        </tr> 
                                                        <?php
                                                    }
                                                ?>
                                                
                                            </table>
                                    <?php 
                                }
                            ?>
                                


                        </div>

                        <div class="form-details-enrolled" id="subjects-details">

                            <h3>Added Subjects</h3>
                            <table>
                                <thead>
                                    <tr class="text-center"> 
                                        <th rowspan="2">ID</th>
                                        <th rowspan="2">Section</th>
                                        <th rowspan="2">Code</th>
                                        <th rowspan="2">Description</th>  
                                        <th rowspan="2">Type</th>
                                        <th rowspan="2">Unit</th>
                                </thead> 
                                    <tbody>
                                        <?php 

                                            // $addedSubjects = $subject->GetTransfereeAddedSubject($student_id,
                                            //     $current_school_year_id, $selected_course_id);

                                            $addedSubjects = $con->prepare("SELECT 
                                                t1.is_transferee, t1.is_final,
                                                t1.student_subject_id as t2_student_subject_id, 
                                                t3.student_subject_id as t3_student_subject_id,

                                                t4.program_section,
                                                t2.* FROM student_subject as t1

                                                INNER JOIN subject as t2 ON t2.subject_id = t1.subject_id
                                                LEFT JOIN course as t4 ON t4.course_id = t2.course_id
                                                LEFT JOIN student_subject_grade as t3 ON t3.student_subject_id = t1.student_subject_id

                                                WHERE t1.student_id=:student_id
                                                AND t1.is_final=0
                                                AND t1.school_year_id=:school_year_id
                                                AND t2.course_id!=:course_id

                                                ");

                                            $addedSubjects->bindValue(":student_id", $student_id);
                                            $addedSubjects->bindValue(":school_year_id", $school_year_id);
                                            $addedSubjects->bindValue(":course_id", $student_course_id);
                                            $addedSubjects->execute();

                                            if($addedSubjects->rowCount() > 0){

                                                while($row = $addedSubjects->fetch(PDO::FETCH_ASSOC)){

                                                    $subject_id = $row['subject_id'];
                                                    $subject_code = $row['subject_code'];
                                                    $subject_title = $row['subject_title'];
                                                    $pre_requisite = $row['pre_requisite'];
                                                    $subject_type = $row['subject_type'];
                                                    $unit = $row['unit'];
                                                    $course_level = $row['course_level'];
                                                    $program_section = $row['program_section'];
                                                    $program_section = $row['program_section'];
                                                    $student_subject_id = $row['t2_student_subject_id'];

                                                    echo "
                                                        <tr class='text-center'>
                                                            <td>$subject_id</td>
                                                            <td>$program_section</td>
                                                            <td>$subject_code</td>
                                                            <td>$subject_title</td>
                                                            <td>$unit</td>
                                                            <td>$pre_requisite</td>
                                                            <td>$subject_type</td>
                                                            
                                                        </tr>
                                                    ";
                                                }

                                            }
                                        ?>

                                    </tbody>
                                <?php
                                    if($totalUnits != 0){
                                        ?>
                                        <tr class="text-center">
                                            <td colspan="5"  style="font-weight:bold;text-align: right;" >Total Units</td>
                                            <td><?php echo $totalUnits;?></td>
                                        </tr> 
                                        <?php
                                    }
                                ?>
                                
                            </table>

                            
                        </div>



                    </div>

                    <div class="asd">
                        <form action="" method="POST">

                            <?php 
                            
                                if($checkIfCashierEvaluated == false && $checkIfRegisrarEvaluated){
                                    echo "
                                        <button type='button' class='btn btn-outline-primary'>Wait for Payment</button>
                                    ";
                                }
                                else if($checkIfCashierEvaluated && $checkIfRegisrarEvaluated){
                                    ?>
                                        <button type="submit" name="inserted_transferee_subject_btn"
                                            id="inserted_transferee_subject_btn"
                                            onclick="return confirm('Are you sure you want to insert.?')"
                                            class="button-style-success success">Approve Enrollment
                                        </button>
                                    <?php
                                }

                                $checkIfCashierEvaluated = $enrollment->CheckEnrollmentCashierApproved($student_id,
                                    $student_course_id, $school_year_id);
                                    
                                    
                                $checkIfRegistrarEvaluated = $enrollment->CheckEnrollmentRegistrarApproved($student_id,
                                    $student_course_id, $school_year_id);

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


                <!--  -->
                <div class="content">
                    <div class="form-header ">
                        <div class="header-content">
                            <h2>Enrollment form</h2>
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
                            <td><?php echo $admission_status; ?></td>

                            <td><?php echo $student_unique_id; ?></td>
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

                            <a href="transferee_insertion.php?details=true&id=<?php echo $student_id?>">
                            
                                <button
                                    type="button"
                                    class="selection-btn"
                                    id="student-details">
                                    <i class="bi bi-clipboard-check"></i>Student details
                                </button>
                            </a>

                        </div>
                        <div class="enrolled-subjects">
                            <a href="transferee_insertion.php?enrolled_subjects=true&id=<?php echo $student_id?>">
                                <button style="background-color: palevioletred;"
                                    type="button"
                                    class="selection-btn"
                                    id="enrolled-subjects">
                                    <i class="bi bi-collection"></i>Enrolled subjects
                                </button>
                            </a>

                        </div>
                    </div>

                    <div class="bg-content">

                        <div style="
                            display: flex;
                            flex-direction: column;
                            justify-content: center;
                            text-align: center;
                            align-items: center;" class="enrolled_nav">

                            <div class="form-details" id="enrollment-form">
                                <h3>Enrollment details</h3>

                                <table>
                                    <tbody>
                                    <tr>
                                        <th>S.Y.</th>
                                        <td><?php echo $current_school_year_term;?></td>
                                        <th>Track</th>
                                        <td colspan="2"><?php echo $track_name;?></td>
                                        <th>Strand</th>
                                        <td colspan="2"><?php echo $strand_name; ?></td>
                                        <th >Level</th>
                                        <td>Grade <?php echo $student_course_level;?></td>
                                        <th>Semester</th>
                                        <td><?php echo $current_school_year_period;?></td>
                                    </tr>
                                    </tbody>
                                </table>
                            </div>

                            <?php 
                                if($checkEnrollmentEnrolled == true){

                                    ?>
                                        <!-- SELECTED SUBJECTS -->
                                        <div class="form-details">

                                            <h4><?php echo $section_name; ?> Subjects</h4>

                                            <table id="dash-table" 
                                                class="table table-striped table-hover table-responsive" 
                                                style="font-size:14px" cellspacing="0">
                                                <thead>
                                                    <tr class="text-center"> 
                                                        <th rowspan="2">ID</th>
                                                        <th rowspan="2">Section</th>
                                                        <th rowspan="2">Code</th>
                                                        <th rowspan="2">Description</th>  
                                                        <th rowspan="2">Type</th>
                                                        <th rowspan="2">Unit</th>
                                                </thead> 
                                                <tbody>
                                                    <?php

                                                        # For Pending Grade 11 1st Semester Only
                                                        $semester = "First";

                                                        $transfereeSubjects = $con->prepare("SELECT 
                                                            t1.is_transferee, t1.is_final,
                                                            t1.student_subject_id as t2_student_subject_id, 
                                                            t3.student_subject_id as t3_student_subject_id,
                                                            t2.*,
                                                            t4.program_section
                                                            
                                                            
                                                            FROM student_subject as t1

                                                            INNER JOIN subject as t2 ON t2.subject_id = t1.subject_id
                                                            LEFT JOIN student_subject_grade as t3 ON t3.student_subject_id = t1.student_subject_id
                                                            LEFT JOIN course as t4 ON t4.course_id = t2.course_id

                                                            WHERE t1.student_id = :student_id
                                                            AND t1.is_final = 1
                                                            AND t1.school_year_id = :school_year_id
                                                            AND t1.is_transferee = :is_transferee
                                                            AND t2.course_id = :course_id");


                                                            $transfereeSubjects->bindValue(":student_id", $student_id);
                                                            $transfereeSubjects->bindValue(":school_year_id", $school_year_id);
                                                            $transfereeSubjects->bindValue(":is_transferee", "no");
                                                            $transfereeSubjects->bindValue(":course_id", $student_course_id);
                                                            $transfereeSubjects->execute();

                                                                $totalUnits = 0;

                                                                if($transfereeSubjects != null){
                                                                    $applyTabIsAvailable = true;

                                                                    foreach ($transfereeSubjects as $key => $row) {

                                                                        $subject_course_id = $row['course_id'];

                                                                        $unit = $row['unit'];
                                                                        $subject_id = $row['subject_id'];
                                                                        $program_section = $row['program_section'];
                                                                        // $program_section = "";

                                                                        if($subject_course_id == $student_course_id){
                                                                            array_push($my_course_subjects, $subject_id);
                                                                        }

                                                                        $subject_title = $row['subject_title'];
                                                                        $course_id = $row['course_id'];
                                                                        $subject_code = $row['subject_code'];
                                                                        $subject_type = $row['subject_type'];
                                                                        $semester = $row['semester'];
                                                                        $course_level = $row['course_level'];
                                                                        $is_transferee = $row['is_transferee'];

                                                                        // $is_final = $row['is_final'];

                                                                        $totalUnits += $unit;

                                                                        $text = "";

                                                                        $student_subject_id = $row['t2_student_subject_id'];
                                                                    
                                                                        echo "
                                                                            <tr class='text-center'>
                                                                                <td>$subject_id</td>
                                                                                <td>$program_section</td>
                                                                                <td>$subject_code</td>
                                                                                <td>$subject_title</td>
                                                                                <td>$subject_type</td>
                                                                                <td>$unit</td>
                                                                            </tr>
                                                                        ";
                                                                    }
                                                                } 
                                                            ?>
                                                        </tbody>
                                                        <?php
                                                            if($totalUnits != 0){
                                                                ?>
                                                                <tr class="text-center">
                                                                    <td colspan="5"  style="font-weight:bold;text-align: right;" >Total Units</td>
                                                                    <td><?php echo $totalUnits;?></td>
                                                                </tr> 
                                                                <?php
                                                            }
                                                    ?>
                                            </table>

                                            <!--  REMOVED SUBJECTS -->
                                            <?php

                                                $result = array_diff($course_section_subjects, $my_course_subjects);
                                            
                                                if (empty($result)) {
                                                    echo "
                                                        <h3 class='text-center text-info'>No Removed Subject .</h3>
                                                    ";
                                                }else{
                                                    ?>
                                                        <h5 class="text-warning text-center">Removed Subjects</h5>
                                                        <!-- REMOVE SUBJECTS -->
                                                        <table id="" class="table table-striped table-hover "  style="font-size:13px" cellspacing="0"  > 
                                                            <thead>
                                                                <tr class="text-center"> 
                                                                    <th rowspan="2">Id</th>
                                                                    <th rowspan="2">Code</th>
                                                                    <th rowspan="2">Description</th>
                                                                    <th rowspan="2">Unit</th>
                                                                    <th rowspan="2">Type</th>
                                                                </tr>	
                                                            </thead> 	
                                                            <tbody>
                                                                <?php

                                                                    // print_r($my_course_subjects);

                                                                    $subjectIds = implode(',', $my_course_subjects);

                                                                    $sql = $con->prepare("SELECT * FROM 
                                                                    
                                                                        subject as t1

                                                                        WHERE t1.subject_id NOT IN ($subjectIds)
                                                                        -- WHERE t1.student_id=:student_id
                                                                        AND t1.course_id=:course_id
                                                                        AND t1.course_level=:course_level
                                                                        AND t1.semester=:semester

                                                                        ");

                                                                    $sql->bindValue(":course_id", $student_course_id);
                                                                    $sql->bindValue(":course_level", $course_level);
                                                                    $sql->bindValue(":semester", $current_school_year_period);
                                                                    $sql->execute();
                                                                
                                                                    $totalUnits = 0;
                                                                
                                                                    if($sql->rowCount() > 0){
                                                                        
                                                                        while($row = $sql->fetch(PDO::FETCH_ASSOC)){

                                                                            $subject_id = $row['subject_id'];
                                                                            $subject_code = $row['subject_code'];
                                                                            $subject_title = $row['subject_title'];
                                                                            $unit = $row['unit'];
                                                                            $subject_type = $row['subject_type'];
                                                                            // $is_transferee = $row['is_transferee'];

                                                                            $totalUnits += $unit;

                                                                            $status = "Ongoing";
                                                                            
                                                                            echo "
                                                                                <tr class='text-center'>
                                                                                    <td>$subject_id</td>
                                                                                    <td>$subject_code</td>
                                                                                    <td>$subject_title</td>
                                                                                    <td>$unit</td>
                                                                                    <td>$subject_type</td>
                                                                                </tr>
                                                                            ";
                                                                        }
                                                                    }
                                                                ?>
                                                                <tr class="text-center">
                                                                    <td colspan="3"  style="text-align: right;" >Remove Units</td>
                                                                    <td><?php echo $totalUnits;?></td>
                                                                </tr> 
                                                            </tbody>
                                                        </table>
                                                    <?php
                                                }
                                            ?>

                                        </div>

                                        <!--  ADDED SUBJECTS -->
                                        <div class="form-details">

                                            <h4 class="mb-3 text-muted">Added Subjects</h4>
                                            <?php 
                                                if(count($subject->GetNewTransfereeEnrolledAddedSubject($student_id,
                                                    $school_year_id, $student_course_id)) > 0){
                                                        
                                                        ?>
                                                            <table class="table table-hover "  style="font-size:13px" cellspacing="0"> 
                                                                <thead>
                                                                    <tr class="text-center"">
                                                                        <th>Id</th>
                                                                        <th>Section</th>
                                                                        <th>Code</th>
                                                                        <th>Description</th>
                                                                        <th>Unit</th>
                                                                        <th>Pre-Requisite</th>
                                                                        <th>Type</th>
                                                                    </tr>
                                                                </thead>

                                                                <tbody>
                                                                    <?php 

                                                                        // $addedSubjects = $subject->GetTransfereeAddedSubject($student_id,
                                                                        //     $current_school_year_id, $selected_course_id);

                                                                        $addedSubjects = $con->prepare("SELECT 
                                                                            t1.is_transferee, t1.is_final,
                                                                            t1.student_subject_id as t2_student_subject_id, 
                                                                            t3.student_subject_id as t3_student_subject_id,

                                                                            t4.program_section,
                                                                            t2.* FROM student_subject as t1

                                                                            INNER JOIN subject as t2 ON t2.subject_id = t1.subject_id
                                                                            LEFT JOIN course as t4 ON t4.course_id = t2.course_id
                                                                            LEFT JOIN student_subject_grade as t3 ON t3.student_subject_id = t1.student_subject_id

                                                                            WHERE t1.student_id=:student_id
                                                                            AND t1.is_final=1
                                                                            AND t1.school_year_id=:school_year_id
                                                                            AND t2.course_id!=:course_id

                                                                            ");

                                                                        $addedSubjects->bindValue(":student_id", $student_id);
                                                                        $addedSubjects->bindValue(":school_year_id", $school_year_id);
                                                                        $addedSubjects->bindValue(":course_id", $student_course_id);
                                                                        $addedSubjects->execute();

                                                                        if($addedSubjects->rowCount() > 0){

                                                                            

                                                                            while($row = $addedSubjects->fetch(PDO::FETCH_ASSOC)){

                                                                                $subject_id = $row['subject_id'];
                                                                                $subject_code = $row['subject_code'];
                                                                                $subject_title = $row['subject_title'];
                                                                                $pre_requisite = $row['pre_requisite'];
                                                                                $subject_type = $row['subject_type'];
                                                                                $unit = $row['unit'];
                                                                                $course_level = $row['course_level'];
                                                                                $program_section = $row['program_section'];
                                                                                $program_section = $row['program_section'];
                                                                                $student_subject_id = $row['t2_student_subject_id'];

                                                                                echo "
                                                                                    <tr class='text-center'>
                                                                                        <td>$subject_id</td>
                                                                                        <td>$program_section</td>
                                                                                        <td>$subject_code</td>
                                                                                        <td>$subject_title</td>
                                                                                        <td>$unit</td>
                                                                                        <td>$pre_requisite</td>
                                                                                        <td>$subject_type</td>
                                                                                    </tr>
                                                                                ";
                                                                            }

                                                                        }
                                                                    ?>
                                                                </tbody>
                                                            </table>
                                                        <?php
                                                }else{
                                                    echo "
                                                        <h3 class='text-center text-info'>No Added Subject(s)</h3>
                                                    ";
                                                }
                                            ?>
                                        </div>

                                        <form action="" method="POST">

                                            <?php 
                                            
                                                if($checkEnrollmentEnrolled == true){
                                                    echo "
                                                        <button type='button' class='btn btn-outline-primary'>
                                                            Print
                                                        </button>
                                                    ";

                                                }
                                            ?>
                                        </form>
                                    <?php

                                }
                                else if($checkEnrollmentEnrolled == false){

                                    ?>
                                        <!-- SELECTED SUBJECTS -->
                                        <div class="form-details">

                                            <h4><?php echo $section_name; ?> Subjects</h4>
                                            <span class="">
                                                Section Capacity:
                                                <?php 
                                                    echo $updatedTotalStudent;
                                                ?> / <?php echo $student_current_capacity;?>
                                            </span>
                                            <table id="dash-table" 
                                                class="table table-striped table-hover table-responsive" 
                                                style="font-size:14px" cellspacing="0">
                                                <thead>
                                                    <tr class="text-center"> 
                                                        <th rowspan="2">ID</th>
                                                        <th rowspan="2">Section</th>
                                                        <th rowspan="2">Code</th>
                                                        <th rowspan="2">Description</th>  
                                                        <th rowspan="2">Type</th>
                                                        <th rowspan="2">Unit</th>
                                                </thead> 
                                                <tbody>
                                                    <?php

                                                        # For Pending Grade 11 1st Semester Only
                                                        $semester = "First";

                                                        $transfereeSubjects = $con->prepare("SELECT 
                                                            t1.is_transferee, t1.is_final,
                                                            t1.student_subject_id as t2_student_subject_id, 
                                                            t3.student_subject_id as t3_student_subject_id,
                                                            t2.*,
                                                            t4.program_section
                                                            
                                                            
                                                            FROM student_subject as t1

                                                            INNER JOIN subject as t2 ON t2.subject_id = t1.subject_id
                                                            LEFT JOIN student_subject_grade as t3 ON t3.student_subject_id = t1.student_subject_id
                                                            LEFT JOIN course as t4 ON t4.course_id = t2.course_id

                                                            WHERE t1.student_id = :student_id
                                                            AND t1.is_final = 0
                                                            AND t1.school_year_id = :school_year_id
                                                            AND t2.course_id = :course_id");


                                                            $transfereeSubjects->bindValue(":student_id", $student_id);
                                                            $transfereeSubjects->bindValue(":school_year_id", $school_year_id);
                                                            $transfereeSubjects->bindValue(":course_id", $student_course_id);
                                                            $transfereeSubjects->execute();

                                                            $totalUnits = 0;

                                                            if($transfereeSubjects != null){
                                                                $applyTabIsAvailable = true;

                                                                foreach ($transfereeSubjects as $key => $row) {

                                                                    $subject_course_id = $row['course_id'];

                                                                    $unit = $row['unit'];
                                                                    $subject_id = $row['subject_id'];
                                                                    $program_section = $row['program_section'];
                                                                    // $program_section = "";

                                                                    if($subject_course_id == $student_course_id){
                                                                        array_push($my_course_subjects, $subject_id);
                                                                    }

                                                                    $subject_title = $row['subject_title'];
                                                                    $course_id = $row['course_id'];
                                                                    $subject_code = $row['subject_code'];
                                                                    $subject_type = $row['subject_type'];
                                                                    $semester = $row['semester'];
                                                                    $course_level = $row['course_level'];
                                                                    $is_transferee = $row['is_transferee'];

                                                                    // $is_final = $row['is_final'];

                                                                    $totalUnits += $unit;

                                                                    $text = "";

                                                                    $student_subject_id = $row['t2_student_subject_id'];
                                                                
                                                                    echo "
                                                                        <tr class='text-center'>
                                                                            <td>$subject_id</td>
                                                                            <td>$program_section</td>
                                                                            <td>$subject_code</td>
                                                                            <td>$subject_title</td>
                                                                            <td>$subject_type</td>
                                                                            <td>$unit</td>
                                                                        </tr>
                                                                    ";
                                                                }
                                                            } 
                                                    ?>
                                                </tbody>
                                                <?php
                                                        if($totalUnits != 0){
                                                            ?>
                                                            <tr class="text-center">
                                                                <td colspan="5"  style="font-weight:bold;text-align: right;" >Total Units</td>
                                                                <td><?php echo $totalUnits;?></td>
                                                            </tr> 
                                                            <?php
                                                        }
                                                ?>
                                            </table>
                                        </div>


                                        <div class="form-details">
                                            <!--  REMOVED SUBJECTS -->
                                            <?php

                                                $result = array_diff($course_section_subjects, $my_course_subjects);
                                            
                                                if (empty($result)) {
                                                echo "
                                                    
                                                    <h3 class='text-center text-info'>No Removed Subject .</h3>
                                                ";

                                                }else{
                                                    ?>
                                                        <h5 class="text-warning text-center">Removed Subjects</h5>
                                                        <!-- REMOVE SUBJECTS -->
                                                        <table id="" class="table table-striped table-hover "  style="font-size:13px" cellspacing="0"  > 
                                                            <thead>
                                                                <tr class="text-center"> 
                                                                    <th rowspan="2">Id</th>
                                                                    <th rowspan="2">Code</th>
                                                                    <th rowspan="2">Description</th>
                                                                    <th rowspan="2">Unit</th>
                                                                    <th rowspan="2">Type</th>
                                                                </tr>	
                                                            </thead> 	
                                                            <tbody>
                                                                <?php

                                                                    // print_r($my_course_subjects);

                                                                    $subjectIds = implode(',', $my_course_subjects);

                                                                    $sql = $con->prepare("SELECT * FROM 
                                                                    
                                                                        subject as t1

                                                                        WHERE t1.subject_id NOT IN ($subjectIds)
                                                                        -- WHERE t1.student_id=:student_id
                                                                        AND t1.course_id=:course_id
                                                                        AND t1.course_level=:course_level
                                                                        AND t1.semester=:semester

                                                                        ");

                                                                    $sql->bindValue(":course_id", $student_course_id);
                                                                    $sql->bindValue(":course_level", $course_level);
                                                                    $sql->bindValue(":semester", $current_school_year_period);
                                                                    $sql->execute();
                                                                
                                                                    $totalUnits = 0;
                                                                
                                                                    if($sql->rowCount() > 0){
                                                                        
                                                                        while($row = $sql->fetch(PDO::FETCH_ASSOC)){

                                                                            $subject_id = $row['subject_id'];
                                                                            $subject_code = $row['subject_code'];
                                                                            $subject_title = $row['subject_title'];
                                                                            $unit = $row['unit'];
                                                                            $subject_type = $row['subject_type'];
                                                                            // $is_transferee = $row['is_transferee'];

                                                                            $totalUnits += $unit;

                                                                            $status = "Ongoing";
                                                                            
                                                                            echo "
                                                                                <tr class='text-center'>
                                                                                    <td>$subject_id</td>
                                                                                    <td>$subject_code</td>
                                                                                    <td>$subject_title</td>
                                                                                    <td>$unit</td>
                                                                                    <td>$subject_type</td>
                                                                                </tr>
                                                                            ";
                                                                        }
                                                                    }
                                                                ?>
                                                                <tr class="text-center">
                                                                    <td colspan="3"  style="text-align: right;" >Remove Units</td>
                                                                    <td><?php echo $totalUnits;?></td>
                                                                </tr> 
                                                            </tbody>
                                                        </table>
                                                    <?php
                                                }
                                            ?>
                                        </div>

                                        <!--  ADDED SUBJECTSX -->
                                        <div class="form-details">

                                            <h4 class="mb-3 text-muted">Added Subjects</h4>
                                            <?php 
                                                if(count($subject->GetNewTransfereeAddedSubject($student_id,
                                                    $school_year_id, $student_course_id)) > 0){
                                                        
                                                        ?>
                                                            <table class="table table-hover "  style="font-size:15px" cellspacing="0"> 
                                                                <thead class="thead-dark">
                                                                    <tr class="text-center"">
                                                                        <th>Id</th>
                                                                        <th>Section</th>
                                                                        <th>Code</th>
                                                                        <th>Description</th>
                                                                        <th>Unit</th>
                                                                        <th>Pre-Requisite</th>
                                                                        <th>Type</th>
                                                                    </tr>
                                                                </thead>

                                                                <tbody>
                                                                    <?php 

                                                                        // $addedSubjects = $subject->GetTransfereeAddedSubject($student_id,
                                                                        //     $current_school_year_id, $selected_course_id);

                                                                        $addedSubjects = $con->prepare("SELECT 
                                                                            t1.is_transferee, t1.is_final,
                                                                            t1.student_subject_id as t2_student_subject_id, 
                                                                            t3.student_subject_id as t3_student_subject_id,

                                                                            t4.program_section,
                                                                            t2.* FROM student_subject as t1

                                                                            INNER JOIN subject as t2 ON t2.subject_id = t1.subject_id
                                                                            LEFT JOIN course as t4 ON t4.course_id = t2.course_id
                                                                            LEFT JOIN student_subject_grade as t3 ON t3.student_subject_id = t1.student_subject_id

                                                                            WHERE t1.student_id=:student_id
                                                                            AND t1.is_final=0
                                                                            AND t1.school_year_id=:school_year_id
                                                                            AND t2.course_id!=:course_id

                                                                            ");

                                                                        $addedSubjects->bindValue(":student_id", $student_id);
                                                                        $addedSubjects->bindValue(":school_year_id", $school_year_id);
                                                                        $addedSubjects->bindValue(":course_id", $student_course_id);
                                                                        $addedSubjects->execute();

                                                                        if($addedSubjects->rowCount() > 0){

                                                                            while($row = $addedSubjects->fetch(PDO::FETCH_ASSOC)){

                                                                                $subject_id = $row['subject_id'];
                                                                                $subject_code = $row['subject_code'];
                                                                                $subject_title = $row['subject_title'];
                                                                                $pre_requisite = $row['pre_requisite'];
                                                                                $subject_type = $row['subject_type'];
                                                                                $unit = $row['unit'];
                                                                                $course_level = $row['course_level'];
                                                                                $program_section = $row['program_section'];
                                                                                $program_section = $row['program_section'];
                                                                                $student_subject_id = $row['t2_student_subject_id'];

                                                                                echo "
                                                                                    <tr class='text-center'>
                                                                                        <td>$subject_id</td>
                                                                                        <td>$program_section</td>
                                                                                        <td>$subject_code</td>
                                                                                        <td>$subject_title</td>
                                                                                        <td>$unit</td>
                                                                                        <td>$pre_requisite</td>
                                                                                        <td>$subject_type</td>
                                                                                        
                                                                                    </tr>
                                                                                ";
                                                                            }

                                                                        }
                                                                    ?>

                                                                </tbody>

                                                                
                                                            </table>

                                                            
                                                        <?php
                                                }else{
                                                    echo "
                                                        <h3 class='text-center text-info'>No Added Data Found.</h3>
                                                    ";
                                                }
                                            ?>
                                        </div>

                                        <form action="" method="POST">

                                            <?php 
                                            
                                                if($checkIfCashierEvaluated == false && $checkIfRegisrarEvaluated){
                                                    echo "
                                                        <button type='button' class='btn btn-outline-primary'>Wait for Payment</button>
                                                    ";
                                                }
                                                else if($checkIfCashierEvaluated && $checkIfRegisrarEvaluated){
                                                    ?>
                                                        <button type="submit" name="inserted_transferee_subject_btn"
                                                            id="inserted_transferee_subject_btn"
                                                            onclick="return confirm('Are you sure you want to insert.?')"
                                                            class="btn btn-success btn">Approve Enrollment
                                                        </button>
                                                    <?php
                                                }

                                                $checkIfCashierEvaluated = $enrollment->CheckEnrollmentCashierApproved($student_id,
                                                    $student_course_id, $school_year_id);
                                                    
                                                    
                                                $checkIfRegistrarEvaluated = $enrollment->CheckEnrollmentRegistrarApproved($student_id,
                                                    $student_course_id, $school_year_id);

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
                                    <?php 
                                }
                            ?>
                          
                        </div>

                    </div>

                    <div style="display: none;" class="container">
                        <h4 class="text-center text-primary">Enrollment Details</h4>
                        <hr>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-2 form-group">
                                    <label class="mb-2" for="">Enrollment ID</label>
                                    <input readonly value="<?php echo $get_student_form_id;?>" type="text" class="form-control">
                                </div>
                                <div class="mb-2 form-group">
                                    <label class="mb-2" for="">Name</label>
                                    <input readonly value="<?php echo $student_fullname; ?>" type="text" class="form-control">
                                </div>
                             
                                <div class="mb-2 form-group">
                                    <label class="mb-2" for="">Status</label>
                                    <input readonly value="Transferee" type="text" class="form-control">
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="mb-2 form-group">
                                    <label class="mb-2" for="">Program & Section</label>
                                    <input readonly value="<?php echo $student_program_section ?>" type="text" class="form-control">
                                </div>
                                <div class="mb-2 form-group">
                                    <label class="mb-2" for="">Semester</label>
                                    <input readonly value="<?php echo $current_school_year_period ?>" type="text" class="form-control">
                                </div>
                                <div class="mb-2 form-group">
                                    <label class="mb-2" for="">Academic Year</label>
                                    <input readonly value="<?php echo $current_school_year_term ?>" type="text" class="form-control">
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        <?php

    }

    if(isset($_GET['enrolled_success'])
        && $_GET['enrolled_success'] == "true"
        && $_GET['id']){
            
            $student_id = $_GET['id'];

            $username = $enroll->GetStudentUsername($student_id);
            $student_fullname = $enroll->GetStudentFullName($student_id);

            $student = new Student($con, $username);
            
            $student_address = $student->GetStudentAddress();
            $student_contact = $student->GetGuardianNameContact();
            $admission_status = $student->GetStudentAdmissionStatus();


            $student_fullname = $enroll->GetStudentFullName($student_id);
            $student_firstname = $student->GetFirstName();
            $student_lastname = $student->GetLastName();
            $student_unique_id = $student->GetStudentUniqueId();
            $student_middle_name = $student->GetMiddleName();


            $student_course_level = $enroll->GetStudentCourseLevel($username);
            $student_course_id = $enroll->GetStudentCourseId($username);

            $section = new Section($con, $student_course_id);

            // echo $student_id;
            $student_course_level = $enroll->GetStudentCourseLevel($username);
            $student_course_id = $enroll->GetStudentCourseId($username);
            $student_program_section = $enroll->GetStudentProgramSection($student_course_id);
            $student_program_id = $enroll->GetStudentProgramId($student_course_id);

            $get_student_form_id = $enrollment->GetEnrollmentFormId($student_id, $student_course_id,$school_year_id);

            $unique_form_id = $enrollment->GenerateEnrollmentFormId();

            $section_name = $section->GetSectionName();

            $subject = new Subject($con, $registrarLoggedIn, null);
            $pending = new Pending($con);


            $checkIfCashierEvaluated = $enrollment->CheckEnrollmentCashierApproved($student_id,
                $student_course_id, $school_year_id);

            $checkIfRegisrarEvaluated = $enrollment->CheckEnrollmentRegistrarApproved($student_id,
                $student_course_id, $school_year_id);

            $checkEnrollmentEnrolled = $enrollment->CheckEnrollmentEnrolled($student_id,
                $student_course_id, $school_year_id);

           
            $my_course_subjects = [];

            $course_section_subjects = $enroll->
                GetStudentsStrandSubjectsPerLevelSemester($username);

            $get_student_enrollment_form_id = $enrollment->GetEnrollmentFormId($student_id, $student_course_id, $school_year_id);
            $pending_form_submission = $pending->GetSubmittedOn($student_firstname);
            $enrolledDate = $enrollment->GetEnrollmentDate(
                $student_id, $student_course_id, $school_year_id);

            $student_section_obj = $section->GetSectionObj($student_course_id);

            $student_curren_course_program_id = $student_section_obj['program_id'];
            $student_current_course_level = $student_section_obj['course_level'];
            $student_current_capacity = $student_section_obj['capacity'];

            $updatedTotalStudent = $section->GetTotalNumberOfStudentInSection($student_course_id,
                $school_year_id);

            $isSectionFull = $section->CheckSectionIsFull($student_course_id);


            $getEnrollmentEnrolledDate = $enrollment
                ->GetEnrollmentEnrolledDate($student_id, $student_course_id,$school_year_id);

            $program_id = $section->GetProgramIdBySectionId($student_course_id);
            $strand_name = $section->GetAcronymByProgramId($program_id);
            $track_name = $section->GetTrackByProgramId($program_id);


            if(isset($_POST['inserted_transferee_subject_btn'])){

                $transfereeSubjects = $con->prepare("SELECT 
                    t1.is_transferee, t1.is_final,
                    t1.student_subject_id,
                    t1.school_year_id,

                    t2.* 
                    
                    FROM student_subject as t1

                    INNER JOIN subject as t2 ON t2.subject_id = t1.subject_id
                    LEFT JOIN student_subject_grade as t3 ON t3.student_subject_id = t1.student_subject_id

                    WHERE t1.student_id=:student_id
                    AND t1.is_final=0
                    AND t1.school_year_id=:school_year_id");

                $transfereeSubjects->bindValue(":student_id", $student_id);
                $transfereeSubjects->bindValue(":school_year_id", $school_year_id);
                $transfereeSubjects->execute();

                if($transfereeSubjects->rowCount() > 0){
                    $final = 1;
                    $isSuccess = false;

                    $update = $con->prepare("UPDATE student_subject
                        SET is_final=:is_final
                        WHERE student_subject_id=:student_subject_id
                        AND student_id=:student_id
                        AND school_year_id=:school_year_id");

                    while($row = $transfereeSubjects->fetch(PDO::FETCH_ASSOC)){

                        $student_subject_id = $row['student_subject_id'];

                        $update->bindParam(":is_final", $final);
                        $update->bindParam(":student_subject_id", $student_subject_id);
                        $update->bindParam(":student_id", $student_id);
                        $update->bindParam(":school_year_id", $school_year_id);
                        $update->execute();
                        $isSuccess = true;
                    }
                    if($isSuccess == true){

                        $enrolledSuccess = $oldEnroll->EnrolledStudentInTheEnrollment($school_year_id,
                            $student_id);

                        $newToOld = $oldEnroll->UpdateSHSStudentNewToOld($student_id);

                        if($enrolledSuccess && $newToOld){

                            AdminUser::success("Student successfully enrolled", "transferee_insertion.php?success=true&id=$student_id");
                                // header("Location: ");
                            exit();

                            // header("Location: transferee_insertion.php?success=true&id=$student_id");
                            // exit();
                        }   
                    }
                }
            }

            ?>
                <div class="col-md-12 row">
                    <div class="table-responsive" style="margin-top:5%;"> 

                    <div class="content">
                        <div class="form-header ">
                            <div class="header-content">
                                <h2>Enrollment form</h2>
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
                                <td>Transferee</td>
                                <td><?php echo $student_unique_id; ?></td>
                                <td>
                                    <?php 
                                        echo $checkEnrollmentEnrolled ? "Enrolled" : "N/A";
                                    ?>
                                </td>
                                <td><?php
                                    $date = new DateTime($getEnrollmentEnrolledDate);
                                    $formattedDate = $date->format('m/d/Y H:i');
                                    echo $formattedDate;
                                ?></td>
                                </tr>
                            </table>
                            </div>
                        </div>

                        <div class="choices">
                            <div class="student-details">

                                <a href="transferee_insertion.php?details=true&id=<?php echo $student_id?>">
                                
                                    <button
                                        type="button"
                                        class="selection-btn"
                                        id="student-details">
                                        <i class="bi bi-clipboard-check"></i>Student details
                                    </button>
                                </a>

                            </div>
                            <div class="enrolled-subjects">
                                <a href="transferee_insertion.php?enrolled_subjects=true&id=<?php echo $student_id?>">
                                    <button
                                        type="button"
                                        class="selection-btn"
                                        id="enrolled-subjects">
                                        <i class="bi bi-collection"></i>Enrolled subjects
                                    </button>
                                </a>

                            </div>
                        </div>

                        <div class="bg-content">

                            <div style="
                                display: flex;
                                flex-direction: column;
                                justify-content: center;
                                text-align: center;
                                align-items: center;" class="enrolled_nav">

                                <div class="form-details" id="enrollment-form">
                                    <h3>Enrollment details</h3>

                                <table>
                                    <tbody>
                                    <tr>
                                        <th>S.Y.</th>
                                        <td><?php echo $current_school_year_term;?></td>
                                        <th>Track</th>
                                        <td colspan="2"><?php echo $track_name;?></td>
                                        <th>Strand</th>
                                        <td colspan="2"><?php echo $strand_name; ?></td>
                                        <th >Level</th>
                                        <td>Grade <?php echo $student_course_level;?></td>
                                        <th>Semester</th>
                                        <td><?php echo $current_school_year_period;?></td>
                                    </tr>
                                    </tbody>
                                </table>
                                </div>

                                <!-- SELECTED SUBJECTS -->
                                <div class="form-details">

                                    <h4><?php echo $section_name; ?> Subjects</h4>

                                    <table id="dash-table" 
                                        class="table table-striped table-hover table-responsive" 
                                        style="font-size:14px" cellspacing="0">
                                        <thead>
                                            <tr class="text-center"> 
                                                <th rowspan="2">ID</th>
                                                <th rowspan="2">Section</th>
                                                <th rowspan="2">Code</th>
                                                <th rowspan="2">Description</th>  
                                                <th rowspan="2">Type</th>
                                                <th rowspan="2">Unit</th>
                                        </thead> 
                                        <tbody>
                                            <?php

                                                # For Pending Grade 11 1st Semester Only
                                                $semester = "First";

                                                $transfereeSubjects = $con->prepare("SELECT 
                                                    t1.is_transferee, t1.is_final,
                                                    t1.student_subject_id as t2_student_subject_id, 
                                                    t3.student_subject_id as t3_student_subject_id,
                                                    t2.*,
                                                    t4.program_section
                                                    
                                                    
                                                    FROM student_subject as t1

                                                    INNER JOIN subject as t2 ON t2.subject_id = t1.subject_id
                                                    LEFT JOIN student_subject_grade as t3 ON t3.student_subject_id = t1.student_subject_id
                                                    LEFT JOIN course as t4 ON t4.course_id = t2.course_id

                                                    WHERE t1.student_id = :student_id
                                                    AND t1.is_final = 1
                                                    AND t1.school_year_id = :school_year_id
                                                    AND t2.course_id = :course_id");


                                                    $transfereeSubjects->bindValue(":student_id", $student_id);
                                                    $transfereeSubjects->bindValue(":school_year_id", $school_year_id);
                                                    $transfereeSubjects->bindValue(":course_id", $student_course_id);
                                                    $transfereeSubjects->execute();

                                                        $totalUnits = 0;

                                                        if($transfereeSubjects != null){
                                                            $applyTabIsAvailable = true;

                                                            foreach ($transfereeSubjects as $key => $row) {

                                                                $subject_course_id = $row['course_id'];

                                                                $unit = $row['unit'];
                                                                $subject_id = $row['subject_id'];
                                                                $program_section = $row['program_section'];
                                                                // $program_section = "";

                                                                if($subject_course_id == $student_course_id){
                                                                    array_push($my_course_subjects, $subject_id);
                                                                }

                                                                $subject_title = $row['subject_title'];
                                                                $course_id = $row['course_id'];
                                                                $subject_code = $row['subject_code'];
                                                                $subject_type = $row['subject_type'];
                                                                $semester = $row['semester'];
                                                                $course_level = $row['course_level'];
                                                                $is_transferee = $row['is_transferee'];

                                                                // $is_final = $row['is_final'];

                                                                $totalUnits += $unit;

                                                                $text = "";

                                                                $student_subject_id = $row['t2_student_subject_id'];
                                                            
                                                                echo "
                                                                    <tr class='text-center'>
                                                                        <td>$subject_id</td>
                                                                        <td>$program_section</td>
                                                                        <td>$subject_code</td>
                                                                        <td>$subject_title</td>
                                                                        <td>$subject_type</td>
                                                                        <td>$unit</td>
                                                                    </tr>
                                                                ";
                                                            }
                                                        } 
                                                    ?>
                                                </tbody>
                                                <?php
                                                    if($totalUnits != 0){
                                                        ?>
                                                        <tr class="text-center">
                                                            <td colspan="5"  style="font-weight:bold;text-align: right;" >Total Units</td>
                                                            <td><?php echo $totalUnits;?></td>
                                                        </tr> 
                                                        <?php
                                                    }
                                            ?>
                                    </table>

                                    <!--  REMOVED SUBJECTS -->
                                    <?php

                                        $result = array_diff($course_section_subjects, $my_course_subjects);
                                    
                                        if (empty($result)) {
                                            echo "
                                                <h3 class='text-center text-info'>No Removed Subject .</h3>
                                            ";
                                        }else{
                                            ?>
                                                <h5 class="text-warning text-center">Removed Subjects</h5>
                                                <!-- REMOVE SUBJECTS -->
                                                <table id="" class="table table-striped table-hover "  style="font-size:13px" cellspacing="0"  > 
                                                    <thead>
                                                        <tr class="text-center"> 
                                                            <th rowspan="2">Id</th>
                                                            <th rowspan="2">Code</th>
                                                            <th rowspan="2">Description</th>
                                                            <th rowspan="2">Unit</th>
                                                            <th rowspan="2">Type</th>
                                                        </tr>	
                                                    </thead> 	
                                                    <tbody>
                                                        <?php

                                                            // print_r($my_course_subjects);

                                                            $subjectIds = implode(',', $my_course_subjects);

                                                            $sql = $con->prepare("SELECT * FROM 
                                                            
                                                                subject as t1

                                                                WHERE t1.subject_id NOT IN ($subjectIds)
                                                                -- WHERE t1.student_id=:student_id
                                                                AND t1.course_id=:course_id
                                                                AND t1.course_level=:course_level
                                                                AND t1.semester=:semester

                                                                ");

                                                            $sql->bindValue(":course_id", $student_course_id);
                                                            $sql->bindValue(":course_level", $course_level);
                                                            $sql->bindValue(":semester", $current_school_year_period);
                                                            $sql->execute();
                                                        
                                                            $totalUnits = 0;
                                                        
                                                            if($sql->rowCount() > 0){
                                                                
                                                                while($row = $sql->fetch(PDO::FETCH_ASSOC)){

                                                                    $subject_id = $row['subject_id'];
                                                                    $subject_code = $row['subject_code'];
                                                                    $subject_title = $row['subject_title'];
                                                                    $unit = $row['unit'];
                                                                    $subject_type = $row['subject_type'];
                                                                    // $is_transferee = $row['is_transferee'];

                                                                    $totalUnits += $unit;

                                                                    $status = "Ongoing";
                                                                    
                                                                    echo "
                                                                        <tr class='text-center'>
                                                                            <td>$subject_id</td>
                                                                            <td>$subject_code</td>
                                                                            <td>$subject_title</td>
                                                                            <td>$unit</td>
                                                                            <td>$subject_type</td>
                                                                        </tr>
                                                                    ";
                                                                }
                                                            }
                                                        ?>
                                                        <tr class="text-center">
                                                            <td colspan="3"  style="text-align: right;" >Remove Units</td>
                                                            <td><?php echo $totalUnits;?></td>
                                                        </tr> 
                                                    </tbody>
                                                </table>
                                            <?php
                                        }
                                    ?>

                                </div>

                                <!--  ADDED SUBJECTS -->
                                <div class="form-details">

                                    <h4 class="mb-3 text-muted">Added Subjects</h4>
                                    <?php 
                                        if(count($subject->GetNewTransfereeEnrolledAddedSubject($student_id,
                                            $school_year_id, $student_course_id)) > 0){
                                                
                                                ?>
                                                    <table class="table table-hover "  style="font-size:13px" cellspacing="0"> 
                                                        <thead>
                                                            <tr class="text-center"">
                                                                <th>Id</th>
                                                                <th>Section</th>
                                                                <th>Code</th>
                                                                <th>Description</th>
                                                                <th>Unit</th>
                                                                <th>Pre-Requisite</th>
                                                                <th>Type</th>
                                                                <th></th>
                                                            </tr>
                                                        </thead>

                                                        <tbody>
                                                            <?php 

                                                                // $addedSubjects = $subject->GetTransfereeAddedSubject($student_id,
                                                                //     $current_school_year_id, $selected_course_id);

                                                                $addedSubjects = $con->prepare("SELECT 
                                                                    t1.is_transferee, t1.is_final,
                                                                    t1.student_subject_id as t2_student_subject_id, 
                                                                    t3.student_subject_id as t3_student_subject_id,

                                                                    t4.program_section,
                                                                    t2.* FROM student_subject as t1

                                                                    INNER JOIN subject as t2 ON t2.subject_id = t1.subject_id
                                                                    LEFT JOIN course as t4 ON t4.course_id = t2.course_id
                                                                    LEFT JOIN student_subject_grade as t3 ON t3.student_subject_id = t1.student_subject_id

                                                                    WHERE t1.student_id=:student_id
                                                                    AND t1.is_final=1
                                                                    AND t1.school_year_id=:school_year_id
                                                                    AND t2.course_id!=:course_id

                                                                    ");

                                                                $addedSubjects->bindValue(":student_id", $student_id);
                                                                $addedSubjects->bindValue(":school_year_id", $school_year_id);
                                                                $addedSubjects->bindValue(":course_id", $student_course_id);
                                                                $addedSubjects->execute();

                                                                if($addedSubjects->rowCount() > 0){

                                                                    

                                                                    while($row = $addedSubjects->fetch(PDO::FETCH_ASSOC)){

                                                                        $subject_id = $row['subject_id'];
                                                                        $subject_code = $row['subject_code'];
                                                                        $subject_title = $row['subject_title'];
                                                                        $pre_requisite = $row['pre_requisite'];
                                                                        $subject_type = $row['subject_type'];
                                                                        $unit = $row['unit'];
                                                                        $course_level = $row['course_level'];
                                                                        $program_section = $row['program_section'];
                                                                        $program_section = $row['program_section'];
                                                                        $student_subject_id = $row['t2_student_subject_id'];

                                                                        echo "
                                                                            <tr class='text-center'>
                                                                                <td>$subject_id</td>
                                                                                <td>$program_section</td>
                                                                                <td>$subject_code</td>
                                                                                <td>$subject_title</td>
                                                                                <td>$unit</td>
                                                                                <td>$pre_requisite</td>
                                                                                <td>$subject_type</td>
                                                                            </tr>
                                                                        ";
                                                                    }

                                                                }
                                                            ?>
                                                        </tbody>
                                                    </table>
                                                <?php
                                        }else{
                                            echo "
                                                <h3 class='text-center text-info'>No Added Data Found.</h3>
                                            ";
                                        }
                                    ?>
                                </div>

                                <form action="" method="POST">

                                    <?php 
                                    
                                        if($checkEnrollmentEnrolled == true){
                                            echo "
                                                <button type='button' class='btn btn-outline-primary'>
                                                    Print
                                                </button>
                                            ";

                                        }
                                    ?>
                                </form>


                                
                            </div>

                        </div>

                        <div style="display: none;" class="container">
                            <h4 class="text-center text-primary">Enrollment Details</h4>
                            <hr>
                            
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-2 form-group">
                                        <label class="mb-2" for="">Enrollment ID</label>
                                        <input readonly value="<?php echo $get_student_form_id;?>" type="text" class="form-control">
                                    </div>
                                    <div class="mb-2 form-group">
                                        <label class="mb-2" for="">Name</label>
                                        <input readonly value="<?php echo $student_fullname; ?>" type="text" class="form-control">
                                    </div>
                                
                                    <div class="mb-2 form-group">
                                        <label class="mb-2" for="">Status</label>
                                        <input readonly value="Transferee" type="text" class="form-control">
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="mb-2 form-group">
                                        <label class="mb-2" for="">Program & Section</label>
                                        <input readonly value="<?php echo $student_program_section ?>" type="text" class="form-control">
                                    </div>
                                    <div class="mb-2 form-group">
                                        <label class="mb-2" for="">Semester</label>
                                        <input readonly value="<?php echo $current_school_year_period ?>" type="text" class="form-control">
                                    </div>
                                    <div class="mb-2 form-group">
                                        <label class="mb-2" for="">Academic Year</label>
                                        <input readonly value="<?php echo $current_school_year_term ?>" type="text" class="form-control">
                                    </div>
                                </div>
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
    //     var checkboxes = document.getElementsByClassName('checkbox');

    //     for (var i = 0; i < checkboxes.length; i++) {
    //         checkboxes[i].checked = this.checked;
    //     }
    // });

</script>