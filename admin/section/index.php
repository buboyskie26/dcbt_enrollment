<?php 
    include('../registrar_enrollment_header.php');
    include('../../enrollment/classes/StudentEnroll.php');
    include('../../enrollment/classes/Schedule.php');
    include('../../enrollment/classes/Section.php');
    include('../classes/Course.php');

    $studentEnroll = new StudentEnroll($con);
    $schedule = new Schedule($con, $studentEnroll);

    $section = new Section($con, null);
  
    $course = new Course($con, $studentEnroll);

    $school_year_obj = $studentEnroll->GetActiveSchoolYearAndSemester();

    $current_school_year_id = $school_year_obj['school_year_id'];
    $current_school_year_term = $school_year_obj['term'];
    $current_school_year_period = $school_year_obj['period'];

    $createUrl = base_url . "/create.php";

    if(isset($_POST['populate_subject_btn']) && isset($_POST['course_id'])
        && isset($_POST['program_id']) && isset($_POST['course_level'])
        && isset($_POST['program_section'])
        
        ){

        $course_id = $_POST['course_id'];
        $program_id = $_POST['program_id'];
        $course_level = $_POST['course_level'];
        $program_section = $_POST['program_section'];

        # REFACTOR.
        $get_subject_program = $con->prepare("SELECT * FROM subject_program
            WHERE program_id=:program_id
            AND course_level=:course_level
            ");

        $get_subject_program->bindValue(":program_id", $program_id);
        $get_subject_program->bindValue(":course_level", $course_level);
        $get_subject_program->execute();
        
        # TODO: Check if in subject_program subjects are already in the subject table.
        // if yes, it is already in.
        # TODO: Set subject code = HUMSS-ES12-SECTION-NAME (HUMMS12-A)

        if($get_subject_program->rowCount() > 0){

            $isSubjectCreated = false;

            $insert_section_subject = $con->prepare("INSERT INTO subject
                (subject_title, description, subject_program_id, unit, semester, program_id, course_level, course_id, subject_type, subject_code)
                VALUES(:subject_title, :description, :subject_program_id, :unit, :semester, :program_id, :course_level, :course_id, :subject_type, :subject_code)");

            while($row = $get_subject_program->fetch(PDO::FETCH_ASSOC)){

                $program_program_id = $row['subject_program_id'];
                $program_course_level = $row['course_level'];
                $program_semester = $row['semester'];
                $program_subject_type = $row['subject_type'];
                $program_subject_title = $row['subject_title'];
                $program_subject_description = $row['description'];
                $program_subject_unit = $row['unit'];

                $program_subject_code = $row['subject_code'] . "-" . $program_section; 

                $insert_section_subject->bindValue(":subject_title", $program_subject_title);
                $insert_section_subject->bindValue(":description", $program_subject_description);
                $insert_section_subject->bindValue(":subject_program_id", $program_program_id);
                $insert_section_subject->bindValue(":unit", $program_subject_unit);
                $insert_section_subject->bindValue(":semester", $program_semester);
                $insert_section_subject->bindValue(":program_id", $program_id);
                $insert_section_subject->bindValue(":course_level", $program_course_level);
                $insert_section_subject->bindValue(":course_id", $course_id);
                $insert_section_subject->bindValue(":subject_type", $program_subject_type);
                $insert_section_subject->bindValue(":subject_code", $program_subject_code);

                // $insert_section_subject->execute();
                if($insert_section_subject->execute()){
                    $isSubjectCreated = true;
                }
            }

            if($isSubjectCreated == true){
                // AdminUser::success("All section`s subjects has been populated within the semester.", "");
            }
        }
        else{
            echo "program id not matched";
            return;
        }
    }

?>
 
    <div class="row col-md-12">
        <div class="col-lg-12">
            <div class="row">
                <div class="col-lg-12 text-center">
                    <h4 class="page-header">Active Enrollment SHS Strand Sections (SY: <?php echo $current_school_year_term;?>-<?php echo $current_school_year_period;?> Semester)</h4>
                </div>
            </div>
            
        </div>

        <div class="table-responsive" style="margin-top:2%; "> 

            <table id="courseTable" class="table table-striped table-bordered table-hover "  
                style="font-size:13px; display:none;" cellspacing="0"  > 
                <thead>
                    <tr class="text-center"> 
                        <th rowspan="2">Course Id</th>
                        <th rowspan="2">Track_Section</th>
                        <th rowspan="2">Total Student</th>  
                        <th rowspan="2">Capacity</th>
                        <th rowspan="2">Room</th>
                        <th rowspan="2">Adviser Name</th>  
                        <th rowspan="2">School Year</th>  
                        <th rowspan="2">Add Subject</th>
                    </tr>	
                </thead> 	
                <tbody>
                    <?php 
                        $username = "";
                        // $sectionScheduleGradeElevenFirst = $schedule->GetSectionScheduleGradeElevenFirst($username, $student_id, 11, "First");

                        $query = $con->query("SELECT t1.*,
                        
                            t2.firstname, t2.lastname
                            FROM course as t1

                            LEFT JOIN teacher as t2 ON t2.teacher_id = t1.adviser_teacher_id
                            WHERE t1.active='yes'");
                        // $query->bindValue("");
                        $query->execute();

                        if($query->rowCount() > 0){
                            while ($row = $query->fetch(PDO::FETCH_ASSOC)) {

                                $course_id = $row['course_id'];
                                $course_level = $row['course_level'];
                                $program_section = $row['program_section'];
                                $program_id = $row['program_id'];
                                $school_year_term = $row['school_year_term'];
                                $room = $row['room'] == "" ? "*N/A" : $row['room'];

                                $totalStudent = $section->GetTotalNumberOfStudentInSection($course_id, $current_school_year_id);

                                $teacher_name = $row['firstname'] . " " . $row['lastname'];

                                $teacher_name = $row['firstname'] == "" ? "N/A" : $teacher_name;

                                $doesPopulated = $course->CheckSectionPopulatedBySubject($course_id);

                                $button = "";

                                $url = directoryPath . "strand_show.php?id=$course_id";

                                $section_url = "$program_section";

                                if($doesPopulated == true){
                                    $button = "
                                            <button class='btn btn-sm btn-outline-success'>Populated</button>
                                    ";
                                    $section_url = "
                                        <a href='$url' style='color: whitesmoke;' >
                                            ".$row['program_section']." 
                                        </a>
                                    ";
                                }
                                else{
                                    $button = "
                                        <td>
                                            <form method='POST'>
                                                <input type='hidden' name='course_id'value='$course_id'>
                                                <input type='hidden' name='program_id'value='$program_id'>
                                                <input type='hidden' name='course_level' value='$course_level'>
                                                <input type='hidden' name='program_section' value='$program_section'>
                                                <button type='submit' name='populate_subject_btn' class='btn btn-sm btn-success populate-btn'>Populate</button>
                                            </form>
                                        </td>
                                    "; 
                                }

                                $editUrl = directoryPath . "edit.php?section_id=$course_id";

                                echo "<tr class='text-center'>";
                                    echo "<td>" . $row['course_id'] . "</td>";
                                    echo "<td>
                                        $section_url
                                    </td>";

                                    echo "<td>".$totalStudent."</td>";
                                    echo "<td>" . $row['capacity'] . "</td>";
                                    echo "<td>" . $room. "</td>";
                                    
                                    echo "<td>".$teacher_name."</td>";
                                    echo "<td>$school_year_term</td>";
                                    echo "
                                        <td>
                                            $button
                                            <a href='$editUrl'>
                                                <button class='btn btn-primary btn-sm'>Edit</button>
                                            </a>
                                        </td>
                                    ";
                                echo "</tr>";

                            }
                        }
                    ?>
                </tbody>
            </table>

            <hr>
            <hr>

            <div class="card">
                <div style="
                align-items: end;
                display: flex;
                flex-direction: column;" 
                class="col-md-12 text-right">
                    <a href="<?php echo directoryPath . "create.php"; ?>">
                        <button class=" btn btn-success">Add Section</button>
                    </a> 
                </div>
                   
                <div class="card-header">

                    <h5 class="text-muted">Section Details</h5>

                </div>
                <div class="card-body">
                    <table id="courseTable" 
                        class="table table-striped table-bordered table-hover " 
                        style="font-size:13px" cellspacing="0"  > 
                        <thead>
                            <tr class="text-center"> 
                                <th rowspan="2">Section Id</th>
                                <th rowspan="2">Section Code</th>
                                <th rowspan="2">School Year</th>  
                                <th rowspan="2">Level</th>
                                <th rowspan="2">Strand</th>
                                <th rowspan="2">Students</th>
                                <th rowspan="2">Action</th>
                            </tr>	
                        </thead> 	
                        <tbody>
                            <?php 
                                $username = "";
                                // $sectionScheduleGradeElevenFirst = $schedule->GetSectionScheduleGradeElevenFirst($username, $student_id, 11, "First");

                                $query = $con->query("SELECT t1.*, t2.acronym 

                                    FROM course as t1
                                    LEFT JOIN program as t2 ON t2.program_id = t1.program_id
                                    ");

                                // $query->bindValue("");
                                $query->execute();

                                if($query->rowCount() > 0){
                                    while ($row = $query->fetch(PDO::FETCH_ASSOC)) {

                                        $course_id = $row['course_id'];
                                        $program_section = $row['program_section'];
                                        $course_level = $row['course_level'];
                                        $program_section = $row['program_section'];
                                        $acronym = $row['acronym'];
                                        $school_year_term = $row['school_year_term'];

                                        $totalStudent = $section->GetTotalNumberOfStudentInSection($course_id,
                                            $current_school_year_id);

                                        $url_program_section = "
                                            <a href='section_subject_list.php?id=$course_id'>
                                                $program_section
                                            </a>
                                        ";

                                        $url = "section_show.php?id=$course_id";
                                        echo "
                                            <tr class='text-center'>
                                                <td>$course_id</td>
                                                <td>$url_program_section</td>
                                                <td>$school_year_term</td>
                                                <td>$course_level</td>
                                                <td>$acronym</td>
                                                <td>$totalStudent</td>
                                                <td>
                                                    <a href='$url'>
                                                        <button class='btn btn-sm btn-primary'>
                                                            View
                                                        </button>
                                                    </a>
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




            <!-- <table id="sectionTable"
                 class="table table-bordered table-hover " 
                 style="font-size:15px" cellspacing="0"> 
                    <thead>
                        <tr class="text-center">
                            <th>Program Name</th>
                            <th>Total Student</th>
                            <th>Capacity</th>
                            <th>Room</th>

                            <th>S.Y</th>
                            <th>Advisery Name</th>
                            <th></th>
                        </tr>
                    </thead>
            </table> -->
        </div>


    </div>

<script>
    $(document).ready(function(){
    var table = $('#sectionTable').DataTable({
        'processing': true,
        'serverSide': true,
        'serverMethod': 'POST',
        'ajax': {
            'url':'sectionFile.php'
        },
        'columns': [
            { data: 'program_section' },
            { data: 'total_student' },
            { data: 'capacity' },
            { data: 'room' },
            { data: 'school_year_term' },
            { data: 'advisery_name' },
            { data: 'actions' }

        ]
    });
    });

</script>

<script>
  // select all populate buttons
  const populateBtns = document.querySelectorAll('.populate-btn');

  // loop through buttons and trigger click event
//   populateBtns.forEach(btn => {
//     btn.click();
//   });
</script>
