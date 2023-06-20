<?php

    class Enrollment{

    private $con, $userLoggedIn, $studentEnroll;

    public function __construct($con, $studentEnroll)
    {
        $this->con = $con;
        $this->studentEnroll = $studentEnroll;
    }

    public function createPage(){

        $nav = $this->createHomePageNav();
        $tabs = $this->createTabs();


        return "
            $nav
        ";

    }

    public function createTabs(){

        $reg_form = "";
 
        $asd = include 'regular_form.php';

        return "
            <div class='tab-content'><br/>
                <div class='tab-pane active' id='New'>

               
            </div>

            <div class='tab-pane' id='Old'><br/>
                <h1>ISDOP</h1>
            </div>
        ";
    }

    private function createRegularForm(){

        if (isset($_POST['regsubmit'])) {
            $_SESSION['STUDID'] 	  =  $_POST['IDNO'];
            $_SESSION['FNAME'] 	      =  $_POST['FNAME'];
            $_SESSION['LNAME']  	  =  $_POST['LNAME'];
            $_SESSION['MI']           =  $_POST['MI'];
            $_SESSION['PADDRESS']     =  $_POST['PADDRESS'];
            $_SESSION['SEX']          =  $_POST['optionsRadios'];
            // $_SESSION['BIRTHDATE']    = date_format(date_create($_POST['BIRTHDATE']),'Y-m-d'); 
            $_SESSION['BIRTHDATE']    = $_POST['BIRTHDATE']; 
            $_SESSION['NATIONALITY']  =  $_POST['NATIONALITY'];
            $_SESSION['BIRTHPLACE']   =  $_POST['BIRTHPLACE'];
            $_SESSION['RELIGION']     =  $_POST['RELIGION'];
            $_SESSION['CONTACT']      =  $_POST['CONTACT'];
            $_SESSION['CIVILSTATUS']  =  $_POST['CIVILSTATUS'];
            $_SESSION['GUARDIAN']     =  $_POST['GUARDIAN'];
            $_SESSION['GCONTACT']     =  $_POST['GCONTACT'];
            $_SESSION['COURSEID'] 	  =  $_POST['COURSE'];
            // $_SESSION['SEMESTER']     =  $_POST['SEMESTER'];  
            $_SESSION['USER_NAME']    =  $_POST['USER_NAME']; 
            $_SESSION['PASS']    	  =  $_POST['PASS']; 
        }


        $_SESSION['SY'] = "2023-2024";
        $autonum = 100002;

    }

    //

    public function createHomePageNav(){
        return "
            <ul class='nav'>
                <li class='nav-item'>
                    <a class='nav-link active' aria-current='page' href='#New'>New</a>
                </li>
                <li class='nav-item'>
                    <a class='nav-link' data-toggle='tab' href='#Old'>Old</a>
                </li>
                <li class='nav-item'>
                    <a class='nav-link' href='#'>Transferees</a>
                </li>
            </ul>
        ";
    }

    public function PopulateSubjects($course_id){

        echo $course_id;
    }

    public function StudentQualifiedEnrollment(){

        # Has passed remarks from start until current semester enrollment date.
        $active = 1;

        $sql = $this->con->prepare("SELECT * FROM student
            WHERE active=:active");

        $sql->bindValue(":active", $active);
        $sql->execute();
        return $sql->fetchAll(PDO::FETCH_ASSOC);

    }

    public function CheckStudentEnrolledSubject($student_username){

        // Get Subject_Program
        $student_course_id = $this->studentEnroll->GetStudentCourseId($student_username);
        $student_program_id = $this->studentEnroll->GetStudentProgramId($student_course_id);
        $school_year_obj = $this->studentEnroll->GetActiveSchoolYearAndSemester();

        $student_course_level = $this->studentEnroll->GetStudentCourseLevel($student_username);

        $current_semester = $school_year_obj['period'];
        
        $sql = $this->con->prepare("SELECT subject_title FROM subject_program
            WHERE program_id=:program_id
            AND semester=:semester
            AND course_level=:course_level
            ");

        $sql->bindValue(":program_id", $student_program_id);
        $sql->bindValue(":semester", $current_semester);
        $sql->bindValue(":course_level", $student_course_level);
        $sql->execute();
        if($sql->rowCount() > 0){

            $result = $sql->fetchAll(PDO::FETCH_ASSOC);

        }
        $student_current_subject = $this->GetStudentCurrentSubjectLoadInCurrentSemester($student_username);
        if($student_current_subject != null){

            print_r($student_current_subject);

        }

    }
    #
    public function GetStudentCurrentSubjectLoadInCurrentSemester($student_username){

        $student_course_id = $this->studentEnroll->GetStudentCourseId($student_username);
        $student_program_id = $this->studentEnroll->GetStudentProgramId($student_course_id);
        $school_year_obj = $this->studentEnroll->GetActiveSchoolYearAndSemester();

        $student_course_level = $this->studentEnroll->GetStudentCourseLevel($student_username);

        $current_semester = $school_year_obj['period'];
        
        $sql = $this->con->prepare("SELECT subject_title FROM subject_program
            WHERE program_id=:program_id
            AND semester=:semester
            AND course_level=:course_level
            ");

        $arr = [];

        $sql->bindValue(":program_id", $student_program_id);
        $sql->bindValue(":semester", $current_semester);
        $sql->bindValue(":course_level", $student_course_level);
        $sql->execute();
        if($sql->rowCount() > 0){

            $result = $sql->fetchAll(PDO::FETCH_ASSOC);
            return $result;
            
        }
        return null;
    }
    
    public function GetStudentSubjectLoad($student_username){

        $student_course_id = $this->studentEnroll->GetStudentCourseId($student_username);
        $student_program_id = $this->studentEnroll->GetStudentProgramId($student_course_id);
        $school_year_obj = $this->studentEnroll->GetActiveSchoolYearAndSemester();

        $student_course_level = $this->studentEnroll->GetStudentCourseLevel($student_username);

        $current_semester = $school_year_obj['period'];
        
        $sql = $this->con->prepare("SELECT subject_title FROM subject_program
            WHERE program_id=:program_id
            AND semester=:semester
            AND course_level=:course_level
            ");

        $arr = [];

        $sql->bindValue(":program_id", $student_program_id);
        $sql->bindValue(":semester", $current_semester);
        $sql->bindValue(":course_level", $student_course_level);
        $sql->execute();
        if($sql->rowCount() > 0){

            $result = $sql->fetchAll(PDO::FETCH_ASSOC);
            return $result;
            
        }
        return null;
    }
    public function GenerateEnrollmentFormId(){

        // $characters = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        // $length = 6;
        // $enrollmentFormId = substr(str_shuffle($characters), 0, $length);

        $characters = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $length = 6;
        $maxAttempts = 100;

        // $sql = $this->con->prepare("SELECT * FROM enrollment
        //     WHERE enrollment_form_id=:enrollment_form_id");

        // $sql->bindValue(":enrollment_form_id", $enrollmentFormId);
        // $sql->execute();

        // if($sql->rowCount() > 0){

        //     # Changed the enrollment_form_id
        // }

        $enrollmentFormId = $this->generateUniqueId($characters, $length);
        $attempt = 1;

        while ($this->isUniqueIdExists($enrollmentFormId) 
            && $attempt <= $maxAttempts) {
            $enrollmentFormId = $this->generateUniqueId($characters, $length);
            $attempt++;
        }

        if ($attempt > $maxAttempts) {
            // Maximum attempts reached, handle the error accordingly
            // You can throw an exception, display an error message, or take any other desired action
            
        }

        return $enrollmentFormId;
    }

    function generateUniqueId($characters, $length) {
        return substr(str_shuffle($characters), 0, $length);
    }   

    function isUniqueIdExists($enrollmentFormId) {
        // Check if the enrollment form ID already exists in the database
        $sql = $this->con->prepare("SELECT * FROM enrollment 
            WHERE enrollment_form_id = :enrollment_form_id");
        $sql->bindValue(":enrollment_form_id", $enrollmentFormId);
        $sql->execute();

        return $sql->rowCount() > 0;
    }

    function GetEnrollmentId($student_id, $course_id, $school_year_id) {
        // Check if the enrollment form ID already exists in the database
        $sql = $this->con->prepare("SELECT enrollment_id FROM enrollment 
            WHERE course_id = :course_id
            AND student_id = :student_id
            AND school_year_id = :school_year_id
            
            ");
        $sql->bindValue(":course_id", $course_id);
        $sql->bindValue(":student_id", $student_id);
        $sql->bindValue(":school_year_id", $school_year_id);
        $sql->execute();
        
        return $sql->fetchColumn();
    }

    function GetEnrollmentFormId($student_id, $course_id, $school_year_id) {
        // Check if the enrollment form ID already exists in the database
        $sql = $this->con->prepare("SELECT enrollment_form_id FROM enrollment 
            WHERE course_id = :course_id
            AND student_id = :student_id
            AND school_year_id = :school_year_id
            
            ");
        $sql->bindValue(":course_id", $course_id);
        $sql->bindValue(":student_id", $student_id);
        $sql->bindValue(":school_year_id", $school_year_id);
        $sql->execute();

        return $sql->fetchColumn();
    }

    function GetEnrollmentDate($student_id, $course_id, $school_year_id) {
        // Check if the enrollment form ID already exists in the database
        $sql = $this->con->prepare("SELECT enrollment_date FROM enrollment 
            WHERE course_id = :course_id
            AND student_id = :student_id
            AND school_year_id = :school_year_id
            
            ");
        $sql->bindValue(":course_id", $course_id);
        $sql->bindValue(":student_id", $student_id);
        $sql->bindValue(":school_year_id", $school_year_id);
        $sql->execute();

        return $sql->fetchColumn();
    }

    function GetEnrollmenDate($student_id, $course_id, $school_year_id) {
        // Check if the enrollment form ID already exists in the database
        $sql = $this->con->prepare("SELECT enrollment_date FROM enrollment 
            WHERE course_id = :course_id
            AND student_id = :student_id
            AND school_year_id = :school_year_id
            
            ");
        $sql->bindValue(":course_id", $course_id);
        $sql->bindValue(":student_id", $student_id);
        $sql->bindValue(":school_year_id", $school_year_id);
        $sql->execute();

        return $sql->fetchColumn();
    }

    function GetStudentEnrollmentCourseId($student_id,
        $school_year_id) {
        // Check if the enrollment form ID already exists in the database
        $sql = $this->con->prepare("SELECT course_id FROM enrollment 
            WHERE student_id = :student_id
            AND school_year_id = :school_year_id
            
            ");
        $sql->bindValue(":student_id", $student_id);
        $sql->bindValue(":school_year_id", $school_year_id);
        $sql->execute();

        return $sql->fetchColumn();
    }

    function GetEnrollmentProgram($student_program_id) {
        

        $program = $this->con->prepare("SELECT acronym FROM program
            WHERE program_id=:program_id
            LIMIT 1
        ");
        $program->bindValue(":program_id", $student_program_id);
        $program->execute();

        return $program->fetchColumn();

    }

    function MarkAsRegistrarEvaluatedByEnrollmentId($enrollment_id) {

        $registrar_evaluated = "yes";
        // Check if the enrollment form ID already exists in the database
        $sql = $this->con->prepare("UPDATE enrollment 
            SET registrar_evaluated=:registrar_evaluated
            WHERE enrollment_id = :enrollment_id");

        $sql->bindValue(":registrar_evaluated", $registrar_evaluated);
        $sql->bindValue(":enrollment_id", $enrollment_id);
        
        return $sql->execute();
    }

    function MarkAsCashierEvaluatedByEnrollmentId($enrollment_id) {

        $cashier_evaluated = "yes";
        // Check if the enrollment form ID already exists in the database
        $sql = $this->con->prepare("UPDATE enrollment 
            SET cashier_evaluated=:cashier_evaluated
            WHERE enrollment_id = :enrollment_id");

        $sql->bindValue(":cashier_evaluated", $cashier_evaluated);
        $sql->bindValue(":enrollment_id", $enrollment_id);
        
        return $sql->execute();
    }

    function UpdateSHSStudentEnrollmentCourseId($enrollment_id,
        $course_id){

        $sql = $this->con->prepare("UPDATE enrollment 
            SET course_id=:course_id
            WHERE enrollment_id = :enrollment_id");

        $sql->bindValue(":course_id", $course_id);
        $sql->bindValue(":enrollment_id", $enrollment_id);
        
        return $sql->execute();
    }

    function CheckEnrollmentCashierApproved($student_id, $course_id, $school_year_id) {
        // Check if the enrollment form ID already exists in the database
        $sql = $this->con->prepare("SELECT enrollment_form_id FROM enrollment 
            WHERE course_id = :course_id
            AND student_id = :student_id
            AND school_year_id = :school_year_id
            AND cashier_evaluated = :cashier_evaluated
            
            ");
        $sql->bindValue(":course_id", $course_id);
        $sql->bindValue(":student_id", $student_id);
        $sql->bindValue(":school_year_id", $school_year_id);
        $sql->bindValue(":cashier_evaluated", "yes");
        $sql->execute();

        return $sql->rowCount() > 0;
    }

    function CheckEnrollmentRegistrarApproved($student_id, $course_id, $school_year_id) {
        // Check if the enrollment form ID already exists in the database
        $sql = $this->con->prepare("SELECT enrollment_form_id FROM enrollment 
            WHERE course_id = :course_id
            AND student_id = :student_id
            AND school_year_id = :school_year_id
            AND registrar_evaluated = :registrar_evaluated
            
            ");
        $sql->bindValue(":course_id", $course_id);
        $sql->bindValue(":student_id", $student_id);
        $sql->bindValue(":school_year_id", $school_year_id);
        $sql->bindValue(":registrar_evaluated", "yes");
        $sql->execute();

        return $sql->rowCount() > 0;
    }

    function CheckEnrollmentEnrolled($student_id, $course_id, $school_year_id) {
        // Check if the enrollment form ID already exists in the database
        $sql = $this->con->prepare("SELECT enrollment_status FROM enrollment 
            WHERE course_id = :course_id
            AND student_id = :student_id
            AND school_year_id = :school_year_id
            AND enrollment_status = :enrollment_status
            AND enrollment_approve IS NOT NULL
            
            ");
        $sql->bindValue(":course_id", $course_id);
        $sql->bindValue(":student_id", $student_id);
        $sql->bindValue(":school_year_id", $school_year_id);
        $sql->bindValue(":enrollment_status", "enrolled");
        $sql->execute();

        return $sql->rowCount() > 0;
    }
    function GetEnrollmentEnrolledDate($student_id, $course_id, $school_year_id) {
        // Check if the enrollment form ID already exists in the database
        $sql = $this->con->prepare("SELECT enrollment_approve FROM enrollment 
            WHERE course_id = :course_id
            AND student_id = :student_id
            AND school_year_id = :school_year_id
            AND enrollment_status = :enrollment_status
            
            ");
        $sql->bindValue(":course_id", $course_id);
        $sql->bindValue(":student_id", $student_id);
        $sql->bindValue(":school_year_id", $school_year_id);
        $sql->bindValue(":enrollment_status", "enrolled");
        $sql->execute();
        if($sql->rowCount() > 0){
            return $sql->fetchColumn();
        }
        return null;
    }   
    
    function GetEnrollmentNonEnrolledDate($student_id, $course_id, $school_year_id) {
        // Check if the enrollment form ID already exists in the database
        $sql = $this->con->prepare("SELECT enrollment_date FROM enrollment 
            WHERE course_id = :course_id
            AND student_id = :student_id
            AND school_year_id = :school_year_id
            
            ");
        $sql->bindValue(":course_id", $course_id);
        $sql->bindValue(":student_id", $student_id);
        $sql->bindValue(":school_year_id", $school_year_id);
        $sql->execute();

        if($sql->rowCount() > 0){
            return $sql->fetchColumn();

        }
        return null;
    }  
    
    public function AnalyzedTransfereeStudentStudentStatus($enrollment_id){


    }
    public function UpdateTransfereeStudentToRegular($enrollment_id){

        $sql = $this->con->prepare("SELECT student_id, course_id 
        
            FROM enrollment 
            WHERE enrollment_id = :enrollment_id
            
            ");
        $sql->bindValue(":enrollment_id", $enrollment_id);
        $sql->execute();

        if($sql->rowCount() > 0){

            $row = $sql->fetch(PDO::FETCH_ASSOC);

            $student_id = $row['student_id'];
            $course_id = $row['course_id'];
            $student_statusv2 = "Regular";
            
            $sql_update = $this->con->prepare("UPDATE student 
                SET student_statusv2=:student_statusv2
                WHERE student_id = :student_id
                AND course_id = :course_id
                ");

            $sql_update->bindValue(":student_statusv2", $student_statusv2);
            $sql_update->bindValue(":student_id", $student_id);
            $sql_update->bindValue(":course_id", $course_id);
            
            return $sql_update->execute();
        }
    }
    public function UpdateTransfereeStudentIntoIrregular($enrollment_id){

        $sql = $this->con->prepare("SELECT student_id, course_id 
        
            FROM enrollment 
            WHERE enrollment_id = :enrollment_id
            
            ");
        $sql->bindValue(":enrollment_id", $enrollment_id);
        $sql->execute();

        if($sql->rowCount() > 0){

            $row = $sql->fetch(PDO::FETCH_ASSOC);

            $student_id = $row['student_id'];
            $course_id = $row['course_id'];
            $student_statusv2 = "Irregular";
            
            $sql_update = $this->con->prepare("UPDATE student 
                SET student_statusv2=:student_statusv2
                WHERE student_id = :student_id
                AND course_id = :course_id
                ");

            $sql_update->bindValue(":student_statusv2", $student_statusv2);
            $sql_update->bindValue(":student_id", $student_id);
            $sql_update->bindValue(":course_id", $course_id);
            
            
            return $sql_update->execute();
        }
    }

    public function EnrolledOSRegular($student_id, $course_id, $school_year_id,
        $enrollment_form_id){

        $now = date("Y-m-d H:i:s");

        $sql = $this->con->prepare("INSERT INTO enrollment
            (student_id, course_id, school_year_id, enrollment_form_id, enrollment_approve,
                is_transferee, registrar_evaluated)
            VALUES(:student_id, :course_id, :school_year_id, :enrollment_form_id, :enrollment_approve,
                :is_transferee, :registrar_evaluated)");

        $sql->bindValue(":student_id", $student_id);
        $sql->bindValue(":course_id", $course_id);
        $sql->bindValue(":school_year_id", $school_year_id);
        $sql->bindValue(":enrollment_form_id", $enrollment_form_id);
        $sql->bindValue(":enrollment_approve", $now);
        $sql->bindValue(":is_transferee", 0);
        $sql->bindValue(":registrar_evaluated", "yes");
        return $sql->execute();

    }

    public function PendingEnrollment(){
        $sql = $this->con->prepare("SELECT t1.*, t2.acronym 
            FROM pending_enrollees as t1

            LEFT JOIN program as t2 ON t2.program_id = t1.program_id
            WHERE t1.student_status !='APPROVED'
            AND t1.is_finished = 1
            ");

        $sql->execute();
        if($sql->rowCount() > 0){

            return $sql->fetchAll(PDO::FETCH_ASSOC);
        }
        return [];
    }

    public function WaitingPaymentEnrollment($current_school_year_id){

        $default_shs_course_level = 11;
        $is_new_enrollee = 1;
        $is_transferee = 1;
        $regular_Status = "Regular";
        $enrollment_status = "tentative";
        $registrar_evaluated = "yes";

        $registrar_side = $this->con->prepare("SELECT 

            t1.student_id, t1.cashier_evaluated,t1.registrar_evaluated,
            t1.enrollment_date, t1.is_transferee,

            t2.firstname,t2.username, t2.student_statusv2,t2.student_unique_id,
            t2.lastname,t2.course_level,
            t2.course_id, t2.student_id as t2_student_id,
            t2.course_id, t2.course_level,t2.student_status, t2.admission_status,
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

            return $registrar_side->fetchAll(PDO::FETCH_ASSOC);
        }
        return [];
    }

    public function OngoingEnrollment(){

        $sql = $this->con->prepare("SELECT t1.*, t4.*, t2.* 
        
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

            return $sql->fetchAll(PDO::FETCH_ASSOC);
        }

        return [];
    }

    public function UnionEnrollment(){

        // $sql = $this->con->prepare("SELECT t1.*, t4.*, t2.* 
        
        //     FROM student as t1

        //     INNER JOIN enrollment as t2 ON t2.student_id = t1.student_id
        //     AND t2.course_id=t1.course_id

        //     LEFT JOIN course as t3 ON t3.course_id = t1.course_id
        //     LEFT JOIN program as t4 ON t4.program_id = t3.program_id

        //     -- WHERE t1.student_status='Transferee'
        //     WHERE t1.admission_status='Transferee'
        //     AND t2.registrar_evaluated='no'
        //     AND t2.enrollment_status='tentative'
        //     AND t2.is_new_enrollee='no'
        // ");

        $sql = $this->con->prepare(" SELECT t1.firstname, t1.lastname, 

            t1.student_statusv2,
            t1.admission_status,
            t2.enrollment_date AS submission_creation,
            t3.program_id,
            NULL AS student_status_pending,
            t1.admission_status AS admission_status,
            t1.is_tertiary AS student_classification,
            NULL AS pending_enrollees_id,
            t1.student_statusv2 AS student_statusv2,
            t2.course_id AS student_course_id,
            t1.student_id AS student_id,
            t1.student_unique_id AS student_unique_id
            

            -- O.S QUERY.

            FROM student as t1
            INNER JOIN enrollment as t2 ON t2.student_id = t1.student_id
            AND t2.course_id = t1.course_id

            LEFT JOIN course as t3 ON t3.course_id = t1.course_id
            LEFT JOIN program as t4 ON t4.program_id = t3.program_id
            WHERE t1.admission_status = 'Transferee'

            AND t2.registrar_evaluated = 'no'
            AND t2.enrollment_status = 'tentative'
            AND t2.is_new_enrollee = 'no'

            UNION

            SELECT t1.firstname, t1.lastname, NULL, NULL,
            t1.date_creation AS submission_creation,
            t1.program_id,
            t1.student_status AS student_status_pending,
            NULL AS admission_status,
            NULL AS student_classification,
            t1.pending_enrollees_id,
            NULL AS student_statusv2,
            NULL AS student_course_id,
            NULL AS student_id,
            NULL AS student_unique_id


            FROM pending_enrollees as t1
            LEFT JOIN program as t2 ON t2.program_id = t1.program_id
            WHERE t1.student_status != 'APPROVED'
            AND t1.is_finished = 1
        ");

        $sql->execute();
        if($sql->rowCount() > 0){
            $result = $sql->fetchAll(PDO::FETCH_ASSOC);
            return $result;
        }
        return [];
    }
    public function WaitingApprovalEnrollment($current_school_year_id){

        $default_shs_course_level = 11;
        $is_new_enrollee = 1;
        $is_transferee = 1;
        $regular_Status = "Regular";
        $enrollment_status = "tentative";
        $registrar_evaluated = "yes";

        $registrar_side = $this->con->prepare("SELECT 

            t1.student_id, t1.cashier_evaluated,t1.registrar_evaluated,
            t1.is_transferee, t1.enrollment_approve,

            t2.firstname,t2.username, t2.student_unique_id,
            t2.lastname,t2.course_level,
            t2.admission_status,t2.student_statusv2,
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

            return $registrar_side->fetchAll(PDO::FETCH_ASSOC);
        }
        return [];
    }

    public function EnrolledStudentsWithinSYSemester($current_school_year_id){

        $enrollment_status = "enrolled";
        $registrar_evaluated = "yes";
        $cashier_evaluated = "yes";

        $sql = $this->con->prepare("SELECT t2.*, t3.program_section 
        
            FROM enrollment as t1

            INNER JOIN student as t2 ON t2.student_id = t1.student_id

            LEFT JOIN course as t3 ON t3.course_id = t2.course_id

            WHERE school_year_id=:school_year_id
            AND enrollment_status=:enrollment_status
            AND registrar_evaluated=:registrar_evaluated
            AND cashier_evaluated=:cashier_evaluated
            ");

        $sql->bindValue(":school_year_id", $current_school_year_id);
        $sql->bindValue(":enrollment_status", $enrollment_status);
        $sql->bindValue(":registrar_evaluated", $registrar_evaluated);
        $sql->bindValue(":cashier_evaluated", $cashier_evaluated);
        $sql->execute();
        if($sql->rowCount() > 0){


            $queries = $sql->fetchAll(PDO::FETCH_ASSOC);
            return $queries;
        }
        return [];


    }


    public function EnrolledStudentsWithinSYSemesterx($current_school_year_id){

        $enrollment_status = "enrolled";
        $registrar_evaluated = "yes";
        $cashier_evaluated = "yes";

        $sql = $this->con->prepare("SELECT t2.*, t3.program_section 
        
            FROM enrollment as t1

            INNER JOIN student as t2 ON t2.student_id = t1.student_id

            LEFT JOIN course as t3 ON t3.course_id = t2.course_id

            -- WHERE school_year_id=:school_year_id
            WHERE enrollment_status=:enrollment_status
            AND registrar_evaluated=:registrar_evaluated
            AND cashier_evaluated=:cashier_evaluated
            ");

        // $sql->bindValue(":school_year_id", $current_school_year_id);
        $sql->bindValue(":enrollment_status", $enrollment_status);
        $sql->bindValue(":registrar_evaluated", $registrar_evaluated);
        $sql->bindValue(":cashier_evaluated", $cashier_evaluated);
        $sql->execute();

        // $sql->bindValue(":school_year_id", $current_school_year_id);
        $sql->bindValue(":enrollment_status", $enrollment_status);
        $sql->bindValue(":registrar_evaluated", $registrar_evaluated);
        $sql->bindValue(":cashier_evaluated", $cashier_evaluated);
        $sql->execute();
        if($sql->rowCount() > 0){


            $queries = $sql->fetchAll(PDO::FETCH_ASSOC);
            return $queries;
        }
        return [];


    }
}


?>