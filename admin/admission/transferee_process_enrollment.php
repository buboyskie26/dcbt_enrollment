<!DOCTYPE html>
<html>
<head>
    <style>
        #addedSubjectsTable tr td {
            text-align: center;
        }
    </style>
</head>

<?php
 
    include('../registrar_enrollment_header.php');
    include('../../enrollment/classes/StudentEnroll.php');
    include('../../enrollment/classes/StudentSubject.php');
    include('../../enrollment/classes/Section.php');
    include('../..//includes/classes/Student.php');
    include('../../admin/classes/Subject.php');
    include('../../enrollment/classes/Enrollment.php');
    include('../../enrollment/classes/OldEnrollees.php');
    include('../../enrollment/classes/Pending.php');
    
    include('../classes/Course.php');

    ?>
        <head>
            <meta charset="UTF-8">
            <meta http-equiv="X-UA-Compatible" content="IE=edge">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <link
                href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css"
                rel="stylesheet"
                integrity="sha384-KK94CHFLLe+nY2dmCWGMq91rCGa5gtU4mk92HdvYe+M/SXH301p5ILy+dN9+nJOZ"
                crossorigin="anonymous"
                />
            <link
            rel="stylesheet"
            href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css"
            />
            <style>

                .process-status{
                    display: flex;
                    flex-direction: row;
                    justify-content: center;
                    font-style: normal;
                    font-weight: 700;
                    align-items: center;
                    margin-top: 80px;
                    padding: 0px 53px;
                    gap: 1px;
                    isolation: isolate;
                    width: 100%;
                    height: 74px;
                    background: #1A0000;
                }

                .process-status .selection{
                    margin-top: 5px;
                }

                .process-status .checkDetails{
                    color: white;
                }

                .process-status #line-1, #line-2{
                    color: #888888;
                }

                .findSection, .subConfirm{
                    color: #888888;
                }

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


    if(!AdminUser::IsRegistrarAuthenticated()){
        header("Location: /dcbt/adminLogin.php");
        exit();
    }


    if(isset($_GET['id'])
        || isset($_GET['st_id'])
    ){

        $pending_enrollees_id = isset($_GET['id']) ?  $_GET['id'] : null;

        unset($_SESSION['pending_enrollees_id']);
        unset($_SESSION['process_enrollment']);

        $studentEnroll = new StudentEnroll($con);
        $enrollment = new Enrollment($con, $studentEnroll);
        $old = new OldEnrollees($con, $studentEnroll);
        $studentSubject = new StudentSubject($con);
        

        $enrollment_form_id = $enrollment->GenerateEnrollmentFormId();

        if (!isset($_SESSION['enrollment_form_id'])) {
            $enrollment_form_id = $enrollment->GenerateEnrollmentFormId();
            $_SESSION['enrollment_form_id'] = $enrollment_form_id;
        } else {
            $enrollment_form_id = $_SESSION['enrollment_form_id'];
        }

        $school_year_obj = $studentEnroll->GetActiveSchoolYearAndSemester();

        $current_school_year_id = $school_year_obj['school_year_id'];
        $current_school_year_term = $school_year_obj['term'];
        $current_school_year_period = $school_year_obj['period'];

        $sql = $con->prepare("SELECT * FROM pending_enrollees
                WHERE pending_enrollees_id=:pending_enrollees_id
            ");

        $sql->bindValue(":pending_enrollees_id", $pending_enrollees_id);
        $sql->execute();

        $row = null;

        $course_id = 0;

        if($sql->rowCount() > 0){


            $row = $sql->fetch(PDO::FETCH_ASSOC);

            $program_id = $row['program_id'];
            $firstname = $row['firstname'];
            $middle_name = $row['middle_name'];
            $lastname = $row['lastname'];
            $birthday = $row['birthday'];
            $address = $row['address'];
            $sex = $row['sex'];
            $contact_number = $row['contact_number'];
            $date_creation = $row['date_creation'];
            $student_status = $row['student_status'];
            $email = $row['email'];
            $pending_enrollees_id = $row['pending_enrollees_id'];
            $password = $row['password'];
            $civil_status = $row['civil_status'];
            $nationality = $row['nationality'];
            $age = $row['age'];
            $guardian_name = $row['guardian_name'];
            $guardian_contact_number = $row['guardian_contact_number'];
            $lrn = $row['lrn'];
            $religion = $row['religion'];
            $birthplace = $row['birthplace'];
            $email = $row['email'];
            $type = $row['type'];

            $student_fullname = $firstname . " " . $lastname;

            $program = $con->prepare("SELECT acronym FROM program
                WHERE program_id=:program_id
                LIMIT 1
            ");
            $program->bindValue(":program_id", $program_id);
            $program->execute();

            $program_acronym = $program->fetchColumn();
 
            // $enrollment_form_id = $enrollment->GetEnrollmentId();

            $student_program_section = "";
            $student_course_id = 0;
            $student_id = null;
            $student_course_level = null;

            $enrollment_id = null;

            $student = $con->prepare("SELECT *
                
                FROM student
                WHERE firstname=:firstname
                AND lastname=:lastname
                AND middle_name=:middle_name
                
                ");
            $student->bindValue(":firstname", $firstname);
            $student->bindValue(":lastname", $lastname);
            $student->bindValue(":middle_name", $middle_name);
            $student->execute();

            $enrollment = new Enrollment($con, $studentEnroll);

            if($student->rowCount() > 0){

                $row_student = $student->fetch(PDO::FETCH_ASSOC);

                $student_username = $row_student['username'];
                $student_id = $row_student['student_id'];
                $student_course_level = $row_student['course_level'];

                $student_course_id = $studentEnroll->GetStudentCourseId($student_username);

                $student_program_section = $studentEnroll->GetStudentProgramSection($student_course_id);

                $enrollment_id = $enrollment->GetEnrollmentId($student_id,
                    $student_course_id, $current_school_year_id);

                $enrollment_form_id = $enrollment->GetEnrollmentFormId($student_id,
                    $student_course_id, $current_school_year_id);

                // echo $enrollment_id;
            }


        }

        if(isset($_GET['id']) && isset($_GET['step1'])){
            ?>
                <div class="row col-md-12">


                    <div class="content">

                        <div class="form-header">
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
                                    <th>Submitted on:</th>
                                    </tr>
                                    <tr>
                                    <td><?php echo $enrollment_form_id;?></td>
                                    <td>Transferee</td>
                                    <td>N/A</td>
                                    <td>Evaluation</td>

                                    <td><?php
                                        $date = new DateTime($date_creation);
                                        $formattedDate = $date->format('m/d/Y H:i');

                                        echo $formattedDate;
                                    ?></td>
                                    
                                    </tr>
                                </table>
                            </div>
                        </div>
                    </div>

                    <div class="process-status">
                        <table class="selection">
                            <tr>
                                <th class="checkDetails" id="icon-1">
                                <i class="bi bi-clipboard-check"></i>
                                </th>
                                <th id="line-1">___________________________</th>
                                <th class="findSection" id="icon-2">
                                <i class="bi bi-building"></i>
                                </th>
                                <th id="line-2">___________________________</th>
                                <th class="subConfirm" id="icon-3">
                                <i class="bi bi-journal"></i>
                                </th>
                            </tr>
                            <tr>
                                <td class="checkDetails" id="process-1">Check details</td>
                                <td></td>
                                <td class="findSection" id="process-2">Find section</td>
                                <td></td>
                                <td class="subConfirm" id="process-3">Subject Confirmation</td>
                            </tr>
                        </table>
                    </div>

                    <div class="bg-content">
                        <div class="form-details" id="check-details">
                            <h3>Student details</h3>

                            <form class="row g-3">
                                <div class="col-md-4">
                                <label for="inputFirstName"  class="form-label">First name</label>
                                <input type="text" value='<?php echo $firstname;?>' class="form-control" id="inputFirstName" />
                                </div>
                                <div class="col-md-4">
                                <label for="inputLastName"  class="form-label">Last name</label>
                                <input type="text" value='<?php echo $lastname;?>' class="form-control" id="inputLastName" />
                                </div>
                                <div class="col-md-4">
                                <label for="inputMiddleName"  class="form-label">Middle name</label
                                >
                                <input type="text" value='<?php echo $middle_name;?>' class="form-control" id="inputMiddleName" />
                                </div>
                                <div class="col-md-4">
                                    <label for="inputBirthdate" class="form-label">Birthdate</label>
                                    <input type="date" value='<?php echo $birthday;?>' class="form-control" id="inputBirthdate" />
                                </div>

                                <div class="col-md-4">
                                <label for="inputGender" class="form-label">Gender</label>
                                <select id="inputGender" class="form-select">
                                        <option <?php if ($sex == 'Male') echo 'selected'; ?>>Male</option>
                                        <option <?php if ($sex == 'FeMale') echo 'selected'; ?>>Male</option>
                                </select>
                                </div>
                                <div class="col-md-4">
                                <label for="inputContactNo" class="form-label">Contact no.</label>
                                <input type="text" value='<?php echo $contact_number;?>' class="form-control" id="inputContactNo" />
                                </div>
                                <div class="col-12">
                                <label for="inputAddress" class="form-label">Address</label>
                                <input type="text" value='<?php echo $address;?>' class="form-control" id="inputAddress" />
                                </div>
                                
                            </form>
                            
                        </div>

            <?php

                echo "
                    <a style='text-align: end;' href='transferee_process_enrollment.php?step2=true&id=$pending_enrollees_id'>
                        <button type='button'class='btn-sm btn-primary'>Proceed</button>
                    </a>
                ";
            
                // if($type == "SHS"){
                // echo "
                //     <a style='text-align: end;' href='transferee_process_enrollment.php?step2=true&id=$pending_enrollees_id'>
                //         <button type='button'class='btn-sm btn-primary'>Proceed</button>
                //     </a>
                // ";
                // }else if($type == "Tertiary"){
                //     echo "
                //     <a style='text-align: end;' href='transferee_process_enrollment.php?step2=true&&id=$pending_enrollees_id'>
                //         <button type='button'class='btn-sm btn-primary'>Proceed</button>
                //     </a>
                // ";
                // }

            ?>


                    </div>


                </div>
            <?php
        }

        # FOR NON EVALUATED. STEP 2 (PENDING TABLE DEPEND)
        ##
        if(isset($_GET['id']) && isset($_GET['step2'])){
            
            unset($_SESSION['enrollment_id']);

            if(isset($_POST['transferee_pending_choose_section'])
                && isset($_POST['selected_course_id'])){

                $_SESSION['enrollment_id'] = $enrollment_id;

                $firstname = $_POST['firstname'];
                $lastname = $_POST['lastname'];
                $middle_name = $_POST['middle_name'];
                $password = $_POST['password'];
                $program_id = $_POST['program_id'];
                $civil_status = $_POST['civil_status'];
                $nationality = $_POST['nationality'];
                $contact_number = $_POST['contact_number'];
                $age = $_POST['age'];
                $guardian_name = $_POST['guardian_name'];
                $sex = $_POST['sex'];
                $guardian_contact_number = $_POST['guardian_contact_number'];
                $student_status = $_POST['student_status'];
                $pending_enrollees_id = $_POST['pending_enrollees_id'];
                $address = $_POST['address'];
                $lrn = $_POST['lrn'];
                $birthday = $_POST['birthday'];
                $religion = $_POST['religion'];
                $birthplace = $_POST['birthplace'];
                $email = $_POST['email'];

                $selected_course_id_value = $_POST['selected_course_id'];

                $course_id = intval($_POST['selected_course_id']); 

                ##
                $get_available_section = $con->prepare("SELECT 
                        course_id, capacity, course_level, program_section

                        FROM course
                        WHERE course_id=:course_id
                        LIMIT 1");

                $get_available_section->bindValue(":course_id", $course_id);
                $get_available_section->execute();

                # Update enrollment

                // echo $course_id;

                $isRedirectAuto = $course_id == $student_course_id;

                if($isRedirectAuto){
                    # Should not prompt.
                    $_SESSION['auto_redirect'] = true;
                    // echo $_SESSION['enrollment_id'];

                    # reef
                    header("Location: transferee_process_enrollment.php?step3=true&id=$pending_enrollees_id&selected_course_id=$student_course_id");
                    // header("Location: transferee_process_enrollment.php?step3=true&st_id=$student_id&selected_course_id=$student_course_id");
                    exit();
                }
                // else if(false){
                if($student_course_id != 0 && $course_id != $student_course_id){

                    # Edit
                    $available_section = $get_available_section->fetch(PDO::FETCH_ASSOC);

                    $course_level = $available_section['course_level'];
                    $program_section = $available_section['program_section'];

                    # Change student course_id & course_level

                    if($get_available_section->rowCount() > 0){

                        if($enrollment_id != null){

                            $isSuccessChangeStudentCourseId = $old->UpdateSHSStudentCourseId(
                            $student_id, $course_id, $course_level);
                        
                            if($isSuccessChangeStudentCourseId){

                                $wasChangingEnrollmentCourseId = $enrollment->UpdateSHSStudentEnrollmentCourseId(
                                    $enrollment_id, $course_id); 

                                if($wasChangingEnrollmentCourseId){
                                    
                                    AdminUser::success("Student is now changed  into $program_section section.",
                                        "transferee_process_enrollment.php?step3=true&id=$pending_enrollees_id&selected_course_id=$course_id");
                                    exit();
                                }
                            }else{
                                # Error.
                            }
                        }else{
                            # 
                            
                            AdminUser::success("Student is now changed  into $program_section section.", "transferee_process_enrollment.php?step2=true&id=$pending_enrollees_id");
                            exit();
                        }

                        

                    }

                }

                // else if(false){
                else if($student_course_id == 0){

                    // echo "add";

                    $generateStudentUniqueId = $studentEnroll->GenerateUniqueStudentNumber();
                    $username = strtolower($lastname) . '.' . $generateStudentUniqueId . '@dcbt.ph';

                    $course_level = null;
                
                    $enrollment_form_id = $enrollment->GenerateEnrollmentFormId();

                    // if(false){
                    if($get_available_section->rowCount() > 0){

                        $available_section = $get_available_section->fetch(PDO::FETCH_ASSOC);

                        $capacity = $available_section['capacity'];
                        $course_level = $available_section['course_level'];
                        $program_section = $available_section['program_section'];

                        $enrolledStudents = $studentEnroll->CheckNumberOfStudentInSection($course_id,
                            "First");

                        if($capacity > $enrolledStudents){

                            # Depends on the subject inserted in STEP3
                            $student_statusv2 = NULL;

                            $admission_status = "Transferee";

                            $sql = "INSERT INTO student (firstname, lastname, middle_name, password, civil_status, nationality, contact_number, birthday, age, guardian_name, guardian_contact_number, sex, student_status,
                                        course_id, student_unique_id, course_level, username,
                                        address, lrn, religion, birthplace, email, student_statusv2, admission_status, is_tertiary) 
                                    VALUES (:firstname, :lastname, :middle_name, :password, :civil_status, :nationality, :contact_number, :birthday, :age, :guardian_name, :guardian_contact_number, :sex, :student_status,
                                        :course_id, :student_unique_id, :course_level, :username,
                                        :address, :lrn, :religion, :birthplace, :email, :student_statusv2, :admission_status, :is_tertiary)";

                            $stmt_insert = $con->prepare($sql);

                            if($type == "SHS"){

                                $stmt_insert->bindParam(':firstname', $firstname);
                                $stmt_insert->bindParam(':lastname', $lastname);
                                $stmt_insert->bindParam(':middle_name', $middle_name);
                                $stmt_insert->bindParam(':password', $password);
                                $stmt_insert->bindParam(':civil_status', $civil_status);
                                $stmt_insert->bindParam(':nationality', $nationality);
                                $stmt_insert->bindParam(':contact_number', $contact_number);
                                $stmt_insert->bindParam(':birthday', $birthday);
                                $stmt_insert->bindParam(':age', $age);
                                $stmt_insert->bindParam(':guardian_name', $guardian_name);
                                $stmt_insert->bindParam(':guardian_contact_number', $guardian_contact_number);
                                $stmt_insert->bindParam(':sex', $sex);
                                // 
                                // SHOULD BE NULL FIRST.
                                $stmt_insert->bindParam(':student_status', $student_status);
                                $stmt_insert->bindParam(':course_id', $course_id);
                                $stmt_insert->bindParam(':student_unique_id', $generateStudentUniqueId);
                                $stmt_insert->bindParam(':course_level', $course_level);
                                $stmt_insert->bindParam(':username', $username);
                                $stmt_insert->bindParam(':address', $address);
                                $stmt_insert->bindParam(':lrn', $lrn);
                                $stmt_insert->bindParam(':religion', $religion);
                                $stmt_insert->bindParam(':birthplace', $birthplace);
                                $stmt_insert->bindParam(':email', $email);
                                $stmt_insert->bindParam(':student_statusv2', $student_statusv2);
                                $stmt_insert->bindParam(':admission_status', $admission_status);
                                $stmt_insert->bindValue(':is_tertiary', 0);

                            }else if($type == "Tertiary"){


                                $stmt_insert->bindParam(':firstname', $firstname);
                                $stmt_insert->bindParam(':lastname', $lastname);
                                $stmt_insert->bindParam(':middle_name', $middle_name);
                                $stmt_insert->bindParam(':password', $password);
                                $stmt_insert->bindParam(':civil_status', $civil_status);
                                $stmt_insert->bindParam(':nationality', $nationality);
                                $stmt_insert->bindParam(':contact_number', $contact_number);
                                $stmt_insert->bindParam(':birthday', $birthday);
                                $stmt_insert->bindParam(':age', $age);
                                $stmt_insert->bindParam(':guardian_name', $guardian_name);
                                $stmt_insert->bindParam(':guardian_contact_number', $guardian_contact_number);
                                $stmt_insert->bindParam(':sex', $sex);
                                // 
                                // SHOULD BE NULL FIRST.
                                $stmt_insert->bindParam(':student_status', $student_status);
                                $stmt_insert->bindParam(':course_id', $course_id);
                                $stmt_insert->bindParam(':student_unique_id', $generateStudentUniqueId);
                                $stmt_insert->bindParam(':course_level', $course_level);
                                $stmt_insert->bindParam(':username', $username);
                                $stmt_insert->bindParam(':address', $address);
                                $stmt_insert->bindParam(':lrn', $lrn);
                                $stmt_insert->bindParam(':religion', $religion);
                                $stmt_insert->bindParam(':birthplace', $birthplace);
                                $stmt_insert->bindParam(':email', $email);
                                $stmt_insert->bindParam(':student_statusv2', $student_statusv2);
                                $stmt_insert->bindParam(':admission_status', $admission_status);
                                $stmt_insert->bindValue(':is_tertiary', 1);
                            }


                            if($stmt_insert->execute()){

                                $student_id = $con->lastInsertId();

                                $pending = new Pending($con);

                                # CHECK FIRST IF STUDENT HAS A PARENT.
                                # UPDATE IF YES.
                                $update_parent = $pending->GetParentMatchPendingStudentId($pending_enrollees_id,
                                    $student_id);
                                
                                if($update_parent == true){

                                    $enrollment_status = "tentative";
                                    $is_new_enrollee = 1;
                                    $is_transferee = 1;
                                    $registrar_evaluated = "yes";
                                    // $username = "generate";

                                    $insert_enrollment = $con->prepare("INSERT INTO enrollment
                                        (student_id, course_id, school_year_id, enrollment_status, is_new_enrollee, registrar_evaluated, is_transferee, enrollment_form_id, is_tertiary)
                                        VALUES (:student_id, :course_id, :school_year_id, :enrollment_status, :is_new_enrollee, :registrar_evaluated, :is_transferee, :enrollment_form_id, :is_tertiary)");
                                                    
                                    if($type == "SHS"){

                                        $insert_enrollment->bindValue(':student_id', $student_id);
                                        $insert_enrollment->bindValue(':course_id', $course_id);
                                        // $insert_enrollment->bindValue(':enrollment_date', null);
                                        $insert_enrollment->bindValue(':school_year_id', $current_school_year_id);
                                        $insert_enrollment->bindValue(':enrollment_status', $enrollment_status);
                                        $insert_enrollment->bindValue(':is_new_enrollee', $is_new_enrollee);

                                        # Modified
                                        $insert_enrollment->bindValue(':registrar_evaluated', "no");
                                        $insert_enrollment->bindValue(':is_transferee', $is_transferee);
                                        $insert_enrollment->bindValue(':enrollment_form_id', $enrollment_form_id);
                                        $insert_enrollment->bindValue(':is_tertiary', 0);

                                    }else{

                                        $insert_enrollment->bindValue(':student_id', $student_id);
                                        $insert_enrollment->bindValue(':course_id', $course_id);
                                        // $insert_enrollment->bindValue(':enrollment_date', null);
                                        $insert_enrollment->bindValue(':school_year_id', $current_school_year_id);
                                        $insert_enrollment->bindValue(':enrollment_status', $enrollment_status);
                                        $insert_enrollment->bindValue(':is_new_enrollee', $is_new_enrollee);

                                        # Modified
                                        $insert_enrollment->bindValue(':registrar_evaluated', "no");
                                        $insert_enrollment->bindValue(':is_transferee', $is_transferee);
                                        $insert_enrollment->bindValue(':enrollment_form_id', $enrollment_form_id);
                                        $insert_enrollment->bindValue(':is_tertiary', 1);
                                    }

                                    if($insert_enrollment->execute()){

                                        // Check enrollment course_id number with course_id capacity
                                        if($insert_enrollment->rowCount() > 0){

                                            $generated_enrollment_id = $con->lastInsertId();

                                            $_SESSION['enrollment_id'] = $generated_enrollment_id;

                                            $section_url = "transferee_process_enrollment.php?step3=true&id=$pending_enrollees_id&selected_course_id=$selected_course_id_value";

                                            // $section_url = "transferee_process_enrollment.php?step3=true&st_id=$student_id&selected_course_id=$selected_course_id_value";

                                            AdminUser::success("Student is now placed in $program_section section.",
                                                $section_url);
                                            exit();
                                        }
                                    }




                                }else{
                                    AdminUser::error("Update parent failed", "");
                                }


                            }
                        }else{
                            echo "Capacity is full";
                            return;
                        }
    
                    }
                }
            }

            ?>
            <div class="row col-md-12">

                <div class="content">

                    <div class="form-header">
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
                                <th>Submitted on:</th>
                                </tr>
                                <tr>
                                <td><?php echo $enrollment_form_id;?></td>
                                <td>Transferee</td>
                                <td>N/A</td>
                                <td>Evaluation</td>

                                <td><?php
                                    $date = new DateTime($date_creation);
                                    $formattedDate = $date->format('m/d/Y H:i');

                                    echo $formattedDate;
                                ?></td>
                                
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>

                <div class="process-status">
                    <table class="selection">
                        <tr>
                            <th class="checkDetails" id="icon-1">
                            <i  style="color: #FFFF;" class="bi bi-clipboard-check"></i>
                            </th>
                            <th style="color: #FFFF;" id="line-1">___________________________</th>
                            <th  class="findSection" id="icon-2">
                            <i style="color: #FFFF;" class="bi bi-building"></i>
                            </th>
                            <th   id="line-2">___________________________</th>
                            <th class="subConfirm" id="icon-3">
                            <i class="bi bi-journal"></i>
                            </th>
                        </tr>
                        <tr>
                            <td style="color: #FFFF;" class="checkDetails" id="process-1">Check details</td>
                            <td></td>
                            <td style="color: #FFFF;" class="findSection" id="process-2">Find section</td>
                            <td></td>
                            <td class="subConfirm" id="process-3">Subject Confirmation</td>
                        </tr>
                    </table>
                </div>

                <div class="card">
                    <div class="card-body">


                        <div style="width: 100%;" class="form-details" id="enrollment-details">
                            <h3>Enrollment Details</h3>

                            <?php 
                            
                                if($type == "SHS"){
                                ?>
                                    <form class="row g-3">

                                        <div class="col-md-4">
                                            <label for="inputSchoolYear" class="form-label">S.Y.</label>
                                            <input readonly type="text" value="<?php echo $current_school_year_term; ?>" class="form-control" id="inputSchoolYear" />
                                        </div>

                                        <div class="col-md-4">
                                            <label for="inputTrack" class="form-label">Track</label>

                                            <select id="inputTrack" class="form-select">
                                                <?php 

                                                    $SHS_DEPARTMENT = 4;
                                                
                                                    $track_sql = $con->prepare("SELECT 
                                                        program_id, track, acronym 
                                                        
                                                        FROM program 
                                                    WHERE department_id =:department_id
                                                    GROUP BY track
                                                    ");

                                                    $track_sql->bindValue(":department_id", $SHS_DEPARTMENT);
                                                    $track_sql->execute();
                                                    

                                                    while($row = $track_sql->fetch(PDO::FETCH_ASSOC)){

                                                        $row_program_id = $row['program_id'];

                                                        $track = $row['track'];

                                                        $selected = ($row_program_id == $program_id) ? "selected" : "";

                                                        echo "<option value='$row_program_id' $selected>$track</option>";
                                                    }
                                                ?>
                                                <!-- <option selected>TVL</option>
                                                <option>Arts and Design</option> -->
                                            </select>
                                        </div>
                                        
                                        <div class="col-md-4">
                                            <label for="inputStrand" class="form-label">Strand</label>

                                            <select onchange="chooseStrand(this, <?php echo $pending_enrollees_id;?>)" 
                                                class="form-select" name="strand" id="strand">

                                                <?php 

                                                    $SHS_DEPARTMENT = 4;
                                                
                                                    $track_sql = $con->prepare("SELECT 
                                                        program_id, track, acronym 
                                                        
                                                        FROM program 
                                                    WHERE department_id =:department_id
                                                    GROUP BY acronym
                                                    ");

                                                    $track_sql->bindValue(":department_id", $SHS_DEPARTMENT);
                                                    $track_sql->execute();

                                                    while($row = $track_sql->fetch(PDO::FETCH_ASSOC)){

                                                        $row_program_id = $row['program_id'];

                                                        $acronym = $row['acronym'];

                                                        $selected = ($row_program_id == $program_id) ? "selected" : "";

                                                        echo "<option value='$row_program_id' $selected>$acronym</option>";
                                                    }
                                                ?>
                                                <!-- <option selected>GAS</option>
                                                <option>HUMSS</option>
                                                <option>ICT</option>
                                                <option>HE</option>
                                                <option>ABM</option>
                                                <option>IE</option> -->

                                            </select>
                                        </div>

                                        <div class="col-md-6">
                                            <label for="standing_level" class="form-label">Standing</label>
                                            
                                            <select id="standing_level" class="form-select" name="standing_level">

                                                <option value="">Choose Section</option>
                                                <option selected value="11"<?php if ($student_course_level == 11) echo ' selected'; ?>>Grade 11</option>
                                                <option value="12"<?php if ($student_course_level == 12) echo ' selected'; ?>>Grade 12</option>
                                                    
                                            </select>
                                            <input type="hidden" value="<?php echo $student_course_id;?>" id="student_course_id" name="student_course_id">
                                        </div>
                                        <div class="col-md-6">
                                            <label for="inputSemester" class="form-label">Semester</label>
                                            <input readonly type="text" value="<?php echo $current_school_year_period; ?>" class="form-control" id="inputSchoolYear" />
                                            <!-- <select id="inputSemester" class="form-select">
                                                <option <?php if ($current_school_year_period == 'First') echo 'selected'; ?>>First</option>
                                                <option <?php if ($current_school_year_period == 'Second') echo 'selected'; ?>>Second</option>
                                            </select> -->
                                        </div>
                                    </form>
                                <?php
                                }
                                else if($type == "Tertiary"){
                                ?>
                                    <form class="row g-3">

                                        <div class="col-md-4">
                                            <label for="inputSchoolYear" class="form-label">S.Y.</label>
                                            <input readonly type="text" value="<?php echo $current_school_year_term; ?>" class="form-control" id="inputSchoolYear" />
                                        </div>

                                        <div class="col-md-4">
                                            <label for="inputTrack" class="form-label">Track</label>

                                            <select id="inputTrack" class="form-select">
                                                <?php 

                                                    $SHS_DEPARTMENT = 4;
                                                
                                                    $track_sql = $con->prepare("SELECT 
                                                        program_id, track, acronym 
                                                        
                                                        FROM program 
                                                    WHERE department_id !=:department_id
                                                    GROUP BY track
                                                    ");

                                                    $track_sql->bindValue(":department_id", $SHS_DEPARTMENT);
                                                    $track_sql->execute();
                                                    

                                                    while($row = $track_sql->fetch(PDO::FETCH_ASSOC)){

                                                        $row_program_id = $row['program_id'];

                                                        $track = $row['track'];

                                                        $selected = ($row_program_id == $program_id) ? "selected" : "";

                                                        echo "<option value='$row_program_id' $selected>$track</option>";
                                                    }
                                                ?>
                                                <!-- <option selected>TVL</option>
                                                <option>Arts and Design</option> -->
                                            </select>
                                        </div>
                                        
                                        <div class="col-md-4">
                                            <label for="inputStrand" class="form-label">Strand</label>

                                            <select onchange="chooseStrand(this, <?php echo $pending_enrollees_id;?>)" 
                                                class="form-select" name="strand" id="strand">
                                                <?php 

                                                    $SHS_DEPARTMENT = 4;
                                                
                                                    $track_sql = $con->prepare("SELECT 
                                                        program_id, track, acronym 
                                                        
                                                        FROM program 
                                                    WHERE department_id !=:department_id
                                                    GROUP BY acronym
                                                    ");

                                                    $track_sql->bindValue(":department_id", $SHS_DEPARTMENT);
                                                    $track_sql->execute();

                                                    while($row = $track_sql->fetch(PDO::FETCH_ASSOC)){

                                                        $row_program_id = $row['program_id'];

                                                        $acronym = $row['acronym'];

                                                        $selected = ($row_program_id == $program_id) ? "selected" : "";

                                                        echo "<option value='$row_program_id' $selected>$acronym</option>";
                                                    }
                                                ?>
                                                <!-- <option selected>GAS</option>
                                                <option>HUMSS</option>
                                                <option>ICT</option>
                                                <option>HE</option>
                                                <option>ABM</option>
                                                <option>IE</option> -->

                                            </select>
                                        </div>

                                        <div class="col-md-6">
                                            <label for="standing_level" class="form-label">Standing</label>
                                            
                                            <select id="standing_level" class="form-select" name="standing_level">

                                                <option value="">Choose Section</option>
                                                <option selected value="1"<?php if ($student_course_level == 11) echo ' selected'; ?>>1st Year</option>
                                                <option value="2"<?php if ($student_course_level == 12) echo ' selected'; ?>>2nd Year</option>
                                                    
                                            </select>
                                            <input type="hidden" value="<?php echo $student_course_id;?>" id="student_course_id" name="student_course_id">
                                        </div>
                                        <div class="col-md-6">
                                            <label for="inputSemester" class="form-label">Semester</label>
                                            <input readonly type="text" value="<?php echo $current_school_year_period; ?>" class="form-control" id="inputSchoolYear" />
                                            <!-- <select id="inputSemester" class="form-select">
                                                <option <?php if ($current_school_year_period == 'First') echo 'selected'; ?>>First</option>
                                                <option <?php if ($current_school_year_period == 'Second') echo 'selected'; ?>>Second</option>
                                            </select> -->
                                        </div>
                                    </form>
                                <?php
                                }
                            ?>

                        </div>
                    </div>

                </div>

                <!-- Enrollment Form -->
                <div style="display: none;" class="container">
                    <h3 class="text-center text-primary">Enrollment Form</h3>
                    <div class="row col-md-12">

                        <?php
                            if($enrollment_id != null ){
                                ?>
                                    <div class="mb-4 col-md-3">
                                        <label for="">* Form Id</label>
                                        <input readonly style="width: 100%;" type="text" 
                                            value='<?php echo $enrollment_form_id;?>' class="form-control">
                                    </div>
                                <?php
                            }else if($enrollment_id == null){
                                ?>
                                    <div class="mb-4 col-md-3">
                                        <label for="">Form Id</label>
                                        <input readonly style="width: 100%;" type="text" 
                                            value='<?php echo $enrollment_form_id;?>' class="form-control">
                                    </div>
                                <?php
                            }
                        
                        ?>
                    
                        <div class="mb-4 col-md-3">
                            <label for="">Name</label>
                            <input readonly style="width: 100%;" type="text" 
                                value='<?php echo $student_fullname?>' class="form-control">
                        </div>  
                        <div class="mb-4 col-md-3">
                            <label for="">Admission Type</label>
                            <input readonly style="width: 100%;" type="text" 
                                value='New' class="form-control">
                        </div>



                        <div class="mb-4 col-md-3">
                            <label for="">Date Submitted</label>
                            <input readonly style="width: 100%;" type="text" 
                                value='<?php echo $date_creation?>' class="form-control">
                        </div>

                    </div>
                </div>

                <!-- Available Section -->

                <div class="card" id="transferee_available_section">
                    <div class="card-header">
                        <h5 class="text-start text-muted">Available Section</h5>
                    </div>

                    <div class="card-body">
                        <form method="POST">

                            <div id="availableTransfereeSectionTablev1"></div>

                            <table style=" font-size:13px;" 
                                id="availableTransfereeSectionTablev1x" 
                                class="table table-bordered table-hover"> 
                                <thead>
                                    <tr class="text-center"> 
                                        <th rowspan="2">Section Id</th>
                                        <th rowspan="2">Section Name</th>
                                        <th rowspan="2">Capacity</th>
                                        <th rowspan="2">Semester</th>
                                        <th rowspan="2">Term</th>
                                        <th rowspan="2">Choose</th>
                                        <th rowspan="2">Action</th>
                                    </tr>	
                                </thead> 	
                                <tbody>
                                    <?php

                                        $selected_section = "";
                                        $course_level = 11;
                                        $active = "yes";

                                        # Only Available now.

                                        $sql = $con->prepare("SELECT * FROM course
                                        WHERE program_id=:program_id
                                        AND active=:active
                                        -- AND course_level=:course_level
                                        ");
                                        $sql->bindValue(":program_id", $program_id);
                                        $sql->bindValue(":active", $active);
                                        // $sql->bindValue(":course_level", $course_level);

                                        $sql->execute();
                                    
                                        if($sql->rowCount() > 0){

                                            while($get_course = $sql->fetch(PDO::FETCH_ASSOC)){

                                                $course_id = $get_course['course_id'];
                                                $program_section = $get_course['program_section'];
                                                $school_year_term = $get_course['school_year_term'];
                                                $capacity = $get_course['capacity'];

                                                $section = new Section($con, $course_id);

                                                $selected_section= $program_section;

                                                $totalStudent = $section->GetTotalNumberOfStudentInSection(
                                                    $course_id, $current_school_year_id);

                                                $removeSection = "removeSection($course_id, \"$program_section\")";
                                                // $student_course_id = 5;
                                                
                                                $isClosed = $totalStudent >= $capacity;
    
                                                $isCloseddisabled = "<input name='selected_course_id' 
                                                    class='radio' value='$course_id' 
                                                    type='radio' " . ($course_id == $student_course_id ? "checked" : "") . " " . ($isClosed ? "disabled" : "") . ">";   
                                            
                                                    
                                                echo "

                                                <tr class='text-center'>
                                                    <td>$course_id</td>
                                                    <td>$program_section</td>
                                                    <td>$totalStudent / $capacity</td>
                                                    <td>$current_school_year_period</td>
                                                    <td>$school_year_term</td>
                                                    <td>
                                                        $isCloseddisabled    
                                                    </td>
                                                    <td>
                                                        <i onclick='$removeSection' style='cursor: pointer; color: orange; " . ($totalStudent != 0 ? "display: none;" : "") . "' class='fas fa-times'></i>
                                                    
                                                    </td>
                                                </tr>
                                            ";
                                            }
                                            
                                        }
                                    ?>
                                </tbody>
                            </table>
                            
                            <input type="hidden" id="firstname" name="firstname" value="<?php echo $firstname; ?>">
                            <input type="hidden" id="lastname" name="lastname" value="<?php echo $lastname; ?>">
                            <input type="hidden" id="middle_name" name="middle_name" value="<?php echo $middle_name; ?>">
                            <input type="hidden" id="password" name="password" value="<?php echo $password; ?>">
                            <input type="hidden" id="program_id" name="program_id" value="<?php echo $program_id; ?>">
                            <input type="hidden" id="civil_status" name="civil_status" value="<?php echo $civil_status; ?>">
                            <input type="hidden" id="nationality" name="nationality" value="<?php echo $nationality; ?>">
                            <input type="hidden" id="contact_number" name="contact_number" value="<?php echo $contact_number; ?>">
                            <input type="hidden" id="age" name="age" value="<?php echo $age; ?>">
                            <input type="hidden" id="guardian_name" name="guardian_name" value="<?php echo $guardian_name; ?>">
                            <input type="hidden" id="sex" name="sex" value="<?php echo $sex; ?>">
                            <input type="hidden" id="guardian_contact_number" name="guardian_contact_number" value="<?php echo $guardian_contact_number; ?>">
                            <input type="hidden" id="student_status" name="student_status" value="<?php echo $student_status; ?>">
                            <!-- <input type="hidden" id="program_section" name="program_section" value="<?php echo $selected_section; ?>"> -->
                            <input type="hidden" id="pending_enrollees_id" name="pending_enrollees_id" value="<?php echo $pending_enrollees_id; ?>">
                            <input type="hidden" id="address" name="address" value="<?php echo $address; ?>">
                            <input type="hidden" id="lrn" name="lrn" value="<?php echo $lrn; ?>">
                            <input type="hidden" id="birthday" name="birthday" value="<?php echo $birthday; ?>">
                            <input type="hidden" id="religion" name="religion" value="<?php echo $religion; ?>">
                            <input type="hidden" id="birthplace" name="birthplace" value="<?php echo $birthplace; ?>">
                            <input type="hidden" id="email" name="email" value="<?php echo $email; ?>">

                            <a href="transferee_process_enrollment.php?step1=true&id=<?php echo $pending_enrollees_id; ?>">
                                <button type="button" class="btn btn-outline-primary">Return</button>
                            </a>

                            <button type="submit"name="transferee_pending_choose_section"class="btn btn-primary">
                                Proceed
                            </button>

                        </form>
                    </div>
                </div>


            </div>

            <script>

                function chooseStrand(entity, pending_enrollees_id){

                        var strand = document.getElementById("strand").value;

                        // console.log("Selected value: " + strand);
                        Swal.fire({
                            icon: 'question',
                            title: `Update Strand?`,
                            showCancelButton: true,
                            confirmButtonText: 'Yes',
                            cancelButtonText: 'Cancel'
                        }).then((result) => {

                            if (result.isConfirmed) {
                                // REFX
                                $.ajax({
                                    url: '../ajax/pending/update_student_strand.php',
                                    type: 'POST',
                                    data: {
                                        strand, pending_enrollees_id
                                    },
                                    success: function(response) {

                                        console.log(response);

                                        // enrollment-details
                                        if(response == "success"){
                                            $('#enrollment-details').load(
                                                location.href + ' #enrollment-details'
                                            );
                                            $('#transferee_available_section').load(
                                                location.href + ' #transferee_available_section'
                                            );
                                        }


                                    }
                                });
                            }

                        });
                    }

                // $('#standing_level').on('change', function() {

                //     var standing_level = parseInt($(this).val());
                //     var program_id = parseInt($("#program_id").val());
                //     var student_course_id = parseInt($("#student_course_id").val());

                //     // Add a delay of 500ms
                //     setTimeout(function() {
                //                                 $.ajax({
                //         url: '../ajax/transferee_process/available_section.php',
                //         type: 'POST',
                //         data: {
                //             standing_level,program_id,student_course_id
                //         },
                //         dataType: 'json',
                //         success: function(response) {

                //             // console.log(response);

                //             if(response.length > 0){

                //                 var html = `
                //                 <table id="res" class="table table-striped table-bordered table-hover "  style="font-size:13px" cellspacing="0"  > 
                //                     <thead>
                //                         <tr class='text-center'> 
                //                             <th rowspan='2'>Section Id</th>
                //                             <th rowspan='2'>Section Name</th>
                //                             <th rowspan='2'>Capacity</th>
                //                             <th rowspan='2'>Semester</th>
                //                             <th rowspan='2'>Term</th>
                //                             <th rowspan='2'>Choose</th>
                //                             <th rowspan='2'>Action</th>
                //                         </tr>	
                //                     </thead> 	
                //                 `;

                //             $.each(response, function(index, value) {

                //                 var course_id = value.course_id;
                //                 var program_section = value.program_section;
                //                 var capacity = value.capacity;
                //                 var program_section = value.program_section;
                //                 var school_year_term = value.school_year_term;
                //                 var totalStudent = value.totalStudent;
                //                 var semester = value.semester;


                //                 var isClosed = totalStudent >= capacity;

                //                 // $removeSection = "removeSection($course_id, \"$program_section\")";

                //                 var removeSection = `removeSection(${course_id}, "${program_section}")`;

                //                 var isCloseddisabled = `<input name='selected_course_id' 
                //                                         class='radio' value='${course_id}' 
                //                                         type='radio' ${course_id == student_course_id ? 'checked' : ''} ${isClosed ? 'disabled' : ''}>`;

                //                 // var onClickFunction = '$removeSection';
                //                 var displayStyle = totalStudent !== 0 ? 'display: none;' : '';

                //                 var remove = `
                //                     <i onclick='${removeSection}' style='cursor: pointer; color: orange; ${displayStyle}' class='fas fa-times'></i>
                //                 `;

                //                 html += `
                //                     <body>
                //                         <tr class='text-center'>
                //                             <td>${course_id}</td>
                //                             <td>${program_section}</td>
                //                             <td>${totalStudent} / ${capacity}</td>
                //                             <td>${semester}</td>
                //                             <td>${school_year_term}</td>
                //                             <td>${isCloseddisabled}</td>
                //                             <td>${remove}</td>

                //                         </tr>
                //                     `;
               
                //             });

                //             html += `
                //                 </table>
                //                 `;

                //             html += `
                //                 <button type="submit" name="transferee_pending_choose_section"
                //                     class="btn btn-primary">
                //                     Proceed
                //                 </button>
                //             `;
                //             $('#availableTransfereeSectionTablev1').html(html);

                                
                //             }else{
                //                 var nothing = `
                //                     <div class="alert alert-warning text-center" role="alert">
                //                         No data found
                //                     </div>
                //                 `;
                //                 $('#availableTransfereeSectionTablev1').html(nothing);
                //             }

                            

                //             }
                //     });
                //     }, 150);



                // });

                function removeSection(course_id, program_section){
                    Swal.fire({
                            icon: 'question',
                            title: `I agreed to removed ${program_section}.`,
                            showCancelButton: true,
                            confirmButtonText: 'Yes',
                            cancelButtonText: 'No'
                        }).then((result) => {
                        if (result.isConfirmed) {
                            // console.log("nice")
                            $.ajax({
                                url: '../ajax/section/remove_section.php',
                                type: 'POST',
                                data: {
                                    course_id
                                },
                                success: function(response) {

                                    $('#availableTransfereeSectionTablev1').load(
                                        location.href + ' #availableTransfereeSectionTablev1'
                                    );

                                },
                                error: function(xhr, status, error) {
                                    // handle any errors here
                                }
                            });
                        } else {
                            // User clicked "No," perform alternative action or do nothing
                        }
                    });
                }
            </script>
            <?php
        }   

        
        # FOR EVALUATED (ONGOING) TRANSFEREE. STEP 2
        if(isset($_GET['st_id']) && isset($_GET['step2'])){

            $student_id = $_GET['st_id'];



            $student_get_step_2 = $con->prepare("SELECT *
                
                FROM student
                WHERE student_id=:student_id
                
                ");
            $student_get_step_2->bindValue(":student_id", $student_id);
            $student_get_step_2->execute();

            $student_course_id = $studentEnroll->GetStudentCourseIdById($student_id);
            $student_username = $studentEnroll->GetStudentUsername($student_id);

            $student = new Student($con, $student_username);

            $student_unique_id = $student->GetStudentUniqueId();

            $admission_status = $student->GetStudentAdmissionStatus();

            $checkEnrollmentEnrolled = $enrollment->CheckEnrollmentEnrolled($student_id,
                    $student_course_id, $current_school_year_id);

            $cashierEvaluated = $enrollment->CheckEnrollmentCashierApproved($student_id,
                    $student_course_id, $current_school_year_id);

            $payment_status = "";

            if($checkEnrollmentEnrolled == true && $cashierEvaluated == true){
                $payment_status = "Enrolled";

            }else if($checkEnrollmentEnrolled == false && $cashierEvaluated == true){
                $payment_status = "Approved";
            }else{
                $payment_status = "Waiting";
            }

            $getEnrollmentNonEnrolledDate = $enrollment
                ->GetEnrollmentNonEnrolledDate($student_id, $student_course_id,
                    $current_school_year_id);

            $getEnrollmentEnrolledDate = $enrollment
                ->GetEnrollmentEnrolledDate($student_id, $student_course_id,
                    $current_school_year_id);

            $proccess_date = null;

            // echo $getEnrollmentNonEnrolledDate;

            if($checkEnrollmentEnrolled == true){
                $proccess_date = $getEnrollmentEnrolledDate;
            }else{
                $proccess_date = $getEnrollmentNonEnrolledDate;
            }

            if($student_get_step_2->rowCount() > 0){

                # BASED.
                $student_row = $student_get_step_2->fetch(PDO::FETCH_ASSOC);

                $student_course_levelv2 = $student_row['course_level'];

                $student_course_idv2 = $student_row['course_id'];

                $student_firstnamev2 = $student_row['firstname'];
                $student_lastnamev2 = $student_row['lastname'];
                $student_fullnamev2 = $student_firstnamev2 . " " . $student_lastnamev2;
                
                $enrollment_id = $enrollment->GetEnrollmentId($student_id,
                    $student_course_idv2, $current_school_year_id);

                $enrollment_form_id = $enrollment->GetEnrollmentFormId($student_id,
                    $student_course_idv2, $current_school_year_id);

                $date_creationv2 = $enrollment->GetEnrollmenDate($student_id,
                    $student_course_idv2, $current_school_year_id);

                
                $student_program_id = $studentEnroll->GetStudentProgramId($student_course_idv2);

                $program_acronymv2 = $enrollment->GetEnrollmentProgram($student_program_id);
            }


            unset($_SESSION['enrollment_id']);

            if(isset($_POST['transferee_pending_choose_section_os'])
                && isset($_POST['selected_course_id'])){

                $_SESSION['enrollment_id'] = $enrollment_id;

                $firstname = $_POST['firstname'];
                $lastname = $_POST['lastname'];
                $middle_name = $_POST['middle_name'];
                $password = $_POST['password'];
                $program_id = $_POST['program_id'];
                $civil_status = $_POST['civil_status'];
                $nationality = $_POST['nationality'];
                $contact_number = $_POST['contact_number'];
                $age = $_POST['age'];
                $guardian_name = $_POST['guardian_name'];
                $sex = $_POST['sex'];
                $guardian_contact_number = $_POST['guardian_contact_number'];
                $student_status = $_POST['student_status'];
                $pending_enrollees_id = $_POST['pending_enrollees_id'];
                $address = $_POST['address'];
                $lrn = $_POST['lrn'];
                $birthday = $_POST['birthday'];
                $religion = $_POST['religion'];
                $birthplace = $_POST['birthplace'];
                $email = $_POST['email'];

                $selected_course_id_value = $_POST['selected_course_id'];


                $course_id = intval($_POST['selected_course_id']); 

                $section = new Section($con, $course_id);
                $doesSectionFull = $section->CheckSectionIsFull($course_id);

                if($doesSectionFull == true){
                    AdminUser::error("Selecrted Section is Full", "");
                    // return;
                }
                // echo $course_id;

                ##
                $get_available_section = $con->prepare("SELECT 
                        course_id, capacity, course_level, program_section

                        FROM course
                        WHERE course_id=:course_id
                        LIMIT 1");
                $get_available_section->bindValue(":course_id", $course_id);
                $get_available_section->execute();

                # Update enrollment

                // echo $course_id;

                $isRedirectAuto = $course_id == $student_course_idv2;

                if($doesSectionFull == false){

                    // if(false){
                    if($isRedirectAuto){
                        # Should not prompt.
                        $_SESSION['auto_redirect'] = true;
                        // echo $_SESSION['enrollment_id'];

                        # reef
                        // header("Location: transferee_process_enrollment.php?step3=true&id=$pending_enrollees_id&selected_course_id=$student_course_id");
                        header("Location: transferee_process_enrollment.php?step3=true&st_id=$student_id&selected_course_id=$student_course_idv2");
                        exit();
                    }
                    
                    // else if(false){
                    if($student_course_idv2 != 0 && $course_id != $student_course_idv2){

                        # Edit
                        $available_section = $get_available_section->fetch(PDO::FETCH_ASSOC);

                        $course_level = $available_section['course_level'];
                        $program_section = $available_section['program_section'];

                        # Change student course_id & course_level

                        if($get_available_section->rowCount() > 0){

                            if($enrollment_id != null){

                                $isSuccessChangeStudentCourseId = $old->UpdateSHSStudentCourseId(
                                $student_id, $course_id, $course_level);
                            
                                if($isSuccessChangeStudentCourseId){

                                    $wasChangingEnrollmentCourseId = $enrollment->UpdateSHSStudentEnrollmentCourseId(
                                        $enrollment_id, $course_id); 

                                    if($wasChangingEnrollmentCourseId){
                                        
                                        AdminUser::success("Student is now changed  into $program_section section.",
                                            "transferee_process_enrollment.php?step3=true&st_id=$student_id&selected_course_id=$course_id");
                                        exit();
                                    }
                                }else{
                                    # Error.
                                }
                            }else{
                                # 
                                
                                AdminUser::success("Student is now changed  into $program_section section.", "transferee_process_enrollment.php?step2=true&id=$pending_enrollees_id");
                                exit();
                            }

                            

                        }

                    }
                    // else if(false){
                    else if($student_course_idv2 == 0){

                        // echo "add";

                        $generateStudentUniqueId = $studentEnroll->GenerateUniqueStudentNumber();
                        $username = strtolower($lastname) . '.' . $generateStudentUniqueId . '@dcbt.ph';

                        $course_level = null;
                        // $get_available_section = $con->prepare("SELECT 
                        //     course_id, capacity, course_level, program_section

                        //     FROM course
                        //     WHERE course_id=:course_id
                        //     LIMIT 1");
                        // $get_available_section->bindValue(":course_id", $course_id);
                        // $get_available_section->execute();

                        $enrollment_form_id = $enrollment->GenerateEnrollmentFormId();

                        // if(false){
                        if($get_available_section->rowCount() > 0){

                            $available_section = $get_available_section->fetch(PDO::FETCH_ASSOC);

                            $capacity = $available_section['capacity'];
                            $course_level = $available_section['course_level'];
                            $program_section = $available_section['program_section'];

                            $enrolledStudents = $studentEnroll->CheckNumberOfStudentInSection($course_id,
                                "First");

                            if($capacity > $enrolledStudents && $doesSectionFull == false){

                                $sql = "INSERT INTO student (firstname, lastname, middle_name, password, civil_status, nationality, contact_number, birthday, age, guardian_name, guardian_contact_number, sex, student_status,
                                            course_id, student_unique_id, course_level, username,
                                            address, lrn, religion, birthplace, email) 
                                        VALUES (:firstname, :lastname, :middle_name, :password, :civil_status, :nationality, :contact_number, :birthday, :age, :guardian_name, :guardian_contact_number, :sex, :student_status,
                                            :course_id, :student_unique_id, :course_level, :username,
                                            :address, :lrn, :religion, :birthplace, :email)";

                                $stmt_insert = $con->prepare($sql);

                                $stmt_insert->bindParam(':firstname', $firstname);
                                $stmt_insert->bindParam(':lastname', $lastname);
                                $stmt_insert->bindParam(':middle_name', $middle_name);
                                $stmt_insert->bindParam(':password', $password);
                                $stmt_insert->bindParam(':civil_status', $civil_status);
                                $stmt_insert->bindParam(':nationality', $nationality);
                                $stmt_insert->bindParam(':contact_number', $contact_number);
                                $stmt_insert->bindParam(':birthday', $birthday);
                                $stmt_insert->bindParam(':age', $age);
                                $stmt_insert->bindParam(':guardian_name', $guardian_name);
                                $stmt_insert->bindParam(':guardian_contact_number', $guardian_contact_number);
                                $stmt_insert->bindParam(':sex', $sex);
                                $stmt_insert->bindParam(':student_status', $student_status);
                                $stmt_insert->bindParam(':course_id', $course_id);
                                $stmt_insert->bindParam(':student_unique_id', $generateStudentUniqueId);
                                $stmt_insert->bindParam(':course_level', $course_level);
                                $stmt_insert->bindParam(':username', $username);
                                $stmt_insert->bindParam(':address', $address);
                                $stmt_insert->bindParam(':lrn', $lrn);
                                $stmt_insert->bindParam(':religion', $religion);
                                $stmt_insert->bindParam(':birthplace', $birthplace);
                                $stmt_insert->bindParam(':email', $email);
                                
                                if($stmt_insert->execute()){

                                    // remove the existing pending table
                                    // Add to the enrollment with regostrar evaluated
                                    // tentative and aligned to the ccourse id

                                    $student_id = $con->lastInsertId();

                                    $enrollment_status = "tentative";
                                    $is_new_enrollee = 1;
                                    $is_transferee = 1;
                                    $registrar_evaluated = "yes";
                                    // $username = "generate";

                                    $insert_enrollment = $con->prepare("INSERT INTO enrollment
                                        (student_id, course_id, school_year_id, enrollment_status, is_new_enrollee, registrar_evaluated, is_transferee, enrollment_form_id)
                                        VALUES (:student_id, :course_id, :school_year_id, :enrollment_status, :is_new_enrollee, :registrar_evaluated, :is_transferee, :enrollment_form_id)");
                                                    
                                    $insert_enrollment->bindValue(':student_id', $student_id);
                                    $insert_enrollment->bindValue(':course_id', $course_id);
                                    $insert_enrollment->bindValue(':school_year_id', $current_school_year_id);
                                    $insert_enrollment->bindValue(':enrollment_status', $enrollment_status);
                                    $insert_enrollment->bindValue(':is_new_enrollee', $is_new_enrollee);

                                    # Modified
                                    $insert_enrollment->bindValue(':registrar_evaluated', "no");
                                    $insert_enrollment->bindValue(':is_transferee', $is_transferee);
                                    $insert_enrollment->bindValue(':enrollment_form_id', $enrollment_form_id);

                                    if($insert_enrollment->execute()){

                                        // Check enrollment course_id number with course_id capacity
                                        if($insert_enrollment->rowCount() > 0){

                                            $generated_enrollment_id = $con->lastInsertId();

                                            $_SESSION['enrollment_id'] = $generated_enrollment_id;

                                            // $section_url = "transferee_process_enrollment.php?step3=true&id=$pending_enrollees_id&selected_course_id=$selected_course_id_value";

                                            $section_url = "transferee_process_enrollment.php?step3=true&st_id=$student_id&selected_course_id=$selected_course_id_value";

                                            AdminUser::success("Student is now placed in $program_section section.", $section_url);
                                            exit();
                                        }
                                    }
                                }
                            }else{
                                echo "Capacity is full";
                                return;
                            }
        
                        }
                    }
                }
                
            }



            ?>
            <div class="row col-md-12">


                <div class="content">

                    <div class="form-header">
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
                                <th>Submitted on:</th>
                                </tr>
                                <tr>
                                <td><?php echo $enrollment_form_id;?></td>
                                <td><?php echo $admission_status;?></td>
                                <td><?php echo $student_unique_id; ?></td>
                                <td>Evaluation</td>

                                <td><?php
                                    $date = new DateTime($proccess_date);
                                    $formattedDate = $date->format('m/d/Y H:i');

                                    echo $formattedDate;
                                ?></td>
                                
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>

                <div class="process-status">
                    <table class="selection">
                        <tr>
                            <th class="checkDetails" id="icon-1">
                            <i  style="color: #FFFF;" class="bi bi-clipboard-check"></i>
                            </th>
                            <th style="color: #FFFF;" id="line-1">___________________________</th>
                            <th  class="findSection" id="icon-2">
                            <i style="color: #FFFF;" class="bi bi-building"></i>
                            </th>
                            <th   id="line-2">___________________________</th>
                            <th class="subConfirm" id="icon-3">
                            <i class="bi bi-journal"></i>
                            </th>
                        </tr>
                        <tr>
                            <td style="color: #FFFF;" class="checkDetails" id="process-1">Check details</td>
                            <td></td>
                            <td style="color: #FFFF;" class="findSection" id="process-2">Find section</td>
                            <td></td>
                            <td class="subConfirm" id="process-3">Subject Confirmation</td>
                        </tr>
                    </table>
                </div>

                <!-- Available Section -->
                <div id="transferee_available_section" class="container mt-4 mb-2">
                    <div class="container">
                    <h3 class="text-center text-success">Available Section For Transferee</h3>

                    <a href="../section/create.php">
                        <button class="mb-2 btn btn-sm btn-success" onclick="<?php 
                            $_SESSION['pending_enrollees_id'] = $pending_enrollees_id;
                            $_SESSION['process_enrollment'] = 'transferee';
                            ?>">
                            Section Maintenance
                        </button>
                    </a>

                    </div>

                    <form method="POST">
                        <table id="availableTransfereeSectionTable" class="table table-striped table-bordered table-hover "  style="font-size:13px" cellspacing="0"  > 
                            <thead>
                                <tr class="text-center"> 
                                    <th rowspan="2">Section Id</th>
                                    <th rowspan="2">Section Name</th>
                                    <th rowspan="2">Capacity</th>
                                    <th rowspan="2">Semester</th>
                                    <th rowspan="2">Term</th>
                                    <th rowspan="2">Choose</th>
                                    <th rowspan="2">Action</th>
                                </tr>	
                            </thead> 	
                            <tbody>
                                <?php

                                    $selected_section = "";
                                    $course_level = 11;
                                    $active = "yes";

                                    # Only Available now.

                                    $sql = $con->prepare("SELECT * FROM course
                                    WHERE program_id=:program_id
                                    AND active=:active
                                    -- AND course_level=:course_level
                                    ");
                                    $sql->bindValue(":program_id", $student_program_id);
                                    $sql->bindValue(":active", $active);
                                    // $sql->bindValue(":course_level", $course_level);

                                    $sql->execute();
                                
                                    if($sql->rowCount() > 0){

                                        while($get_course = $sql->fetch(PDO::FETCH_ASSOC)){

                                            $course_id = $get_course['course_id'];
                                            $program_section = $get_course['program_section'];
                                            $school_year_term = $get_course['school_year_term'];
                                            $capacity = $get_course['capacity'];
                                            $section = new Section($con, $course_id);

                                            $selected_section= $program_section;

                                            $totalStudent = $section->GetTotalNumberOfStudentInSection(
                                                $course_id, $current_school_year_id);

                                            $removeSection = "removeSection($course_id, \"$program_section\")";


                                            $isClosed = $totalStudent >= $capacity;
 
                                            $isCloseddisabled = "<input name='selected_course_id' class='radio' value='$course_id' type='radio' " . ($course_id == $student_course_idv2 ? "checked" : "") . " " . ($isClosed ? "disabled" : "") . ">";      
                                            
                                            // $student_course_id = 5;
                                            echo "
                                                <tr class='text-center'>
                                                    <td>$course_id</td>
                                                    <td>$program_section</td>
                                                    <td>$totalStudent / $capacity</td>
                                                    <td>$current_school_year_period</td>
                                                    <td>$school_year_term</td>
                                                    <td>
                                                    <input name='selected_course_id' class='radio'
                                                            value='$course_id' 
                                                            type='radio' " . ($course_id == $student_course_idv2 ? "checked" : "") . ">
                                                    </td>
                                                    <td>
                                                        <i onclick='$removeSection' style='cursor: pointer; color: orange; " . ($totalStudent != 0 ? "display: none;" : "") . "' class='fas fa-times'></i>
                                                    </td>
                                                </tr>
                                            ";
                                        }
                                        
                                    }
                                ?>
                            </tbody>
                        </table>
                        <input type="hidden" id="firstname" name="firstname" value="<?php echo $firstname; ?>">
                        <input type="hidden" id="lastname" name="lastname" value="<?php echo $lastname; ?>">
                        <input type="hidden" id="middle_name" name="middle_name" value="<?php echo $middle_name; ?>">
                        <input type="hidden" id="password" name="password" value="<?php echo $password; ?>">
                        <input type="hidden" id="program_id" name="program_id" value="<?php echo $program_id; ?>">
                        <input type="hidden" id="civil_status" name="civil_status" value="<?php echo $civil_status; ?>">
                        <input type="hidden" id="nationality" name="nationality" value="<?php echo $nationality; ?>">
                        <input type="hidden" id="contact_number" name="contact_number" value="<?php echo $contact_number; ?>">
                        <input type="hidden" id="age" name="age" value="<?php echo $age; ?>">
                        <input type="hidden" id="guardian_name" name="guardian_name" value="<?php echo $guardian_name; ?>">
                        <input type="hidden" id="sex" name="sex" value="<?php echo $sex; ?>">
                        <input type="hidden" id="guardian_contact_number" name="guardian_contact_number" value="<?php echo $guardian_contact_number; ?>">
                        <input type="hidden" id="student_status" name="student_status" value="<?php echo $student_status; ?>">
                        <!-- <input type="hidden" id="program_section" name="program_section" value="<?php echo $selected_section; ?>"> -->
                        <input type="hidden" id="pending_enrollees_id" name="pending_enrollees_id" value="<?php echo $pending_enrollees_id; ?>">
                        <input type="hidden" id="address" name="address" value="<?php echo $address; ?>">
                        <input type="hidden" id="lrn" name="lrn" value="<?php echo $lrn; ?>">
                        <input type="hidden" id="birthday" name="birthday" value="<?php echo $birthday; ?>">
                        <input type="hidden" id="religion" name="religion" value="<?php echo $religion; ?>">
                        <input type="hidden" id="birthplace" name="birthplace" value="<?php echo $birthplace; ?>">
                        <input type="hidden" id="email" name="email" value="<?php echo $email; ?>">

                        <!-- !!! -->

                        <button type="submit"name="transferee_pending_choose_section_os"class="btn btn-primary">
                            Proceed
                        </button>

                    </form>
                </div> 
            </div>
            <script>
                function removeSection(course_id, program_section){
                    Swal.fire({
                            icon: 'question',
                            title: `I agreed to removed ${program_section}.`,
                            showCancelButton: true,
                            confirmButtonText: 'Yes',
                            cancelButtonText: 'No'
                        }).then((result) => {
                        if (result.isConfirmed) {
                            // console.log("nice")
                            $.ajax({
                                url: '../ajax/section/remove_section.php',
                                type: 'POST',
                                data: {
                                    course_id
                                },
                                success: function(response) {

                                    $('#availableTransfereeSectionTable').load(
                                        location.href + ' #availableTransfereeSectionTable'
                                    );

                                },
                                error: function(xhr, status, error) {
                                    // handle any errors here
                                }
                            });
                        } else {
                            // User clicked "No," perform alternative action or do nothing
                        }
                    });
                }
            </script>
            <?php
        }   

        # FOR EVALUATED (ONGOING) TRANSFEREE. STEP 3
        if(isset($_GET['st_id']) 
            && isset($_GET['step3']) && $_GET['step3'] == "true"
             && isset($_GET['selected_course_id'])
            ){

            // echo "qweqwe";
            # Should not be dependent on pending table


            # PFP
            $student_id = $_GET['st_id'];
            $selected_course_id = $_GET['selected_course_id'];

            $student_get = $con->prepare("SELECT *
                
                FROM student
                WHERE student_id=:student_id
                
                ");
            $student_get->bindValue(":student_id", $student_id);
            $student_get->execute();

            if($student_get->rowCount() > 0){

                $student_row = $student_get->fetch(PDO::FETCH_ASSOC);

                $student_course_levelv2 = $student_row['course_level'];
                $student_course_idv2 = $student_row['course_id'];


                $enrollment_id = $enrollment->GetEnrollmentId($student_id,
                    $student_course_idv2, $current_school_year_id);

                $enrollment_form_id = $enrollment->GetEnrollmentFormId($student_id,
                    $student_course_idv2, $current_school_year_id);



            }

            if(isset($_SESSION['selected_course_id'])){
                unset($_SESSION['selected_course_id']);
            }
            
           
            $section = new Section($con, $selected_course_id);
            $subject = new Subject($con, $registrarLoggedIn);

            $section_name = $section->GetSectionName();

            $enroll = new StudentEnroll($con);

            $student_username = $enroll->GetStudentUsername($student_id);

            $student = new Student($con, $student_username);
            $enrollment = new Enrollment($con, $enroll);

            $student_unique_id = $student->GetStudentUniqueId();


            $enrollment_form_id = $enrollment->GetEnrollmentFormId($student_id,
                $selected_course_id, $current_school_year_id);

            $admission_status = $student->GetStudentAdmissionStatus();
                    $student_status = $student->GetStudentStatusv2();

            $checkEnrollmentEnrolled = $enrollment->CheckEnrollmentEnrolled($student_id,
                    $selected_course_id, $current_school_year_id);

            $cashierEvaluated = $enrollment->CheckEnrollmentCashierApproved($student_id,
                    $selected_course_id, $current_school_year_id);

            $payment_status = "";

            if($checkEnrollmentEnrolled == true && $cashierEvaluated == true){
                $payment_status = "Enrolled";

            }else if($checkEnrollmentEnrolled == false && $cashierEvaluated == true){
                $payment_status = "Approved";
            }else{
                $payment_status = "Waiting";
            }

            $getEnrollmentNonEnrolledDate = $enrollment
                ->GetEnrollmentNonEnrolledDate($student_id, $selected_course_id, $current_school_year_id);

            $getEnrollmentEnrolledDate = $enrollment
                ->GetEnrollmentEnrolledDate($student_id, $selected_course_id, $current_school_year_id);

            $proccess_date = null;

            if($checkEnrollmentEnrolled == true){
                $proccess_date = $getEnrollmentEnrolledDate;
            }else{
                $proccess_date = $getEnrollmentNonEnrolledDate;
            }


            // echo $section_name . " step 3";
            
            if(isset($_POST['selected_btn_student']) && $_POST['normal_selected_subject']){

                $subjects = $_POST['normal_selected_subject'];

                // echo $subjects;

                // $course_level = $_POST['course_level'];

                // echo $course_level;
                $isInserted = false;

                foreach ($subjects as $key => $value) {
                    # code...

                    $subject_id = $value;

                    $get_course_level = $subject->GetSubjectCourseLevel($subject_id);

                    # Check if inserted.

                    $getSubjectProgramId = $subject->GetSubjectProgramId($subject_id);

                    $check = $studentSubject->CheckStudentSubject($student_id, $subject_id,
                        $enrollment_id, $current_school_year_id);
                    
                    if($check == false){
                        $wasInserted = $studentSubject->InsertStudentSubject($student_id, $subject_id,
                            $enrollment_id, $get_course_level, $getSubjectProgramId, $current_school_year_id, "no");
                            
                        if($wasInserted == true){
                            $isInserted = true;
                        }
                    }

                }

                // if(false){
                if($isInserted == true){

                    // echo "success";
                    
                    // AdminUser::remove("Successfully added subjects",
                    //     "view_student_transferee_enrollment_review.php?inserted=true&id=$student_id&p_id=$pending_enrollees_id&e_id=$enrollment_id");

                    // $url = "../enrollees/view_student_transferee_enrollment_review.php?inserted=true&id=$student_id&p_id=$pending_enrollees_id&e_id=$enrollment_id";
                    $url = "../enrollees/view_student_transferee_enrollment_review_student.php?inserted=true&id=$student_id&e_id=$enrollment_id";

                    // AdminUser::success("Successfully added subjects",
                    //     "transferee_process_enrollment.php?step3=true&id=$pending_enrollees_id&selected_course_id=$selected_course_id");

                    AdminUser::success("Successfully added subjects",
                        "$url");

                    $_SESSION['selected_course_id'] = $selected_course_id;
                    exit();
                }
                
            }

            ?>
                <div class="row col-md-12">


                    <div class="content">

                        <div class="form-header">
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
                                    <th>Submitted on:</th>
                                    </tr>
                                    <tr>
                                    <td><?php echo $enrollment_form_id;?></td>
                                    <td>Transferee</td>
                                    <td><?php echo $student_unique_id;?></td>
                                    <td>Evaluation</td>

                                    <td><?php
                                        $date = new DateTime($proccess_date);
                                        $formattedDate = $date->format('m/d/Y H:i');

                                        echo $formattedDate;
                                    ?></td>
                                    
                                    </tr>
                                </table>
                            </div>
                        </div>
                    </div>

                    <div class="process-status">
                        <table class="selection">
                            <tr>
                                <th class="checkDetails" id="icon-1">
                                    <i  style="color: #FFFF;" class="bi bi-clipboard-check"></i>
                                </th>

                                <th style="color: #FFFF;" id="line-1">___________________________</th>
                                <th  class="findSection" id="icon-2">
                                    <i style="color: #FFFF;" class="bi bi-building"></i>
                                </th>

                                <th  style="color: #FFFF;"  id="line-2">___________________________</th>
                                <th class="subConfirm" id="icon-3">
                                    <i style="color: #FFFF;" class="bi bi-journal"></i>
                                </th>
                            </tr>
                            <tr>
                                <td style="color: #FFFF;" class="checkDetails" id="process-1">Check details</td>
                                <td></td>
                                <td style="color: #FFFF;" class="findSection" id="process-2">Find section</td>
                                <td></td>
                                <td style="color: #FFFF;" class="subConfirm" id="process-3">Subject Confirmation</td>
                            </tr>
                        </table>
                    </div>




                    <div class="container mt-4 mb-2">
                        <h3 class="mb-3 text-center text-muted">S.Y <?php echo $current_school_year_term;?> <?php echo $current_school_year_period;?> Semester</h3>
                        <h4 class="mb-3 text-center text-muted">Selected Section: <?php echo $section_name;?> Subjects </h4>

                    <form method="POST">

                            <table class="table table-hover "  style="font-size:15px" cellspacing="0"> 
                                <thead>
                                    <tr class="text-center"">
                                        <th>Id</th>
                                        <th>Code</th>
                                        <th>Description</th>
                                        <th>Unit</th>
                                        <th>Pre-Requisite</th>
                                        <th>Type</th>
                                        <th>
                                            Action
                                        </th>
                                    </tr>
                                </thead>

                                <tbody>
                                    <?php 
                                    
                                        $sql = $con->prepare("SELECT t2.* FROM course as t1

                                            INNER JOIN subject as t2 ON t2.course_id = t1.course_id

                                            WHERE t1.course_id=:course_id
                                            AND t1.course_level=:course_level
                                            AND t2.semester=:semester
                                            AND t1.active='yes'");

                                        $sql->bindValue(":course_id", $selected_course_id);
                                        $sql->bindValue(":course_level", $student_course_levelv2);
                                        $sql->bindValue(":semester", $current_school_year_period);
                                        $sql->execute();

                                        if($sql->rowCount() > 0){

                                            while($row = $sql->fetch(PDO::FETCH_ASSOC)){

                                                $subject_id = $row['subject_id'];
                                                $subject_code = $row['subject_code'];
                                                $subject_title = $row['subject_title'];
                                                $pre_requisite = $row['pre_requisite'];
                                                $subject_type = $row['subject_type'];
                                                $unit = $row['unit'];

                                                $student_student_subject_id = 0;

                                                $get_student_subject = $studentSubject->
                                                    GetNonFinalizedStudentSubject($student_id, $subject_id,
                                                    $enrollment_id, $current_school_year_id);

                                                
                                                if(count($get_student_subject) > 0){
                                                    $student_student_subject_id = $get_student_subject['subject_id'];
                                                    // echo $student_student_subject_id;
                                                }
                                                // $student_student_subject_id = 0;
                                                

                                                echo "
                                                    <tr class='text-center'>
                                                        <td>$subject_id</td>
                                                        <td>$subject_code</td>
                                                        <td>$subject_title</td>
                                                        <td>$unit</td>
                                                        <td>$pre_requisite</td>
                                                        <td>$subject_type</td>
                                                        <td>
                                                            <input name='normal_selected_subject[]' 
                                                                value='" . $subject_id . "'
                                                                type='checkbox'" . ($student_student_subject_id == $subject_id ? " checked" : "") . ">
                                                        </td>
                                                    </tr>
                                                ";
                                            }

                                        }
                                    ?>

                                </tbody>

                            </table>

                            <h3 class="mb-3 text-muted">Added Subjects</h3>
                            <table id="addedSubjectsTable" class="table table-bordered table-hover "  style="font-size:15px" cellspacing="0"> 
                                <thead>
                                    <tr class="text-center"">
                                        <th>Id</th>
                                        <th>Section</th>
                                        <th>Code</th>
                                        <th>Description</th>
                                        <th>Unit</th>
                                        <th>Pre-Requisite</th>
                                        <th>Type</th>
                                        <th>
                                            Action
                                        </th>
                                    </tr>
                                </thead>

                                <!-- <tbody>
                                    <?php 
                                    
                                        $sql = $con->prepare("SELECT 
                                            t1.program_section,
                                            t2.* 
                                            
                                            FROM course as t1

                                            INNER JOIN subject as t2 ON t2.course_id = t1.course_id

                                            WHERE t1.course_id !=:course_id
                                            AND t1.active='yes'
                                            AND t1.course_level=:course_level
                                            AND t2.semester=:semester
                                            ");

                                        $sql->bindValue(":course_id", $selected_course_id);
                                        $sql->bindValue(":course_level", $student_course_levelv2);
                                        $sql->bindValue(":semester", $current_school_year_period);
                                        $sql->execute();

                                        if($sql->rowCount() > 0){

                                            while($row = $sql->fetch(PDO::FETCH_ASSOC)){

                                                $subject_id = $row['subject_id'];
                                                $subject_code = $row['subject_code'];
                                                $subject_title = $row['subject_title'];
                                                $pre_requisite = $row['pre_requisite'];
                                                $subject_type = $row['subject_type'];
                                                $unit = $row['unit'];
                                                $course_level = $row['course_level'];
                                                $program_section = $row['program_section'];
                                                
                                                $student_student_subject_id = 0;

                                                $get_student_subject = $studentSubject->
                                                    GetNonFinalizedStudentSubject($student_id, $subject_id,
                                                    $enrollment_id, $current_school_year_id);

                                                
                                                if(count($get_student_subject) > 0){
                                                    $student_student_subject_id = $get_student_subject['subject_id'];
                                                    // echo $student_student_subject_id;
                                                }



                                                echo "
                                                    <tr class='text-center'>
                                                        <td>$subject_id</td>
                                                        <td>$program_section</td>
                                                        <td>$subject_code</td>
                                                        <td>$subject_title</td>
                                                        <td>$unit</td>
                                                        <td>$pre_requisite</td>
                                                        <td>$subject_type</td>
                                                        <td>
                                                            <input name='normal_selected_subject[]' 
                                                                value='" . $subject_id . "'
                                                                type='checkbox'" . ($student_student_subject_id == $subject_id ? " checked" : "") . ">
                                                        </td>
                                                    </tr>
                                                ";
                                            }

                                        }
                                    ?>

                                </tbody> -->
                                
                            </table>
                            <?php
                                if($student_id != null){

                                    $_SESSION['selected_course_id'] = $selected_course_id;

                                    ?>
                                        <button name="selected_btn_student" type="submit" 
                                            class="btn btn-primary btn">Add Subjects
                                        </button>

                                        <!-- <a href="../enrollees/view_student_transferee_enrollment_review.php?inserted=true&id=<?php echo $student_id?>&p_id=<?php echo $pending_enrollees_id?>&e_id=<?php echo $enrollment_id;?>">

                                            <button type="button" 
                                                class="btn btn-outline-info btn">Review Insertion
                                            </button>
                                        </a> -->

                                        <a href="../enrollees/view_student_transferee_enrollment_review_student.php?inserted=true&id=<?php echo $student_id?>&e_id=<?php echo $enrollment_id;?>">
                                        
                                            <button type="button" 
                                                class="btn btn-outline-info btn">Review Insertionv2
                                            </button>
                                        </a>
                                    <?php
                                }else{
                                    ?>
                                        <button type="button" disabled
                                            class="btn btn-outline-success btn">Select Section First
                                        </button>
                                    <?php 
                                }
                            ?>
                        <a href="transferee_process_enrollment.php?step2=true&st_id=<?php echo $student_id;?>">
                            <button type="button" class="btn-secondary btn">
                                Go backv2
                            </button>
                        </a>
                    </form>

                    </div> 
                </div>

                <script>
                    function add_non_transferee(student_id, subject_id,
                        course_level, school_year_id,
                        subject_code, enrollment_id){

                        Swal.fire({
                            icon: 'question',
                            title: `Inserting ${subject_code}`,
                            showCancelButton: true,
                            confirmButtonText: 'Yes',
                            cancelButtonText: 'No'
                        }).then((result) => {
                            if (result.isConfirmed) {
                                // console.log("nice")
                                $.ajax({
                                    url: '../ajax/enrollee/add_non_credit_subject.php',
                                    type: 'POST',
                                    data: {
                                        student_id, subject_id,
                                        course_level, school_year_id,
                                        enrollment_id
                                    },
                                    success: function(response) {
                                        // console.log(response);
                                        // alert(response);
                                        // window.location.href = 'transferee_process_enrollment.php?step3=true&id=101&selected_course_id=415';
                                        alert(response);

                                    },
                                    error: function(xhr, status, error) {
                                        // handle any errors here
                                    }
                                });
                            } else {
                                // User clicked "No," perform alternative action or do nothing
                            }
                        });

                       

                    }

                    function add_credit_transferee(student_id, subject_id,
                        course_level, school_year_id, course_id,
                        subject_title, subject_code, enrollment_id){

                        Swal.fire({
                            icon: 'question',
                            title: `Inserting as credited ${subject_code}`,
                            showCancelButton: true,
                            confirmButtonText: 'Yes',
                            cancelButtonText: 'No'

                        }).then((result) => {
                            if (result.isConfirmed) {

                                $.ajax({
                                    url: '../ajax/enrollee/add_credit_subject.php',
                                    type: 'POST',
                                    data: {
                                        student_id, subject_id,
                                        course_level, school_year_id,
                                        course_id, subject_title, enrollment_id
                                    },
                                    success: function(response) {

                                        // console.log(response);
                                        alert(response);

                                        // Admin::success("", "");

                                        // window.location.href = 'transfee_enrollees.php';
                                    },
                                    error: function(xhr, status, error) {
                                        // handle any errors here
                                    }
                                });
                            } else {
                                // User clicked "No," perform alternative action or do nothing
                            }
                        });

                       
                    }
                    $(document).ready(function(){

                        var course_id = `<?php echo $selected_course_id;?>`;
                        var course_level = `<?php echo $student_course_levelv2;?>`;
                        var semester = `<?php echo $current_school_year_period;?>`;
                        var student_id = `<?php echo $student_id;?>`;
                        var enrollment_id = `<?php echo $enrollment_id;?>`;
                        
                        var addedSubjectTable = $('#addedSubjectsTable').DataTable({
                            'processing': true,
                            'serverSide': true,
                            'serverMethod': 'POST',
                            'ajax': {
                                'url':`addedSubjectDataTable.php?id=${course_id}&level=${course_level}&semester=${semester}&st_id=${student_id}&e_id=${enrollment_id}`
                            },
                            'lengthChange': false,

                            'columns': [
                                { data: 'subject_id' },
                                { data: 'program_section' },
                                { data: 'subject_code' },
                                { data: 'subject_title' },
                                { data: 'unit' },
                                { data: 'pre_requisite' },
                                { data: 'subject_type' },
                                { data: 'actions1' }
                            ]
                        });

                        var id = '<?php echo $student_id; ?>';  
                    
                        var table = $('#transferee_selection_table').DataTable({
                            'processing': true,
                            'serverSide': true,
                            'serverMethod': 'POST',
                            'ajax': {
                                'url':'transfereeEnrollmentDataList.php?id=' + id
                            },
                            'columns': [
                                { data: 'subject_id' },
                                { data: 'subject_code' },
                                { data: 'subject_title' },
                                { data: 'unit' },
                                { data: 'semester' },
                                { data: 'subject_type' },
                                { data: 'actions2' },
                                { data: 'actions3' }
                            ]
                        });
                    });
                </script>
            <?php
        }
        
        # FOR NON EVALUATED (NEW TRANSFEREE) STEP 3
        ##
        if(isset($_GET['id']) && isset($_GET['step3']) 
            && $_GET['step3'] == "true"
            && isset($_GET['selected_course_id'])){

            if(isset($_SESSION['selected_course_id'])){
                unset($_SESSION['selected_course_id']);
            }
            
            $selected_course_id = $_GET['selected_course_id'];
           
            $section = new Section($con, $selected_course_id);
            $subject = new Subject($con, $registrarLoggedIn);

            $section_name = $section->GetSectionName();

            // echo $section_name . " step 3";
            
            if(isset($_POST['selected_btn']) && $_POST['normal_selected_subject']){

                $subjects = $_POST['normal_selected_subject'];

                // $course_level = $_POST['course_level'];

                // echo $course_level;
                $isInserted = false;

                foreach ($subjects as $key => $value) {
                    # code...

                    $subject_id = $value;
                    // echo $subject_id;
                    // echo "<br>";

                    $get_course_level = $subject->GetSubjectCourseLevel($subject_id);

                    # Check if inserted.

                    $getSubjectProgramId = $subject->GetSubjectProgramId($subject_id);

                    $checkSubjectExists = $studentSubject->CheckStudentSubject($student_id, $subject_id,
                        $enrollment_id, $current_school_year_id);
                    
                    if($checkSubjectExists == false){

                        $wasInserted = $studentSubject->InsertStudentSubject($student_id, $subject_id,
                            $enrollment_id, $get_course_level, $getSubjectProgramId, $current_school_year_id, "no");
                            
                        if($wasInserted == true){
                            $isInserted = true;
                        }
                    }

                }

                if($isInserted == true){

                    # ANALYZED IF TRANSFEREE STUDENT IS REGULAR OR IRREGULAR


                    // echo "success";
                    
                    // AdminUser::remove("Successfully added subjects",
                    //     "view_student_transferee_enrollment_review.php?inserted=true&id=$student_id&p_id=$pending_enrollees_id&e_id=$enrollment_id");

                    $url = "../enrollees/view_student_transferee_enrollment_review.php?inserted=true&id=$student_id&p_id=$pending_enrollees_id&e_id=$enrollment_id";

                    // AdminUser::success("Successfully added subjects",
                    //     "transferee_process_enrollment.php?step3=true&id=$pending_enrollees_id&selected_course_id=$selected_course_id");

                    AdminUser::success("Successfully added subjects",
                        "$url");
                    $_SESSION['selected_course_id'] = $selected_course_id;
                    exit();
                }
                
            }

            ?>
                <div class="row col-md-12">


                    <div class="content">

                        <div class="form-header">
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
                                    <th>Submitted on:</th>
                                    </tr>
                                    <tr>
                                    <td><?php echo $enrollment_form_id;?></td>
                                    <td>Transferee</td>
                                    <td>N/A</td>
                                    <td>Evaluation</td>

                                    <td><?php
                                        $date = new DateTime($date_creation);
                                        $formattedDate = $date->format('m/d/Y H:i');

                                        echo $formattedDate;
                                    ?></td>
                                    
                                    </tr>
                                </table>
                            </div>
                        </div>
                    </div>

                    <div class="process-status">
                        <table class="selection">
                            <tr>
                                <th class="checkDetails" id="icon-1">
                                <i  style="color: #FFFF;" class="bi bi-clipboard-check"></i>
                                </th>
                                <th style="color: #FFFF;" id="line-1">___________________________</th>
                                <th  class="findSection" id="icon-2">
                                <i style="color: #FFFF;" class="bi bi-building"></i>
                                </th>
                                <th  style="color: #FFFF;"  id="line-2">___________________________</th>
                                <th class="subConfirm" id="icon-3">
                                <i style="color: #FFFF;" class="bi bi-journal"></i>
                                </th>
                            </tr>
                            <tr>
                                <td style="color: #FFFF;" class="checkDetails" id="process-1">Check details</td>
                                <td></td>
                                <td style="color: #FFFF;" class="findSection" id="process-2">Find section</td>
                                <td></td>
                                <td style="color: #FFFF;" class="subConfirm" id="process-3">Subject Confirmation</td>
                            </tr>
                        </table>
                    </div>

                    <div class="container mt-4 mb-2">
                        <h3 class="mb-3 text-center text-muted">S.Y <?php echo $current_school_year_term;?> <?php echo $current_school_year_period;?> Semester</h3>
                        <h4 class="mb-3 text-center text-muted">Selected Section: <?php echo $section_name;?> Subjects </h4>

                        <form method="POST">

                                <table class="table table-bordered table-hover "  style="font-size:15px" cellspacing="0"> 
                                    <thead>
                                        <tr class="text-center"">
                                            <th>Id</th>
                                            <th>Code</th>
                                            <th>Description</th>
                                            <th>Unit</th>
                                            <th>Pre-Requisite</th>
                                            <th>Type</th>
                                            <th>
                                                Action
                                            </th>
                                        </tr>
                                    </thead>

                                    <tbody>
                                        <?php 
                                        
                                            $sql = $con->prepare("SELECT t2.* FROM course as t1

                                                INNER JOIN subject as t2 ON t2.course_id = t1.course_id

                                                WHERE t1.course_id=:course_id
                                                AND t1.course_level=:course_level
                                                AND t2.semester=:semester
                                                AND t1.active='yes'");

                                            $sql->bindValue(":course_id", $selected_course_id);
                                            $sql->bindValue(":course_level", $student_course_level);
                                            $sql->bindValue(":semester", $current_school_year_period);
                                            $sql->execute();

                                            if($sql->rowCount() > 0){

                                                while($row = $sql->fetch(PDO::FETCH_ASSOC)){

                                                    $subject_id = $row['subject_id'];
                                                    $subject_code = $row['subject_code'];
                                                    $subject_title = $row['subject_title'];
                                                    $pre_requisite = $row['pre_requisite'];
                                                    $subject_type = $row['subject_type'];
                                                    $unit = $row['unit'];

                                                    $student_student_subject_id = 0;

                                                    $get_student_subject = $studentSubject->
                                                        GetNonFinalizedStudentSubject($student_id, $subject_id,
                                                        $enrollment_id, $current_school_year_id);

                                                    
                                                    if(count($get_student_subject) > 0){
                                                        $student_student_subject_id = $get_student_subject['subject_id'];
                                                        // echo $student_student_subject_id;
                                                    }
                                                    // $student_student_subject_id = 0;
                                                    

                                                    echo "
                                                        <tr class='text-center'>
                                                            <td>$subject_id</td>
                                                            <td>$subject_code</td>
                                                            <td>$subject_title</td>
                                                            <td>$unit</td>
                                                            <td>$pre_requisite</td>
                                                            <td>$subject_type</td>
                                                            <td>
                                                                <input name='normal_selected_subject[]' 
                                                                    value='" . $subject_id . "'
                                                                    type='checkbox'" . ($student_student_subject_id == $subject_id ? " checked" : "") . ">
                                                            </td>
                                                        </tr>
                                                    ";
                                                }

                                            }
                                        ?>

                                    </tbody>

                                </table>
                                <!-- <button type="button" class="btn btn-sm btn-outline-primary" onclick="showDiv()">Add Subject</button> -->
                                <div class="added_subjects" id="subjectDiv" style="display: block;">

                                    <h3 class="mb-3 text-success">Add Subjects</h3>
                                    <table id="addedSubjectsTable" class="table table-bordered table-hover "  style="font-size:15px" cellspacing="0"> 
                                        <thead>
                                            <tr class="text-center"">
                                                <th>Id</th>
                                                <th>Section</th>
                                                <th>Code</th>
                                                <th>Description</th>
                                                <th>Unit</th>
                                                <th>Pre-Requisite</th>
                                                <th>Type</th>
                                                <th>
                                                    Action
                                                </th>
                                            </tr>
                                        </thead>

                                        <!-- <tbody>
                                            <?php 
                                            
                                                $sql = $con->prepare("SELECT 
                                                    t1.program_section,
                                                    t2.* 
                                                    
                                                    FROM course as t1

                                                    INNER JOIN subject as t2 ON t2.course_id = t1.course_id

                                                    WHERE t1.course_id !=:course_id
                                                    AND t1.active='yes'
                                                    AND t1.course_level=:course_level
                                                    AND t2.semester=:semester
                                                    ");

                                                $sql->bindValue(":course_id", $selected_course_id);
                                                $sql->bindValue(":course_level", $student_course_level);
                                                $sql->bindValue(":semester", $current_school_year_period);
                                                $sql->execute();

                                                if($sql->rowCount() > 0){

                                                    while($row = $sql->fetch(PDO::FETCH_ASSOC)){

                                                        $subject_id = $row['subject_id'];
                                                        $subject_code = $row['subject_code'];
                                                        $subject_title = $row['subject_title'];
                                                        $pre_requisite = $row['pre_requisite'];
                                                        $subject_type = $row['subject_type'];
                                                        $unit = $row['unit'];
                                                        $course_level = $row['course_level'];
                                                        $program_section = $row['program_section'];
                                                        
                                                        $student_student_subject_id = 0;

                                                        $get_student_subject = $studentSubject->
                                                            GetNonFinalizedStudentSubject($student_id, $subject_id,
                                                            $enrollment_id, $current_school_year_id);

                                                        
                                                        if(count($get_student_subject) > 0){
                                                            $student_student_subject_id = $get_student_subject['subject_id'];
                                                            // echo $student_student_subject_id;
                                                        }



                                                        echo "
                                                            <tr class='text-center'>
                                                                <td>$subject_id</td>
                                                                <td>$program_section</td>
                                                                <td>$subject_code</td>
                                                                <td>$subject_title</td>
                                                                <td>$unit</td>
                                                                <td>$pre_requisite</td>
                                                                <td>$subject_type</td>
                                                                <td>
                                                                    <input name='normal_selected_subject[]' 
                                                                        value='" . $subject_id . "'
                                                                        type='checkbox'" . ($student_student_subject_id == $subject_id ? " checked" : "") . ">
                                                                </td>
                                                            </tr>
                                                        ";
                                                    }

                                                }
                                            ?>

                                        </tbody> -->

                                        
                                    </table>
                                </div>

                                <?php
                                    if($student_id != null){

                                        $_SESSION['selected_course_id'] = $selected_course_id;

                                        ?>
                                            <button name="selected_btn" type="submit" 
                                                class="btn btn-primary btn">Add Subjects
                                            </button>

                                            <a href="../enrollees/view_student_transferee_enrollment_review.php?inserted=true&id=<?php echo $student_id?>&p_id=<?php echo $pending_enrollees_id?>&e_id=<?php echo $enrollment_id;?>">
                                                <button type="button" 
                                                    class="btn btn-outline-info btn">Review Insertion
                                                </button>
                                            </a>
                                        <?php
                                    }else{
                                        ?>
                                            <button type="button" disabled
                                                class="btn btn-outline-success btn">Select Section First
                                            </button>
                                        <?php 
                                    }
                                ?>
                            <a href="transferee_process_enrollment.php?step2=true&id=<?php echo $pending_enrollees_id;?>">
                                <button type="button" class="btn-secondary btn">
                                    Go back
                                </button>
                            </a>
                        </form>

                    </div> 
                </div>

                <script>

                    function showDiv() {
                        var subjectDiv = document.getElementById("subjectDiv");
                            if (subjectDiv.style.display === "none") {
                                subjectDiv.style.display = "block";
                            } else {
                                subjectDiv.style.display = "none";
                            }
                    }

                    function add_non_transferee(student_id, subject_id,
                        course_level, school_year_id,
                        subject_code, enrollment_id){

                        Swal.fire({
                            icon: 'question',
                            title: `Inserting ${subject_code}`,
                            showCancelButton: true,
                            confirmButtonText: 'Yes',
                            cancelButtonText: 'No'
                        }).then((result) => {
                            if (result.isConfirmed) {
                                // console.log("nice")
                                $.ajax({
                                    url: '../ajax/enrollee/add_non_credit_subject.php',
                                    type: 'POST',
                                    data: {
                                        student_id, subject_id,
                                        course_level, school_year_id,
                                        enrollment_id
                                    },
                                    success: function(response) {
                                        // console.log(response);
                                        // alert(response);
                                        // window.location.href = 'transferee_process_enrollment.php?step3=true&id=101&selected_course_id=415';
                                        alert(response);

                                    },
                                    error: function(xhr, status, error) {
                                        // handle any errors here
                                    }
                                });
                            } else {
                                // User clicked "No," perform alternative action or do nothing
                            }
                        });

                       

                    }

                    function add_credit_transferee(student_id, subject_id,
                        course_level, school_year_id, course_id,
                        subject_title, subject_code, enrollment_id){

                        Swal.fire({
                            icon: 'question',
                            title: `Inserting as credited ${subject_code}`,
                            showCancelButton: true,
                            confirmButtonText: 'Yes',
                            cancelButtonText: 'No'

                        }).then((result) => {
                            if (result.isConfirmed) {

                                $.ajax({
                                    url: '../ajax/enrollee/add_credit_subject.php',
                                    type: 'POST',
                                    data: {
                                        student_id, subject_id,
                                        course_level, school_year_id,
                                        course_id, subject_title, enrollment_id
                                    },
                                    success: function(response) {

                                        // console.log(response);
                                        alert(response);

                                        // Admin::success("", "");

                                        // window.location.href = 'transfee_enrollees.php';
                                    },
                                    error: function(xhr, status, error) {
                                        // handle any errors here
                                    }
                                });
                            } else {
                                // User clicked "No," perform alternative action or do nothing
                            }
                        });

                       
                    }
                    $(document).ready(function(){

                        var course_id = `<?php echo $selected_course_id;?>`;
                        var course_level = `<?php echo $student_course_level;?>`;
                        var semester = `<?php echo $current_school_year_period;?>`;
                        var student_id = `<?php echo $student_id;?>`;
                        var enrollment_id = `<?php echo $enrollment_id;?>`;
                        
                        var addedSubjectTable = $('#addedSubjectsTable').DataTable({
                            'processing': true,
                            'serverSide': true,
                            'serverMethod': 'POST',
                            'ajax': {
                                'url':`addedSubjectDataTable.php?id=${course_id}&level=${course_level}&semester=${semester}&st_id=${student_id}&e_id=${enrollment_id}`
                            },
                            'lengthChange': false, // Disable the length change

                            'columns': [
                                { data: 'subject_id' },
                                { data: 'program_section' },
                                { data: 'subject_code' },
                                { data: 'subject_title' },
                                { data: 'unit' },
                                { data: 'pre_requisite' },
                                { data: 'subject_type' },
                                { data: 'actions1' }
                            ]
                        });

                        var id = '<?php echo $student_id; ?>';  
                    
                        var table = $('#transferee_selection_table').DataTable({
                            'processing': true,
                            'serverSide': true,
                            'serverMethod': 'POST',
                            'ajax': {
                                'url':'transfereeEnrollmentDataList.php?id=' + id
                            },
                            'columns': [
                                { data: 'subject_id' },
                                { data: 'subject_code' },
                                { data: 'subject_title' },
                                { data: 'unit' },
                                { data: 'semester' },
                                { data: 'subject_type' },
                                { data: 'actions2' },
                                { data: 'actions3' }
                            ]
                        });
                    });
                </script>
            <?php
        }
        

        ##
        ##
        if(isset($_GET['id']) 
            && isset($_GET['step3x'])
             && isset($_GET['selected_course_id'])
            ){

            $selected_course_id = $_GET['selected_course_id'];
           
            $section = new Section($con, $selected_course_id);

            $section_name = $section->GetSectionName();

            // echo $section_name . " step 3";
            
            ?>
                <div class="row col-md-12">
                    <div class="container mt-4 mb-2">
                        <h4 class="mb-3 text-center text-muted">Selected Section: <?php echo $section_name;?> Subjects </h4>
                        <h4 class="mb-3 text-center text-success">Enrollment Subjects Subjects </h4>
                        <h5 class="mb-3 text-center text-muted">S.Y <?php echo $current_school_year_term;?> <?php echo $current_school_year_period;?> Semester</h5>

                        <table id="transferee_selection_table" class="table table-bordered table-hover "  style="font-size:15px" cellspacing="0"> 
                            <thead>
                                <tr>
                                    <th>Id</th>
                                    <th>Code</th>
                                    <th>Description</th>
                                    <th>Unit</th>
                                    <th>Type</th>
                                    <th>Semester</th>
                                    <th>Non-Credit</th>
                                    <th>Credit</th>
                                </tr>
                            </thead>
                        </table>
                        <?php
                            if($student_id != null){

                                // echo $_SESSION['enrollment_id'];
                                ?>
                                    <!-- <button type="submit" name="shs_irreg_subject_load_btn"
                                        id="shs_irreg_subject_load_btn"
                                        class="btn btn-success btn">Insert & Enroll
                                    </button> -->

                                    <a href="../enrollees/view_student_transferee_enrollment_review.php?inserted=true&id=<?php echo $student_id?>&p_id=<?php echo $pending_enrollees_id?>&e_id=<?php echo $enrollment_id;?>">
                                        <button type="button" 
                                            class="btn btn-primary btn">Review Insertion
                                        </button>
                                    </a>

                                <?php
                            }else{
                                ?>
                                    <button type="submit" disabled
                                        class="btn btn-outline-success btn">Select Section First
                                    </button>
                                <?php 
                            }
                        ?>

                        <a href="transferee_process_enrollment.php?step2=true&id=<?php echo $pending_enrollees_id;?>">
                            <button class="btn-secondary btn">
                                Go back
                            </button>
                        </a>
                    </div> 
                </div>

                <script>

                    function add_non_transferee(student_id, subject_id,
                        course_level, school_year_id,
                        subject_code, enrollment_id){

                        Swal.fire({
                            icon: 'question',
                            title: `Inserting ${subject_code}`,
                            showCancelButton: true,
                            confirmButtonText: 'Yes',
                            cancelButtonText: 'No'
                        }).then((result) => {
                            if (result.isConfirmed) {
                                // console.log("nice")
                                $.ajax({
                                    url: '../ajax/enrollee/add_non_credit_subject.php',
                                    type: 'POST',
                                    data: {
                                        student_id, subject_id,
                                        course_level, school_year_id,
                                        enrollment_id
                                    },
                                    success: function(response) {
                                        // console.log(response);
                                        // alert(response);
                                        // window.location.href = 'transferee_process_enrollment.php?step3=true&id=101&selected_course_id=415';
                                        alert(response);

                                    },
                                    error: function(xhr, status, error) {
                                        // handle any errors here
                                    }
                                });
                            } else {
                                // User clicked "No," perform alternative action or do nothing
                            }
                        });

                       

                    }

                    function add_credit_transferee(student_id, subject_id,
                        course_level, school_year_id, course_id,
                        subject_title, subject_code, enrollment_id){

                        Swal.fire({
                            icon: 'question',
                            title: `Inserting as credited ${subject_code}`,
                            showCancelButton: true,
                            confirmButtonText: 'Yes',
                            cancelButtonText: 'No'

                        }).then((result) => {
                            if (result.isConfirmed) {

                                $.ajax({
                                    url: '../ajax/enrollee/add_credit_subject.php',
                                    type: 'POST',
                                    data: {
                                        student_id, subject_id,
                                        course_level, school_year_id,
                                        course_id, subject_title, enrollment_id
                                    },
                                    success: function(response) {

                                        // console.log(response);
                                        alert(response);

                                        // Admin::success("", "");

                                        // window.location.href = 'transfee_enrollees.php';
                                    },
                                    error: function(xhr, status, error) {
                                        // handle any errors here
                                    }
                                });
                            } else {
                                // User clicked "No," perform alternative action or do nothing
                            }
                        });

                       
                    }
                    $(document).ready(function(){

                        var id = '<?php echo $student_id; ?>';  
                    
                        var table = $('#transferee_selection_table').DataTable({
                            'processing': true,
                            'serverSide': true,
                            'serverMethod': 'POST',
                            'ajax': {
                                'url':'transfereeEnrollmentDataList.php?id=' + id
                            },
                            'columns': [
                                { data: 'subject_id' },
                                { data: 'subject_code' },
                                { data: 'subject_title' },
                                { data: 'unit' },
                                { data: 'semester' },
                                { data: 'subject_type' },
                                { data: 'actions2' },
                                { data: 'actions3' }
                            ]
                        });
                    });
                </script>
            <?php
        }
    }


?>

