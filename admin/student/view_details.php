<script src="../assets/js/common.js"></script>

<?php 

    include('../registrar_enrollment_header.php');
    include('../../enrollment/classes/StudentEnroll.php');
    include('../../enrollment/classes/StudentSubject.php');
    include('../../enrollment/classes/Section.php');
    include('../../enrollment/classes/OldEnrollees.php');
    include('../../enrollment/classes/Enrollment.php');
    include('../classes/Subject.php');
    include('../classes/Course.php');
    include('../../includes/classes/Student.php');

 
    ?>
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

            .inner-student-table{
                table-layout: fixed;
                border-collapse: collapse;
                width: 100%;
                text-align: center;
            }

            /* table{
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
            } */

            .choices{
                display: flex;
                flex-direction: row;
                justify-content: center;
                align-items: center;
                margin-top: 80px;
                padding: 20px 53px 0px;
                gap: 1px;
                width: 100%;
                height: 105px;
                background: #1A0000;
                flex: none;
                order: 2;
                align-self: stretch;
                flex-grow: 0;

                padding: 10px;
            }
            .selection-btn{
                display: flex;
                flex-direction: row;
                justify-content: center;
                align-items: center;
                padding: 5px 20px;
                gap: 10px;
                width: 233px;
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
    <?php

    $enroll = new StudentEnroll($con);
    $old_enroll = new OldEnrollees($con, $enroll);
    $course = new Course($con, $enroll);

    if(isset($_GET['id'])){

        $student_id = $_GET['id'];
        
        $subject = new Subject($con, $student_id, null);

        $school_year_obj = $enroll->GetLatestSchoolYearAndSemester();

        $current_term = $school_year_obj[0];
        $current_semester = $school_year_obj[1];
        $school_year_id = $school_year_obj[2];

        $student_username = $enroll->GetStudentUsername($student_id);

        $userLoggedInId = $enroll->GetStudentId($student_username);
        $course_main_id = $enroll->GetStudentCourseId($student_username);
        $student_program_id = $enroll->GetStudentProgramId($course_main_id);

        $section = new Section($con, $course_main_id);

        $section_acronym = $section->GetAcronymByProgramId($student_program_id);

        
        $student_course = $course->GetStudentCourse($student_username);

        $student_obj = $enroll->GetStudentCourseLevelYearIdCourseId($student_username);


        $student_course_level = $student_obj['course_level'];

        // $student_course_level = $enroll->GetStudentCourseLevel($student_username);
        $section_name = $enroll->GetStudentCourseName($student_username);

        $student_course_id = $student_obj['course_id'];
        $student_status = $student_obj['student_status'];

        $student_section_level = $course->GetStudentSectionLevel($student_course_id);

        // echo $student_status;

        $get_student = $con->prepare("SELECT * FROM student
            WHERE student_id=:student_id
            AND student_status=:student_status
            -- AND admission_status!=:admission_status
            LIMIT 1");
        
        $get_student->bindValue(":student_id", $student_id);
        $get_student->bindValue(":student_status", $student_status);
        // $get_student->bindValue(":admission_status", "Transferee");
        $get_student->execute();


        $GRADE_ELEVEN = 11;
        $GRADE_TWELVE = 12;

        $FIRST_SEMESTER = "First";
        $SECOND_SEMESTER = "Second";

        if($get_student->rowCount() > 0){

            $row = $get_student->fetch(PDO::FETCH_ASSOC);


            // $program_id = $row['program_id'];
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
            // $pending_enrollees_id = $row['pending_enrollees_id'];
            // $password = $row['password'];
            $civil_status = $row['civil_status'];
            $religion = $row['religion'];
            $citizenship = $row['citizenship'];
            $nationality = $row['nationality'];
            $age = $row['age'];
            $guardian_name = $row['guardian_name'];
            $guardian_contact_number = $row['guardian_contact_number'];
            $lrn = $row['lrn'];
            $student_unique_id = $row['student_unique_id'];

            $admission_status = $row['admission_status'];

            $is_tertiary = $row['is_tertiary'];

            $proccess_date = "";

            $enrollment = new Enrollment($con, $enroll);
            $enrollment_form_id = $enrollment->GenerateEnrollmentFormId();

            $checkEnrollmentEnrolled = $enrollment->CheckEnrollmentEnrolled($student_id,
                    $course_main_id, $school_year_id);

            $cashierEvaluated = $enrollment->CheckEnrollmentCashierApproved($student_id,
                    $course_main_id, $school_year_id);


            $registrarEvaluated = $enrollment->CheckEnrollmentRegistrarApproved($student_id,
                    $course_main_id, $school_year_id);

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
                ->GetEnrollmentNonEnrolledDate($student_id, $course_main_id,
                    $school_year_id);

            $getEnrollmentEnrolledDate = $enrollment
                ->GetEnrollmentEnrolledDate($student_id, $course_main_id,
                    $school_year_id);

            $proccess_date = null;

            if($checkEnrollmentEnrolled == true){
                $proccess_date = $getEnrollmentEnrolledDate;
            }else{
                $proccess_date = $getEnrollmentNonEnrolledDate;
            }


            # ==1
            if(isset($_GET['profile']) && $_GET['profile'] == "show"){
            ?>
                <div class="row col-md-12">

                    <div class="content">

                        <div class="form-header">
                            <div class="header-content">
                            <h2><?php echo $lastname?>, <?php echo $firstname;?> <?php echo $middle_name;?>,</h2>
                            </div>

                            <div class="student-table">
                                <table class="inner-student-table">
                                    <tr>
                                        <th>Student No.</th>
                                        <th>Level</th>
                                        <th>Strand</th>
                                        <th>Status</th>
                                        <th>Added on:</th>
                                    </tr>

                                    <tr>
                                        <td><?php echo $student_unique_id; ?></td>
                                        <td><?php echo $student_course_level; ?></td>
                                        <td><?php echo $section_acronym; ?></td>
                                        <td><?php echo $payment_status; ?></td>
                                     

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

                                <a href="view_details.php?profile=show&id=<?php echo $student_id?>">
                                
                                    <button style="background-color:palevioletred;"
                                        type="button"
                                        class="selection-btn"
                                        id="student-details">
                                        <i class="bi bi-clipboard-check"></i>Student Details
                                    </button>
                                </a>
                            </div>

                            <?php 
                                # SHS
                                if($is_tertiary == 0){
                                    ?>
                                    <div class="enrolled-subjects">
                                        <a href="view_details.php?grade_record=show&id=<?php echo $student_id;?>">
                                            <button
                                                type="button"
                                                class="selection-btn"
                                                id="enrolled-subjects">
                                                <i class="bi bi-collection"></i>Grades Records
                                            </button>
                                        </a>
                                    </div>

                                    <div class="enrolled-subjects">
                                        <a href="view_details.php?subject=show&id=<?php echo $student_id?>">
                                            <button
                                                type="button"
                                                class="selection-btn"
                                                id="enrolled-subjects">
                                                <i class="bi bi-collection"></i>Enrolled subjects
                                            </button>
                                        </a>
                                    </div>
                                    <?php  
                                }

                                # Tertiary
                                if($is_tertiary == 1){
                                    ?>
                                    <div class="enrolled-subjects">
                                        <a href="view_details.php?grade_record=show&tertiary=true&id=<?php echo $student_id;?>">
                                            <button
                                                type="button"
                                                class="selection-btn"
                                                id="enrolled-subjects">
                                                <i class="bi bi-collection"></i>*Grades Records
                                            </button>
                                        </a>
                                    </div>

                                    <div class="enrolled-subjects">
                                        <!-- <a href="view_details.php?subject=show&tertiary=true&id=<?php echo $student_id?>"> -->
                                        <a href="view_details.php?subject=show&tertiary=true&id=<?php echo $student_id;?>">
                                            <button
                                                type="button"
                                                class="selection-btn"
                                                id="enrolled-subjects">
                                                <i class="bi bi-collection"></i>*Enrolled subjects
                                            </button>
                                        </a>
                                    </div>
                                    <?php  
                                }
                            ?>
                        </div>
                    </div>

                    <hr>

                    <div style="display: none;" class="card">
                        <div class="card-body">
                            <div class="table-responsive">
                                <h4 class="mb-3 text-start text-primary">Student Information</h4>
                                <hr>
                                <div class="col-md-4 mb-4">
                                    <label style="font-weight: bold;" class="text-muted">
                                        Academic Year: <?php echo $current_term; ?> 
                                        <span>(<?php echo $current_semester; ?>)</span> 
                                    </label>
                                </div>
                                <table class="table">
                                    <tr>
                                        <td><label>Id</label></td>
                                        <td>
                                            <input class="form-control input-md" 
                                                readonly id="student_id" name="student_id" placeholder="Student Id" type="text" 
                                                value='<?php echo $row['student_id']?>'>
                                        </td>

                                        <td ><label>Grade Level</label></td> 
                                        <td colspan="1">
                                            <?php 
                                                if($student_course_level != 0){
                                                    echo '<input value="Grade ' . $student_course_level . '" readonly class="form-control input-md" type="text">';
                                                }else{
                                                    echo '<input value="Not Set" readonly class="form-control input-md" type="text">';
                                                    
                                                }
                                            ?>

                                        </td>

                                        <td ><label>LRN</label></td> 
                                        <td colspan="1">
                                            <input class="form-control input-md" 
                                                name="lrn" placeholder="LRN:136-746-XXX" type="text" 
                                                value='<?php echo $row['lrn']?>'>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td><label>Firstname</label></td>
                                        <td>
                                            <input  value='<?php echo $row['firstname']?>' class="form-control input-md" id="FNAME" name="FNAME" placeholder="First Name" type="text">
                                        </td>
                                        <td><label>Lastname</label></td>
                                        <td colspan="2">
                                            <input value='<?php echo $row['lastname']?>'  class="form-control input-md" id="LNAME" name="LNAME" placeholder="Last Name" type="text">
                                        </td> 
                                        <td>
                                            <input value='<?php echo $row['middle_name']?>' class="form-control input-md" id="MI" name="MI" placeholder="MI"  maxlength="1" type="text">
                                        </td>
                                    </tr>
                                    <tr>
                                        <td><label>Address</label></td>
                                        <td colspan="5"  >
                                        <input  value='<?php echo $row['address']?>' class="form-control input-md" id="PADDRESS" name="PADDRESS" placeholder="Permanent Address" type="text" >
                                        </td> 
                                    </tr>
                                    <tr>
                                        <td ><label>Sex </label></td> 
                                        <td colspan="2">
                                            <label>
                                                <input checked id="optionsRadios1" name="optionsRadios" type="radio" value="Female">Female 
                                                <input id="optionsRadios2" name="optionsRadios" type="radio" value="Male"> Male
                                            </label>
                                        </td>
                                        <td ><label>Date of birth</label></td>
                                        <td colspan="2"> 
                                        <div class="input-group" >
                                        <div class="input-group-addon"> 
                                            <i class="fa fa-calendar"></i>
                                        </div>
                                        <input value='<?php echo $row['birthday']?>'  name="BIRTHDATE"  id="BIRTHDATE"  type="text" class="form-control input-md"   data-inputmask="'alias': 'mm/dd/yyyy'" data-mask >
                                        </div>             
                                        </td>
                                    </tr>

                                    <tr><td><label>Place of Birth</label></td>
                                        <td colspan="5">
                                        <input value='<?php echo $row['birthplace']?>'  class="form-control input-md" id="BIRTHPLACE" name="BIRTHPLACE" placeholder="Place of Birth" type="text" >
                                    </td>
                                    </tr>

                                    <tr>
                                        <td><label>Nationality</label></td>
                                        <td colspan="2"><input value='<?php echo $row['nationality']?>'   class="form-control input-md" id="NATIONALITY" name="NATIONALITY" placeholder="Nationality" type="text" >
                                                    </td>
                                        <td><label>Religion</label></td>
                                        <td colspan="2"><input  value='<?php echo $row['religion']?>'  class="form-control input-md" id="RELIGION" name="RELIGION" placeholder="Religion" type="text" >
                                        </td>
                                    </tr>
                                    <tr>
                                        <td><label>Contact No.</label></td>
                                            <td value='<?php echo $row['contact_number']?>'  colspan="2"><input class="form-control input-md" id="CONTACT" name="CONTACT" placeholder="Contact Number" type="number" maxlength="11" >
                                            </td>
                                        
                                        <td><label>Civil Status</label></td>
                                        <td colspan="2">
                                            <select class="form-control input-sm" name="CIVILSTATUS">
                                                <option value="Single">Single</option>
                                                <option value="Married">Married</option> 
                                                <option value="Widow">Widow</option>
                                            </select>
                                        </td>
                                    </tr>

                                    
                                    <tr>
                                    <td>
                                        <label>Strand Section</label></td>
                                        <td colspan="2">
                                            <select class="form-control input-sm" name="course_id">
                                                <?php

                                                    $selected_course_id = 0; 

                                                    $active_yes = "yes";
                                                    $get_course = $con->prepare("SELECT * FROM course
                                                                                WHERE school_year_term = :school_year_term
                                                                                AND active = :active
                                                                                ORDER BY school_year_term ASC");

                                                    $get_course->bindValue(":school_year_term", $current_term);
                                                    $get_course->bindValue(":active", $active_yes);
                                                    $get_course->execute();

                                                    while ($get_row = $get_course->fetch(PDO::FETCH_ASSOC)) {

                                                        $selected = ($get_row['course_id'] == $student_course_id) ? 'selected' : '';

                                                        echo "<option value='{$get_row['course_id']}' {$selected}>{$get_row['program_section']}</option>";
                                                    }
                                                ?>
                                            </select>

                                        </td>
                                    
                                        <td><label>Student Status</label></td>
                                        <td colspan="3">
                                            <!-- <select class="form-control input-sm" name="student_status">
                                                <option value="Transferee">Transferee</option>
                                                <option value="Regular">New Enrollee (Regular)</option> 
                                            </select> -->

                                            <select class="form-control input-sm" name="student_status">
                                                <option value="Transferee" <?php if ($row['student_status'] == "Transferee") { echo "selected"; } ?>>Transferee</option>
                                                <option value="Regular" <?php if ($row['student_status'] == "Regular") { echo "selected"; } ?>>New Enrollee (Regular)</option> 
                                            </select>
                                        </td>
                                    </tr>
                                    
                                    <tr>
                                        <td><label>Guardian</label></td>
                                        <td colspan="2">
                                            <input value='<?php echo $row['guardian_name']?>'  class="form-control input-md" id="GUARDIAN" name="GUARDIAN" placeholder="Parents/Guardian Name" type="text">
                                        </td>
                                        <td><label>Contact No.</label></td>
                                        <td colspan="2"><input value='<?php echo $row['guardian_contact_number']?>'  class="form-control input-md" id="GCONTACT" name="GCONTACT" placeholder="Contact Number" type="number" ></td>
                                    </tr>
                                
                                </table>
                            </div>
                        </div>
                    </div>
                    <div style="display: none;" class="card">
                        <div class="card-body">
                            <div class="table-responsive">
                                <h4 class="mb-3 text-start text-primary">Guardian`s Information</h4>
                                <hr>
                                <table class="table">
                                    
                                    <tr>
                                        <td><label>Firstname</label></td>
                                        <td>
                                            <input  value='<?php echo $row['firstname']?>' class="form-control input-md" id="FNAME" 
                                            name="FNAME" placeholder="First Name" type="text">
                                        </td>
                                        <td><label>Lastname</label></td>
                                        <td colspan="2">
                                            <input value='<?php echo $row['lastname']?>' class="form-control input-md" id="LNAME" name="LNAME" 
                                            placeholder="Last Name" type="text">
                                        </td> 
                                        <td><label>Middle</label></td>

                                        <td colspan="2">
                                            <input value='<?php echo $row['middle_name']?>' class="form-control input-md"
                                             id="MI" name="MI" placeholder="MI"  maxlength="1" type="text">
                                        </td>
                                    </tr>


                                    <tr>
                                        <td><label>Phone</label></td>
                                        <td>
                                            <input  value='' type="text">
                                        </td>
                                        <td><label>Email</label></td>
                                        <td colspan="2">
                                            <input  value='' type="text">
                                        </td> 

                                    </tr>

                                    <tr>
                                        <td colspan="2">
                                            <input  value='' type="text">
                                        </td>

                                        <td><label>Relationship</label></td>
                                        <td colspan="2">
                                            <input  value='' type="text">
                                        </td>
                                    </tr>
                                
                                </table>
                            </div>
                        </div>
                    </div>

                    <head>
                        <meta charset="utf-8" />
                        <meta
                            name="viewport"
                            content="width=device-width, inital-scale=1, shrink-to-fit=no"
                        />
                        <link
                            rel="stylesheet"
                            href="./admission-enrollment.css"
                            />

                            <div class="card">
                                <div class="card-body">
                                <div class="bg-content">
                                    <div class="step-content active">
                                    <div class="content-box">
                                    <h3 class="text-center">Student form details</h3>
                                    <p>
                                    Assure every student information in this section. This will be the
                                    student data
                                    </p>

                                    <div class="student-info">
                                    <h4>Student information</h4>

                                    <form action="" class="info-box">
                                        <div class="info-1">
                                        <label for="name"> Name </label>
                                        <input
                                            type="text"
                                            name="lastName"
                                            id="lastName"
                                            value="<?php echo $lastname;?>"
                                            placeholder="Last name"
                                        />
                                        <input
                                            type="text"
                                            name="firstName"
                                            id="firstName"
                                            value="<?php echo $firstname;?>"

                                            placeholder="First name"
                                        />
                                        <input
                                            type="text"
                                            name="middleName"
                                            id="middleName"
                                            value="<?php echo $middle_name;?>"
                                            placeholder="Middle name"
                                        />
                                        <input
                                            type="text"
                                            name="suffixName"
                                            id="suffixName"
                                            value=""
                                            placeholder="Suffix name"
                                        />
                                        </div>
                                        <div class="info-2">
                                        <label for="status"> Status </label>
                                        <div class="selection-box-1">
                                            <select name="status" id="status">
                                            <option value="Single">Single</option>
                                            <option value="Married">Married</option>
                                            <option value="Divorced">Divorced</option>
                                            <option value="Widowed">Widowed</option>
                                            </select>
                                        </div>
                                        <label for="citizenship"> Citizenship </label>
                                        <input type="text" name="citizenship" id="citizenship" 
                                        value="<?php echo $firstname;?>"
                                        />
                                        <label for="gender"> Gender </label>
                                        <div class="selection-box-1">
                                            <select name="gender" id="gender">
                                            <option value="Male">Male</option>
                                            <option value="Female">Female</option>
                                            </select>
                                        </div>
                                        </div>
                                        <div class="info-3">
                                        <label for="birthdate"> Birthdate </label>
                                        <input type="date" name="birthdate" 
                                        id="birthdate" value="<?php echo $birthday?>" />
                                        <label for="birthplade"> Birthplace </label>
                                        <input
                                            type="text"
                                            name="birthplace"
                                            id="birthplace"
                                            value=""
                                        />
                                        <label for="religion"> Religion </label>
                                        <input type="text" name="religion" id="religion" 
                                        value="<?php echo $religion;?>" />
                                        </div>
                                        <div class="info-4">
                                        <label for="address"> Address </label>
                                        <input type="text" value="<?php echo $address?>" name="address" id="address" value="" />
                                        </div>
                                        <div class="info-5">
                                        <label for="phoneNo"> Phone no. </label>
                                        <input type="text" value="<?php echo $contact_number?>" name="phoneNo" id="phoneNo" value="" />
                                        <label for="email"> Email </label>
                                        <input type="text" value="<?php echo $email?>" name="email" id="email" value="" />
                                        </div>
                                    </form>
                                    </div>

                                    <div class="ParentGuardian-info">
                                    <h4>Parent/Guardian's Information</h4>
                                    <h5>Father's information</h5>

                                    <form action="" class="info-box">
                                        <div class="info-1">
                                        <label for="name"> Name </label>
                                        <input
                                            type="text"
                                            name="fathrLastName"
                                            id="fatherLastName"
                                            value=""
                                            placeholder="Last name"
                                        />
                                        <input
                                            type="text"
                                            name="fatherFirstName"
                                            id="fatherFirstName"
                                            value=""
                                            placeholder="First name"
                                        />
                                        <input
                                            type="text"
                                            name="fatherMiddleName"
                                            id="fatherMiddleName"
                                            value=""
                                            placeholder="Middle name"
                                        />
                                        <input
                                            type="text"
                                            name="fatherSuffixName"
                                            id="fatherSuffixName"
                                            value=""
                                            placeholder="Suffix name"
                                        />
                                        </div>
                                        <div class="info-2">
                                        <label for="phoneNo"> Phone no. </label>
                                        <input
                                            type="text"
                                            name="fatherPhoneNo"
                                            id="fatherPhoneNo"
                                            value=""
                                        />
                                        <label for="email"> Email </label>
                                        <input
                                            type="text"
                                            name="fatherEmail"
                                            id="fatherEmail"
                                            value=""
                                        />
                                        <label for="occupation"> Occupation </label>
                                        <input
                                            type="text"
                                            name="fatherOccupation"
                                            id="fatherOccupation"
                                        />
                                        </div>
                                    </form>
                                    </div>

                                    <div class="ParentGuardian-info">
                                    <h5>Mother's information</h5>

                                    <form action="" class="info-box">
                                        <div class="info-3">
                                        <label for="name"> Name </label>
                                        <input
                                            type="text"
                                            name="motherLastName"
                                            id="motherLastName"
                                            value=""
                                            placeholder="Last name"
                                        />
                                        <input
                                            type="text"
                                            name="motherFirstName"
                                            id="motherFirstName"
                                            value=""
                                            placeholder="First name"
                                        />
                                        <input
                                            type="text"
                                            name="motherMiddleName"
                                            id="motherMiddleName"
                                            value=""
                                            placeholder="Middle name"
                                        />
                                        <input
                                            type="text"
                                            name="motherSuffixName"
                                            id="motherSuffixName"
                                            value=""
                                            placeholder="Suffix name"
                                        />
                                        </div>
                                        <div class="info-4">
                                        <label for="phoneNo"> Phone no. </label>
                                        <input
                                            type="text"
                                            name="motherPhoneNo"
                                            id="motherPhoneNo"
                                            value=""
                                        />
                                        <label for="email"> Email </label>
                                        <input
                                            type="text"
                                            name="motherEmail"
                                            id="motherEmail"
                                            value=""
                                        />
                                        <label for="occupation"> Occupation </label>
                                        <input
                                            type="text"
                                            name="motherOccupation"
                                            id="motherOccupation"
                                            value=""
                                        />
                                        </div>
                                    </form>
                                    </div>

                                    <div class="ParentGuardian-info">
                                    <h5>Guardian's information</h5>

                                    <form action="" class="info-box">
                                        <div class="info-5">
                                        <label for="name"> Name </label>
                                        <input
                                            type="text"
                                            name="guardianLastName"
                                            id="guardianLastName"
                                            value=""
                                            placeholder="Last name"
                                        />
                                        <input
                                            type="text"
                                            name="guardianFirstName"
                                            id="guardianFirstName"
                                            value=""
                                            placeholder="First name"
                                        />
                                        <input
                                            type="text"
                                            name="guardianMiddleName"
                                            id="guardianMiddleName"
                                            value=""
                                            placeholder="Middle name"
                                        />
                                        <input
                                            type="text"
                                            name="guardianSuffixName"
                                            id="guardianSuffixName"
                                            value=""
                                            placeholder="Suffix name"
                                        />
                                        </div>
                                        <div class="info-6">
                                        <label for="phoneNo"> Phone no. </label>
                                        <input
                                            type="text"
                                            name="guardianPhoneNo"
                                            id="guardianPhoneNo"
                                            value=""
                                        />
                                        <label for="email"> Email </label>
                                        <input
                                            type="text"
                                            name="guardianEmail"
                                            id="guardianEmail"
                                            value=""
                                        />
                                        </div>
                                        <div class="info-7">
                                        <label for="relationship"> Relationship </label>
                                        <input
                                            type="text"
                                            name="guardianRelationship"
                                            id="guardianRelationship"
                                            value=""
                                        />
                                        <label for="occupation"> Occupation </label>
                                        <input
                                            type="text"
                                            name="guardianOccupation"
                                            id="guardianOccupation"
                                            value=""
                                        />
                                        </div>
                                    </form>
                                    </div>
                                    </div>
                                </div>
                            
                            
                            </div>
                                </div>
                            </div>
                    


                </div>
            <?php
            }

            # ==2
            if(isset($_GET['grade_record']) 
                && !isset($_GET['tertiary'])
                && $_GET['grade_record'] == "show"){
                 $studentEnroll = new StudentEnroll($con);

                $username = $studentEnroll->GetStudentUsername($_GET['id']);
                $userLoggedInId = $studentEnroll->GetStudentId($username);
                $course_main_id = $studentEnroll->GetStudentCourseId($username);
                $student_course_level = $studentEnroll->GetStudentCourseLevel($username);

                $school_year_obj = $studentEnroll->GetActiveSchoolYearAndSemester();

                $current_school_year_id = $school_year_obj['school_year_id'];
                $current_school_year_term = $school_year_obj['term'];
                $current_school_year_period = $school_year_obj['period'];


                $admission_status = $row['admission_status'];

                $proccess_date = "";

                $enrollment = new Enrollment($con, $enroll);
                $enrollment_form_id = $enrollment->GenerateEnrollmentFormId();

                $checkEnrollmentEnrolled = $enrollment->CheckEnrollmentEnrolled($student_id,
                        $course_main_id, $school_year_id);

                $cashierEvaluated = $enrollment->CheckEnrollmentCashierApproved($student_id,
                        $course_main_id, $school_year_id);


                $registrarEvaluated = $enrollment->CheckEnrollmentRegistrarApproved($student_id,
                        $course_main_id, $school_year_id);

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
                    ->GetEnrollmentNonEnrolledDate($student_id, $course_main_id,
                        $school_year_id);

                $getEnrollmentEnrolledDate = $enrollment
                    ->GetEnrollmentEnrolledDate($student_id, $course_main_id,
                        $school_year_id);

                $proccess_date = null;

                if($checkEnrollmentEnrolled == true){
                    $proccess_date = $getEnrollmentEnrolledDate;
                }else{
                    $proccess_date = $getEnrollmentNonEnrolledDate;
                }

            ?>
                <div class="row col-md-12">


                    <div class="content">

                        <div class="form-header">
                            <div class="header-content">
                            <h2><?php echo $lastname?>, <?php echo $firstname;?> <?php echo $middle_name;?>,</h2>
                             
                            </div>

                            <div class="student-table">
                                <table class="inner-student-table">
                                    <tr>
                                        <th>Student No.</th>
                                        <th>Level</th>
                                        <th>Strand</th>
                                        <th>Status</th>
                                        <th>Added on:</th>
                                    </tr>

                                    <tr>
                                        <td><?php echo $student_unique_id; ?></td>
                                        <td><?php echo $student_course_level; ?></td>
                                        <td><?php echo $section_acronym; ?></td>
                                        <td><?php echo $payment_status; ?></td>
                                     

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

                                <a href="view_details.php?profile=show&id=<?php echo $student_id?>">
                                
                                    <button "
                                        type="button"
                                        class="selection-btn"
                                        id="student-details">
                                        <i class="bi bi-clipboard-check"></i>Student Details
                                    </button>
                                </a>

                            </div>
                            <div  class="enrolled-subjects">
                                <a href="view_details.php?grade_record=show&id=<?php echo $student_id;?>">
                                    <button style="background-color:palevioletred;"
                                        type="button"
                                        class="selection-btn"
                                        id="enrolled-subjects">
                                        <i class="bi bi-collection"></i>Grades Records
                                    </button>
                                </a>
                            </div>


                            <div class="enrolled-subjects">
                                <a href="view_details.php?subject=show&id=<?php echo $student_id?>">
                                    <button
                                        type="button"
                                        class="selection-btn"
                                        id="enrolled-subjects">
                                        <i class="bi bi-collection"></i>Enrolled subjects
                                    </button>
                                </a>
                            </div>

                        </div>
                    </div>

                    <hr>
                    <div class="card">
                        <div class="card-body">

                            <!-- GRADE 11 1st SEMESTER -->
                            <div class="row col-md-12 table-responsive"
                                    style="margin-top:5%;">
                                <div class="table-responsive" style="margin-top:2%;"> 

                                    <!-- <h3>Grade 11 First Semester</h3>	 -->
                                    <?php 
                                        // echo "wee";
                                        $GRADE_TWELVE = 12;
                                        $GRADE_ELEVEN = 11;
                                        $FIRST_SEMESTER = "First";

                                        // Section Based on the enrollment.
                                        $enrollment_school_year = $studentEnroll->GetStudentSectionGradeElevenSchoolYear($username, $userLoggedInId, 11, "First");

                                        if($enrollment_school_year !== null){
                                            $term = $enrollment_school_year['term'];
                                            $period = $enrollment_school_year['period'];
                                            $school_year_id = $enrollment_school_year['school_year_id'];
                                            $enrollment_course_id = $enrollment_school_year['course_id'];

                                            $section = new Section($con, $enrollment_course_id);
                                            $enrollment_course_level = $section->GetSectionGradeLevel();

                                            $enrollment_section_name = $enroll->GetStudentCourseNameByCourseId($enrollment_course_id);
                                            
                                            echo "
                                                <h4 style='font-weight: 500;' class='text-primary text-center mb-3'>
                                                    Grade 11 $enrollment_section_name $period Semester (SY $term)
                                                </h4>

                                                <form action='grade_subject_generate.php' method='POST'>

                                                    <input type='hidden' value='$student_username' name='student_username'>
                                                    <input type='hidden' value='$student_id' name='student_id'>
                                                    <input type='hidden' value='$enrollment_course_id' name='enrollment_course_id'>
                                                    <input type='hidden' value='$enrollment_course_level' name='student_course_level'>
                                                    <input type='hidden' value='$period' name='current_school_year_period'>

                                                    <button name='grade_subject_generate' type='submit'
                                                        class='btn btn-outline-primary btn-sm'>
                                                        Generate PDF
                                                    </button>
                                                </form>
                                            ";

                                        }else{
                                            echo "
                                                <h3>Grade 11 First Semester</h3>	
                                            ";
                                        }
                                    ?>	
                                    <table style="font-weight: 200;" class="table table-striped table-bordered table-hover "  style="font-size:12px" cellspacing="0"  > 
                                        <thead>
                                            <tr class="text-center"> 
                                                <th class="text-success" rowspan="2">Code</th>
                                                <th class="text-success" rowspan="2">Type</th>
                                                <th class="text-success" rowspan="2">Description</th>  
                                                <th class="text-success" rowspan="2">Unit</th>
                                                <th class="text-success" rowspan="2">Prelim</th>
                                                <th class="text-success" rowspan="2">Midterm</th>
                                                <th class="text-success" rowspan="2">Pre-Final</th>
                                                <th class="text-success" rowspan="2">Final</th>
                                                <th class="text-success" rowspan="2">Average</th>
                                                <th class="text-success" rowspan="2">Remark</th>
                                            </tr>	
                                        </thead> 	

                                        <tbody>
                                            <?php 

                                                // $listOfSubjects = $studentEnroll->GetStudentSubjectListWithGrades($username);
                                                $listOfSubjects = $studentEnroll
                                                    ->GetStudentCurriculumBasedOnSemesterSubject($username,
                                                $userLoggedInId, $GRADE_ELEVEN, $FIRST_SEMESTER);


                                                if($listOfSubjects !== null){

                                                    foreach ($listOfSubjects as $key => $value) {
                                                        
                                                        $subject_id = $value['subject_id'];
                                                        $course_level = $value['course_level'];

                                                        $first = $value['first'];
                                                        $second = $value['second'];
                                                        $third = $value['third'];
                                                        $fourth = $value['fourth'];
                                                        $grade_remarks = $value['grade_remarks'];

                                                        $remarks_url = "";

                                                        $query_student_subject = $con->prepare("SELECT 
                                                    
                                                            t1.subject_id, t1.student_subject_id as t1_student_subject_id,
                                                            
                                                            t2.student_subject_id as t2_student_subject_id,
                                                            t2.remarks

                                                            FROM student_subject as t1

                                                            LEFT JOIN student_subject_grade as t2 ON t2.student_subject_id = t1.student_subject_id

                                                            WHERE t1.subject_id=:subject_id
                                                            AND t1.student_id=:student_id
                                                            LIMIT 1");

                                                        $query_student_subject->bindValue(":subject_id", $subject_id);
                                                        $query_student_subject->bindValue(":student_id", $student_id);
                                                        $query_student_subject->execute();


                                                        $t1_student_subject_id = null;
                                                        if($query_student_subject->rowCount() > 0){

                                                            $row = $query_student_subject->fetch(PDO::FETCH_ASSOC);

                                                            $student_subject_subject_id = $row['subject_id'];
                                                            $t1_student_subject_id = $row['t1_student_subject_id'];
                                                            $t2_student_subject_id = $row['t2_student_subject_id'];
                                                            $remarks = $row['remarks'];

                                                            // echo $t1_student_subject_id . " ";
                                                            // echo $t2_student_subject_id . " ";

                                                            if($t1_student_subject_id == $t2_student_subject_id){

                                                                $remarks_url = $remarks;

                                                            }else if($student_subject_subject_id == $subject_id
                                                                && $t1_student_subject_id != $t2_student_subject_id){

                                                                $remarks_url = "
                                                                    <a href='shs_transferee_remarks.php?student_id=$student_id&s_id=$subject_id&page=view_details'>
                                                                        <button type='button' class='btn btn-sm btn-primary'>Remark</button>
                                                                    </a>
                                                                ";
                                                            }
                                                        }
                                                    
                                                        echo '<tr class="text-center">'; 
                                                                echo '<td>'.$value['subject_code'].'</td>';
                                                                echo '<td>'.$value['subject_type'].'</td>';
                                                                echo '<td>'.$value['subject_title'].'</td>';
                                                                echo '<td>'.$value['unit'].'</td>';
                                                             
                                                                echo '<td>'.$first.'</td>';
                                                                echo '<td>'.$second.'</td>';
                                                                echo '<td>'.$third.'</td>';
                                                                echo '<td>'.$fourth.'</td>';
                                                                echo '<td></td>';
                                                                echo '<td>'.$grade_remarks.'</td>';

                                                        echo '</tr>';
                                                    }     
                                                }
                                            ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                            <!-- GRADE 11 2nd SEMESTER -->
                            <div class="row col-md-12 table-responsive"
                                    style="margin-top:5%;">
                                <div class="table-responsive" style="margin-top:2%;"> 

                                    <?php 

                                        $GRADE_TWELVE = 12;
                                        $GRADE_ELEVEN = 11;
                                        $FIRST_SEMESTER = "First";
                                        // Section Based on the enrollment.
                                        $enrollment_school_year = $studentEnroll->GetStudentSectionGradeElevenSchoolYear($username, $userLoggedInId, 11, "Second");

                                        if($enrollment_school_year !== null){
                                            $term = $enrollment_school_year['term'];
                                            $period = $enrollment_school_year['period'];
                                            $school_year_id = $enrollment_school_year['school_year_id'];
                                            $enrollment_course_id = $enrollment_school_year['course_id'];

                                            $section = new Section($con, $enrollment_course_id);
                                            $enrollment_course_level = $section->GetSectionGradeLevel();

                                            $enrollment_section_name = $enroll->GetStudentCourseNameByCourseId($enrollment_course_id);
                                            
                                            echo "
                                                <h4 style='font-weight: 500;' class='text-primary text-center mb-3'>
                                                    Grade 11 $enrollment_section_name $period Semester (SY $term)
                                                </h4>

                                                <form action='grade_subject_generate.php' method='POST'>

                                                    <input type='hidden' value='$student_username' name='student_username'>
                                                    <input type='hidden' value='$student_id' name='student_id'>
                                                    <input type='hidden' value='$enrollment_course_id' name='enrollment_course_id'>
                                                    <input type='hidden' value='$enrollment_course_level' name='student_course_level'>
                                                    <input type='hidden' value='$period' name='current_school_year_period'>

                                                    <button name='grade_subject_generate' type='submit'
                                                        class='btn btn-outline-primary btn-sm'>
                                                        Generate PDF
                                                    </button>
                                                </form>
                                            ";

                                        }else{
                                            echo "
                                                <h3>Grade 11 First Semester</h3>	
                                            ";
                                        }
                                    ?>	
                                    <table style="font-weight: 200;" class="table table-striped table-bordered table-hover "  style="font-size:12px" cellspacing="0"  > 
                                        <thead>
                                            <tr class="text-center"> 
                                                <th class="text-success" rowspan="2">Code</th>
                                                <th class="text-success" rowspan="2">Type</th>
                                                <th class="text-success" rowspan="2">Description</th>  
                                                <th class="text-success" rowspan="2">Unit</th>
                                                <th class="text-success" rowspan="2">Prelim</th>
                                                <th class="text-success" rowspan="2">Midterm</th>
                                                <th class="text-success" rowspan="2">Pre-Final</th>
                                                <th class="text-success" rowspan="2">Final</th>
                                                <th class="text-success" rowspan="2">Average</th>
                                                <th class="text-success" rowspan="2">Remark</th>
                                            </tr>	
                                        </thead> 	

                                        <tbody>
                                            <?php 

                                                // $listOfSubjects = $studentEnroll->GetStudentSubjectListWithGrades($username);
                                                $listOfSubjects = $studentEnroll
                                                    ->GetStudentCurriculumBasedOnSemesterSubject($username,
                                                $userLoggedInId, $GRADE_ELEVEN, $SECOND_SEMESTER);


                                                if($listOfSubjects !== null){

                                                    foreach ($listOfSubjects as $key => $value) {
                                                        
                                                        $subject_id = $value['subject_id'];
                                                        $course_level = $value['course_level'];

                                                        $first = $value['first'];
                                                        $second = $value['second'];
                                                        $third = $value['third'];
                                                        $fourth = $value['fourth'];
                                                        $grade_remarks = $value['grade_remarks'];

                                                        $remarks_url = "";

                                                        $query_student_subject = $con->prepare("SELECT 
                                                    
                                                            t1.subject_id, t1.student_subject_id as t1_student_subject_id,
                                                            
                                                            t2.student_subject_id as t2_student_subject_id,
                                                            t2.remarks

                                                            FROM student_subject as t1

                                                            LEFT JOIN student_subject_grade as t2 ON t2.student_subject_id = t1.student_subject_id

                                                            WHERE t1.subject_id=:subject_id
                                                            AND t1.student_id=:student_id
                                                            LIMIT 1");


                                                        $query_student_subject->bindValue(":subject_id", $subject_id);
                                                        $query_student_subject->bindValue(":student_id", $student_id);
                                                        $query_student_subject->execute();

                                                        $t1_student_subject_id = null;

                                                        if($query_student_subject->rowCount() > 0){

                                                            $row = $query_student_subject->fetch(PDO::FETCH_ASSOC);

                                                            $student_subject_subject_id = $row['subject_id'];
                                                            $t1_student_subject_id = $row['t1_student_subject_id'];
                                                            $t2_student_subject_id = $row['t2_student_subject_id'];
                                                            $remarks = $row['remarks'];

                                                            // echo $t1_student_subject_id . " ";
                                                            // echo $t2_student_subject_id . " ";

                                                            if($t1_student_subject_id == $t2_student_subject_id){

                                                                $remarks_url = $remarks;

                                                            }else if($student_subject_subject_id == $subject_id
                                                                && $t1_student_subject_id != $t2_student_subject_id){

                                                                $remarks_url = "
                                                                    <a href='shs_transferee_remarks.php?student_id=$student_id&s_id=$subject_id&page=view_details'>
                                                                        <button type='button' class='btn btn-sm btn-primary'>Remark</button>
                                                                    </a>
                                                                ";
                                                            }
                                                        }
                                                    
                                                        echo '<tr class="text-center">'; 
                                                                echo '<td>'.$value['subject_code'].'</td>';
                                                                echo '<td>'.$value['subject_type'].'</td>';
                                                                echo '<td>'.$value['subject_title'].'</td>';
                                                                echo '<td>'.$value['unit'].'</td>';
                                                             
                                                                echo '<td>'.$first.'</td>';
                                                                echo '<td>'.$second.'</td>';
                                                                echo '<td>'.$third.'</td>';
                                                                echo '<td>'.$fourth.'</td>';
                                                                echo '<td></td>';
                                                                echo '<td>'.$grade_remarks.'</td>';

                                                        echo '</tr>';
                                                    }     
                                                }
                                            ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>



                        </div>
                    </div>

                </div>
            <?php
            }

            if(isset($_GET['grade_record']) 
                && isset($_GET['tertiary'])
                && $_GET['grade_record'] == "show"
                ){

                // echo "tertiary";
                $studentEnroll = new StudentEnroll($con);

                $username = $studentEnroll->GetStudentUsername($_GET['id']);
                $userLoggedInId = $studentEnroll->GetStudentId($username);
                $course_main_id = $studentEnroll->GetStudentCourseId($username);
                $student_course_level = $studentEnroll->GetStudentCourseLevel($username);

                $school_year_obj = $studentEnroll->GetActiveSchoolYearAndSemester();

                $current_school_year_id = $school_year_obj['school_year_id'];
                $current_school_year_term = $school_year_obj['term'];
                $current_school_year_period = $school_year_obj['period'];


                $admission_status = $row['admission_status'];

                $proccess_date = "";

                $enrollment = new Enrollment($con, $enroll);
                $enrollment_form_id = $enrollment->GenerateEnrollmentFormId();

                $checkEnrollmentEnrolled = $enrollment->CheckEnrollmentEnrolled($student_id,
                        $course_main_id, $school_year_id);

                $cashierEvaluated = $enrollment->CheckEnrollmentCashierApproved($student_id,
                        $course_main_id, $school_year_id);


                $registrarEvaluated = $enrollment->CheckEnrollmentRegistrarApproved($student_id,
                        $course_main_id, $school_year_id);

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
                    ->GetEnrollmentNonEnrolledDate($student_id, $course_main_id,
                        $school_year_id);

                $getEnrollmentEnrolledDate = $enrollment
                    ->GetEnrollmentEnrolledDate($student_id, $course_main_id,
                        $school_year_id);

                $proccess_date = null;

                if($checkEnrollmentEnrolled == true){
                    $proccess_date = $getEnrollmentEnrolledDate;
                }else{
                    $proccess_date = $getEnrollmentNonEnrolledDate;
                }

            ?>
                <div class="row col-md-12">


                    <div class="content">

                        <div class="form-header">
                            <div class="header-content">
                            <h2><?php echo $lastname?>, <?php echo $firstname;?> <?php echo $middle_name;?>,</h2>
                             
                            </div>

                            <div class="student-table">
                                <table class="inner-student-table">
                                    <tr>
                                        <th>Student No.</th>
                                        <th>Level</th>
                                        <th>Strand</th>
                                        <th>Status</th>
                                        <th>Added on:</th>
                                    </tr>

                                    <tr>
                                        <td><?php echo $student_unique_id; ?></td>
                                        <td><?php echo $student_course_level; ?></td>
                                        <td><?php echo $section_acronym; ?></td>
                                        <td><?php echo $payment_status; ?></td>
                                     

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

                                <a href="view_details.php?profile=show&id=<?php echo $student_id?>">
                                
                                    <button "
                                        type="button"
                                        class="selection-btn"
                                        id="student-details">
                                        <i class="bi bi-clipboard-check"></i>Student Details
                                    </button>
                                </a>

                            </div>
                            <div  class="enrolled-subjects">
                                <a href="view_details.php?grade_record=show&id=<?php echo $student_id;?>">
                                    <button style="background-color:palevioletred;"
                                        type="button"
                                        class="selection-btn"
                                        id="enrolled-subjects">
                                        <i class="bi bi-collection"></i>*Grades Records
                                    </button>
                                </a>
                            </div>


                            <div class="enrolled-subjects">
                                <a href="view_details.php?subject=show&tertiary=true&id=<?php echo $student_id?>">
                                    <button
                                        type="button"
                                        class="selection-btn"
                                        id="enrolled-subjects">
                                        <i class="bi bi-collection"></i>*Enrolled subjects
                                    </button>
                                </a>
                            </div>

                        </div>
                    </div>

                    <hr>
                    <div class="card">
                        <div class="card-body">

                            <!-- GRADE 11 1st SEMESTER -->
                            <div class="row col-md-12 table-responsive"
                                    style="margin-top:5%;">
                                <div class="table-responsive" style="margin-top:2%;"> 

                                    <!-- <h3>Grade 11 First Semester</h3>	 -->
                                    <?php 
                                        // echo "wee";
                                        $GRADE_TWELVE = 12;
                                        $GRADE_ELEVEN = 11;
                                        $FIRST_SEMESTER = "First";

                                        // Section Based on the enrollment.
                                        $enrollment_school_year = $studentEnroll->GetStudentSectionGradeElevenSchoolYear($username, $userLoggedInId, 11, "First");

                                        if($enrollment_school_year !== null){
                                            $term = $enrollment_school_year['term'];
                                            $period = $enrollment_school_year['period'];
                                            $school_year_id = $enrollment_school_year['school_year_id'];
                                            $enrollment_course_id = $enrollment_school_year['course_id'];

                                            $section = new Section($con, $enrollment_course_id);
                                            $enrollment_course_level = $section->GetSectionGradeLevel();

                                            $enrollment_section_name = $enroll->GetStudentCourseNameByCourseId($enrollment_course_id);
                                            
                                            echo "
                                                <h4 style='font-weight: 500;' class='text-primary text-center mb-3'>
                                                    Grade 11 $enrollment_section_name $period Semester (SY $term)
                                                </h4>

                                                <form action='grade_subject_generate.php' method='POST'>

                                                    <input type='hidden' value='$student_username' name='student_username'>
                                                    <input type='hidden' value='$student_id' name='student_id'>
                                                    <input type='hidden' value='$enrollment_course_id' name='enrollment_course_id'>
                                                    <input type='hidden' value='$enrollment_course_level' name='student_course_level'>
                                                    <input type='hidden' value='$period' name='current_school_year_period'>

                                                    <button name='grade_subject_generate' type='submit'
                                                        class='btn btn-outline-primary btn-sm'>
                                                        Generate PDF
                                                    </button>
                                                </form>
                                            ";

                                        }else{
                                            echo "
                                                <h3>Grade 11 First Semester</h3>	
                                            ";
                                        }
                                    ?>	
                                    <table style="font-weight: 200;" class="table table-striped table-bordered table-hover "  style="font-size:12px" cellspacing="0"  > 
                                        <thead>
                                            <tr class="text-center"> 
                                                <th class="text-success" rowspan="2">Code</th>
                                                <th class="text-success" rowspan="2">Type</th>
                                                <th class="text-success" rowspan="2">Description</th>  
                                                <th class="text-success" rowspan="2">Unit</th>
                                                <th class="text-success" rowspan="2">Prelim</th>
                                                <th class="text-success" rowspan="2">Midterm</th>
                                                <th class="text-success" rowspan="2">Pre-Final</th>
                                                <th class="text-success" rowspan="2">Final</th>
                                                <th class="text-success" rowspan="2">Average</th>
                                                <th class="text-success" rowspan="2">Remark</th>
                                            </tr>	
                                        </thead> 	

                                        <tbody>
                                            <?php 

                                                // $listOfSubjects = $studentEnroll->GetStudentSubjectListWithGrades($username);
                                                $listOfSubjects = $studentEnroll
                                                    ->GetStudentCurriculumBasedOnSemesterSubject($username,
                                                $userLoggedInId, $GRADE_ELEVEN, $FIRST_SEMESTER);


                                                if($listOfSubjects !== null){

                                                    foreach ($listOfSubjects as $key => $value) {
                                                        
                                                        $subject_id = $value['subject_id'];
                                                        $course_level = $value['course_level'];

                                                        $first = $value['first'];
                                                        $second = $value['second'];
                                                        $third = $value['third'];
                                                        $fourth = $value['fourth'];
                                                        $grade_remarks = $value['grade_remarks'];

                                                        $remarks_url = "";

                                                        $query_student_subject = $con->prepare("SELECT 
                                                    
                                                            t1.subject_id, t1.student_subject_id as t1_student_subject_id,
                                                            
                                                            t2.student_subject_id as t2_student_subject_id,
                                                            t2.remarks

                                                            FROM student_subject as t1

                                                            LEFT JOIN student_subject_grade as t2 ON t2.student_subject_id = t1.student_subject_id

                                                            WHERE t1.subject_id=:subject_id
                                                            AND t1.student_id=:student_id
                                                            LIMIT 1");

                                                        $query_student_subject->bindValue(":subject_id", $subject_id);
                                                        $query_student_subject->bindValue(":student_id", $student_id);
                                                        $query_student_subject->execute();


                                                        $t1_student_subject_id = null;
                                                        if($query_student_subject->rowCount() > 0){

                                                            $row = $query_student_subject->fetch(PDO::FETCH_ASSOC);

                                                            $student_subject_subject_id = $row['subject_id'];
                                                            $t1_student_subject_id = $row['t1_student_subject_id'];
                                                            $t2_student_subject_id = $row['t2_student_subject_id'];
                                                            $remarks = $row['remarks'];

                                                            // echo $t1_student_subject_id . " ";
                                                            // echo $t2_student_subject_id . " ";

                                                            if($t1_student_subject_id == $t2_student_subject_id){

                                                                $remarks_url = $remarks;

                                                            }else if($student_subject_subject_id == $subject_id
                                                                && $t1_student_subject_id != $t2_student_subject_id){

                                                                $remarks_url = "
                                                                    <a href='shs_transferee_remarks.php?student_id=$student_id&s_id=$subject_id&page=view_details'>
                                                                        <button type='button' class='btn btn-sm btn-primary'>Remark</button>
                                                                    </a>
                                                                ";
                                                            }
                                                        }
                                                    
                                                        echo '<tr class="text-center">'; 
                                                                echo '<td>'.$value['subject_code'].'</td>';
                                                                echo '<td>'.$value['subject_type'].'</td>';
                                                                echo '<td>'.$value['subject_title'].'</td>';
                                                                echo '<td>'.$value['unit'].'</td>';
                                                             
                                                                echo '<td>'.$first.'</td>';
                                                                echo '<td>'.$second.'</td>';
                                                                echo '<td>'.$third.'</td>';
                                                                echo '<td>'.$fourth.'</td>';
                                                                echo '<td></td>';
                                                                echo '<td>'.$grade_remarks.'</td>';

                                                        echo '</tr>';
                                                    }     
                                                }
                                            ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                            <!-- GRADE 11 2nd SEMESTER -->
                            <div class="row col-md-12 table-responsive"
                                    style="margin-top:5%;">
                                <div class="table-responsive" style="margin-top:2%;"> 

                                    <?php 

                                        $GRADE_TWELVE = 12;
                                        $GRADE_ELEVEN = 11;
                                        $FIRST_SEMESTER = "First";
                                        // Section Based on the enrollment.
                                        $enrollment_school_year = $studentEnroll->GetStudentSectionGradeElevenSchoolYear($username, $userLoggedInId, 11, "Second");

                                        if($enrollment_school_year !== null){
                                            $term = $enrollment_school_year['term'];
                                            $period = $enrollment_school_year['period'];
                                            $school_year_id = $enrollment_school_year['school_year_id'];
                                            $enrollment_course_id = $enrollment_school_year['course_id'];

                                            $section = new Section($con, $enrollment_course_id);
                                            $enrollment_course_level = $section->GetSectionGradeLevel();

                                            $enrollment_section_name = $enroll->GetStudentCourseNameByCourseId($enrollment_course_id);
                                            
                                            echo "
                                                <h4 style='font-weight: 500;' class='text-primary text-center mb-3'>
                                                    Grade 11 $enrollment_section_name $period Semester (SY $term)
                                                </h4>

                                                <form action='grade_subject_generate.php' method='POST'>

                                                    <input type='hidden' value='$student_username' name='student_username'>
                                                    <input type='hidden' value='$student_id' name='student_id'>
                                                    <input type='hidden' value='$enrollment_course_id' name='enrollment_course_id'>
                                                    <input type='hidden' value='$enrollment_course_level' name='student_course_level'>
                                                    <input type='hidden' value='$period' name='current_school_year_period'>

                                                    <button name='grade_subject_generate' type='submit'
                                                        class='btn btn-outline-primary btn-sm'>
                                                        Generate PDF
                                                    </button>
                                                </form>
                                            ";

                                        }else{
                                            echo "
                                                <h3>Grade 11 First Semester</h3>	
                                            ";
                                        }
                                    ?>	
                                    <table style="font-weight: 200;" class="table table-striped table-bordered table-hover "  style="font-size:12px" cellspacing="0"  > 
                                        <thead>
                                            <tr class="text-center"> 
                                                <th class="text-success" rowspan="2">Code</th>
                                                <th class="text-success" rowspan="2">Type</th>
                                                <th class="text-success" rowspan="2">Description</th>  
                                                <th class="text-success" rowspan="2">Unit</th>
                                                <th class="text-success" rowspan="2">Prelim</th>
                                                <th class="text-success" rowspan="2">Midterm</th>
                                                <th class="text-success" rowspan="2">Pre-Final</th>
                                                <th class="text-success" rowspan="2">Final</th>
                                                <th class="text-success" rowspan="2">Average</th>
                                                <th class="text-success" rowspan="2">Remark</th>
                                            </tr>	
                                        </thead> 	

                                        <tbody>
                                            <?php 

                                                // $listOfSubjects = $studentEnroll->GetStudentSubjectListWithGrades($username);
                                                $listOfSubjects = $studentEnroll
                                                    ->GetStudentCurriculumBasedOnSemesterSubject($username,
                                                $userLoggedInId, $GRADE_ELEVEN, $SECOND_SEMESTER);


                                                if($listOfSubjects !== null){

                                                    foreach ($listOfSubjects as $key => $value) {
                                                        
                                                        $subject_id = $value['subject_id'];
                                                        $course_level = $value['course_level'];

                                                        $first = $value['first'];
                                                        $second = $value['second'];
                                                        $third = $value['third'];
                                                        $fourth = $value['fourth'];
                                                        $grade_remarks = $value['grade_remarks'];

                                                        $remarks_url = "";

                                                        $query_student_subject = $con->prepare("SELECT 
                                                    
                                                            t1.subject_id, t1.student_subject_id as t1_student_subject_id,
                                                            
                                                            t2.student_subject_id as t2_student_subject_id,
                                                            t2.remarks

                                                            FROM student_subject as t1

                                                            LEFT JOIN student_subject_grade as t2 ON t2.student_subject_id = t1.student_subject_id

                                                            WHERE t1.subject_id=:subject_id
                                                            AND t1.student_id=:student_id
                                                            LIMIT 1");


                                                        $query_student_subject->bindValue(":subject_id", $subject_id);
                                                        $query_student_subject->bindValue(":student_id", $student_id);
                                                        $query_student_subject->execute();

                                                        $t1_student_subject_id = null;

                                                        if($query_student_subject->rowCount() > 0){

                                                            $row = $query_student_subject->fetch(PDO::FETCH_ASSOC);

                                                            $student_subject_subject_id = $row['subject_id'];
                                                            $t1_student_subject_id = $row['t1_student_subject_id'];
                                                            $t2_student_subject_id = $row['t2_student_subject_id'];
                                                            $remarks = $row['remarks'];

                                                            // echo $t1_student_subject_id . " ";
                                                            // echo $t2_student_subject_id . " ";

                                                            if($t1_student_subject_id == $t2_student_subject_id){

                                                                $remarks_url = $remarks;

                                                            }else if($student_subject_subject_id == $subject_id
                                                                && $t1_student_subject_id != $t2_student_subject_id){

                                                                $remarks_url = "
                                                                    <a href='shs_transferee_remarks.php?student_id=$student_id&s_id=$subject_id&page=view_details'>
                                                                        <button type='button' class='btn btn-sm btn-primary'>Remark</button>
                                                                    </a>
                                                                ";
                                                            }
                                                        }
                                                    
                                                        echo '<tr class="text-center">'; 
                                                                echo '<td>'.$value['subject_code'].'</td>';
                                                                echo '<td>'.$value['subject_type'].'</td>';
                                                                echo '<td>'.$value['subject_title'].'</td>';
                                                                echo '<td>'.$value['unit'].'</td>';
                                                             
                                                                echo '<td>'.$first.'</td>';
                                                                echo '<td>'.$second.'</td>';
                                                                echo '<td>'.$third.'</td>';
                                                                echo '<td>'.$fourth.'</td>';
                                                                echo '<td></td>';
                                                                echo '<td>'.$grade_remarks.'</td>';

                                                        echo '</tr>';
                                                    }     
                                                }
                                            ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>


                        </div>
                    </div>

                </div>
            <?php
            }

            # SHS Enrolled Subject 3
            if(isset($_GET['subject']) 
                && !isset($_GET['tertiary'])

                && $_GET['subject'] == "show" && isset($_GET['id'])){

                $studentEnroll = new StudentEnroll($con);

                $username = $studentEnroll->GetStudentUsername($_GET['id']);
                
                $userLoggedInId = $studentEnroll->GetStudentId($username);
                $course_main_id = $studentEnroll->GetStudentCourseId($username);
                $student_course_level = $studentEnroll->GetStudentCourseLevel($username);

                $student_program_id = $studentEnroll->GetStudentProgramId($course_main_id);

                $school_year_obj = $studentEnroll->GetActiveSchoolYearAndSemester();

                $current_school_year_id = $school_year_obj['school_year_id'];
                $current_school_year_term = $school_year_obj['term'];
                $current_school_year_period = $school_year_obj['period'];

                $student = new Student($con, $username);

                $student_id = $student->GetId();
                $is_student_graduated = $student->GetIsGraduated();
                $student_is_active = $student->CheckIfActive();
                $checkIfTertiary = $student->CheckIfTertiary();


                $studentSubject = new StudentSubject($con);


                if(isset($_POST['passed_remark_btn'])
                    && isset($_POST['subject_id'])
                    && isset($_POST['course_id'])
                    && isset($_POST['student_subject_id'])
                    && isset($_POST['subject_title'])

                ){

                    $subject_id = $_POST['subject_id'];
                    $course_id = $_POST['course_id'];
                    $student_subject_id = $_POST['student_subject_id'];
                    $subject_title = $_POST['subject_title'];

                    $sql = $con->prepare("INSERT INTO student_subject_grade 
                        (student_id, subject_id, remarks, student_subject_id, subject_title, course_id)
                        VALUES(:student_id, :subject_id, :remarks, :student_subject_id, :subject_title, :course_id)");
                        
                        $sql->bindValue(":student_id", $student_id);
                        $sql->bindValue(":subject_id", $subject_id);
                        $sql->bindValue(":remarks", "Passed");
                        $sql->bindValue(":student_subject_id", $student_subject_id);
                        $sql->bindValue(":subject_title", $subject_title);
                        $sql->bindValue(":course_id", $course_id);

                        if($sql->execute()){
                            // echo "success";
                            Alert::success("Subject $subject_title remarked as Passed.",
                                "view_details.php?subject=show&id=$student_id");
                        }

                }
                ?>
                <div class="row col-md-12">

                    <div class="content">

                        <div class="form-header">
                            <div class="header-content">
                                <h2><?php echo $lastname?>, <?php echo $firstname;?> <?php echo $middle_name;?>,</h2>
                            </div>

                            <div class="student-table">
                                <table class="inner-student-table">
                                    <tr>
                                        <th>Student No.</th>
                                        <th>Level</th>
                                        <th>Strand</th>
                                        <th>Status</th>
                                        <th>Added on:</th>
                                    </tr>

                                    <tr>
                                        <td><?php echo $student_unique_id; ?></td>
                                        <td><?php echo $student_course_level; ?></td>
                                        <td><?php echo $section_acronym; ?></td>
                                        <td><?php echo $payment_status; ?></td>
                                     

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

                                <a href="view_details.php?profile=show&id=<?php echo $student_id?>">
                                
                                    <button "
                                        type="button"
                                        class="selection-btn"
                                        id="student-details">
                                        <i class="bi bi-clipboard-check"></i>Student Details
                                    </button>
                                </a>

                            </div>

                            <div  class="enrolled-subjects">
                                <a href="view_details.php?grade_record=show&id=<?php echo $student_id;?>">
                                    <button 
                                        type="button"
                                        class="selection-btn"
                                        id="enrolled-subjects">
                                        <i class="bi bi-collection"></i>Grades Records
                                    </button>
                                </a>
                            </div>


                            <div class="enrolled-subjects">
                                <a href="view_details.php?subject=show&id=<?php echo $student_id?>">
                                    <button style="background-color:palevioletred;"
                                        type="button"
                                        class="selection-btn"
                                        id="enrolled-subjects">
                                        <i class="bi bi-collection"></i>Enrolled subjects
                                    </button>
                                </a>
                            </div>

                        </div>
                    </div>

                    <div style="display: none;" class="card">
                        <div class="card-body">
                            <div class="row col-md-12">
                                <div class="col-md-4 text-center">
                                    <a href="view_details.php?profile=show&id=<?php echo $student_id;?>">
                                        <button class="btn btn-lg btn-outline-primary">
                                            Student Information
                                        </button>
                                    </a>
                                </div>

                                <div class="col-md-4 text-center">
                                    <a href="view_details.php?grade_record=show&id=<?php echo $student_id;?>">
                                        <button class="btn btn-lg btn-outline-primary">
                                            Grade Records
                                        </button>
                                    </a>
                                </div>

                                <div class="col-md-4 text-center">
                                    <a href="view_details.php?subject=show&id=<?php echo $student_id;?>">
                                        <button class="btn btn-lg btn-primary">
                                            Enrolled Subjects
                                        </button>
                                    </a>
                                </div>

                            </div>
                        </div>
                    </div>

                    <!-- GRADE 11 1st SEM -->
                    <div class="row col-md-12 table-responsive"
                        style="margin-top:5%;">
                        <div class="table-responsive" style="margin-top:2%;"> 

                            <!-- <h3>Grade 11 First Semester</h3>	 -->
                            <?php 
                                // echo "wee";
                                $GRADE_TWELVE = 12;
                                $GRADE_ELEVEN = 11;
                                $FIRST_SEMESTER = "First";

                                // Section Based on the enrollment.
                                $enrollment_school_year = $studentEnroll->GetStudentSectionGradeElevenSchoolYear($username, $userLoggedInId, 11, "First");

                                if($enrollment_school_year !== null){
                                    $term = $enrollment_school_year['term'];
                                    $period = $enrollment_school_year['period'];
                                    $school_year_id = $enrollment_school_year['school_year_id'];
                                    $enrollment_course_id = $enrollment_school_year['course_id'];

                                    $section = new Section($con, $enrollment_course_id);
                                    $enrollment_course_level = $section->GetSectionGradeLevel();

                                    $enrollment_section_name = $enroll->GetStudentCourseNameByCourseId($enrollment_course_id);
                                    
                                    echo "
                                        <h4 style='font-weight: 500;' class='text-primary text-center mb-3'>
                                            Grade 11 $enrollment_section_name $period Semester (SY $term)
                                        </h4>

                                        <form action='enrolled_subject_generate.php' method='POST'>

                                            <input type='hidden' value='$student_username' name='student_username'>
                                            <input type='hidden' value='$student_id' name='student_id'>
                                            <input type='hidden' value='$enrollment_course_id' name='enrollment_course_id'>
                                            <input type='hidden' value='$enrollment_course_level' name='student_course_level'>
                                            <input type='hidden' value='$period' name='current_school_year_period'>

                                            <button name='enrolled_subject_generate' type='submit'
                                                class='btn btn-outline-primary btn-sm'>
                                                Generate PDF
                                            </button>
                                        </form>
                                    ";

                                }else{
                                    echo "
                                        <h3>Grade 11 First Semester</h3>	
                                    ";
                                }
                            ?>	

                            <table style="font-weight: 200;" class="table table-striped table-bordered table-hover"  style="font-size:12px" cellspacing="0"  > 
                                <thead>
                                    <tr class="text-center"> 
                                        <th class="text-success" rowspan="2">Subject</th>
                                        <th class="text-success" rowspan="2">Type</th>
                                        <th class="text-success" rowspan="2">Description</th>  
                                        <th class="text-success" rowspan="2">Unit</th>
                                        <th class="text-success">Semester</th>  
                                        <th class="text-success">Course Level</th>  
                                        <th style="min-width: 150px;" class="text-success">Time & Date</th>  
                                        <th class="text-success">Remarks</th>  
                                    </tr>
                                </thead> 	 
                                <tbody>
                                    <?php 

                                        // $listOfSubjects = $studentEnroll->GetStudentSubjectListWithGrades($username);
                                        $listOfSubjects = $studentEnroll
                                            ->GetStudentCurriculumBasedOnSemesterSubject($username,
                                        $userLoggedInId, $GRADE_ELEVEN, $FIRST_SEMESTER);

                                        if($listOfSubjects !== null){

                                            foreach ($listOfSubjects as $key => $value) {
                                                 
                                                $subject_id = $value['subject_id'];
                                                $course_id = $value['course_id'];
                                                $course_level = $value['course_level'];

                                                $schedule_day = $value['schedule_day'];
                                                $schedule_time = $value['schedule_time'];
                                                $time_from = $value['time_from'];
                                                $time_to = $value['time_to'];

                                                $remarks_url = "";

                                                $subject_title = $value['subject_title'];


                                                $check = $studentSubject->CheckAlreadyCreditedSubject($student_id,
                                                    $subject_title);

                                                $credited = "";
                                                
                                                if($check){
                                                    $credited = "Credited";
                                                }

                                                if($remarks_url == ""){
                                                    $remarks_url = $credited;
                                                }


                                                $query_student_subject = $con->prepare("SELECT 

                                                    t1.subject_id, t1.student_subject_id as t1_student_subject_id,
                                                    
                                                    t2.student_subject_id as t2_student_subject_id,
                                                    t2.remarks

                                                    FROM student_subject as t1

                                                    LEFT JOIN student_subject_grade as t2 ON t2.student_subject_id = t1.student_subject_id

                                                    WHERE t1.subject_id=:subject_id
                                                    AND t1.student_id=:student_id
                                                    LIMIT 1");

                                                $query_student_subject->bindValue(":subject_id", $subject_id);
                                                $query_student_subject->bindValue(":student_id", $student_id);
                                                $query_student_subject->execute();

                                                $t1_student_subject_id = null;

                                                    // echo $subject_id . " 1 ";
                                                if($query_student_subject->rowCount() > 0){

                                                    $row = $query_student_subject->fetch(PDO::FETCH_ASSOC);

                                                    $student_subject_subject_id = $row['subject_id'];
                                                    $t1_student_subject_id = $row['t1_student_subject_id'];
                                                    $t2_student_subject_id = $row['t2_student_subject_id'];
                                                    $remarks = $row['remarks'];

                                                    // echo $t1_student_subject_id . " ";
                                                    // echo $t2_student_subject_id . " ";

                                                    if($t1_student_subject_id == $t2_student_subject_id){

                                                        $remarks_url = $remarks;
                                                        // echo "we";
                                                    }

                                                    else if($student_subject_subject_id == $subject_id
                                                        && $t1_student_subject_id != $t2_student_subject_id 
                                                        && $payment_status == "Enrolled"
                                                        ){

                                                        // $remarks_url = "
                                                        //     <a href='shs_transferee_remarks.php?student_id=$student_id&s_id=$subject_id&page=view_details'>
                                                        //         <button type='button' class='btn btn-sm btn-primary'>Remark</button>
                                                        //     </a>
                                                        // ";

                                                                        
                                                        // $remarks_url = "
                                                        //     <form method='POST'>

                                                        //         <input type='hidden' name='subject_id' value='$subject_id'>
                                                        //         <input type='hidden' name='student_subject_id' value='$t1_student_subject_id'>
                                                        //         <input type='hidden' name='course_id' value='$course_id'>
                                                        //         <input type='hidden' name='subject_title' value='$subject_title'>

                                                        //         <button name='passed_remark_btn' type='submit' class='btn btn-sm btn-primary' 
                                                        //             onclick=\"if(confirm('Mark as Passed?')){ /* Perform your action here */ }\">Remark</button>
                                                        //     </form>
                                                        // ";

                                                        $markAsPassed = "MarkAsPassed($subject_id,
                                                            $student_id, \"Passed\",
                                                            $t1_student_subject_id, $course_id, \"$subject_title\")";

                                                        $remarks_url = "
                                                            <i style='color:blue; cursor:pointer;' onclick='$markAsPassed' class='fas fa-marker'></i>
                                                        ";

                                                    }
                                                }
                                                

                                                echo '<tr class="text-center">'; 
                                                        echo '<td>'.$value['subject_code'].'</td>';
                                                        echo '<td>'.$value['subject_type'].'</td>';
                                                        echo '<td>'.$value['subject_title'].'</td>';
                                                        echo '<td>'.$value['unit'].'</td>';
                                                        // echo '<td>'.$schedule_day.'</td>';
                                                        // echo '<td>'.$schedule_time.'</td>';
                                                        // echo '<td>'.$room.'</td>';
                                                        // echo '<td>'.$section.'</td>';
                                                        echo '<td>'.$value['semester'].'</td>';
                                                        echo '<td>'.$value['course_level'].'</td>';
                                                        echo '<td>'.$schedule_time.' '.$schedule_day.'</td>';
                                                        echo '<td>'.$remarks_url.'</td>';
                                                echo '</tr>';
                                            }     
                                        }
                                    ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    
                    <!-- GRADE 11 2nd SEM -->
                    <div class="row col-md-12 table-responsive"
                        style="margin-top:5%;">
                        <div class="table-responsive" style="margin-top:2%;"> 
                            
                            <?php 
                                // echo "wee";
                                $GRADE_TWELVE = 12;
                                $GRADE_ELEVEN = 11;
                                $FIRST_SEMESTER = "First";

                                // Section Based on the enrollment.
                                $enrollment_school_year = $studentEnroll->GetStudentSectionGradeLevelSemester($username, $userLoggedInId,
                                    $GRADE_ELEVEN, $SECOND_SEMESTER);

                                if($enrollment_school_year !== null){
                                    $term = $enrollment_school_year['term'];
                                    $period = $enrollment_school_year['period'];
                                    $school_year_id = $enrollment_school_year['school_year_id'];
                                    $enrollment_course_id = $enrollment_school_year['course_id'];

                                    
                                    $section = new Section($con, $enrollment_course_id);
                                    $enrollment_course_level = $section->GetSectionGradeLevel();
                                    $enrollment_section_name = $enroll->GetStudentCourseNameByCourseId($enrollment_course_id);
                                    
                                    echo "
                                        <h4 style='font-weight: 500;' class='text-primary text-center mb-3'>
                                            Grade 11 $enrollment_section_name $period Semester (SY $term)
                                        </h4>

                                        <form action='enrolled_subject_generate.php' method='POST'>

                                            <input type='hidden' value='$student_username' name='student_username'>
                                            <input type='hidden' value='$student_id' name='student_id'>
                                            <input type='hidden' value='$enrollment_course_id' name='enrollment_course_id'>
                                            <input type='hidden' value='$enrollment_course_level' name='student_course_level'>
                                            <input type='hidden' value='$period' name='current_school_year_period'>

                                            <button name='enrolled_subject_generate' type='submit'
                                                class='btn btn-outline-primary btn-sm'>
                                                Generate PDF
                                            </button>

                                            <button type='button'
                                                class='btn btn-primary btn-sm'>
                                                :
                                            </button>
                                        </form>
                                    ";
                                }else{
                                    echo "
                                        <h3>Grade 11 Second Semester</h3>	
                                    ";
                                }
                            ?>	

                            <!-- S2 -->
                            <?php 

                                # ONLY FOR GRADE 11 2nd BECAUSE OF MOVING UP.
                                $isFinished = $old_enroll->CheckIfGradeLevelSemesterSubjectWereAllPassed(
                                    $student_id, $GRADE_ELEVEN, $SECOND_SEMESTER);

                                $checkMoveUp = $student->CheckShsEligibleForMoveUp($student_id,
                                                    $student_program_id);
                                
                                $moveUpBtn = "moveUpAction(\"$student_username\", $student_id)";

                                if($checkMoveUp == true && $student_course_level != $GRADE_TWELVE){

                                    $wasSuccess = $old_enroll->StudentMoveUpToGrade12($student_username);

                                    if($wasSuccess){

                                        AdminUser::success("$student_username has been Move Up to Grade 12 (NON TRANS)"
                                            , "view_details.php?subject=show&id=$student_id");
                                        exit();
                                    }
                                }
                            ?>

                            <table id="shs_subjects_table" style="font-weight: 200;" class="table table-striped table-bordered table-hover "  style="font-size:12px" cellspacing="0"  > 
                                <thead>
                                    <tr class="text-center"> 
                                        <th class="text-success" rowspan="2">Subject</th>
                                        <th class="text-success" rowspan="2">Type</th>
                                        <th class="text-success" rowspan="2">Description</th>  
                                        <th class="text-success" rowspan="2">Unit</th>
                                    </tr>	
                                    <tr> 
                                        <th class="text-success">Semester</th>  
                                        <th class="text-success">Course Level</th>  
                                        <th class="text-success">Remarks</th>  
                                    </tr>
                                </thead> 	 
                                <tbody>
                                    <?php 

                                        // $listOfSubjects = $studentEnroll->GetStudentSubjectListWithGrades($username);

                                        $listOfSubjects = $studentEnroll
                                            ->GetStudentCurriculumBasedOnSemesterSubject($username,
                                        $userLoggedInId, $GRADE_ELEVEN, $SECOND_SEMESTER);

                                        if($listOfSubjects !== null){

                                            foreach ($listOfSubjects as $key => $value) {
                                                 
                                                $subject_id = $value['subject_id'];
                                                $course_id = $value['subject_id'];
                                                $course_level = $value['course_level'];
                                                $subject_title = $value['subject_title'];

                                                $remarks_url = "";

                                                $query_student_subject = $con->prepare("SELECT 
                                            
                                                    t1.subject_id, t1.student_subject_id as t1_student_subject_id,
                                                    
                                                    t2.student_subject_id as t2_student_subject_id,
                                                    t2.remarks

                                                    FROM student_subject as t1

                                                    LEFT JOIN student_subject_grade as t2 ON t2.student_subject_id = t1.student_subject_id

                                                    WHERE t1.subject_id=:subject_id
                                                    AND t1.student_id=:student_id
                                                    LIMIT 1");

                                                $query_student_subject->bindValue(":subject_id", $subject_id);
                                                $query_student_subject->bindValue(":student_id", $student_id);
                                                $query_student_subject->execute();


                                                $t1_student_subject_id = null;

                                                if($query_student_subject->rowCount() > 0){

                                                    $row = $query_student_subject->fetch(PDO::FETCH_ASSOC);

                                                    $student_subject_subject_id = $row['subject_id'];
                                                    $t1_student_subject_id = $row['t1_student_subject_id'];
                                                    $t2_student_subject_id = $row['t2_student_subject_id'];
                                                    $remarks = $row['remarks'];

                                                    // echo $t1_student_subject_id . " ";
                                                    // echo $t2_student_subject_id . " ";

                                                    if($t1_student_subject_id == $t2_student_subject_id){

                                                        $remarks_url = $remarks;

                                                    }else if($student_subject_subject_id == $subject_id
                                                        && $t1_student_subject_id != $t2_student_subject_id
                                                        && $payment_status == "Enrolled"
                                                        
                                                        ){

                                                        $markAsPassed = "MarkAsPassed($subject_id,
                                                            $student_id, \"Passed\",
                                                            $t1_student_subject_id, $course_id, \"$subject_title\")";

                                                        $remarks_url = "
                                                            <i style='color:blue; cursor:pointer;' onclick='$markAsPassed' class='fas fa-marker'></i>

                                                        ";

                                                        // $remarks_url = "
                                                        //     <a href='shs_transferee_remarks.php?student_id=$student_id&s_id=$subject_id&page=view_details'>
                                                        //         <button type='button' class='btn btn-sm btn-primary'>Remark</button>
                                                        //     </a>
                                                        // ";

                                                        // $remarks_url = "
                                                        //     <form method='POST'>

                                                        //         <input type='hidden' name='subject_id' value='$subject_id'>
                                                        //         <input type='hidden' name='student_subject_id' value='$t1_student_subject_id'>
                                                        //         <input type='hidden' name='course_id' value='$course_id'>
                                                        //         <input type='hidden' name='subject_title' value='$subject_title'>

                                                        //         <button name='passed_remark_btn' type='submit' class='btn btn-sm btn-primary' 
                                                        //             onclick=\"if(confirm('Mark as Passed?')){ /* Perform your action here */ }\">Remark</button>
                                                        //     </form>
                                                        // ";
                                                                # ORIG
                                                        
                                                    }
                                                }

                                                $subject_title = $value['subject_title'];


                                                $check = $studentSubject->CheckAlreadyCreditedSubject($student_id,
                                                    $subject_title);

                                                $credited = "";
                                                
                                                if($check){
                                                    $credited = "Credited";
                                                }

                                                if($remarks_url == ""){
                                                    $remarks_url = $credited;
                                                }
                                            
                                                echo '<tr class="text-center">'; 
                                                        echo '<td>'.$value['subject_code'].'</td>';
                                                        echo '<td>'.$value['subject_type'].'</td>';
                                                        echo '<td>'.$value['subject_title'].'</td>';
                                                        echo '<td>'.$value['unit'].'</td>';
                                                        // echo '<td>'.$schedule_day.'</td>';
                                                        // echo '<td>'.$schedule_time.'</td>';
                                                        // echo '<td>'.$room.'</td>';
                                                        // echo '<td>'.$section.'</td>';
                                                        echo '<td>'.$value['semester'].'</td>';
                                                        echo '<td>'.$value['course_level'].'</td>';
                                                        echo '<td>'.$remarks_url.'</td>';
                                                echo '</tr>';
                                            }     
                                        }
                                        else{
                                            echo "No Datax was found for Grade 11 1st Semester.";
                                        }
                                    ?>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- GRADE 12 1st SEM -->
                    <div class="row col-md-12 table-responsive"
                        style="margin-top:5%;">
                        <div class="table-responsive" style="margin-top:2%;"> 
                            <?php 
                                // echo "wee";
                                $GRADE_TWELVE = 12;
                                $GRADE_ELEVEN = 11;
                                $FIRST_SEMESTER = "First";

                                // Section Based on the enrollment.
                                $enrollment_school_year = $studentEnroll->GetStudentSectionGradeLevelSemester($username, $userLoggedInId,
                                    $GRADE_TWELVE, $FIRST_SEMESTER);

                                if($enrollment_school_year !== null){
                                    $term = $enrollment_school_year['term'];
                                    $period = $enrollment_school_year['period'];
                                    $school_year_id = $enrollment_school_year['school_year_id'];
                                    $enrollment_course_id = $enrollment_school_year['course_id'];


                                    $enrollment_section_name = $enroll->GetStudentCourseNameByCourseId($enrollment_course_id);

                                    echo "
                                        <h4 style='font-weight: 500;' class='text-primary text-center mb-3'>
                                            Grade 12 $enrollment_section_name $period Semester (SY $term)
                                        </h4>
                                    ";
                                }else{
                                    echo "<h3 class='text-center'>Grade 12 First Semester</h3>";	
                                }
                            ?>		

                            <table style="font-weight: 200;" class="table table-striped table-bordered table-hover "  style="font-size:12px" cellspacing="0"  > 
                                <thead>
                                    <tr class="text-center"> 
                                        <th class="text-success" rowspan="2">Subject</th>
                                        <th class="text-success" rowspan="2">Type</th>
                                        <th class="text-success" rowspan="2">Description</th>  
                                        <th class="text-success" rowspan="2">Unit</th>
                                    </tr>	
                                    <tr class="text-center"> 

                                        <!-- <th>Day</th> 
                                        <th>Time</th>
                                        <th>Room</th> 
                                        <th>Section</th>   -->
                                        <th class="text-success">Semester</th>  
                                        <th class="text-success">Course Level</th>  
                                        <th class="text-success">Remarks</th>  
                                    </tr>
                                </thead> 	 
                                <tbody>
                                    <?php 

                                        // $listOfSubjects = $studentEnroll->GetStudentSubjectListWithGrades($username);
                                        $listOfSubjects = $studentEnroll
                                            ->GetStudentCurriculumBasedOnSemesterSubject($username,
                                        $userLoggedInId, $GRADE_TWELVE, $FIRST_SEMESTER);

                                        if($listOfSubjects !== null){

                                            foreach ($listOfSubjects as $key => $value) {
                                                 
                                                $subject_id = $value['subject_id'];
                                                $course_id = $value['subject_id'];

                                                $course_level = $value['course_level'];
                                                $subject_title = $value['subject_title'];

                                                $remarks_url = "";

                                                $query_student_subject = $con->prepare("SELECT 
                                            
                                                    t1.subject_id, t1.student_subject_id as t1_student_subject_id,
                                                    
                                                    t2.student_subject_id as t2_student_subject_id,
                                                    t2.remarks

                                                    FROM student_subject as t1

                                                    LEFT JOIN student_subject_grade as t2 ON t2.student_subject_id = t1.student_subject_id

                                                    WHERE t1.subject_id=:subject_id
                                                    AND t1.student_id=:student_id
                                                    LIMIT 1");

                                                $query_student_subject->bindValue(":subject_id", $subject_id);
                                                $query_student_subject->bindValue(":student_id", $student_id);
                                                $query_student_subject->execute();


                                                $t1_student_subject_id = null;

                                                    // echo $subject_id . " 1 ";

                                                if($query_student_subject->rowCount() > 0){

                                                    $row = $query_student_subject->fetch(PDO::FETCH_ASSOC);

                                                    $student_subject_subject_id = $row['subject_id'];
                                                    $t1_student_subject_id = $row['t1_student_subject_id'];
                                                    $t2_student_subject_id = $row['t2_student_subject_id'];
                                                    $remarks = $row['remarks'];

                                                    // echo $t1_student_subject_id . " ";
                                                    // echo $t2_student_subject_id . " ";

                                                    if($t1_student_subject_id == $t2_student_subject_id){

                                                        $remarks_url = $remarks;

                                                    }else if($student_subject_subject_id == $subject_id
                                                        && $t1_student_subject_id != $t2_student_subject_id
                                                        && $payment_status == "Enrolled"
                                                        
                                                        ){

                                                        $markAsPassed = "MarkAsPassed($subject_id,
                                                            $student_id, \"Passed\",
                                                            $t1_student_subject_id, $course_id, \"$subject_title\")";

                                                        $remarks_url = "
                                                            <i style='color:blue; cursor:pointer;' onclick='$markAsPassed' class='fas fa-marker'></i>
                                                        ";

                                                        // $remarks_url = "
                                                        //     <form method='POST'>

                                                        //         <input type='hidden' name='subject_id' value='$subject_id'>
                                                        //         <input type='hidden' name='student_subject_id' value='$t1_student_subject_id'>
                                                        //         <input type='hidden' name='course_id' value='$course_id'>
                                                        //         <input type='hidden' name='subject_title' value='$subject_title'>

                                                        //         <button name='passed_remark_btn' type='submit' class='btn btn-sm btn-primary' 
                                                        //             onclick=\"if(confirm('Mark as Passed?')){ /* Perform your action here */ }\">Remark</button>
                                                        //     </form>
                                                        // ";

                                                        // $remarks_url = "
                                                        //     <a href='shs_transferee_remarks.php?student_id=$student_id&s_id=$subject_id&page=view_details'>
                                                        //         <button type='button' class='btn btn-sm btn-primary'>Remark</button>
                                                        //     </a>
                                                        // ";


                                                    }
                                                }

                                                $subject_title = $value['subject_title'];


                                                $check = $studentSubject->CheckAlreadyCreditedSubject($student_id,
                                                    $subject_title);

                                                $credited = "";
                                                
                                                if($check){
                                                    $credited = "Credited";
                                                }

                                                if($remarks_url == ""){
                                                    $remarks_url = $credited;
                                                }
                                            
                                                echo '<tr class="text-center">'; 
                                                        echo '<td>'.$value['subject_code'].'</td>';
                                                        echo '<td>'.$value['subject_type'].'</td>';
                                                        echo '<td>'.$value['subject_title'].'</td>';
                                                        echo '<td>'.$value['unit'].'</td>';
                                                        // echo '<td>'.$schedule_day.'</td>';
                                                        // echo '<td>'.$schedule_time.'</td>';
                                                        // echo '<td>'.$room.'</td>';
                                                        // echo '<td>'.$section.'</td>';
                                                        echo '<td>'.$value['semester'].'</td>';
                                                        echo '<td>'.$value['course_level'].'</td>';
                                                        echo '<td>'.$remarks_url.'</td>';
                                                echo '</tr>';
                                            }     
                                        }
                                        else{
                                            echo "No Datax was found for Grade 11 1st Semester.";
                                        }
                                        
                                    ?>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- GRADE 12 2nd SEM -->
                    <div class="row col-md-12 table-responsive"
                        style="margin-top:5%;">
                        <div class="table-responsive" style="margin-top:2%;"> 
                            
                            <?php 
                                // echo "wee";
                                $GRADE_TWELVE = 12;
                                $GRADE_ELEVEN = 11;
                                $FIRST_SEMESTER = "First";

                                // Section Based on the enrollment.
                                $enrollment_school_year = $studentEnroll->GetStudentSectionGradeLevelSemester($username, $userLoggedInId,
                                    $GRADE_TWELVE, $SECOND_SEMESTER);

                                if($enrollment_school_year !== null){
                                    $term = $enrollment_school_year['term'];
                                    $period = $enrollment_school_year['period'];
                                    $school_year_id = $enrollment_school_year['school_year_id'];
                                    $enrollment_course_id = $enrollment_school_year['course_id'];


                                    $enrollment_section_name = $enroll->GetStudentCourseNameByCourseId($enrollment_course_id);

                                    echo "
                                        <h4 style='font-weight: 500;' class='text-primary text-center mb-3'>
                                            Grade 12 $enrollment_section_name $period Semester (SY $term)
                                        </h4>
                                    ";
                                }else{
                                    echo "<h3 class='text-center'>Grade 12 Second Semester</h3>";	
                                    
                                }
                                
                                # Check passed All SHS Grade 11 1st sem to Grade 12 2nd Sem Subjects.

                                $isCandidateForGraduation = $student->CheckShsStudentGraduateCandidate($student_id, $student_program_id);

                                $isCandidateForGraduationv2 = $student->
                                    CheckShsStudentGraduateCandidateTitleBased($student_id, 
                                    $student_program_id);

                                if($isCandidateForGraduationv2 && $is_student_graduated == 0
                                    && $checkIfTertiary == 0 && $student_is_active == 1                                     ){

                                    // Update 
                                   $wasSuccessGraduate = $student->MarkAsGraduate($student_id);
                                   if($wasSuccessGraduate){
                                        AdminUser::success("SHS Student: $student_id is now graduated in the system.", "");
                                   }

                                } 
                            ?>		

                            <table style="font-weight: 200;" class="table table-striped table-bordered table-hover "  style="font-size:12px" cellspacing="0"  > 
                                <thead>
                                    <tr class="text-center"> 
                                        <th class="text-success" rowspan="2">Subject</th>
                                        <th class="text-success" rowspan="2">Type</th>
                                        <th class="text-success" rowspan="2">Description</th>  
                                        <th class="text-success" rowspan="2">Unit</th>
                                        <th class="text-muted" colspan="4">Schedule</th> 
                                    </tr>	
                                    <tr class="text-center"> 

                                        <!-- <th>Day</th> 
                                        <th>Time</th>
                                        <th>Room</th> 
                                        <th>Section</th>   -->
                                        <th class="text-success">Semester</th>  
                                        <th class="text-success">Course Level</th>  
                                        <th class="text-success">Remarks</th>  
                                    </tr>
                                </thead> 	 
                                <tbody>
                                    <?php 

                                        // $listOfSubjects = $studentEnroll->GetStudentSubjectListWithGrades($username);
                                        $listOfSubjects = $studentEnroll
                                            ->GetStudentCurriculumBasedOnSemesterSubject($username,
                                        $userLoggedInId, $GRADE_TWELVE, $SECOND_SEMESTER);

                                        if($listOfSubjects !== null){

                                            foreach ($listOfSubjects as $key => $value) {
                                                 
                                                $subject_id = $value['subject_id'];
                                                $course_id = $value['subject_id'];

                                                $course_level = $value['course_level'];
                                                $subject_title = $value['subject_title'];

                                                $remarks_url = "";

                                                $query_student_subject = $con->prepare("SELECT 
                                            
                                                    t1.subject_id, t1.student_subject_id as t1_student_subject_id,
                                                    
                                                    t2.student_subject_id as t2_student_subject_id,
                                                    t2.remarks

                                                    FROM student_subject as t1

                                                    LEFT JOIN student_subject_grade as t2 ON t2.student_subject_id = t1.student_subject_id

                                                    WHERE t1.subject_id=:subject_id
                                                    AND t1.student_id=:student_id
                                                    LIMIT 1");

                                                $query_student_subject->bindValue(":subject_id", $subject_id);
                                                $query_student_subject->bindValue(":student_id", $student_id);
                                                $query_student_subject->execute();


                                                $t1_student_subject_id = null;

                                                    // echo $subject_id . " 1 ";

                                                if($query_student_subject->rowCount() > 0){

                                                    $row = $query_student_subject->fetch(PDO::FETCH_ASSOC);

                                                    $student_subject_subject_id = $row['subject_id'];
                                                    $t1_student_subject_id = $row['t1_student_subject_id'];
                                                    $t2_student_subject_id = $row['t2_student_subject_id'];
                                                    $remarks = $row['remarks'];

                                                    // echo $t1_student_subject_id . " ";
                                                    // echo $t2_student_subject_id . " ";

                                                    if($t1_student_subject_id == $t2_student_subject_id){

                                                        $remarks_url = $remarks;

                                                    }else if($student_subject_subject_id == $subject_id
                                                        && $t1_student_subject_id != $t2_student_subject_id
                                                        && $payment_status == "Enrolled"
                                                        
                                                        ){


                                                        $markAsPassed = "MarkAsPassed($subject_id,
                                                            $student_id, \"Passed\",
                                                            $t1_student_subject_id, $course_id, \"$subject_title\")";

                                                        $remarks_url = "
                                                            <i style='color:blue; cursor:pointer;' onclick='$markAsPassed' class='fas fa-marker'></i>
                                                        ";

                                                        // $remarks_url = "
                                                        //     <form method='POST'>

                                                        //         <input type='hidden' name='subject_id' value='$subject_id'>
                                                        //         <input type='hidden' name='student_subject_id' value='$t1_student_subject_id'>
                                                        //         <input type='hidden' name='course_id' value='$course_id'>
                                                        //         <input type='hidden' name='subject_title' value='$subject_title'>

                                                        //         <button name='passed_remark_btn' type='submit' class='btn btn-sm btn-primary' 
                                                        //             onclick=\"if(confirm('Mark as Passed?')){ /* Perform your action here */ }\">Remark</button>
                                                        //     </form>
                                                        // ";
                                                        // $remarks_url = "
                                                        //     <a href='shs_transferee_remarks.php?student_id=$student_id&s_id=$subject_id&page=view_details'>
                                                        //         <button type='button' class='btn btn-sm btn-primary'>Remark</button>
                                                        //     </a>
                                                        // ";
                                                    }
                                                }


                                                $subject_title = $value['subject_title'];


                                                $check = $studentSubject->CheckAlreadyCreditedSubject($student_id,
                                                    $subject_title);

                                                $credited = "";
                                                
                                                if($check){
                                                    $credited = "Credited";
                                                }

                                                if($remarks_url == ""){
                                                    $remarks_url = $credited;
                                                }
                                            
                                                echo '<tr class="text-center">'; 
                                                        echo '<td>'.$value['subject_code'].'</td>';
                                                        echo '<td>'.$value['subject_type'].'</td>';
                                                        echo '<td>'.$value['subject_title'].'</td>';
                                                        echo '<td>'.$value['unit'].'</td>';
                                                        // echo '<td>'.$schedule_day.'</td>';
                                                        // echo '<td>'.$schedule_time.'</td>';
                                                        // echo '<td>'.$room.'</td>';
                                                        // echo '<td>'.$section.'</td>';
                                                        echo '<td>'.$value['semester'].'</td>';
                                                        echo '<td>'.$value['course_level'].'</td>';
                                                        echo '<td>'.$remarks_url.'</td>';
                                                echo '</tr>';
                                            }     
                                        }
                                        else{
                                            echo "No Datax was found for Grade 11 1st Semester.";
                                        }
                                    ?>
                                </tbody>
                            </table>
                        </div>
                    </div>

                <?php
            }

            # Tertiary Enrolled Subject 3
            if(isset($_GET['subject']) 
                && isset($_GET['tertiary'])
                && $_GET['subject'] == "show" && isset($_GET['id'])){

                $studentEnroll = new StudentEnroll($con);

                $username = $studentEnroll->GetStudentUsername($_GET['id']);
                $userLoggedInId = $studentEnroll->GetStudentId($username);
                $course_main_id = $studentEnroll->GetStudentCourseId($username);
                $student_course_level = $studentEnroll->GetStudentCourseLevel($username);

                $school_year_obj = $studentEnroll->GetActiveSchoolYearAndSemester();

                $current_school_year_id = $school_year_obj['school_year_id'];
                $current_school_year_term = $school_year_obj['term'];
                $current_school_year_period = $school_year_obj['period'];

                $student_program_id = $studentEnroll->GetStudentProgramId($course_main_id);

                $FIRST_YEAR = 1;
                $SECOND_YEAR = 2;
                $THIRD_YEAR = 3;
                $FOURTH_YEAR  = 4;

                $subject = new Subject($con, $student_id, null);

                // echo $student_program_id;

                $student = new Student($con, $username);

                $is_student_graduated = $student->GetIsGraduated();
                $student_is_active = $student->CheckIfActive();
                $checkIfTertiary = $student->CheckIfTertiary();

                $studentSubject  = new StudentSubject($con, $username);

                if(isset($_POST['passed_remark_btn'])
                    && isset($_POST['subject_id'])
                    && isset($_POST['course_id'])
                    && isset($_POST['student_subject_id'])
                    && isset($_POST['subject_title']) ){
                    
                    $subject_id = $_POST['subject_id'];

                    $course_id = $_POST['course_id'];

                    $student_subject_id = $_POST['student_subject_id'];

                    $subject_title = $_POST['subject_title'];

                    $sql = $con->prepare("INSERT INTO student_subject_grade 
                        (student_id, subject_id, remarks, student_subject_id, subject_title, course_id)
                        VALUES(:student_id, :subject_id, :remarks, :student_subject_id, :subject_title, :course_id)");
                        
                        $sql->bindValue(":student_id", $student_id);
                        $sql->bindValue(":subject_id", $subject_id);
                        $sql->bindValue(":remarks", "Passed");
                        $sql->bindValue(":student_subject_id", $student_subject_id);
                        $sql->bindValue(":subject_title", $subject_title);
                        $sql->bindValue(":course_id", $course_id);

                        if($sql->execute()){
                            // echo "success";
                            Alert::success("Subject $subject_title remarked as Passed.",
                                "view_details.php?subject=show&tertiary=true&id=$student_id");
                        }
                }

                ?>
                <div class="row col-md-12">

                    <div class="content">

                        <div class="form-header">
                            <div class="header-content">
                                <h2><?php echo $lastname?>, <?php echo $firstname;?> <?php echo $middle_name;?>,</h2>
                            </div>

                            <div class="student-table">
                                <table class="inner-student-table">
                                    <tr>
                                        <th>Student No.</th>
                                        <th>Level</th>
                                        <th>Strand</th>
                                        <th>Status</th>
                                        <th>Added on:</th>
                                    </tr>

                                    <tr>
                                        <td><?php echo $student_unique_id; ?></td>
                                        <td><?php echo $student_course_level; ?></td>
                                        <td><?php echo $section_acronym; ?></td>
                                        <td><?php echo $payment_status; ?></td>
                                     

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

                                <a href="view_details.php?profile=show&id=<?php echo $student_id?>">
                                    <button "
                                        type="button"
                                        class="selection-btn"
                                        id="student-details">
                                        <i class="bi bi-clipboard-check"></i>Student Details
                                    </button>
                                </a>
                            </div>

                            <div  class="enrolled-subjects">
                                <a href="view_details.php?grade_record=show&&tertiary=true&id=<?php echo $student_id;?>">
                                    <button 
                                        type="button"
                                        class="selection-btn"
                                        id="enrolled-subjects">
                                        <i class="bi bi-collection"></i>*Grades Records
                                    </button>
                                </a>
                            </div>


                            <div class="enrolled-subjects">
                                <a href="view_details.php?subject=show&tertiary=true&id=<?php echo $student_id;?>">
                                    <button style="background-color:palevioletred;"
                                        type="button"
                                        class="selection-btn"
                                        id="enrolled-subjects">
                                        <i class="bi bi-collection"></i>*Enrolled subjects
                                    </button>
                                </a>
                            </div>

                        </div>
                    </div>


                    <!-- 1st Year First Semester -->
                    <div class="row col-md-12 table-responsive"
                        style="margin-top:5%;">
                        <div class="table-responsive" style="margin-top:2%;"> 

                            <?php 
                                if(count($subject->
                                    CheckStudentGradeLevelEnrolledSubjectSemesterv2($userLoggedInId,
                                    $FIRST_SEMESTER, $FIRST_YEAR)) > 0){

                                    
                                    $obj = $subject->CheckStudentGradeLevelEnrolledSubjectSemesterv2($userLoggedInId,
                                        $FIRST_SEMESTER, $FIRST_YEAR);

                                    $term = $obj['term'];
                                    $period = $obj['period'];
                                    $school_year_id = $obj['school_year_id'];
                                    $enrollment_course_id = $obj['course_id'];
                                    $enrollment_form_id = $obj['enrollment_form_id'];

                                    $section = new Section($con, $enrollment_course_id);
                                    $enrollment_course_level = $section->GetSectionGradeLevel();
                                    $enrollment_section_name = $enroll->GetStudentCourseNameByCourseId($enrollment_course_id);
                                

                                    echo "
                                    <h4 style='font-weight: 500;' class='text-primary text-center mb-3'>
                                        1st Year $enrollment_section_name $period Semester (SY $term) #$enrollment_form_id
                                    </h4>

                                    <form action='enrolled_subject_generate.php' method='POST'>

                                        <input type='hidden' value='$student_username' name='student_username'>
                                        <input type='hidden' value='$student_id' name='student_id'>
                                        <input type='hidden' value='$enrollment_course_id' name='enrollment_course_id'>
                                        <input type='hidden' value='$enrollment_course_level' name='student_course_level'>
                                        <input type='hidden' value='$period' name='current_school_year_period'>

                                        <button name='enrolled_subject_generate' type='submit'
                                            class='btn btn-outline-primary btn-sm'>
                                            Generate PDF
                                        </button>
                                    </form>
                                ";

                                }else{
                                    echo "
                                        <h3 class='text-center'>1st Year First Semester</h3>	
                                    ";
                                }

                                // Section Based on the enrollment.
                                $enrollment_school_year = $studentEnroll->
                                    GetStudentSectionGradeElevenSchoolYear($username, 
                                        $userLoggedInId, $SECOND_YEAR, $FIRST_SEMESTER);
                            ?>	

                            <table style="font-weight: 200;" class="table table-striped table-bordered table-hover "  style="font-size:12px" cellspacing="0"  > 
                                <thead>
                                    <tr class="text-center"> 
                                        <th class="text-success" rowspan="2">Subject</th>
                                        <th class="text-success" rowspan="2">Type</th>
                                        <th class="text-success" rowspan="2">Description</th>  
                                        <th class="text-success" rowspan="2">Unit</th>
                                    </tr>	
                                    <tr class="text-center"> 
                                        <th class="text-success">Semester</th>  
                                        <th class="text-success">Year Level</th>  
                                        <th class="text-success">Remarks</th>  
                                    </tr>
                                </thead> 	 
                                <tbody>
                                    <?php 
                                        // $listOfSubjects = $studentEnroll->GetStudentSubjectListWithGrades($username);
                                        $listOfSubjects = $studentEnroll
                                            ->GetStudentCurriculumBasedOnSemesterSubject($username,
                                        $userLoggedInId, $FIRST_YEAR, $FIRST_SEMESTER);

                                        if($listOfSubjects !== null){

                                            foreach ($listOfSubjects as $key => $value) {
                                                 
                                                $subject_id = $value['subject_id'];
                                                $course_id = $value['course_id'];
                                                $subject_title = $value['subject_title'];
                                                $course_level = $value['course_level'];

                                                $remarks_url = "";

                                                $query_student_subject = $con->prepare("SELECT 

                                                    t1.subject_id, t1.student_subject_id as t1_student_subject_id,
                                                    
                                                    t2.student_subject_id as t2_student_subject_id,
                                                    t2.remarks

                                                    FROM student_subject as t1

                                                    LEFT JOIN student_subject_grade as t2 ON t2.student_subject_id = t1.student_subject_id

                                                    WHERE t1.subject_id=:subject_id
                                                    AND t1.student_id=:student_id
                                                    LIMIT 1");

                                                $query_student_subject->bindValue(":subject_id", $subject_id);
                                                $query_student_subject->bindValue(":student_id", $student_id);
                                                $query_student_subject->execute();

                                                $t1_student_subject_id = null;

                                                if($query_student_subject->rowCount() > 0){

                                                    $row = $query_student_subject->fetch(PDO::FETCH_ASSOC);

                                                    $student_subject_subject_id = $row['subject_id'];
                                                    $t1_student_subject_id = $row['t1_student_subject_id'];
                                                    $t2_student_subject_id = $row['t2_student_subject_id'];
                                                    $remarks = $row['remarks'];

                                                    if($t1_student_subject_id == $t2_student_subject_id){

                                                        $remarks_url = $remarks;
                                                    }
                     
                                                    else if($student_subject_subject_id == $subject_id
                                                        && $t1_student_subject_id != $t2_student_subject_id 
                                                        && $payment_status == "Enrolled"
                                                        ){


                                                        $markAsPassed = "MarkAsPassed($subject_id,
                                                            $student_id, \"Passed\",
                                                            $t1_student_subject_id, $course_id, \"$subject_title\")";

                                                        $remarks_url = "
                                                            <i style='color:blue; cursor:pointer;' onclick='$markAsPassed' class='fas fa-marker'></i>
                                                        ";

                                                        // $remarks_url = "
                                                        //     <form method='POST'>

                                                        //         <input type='hidden' name='subject_id' value='$subject_id'>
                                                        //         <input type='hidden' name='student_subject_id' value='$t1_student_subject_id'>
                                                        //         <input type='hidden' name='course_id' value='$course_id'>
                                                        //         <input type='hidden' name='subject_title' value='$subject_title'>

                                                        //         <button name='passed_remark_btn' type='submit' class='btn btn-sm btn-primary' 
                                                        //             onclick=\"if(confirm('Mark as Passed?')){ /* Perform your action here */ }\">Remark</button>
                                                        //     </form>
                                                        // ";

                                                        // $remarks_url = "
                                                        //     <a href='shs_transferee_remarks.php?student_id=$student_id&s_id=$subject_id&page=view_details'>
                                                        //         <button type='button' class='btn btn-sm btn-primary'>Remark</button>
                                                        //     </a>
                                                        // ";
                                                    }
                                                }
                                               
                                                if($remarks_url == ""){
                                                    // $remarks_url = $credited;
                                                    $remarks_url = $studentSubject->CheckIfCredited($value['subject_title'],
                                                        $student_id);
                                                }
                                            
                                                echo '<tr class="text-center">'; 
                                                        echo '<td>'.$value['subject_code'].'</td>';
                                                        echo '<td>'.$value['subject_type'].'</td>';
                                                        echo '<td>'.$value['subject_title'].'</td>';
                                                        echo '<td>'.$value['unit'].'</td>';
                                                        // echo '<td>'.$schedule_day.'</td>';
                                                        // echo '<td>'.$schedule_time.'</td>';
                                                        // echo '<td>'.$room.'</td>';
                                                        // echo '<td>'.$section.'</td>';
                                                        echo '<td>'.$value['semester'].'</td>';
                                                        echo '<td>'.$value['course_level'].'</td>';
                                                        echo '<td>'.$remarks_url.'</td>';
                                                echo '</tr>';
                                            }     
                                        }
                                    ?>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- 1st Year Second Semester -->
                    <div class="row col-md-12 table-responsive"
                        style="margin-top:5%;">
                        <div class="table-responsive" style="margin-top:2%;"> 
                            
                            <?php 
                                if(count($subject->
                                    CheckStudentGradeLevelEnrolledSubjectSemesterv2($userLoggedInId,
                                    $SECOND_SEMESTER, $FIRST_YEAR)) > 0){

                                    
                                    $obj = $subject->CheckStudentGradeLevelEnrolledSubjectSemesterv2($userLoggedInId,
                                        $SECOND_SEMESTER, $FIRST_YEAR);

                                    $term = $obj['term'];
                                    $period = $obj['period'];
                                    $school_year_id = $obj['school_year_id'];
                                    $enrollment_course_id = $obj['course_id'];
                                    $enrollment_form_id = $obj['enrollment_form_id'];

                                    $section = new Section($con, $enrollment_course_id);
                                    $enrollment_course_level = $section->GetSectionGradeLevel();
                                    $enrollment_section_name = $enroll->GetStudentCourseNameByCourseId($enrollment_course_id);
                                

                                    echo "
                                    <h4 style='font-weight: 500;' class='text-primary text-center mb-3'>
                                        1st Year $enrollment_section_name $period Semester (SY $term) #$enrollment_form_id
                                    </h4>

                                    <form action='enrolled_subject_generate.php' method='POST'>

                                        <input type='hidden' value='$student_username' name='student_username'>
                                        <input type='hidden' value='$student_id' name='student_id'>
                                        <input type='hidden' value='$enrollment_course_id' name='enrollment_course_id'>
                                        <input type='hidden' value='$enrollment_course_level' name='student_course_level'>
                                        <input type='hidden' value='$period' name='current_school_year_period'>

                                        <button name='enrolled_subject_generate' type='submit'
                                            class='btn btn-outline-primary btn-sm'>
                                            Generate PDF
                                        </button>
                                    </form>
                                ";

                                }else{
                                    echo "
                                        <h3 class='text-center'>1st Year Second Semester</h3>	
                                    ";
                                }

                                // Section Based on the enrollment.
                                $enrollment_school_year = $studentEnroll->
                                    GetStudentSectionGradeElevenSchoolYear($username, 
                                        $userLoggedInId, $SECOND_YEAR, $FIRST_SEMESTER);
                            ?>	
                            <?php 

                                $doesFinishedFirstYearSubjects = $student->CheckTertiaryEligibleForMoveUp($student_id, $student_program_id,
                                    $FIRST_YEAR);

                                # ONLY FOR Tertiary every 2nd Semester BECAUSE OF MOVING UP.
                               
                                // Moving up student should be the end of Semester Period.
                                $checkSemesterSubjectPassed = $old_enroll->CheckCurrentSemesterAllPassed(
                                    $userLoggedInId, $student_course_id, $current_school_year_id);


                                    // echo $student_course_level;
                                if($doesFinishedFirstYearSubjects == true 
                                    && $student_course_level == $FIRST_YEAR
                                    && $student_section_level == $student_course_level
                                    && $current_school_year_period == $SECOND_SEMESTER){

                                    $wasSuccess = $old_enroll->TertiaryMoveUp($student_username, $student_course_level);

                                    if($wasSuccess){
                                        AdminUser::success("$student_username has been added by 1 year (Moved-Up)"
                                            ,"view_details.php?subject=show&tertiary=true&id=$userLoggedInId");

                                        exit();
                                    }

                                }

                            ?>

                            <table style="font-weight: 200;" class="table table-striped table-bordered table-hover "  style="font-size:12px" cellspacing="0"  > 
                                <thead>
                                    <tr class="text-center"> 
                                        <th class="text-success" rowspan="2">Subject</th>
                                        <th class="text-success" rowspan="2">Type</th>
                                        <th class="text-success" rowspan="2">Description</th>  
                                        <th class="text-success" rowspan="2">Unit</th>
                                    </tr>	
                                    <tr class="text-center"> 
                                        <th class="text-success">Semester</th>  
                                        <th class="text-success">Year Level</th>  
                                        <th class="text-success">Remarks</th>  
                                    </tr>
                                </thead> 	 
                                <tbody>
                                    <?php 
                                        // $listOfSubjects = $studentEnroll->GetStudentSubjectListWithGrades($username);
                                        $listOfSubjects = $studentEnroll
                                            ->GetStudentCurriculumBasedOnSemesterSubject($username,
                                        $userLoggedInId, $FIRST_YEAR, $SECOND_SEMESTER);

                                        if($listOfSubjects !== null){

                                            foreach ($listOfSubjects as $key => $value) {
                                                 
                                                $subject_id = $value['subject_id'];
                                                $course_id = $value['subject_id'];

                                                $course_level = $value['course_level'];
                                                $subject_title = $value['subject_title'];

                                                $remarks_url = "";

                                                $query_student_subject = $con->prepare("SELECT 

                                                    t1.subject_id, t1.student_subject_id as t1_student_subject_id,
                                                    
                                                    t2.student_subject_id as t2_student_subject_id,
                                                    t2.remarks

                                                    FROM student_subject as t1

                                                    LEFT JOIN student_subject_grade as t2 ON t2.student_subject_id = t1.student_subject_id

                                                    WHERE t1.subject_id=:subject_id
                                                    AND t1.student_id=:student_id
                                                    LIMIT 1");

                                                $query_student_subject->bindValue(":subject_id", $subject_id);
                                                $query_student_subject->bindValue(":student_id", $student_id);
                                                $query_student_subject->execute();

                                                $t1_student_subject_id = null;

                                                if($query_student_subject->rowCount() > 0){

                                                    $row = $query_student_subject->fetch(PDO::FETCH_ASSOC);

                                                    $student_subject_subject_id = $row['subject_id'];
                                                    $t1_student_subject_id = $row['t1_student_subject_id'];
                                                    $t2_student_subject_id = $row['t2_student_subject_id'];
                                                    $remarks = $row['remarks'];

                                                    if($t1_student_subject_id == $t2_student_subject_id){

                                                        $remarks_url = $remarks;
                                                    }
                     
                                                    else if($student_subject_subject_id == $subject_id
                                                        && $t1_student_subject_id != $t2_student_subject_id 
                                                        && $payment_status == "Enrolled"
                                                        ){
                                                            
                                                            
                                                        $markAsPassed = "MarkAsPassed($subject_id,
                                                            $student_id, \"Passed\",
                                                            $t1_student_subject_id, $course_id, \"$subject_title\")";

                                                        $remarks_url = "
                                                            <i style='color:blue; cursor:pointer;' onclick='$markAsPassed' class='fas fa-marker'></i>

                                                        ";

                                                        // $remarks_url = "
                                                        //     <a href='shs_transferee_remarks.php?student_id=$student_id&s_id=$subject_id&page=view_details'>
                                                        //         <button type='button' class='btn btn-sm btn-primary'>Remark</button>
                                                        //     </a>
                                                        // ";
                                                    }
                                                }

                                                if($remarks_url == ""){
                                                    // $remarks_url = $credited;
                                                    $remarks_url = $studentSubject->CheckIfCredited($value['subject_title'],
                                                        $student_id);
                                                }
                                            
                                                echo '<tr class="text-center">'; 
                                                        echo '<td>'.$value['subject_code'].'</td>';
                                                        echo '<td>'.$value['subject_type'].'</td>';
                                                        echo '<td>'.$value['subject_title'].'</td>';
                                                        echo '<td>'.$value['unit'].'</td>';
                                                        // echo '<td>'.$schedule_day.'</td>';
                                                        // echo '<td>'.$schedule_time.'</td>';
                                                        // echo '<td>'.$room.'</td>';
                                                        // echo '<td>'.$section.'</td>';
                                                        echo '<td>'.$value['semester'].'</td>';
                                                        echo '<td>'.$value['course_level'].'</td>';
                                                        echo '<td>'.$remarks_url.'</td>';
                                                echo '</tr>';
                                            }     
                                        }
                                    ?>
                                </tbody>
                            </table>
                        </div>
                    </div>


                    <!-- 2nd Year First Semester -->
                    <div class="row col-md-12 table-responsive"
                        style="margin-top:5%;">
                        <div class="table-responsive" style="margin-top:2%;"> 

                            <?php 
                                if(count($subject->
                                    CheckStudentGradeLevelEnrolledSubjectSemesterv2($userLoggedInId,
                                    $FIRST_SEMESTER, $SECOND_YEAR)) > 0){

                                    
                                    $obj = $subject->CheckStudentGradeLevelEnrolledSubjectSemesterv2($userLoggedInId,
                                        $FIRST_SEMESTER, $SECOND_YEAR);

                                    $term = $obj['term'];
                                    $period = $obj['period'];
                                    $school_year_id = $obj['school_year_id'];
                                    $enrollment_course_id = $obj['course_id'];
                                    $enrollment_form_id = $obj['enrollment_form_id'];

                                    $section = new Section($con, $enrollment_course_id);
                                    $enrollment_course_level = $section->GetSectionGradeLevel();
                                    $enrollment_section_name = $enroll->GetStudentCourseNameByCourseId($enrollment_course_id);
                                

                                    echo "
                                    <h4 style='font-weight: 500;' class='text-primary text-center mb-3'>
                                        2nd Year $enrollment_section_name $period Semester (SY $term) #$enrollment_form_id
                                    </h4>

                                    <form action='enrolled_subject_generate.php' method='POST'>

                                        <input type='hidden' value='$student_username' name='student_username'>
                                        <input type='hidden' value='$student_id' name='student_id'>
                                        <input type='hidden' value='$enrollment_course_id' name='enrollment_course_id'>
                                        <input type='hidden' value='$enrollment_course_level' name='student_course_level'>
                                        <input type='hidden' value='$period' name='current_school_year_period'>

                                        <button name='enrolled_subject_generate' type='submit'
                                            class='btn btn-outline-primary btn-sm'>
                                            Generate PDF
                                        </button>
                                    </form>
                                ";

                                }else{
                                    echo "
                                        <h3 class='text-center'>2nd Year First Semester</h3>	
                                    ";
                                }

                                // Section Based on the enrollment.
                                $enrollment_school_year = $studentEnroll->
                                    GetStudentSectionGradeElevenSchoolYear($username, 
                                        $userLoggedInId, $SECOND_YEAR, $FIRST_SEMESTER);
                            ?>	

                            <table style="font-weight: 200;" class="table table-striped table-bordered table-hover "  style="font-size:12px" cellspacing="0"  > 
                                <thead>
                                    <tr class="text-center"> 
                                        <th class="text-success" rowspan="2">Subject</th>
                                        <th class="text-success" rowspan="2">Type</th>
                                        <th class="text-success" rowspan="2">Description</th>  
                                        <th class="text-success" rowspan="2">Unit</th>
                                    </tr>	
                                    <tr class="text-center"> 
                                        <th class="text-success">Semester</th>  
                                        <th class="text-success">Year Level</th>  
                                        <th class="text-success">Remarks</th>  
                                    </tr>
                                </thead> 	 
                                <tbody>
                                    <?php 
                                        // $listOfSubjects = $studentEnroll->GetStudentSubjectListWithGrades($username);
                                        $listOfSubjects = $studentEnroll
                                            ->GetStudentCurriculumBasedOnSemesterSubject($username,
                                        $userLoggedInId, $SECOND_YEAR, $FIRST_SEMESTER);

                                        if($listOfSubjects !== null){

                                            foreach ($listOfSubjects as $key => $value) {
                                                 
                                                $subject_id = $value['subject_id'];
                                                $course_id = $value['subject_id'];

                                                $course_level = $value['course_level'];
                                                $subject_title = $value['subject_title'];

                                                

                                                $remarks_url = "";

                                                $query_student_subject = $con->prepare("SELECT 

                                                    t1.subject_id, t1.student_subject_id as t1_student_subject_id,
                                                    
                                                    t2.student_subject_id as t2_student_subject_id,
                                                    t2.remarks

                                                    FROM student_subject as t1

                                                    LEFT JOIN student_subject_grade as t2 ON t2.student_subject_id = t1.student_subject_id

                                                    WHERE t1.subject_id=:subject_id
                                                    AND t1.student_id=:student_id
                                                    LIMIT 1");

                                                $query_student_subject->bindValue(":subject_id", $subject_id);
                                                $query_student_subject->bindValue(":student_id", $student_id);
                                                $query_student_subject->execute();

                                                $t1_student_subject_id = null;

                                                if($query_student_subject->rowCount() > 0){

                                                    $row = $query_student_subject->fetch(PDO::FETCH_ASSOC);

                                                    $student_subject_subject_id = $row['subject_id'];
                                                    $t1_student_subject_id = $row['t1_student_subject_id'];
                                                    $t2_student_subject_id = $row['t2_student_subject_id'];
                                                    $remarks = $row['remarks'];

                                                    if($t1_student_subject_id == $t2_student_subject_id){

                                                        $remarks_url = $remarks;
                                                    }
                     
                                                    else if($student_subject_subject_id == $subject_id
                                                        && $t1_student_subject_id != $t2_student_subject_id 
                                                        && $payment_status == "Enrolled"
                                                        ){


                                                        $markAsPassed = "MarkAsPassed($subject_id,
                                                            $student_id, \"Passed\",
                                                            $t1_student_subject_id, $course_id, \"$subject_title\")";

                                                        $remarks_url = "
                                                            <i style='color:blue; cursor:pointer;' onclick='$markAsPassed' class='fas fa-marker'></i>
                                                        ";

                                                        // $remarks_url = "
                                                        //     <a href='shs_transferee_remarks.php?student_id=$student_id&s_id=$subject_id&page=view_details'>
                                                        //         <button type='button' class='btn btn-sm btn-primary'>Remark</button>
                                                        //     </a>
                                                        // ";
                                                    }
                                                }

                                                if($remarks_url == ""){
                                                    // $remarks_url = $credited;
                                                    $remarks_url = $studentSubject->CheckIfCredited($value['subject_title'],
                                                        $student_id);
                                                }
                                            
                                                echo '<tr class="text-center">'; 
                                                        echo '<td>'.$value['subject_code'].'</td>';
                                                        echo '<td>'.$value['subject_type'].'</td>';
                                                        echo '<td>'.$value['subject_title'].'</td>';
                                                        echo '<td>'.$value['unit'].'</td>';
                                                        // echo '<td>'.$schedule_day.'</td>';
                                                        // echo '<td>'.$schedule_time.'</td>';
                                                        // echo '<td>'.$room.'</td>';
                                                        // echo '<td>'.$section.'</td>';
                                                        echo '<td>'.$value['semester'].'</td>';
                                                        echo '<td>'.$value['course_level'].'</td>';
                                                        echo '<td>'.$remarks_url.'</td>';
                                                echo '</tr>';
                                            }     
                                        }
                                    ?>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- 2nd Year Second Semester -->
                    <div class="row col-md-12 table-responsive"
                        style="margin-top:5%;">
                        <div class="table-responsive" style="margin-top:2%;"> 
                            
                            <?php 
                                if(count($subject->
                                    CheckStudentGradeLevelEnrolledSubjectSemesterv2($userLoggedInId,
                                    $SECOND_SEMESTER, $SECOND_YEAR)) > 0){

                                    
                                    $obj = $subject->CheckStudentGradeLevelEnrolledSubjectSemesterv2($userLoggedInId,
                                        $SECOND_SEMESTER, $SECOND_YEAR);

                                    $term = $obj['term'];
                                    $period = $obj['period'];
                                    $school_year_id = $obj['school_year_id'];
                                    $enrollment_course_id = $obj['course_id'];
                                    $enrollment_form_id = $obj['enrollment_form_id'];

                                    $section = new Section($con, $enrollment_course_id);
                                    $enrollment_course_level = $section->GetSectionGradeLevel();
                                    $enrollment_section_name = $enroll->GetStudentCourseNameByCourseId($enrollment_course_id);
                                

                                    echo "
                                    <h4 style='font-weight: 500;' class='text-primary text-center mb-3'>
                                        2nd Year $enrollment_section_name $period Semester (SY $term) #$enrollment_form_id
                                    </h4>

                                    <form action='enrolled_subject_generate.php' method='POST'>

                                        <input type='hidden' value='$student_username' name='student_username'>
                                        <input type='hidden' value='$student_id' name='student_id'>
                                        <input type='hidden' value='$enrollment_course_id' name='enrollment_course_id'>
                                        <input type='hidden' value='$enrollment_course_level' name='student_course_level'>
                                        <input type='hidden' value='$period' name='current_school_year_period'>

                                        <button name='enrolled_subject_generate' type='submit'
                                            class='btn btn-outline-primary btn-sm'>
                                            Generate PDF
                                        </button>
                                    </form>
                                ";

                                }else{
                                    echo "
                                        <h3 class='text-center'>2nd Year Second Semester</h3>	
                                    ";
                                }
                            ?>	
                            <?php 

                                # ONLY FOR Tertiary every 2nd Semester BECAUSE OF MOVING UP.
                                // Moving up student should be the end of Semester Period.


                                $doesFinishedFirstYearSubjects = $student->CheckTertiaryEligibleForMoveUp($student_id, $student_program_id,
                                    $SECOND_YEAR);

                                $checkSemesterSubjectPassed = $old_enroll->CheckCurrentSemesterAllPassed(
                                    $userLoggedInId, $student_course_id, $current_school_year_id);


                                if($doesFinishedFirstYearSubjects == true 
                                    && $student_course_level == $SECOND_YEAR
                                    && $student_section_level == $student_course_level
                                    && $current_school_year_period == $SECOND_SEMESTER){

                                    $wasSuccess = $old_enroll->TertiaryMoveUp($student_username, $student_course_level);

                                    if($wasSuccess){

                                        AdminUser::success("$student_username has been added by 1 year (Moved-Up)"
                                            ,"view_details.php?subject=show&tertiary=true&id=$userLoggedInId");

                                        exit();
                                    }
                                }

                            ?>

                            <table style="font-weight: 200;" class="table table-striped table-bordered table-hover "  style="font-size:12px" cellspacing="0"  > 
                                <thead>
                                    <tr class="text-center"> 
                                        <th class="text-success" rowspan="2">Subject</th>
                                        <th class="text-success" rowspan="2">Type</th>
                                        <th class="text-success" rowspan="2">Description</th>  
                                        <th class="text-success" rowspan="2">Unit</th>
                                    </tr>	
                                    <tr class="text-center"> 
                                        <th class="text-success">Semester</th>  
                                        <th class="text-success">Year Level</th>  
                                        <th class="text-success">Remarks</th>  
                                    </tr>
                                </thead> 	 
                                <tbody>
                                    <?php 
                                        // $listOfSubjects = $studentEnroll->GetStudentSubjectListWithGrades($username);
                                        $listOfSubjects = $studentEnroll
                                            ->GetStudentCurriculumBasedOnSemesterSubject($username,
                                        $userLoggedInId, $SECOND_YEAR, $SECOND_SEMESTER);

                                        if($listOfSubjects !== null){

                                            foreach ($listOfSubjects as $key => $value) {
                                                 
                                                $subject_id = $value['subject_id'];
                                                $course_id = $value['course_id'];

                                                $subject_title = $value['subject_title'];
                                                $course_level = $value['course_level'];

                                                $remarks_url = "";

                                                $query_student_subject = $con->prepare("SELECT 

                                                    t1.subject_id, t1.student_subject_id as t1_student_subject_id,
                                                    
                                                    t2.student_subject_id as t2_student_subject_id,
                                                    t2.remarks

                                                    FROM student_subject as t1

                                                    LEFT JOIN student_subject_grade as t2 ON t2.student_subject_id = t1.student_subject_id

                                                    WHERE t1.subject_id=:subject_id
                                                    AND t1.student_id=:student_id
                                                    LIMIT 1");

                                                $query_student_subject->bindValue(":subject_id", $subject_id);
                                                $query_student_subject->bindValue(":student_id", $student_id);
                                                $query_student_subject->execute();

                                                $t1_student_subject_id = null;

                                                if($query_student_subject->rowCount() > 0){

                                                    $row = $query_student_subject->fetch(PDO::FETCH_ASSOC);

                                                    $student_subject_subject_id = $row['subject_id'];
                                                    $t1_student_subject_id = $row['t1_student_subject_id'];
                                                    $t2_student_subject_id = $row['t2_student_subject_id'];
                                                    $remarks = $row['remarks'];

                                                    if($t1_student_subject_id == $t2_student_subject_id){

                                                        $remarks_url = $remarks;
                                                    }
                     
                                                    else if($student_subject_subject_id == $subject_id
                                                        && $t1_student_subject_id != $t2_student_subject_id 
                                                        && $payment_status == "Enrolled"
                                                        ){


                                                        $markAsPassed = "MarkAsPassed($subject_id,
                                                            $student_id, \"Passed\",
                                                            $t1_student_subject_id, $course_id, \"$subject_title\")";

                                                        $remarks_url = "
                                                            <i style='color:blue; cursor:pointer;' onclick='$markAsPassed' class='fas fa-marker'></i>
                                                        ";

                                                        // $remarks_url = "
                                                        //     <a href='shs_transferee_remarks.php?student_id=$student_id&s_id=$subject_id&page=view_details'>
                                                        //         <button type='button' class='btn btn-sm btn-primary'>Remark</button>
                                                        //     </a>
                                                        // ";

                                                    }
                                                }

                                                if($remarks_url == ""){
                                                    // $remarks_url = $credited;
                                                    $remarks_url = $studentSubject->CheckIfCredited($value['subject_title'],
                                                        $student_id);
                                                }
                                            
                                                echo '<tr class="text-center">'; 
                                                        echo '<td>'.$value['subject_code'].'</td>';
                                                        echo '<td>'.$value['subject_type'].'</td>';
                                                        echo '<td>'.$value['subject_title'].'</td>';
                                                        echo '<td>'.$value['unit'].'</td>';
                                                        // echo '<td>'.$schedule_day.'</td>';
                                                        // echo '<td>'.$schedule_time.'</td>';
                                                        // echo '<td>'.$room.'</td>';
                                                        // echo '<td>'.$section.'</td>';
                                                        echo '<td>'.$value['semester'].'</td>';
                                                        echo '<td>'.$value['course_level'].'</td>';
                                                        echo '<td>'.$remarks_url.'</td>';
                                                echo '</tr>';
                                            }     
                                        }
                                    ?>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- 3rd Year First Semester -->
                    <div class="row col-md-12 table-responsive"
                        style="margin-top:5%;">
                        <div class="table-responsive" style="margin-top:2%;"> 
                            <?php 
                                if(count($subject->
                                    CheckStudentGradeLevelEnrolledSubjectSemesterv2($userLoggedInId,
                                    $FIRST_SEMESTER, $THIRD_YEAR)) > 0){

                                    
                                    $obj = $subject->CheckStudentGradeLevelEnrolledSubjectSemesterv2($userLoggedInId,
                                        $FIRST_SEMESTER, $THIRD_YEAR);

                                    $term = $obj['term'];
                                    $period = $obj['period'];
                                    $school_year_id = $obj['school_year_id'];
                                    $enrollment_course_id = $obj['course_id'];
                                    $enrollment_form_id = $obj['enrollment_form_id'];

                                    $section = new Section($con, $enrollment_course_id);
                                    $enrollment_course_level = $section->GetSectionGradeLevel();
                                    $enrollment_section_name = $enroll->GetStudentCourseNameByCourseId($enrollment_course_id);
                                

                                    echo "
                                    <h4 style='font-weight: 500;' class='text-primary text-center mb-3'>
                                        3rd Year $enrollment_section_name $period Semester (SY $term) #$enrollment_form_id
                                    </h4>

                                    <form action='enrolled_subject_generate.php' method='POST'>

                                        <input type='hidden' value='$student_username' name='student_username'>
                                        <input type='hidden' value='$student_id' name='student_id'>
                                        <input type='hidden' value='$enrollment_course_id' name='enrollment_course_id'>
                                        <input type='hidden' value='$enrollment_course_level' name='student_course_level'>
                                        <input type='hidden' value='$period' name='current_school_year_period'>

                                        <button name='enrolled_subject_generate' type='submit'
                                            class='btn btn-outline-primary btn-sm'>
                                            Generate PDF
                                        </button>
                                    </form>
                                ";

                                }else{
                                    echo "
                                        <h3 class='text-center'>3rd Year First Semester</h3>	
                                    ";
                                }

                                // Section Based on the enrollment.
                                $enrollment_school_year = $studentEnroll->
                                    GetStudentSectionGradeElevenSchoolYear($username, 
                                        $userLoggedInId, $THIRD_YEAR, $FIRST_SEMESTER);
                            ?>	

                            <table style="font-weight: 200;" class="table table-striped table-bordered table-hover "  style="font-size:12px" cellspacing="0"  > 
                                <thead>
                                    <tr class="text-center"> 
                                        <th class="text-success" rowspan="2">Subject</th>
                                        <th class="text-success" rowspan="2">Type</th>
                                        <th class="text-success" rowspan="2">Description</th>  
                                        <th class="text-success" rowspan="2">Unit</th>
                                    </tr>	
                                    <tr class="text-center"> 
                                        <th class="text-success">Semester</th>  
                                        <th class="text-success">Year Level</th>  
                                        <th class="text-success">Remarks</th>  
                                    </tr>
                                </thead> 	 
                                <tbody>
                                    <?php 
                                        // $listOfSubjects = $studentEnroll->GetStudentSubjectListWithGrades($username);
                                        $listOfSubjects = $studentEnroll
                                            ->GetStudentCurriculumBasedOnSemesterSubject($username,
                                        $userLoggedInId, $THIRD_YEAR, $FIRST_SEMESTER);

                                        if($listOfSubjects !== null){

                                            foreach ($listOfSubjects as $key => $value) {
                                                 
                                                $subject_id = $value['subject_id'];
                                                $course_id = $value['course_id'];

                                                $subject_title = $value['subject_title'];
                                                $course_level = $value['course_level'];

                                                $remarks_url = "";

                                                $query_student_subject = $con->prepare("SELECT 

                                                    t1.subject_id, t1.student_subject_id as t1_student_subject_id,
                                                    
                                                    t2.student_subject_id as t2_student_subject_id,
                                                    t2.remarks

                                                    FROM student_subject as t1

                                                    LEFT JOIN student_subject_grade as t2 ON t2.student_subject_id = t1.student_subject_id

                                                    WHERE t1.subject_id=:subject_id
                                                    AND t1.student_id=:student_id
                                                    LIMIT 1");

                                                $query_student_subject->bindValue(":subject_id", $subject_id);
                                                $query_student_subject->bindValue(":student_id", $student_id);
                                                $query_student_subject->execute();

                                                $t1_student_subject_id = null;

                                                if($query_student_subject->rowCount() > 0){

                                                    $row = $query_student_subject->fetch(PDO::FETCH_ASSOC);

                                                    $student_subject_subject_id = $row['subject_id'];
                                                    $t1_student_subject_id = $row['t1_student_subject_id'];
                                                    $t2_student_subject_id = $row['t2_student_subject_id'];
                                                    $remarks = $row['remarks'];

                                                    if($t1_student_subject_id == $t2_student_subject_id){

                                                        $remarks_url = $remarks;
                                                    }
                     
                                                    else if($student_subject_subject_id == $subject_id
                                                        && $t1_student_subject_id != $t2_student_subject_id 
                                                        && $payment_status == "Enrolled"
                                                        ){

                                                        $markAsPassed = "MarkAsPassed($subject_id,
                                                            $student_id, \"Passed\",
                                                            $t1_student_subject_id, $course_id, \"$subject_title\")";

                                                        $remarks_url = "
                                                            <i style='color:blue; cursor:pointer;' onclick='$markAsPassed' class='fas fa-marker'></i>
                                                        ";

                                                        // $remarks_url = "
                                                        //     <a href='shs_transferee_remarks.php?student_id=$student_id&s_id=$subject_id&page=view_details'>
                                                        //         <button type='button' class='btn btn-sm btn-primary'>Remark</button>
                                                        //     </a>
                                                        // ";
                                                    }
                                                }

                                                if($remarks_url == ""){
                                                    // $remarks_url = $credited;
                                                    $remarks_url = $studentSubject->CheckIfCredited($value['subject_title'],
                                                        $student_id);
                                                }
                                            
                                                echo '<tr class="text-center">'; 
                                                        echo '<td>'.$value['subject_code'].'</td>';
                                                        echo '<td>'.$value['subject_type'].'</td>';
                                                        echo '<td>'.$value['subject_title'].'</td>';
                                                        echo '<td>'.$value['unit'].'</td>';
                                                        // echo '<td>'.$schedule_day.'</td>';
                                                        // echo '<td>'.$schedule_time.'</td>';
                                                        // echo '<td>'.$room.'</td>';
                                                        // echo '<td>'.$section.'</td>';
                                                        echo '<td>'.$value['semester'].'</td>';
                                                        echo '<td>'.$value['course_level'].'</td>';
                                                        echo '<td>'.$remarks_url.'</td>';
                                                echo '</tr>';
                                            }     
                                        }
                                    ?>
                                </tbody>
                            </table>
                        </div>
                    </div>


                    <!-- 3rd Year Second Semester -->
                    <div class="row col-md-12 table-responsive"
                        style="margin-top:5%;">
                        <div class="table-responsive" style="margin-top:2%;"> 
                            <?php 
                                if(count($subject->
                                    CheckStudentGradeLevelEnrolledSubjectSemesterv2($userLoggedInId,
                                        $SECOND_SEMESTER, $THIRD_YEAR)) > 0){

                                    $obj = $subject->CheckStudentGradeLevelEnrolledSubjectSemesterv2($userLoggedInId,
                                        $SECOND_SEMESTER, $THIRD_YEAR);

                                    $term = $obj['term'];
                                    $period = $obj['period'];
                                    $school_year_id = $obj['school_year_id'];
                                    $enrollment_course_id = $obj['course_id'];
                                    $enrollment_form_id = $obj['enrollment_form_id'];

                                    $section = new Section($con, $enrollment_course_id);
                                    $enrollment_course_level = $section->GetSectionGradeLevel();
                                    $enrollment_section_name = $enroll->GetStudentCourseNameByCourseId($enrollment_course_id);
                                

                                    echo "
                                    <h4 style='font-weight: 500;' class='text-primary text-center mb-3'>
                                        3rd Year $enrollment_section_name $period Semester (SY $term) #$enrollment_form_id
                                    </h4>

                                    <form action='enrolled_subject_generate.php' method='POST'>

                                        <input type='hidden' value='$student_username' name='student_username'>
                                        <input type='hidden' value='$student_id' name='student_id'>
                                        <input type='hidden' value='$enrollment_course_id' name='enrollment_course_id'>
                                        <input type='hidden' value='$enrollment_course_level' name='student_course_level'>
                                        <input type='hidden' value='$period' name='current_school_year_period'>

                                        <button name='enrolled_subject_generate' type='submit'
                                            class='btn btn-outline-primary btn-sm'>
                                            Generate PDF
                                        </button>
                                    </form>
                                ";

                                }else{
                                    echo "
                                        <h3 class='text-center'>3rd Year Second Semester</h3>	
                                    ";
                                }
                            ?>	
                            <?php 

                                # ONLY FOR Tertiary every 2nd Semester BECAUSE OF MOVING UP.
                                // Moving up student should be the end of Semester Period.

                                $checkSemesterSubjectPassed = $old_enroll->CheckCurrentSemesterAllPassed(
                                    $userLoggedInId, $student_course_id, $current_school_year_id);

                                $doesFinishedFirstYearSubjects = $student->CheckTertiaryEligibleForMoveUp($student_id, $student_program_id,
                                    $THIRD_YEAR);

                                if($doesFinishedFirstYearSubjects == true 
                                    && $student_course_level == $THIRD_YEAR
                                    && $student_section_level == $student_course_level
                                    && $current_school_year_period == $SECOND_SEMESTER
                                    ){

                                    $wasSuccess = $old_enroll->TertiaryMoveUp($student_username, $student_course_level);

                                    if($wasSuccess){

                                        AdminUser::success("$student_username has been added by 1 year (Moved-Up)"
                                            ,"view_details.php?subject=show&tertiary=true&id=$userLoggedInId");

                                        exit();
                                    }
                                }

                            ?>

                            <table style="font-weight: 200;" class="table table-striped table-bordered table-hover "  style="font-size:12px" cellspacing="0"  > 
                                <thead>
                                    <tr class="text-center"> 
                                        <th class="text-success" rowspan="2">Subject</th>
                                        <th class="text-success" rowspan="2">Type</th>
                                        <th class="text-success" rowspan="2">Description</th>  
                                        <th class="text-success" rowspan="2">Unit</th>
                                    </tr>	
                                    <tr class="text-center"> 
                                        <th class="text-success">Semester</th>  
                                        <th class="text-success">Year Level</th>  
                                        <th class="text-success">Remarks</th>  
                                    </tr>
                                </thead> 	 
                                <tbody>
                                    <?php 
                                        // $listOfSubjects = $studentEnroll->GetStudentSubjectListWithGrades($username);
                                        $listOfSubjects = $studentEnroll
                                            ->GetStudentCurriculumBasedOnSemesterSubject($username,
                                        $userLoggedInId, $THIRD_YEAR, $SECOND_SEMESTER);

                                        if($listOfSubjects !== null){

                                            foreach ($listOfSubjects as $key => $value) {
                                                 
                                                $subject_id = $value['subject_id'];
                                                $course_id = $value['course_id'];

                                                $subject_title = $value['subject_title'];
                                                $course_level = $value['course_level'];

                                                $remarks_url = "";

                                                $query_student_subject = $con->prepare("SELECT 

                                                    t1.subject_id, t1.student_subject_id as t1_student_subject_id,
                                                    
                                                    t2.student_subject_id as t2_student_subject_id,
                                                    t2.remarks

                                                    FROM student_subject as t1

                                                    LEFT JOIN student_subject_grade as t2 ON t2.student_subject_id = t1.student_subject_id

                                                    WHERE t1.subject_id=:subject_id
                                                    AND t1.student_id=:student_id
                                                    LIMIT 1");

                                                $query_student_subject->bindValue(":subject_id", $subject_id);
                                                $query_student_subject->bindValue(":student_id", $student_id);
                                                $query_student_subject->execute();

                                                $t1_student_subject_id = null;

                                                if($query_student_subject->rowCount() > 0){

                                                    $row = $query_student_subject->fetch(PDO::FETCH_ASSOC);

                                                    $student_subject_subject_id = $row['subject_id'];
                                                    $t1_student_subject_id = $row['t1_student_subject_id'];
                                                    $t2_student_subject_id = $row['t2_student_subject_id'];
                                                    $remarks = $row['remarks'];

                                                    if($t1_student_subject_id == $t2_student_subject_id){

                                                        $remarks_url = $remarks;
                                                    }
                     
                                                    else if($student_subject_subject_id == $subject_id
                                                        && $t1_student_subject_id != $t2_student_subject_id 
                                                        && $payment_status == "Enrolled"
                                                        ){


                                                        $markAsPassed = "MarkAsPassed($subject_id,
                                                            $student_id, \"Passed\",
                                                            $t1_student_subject_id, $course_id, \"$subject_title\")";

                                                        $remarks_url = "
                                                            <i style='color:blue; cursor:pointer;' onclick='$markAsPassed' class='fas fa-marker'></i>
                                                        ";

                                                        // $remarks_url = "
                                                        //     <a href='shs_transferee_remarks.php?student_id=$student_id&s_id=$subject_id&page=view_details'>
                                                        //         <button type='button' class='btn btn-sm btn-primary'>Remark</button>
                                                        //     </a>
                                                        // ";
                                                    }
                                                }

                                                if($remarks_url == ""){
                                                    // $remarks_url = $credited;
                                                    $remarks_url = $studentSubject->CheckIfCredited($value['subject_title'],
                                                        $student_id);
                                                }
                                            
                                                echo '<tr class="text-center">'; 
                                                        echo '<td>'.$value['subject_code'].'</td>';
                                                        echo '<td>'.$value['subject_type'].'</td>';
                                                        echo '<td>'.$value['subject_title'].'</td>';
                                                        echo '<td>'.$value['unit'].'</td>';
                                                     
                                                        echo '<td>'.$value['semester'].'</td>';
                                                        echo '<td>'.$value['course_level'].'</td>';
                                                        echo '<td>'.$remarks_url.'</td>';
                                                echo '</tr>';
                                            }     
                                        }
                                    ?>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- 4th Year First Semester -->
                    <div class="row col-md-12 table-responsive"
                        style="margin-top:5%;">
                        <div class="table-responsive" style="margin-top:2%;"> 
                            <?php 
                                if(count($subject->
                                    CheckStudentGradeLevelEnrolledSubjectSemesterv2($userLoggedInId,
                                    $FIRST_SEMESTER, $FOURTH_YEAR)) > 0){
                                    
                                    $obj = $subject->CheckStudentGradeLevelEnrolledSubjectSemesterv2($userLoggedInId,
                                        $FIRST_SEMESTER, $FOURTH_YEAR);

                                    $term = $obj['term'];
                                    $period = $obj['period'];
                                    $school_year_id = $obj['school_year_id'];
                                    $enrollment_course_id = $obj['course_id'];
                                    $enrollment_form_id = $obj['enrollment_form_id'];

                                    $section = new Section($con, $enrollment_course_id);
                                    $enrollment_course_level = $section->GetSectionGradeLevel();
                                    $enrollment_section_name = $enroll->GetStudentCourseNameByCourseId($enrollment_course_id);
                                

                                    echo "
                                    <h4 style='font-weight: 500;' class='text-primary text-center mb-3'>
                                        4th Year $enrollment_section_name $period Semester (SY $term) #$enrollment_form_id
                                    </h4>

                                    <form action='enrolled_subject_generate.php' method='POST'>

                                        <input type='hidden' value='$student_username' name='student_username'>
                                        <input type='hidden' value='$student_id' name='student_id'>
                                        <input type='hidden' value='$enrollment_course_id' name='enrollment_course_id'>
                                        <input type='hidden' value='$enrollment_course_level' name='student_course_level'>
                                        <input type='hidden' value='$period' name='current_school_year_period'>

                                        <button name='enrolled_subject_generate' type='submit'
                                            class='btn btn-outline-primary btn-sm'>
                                            Generate PDF
                                        </button>
                                    </form>
                                ";

                                }else{
                                    echo "
                                        <h3 class='text-center'>4th Year First Semester</h3>	
                                    ";
                                }
                            ?>	

                            <table style="font-weight: 200;" class="table table-striped table-bordered table-hover "  style="font-size:12px" cellspacing="0"  > 
                                <thead>
                                    <tr class="text-center"> 
                                        <th class="text-success" rowspan="2">Subject</th>
                                        <th class="text-success" rowspan="2">Type</th>
                                        <th class="text-success" rowspan="2">Description</th>  
                                        <th class="text-success" rowspan="2">Unit</th>
                                    </tr>	
                                    <tr class="text-center"> 
                                        <th class="text-success">Semester</th>  
                                        <th class="text-success">Year Level</th>  
                                        <th class="text-success">Remarks</th>  
                                    </tr>
                                </thead> 	 
                                <tbody>
                                    <?php 
                                        // $listOfSubjects = $studentEnroll->GetStudentSubjectListWithGrades($username);
                                        $listOfSubjects = $studentEnroll
                                            ->GetStudentCurriculumBasedOnSemesterSubject($username,
                                        $userLoggedInId, $FOURTH_YEAR, $FIRST_SEMESTER);

                                        if($listOfSubjects !== null){

                                            foreach ($listOfSubjects as $key => $value) {
                                                 
                                                $subject_id = $value['subject_id'];
                                                $course_id = $value['course_id'];

                                                $subject_title = $value['subject_title'];
                                                $course_level = $value['course_level'];

                                                $remarks_url = "";

                                                $query_student_subject = $con->prepare("SELECT 

                                                    t1.subject_id, t1.student_subject_id as t1_student_subject_id,
                                                    
                                                    t2.student_subject_id as t2_student_subject_id,
                                                    t2.remarks

                                                    FROM student_subject as t1

                                                    LEFT JOIN student_subject_grade as t2 ON t2.student_subject_id = t1.student_subject_id

                                                    WHERE t1.subject_id=:subject_id
                                                    AND t1.student_id=:student_id
                                                    LIMIT 1");

                                                $query_student_subject->bindValue(":subject_id", $subject_id);
                                                $query_student_subject->bindValue(":student_id", $student_id);
                                                $query_student_subject->execute();

                                                $t1_student_subject_id = null;

                                                if($query_student_subject->rowCount() > 0){

                                                    $row = $query_student_subject->fetch(PDO::FETCH_ASSOC);

                                                    $student_subject_subject_id = $row['subject_id'];
                                                    $t1_student_subject_id = $row['t1_student_subject_id'];
                                                    $t2_student_subject_id = $row['t2_student_subject_id'];
                                                    $remarks = $row['remarks'];

                                                    if($t1_student_subject_id == $t2_student_subject_id){

                                                        $remarks_url = $remarks;
                                                    }
                     
                                                    else if($student_subject_subject_id == $subject_id
                                                        && $t1_student_subject_id != $t2_student_subject_id 
                                                        && $payment_status == "Enrolled"
                                                        ){

                                                        $markAsPassed = "MarkAsPassed($subject_id,
                                                            $student_id, \"Passed\",
                                                            $t1_student_subject_id, $course_id, \"$subject_title\")";

                                                        $remarks_url = "
                                                            <i style='color:blue; cursor:pointer;' onclick='$markAsPassed' class='fas fa-marker'></i>
                                                        ";

                                                        // $remarks_url = "
                                                        //     <a href='shs_transferee_remarks.php?student_id=$student_id&s_id=$subject_id&page=view_details'>
                                                        //         <button type='button' class='btn btn-sm btn-primary'>Remark</button>
                                                        //     </a>
                                                        // ";
                                                    }
                                                }

                                                if($remarks_url == ""){
                                                    // $remarks_url = $credited;
                                                    $remarks_url = $studentSubject->CheckIfCredited($value['subject_title'],
                                                        $student_id);
                                                }
                                            
                                                echo '<tr class="text-center">'; 
                                                        echo '<td>'.$value['subject_code'].'</td>';
                                                        echo '<td>'.$value['subject_type'].'</td>';
                                                        echo '<td>'.$value['subject_title'].'</td>';
                                                        echo '<td>'.$value['unit'].'</td>';
                                                        // echo '<td>'.$schedule_day.'</td>';
                                                        // echo '<td>'.$schedule_time.'</td>';
                                                        // echo '<td>'.$room.'</td>';
                                                        // echo '<td>'.$section.'</td>';
                                                        echo '<td>'.$value['semester'].'</td>';
                                                        echo '<td>'.$value['course_level'].'</td>';
                                                        echo '<td>'.$remarks_url.'</td>';
                                                echo '</tr>';
                                            }     
                                        }
                                    ?>
                                </tbody>
                            </table>
                        </div>
                    </div>


                    <!-- 4th Year Second Semester -->
                    <div class="row col-md-12 table-responsive"
                        style="margin-top:5%;">
                        <div class="table-responsive" style="margin-top:2%;"> 
                            <?php 
                                if(count($subject->
                                    CheckStudentGradeLevelEnrolledSubjectSemesterv2($userLoggedInId,
                                        $SECOND_SEMESTER, $FOURTH_YEAR)) > 0){

                                    $obj = $subject->CheckStudentGradeLevelEnrolledSubjectSemesterv2($userLoggedInId,
                                        $SECOND_SEMESTER, $FOURTH_YEAR);

                                    $term = $obj['term'];
                                    $period = $obj['period'];
                                    $school_year_id = $obj['school_year_id'];
                                    $enrollment_course_id = $obj['course_id'];
                                    $enrollment_form_id = $obj['enrollment_form_id'];

                                    $section = new Section($con, $enrollment_course_id);
                                    $enrollment_course_level = $section->GetSectionGradeLevel();
                                    $enrollment_section_name = $enroll->GetStudentCourseNameByCourseId($enrollment_course_id);
                                

                                    echo "
                                    <h4 style='font-weight: 500;' class='text-primary text-center mb-3'>
                                        4th Year $enrollment_section_name $period Semester (SY $term) #$enrollment_form_id
                                    </h4>

                                    <form action='enrolled_subject_generate.php' method='POST'>

                                        <input type='hidden' value='$student_username' name='student_username'>
                                        <input type='hidden' value='$student_id' name='student_id'>
                                        <input type='hidden' value='$enrollment_course_id' name='enrollment_course_id'>
                                        <input type='hidden' value='$enrollment_course_level' name='student_course_level'>
                                        <input type='hidden' value='$period' name='current_school_year_period'>

                                        <button name='enrolled_subject_generate' type='submit'
                                            class='btn btn-outline-primary btn-sm'>
                                            Generate PDF
                                        </button>
                                    </form>
                                ";

                                }else{
                                    echo "
                                        <h3 class='text-center'>4th Year Second Semester</h3>	
                                    ";
                                }
                            ?>	
                            <?php 

                                # ONLY FOR Tertiary every 2nd Semester BECAUSE OF MOVING UP.
                                // Moving up student should be the end of Semester Period.

                                $checkSemesterSubjectPassed = $old_enroll->CheckCurrentSemesterAllPassed(
                                    $userLoggedInId, $student_course_id, $current_school_year_id);
 
                                $doesFinishedFirstYearSubjects = $student->CheckTertiaryEligibleForMoveUp($student_id, $student_program_id,
                                    $FOURTH_YEAR);

                                $isCandidateForGraduation = $student->CheckShsStudentGraduateCandidatev2($student_id, $student_program_id);

                                if($doesFinishedFirstYearSubjects && 
                                    $isCandidateForGraduation && 
                                    $is_student_graduated == 0 && 
                                    $student_is_active == 1 &&
                                    $checkIfTertiary == 1

                                    ){

                                    echo "candidate";
                                       $wasSuccessGraduate = $student->MarkAsGraduate($student_id);

                                       if($wasSuccessGraduate){
                                            AdminUser::success("Tertiary Student: $student_id is now graduated in the system.", "");
                                       }

                                } else{
                                    echo "not candidate";
                                }

                            ?>

                            <table style="font-weight: 200;" class="table table-striped table-bordered table-hover "  style="font-size:12px" cellspacing="0"  > 
                                <thead>
                                    <tr class="text-center"> 
                                        <th class="text-success" rowspan="2">Subject</th>
                                        <th class="text-success" rowspan="2">Type</th>
                                        <th class="text-success" rowspan="2">Description</th>  
                                        <th class="text-success" rowspan="2">Unit</th>
                                    </tr>	
                                    <tr class="text-center"> 
                                        <th class="text-success">Semester</th>  
                                        <th class="text-success">Year Level</th>  
                                        <th class="text-success">Remarks</th>  
                                    </tr>
                                </thead> 	 
                                <tbody>
                                    <?php 
                                        // $listOfSubjects = $studentEnroll->GetStudentSubjectListWithGrades($username);
                                        $listOfSubjects = $studentEnroll
                                            ->GetStudentCurriculumBasedOnSemesterSubject($username,
                                        $userLoggedInId, $FOURTH_YEAR, $SECOND_SEMESTER);

                                        if($listOfSubjects !== null){

                                            foreach ($listOfSubjects as $key => $value) {
                                                 
                                                $subject_id = $value['subject_id'];
                                                $course_id = $value['course_id'];

                                                $subject_title = $value['subject_title'];
                                                $course_level = $value['course_level'];

                                                $remarks_url = "";

                                                $query_student_subject = $con->prepare("SELECT 

                                                    t1.subject_id, t1.student_subject_id as t1_student_subject_id,
                                                    
                                                    t2.student_subject_id as t2_student_subject_id,
                                                    t2.remarks

                                                    FROM student_subject as t1

                                                    LEFT JOIN student_subject_grade as t2 ON t2.student_subject_id = t1.student_subject_id

                                                    WHERE t1.subject_id=:subject_id
                                                    AND t1.student_id=:student_id
                                                    LIMIT 1");

                                                $query_student_subject->bindValue(":subject_id", $subject_id);
                                                $query_student_subject->bindValue(":student_id", $student_id);
                                                $query_student_subject->execute();

                                                $t1_student_subject_id = null;

                                                if($query_student_subject->rowCount() > 0){

                                                    $row = $query_student_subject->fetch(PDO::FETCH_ASSOC);

                                                    $student_subject_subject_id = $row['subject_id'];
                                                    $t1_student_subject_id = $row['t1_student_subject_id'];
                                                    $t2_student_subject_id = $row['t2_student_subject_id'];
                                                    $remarks = $row['remarks'];

                                                    if($t1_student_subject_id == $t2_student_subject_id){

                                                        $remarks_url = $remarks;
                                                    }
                     
                                                    else if($student_subject_subject_id == $subject_id
                                                        && $t1_student_subject_id != $t2_student_subject_id 
                                                        && $payment_status == "Enrolled"
                                                        ){

                                                        $markAsPassed = "MarkAsPassed($subject_id,
                                                            $student_id, \"Passed\",
                                                            $t1_student_subject_id, $course_id, \"$subject_title\")";

                                                        $remarks_url = "
                                                            <i style='color:blue; cursor:pointer;' onclick='$markAsPassed' class='fas fa-marker'></i>
                                                        ";

                                                        // $remarks_url = "
                                                        //     <a href='shs_transferee_remarks.php?student_id=$student_id&s_id=$subject_id&page=view_details'>
                                                        //         <button type='button' class='btn btn-sm btn-primary'>Remark</button>
                                                        //     </a>
                                                        // ";
                                                    }
                                                }

                                                echo '<tr class="text-center">'; 
                                                        echo '<td>'.$value['subject_code'].'</td>';
                                                        echo '<td>'.$value['subject_type'].'</td>';
                                                        echo '<td>'.$value['subject_title'].'</td>';
                                                        echo '<td>'.$value['unit'].'</td>';
                                                     
                                                        echo '<td>'.$value['semester'].'</td>';
                                                        echo '<td>'.$value['course_level'].'</td>';
                                                        echo '<td>'.$remarks_url.'</td>';
                                                echo '</tr>';
                                            }     
                                        }
                                    ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                <?php
            }
        }
    }
?>


<!-- OOO -->

<script>

    // 
    function MarkAsPassed(subject_id, student_id, remarks,
        student_subject_id, course_id, subject_title){

        // console.log('click');

        $.post('../ajax/shs_transferee_grading.php', {
            student_id,
            subject_id,
            remarks,
            student_subject_id, 
            course_id,
            subject_title

        }).done(function (data) {
            Swal.fire({
                icon: 'success',
                title: `Subject: ${subject_title} remarked as Passed.`,
                showConfirmButton: false,
                timer: 800, // Adjust the duration of the toast message in milliseconds (e.g., 3000 = 3 seconds)
                toast: true,
                position: 'top-end',
                showClass: {
                popup: 'swal2-noanimation',
                backdrop: 'swal2-noanimation'
                },
                hideClass: {
                popup: '',
                backdrop: ''
                }
            }).then((result) => {
                location.reload();
            });
            
        });
    }
</script>