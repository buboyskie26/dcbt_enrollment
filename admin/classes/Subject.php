<?php

    class Subject{

    private $con, $userLoggedIn, $subject_id, $sqlData;

    public function __construct($con, $userLoggedIn, $subject_id)
    {
        $this->con = $con;
        $this->userLoggedIn = $userLoggedIn;
        $this->subject_id = $subject_id;

        $query = $this->con->prepare("SELECT t1.*, t2.program_section 
        
                FROM subject as t1

                INNER JOIN course as t2 ON t2.course_id = t1.course_id
                WHERE t1.subject_id=:subject_id

                ");

        $query->bindValue(":subject_id", $subject_id);
        $query->execute();

        $this->sqlData = $query->fetch(PDO::FETCH_ASSOC);
    }

    public function InvalidSubject() {

        // Assuming $con is your database connection object

        $stmt = $this->con->prepare("SELECT * FROM subject
            
            WHERE subject_id = ?");

        $stmt->execute([$this->subject_id]);

        if( $stmt->rowCount() > 0){
            return true;
        } 

        return false;

    }
 
  

    public function GetSubjectSection() {
        return isset($this->sqlData['program_section']) ? $this->sqlData["program_section"] : ""; 
    }

    public function GetSubjectName() {
        return isset($this->sqlData['subject_title']) ? $this->sqlData["subject_title"] : ""; 
    }

    public function createFormTemplate(){
        $createProgramSelection = "";
        return "
            <h4 class='text-center mb-3'>Create Subject Program</h4>
            <form method='POST'>

                $createProgramSelection

                <div class='form-group mb-2'>
                    <input class='form-control' type='text' placeholder='Subject Code' name='subject_code'>
                </div>

                <div class='form-group mb-2'>
                    <input class='form-control' type='text' placeholder='Subject Title' name='subject_title'>
                </div>

                <div class='form-group mb-2'>
                    <input class='form-control' type='text' placeholder='Pre-Requisite' name='pre_subject_id'>
                </div>

                <div class='form-group mb-2'>
                    <input class='form-control' type='number' placeholder='Number Of Units' name='unit' >
                </div>

                <div class='form-group mb-2'>
                    <textarea class='form-control' placeholder='Description' name='description' rows='3'></textarea>
                </div>

                <div class='form-group mb-2'>
                    <select class='form-control' name='course_level'>
                        <option value='11'>Grade 11</option>
                        <option value='12'>Grade 12</option>
                    </select>
                </div>
                
                <div class='form-group mb-2'>
                    <select class='form-control' name='semester'>
                        <option value='First'>First</option>
                        <option value='Second'>Second</option>
                    </select>
                </div>

                <div class='form-group mb-2'>
                    <select class='form-control' name='subject_type'>
                        <option value='CORE SUBJECTS'>CORE SUBJECTS</option>
                        <option value='APPLIED SUBJECTS'>APPLIED SUBJECTS</option>
                        <option value='SPECIALIZED_SUBJECTS'>SPECIALIZED_SUBJECTS</option>
                    </select>
                </div>

                <button type='submit' class='btn btn-primary' name='create_subject_program'>Save</button>
            </form>
        ";
    }

    public function createForm(){

            // TODO: 
        if(isset($_POST['create_subject_program'])){
            // echo $semester;
            // Check
            // if (isset($_POST['program_id']) && $_POST['program_id'] !== ''
            //     && isset($_POST['subject_code']) && $_POST['subject_code'] !== ''
            //     // && isset($_POST['subject_title']) && $_POST['subject_title'] !== ''
            //     && isset($_POST['pre_subject_id']) && $_POST['pre_subject_id'] !== ''
            //     // && isset($_POST['unit']) && $_POST['unit'] !== ''
            //     // && isset($_POST['description']) && $_POST['description'] !== ''
            //     && isset($_POST['course_level']) && $_POST['course_level'] !== ''
            //     && isset($_POST['semester']) && $_POST['semester'] !== ''
            //     && isset($_POST['subject_type']) && $_POST['subject_type'] !== '') 

            //     {

            //     $program_id = $_POST['program_id'];
            //     $subject_code = $_POST['subject_code'];

            //     // $subject_title = $_POST['subject_title'];
            //     // $unit = $_POST['unit'];
            //     // $description = $_POST['description'];

            //     $pre_subject_id = $_POST['pre_subject_id'];
            //     $course_level = $_POST['course_level'];
            //     $semester = $_POST['semester'];
            //     $subject_type = $_POST['subject_type'];

            //     // if($create->execute()){

            //     //     // echo "successfully created";
            //     // }
                
            //     $real_subject_title = "";
            //     $real_unit =  "";
            //     $real_description = "";

            //     $subject_template_id = $_POST['subject_template_id'];

            //     $get_subject_template = $this->con->prepare("SELECT * FROM subject_template
            //         WHERE subject_template_id=:subject_template_id
            //         LIMIT 1");

            //     $get_subject_template->bindValue(":subject_template_id", $subject_template_id);
            //     $get_subject_template->execute();

            //     if($get_subject_template->rowCount() > 0){

            //         $row = $get_subject_template->fetch(PDO::FETCH_ASSOC);

            //         $subject_title = $row['subject_title'];
            //         $subject_description = $row['description'];
            //         $unit = $row['unit'];

            //         $real_subject_title = $subject_title;
            //         $real_description = $subject_description;
            //         $real_unit =  $unit;

            //         $create = $this->con->prepare("INSERT INTO subject_program
            //             (program_id, subject_code, pre_subject_id, 

            //                 subject_title, unit, description, 
                            
            //                 course_level, semester, subject_type)

            //             VALUES(:program_id, :subject_code, :pre_subject_id,
            //                 :subject_title, :unit, :description, 

            //                 :course_level, :semester, :subject_type)");
                        
            //         $create->bindParam(':program_id', $program_id);
            //         $create->bindParam(':subject_code', $subject_code);

            //         $create->bindParam(':subject_title', $real_subject_title);
            //         $create->bindParam(':unit', $real_unit);
            //         $create->bindParam(':description', $real_description);

            //         $create->bindParam(':pre_subject_id', $pre_subject_id);
            //         $create->bindParam(':course_level', $course_level);
            //         $create->bindParam(':semester', $semester);
            //         $create->bindParam(':subject_type', $subject_type);
            //         $create->execute();
            //         //
            //     }

            // } else {
            //     // One or more variables are empty strings
            //     // Handle the error or display a message
            //     echo "One or more variables are empty strings";
            // }

        }
        // $courseMain = $this->createCourseMainCategory();
        $createProgramSelection = $this->createProgramSelection();

        //    <div class='form-group mb-2'>
        //                 <input class='form-control' type='text' placeholder='Subject Title' name='subject_title'>
        //     </div>

        //     <div class='form-group mb-2'>
        //         <input class='form-control' type='number' placeholder='Number Of Units' name='unit' >
        //     </div>

        //     <div class='form-group mb-2'>
        //         <textarea class='form-control' placeholder='Description' name='description' rows='3'></textarea>
        //     </div>

        $subjectTitleDropDown = $this->subjectTitleDropDown(4);

        return "
                <h4 class='text-center mb-3'>Create Subject Program</h4>
                <form method='POST'>

                    $createProgramSelection

                    <div class='form-group mb-2'>
                        <input class='form-control' type='text' placeholder='Subject Code' name='subject_code'>
                    </div>
               
                    <div class='form-group mb-2'>
                        <input class='form-control' type='text' placeholder='Pre-Requisite' name='pre_subject_id'>
                    </div>
 
                    <div class='form-group mb-2'>
                        <select class='form-control' name='course_level'>
                            <option value='11'>Grade 11</option>
                            <option value='12'>Grade 12</option>
                        </select>
                    </div>
                    
                    <div class='form-group mb-2'>
                        <select class='form-control' name='semester'>
                            <option value='First'>First</option>
                            <option value='Second'>Second</option>
                        </select>
                    </div>

                    <div class='form-group mb-2'>
                        <select class='form-control' name='subject_type'>
                            <option value='CORE SUBJECTS'>CORE SUBJECTS</option>
                            <option value='APPLIED SUBJECTS'>APPLIED SUBJECTS</option>
                            <option value='SPECIALIZED_SUBJECTS'>SPECIALIZED_SUBJECTS</option>
                        </select>
                    </div>

                    <button type='submit' class='btn btn-primary' name='create_subject_program'>Save</button>
                </form>
            ";
    }

    public function createFormModified(){

        if(isset($_POST['create_subject_template'])){

            $pre_requisite_title = $_POST['pre_requisite_title'];
            $subject_title = $_POST['subject_title'];
            $subject_type = $_POST['subject_type'];
            $unit = $_POST['unit'];
            $description = $_POST['description'];
            $subject_code = $_POST['subject_code'];

            $create = $this->con->prepare("INSERT INTO subject_template
                (subject_title, unit, subject_type,
                pre_requisite_title, description, subject_code)

                VALUES(:subject_title, :unit, :subject_type,
                :pre_requisite_title, :description, :subject_code)");
                
            $create->bindParam(':subject_title', $subject_title);
            $create->bindParam(':pre_requisite_title', $pre_requisite_title);
            $create->bindParam(':subject_type', $subject_type);
            $create->bindParam(':unit', $unit);
            $create->bindParam(':description', $description);
            $create->bindParam(':subject_code', $subject_code);

            if($create->execute()){

                AdminUser::success("New Template Added", "list.php");
                exit();
            }

        }

        return "
            <div class='card'>
                <div class='card-header'>
                    <h4 class='text-center mb-3'>Create Subject Program</h4>
                </div>
                <div class='card-body'>
                    <form method='POST'>

                        <div class='form-group mb-2'>
                            <input class='form-control' type='text' placeholder='Subject Code' name='subject_code'>
                        </div>

                        <div class='form-group mb-2'>
                            <input class='form-control' type='text' placeholder='Subject Title' name='subject_title'>
                        </div>

                        <div class='form-group mb-2'>
                            <textarea class='form-control' placeholder='Subject Description' name='description'></textarea>
                        </div>


                
                        <div class='form-group mb-2'>
                            <input class='form-control' type='text' placeholder='Pre-Requisite' name='pre_requisite_title'>
                        </div>
    
                        <div class='form-group mb-2'>
                            <select class='form-control' name='subject_type'>
                                <option value='Core'>Core</option>
                                <option value='Applied'>Applied</option>
                                <option value='Specialized'>Specialized</option>
                            </select>
                        </div>

                        <div class='form-group mb-2'>
                            <input class='form-control' value='3' type='text' placeholder='Unit' name='unit'>
                        </div>

                        <button type='submit' class='btn btn-primary'
                            name='create_subject_template'>Save</button>
                    </form>
                </div>
            </div>

            ";
        

    }

    private function subjectTitleDropDown($program_id){

        $query = $this->con->prepare("SELECT * FROM subject_template
            WHERE program_id=:program_id
        ");
        $query->bindValue(":program_id", $program_id);
        $query->execute();
        if($query->rowCount() > 0){

        }
    }

    public function insertSubject($subject_code, $subject_title,
        $units, $semester, $subject_description, $pre_requisite,
        $course_main_id, $course_level){
            
        // Check if the subject already entered.

        $query = $this->con->prepare("INSERT INTO subject(subject_code, subject_title,
            description, unit, semester, pre_requisite, course_main_id, course_level)
            VALUES(:subject_code, :subject_title,
                :description, :unit, :semester, :pre_requisite, :course_main_id, :course_level)");
        
        $query->bindValue(":subject_code", $subject_code);
        $query->bindValue(":subject_title", $subject_title);
        $query->bindValue(":description", $subject_description);
        $query->bindValue(":unit", $units);
        $query->bindValue(":semester", $semester);
        $query->bindValue(":pre_requisite", $pre_requisite);
        $query->bindValue(":course_main_id", $course_main_id);
        $query->bindValue(":course_level", $course_level);

        return $query->execute();
    }

    public function createProgramSelection(){
        $SHS_DEPARTMENT = 4;
        $query = $this->con->prepare("SELECT * FROM program
            WHERE department_id=:department_id
        ");
        $query->bindValue(":department_id", $SHS_DEPARTMENT);
        $query->execute();
        if($query->rowCount() > 0){

            $html = "<div class='form-group mb-2'>
                <select id='program_id' class='form-control' name='program_id'>";

            $html .= "<option value='Course-Section' disabled selected>Select-Subject</option>";

            while($row = $query->fetch(PDO::FETCH_ASSOC)){
                $html .= "
                    <option value='".$row['program_id']."'>".$row['program_name']."</option>
                ";
            }
            $html .= "</select>
                    </div>";
            return $html;
        }
 
       return null;
    }

    public function SelectSubjectTitle(){
        $SHS_DEPARTMENT = 4;
        $query = $this->con->prepare("SELECT * FROM subject_template
            WHERE course_level=:course_level
            AND semester=:semester
        ");
        $query->bindValue(":course_level", 0);
        $query->bindValue(":semester", "");
        $query->execute();

        if($query->rowCount() > 0){

            $html = "<div class='form-group mb-2'>
                <label   class='mb-2'>Template</label>
                <select id='subject_template_id' class='form-control' name='subject_template_id'>";

            while($row = $query->fetch(PDO::FETCH_ASSOC)){
                $html .= "
                    <option value='".$row['subject_template_id']."'>".$row['subject_title']."</option>
                ";
            }
            $html .= "</select>
                    </div>";
            return $html;
        }
 
       return null;
    }

    public function SelectSubjectTitleEdit(){
        $SHS_DEPARTMENT = 4;
        $query = $this->con->prepare("SELECT * FROM subject_template
            WHERE course_level=:course_level
            AND semester=:semester
        ");
        $query->bindValue(":course_level", 0);
        $query->bindValue(":semester", "");
        $query->execute();

        if($query->rowCount() > 0){

            $html = "<div class='form-group mb-2'>
                <label   class='mb-2'>Template</label>
                <select id='edit_subject_template_id' class='form-control'
                    name='edit_subject_template_id'>";

            while($row = $query->fetch(PDO::FETCH_ASSOC)){
                $html .= "
                    <option value='".$row['subject_template_id']."'>".$row['subject_title']."</option>
                ";
            }
            $html .= "</select>
                    </div>";
            return $html;
        }
 
       return null;
    }

    public function GetPreRequisiteSubjectTitle($subject_tertiary_id){

        $pre_req = "";

        $query_subject = $this->con->prepare("SELECT subject_title 
        
            FROM subject_tertiary  

            WHERE subject_tertiary_id=:subject_tertiary_id
            LIMIT 1
        ");
        $query_subject->bindValue(":subject_tertiary_id", $subject_tertiary_id);
        $query_subject->execute();

        if($query_subject->rowCount() > 0){

            $subject_tertiary_title = $query_subject->fetchColumn();
                $pre_req =  $subject_tertiary_title;
        }
        return $pre_req;
    }

    public function GetTertiarySubjectId($subject_tertiary_id){

        $subject_program_id = null;

        $query_subject = $this->con->prepare("SELECT subject_program_id 
        
            FROM subject_tertiary  

            WHERE subject_tertiary_id=:subject_tertiary_id
            LIMIT 1
        ");
        $query_subject->bindValue(":subject_tertiary_id", $subject_tertiary_id);
        $query_subject->execute();

        if($query_subject->rowCount() > 0){

            $subject_program_id = $query_subject->fetchColumn();
        }
        return $subject_program_id;
    }


    public function GetSubjectTertiaryCourseId($subject_tertiary_id){

        $query_subject = $this->con->prepare("SELECT course_tertiary_id FROM subject_tertiary  

            WHERE subject_tertiary_id=:subject_tertiary_id
            LIMIT 1
        ");
        
        $query_subject->bindValue(":subject_tertiary_id", $subject_tertiary_id);
        $query_subject->execute();
    
        if($query_subject->rowCount() > 0){

            return $query_subject->fetchColumn();
        }
    }

    public function CheckIfPreReqSubjectIsPassed($subject_tertiary_title, 
        $subject_tertiary_id, $student_id){

        $query = $this->con->prepare("SELECT * 
        
            FROM student_subject_grade_tertiary as t1

            INNER JOIN subject_tertiary as t2 ON t2.subject_tertiary_id = t1.subject_tertiary_id

            WHERE t2.subject_title=:subject_title
            AND t1.remarks=:remarks
            AND t1.student_id=:student_id
            LIMIT 1
        ");

        $query->bindValue(":subject_title", $subject_tertiary_title);
        $query->bindValue(":remarks", "Passed");
        $query->bindValue(":student_id", $student_id);
        $query->execute();
        // $query = $this->con->prepare("SELECT * 
        
        //     FROM student_subject_grade_tertiary as t1

        //     INNER JOIN subject_tertiary as t2 ON t2.subject_tertiary_id = t1.subject_tertiary_id

        //     WHERE t1.subject_tertiary_id=:subject_tertiary_id
        //     AND t1.remarks=:remarks
        //     AND t1.student_id=:student_id
        //     LIMIT 1
        // ");

        // $query->bindValue(":subject_title", $subject_tertiary_title);
        // $query->bindValue(":subject_tertiary_id", $subject_tertiary_id);
        // $query->bindValue(":remarks", "Passed");
        // $query->bindValue(":student_id", $student_id);
        // $query->execute();
 
        if($query->rowCount() > 0){
            // echo "$subject_tertiary_title pre req is passed";
        }

        return $query->rowCount() > 0;
    }

    public function CheckIfAlreadyInsertedButNotYetRemarked($subject_tertiary_title, 
        $subject_tertiary_id, $student_id){


        $query = $this->con->prepare("SELECT 
        
            t2.subject_title, t2.subject_tertiary_id, t3.remarks
            
            FROM student_subject_tertiary as t1

            INNER JOIN subject_tertiary as t2 ON t2.subject_tertiary_id = t1.subject_tertiary_id
            LEFT JOIN student_subject_grade_tertiary as t3 ON t3.subject_tertiary_id = t1.subject_tertiary_id

            WHERE t1.student_id=:student_id
            AND t2.subject_title =:subject_title
            -- AND t3.remarks =:remarks

            -- OR t1.student_id=:student_id
            -- AND t2.remarks !=:remarks2
        ");

        $query->bindValue(":student_id", $student_id);
        $query->bindValue(":subject_title", $subject_tertiary_title);
        // $query->bindValue(":remarks", "");
        // $query->bindValue(":remarks2", "Failed");
        $query->execute();

        $isAlreadyTakenButNotYetRemarked = false;

        if($query->rowCount() > 0){

            // $p = $query->fetchAll(PDO::FETCH_ASSOC);

            while($row = $query->fetch(PDO::FETCH_ASSOC)){

                if($row['remarks'] != "Passed" && $row['remarks'] != "Failed"){

                    // echo "$subject_tertiary_title is already inserted but not yet remarked";
                    // echo "<br>";
                    $isAlreadyTakenButNotYetRemarked = true;
                }
            }
            // print_r($p);
        }else{
            // echo "$subject_tertiary_title is not inserted but and not yet remarked";
            $isAlreadyTakenButNotYetRemarked = false;

        }
        return $isAlreadyTakenButNotYetRemarked;
    }
    public function CheckRetakeSubject($subject_tertiary_title, 
        $subject_tertiary_id, $student_id){

        # Get the failed subject
        # Check if that subject is equal to the subject

        $query = $this->con->prepare("SELECT t2.subject_title
        
            FROM student_subject_grade_tertiary as t1

            INNER JOIN subject_tertiary as t2 ON t2.subject_tertiary_id = t1.subject_tertiary_id

            WHERE remarks=:remarks
            AND student_id=:student_id
            ");

        $query->bindValue(":remarks", "Failed");
        $query->bindValue(":student_id", $student_id);

        $query->execute();

        $isRetakeSubject = false;

        // echo $subject_tertiary_title;

        if($query->rowCount() > 0){

            while($row = $query->fetch(PDO::FETCH_ASSOC)){

                $failed_subject_title = $row['subject_title'];

                // echo "$failed_subject_title is failed subject";

                if($failed_subject_title == $subject_tertiary_title){

                    $isRetakeSubject = true;
                    // echo "$subject_tertiary_title subject has been retake.";
                    // echo "<br>";
                }else{
                    // echo "$subject_tertiary_title is not retake subject.";
                    // echo "<br>";
                    $isRetakeSubject = false;

                }
            }
        }
        return $isRetakeSubject;

    }


    # TODO.
    public function CheckIfSubjectAlreadyTaken($subject_tertiary_title, 
        $subject_tertiary_id, $student_id){

        # Get the failed subject
        # Check if that subject is equal to the subject

        $query = $this->con->prepare("SELECT t2.subject_title
        
            FROM student_subject_grade_tertiary as t1

            INNER JOIN subject_tertiary as t2 ON t2.subject_tertiary_id = t1.subject_tertiary_id

            WHERE remarks=:remarks
            AND student_id=:student_id
            ");
    }


    public function CheckStudentGradeLevelEnrolledSubjectSemester($student_id,
        $selected_semester, $selected_course_level){

        $query = $this->con->prepare("SELECT * FROM student_subject as t1
        
            INNER JOIN subject as t2 ON t2.subject_id = t1.subject_id

            WHERE t1.student_id=:student_id
            AND t2.semester=:semester
            AND t2.course_level=:course_level
            ");
        
        $query->bindValue("student_id", $student_id); 
        $query->bindValue("semester", $selected_semester); 
        $query->bindValue("course_level", $selected_course_level); 
        $query->execute();
        
        return $query->rowCount() > 0;

    }

    public function CheckStudentGradeLevelEnrolledSubjectSemesterv2($student_id,
        $selected_semester, $selected_course_level){

            // echo $selected_course_level;
            
        $query = $this->con->prepare("SELECT 

            t1.enrollment_id, t1.enrollment_form_id, t2.term,
            t1.student_id, t1.course_id, t2.school_year_id, t2.period 

            FROM enrollment as t1

            INNER JOIN school_year as t2 ON t1.school_year_id = t2.school_year_id
            INNER JOIN course as t3 ON t3.course_id = t1.course_id

            WHERE t1.student_id = :student_id

            AND t1.enrollment_status=:enrollment_status
            AND t2.period =:period
            AND t3.course_level =:course_level
            --  Get the latest
            ORDER BY t1.enrollment_id DESC
            LIMIT 1

            -- AND c.course_id =:course_id
            ");

        $query->bindValue("student_id", $student_id); 
        $query->bindValue("period", $selected_semester); 
        $query->bindValue("enrollment_status", "enrolled"); 
        $query->bindValue("course_level", $selected_course_level); 
        $query->execute(); 

        if($query->rowCount() > 0){
            return $query->fetch(PDO::FETCH_ASSOC);
        }
        return [];
        // return $query->rowCount() > 0;
    }

    public function GetSubjectCourseLevel($subject_id){

        $query = $this->con->prepare("SELECT course_level FROM subject
        
            WHERE subject_id=:subject_id");

        $query->bindValue(":subject_id", $subject_id);
        $query->execute();

        if($query->rowCount() > 0){
            return $query->fetchColumn();
        }

        return "N/A";

    }

    public function GetSubjectProgramId($subject_id){

        $query = $this->con->prepare("SELECT subject_program_id FROM subject
        
            WHERE subject_id=:subject_id");

        $query->bindValue(":subject_id", $subject_id);
        $query->execute();

        if($query->rowCount() > 0){
            return $query->fetchColumn();
        }

        return "";

    }

    public function GetSubjectTitle($subject_id){

        $query = $this->con->prepare("SELECT subject_title FROM subject
        
            WHERE subject_id=:subject_id");

        $query->bindValue(":subject_id", $subject_id);
        $query->execute();

        if($query->rowCount() > 0){
            return $query->fetchColumn();
        }

        return "";

    }

    public function GetNewTransfereeAddedSubject($student_id, 
        $current_school_year_id, $selected_course_id) : array{


            // echo $selected_course_id;
            // echo $student_id;
        $addedSubjects = $this->con->prepare("SELECT 
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
        $addedSubjects->bindValue(":school_year_id", $current_school_year_id);
        $addedSubjects->bindValue(":course_id", $selected_course_id);
        $addedSubjects->execute();

        if($addedSubjects->rowCount() > 0){
            
            return $addedSubjects->fetchAll(PDO::FETCH_ASSOC);
        }

        return [];

    }

    public function GetNewTransfereeEnrolledAddedSubject($student_id, 
        $current_school_year_id, $selected_course_id) : array{


            // echo $selected_course_id;
            // echo $student_id;
        $addedSubjects = $this->con->prepare("SELECT 
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
        $addedSubjects->bindValue(":school_year_id", $current_school_year_id);
        $addedSubjects->bindValue(":course_id", $selected_course_id);
        $addedSubjects->execute();

        if($addedSubjects->rowCount() > 0){
            
            return $addedSubjects->fetchAll(PDO::FETCH_ASSOC);
        }

        return [];

    }


    public function GetOSransfereeAddedSubject($student_id, 
        $current_school_year_id, $selected_course_id) : array{

        $addedSubjects = $this->con->prepare("SELECT 
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
        $addedSubjects->bindValue(":school_year_id", $current_school_year_id);
        $addedSubjects->bindValue(":course_id", $selected_course_id);
        $addedSubjects->execute();

        if($addedSubjects->rowCount() > 0){
            
            return $addedSubjects->fetchAll(PDO::FETCH_ASSOC);
        }

        return [];

    }

    public function GetEnrolledStudentInSubjects($subject_id){

        $addedSubjects = $this->con->prepare("SELECT * FROM student_subject
            WHERE subject_id=:subject_id");
        
        $addedSubjects->bindParam(":subject_id", $subject_id);
        $addedSubjects->execute();

        return $addedSubjects->rowCount();
        
    }

    

}

?>