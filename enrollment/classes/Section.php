<?php

    class Section{

        private $con, $course_id, $sqlData;


        public function __construct($con, $course_id){
            $this->con = $con;
            $this->course_id = $course_id;

            $query = $this->con->prepare("SELECT * FROM course
                 WHERE course_id=:course_id");

            $query->bindValue(":course_id", $course_id);
            $query->execute();

            $this->sqlData = $query->fetch(PDO::FETCH_ASSOC);
        }

        public function GetCourseId() {
            return isset($this->sqlData['course_id']) ? $this->sqlData["course_id"] : ""; 
        }
        public function GetSectionName() {
            return isset($this->sqlData['program_section']) ? $this->sqlData["program_section"] : ""; 
        }
        public function GetSectionId() {
            return isset($this->sqlData['course_id']) ? $this->sqlData["course_id"] : ""; 
        }
        public function GetSectionGradeLevel() {
            return isset($this->sqlData['course_level']) ? $this->sqlData["course_level"] : ""; 
        }
        public function GetSectionSY() {
            return isset($this->sqlData['school_year_term']) ? $this->sqlData["school_year_term"] : ""; 
        }
        public function GetGuardianName() {
            return isset($this->sqlData['guardian_name']) ? $this->sqlData["guardian_name"] : ""; 
        }
        public function GetGuardianContact() {
            return isset($this->sqlData['guardian_contact_number']) ? $this->sqlData["guardian_contact_number"] : ""; 
        }

 

        // public function GetSectionPeriod() {
        //     return isset($this->sqlData['program_section']) ? $this->sqlData["program_section"] : ""; 
        // }
        public function GetSectionAdvisery() {

            $adviser_id =isset($this->sqlData['adviser_teacher_id']) ? $this->sqlData["adviser_teacher_id"] : null;
    
            // echo $adviser_id;
            $sql = $this->con->prepare("SELECT firstname, lastname FROM teacher
                WHERE teacher_id=:teacher_id
                LIMIT 1");

            $sql->bindValue(":teacher_id", $adviser_id);
            $sql->execute();
            if($sql->rowCount() > 0){
                $row = $sql->fetch(PDO::FETCH_ASSOC);
                return $row['firstname'] . " " .$row['lastname'];
            }else{

                return "No Adviser Yet";
            }
        }

        public function createForm($subject_id){

            $course_id = $this->GetCourseId();

            if(isset($_POST['edit_subject_program']) 
                && isset($_POST['subject_code']) && isset($_POST['subject_title'])){

                $subject_code = $_POST['subject_code'];
                $subject_title = $_POST['subject_title'];


                $edit_subject = $this->con->prepare("UPDATE subject
                    SET subject_code=:subject_code,
                        subject_title=:subject_title

                    WHERE subject_id=:subject_id
                    AND course_id=:course_id
                    ");
                $edit_subject->bindValue(":subject_code", $subject_code); 
                $edit_subject->bindValue(":subject_title", $subject_title); 
                $edit_subject->bindValue(":subject_id", $subject_id); 
                $edit_subject->bindValue(":course_id", $course_id); 

                if($edit_subject->execute()){

                } 

            }
   
            $subject_query = $this->con->prepare("SELECT * FROM subject
                WHERE subject_id=:subject_id
                AND course_id=:course_id
                LIMIT 1");

            $subject_query->bindValue(":subject_id", $subject_id); 
            $subject_query->bindValue(":course_id", $course_id); 
            $subject_query->execute(); 

            $createProgramSelection = "";

            if($subject_query->rowCount() > 0){
                $row = $subject_query->fetch(PDO::FETCH_ASSOC);

                $subject_code = $row['subject_code'];
                $subject_title = $row['subject_title'];

                return "
                    <h4 class='text-center mb-3'>Edit Section Subject</h4>
                    <form method='POST'>

                        $createProgramSelection

                        <div class='form-group mb-2'>
                            <input class='form-control' value='$subject_code' type='text' placeholder='Subject Code' name='subject_code'>
                        </div>

                        <div class='form-group mb-2'>
                            <input class='form-control' value='$subject_title' type='text' placeholder='Subject Title' name='subject_title'>
                        </div>
                        <button type='submit' class='btn btn-primary' name='edit_subject_program'>Save</button>
                    </form>
                ";
            }
            
        }

        public function createProgramSelection(){

            $SHS_DEPARTMENT = 4;

            $query = $this->con->prepare("SELECT * FROM program
                -- WHERE department_id=:department_id
            ");

            // $query->bindValue(":department_id", $SHS_DEPARTMENT);
            $query->execute();
            
            if($query->rowCount() > 0){

                $html = "<div class='form-group mb-2'>
                    <label class='mb-2'>Program</label>

                    <select id='program_id' class='form-control' name='program_id'>";

                $html .= "<option value='Course-Section' disabled selected>Select-Program</option>";

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

        public function editProgramSelection($program_id){

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

                    $selected = ($row['program_id'] == $program_id) ? 'selected' : '';

                    $html .= "
                        <option value='".$row['program_id']."' $selected>".$row['program_name']."</option>
                    ";
                    // echo "<option value='{$get_row['course_id']}' {$selected}>{$get_row['program_section']}</option>";

                }
                $html .= "</select>
                        </div>";
                return $html;
            }
 
            return null;
        }

        public function createSectionDropdown(){

            $school_year_obj = $this->GetActiveSchoolYearAndSemester();

            $current_school_year_term = $school_year_obj['term'];

            $query = $this->con->prepare("SELECT * FROM course
                WHERE school_year_term=:school_year_term
                AND active=:active
            ");
            $query->bindValue(":school_year_term", $current_school_year_term);
            $query->bindValue(":active", "yes");
            $query->execute();

            if($query->rowCount() > 0){

                $html = "<div class='form-group mb-2'>
                    <select id='course_id' class='form-control' name='course_id'>";

                $html .= "<option value='Course-Section' disabled selected>Select-Strand</option>";

                while($row = $query->fetch(PDO::FETCH_ASSOC)){
                    $html .= "
                        <option value='".$row['course_id']."'>".$row['program_section']."</option>
                    ";
                }

                $html .= "</select>
                        </div>";
                return $html;

            }
 
            return null;
        }
        public function GetActiveSchoolYearAndSemester(){

            $query = $this->con->prepare("SELECT school_year_id,
                term, period

                FROM school_year
                WHERE statuses='Active'
                -- ORDER BY school_year_id DESC
                LIMIT 1");

            $query->execute();
        
            return $query->fetch(PDO::FETCH_ASSOC);
        }

        public function GetTotalNumberOfStudentInSection($course_id,
            $current_school_year_id){
        
            // Based on the enrollment.

            // $query = $this->con->prepare("SELECT t1.enrollment_id, 
            //     t1.course_id,
            //      t1.student_id
            //     -- t1.school_year_id 
                
            //     FROM enrollment as t1

            //     INNER JOIN course as t2 ON t2.course_id = t1.course_id

            //     WHERE t1.course_id=:course_id
            //     -- AND (t1.enrollment_status=:enrollment_status
            //     --     OR t1.enrollment_status=:enrollment_status2)

            //     AND t1.enrollment_status=:enrollment_status
            //     AND t1.school_year_id=:school_year_id
            //     AND t2.active=:active
            //     ");

            // $query->bindValue(":course_id", $course_id);
            // $query->bindValue(":enrollment_status", "enrolled");
            // // $query->bindValue(":enrollment_status2", "enrolled");
            // $query->bindValue(":school_year_id", $current_school_year_id);
            // $query->bindValue(":active", "yes");
            // $query->execute();

            // return $query->rowCount();

            $sql = $this->con->prepare("SELECT 
                            
                    t3.program_id, t2.student_id,
                    t2.student_status,t2.firstname, t2.lastname 
                    
                    FROM enrollment as t1
            
                    LEFT JOIN student as t2 ON t2.student_id=t1.student_id
                    LEFT JOIN course as t3 ON t3.course_id=t1.course_id


                    WHERE t1.course_id=:course_id
                    AND t1.school_year_id=:school_year_id
                    AND t1.enrollment_status=:enrollment_status
            ");

            $sql->bindValue(":course_id", $course_id);
            $sql->bindValue(":school_year_id", $current_school_year_id);
            $sql->bindValue(":enrollment_status", "enrolled");
            $sql->execute();
            return $sql->rowCount();

        }

        public function GetSectionTotalSubjects($course_id){

            $query = $this->con->prepare("SELECT * FROM subject
                WHERE course_id=:course_id");

            $query->bindValue(":course_id", $course_id);
            $query->execute();
            return $query->rowCount();
        }

        public function GetSectionObj($course_id){

            $query = $this->con->prepare("SELECT capacity, course_id,
                program_id, course_level, school_year_term, program_section
                
                FROM course
                WHERE course_id=:course_id");

            $query->bindValue(":course_id", $course_id);
            $query->execute();

            if($query->rowCount() > 0){
                return $query->fetch();
            }
        }


        public function GetSectionTotalScheduleSubjects($course_id){

            $query = $this->con->prepare("SELECT * FROM subject as t1

                INNER JOIN subject_schedule as t2 ON t2.subject_id = t1.subject_id
                WHERE t1.course_id=:course_id");

            $query->bindValue(":course_id", $course_id);
            $query->execute();
            return $query->rowCount();

        }

        public function AutoCreateAnotherSectionv2($row){

            // STEM11-A -> STEM11-B
            // STEM11-C -> STEM11-D

            $program_section = $row['program_section'];
            // $program_section = "HUMMS11-D";
            $program_id = $row['program_id'];
            $course_level = $row['course_level'];
            $capacity = $row['capacity'];
            $school_year_term = $row['school_year_term'];

            // $program_section = "F";

            if($program_section != ""){
                $last_letter = substr($program_section, -1);

                $next_letter = chr(ord($last_letter) + 1);

                // echo $next_letter;
                // echo "<br>";
                $prefix = substr($program_section, 0, -1);
                // echo $prefix;
                // echo $prefix . $next_letter;
                return $prefix . $next_letter;
            }
        }
        public function AutoCreateAnotherSection($program_section){

            // STEM11-A -> STEM11-B
            // STEM11-C -> STEM11-D

            // $program_section = $row['program_section'];
            
            if($program_section != ""){
                $last_letter = substr($program_section, -1);

                $next_letter = chr(ord($last_letter) + 1);

                // echo $next_letter;
                // echo "<br>";
                $prefix = substr($program_section, 0, -1);
                // echo $prefix;
                // echo $prefix . $next_letter;
                return $prefix . $next_letter;
            }
        }

        public function RemoveSection($course_id){

            $sql = $this->con->prepare("DELETE FROM course
                WHERE course_id=:course_id");
                
            $sql->bindValue(":course_id", $course_id);
            return $sql->execute();
        }


        public function GetSectionNameByProgramId($program_id){

            $sql = $this->con->prepare("SELECT program_section FROM course
                WHERE program_id=:program_id");
                
            $sql->bindValue(":program_id", $program_id);
            $sql->execute();

            if($sql->rowCount() > 0)
                return $sql->fetchColumn();
            
            return "N/A";
        }

        public function GetProgramIdBySectionId($course_id){

            $sql = $this->con->prepare("SELECT program_id FROM course
                WHERE course_id=:course_id");
                
            $sql->bindValue(":course_id", $course_id);
            $sql->execute();

            if($sql->rowCount() > 0)
                return $sql->fetchColumn();
            
            return null;
        }

        public function GetSectionCapacity($course_id){

            $sql = $this->con->prepare("SELECT capacity FROM course
                WHERE course_id=:course_id");
                
            $sql->bindValue(":course_id", $course_id);
            $sql->execute();

            if($sql->rowCount() > 0)
                return $sql->fetchColumn();
            
            return null;
        }

        public function GetSectionNameByCourseId($course_id){

            $sql = $this->con->prepare("SELECT program_section FROM course
                WHERE course_id=:course_id");
                
            $sql->bindValue(":course_id", $course_id);
            $sql->execute();

            if($sql->rowCount() > 0)
                return $sql->fetchColumn();
            
            return "N/A";
        }

        public function CheckSetionExistsWithinCurrentSY($program_section, $school_year_term){

            $sql = $this->con->prepare("SELECT program_section FROM course
                WHERE program_section=:program_section
                AND school_year_term=:school_year_term
                ");
                
            $sql->bindValue(":program_section", $program_section);
            $sql->bindValue(":school_year_term", $school_year_term);
            $sql->execute();

            return $sql->rowCount() > 0;
        }

        public function GetAcronymByProgramId($program_id){

            $sql = $this->con->prepare("SELECT acronym FROM program
                WHERE program_id=:program_id");
                
            $sql->bindValue(":program_id", $program_id);
            $sql->execute();

            if($sql->rowCount() > 0)
                return $sql->fetchColumn();
            
            return "N/A";
        }

        public function GetDepartmentIdByProgramId($program_id){

            $sql = $this->con->prepare("SELECT department_id FROM program
                WHERE program_id=:program_id");
                
            $sql->bindValue(":program_id", $program_id);
            $sql->execute();

            if($sql->rowCount() > 0)
                return $sql->fetchColumn();
            
            return -1;
        }

        public function GetTrackByProgramId($program_id){

            $sql = $this->con->prepare("SELECT track FROM program
                WHERE program_id=:program_id");
                
            $sql->bindValue(":program_id", $program_id);
            $sql->execute();

            if($sql->rowCount() > 0)
                return $sql->fetchColumn();
            
            return "N/A";
        }

        public function CreateNewSection($new_section_name, 
            $program_id, $course_level, $current_school_year_term){
            
            // $sql = $this->con->prepare("INSERT INTO course
            //     (program_section, program_id, creationDate)
            //     WHERE program_id=:program_id");

            $active = "yes";
            $is_full = "no";


            $defaultGrade11StemStrand = $this->con->prepare("INSERT INTO course

                (program_section, program_id, course_level, capacity,
                    school_year_term, active, is_full)

                VALUES(:program_section, :program_id, :course_level, :capacity,
                    :school_year_term, :active, :is_full)");

            $defaultGrade11StemStrand->bindValue(":program_section", $new_section_name);

            $defaultGrade11StemStrand->bindValue(":program_id", $program_id, PDO::PARAM_INT);
            $defaultGrade11StemStrand->bindValue(":course_level", $course_level, PDO::PARAM_INT);
            $defaultGrade11StemStrand->bindValue(":capacity", 2);
            $defaultGrade11StemStrand->bindValue(":school_year_term", $current_school_year_term);
            $defaultGrade11StemStrand->bindValue(":active", $active);
            $defaultGrade11StemStrand->bindValue(":is_full", $is_full);

            // Check and handle duplication entry of
            // same program_section and school_year_term
           
            return $defaultGrade11StemStrand->execute();

        }

        public function SetSectionIsFull($course_id){

            $is_full = "yes";
            
            $update = $this->con->prepare("UPDATE course
                SET is_full=:is_full
                WHERE course_id=:course_id");
            
            $update->bindValue(":is_full", $is_full);
            $update->bindValue(":course_id", $course_id);

            return $update->execute();

        }


        public function CheckSectionIsFull($course_id){

            $sql = $this->con->prepare("SELECT is_full FROM course
                WHERE course_id=:course_id
                AND is_full='yes'");
                
            $sql->bindValue(":course_id", $course_id);
            $sql->execute();

            return $sql->rowCount() > 0;
        }

        public function CheckShiftedCourseIsFull($course_id,
            $current_school_year_id){

            $isFull = false;

            $totalStudentInSection = $this->GetTotalNumberOfStudentInSection($course_id,
                $current_school_year_id);

            $totalCurrentSectionCapacity = $this->GetSectionCapacity($course_id);

            if($totalStudentInSection >= $totalCurrentSectionCapacity){
                $isFull = true;
            }

            // $sql = $this->con->prepare("SELECT program_section FROM course
            //     WHERE course_id=:course_id
            //     AND is_full=:is_full
            //     ");
                
            // $sql->bindValue(":course_id", $course_id);
            // $sql->bindValue(":is_full", "yes");
            // $sql->execute();

            // return $sql->rowCount() > 0;
            
            return $isFull;
        }

        public function GetStrandrogramId($acronym){
            
            // $acronym = "HUMMS";
            // $program_name = "Humanities and Social Sciences";

            $sql = $this->con->prepare("SELECT program_id FROM program
                WHERE acronym=:acronym
                -- OR program_name=:program_name
                LIMIT 1");
                
            $sql->bindValue(":acronym", $acronym);
            // $sql->bindValue(":program_name", $program_name);
            $sql->execute();

            if($sql->rowCOunt() > 0){
                return $sql->fetchColumn();
            }
        }


        


        public function GetCurrentSYFullSection($school_year_term){
           
            $sql = $this->con->prepare("SELECT course_id FROM course
                WHERE is_full=:is_full
                AND school_year_term=:school_year_term
                ");
                
            $sql->bindValue(":is_full", "yes");
            $sql->bindValue(":school_year_term", $school_year_term);
            $sql->execute();

            if($sql->rowCount() > 0){

                $full = $sql->fetchAll(PDO::FETCH_ASSOC);
                return $full;
            }
            return [];
        }


        public function ResetSectionIsFull($school_year_term, $current_school_year_period){
            
            $isHit = false;
            if($current_school_year_period == "Second"){

                $fullSections = $this->GetCurrentSYFullSection($school_year_term);

                // var_dump($fullSections);

                $sql = $this->con->prepare("UPDATE course
                    SET is_full='no'
                    WHERE course_id=:course_id
                    ");
                foreach ($fullSections as $key => $value) {
                    # code...

                    $course_ids = $value['course_id'];

                    $sql->bindValue(":course_id", $course_ids);

                    if($sql->execute()){
                        $isHit = true;
                    }
                }
            }
            return $isHit;

        }

        public function GetEnrollmentSectionName($enrollment_id){


            $sql = $this->con->prepare("SELECT course_id FROM enrollment
                WHERE enrollment_id=:enrollment_id");

            $sql->bindValue(":enrollment_id", $enrollment_id);
            $sql->execute();

            if($sql->rowCount() > 0){

                $course_id = $sql->fetchColumn();

                $course = $this->con->prepare("SELECT program_section 
                    FROM course
                    WHERE course_id=:course_id
                    ");

                $course->bindValue(":course_id", $course_id);
                $course->execute();

                if($course->rowCount() > 0){

                    $program_section = $course->fetchColumn();
                    return $program_section;
                }
            }

            return "";
        }

        public function GetEnrollmentSectionId($enrollment_id){

            $sql = $this->con->prepare("SELECT course_id FROM enrollment
                WHERE enrollment_id=:enrollment_id");

            $sql->bindValue(":enrollment_id", $enrollment_id);
            $sql->execute();

            if($sql->rowCount() > 0){

                $course_id = $sql->fetchColumn();
                return $course_id;
            }

            return null;
        }

        public function GetEnrollmentStudentId($enrollment_id){

            $sql = $this->con->prepare("SELECT student_id FROM enrollment
                WHERE enrollment_id=:enrollment_id");

            $sql->bindValue(":enrollment_id", $enrollment_id);
            $sql->execute();

            if($sql->rowCount() > 0){

                $course_id = $sql->fetchColumn();
                return $course_id;
            }

            return null;
        }


        public function CheckSectionAligned($student_course_id, $student_course_level){

            $sql = $this->con->prepare("SELECT course_id FROM course
                WHERE course_id=:course_id
                AND course_level=:course_level");

            $sql->bindValue(":course_id", $student_course_id);
            $sql->bindValue(":course_level", $student_course_level);

            $sql->execute();

            if($sql->rowCount() > 0){
                return true;
            }

            return false;
        }

        public function GetMovedUpSectionId($tertiary_program_section,
            $school_year_term){

            $sql = $this->con->prepare("SELECT course_id FROM course
                WHERE program_section=:program_section
                AND school_year_term=:school_year_term");

            $sql->bindValue(":program_section", $tertiary_program_section);
            $sql->bindValue(":school_year_term", $school_year_term);

            $sql->execute();

            if($sql->rowCount() > 0){
                return $sql->fetchColumn();
            }

            return null;
        }

        public function TertiarySectionMovedUp($tertiary_program_section){
            $pattern = '/(\d+)/';
            $replacement = '${1}';

            $newString = preg_replace_callback($pattern, function($matches) {
                return intval($matches[0]) + 1;
            }, $tertiary_program_section);
        
            return $newString;
        }

        public function CheckIfProgramHasSection($program_id, $school_year_term){

            $sql = $this->con->prepare("SELECT * FROM course
                WHERE program_id=:program_id
                AND school_year_term=:school_year_term");

            $sql->bindValue(":program_id", $program_id);
            $sql->bindValue(":school_year_term", $school_year_term);
            $sql->execute();
      
            
            // if($sql->rowCount() > 0){
            //     echo "true";
            // }else{
            //     echo "false";
            // }
            return $sql->rowCount() > 0;
        }
    }
?>