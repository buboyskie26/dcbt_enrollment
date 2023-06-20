<?php

class AdminUser{

    private $con, $sqlData;

    public function __construct($con, $input)
    {
        $this->con = $con;
        $this->sqlData = $input;

        // echo "hey";
        // print_r($input);
        if(!is_array($input)){
            $query = $this->con->prepare("SELECT * FROM users
            WHERE username=:username");

            $query->bindValue(":username", $input);
            $query->execute();

            $this->sqlData = $query->fetch(PDO::FETCH_ASSOC);
        }
    }
                // allowEscapeKey: false,

    public static function success($text, $redirectUrl) {
        echo "<script>
            Swal.fire({
                icon: 'success',
                title: 'Success!',
                text: '$text',
                backdrop: false,

            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = '$redirectUrl';
                }
            });
        </script>";
    }
    public static function error($text, $redirectUrl) {
        echo "<script>
            Swal.fire({
                icon: 'error',
                title: 'Oh no!',
                text: '$text'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = '$redirectUrl';
                }
            });
        </script>";
    }
    public static function remove($text, $redirectUrl) {
        echo "<script>
            Swal.fire({
                icon: 'error',
                title: 'Removal!',
                text: '$text'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = '$redirectUrl';
                }
            });
        </script>";
    }

    public static function confirm($text, $redirectUrl) {
        echo "
            <script>
                Swal.fire({
                    icon: 'success',
                    title: 'Success!',
                    text: '$text',
                    showCancelButton: true,
                    confirmButtonText: 'OK'
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.location.href = '$redirectUrl';
                    }
                });
            </script>
        ";

    }
    

    public function GetId() {
        return isset($this->sqlData['user_id']) ? $this->sqlData["user_id"] : 0; 
    }
    public function GetUsername() {
        return isset($this->sqlData['username']) ? $this->sqlData["username"] : ""; 
    }
    public function GetFirstName() {
        return isset($this->sqlData['firstName']) ? $this->sqlData["firstName"] : ""; 
    }
    public function GetName() {
        return $this->sqlData["firstName"] . " " . $this->sqlData["lastName"];
    }
    public function GetLastName() {
        return isset($this->sqlData['lastName']) ? $this->sqlData["lastName"] : ""; 
    }

    public static function IsAuthenticated(){
        return isset($_SESSION['adminLoggedIn']);
    }
      public static function IsCashierAuthenticated(){
        return isset($_SESSION['cashierLoggedIn']);
    }

    public static function IsRegistrarAuthenticated(){
        return isset($_SESSION['registrarLoggedIn']);
    }

    public static function IsTeacherAuthenticated(){
        return isset($_SESSION['teacherLoggedIn']);
    }

    public static function IsStudentAuthenticated(){
        return isset($_SESSION['username']);
    }


    public static function IsStudentEnrolledAuthenticated(){
        return isset($_SESSION['username']) 
            && isset($_SESSION['status']) && $_SESSION['status'] == "enrolled";
    }

    public static function IsStudentPendingAuthenticated(){
        return isset($_SESSION['username']) 
            && isset($_SESSION['status']) && $_SESSION['status'] == "pending";
    }

    public function createForm(){
        $createCourseCategory = $this->createCourseCategory();
        return "
                <form action='student.php' method='POST'>
                    <div class='form-group'>
                    
                        $createCourseCategory
                        <input class='form-control' type='text' 
                            placeholder='ID Number' name='username'>
                        <input class='form-control' type='text' 
                            placeholder='First Name' name='firstname'>
                        <input class='form-control' type='text' 
                            placeholder='Last Name' name='lastname'>
                    </div>

                    <button type='submit' class='btn btn-primary' name='submit_student'>Save</button>
                </form>
            ";
    }

    public function insertStudent($course_id, $username, $firstname, $lastname){
            
        // Check if the subject already entered.

        $query = $this->con->prepare("INSERT INTO student(course_id, username,firstname,lastname)
            VALUES(:course_id, :username,:firstname,:lastname)");
        
        $query->bindValue(":course_id", $course_id);
        $query->bindValue(":username", $username);
        $query->bindValue(":firstname", $firstname);
        $query->bindValue(":lastname", $lastname);

        return $query->execute();
    }
    private function createCourseCategory(){

        $query = $this->con->prepare("SELECT * FROM course");
        $query->execute();

            $html = "<div class='form-group'>
                    <select class='form-control' name='course_id'>";

            while($row = $query->fetch(PDO::FETCH_ASSOC)){
                $html .= "
                    <option value='".$row['course_id']."'>".$row['course_name']."</option>
                ";
            }
            $html .= "</select>
                    </div>";
            return $html;
    }
    
    public function createTable(){

        $enroll = new StudentEnroll($this->con);
        $school_year_obj = $enroll->GetActiveSchoolYearAndSemester();

        $school_year_id = null;

        if($school_year_obj == null){
            echo "System doesnt have Active School Year.";
            exit();
        }
        
        $school_year_id = $school_year_obj['school_year_id'];
        $current_school_period = $school_year_obj['period'];
        $current_school_term = $school_year_obj['term'];
        $current_school_year_id = $school_year_obj['school_year_id'];

        if(isset($_POST['set_year_semester']) 
            && isset( $_POST['school_year_id_btn']) ){

            $school_year_id_btn = $_POST['school_year_id_btn'];

            // echo $school_year_id_btn . " school_year_id_btn";
            // echo $school_year_id;
            $status_active = "Active";
            $current_status = "InActive";

            // Get the prev school_yerm term before the changing of new school_year term.
            # We have this function already.

            $current_school_year_term = null;
            $get_current_school_year_term = $this->con->prepare("SELECT term, period FROM school_year
                    WHERE statuses=:statuses
                    LIMIT 1");

            $get_current_school_year_term->bindValue(":statuses", $status_active);
            $get_current_school_year_term->execute();

            if($get_current_school_year_term->rowCount() > 0){
                $current_term_row = $get_current_school_year_term->fetch(PDO::FETCH_ASSOC);

                $current_school_year_term = $current_term_row['term'];
                $current_school_semester = $current_term_row['period'];
            }

            // echo $current_school_year_term;
            // echo $current_school_semester;

            // The previous active becomes inactive
            $update_normalized = $this->con->prepare("UPDATE school_year
                    SET statuses=:statuses
                    WHERE statuses=:current_status
                    AND school_year_id=:school_year_id");

            $update_normalized->bindValue(":statuses", "InActive");
            $update_normalized->bindValue(":current_status", "Active");
            $update_normalized->bindValue(":school_year_id", $school_year_id);
            $update_normalized->execute();

            // (Setting a new school_year term) the click inactive become active.
            $update_year = $this->con->prepare("UPDATE school_year
                    SET statuses=:statuses
                    WHERE statuses=:current_status
                    AND school_year_id=:school_year_id");

            $update_year->bindValue(":statuses", $status_active);
            $update_year->bindValue(":current_status", $current_status);
            $update_year->bindValue(":school_year_id", $school_year_id_btn);
            $update_year->execute();

            if($update_year->execute() && $current_school_year_term != null){

                $select_recently_term = $this->con->prepare("SELECT term FROM school_year
                        WHERE statuses=:statuses");
                $select_recently_term->bindValue(":statuses", $status_active);
                $select_recently_term->execute();

                // comes from update_year execution
                $new_school_year_term = $select_recently_term->rowCount() > 0 ? $select_recently_term->fetchColumn() : null;

                // echo "new_school_year_term=" . $new_school_year_term;
                // echo "School year have changed ";
                // echo "<br>";

                $course_level = 11;
                $active = "yes";

                // Will execute if changing FROM SECOND to FIRST Semester.
                $get_course_level_eleven = $this->con->prepare("SELECT * FROM course
                    WHERE course_level=:course_level
                    AND school_year_term=:school_year_term
                    AND active=:active");

                $get_course_level_eleven->bindValue(":course_level", $course_level);
                $get_course_level_eleven->bindValue(":school_year_term", $current_school_year_term);
                $get_course_level_eleven->bindValue(":active", $active);
                $get_course_level_eleven->execute();

                // echo " $current_school_year_term --";
                // echo "<br>";
             

                # Algorithm for Moving Up the Current Tertiary Section Based on the Current Term (2021-2022)
                # As registrar selects new School Year From Second Sem(S.Y2021) To (S.Y2022)First Sem

                # 1. Select the current Section(s) based in todays S.Y
                # 2. Loop each section. Update Section active='yes' column into active='no'
                # 3. For every update of no.2, Create a newly tertiary section that will move-up the program_section (from ABE-1A to ABE-2A)
                # 4. Get the newly created course_tertiary_id
                # 5. Get the course_tertiary_id, course_level, program_id column on that newly created section
                # 6. Get All Subject_Program Table referencing the program_id and course_level
                # 7. Insert the subject_tertiary table that referenced the necessary column of Subject_Program Table (subject_title, subject_code etc)
                # 8. In just changing the S.Y from 2nd sem to 1st sem. We created individual newly section based on the previous active tertiary_course section
                # Which we have included its appropriate subjects.

                
                $active_update = "no";

                # For SHS automatically created move_up course section
                # TODO. Automatically populating course subject is NOT SUPPORTED YET

                // if(false){
                    
                // Creates new section from (HUMMSS11-A, STEM11-A) to (HUMMSS12-A, STEM12-A)
                if($get_course_level_eleven->rowCount() > 0 && $current_school_semester == "Second"){

                    $gradeElevenCourses = $get_course_level_eleven->fetchAll(PDO::FETCH_ASSOC);

                    $moveUpCourseLevel = 12;
                    $capacity = 2;
                    $total_student = 0;
                    // Must be the new set school_year term.
                    $school_year_term = 0;
                    $active = "yes";
                    $is_full = "no";

                    $new_number = $moveUpCourseLevel;
                    

                    $update = $this->con->prepare("UPDATE course
                        SET active=:active
                        WHERE course_level=:course_level
                        AND course_id=:course_id");

                    $moveUpGradeSection = $this->con->prepare("INSERT INTO course
                            (program_section, program_id, course_level, capacity, school_year_term, active, is_full, previous_course_id)
                            VALUES(:program_section, :program_id, :course_level, :capacity, :school_year_term, :active, :is_full, :previous_course_id)");

                    $last_inserted_id = null;

                    $insert_section_subject = $this->con->prepare("INSERT INTO subject
                        (subject_title, description, subject_program_id, unit, semester, program_id, course_level, course_id, subject_type, subject_code)
                        VALUES(:subject_title, :description, :subject_program_id, :unit, :semester, :program_id, :course_level, :course_id, :subject_type, :subject_code)");

                    foreach ($gradeElevenCourses as $key => $value) {
                        
                        $program_section = $value['program_section'];
                        $program_id = $value['program_id'];
                        $course_id = $value['course_id'];
                        $previous_course_id = $value['course_id'];
                        
                        // $new_program_section = preg_replace('/(?<=HUMMS)\d+/', '12', $program_section);
                        $new_program_section = str_replace('11', $new_number, $program_section);


                        $update->bindValue(":active", $active_update);
                        $update->bindValue(":course_level", $course_level);
                        $update->bindValue(":course_id", $course_id);

                        if($update->execute()){
                            // echo "Grade 11 Section $program_section is de-activated";
                            // echo "<br>";
 
                            $moveUpGradeSection->bindValue(":program_section", $new_program_section);
                            $moveUpGradeSection->bindValue(":program_id", $program_id);
                            $moveUpGradeSection->bindValue(":course_level", $moveUpCourseLevel);
                            $moveUpGradeSection->bindValue(":capacity", $capacity);
                            $moveUpGradeSection->bindValue(":school_year_term", $new_school_year_term);
                            $moveUpGradeSection->bindValue(":active", "yes");
                            $moveUpGradeSection->bindValue(":is_full", "no");
                            $moveUpGradeSection->bindValue(":previous_course_id", $previous_course_id);
                            // Check and handle duplication entry of
                            // same program_section and school_year_term
                            if($moveUpGradeSection->execute()){
                                // echo "New Grade $moveUpCourseLevel $new_program_section  section is established at new $new_school_year_term";
                                // echo "<br>";
                                
                                // All Updated Id needs to insert an subjects.
                                $last_inserted_id = $this->con->lastInsertId();

                            }
                        }
                    }
                    
                    // Must insert the client strand offered, adjustable.
                    // (HUMMS,ABM,GAS,TVL)
                    $stem_program_id = 3;
                    $stem_program_section = "STEM11-A";
                    $humms_program_id = 4;
                    $humms_program_section = "HUMSS11-A";

                    if(true){
                        
                        $defaultGrade11StemStrand = $this->con->prepare("INSERT INTO course
                            (program_section, program_id, course_level, capacity, school_year_term, active, is_full)
                            VALUES(:program_section, :program_id, :course_level, :capacity, :school_year_term, :active, :is_full)");

                        $defaultGrade11StemStrand->bindValue(":program_section", $stem_program_section);
                        $defaultGrade11StemStrand->bindValue(":program_id", $stem_program_id, PDO::PARAM_INT);
                        $defaultGrade11StemStrand->bindValue(":course_level", $course_level, PDO::PARAM_INT);
                        $defaultGrade11StemStrand->bindValue(":capacity", $capacity);
                        $defaultGrade11StemStrand->bindValue(":school_year_term", $new_school_year_term);
                        $defaultGrade11StemStrand->bindValue(":active", $active);
                        $defaultGrade11StemStrand->bindValue(":is_full", $is_full);

                        // Check and handle duplication entry of
                        // same program_section and school_year_term
                        if($defaultGrade11StemStrand->execute()){
                            // echo "<br>";
                            // echo "New Grade $course_level $stem_program_section section is established at new $new_school_year_term";
                            // echo "<br>";
                        }
                    }

                    if(true){
                        $defaultGrade11HummsStrand = $this->con->prepare("INSERT INTO course
                            (program_section, program_id, course_level, capacity, school_year_term, active, is_full)
                            VALUES(:program_section, :program_id, :course_level, :capacity, :school_year_term, :active, :is_full)");

                        $defaultGrade11HummsStrand->bindValue(":program_section", $humms_program_section);
                        $defaultGrade11HummsStrand->bindValue(":program_id", $humms_program_id, PDO::PARAM_INT);
                        $defaultGrade11HummsStrand->bindValue(":course_level", $course_level, PDO::PARAM_INT);
                        $defaultGrade11HummsStrand->bindValue(":capacity", $capacity);
                        $defaultGrade11HummsStrand->bindValue(":school_year_term", $new_school_year_term);
                        $defaultGrade11HummsStrand->bindValue(":active", $active);
                        $defaultGrade11HummsStrand->bindValue(":is_full", $is_full);

                        // Check and handle duplication entry of
                        // same program_section and school_year_term
                        if($defaultGrade11HummsStrand->execute()){
                            // echo "<br>";
                            // echo "New Grade $course_level $humms_program_section section is established at new $new_school_year_term";
                            // echo "<br>";
                        }
                    }

                    # TODO: It should inactive only if the second semester changed
                    # into first semester.
                    $GRADE_TWELVE = 12;
                    // Get all Grade 12 Sections and Mark them as Inactive.

                    $update_old_section = $this->con->prepare("UPDATE course
                        SET active=:active
                        WHERE course_level=:course_level
                        AND school_year_term=:school_year_term
                        AND active=:current_status ");

                    $update_old_section->bindValue(":active", $active_update);
                    $update_old_section->bindValue(":course_level", $GRADE_TWELVE);
                    $update_old_section->bindValue(":school_year_term", $current_school_year_term);
                    $update_old_section->bindValue(":current_status", $active);
                    $update_old_section->execute();

                }


            }
        }
        

        if(isset($_POST['end_enrollment_btn'])){

            $student_id = "";
            // All student enrolled in last semester will be deactived.

            // Current semester is Second
            // Check student in the first semester who have enrolled
            // and did not enrolled in this Current Semester.

            // if($current_school_period == "Second"){

            //     $asd = " SELECT e.*
            //         FROM enrollment e
            //         JOIN school_year s ON e.school_year_id = s.school_year_id
            //         WHERE s.period = 'First'
            //         AND e.school_year_id.period = 'First'
            //         ";

            //     $ah = $this->con->prepare("SELECT e.student_id
            //         FROM enrollment e

            //         WHERE e.school_year_id IN (
            //         SELECT s.school_year_id
            //         FROM school_year s
            //         WHERE s.period = 'First'
            //         AND s.term=:term
            //         -- AND e.student_id !=:student_id
            //         )");
                
            //     $ah->bindValue(":term", $current_school_term);
            //     // $ah->bindValue(":student_id", $student_id);
            //     $ah->execute();

            //     $row1 = $ah->fetchAll(PDO::FETCH_COLUMN);

            //     print_r($row1);
            //     echo "<br>";

            //     // Get all second semester enrolled
            //     $get_student_current_sy = $this->con->prepare("SELECT student_id FROM enrollment
            //         -- student_id=:student_id
            //         WHERE school_year_id=:school_year_id
            //         AND enrollment_status=:enrollment_status
            //         ");
            //     $get_student_current_sy->bindValue(":school_year_id", $school_year_id);
            //     $get_student_current_sy->bindValue(":enrollment_status", "tentative");
            //     $get_student_current_sy->execute();

            //     $row2 = $get_student_current_sy->fetchAll(PDO::FETCH_COLUMN);

            //     echo "<br>";
            //     print_r($row2);

            //     echo "<br>";
            //     echo "<br>";

            //     $not_enrolled_second_sem = array_diff($row1, $row2);

            //     print_r($not_enrolled_second_sem);
                
            //     $status_drop = "Drop";
            //     $regular_status = "Regular";
            //     $transferee_status = "Transferee";
            //     $dropped_status = "Drop";

            //     $update_dropped_student = $this->con->prepare("UPDATE student
            //         SET student_status=:update_status

            //         WHERE student_id=:student_id
            //         AND student_status=:regular_status
            //         OR student_id=:student_id
            //         AND student_status=:transferee_status
            //         ");

            //       // Get all second semester enrolled
            //     $check_already_dropped = $this->con->prepare("SELECT student_id FROM student
            //         WHERE student_id=:student_id
            //         AND student_status != :dropped_status
            //         ");


            //     // Enrolled for 1st semester
            //     // Did not enroll in this current second semester
            //     foreach ($not_enrolled_second_sem as $key => $value) {
            //         # code...
            //         // echo "not enrolled $value";

            //         $check_already_dropped->bindValue(":student_id", $value);
            //         $check_already_dropped->bindValue(":dropped_status", $dropped_status);
            //         $check_already_dropped->execute();

            //         if($check_already_dropped->rowCount() > 0){

            //             // echo "thats student $value is not dropped and neede to be dropped";
            //             // echo "<br>";
            //             $update_dropped_student->bindValue(":update_status", $status_drop);
            //             $update_dropped_student->bindValue(":student_id", $value);
            //             $update_dropped_student->bindValue(":regular_status", $regular_status);
            //             $update_dropped_student->bindValue(":transferee_status", $transferee_status);

            //             if($update_dropped_student->execute()){
            //                 echo "Student $value becomes drop";
            //                 echo "<br>";
            //             }
            //         }else{
            //             echo "no active student had drop";
            //         }
            //     }
            // }else{
            //     // echo "The system semester is not Second Semester";
            // }
            
            
            # From 1st Semester to Start of Second Semester
            # Grade 11 1st sem to Grade 11 2nd sem.
            # Grade 12 1st sem to Grade 12 2nd sem.
            
            // if($current_school_period == "Second"){

            //     $previos_sy_id = $this->con->prepare("SELECT school_year_id FROM school_year 
            //         WHERE school_year_id < (SELECT school_year_id FROM school_year WHERE statuses = 'Active') ORDER BY school_year_id DESC LIMIT 1
            //     ");

            //     $previos_sy_id->execute();

            //     $enrolled_prev_student_arr = [];
            //     $enrolled_current_student_arr = [];

            //     if($previos_sy_id->rowCount() > 0 && $current_school_period == "First"){
            //         $previous_school_year_id = $previos_sy_id->fetchColumn();

            //         // echo $previous_school_year_id;

            //         $enrollment_status = "enrolled";

            //         $enrollment_previous_sy_id = $this->con->prepare("SELECT student_id FROM enrollment 
            //             WHERE enrollment_status=:enrollment_status
            //             AND school_year_id=:school_year_id
            //         ");

            //         $enrollment_previous_sy_id->bindValue(":enrollment_status", $enrollment_status);
            //         $enrollment_previous_sy_id->bindValue(":school_year_id", $previous_school_year_id);
            //         $enrollment_previous_sy_id->execute();

            //         if($enrollment_previous_sy_id->rowCount() > 0){
            //             while($row = $enrollment_previous_sy_id->fetch(PDO::FETCH_ASSOC)){
            //                 array_push($enrolled_prev_student_arr, $row['student_id']);
            //             }
            //         }

            //         $enrollment_current_sy_id = $this->con->prepare("SELECT student_id FROM enrollment 
            //             WHERE enrollment_status=:enrollment_status
            //             AND school_year_id=:school_year_id
            //         ");

            //         $enrollment_current_sy_id->bindValue(":enrollment_status", $enrollment_status);
            //         $enrollment_current_sy_id->bindValue(":school_year_id", $current_school_year_id);
            //         $enrollment_current_sy_id->execute();

            //         if($enrollment_current_sy_id->rowCount() > 0){
            //             while($row = $enrollment_current_sy_id->fetch(PDO::FETCH_ASSOC)){
            //                 array_push($enrolled_current_student_arr, $row['student_id']);
            //             }
            //         }

            //         $student_did_not_enrolled_now_sy = array_diff($enrolled_prev_student_arr, $enrolled_current_student_arr);
                
            //         $active_status = 1;
            //         $non_active = 0;
            //         $update_dropped_student = $this->con->prepare("UPDATE student

            //             SET active=:update_status

            //             WHERE student_id=:student_id
            //             AND student_status=:regular_status
            //             AND active=:active_status

            //             OR student_id=:student_id
            //             AND student_status=:transferee_status
            //             AND active=:active_status

            //             ");

            //         $stopped_status = "Stopped";
            //         $in_active_status = "no";
            //         $regular_status = "Regular";
            //         $transferee_status = "Transferee";

            //         $reason = "Student Had Reached the Enrollment Data";
            //         $description = "If you want to enroll, Please walk in to registrar.";
                    
            //         // Enrolled for 1st semester
            //         // Did not enroll in this current second semester.

            //         foreach ($student_did_not_enrolled_now_sy as $key => $value) {

            //             $update_dropped_student->bindValue(":update_status", $non_active);
            //             $update_dropped_student->bindValue(":student_id", $value);

            //             $update_dropped_student->bindValue(":regular_status", $regular_status);
            //             $update_dropped_student->bindValue(":transferee_status", $transferee_status);
            //             $update_dropped_student->bindValue(":active_status", $active_status);

            //             if($update_dropped_student->execute()){
            //                 echo "Student $value is set to stopped";
            //                 echo "<br>";
            //                 // Put that information to the provided table for reference.

            //                 $insert = $this->con->prepare("INSERT INTO student_inactive_reason
            //                     (student_id, reason_title, description)
            //                     -- student_status, current_course_id, current_course_level
            //                     VALUES(:student_id, :reason_title, :description)");
            //                     // :student_status, :current_course_id,:current_course_level
                            
            //                 $insert->bindValue(":student_id", $value);
            //                 $insert->bindValue(":reason_title", $reason);
            //                 $insert->bindValue(":description", $description);
            //                 // $insert->bindValue(":student_status", $description);
            //                 // $insert->bindValue(":current_course_id", $description);
            //                 // $insert->bindValue(":current_course_level", $description);
            //                 $insert->execute();
            //             }

            //         }
            //     } 

            // }


            # Before system reached the end of enrollment
            # registrar should enrolled all tentative enrollees.
            # Dropping Logic.
            if($current_school_period == "First"){

                $previos_sy_id = $this->con->prepare("SELECT school_year_id FROM school_year 
                    WHERE school_year_id < (SELECT school_year_id FROM school_year WHERE statuses = 'Active') ORDER BY school_year_id DESC LIMIT 1
                ");

                $previos_sy_id->execute();

                $enrolled_prev_student_arr = [];
                $enrolled_current_student_arr = [];

                if($previos_sy_id->rowCount() > 0 && $current_school_period == "First"){

                    $previous_school_year_id = $previos_sy_id->fetchColumn();

                    if($previous_school_year_id != false){
                    // echo $previous_school_year_id;
                        $enrollment_status = "enrolled";

                        $enrollment_previous_sy_id = $this->con->prepare("SELECT student_id FROM enrollment 
                            WHERE enrollment_status=:enrollment_status
                            AND school_year_id=:school_year_id
                        ");

                        $enrollment_previous_sy_id->bindValue(":enrollment_status", $enrollment_status);
                        $enrollment_previous_sy_id->bindValue(":school_year_id", $previous_school_year_id);
                        $enrollment_previous_sy_id->execute();

                        if($enrollment_previous_sy_id->rowCount() > 0){
                            while($row = $enrollment_previous_sy_id->fetch(PDO::FETCH_ASSOC)){
                                array_push($enrolled_prev_student_arr, $row['student_id']);
                            }
                        }

                        $enrollment_current_sy_id = $this->con->prepare("SELECT student_id FROM enrollment 
                            WHERE enrollment_status=:enrollment_status
                            AND school_year_id=:school_year_id
                        ");

                        $enrollment_current_sy_id->bindValue(":enrollment_status", $enrollment_status);
                        $enrollment_current_sy_id->bindValue(":school_year_id", $current_school_year_id);
                        $enrollment_current_sy_id->execute();

                        if($enrollment_current_sy_id->rowCount() > 0){
                            while($row = $enrollment_current_sy_id->fetch(PDO::FETCH_ASSOC)){
                                array_push($enrolled_current_student_arr, $row['student_id']);
                            }
                        }

                        $student_did_not_enrolled_now_sy = array_diff($enrolled_prev_student_arr, $enrolled_current_student_arr);
                    
                        $active_status = 1;
                        $non_active = 0;
                        $update_dropped_student = $this->con->prepare("UPDATE student

                            SET active=:update_status

                            WHERE student_id=:student_id
                            AND student_status=:regular_status
                            AND active=:active_status

                            OR student_id=:student_id
                            AND student_status=:transferee_status
                            AND active=:active_status

                            ");

                        $stopped_status = "Stopped";
                        $in_active_status = "no";
                        $regular_status = "Regular";
                        $transferee_status = "Transferee";

                        $reason = "Student Had Reached the Enrollment Data";
                        $description = "If you want to enroll, Please walk in to registrar.";
                        
                        // Enrolled for 1st semester
                        // Did not enroll in this current second semester.

                        foreach ($student_did_not_enrolled_now_sy as $key => $value) {

                            $update_dropped_student->bindValue(":update_status", $non_active);
                            $update_dropped_student->bindValue(":student_id", $value);

                            $update_dropped_student->bindValue(":regular_status", $regular_status);
                            $update_dropped_student->bindValue(":transferee_status", $transferee_status);
                            $update_dropped_student->bindValue(":active_status", $active_status);

                            if(false){
                            // if($update_dropped_student->execute()){
                                echo "Student $value is set to stopped";
                                echo "<br>";
                                // Put that information to the provided table for reference.

                                $insert = $this->con->prepare("INSERT INTO student_inactive_reason
                                    (student_id, reason_title, description)
                                    -- student_status, current_course_id, current_course_level
                                    VALUES(:student_id, :reason_title, :description)");
                                    // :student_status, :current_course_id,:current_course_level
                                
                                $insert->bindValue(":student_id", $value);
                                $insert->bindValue(":reason_title", $reason);
                                $insert->bindValue(":description", $description);
                                // $insert->bindValue(":student_status", $description);
                                // $insert->bindValue(":current_course_id", $description);
                                // $insert->bindValue(":current_course_level", $description);
                                $insert->execute();
                            }

                        }
                    }else{
                        var_dump($previous_school_year_id);
                    }
                } 
            }

            if($current_school_period == "Second"){

                $previos_sy_id = $this->con->prepare("SELECT school_year_id FROM school_year 
                    WHERE school_year_id < (SELECT school_year_id FROM school_year WHERE statuses = 'Active') ORDER BY school_year_id DESC LIMIT 1
                ");

                $previos_sy_id->execute();

                $enrolled_prev_student_arr = [];
                $enrolled_current_student_arr = [];

                if($previos_sy_id->rowCount() > 0 && $current_school_period == "Second"){

                    $previous_school_year_id = $previos_sy_id->fetchColumn();

                    // echo $previous_school_year_id;

                    // echo $previous_school_year_id;

                    $enrollment_status = "enrolled";

                    // Get all enrolled & tentative student_id based on the previous_school_year_id
                    $enrollment_previous_sy_id = $this->con->prepare("SELECT student_id FROM enrollment 
                        WHERE enrollment_status=:enrollment_status
                        AND school_year_id=:school_year_id
                    ");

                    $enrollment_previous_sy_id->bindValue(":enrollment_status", $enrollment_status);
                    $enrollment_previous_sy_id->bindValue(":school_year_id", $previous_school_year_id);
                    $enrollment_previous_sy_id->execute();

                    if($enrollment_previous_sy_id->rowCount() > 0){
                        while($row = $enrollment_previous_sy_id->fetch(PDO::FETCH_ASSOC)){
                            array_push($enrolled_prev_student_arr, $row['student_id']);
                        }
                    }

                    // Get all enrolled student_id based on the current_school_year_id
                    $enrollment_current_sy_id = $this->con->prepare("SELECT student_id FROM enrollment 
                        WHERE enrollment_status=:tentative_enrollment_status
                        AND school_year_id=:school_year_id

                        OR enrollment_status=:enrolled_enrollment_status
                        AND school_year_id=:school_year_id
                    ");

                    $enrollment_current_sy_id->bindValue(":tentative_enrollment_status", "tentative");
                    $enrollment_current_sy_id->bindValue(":enrolled_enrollment_status", $enrollment_status);
                    $enrollment_current_sy_id->bindValue(":school_year_id", $current_school_year_id);
                    $enrollment_current_sy_id->execute();

                    if($enrollment_current_sy_id->rowCount() > 0){
                        while($row = $enrollment_current_sy_id->fetch(PDO::FETCH_ASSOC)){
                            array_push($enrolled_current_student_arr, $row['student_id']);
                        }
                    }

                    $student_did_not_enrolled_now_sy = array_diff($enrolled_prev_student_arr, $enrolled_current_student_arr);
                
                    print_r($student_did_not_enrolled_now_sy);
                    
                    $active_status = 1;
                    $non_active = 0;
                    $update_dropped_student = $this->con->prepare("UPDATE student

                        SET active=:update_status

                        WHERE student_id=:student_id
                        AND student_status=:regular_status
                        AND active=:active_status

                        OR student_id=:student_id
                        AND student_status=:transferee_status
                        AND active=:active_status

                        ");

                    $stopped_status = "Stopped";
                    $in_active_status = "no";
                    $regular_status = "Regular";
                    $transferee_status = "Transferee";

                    $reason = "Student Had Reached the Enrollment Data";
                    $description = "If you want to enroll, Please walk in to registrar.";
                    
                    // Enrolled for 1st semester
                    // Did not enroll in this current second semester.

                    foreach ($student_did_not_enrolled_now_sy as $key => $value) {

                        $update_dropped_student->bindValue(":update_status", $non_active);
                        $update_dropped_student->bindValue(":student_id", $value);

                        $update_dropped_student->bindValue(":regular_status", $regular_status);
                        $update_dropped_student->bindValue(":transferee_status", $transferee_status);
                        $update_dropped_student->bindValue(":active_status", $active_status);

                        // if(false){
                        if($update_dropped_student->execute()){
                            echo "Student $value is set to stopped";
                            echo "<br>";
                            // Put that information to the provided table for reference.

                            $insert = $this->con->prepare("INSERT INTO student_inactive_reason
                                (student_id, reason_title, description)
                                -- student_status, current_course_id, current_course_level
                                VALUES(:student_id, :reason_title, :description)");
                                // :student_status, :current_course_id,:current_course_level
                            
                            $insert->bindValue(":student_id", $value);
                            $insert->bindValue(":reason_title", $reason);
                            $insert->bindValue(":description", $description);
                            // $insert->bindValue(":student_status", $description);
                            // $insert->bindValue(":current_course_id", $description);
                            // $insert->bindValue(":current_course_level", $description);
                            $insert->execute();
                        }

                    }
                } 

            }

        }

        if(isset($_POST['start_enrollment_date_btn'])){

            // echo "post";
            $school_year_id = $_POST['school_year_idx'];

            $currentDateTime = new DateTime();
            $current_time = $currentDateTime->format('Y-m-d H:i:s');

            $update = $this->con->prepare("UPDATE school_year
                SET start_enrollment_date=:start_enrollment_date
                WHERE school_year_id=:school_year_id
                AND statuses='Active'
                ");

            $update->bindValue(":start_enrollment_date", $current_time);
            $update->bindValue(":school_year_id", $school_year_id);
            $update->execute();
        }

        if(isset($_POST['end_enrollment_date_btn'])){

            $school_year_id = $_POST['school_year_idx'];

            $currentDateTime = new DateTime();
            $current_time = $currentDateTime->format('Y-m-d H:i:s');

            $update = $this->con->prepare("UPDATE school_year
                SET end_enrollment_date=:end_enrollment_date
                WHERE school_year_id=:school_year_id
                AND statuses='Active'
                
                ");

            $update->bindValue(":end_enrollment_date", $current_time);
            $update->bindValue(":school_year_id", $school_year_id);
            $update->execute();
        }

        if(isset($_POST['end_period_btn'])){

            $school_year_id = $_POST['school_year_idx'];

            $currentDateTime = new DateTime();
            $current_time = $currentDateTime->format('Y-m-d H:i:s');

            $update = $this->con->prepare("UPDATE school_year
                SET end_period=:end_period
                WHERE school_year_id=:school_year_id
                AND statuses='Active'
                ");

            $update->bindValue(":end_period", $current_time);
            $update->bindValue(":school_year_id", $school_year_id);
            $update->execute();
        }
        

        $table = "
            <div class='card'>

                <div class='card-header'>
                    <h6>System S.Y $current_school_term $current_school_period Semester</h6>
                    <h3 class='text-center'>School Year Maintenance</h3>

                </div>

                <div class='card-body'>
                    <form style='display: none;' method='POST'>
                        <button name='end_enrollment_btn' type='submit'class='btn btn-sm btn-success'>End Enrollment</button>
                    </form>
                    <table class='table table-hover'>
                        <thead >
                            <tr class='text-center'>
                                <th>Year</th>
                                <th>Semester</th>
                                <th style='width:350px;'>Action</th>
                            </tr>
                        </thead>


        ";

        $get_school_year = $this->con->prepare("SELECT * FROM school_year
            -- WHERE school_year_id >= :school_year_id
        ");

        // $get_school_year->bindValue(":school_year_id", $school_year_id);
        $get_school_year->execute();

        if($get_school_year->rowCount() > 0){
            while($row = $get_school_year->fetch(PDO::FETCH_ASSOC)){
                $table .= $this->GenerateTableBody($row);
            }
        }
        $table .= "
                    </table>
                </div>
            </div>
        ";
        return $table;
    }

    private function GenerateTableBody($row){
      
        $school_year_id = $row['school_year_id'];
        $period = $row['period'];
        $school_year_term = $row['term'];
        $isActive = $row['statuses'];

        $maintenance_button = "
            <a href='sy_maintenance.php?id=$school_year_id'>
                <button name='school_year_maintenance_btn' 
                    type='submit' class='btn btn-outline-sm btn-success'>
                    <i class='fas fa-plus'></i>
                </button>
            </a>

        ";

        $start_enrollment_date_btn = "
            <button name='start_enrollment_date_btn' 
                type='submit' class='btn btn-success btn-sm'>
                SED
            </button>
            <input type='hidden' name='school_year_idx' value='".$row['school_year_id']."'>
        ";

        $end_enrollment_date_btn = "
            <button name='end_enrollment_date_btn' 
                type='submit' class='btn btn-outline-warning btn-sm'>
                EED
            </button>
            <input type='hidden' name='school_year_idx' value='".$row['school_year_id']."'>
        ";

        $end_period_btn = "
            <button name='end_period_btn' 
                type='submit' class='btn btn-secondary btn-sm'>
                EP
            </button>
            <input type='hidden' name='school_year_idx' value='".$row['school_year_id']."'>
        ";

        // Todo for changing the school year semester.
        $button = "
            <form method='POST' name='set_year_semester'>

                <button name='set_year_semester'   type='submit' class='btn btn-sm btn-primary'>
                    Set
                </button>
                <input type='hidden' name='school_year_id_btn' value='".$row['school_year_id']."'>
            </form >

        ";
        if($isActive == "Active"){
            $button = "
                <form method='POST' name='set_year_semester'>
                    <button name='set_year_semester' type='submit' class='btn btn-sm btn-success'>
                        Active
                    </button>
                    <input type='hidden' name='school_year_id_btn' value='".$row['school_year_id']."'>
                </form >
            ";
        }

        return "
            <tbody>
                <tr class='text-center'>
                    <td>$school_year_term</td>
                    <td>$period</td>
                    <td>
                        <div class='col-md-12 row'>
                            <div class='col-md-2'>$button</div>
                            <div class='col-md-2'>$maintenance_button</div>
                            <div class='col-md-8 row'>
                            <form method='POST'>
                                <div class='col-md-2'>$start_enrollment_date_btn</div>
                                <div class='col-md-2'>$end_enrollment_date_btn</div>
                                <div class='col-md-2'>$end_period_btn</div>
                            </form>
                            </div>


                        </div>
                        
                        
                    </td>
                </tr>
            </tbody>
        ";
    }
}

?>