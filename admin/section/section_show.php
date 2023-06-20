 
<?php 

    include('../registrar_enrollment_header.php');
    include('../../enrollment/classes/StudentEnroll.php');
    include('../../enrollment/classes/Schedule.php');
    include('../../enrollment/classes/Section.php');

    $enroll = new StudentEnroll($con);
    $schedule = new Schedule($con, $enroll);
   
    // The Section should have a list of Subject
    if(isset($_GET['id'])){

        $course_id = $_GET['id'];

        // echo $course_id;

        $section = new Section($con, $course_id);

        $userLoggedInId = "";
        $username = "";
        $student_id  = "";
        $school_year_obj = $enroll->GetActiveSchoolYearAndSemester();
        $current_school_year_id = $school_year_obj['school_year_id'];
        $current_school_year_term = $school_year_obj['term'];
        $current_school_year_period = $school_year_obj['period'];

        // Grade 12 STEM12-A 2024-2025 1st Semester Subjects Schedule
        // Grade 12 STEM12-A 2024-2025 2nd Semester
    
        $section_name = $section->GetSectionName();
        $section_id = $section->GetSectionId();
        $section_s_y = $section->GetSectionSY();
        $section_level = $section->GetSectionGradeLevel();
        $section_advisery = $section->GetSectionAdvisery();

        $getProgramIdBySectionId = $section->GetProgramIdBySectionId($section_id);
        $section_acronym = $section->GetAcronymByProgramId($getProgramIdBySectionId);

        $totalStudent = $section->GetTotalNumberOfStudentInSection($section_id, 
            $current_school_year_id);

        ?>
            <!-- Grade 12 1st sem REGARDLESS OF STUDENT_sTATUS -->
            <div class="row col-md-12">
                <div class="row mb-3">
                   
                    <h6 class="text-center">Select School Year Term:</h6>

                    <div class="col-6 offset-md-2">

                        <input type="hidden" value="<?php echo $course_id;?>" id="course_id">

                        <select name="select_school_year_id"
                            id="select_school_year_id"
                            class="form-select">
                            <?php
                                $query = "";



                                // if($current_school_year_period == "First"){
                                //     $query = $con->prepare("SELECT 
                                //         DISTINCT
                                //         t1.school_year_id,
                                //         t1.period
                                    
                                //         FROM school_year as t1

                                //         INNER JOIN course as t2 ON t2.school_year_term = t1.term

                                //         AND t1.period='First'

                                //         WHERE t1.term=:term

                                //     ");
                                // }

                                // # Select Course Id
                                // # Select school_year_term

                                // if($current_school_year_period == "Second"){
                                //     $query = $con->prepare("SELECT 
                                //         DISTINCT
                                //         t1.school_year_id,
                                //         t1.period
                                    
                                //         FROM school_year as t1

                                //         INNER JOIN course as t2 ON t2.school_year_term = t1.term

                                //         AND (
                                //             t1.period='First'
                                //             OR
                                //             t1.period='Second'
                                //             )

                                //         WHERE t1.term=:term

                                //     ");
                                // }
 
                                // $query->bindValue(":term", $current_school_year_term);
                                // $query->execute();
  
                                // echo "<option value='0' selected>Select School Year</option>";
                                // while($row = $query->fetch(PDO::FETCH_ASSOC)) {
                                //     echo "<option value='" . $row['school_year_id'] . "'>" . $row['period'] . "</option>";
                                // }

                                $query = $con->prepare("SELECT DISTINCT e.school_year_id, sy.period
                                    FROM enrollment e

                                    INNER JOIN school_year sy ON e.school_year_id = sy.school_year_id
                                    WHERE e.course_id = :course_id
                                    -- AND sy.period = 11
                                ");

                                $query->bindValue(":course_id", $course_id);
                                $query->execute();

                                echo "<option selected>Select School Year</option>";
                                while($row = $query->fetch(PDO::FETCH_ASSOC)) {
                                    echo "<option value='" . $row['school_year_id'] . "'>" . $row['period'] . "</option>";
                                }

                            ?>     
                        </select>


                        <!-- <select name="select_school_year_id"
                            id="select_school_year_id" class="form-select">
                            <option value="" selected>Choose Semester</option>
                            <option value="First">First</option>
                            <option value="Second">Second</option>
                        </select> -->

                    </div>
                </div>

                <div style="display: none;" class="card">
                    <div class="card-body">
                        <div class="card-header">
                            <h3><?php echo $section_name;?></h3>
                        </div>

                        <div class="row col-md-12">
                            <div class="col-md-2">
                                <h5>Section Id</h5>
                                <span><?php echo $section_id;?></span>
                            </div>

                            <div class="col-md-2">
                                <h5>School Year</h5>
                                <span><?php echo $current_school_year_term;?></span>
                            </div>

                            <div class="col-md-2">
                                <h5>Semester</h5>
                                <span><?php echo $current_school_year_period;?></span>
                            </div>

                            <div class="col-md-2">
                                <h5>Level</h5>
                                <span><?php echo $section_level;?></span>
                            </div>

                            
                            <div class="col-md-2">
                                <h5>Strand</h5>
                                <span><?php echo $section_acronym;?></span>
                            </div>

                            <div class="col-md-2">
                                <h5>Students</h5>
                                <span><?php echo $totalStudent;?></span>
                            </div>


                        </div>
                    </div>
                </div>


                <div id="upper"></div>

                <hr>
                <hr>


                <div id="insert_table"></div>

                <div style="display: none;" class="card">
                  
                    <div class="card-header">
                        <a href="section_students.php?course_id=<?php echo $section_id;?>&sy_id=<?php echo $current_school_year_id;?>">
                            <button class='btn btn-sm btn-success'>Show Students</button>
                        </a>
                    </div>

                    <div class="card-body">

                        <hr>
                        <hr>
                        <table  class="table table-hover table-responsive">
                            <thead>
                                <tr class="text-center">
                                    <th>Code</th>
                                    <th>Subject</th>
                                    <th>Grade Level</th>
                                    <th>Semester</th>
                                    <th>Pre-Requisite</th>
                                    <th>Type</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php 

                                    $sql = $con->prepare("SELECT 
                                    
                                        t1.*,
                                        t2.subject_code as t2_subject_code
 
                                        FROM subject_program as t1

                          
                                        LEFT JOIN subject as t2 ON t2.subject_program_id = t1.subject_program_id
                            
                                        WHERE t1.program_id=:program_id

                                        AND t1.course_level=:course_level
                                        AND t1.semester=:semester
                                        AND t2.course_id=:course_id
                                        -- AND (t1.semester='First'
                                        --     OR
                                        --     t1.semester='Second'
                                        -- )

                                        ORDER BY t1.course_level DESC,
                                        t1.semester
                                        ");
                                    
                                    // $sql->bindValue(":course_id", $course_id);
                                    $sql->bindValue(":program_id", $getProgramIdBySectionId);
                                    $sql->bindValue(":course_level", $section_level);
                                    $sql->bindValue(":semester", $current_school_year_period);
                                    $sql->bindValue(":course_id", $section_id);
                                    // $sql->bindValue(":semester", $current_school_year_period);
                                    
                                    $sql->execute();

                                    if($sql->rowCount() > 0){

                                        while($row = $sql->fetch(PDO::FETCH_ASSOC)){

                                            
                                            $t2_subject_code = $row['t2_subject_code'];
                                            $subject_program_id = $row['subject_program_id'];
                                            $subject_code = $row['subject_code'];
                                            $subject_title = $row['subject_title'];
                                            $course_level = $row['course_level'];
                                            $semester = $row['semester'];
                                            // $pre_requisite = $row['pre_requisite'];
                                            $pre_requisite = $row['pre_req_subject_title'];
                                            $subject_type = $row['subject_type'];
                                            // $subject_subject_program_id = $row['subject_subject_program_id'];
                                            // $subject_subject_title = $row['subject_subject_title'];

                                            # Find missing subjects based on subject_program

                                            $statuss = "N/A";


                                            $subject_real = $con->prepare("SELECT 
                                                    
                                                t1.subject_title as t1_subject_title,
                                                t1.subject_program_id as t1_subject_program_id

                                                FROM subject as t1 

                                                WHERE t1.subject_program_id=:subject_program_id
                                                AND t1.course_id=:course_id
                                                LIMIT 1");
                                                            
                                            $subject_real->bindValue(":subject_program_id", $subject_program_id);
                                            $subject_real->bindValue(":course_id", $course_id);
                                            $subject_real->execute();

                                            $t1_subject_program_id = null;

                                            if($subject_real->rowCount() > 0){

                                                $row = $subject_real->fetch(PDO::FETCH_ASSOC);

                                                $t1_subject_title = $row['t1_subject_title'];
                                                $t1_subject_program_id = $row['t1_subject_program_id'];
                                            }

                                            if($t1_subject_program_id != null && $t1_subject_program_id == $subject_program_id){
                                                $statuss = "
                                                    <i class='fas fa-check'></i>
                                                ";
                                            }
                                            else{
                                                $statuss = "
                                                    <button class='btn btn-sm btn-primary'>Populate</button>
                                                ";
                                            }

                                            
                                            echo "
                                                <tr class='text-center'>
                                                    <td>$t2_subject_code</td>
                                                    <td>$subject_title</td>
                                                    <td>Grade $course_level</td>
                                                    <td>$semester</td>
                                                    <td>$pre_requisite</td>
                                                    <td>$subject_type</td>
                                                    <td>$statuss</td>
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
        <?php
    }
?>

<style>
  a {
    color: whitesmoke; /* light brown color */
    text-decoration: none;
  }
</style>

<script>
    $(document).ready(function() {

            $('#select_school_year_id').on('change', function() {

            var school_year_id = parseInt($(this).val());

            // console.log(school_year_id)
            
            if(!school_year_id){
                // school_year_id = "nothing";
            }
            var course_id = parseInt($("#course_id").val());

            $.ajax({
                url: '../ajax/section/get_top.php',
                type: 'POST',
                data: {
                    course_id: course_id,
                    school_year_id: school_year_id
                },

                dataType: 'json',

                success: function(response) {

                    // console.log(response);
                    // console.log(response[0].section_name);

                    var section_name = response[0].section_name;
                    var section_id = response[0].section_id;
                    var current_school_year_term = response[0].current_school_year_term;
                    var current_school_year_period = response[0].current_school_year_period;
                    var section_level = response[0].section_level;
                    var section_acronym = response[0].section_acronym;
                    var totalStudent = response[0].totalStudent;
                    
                    var top = `
                        <div class="card">
                            <div class="card-body">
                                <div class="card-header">
                                    <h3>${section_name}</h3>
                                </div>

                                <div class="row col-md-12">
                                    <div class="col-md-2">
                                        <h5>Section Id</h5>
                                        <span>${section_id}</span>
                                    </div>

                                    <div class="col-md-2">
                                        <h5>School Year</h5>
                                        <span>${current_school_year_term}</span>
                                    </div>

                                    <div class="col-md-2">
                                        <h5>Semester</h5>
                                        <span>${current_school_year_period}</span>
                                    </div>

                                    <div class="col-md-2">
                                        <h5>Level</h5>
                                        <span>${section_level}</span>
                                    </div>

                                    
                                    <div class="col-md-2">
                                        <h5>Strand</h5>
                                        <span>${section_acronym}</span>
                                    </div>

                                    <div class="col-md-2">
                                        <h5>Students</h5>
                                        <span>${totalStudent}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    `;
    
                    $('#upper').html(top); 
                }
            });

            console.log(school_year_id)

            $.ajax({
                url: '../ajax/section/get_strand_show.php',
                type: 'POST',
                data: {
                    course_id: course_id,
                    school_year_id: school_year_id
                },
                dataType: 'json',
                success: function(response) {
                    
                    console.log(response);

                    var html = `
                        <div class="card">
                            <div class="card-header">
                                <a href="section_students.php?course_id=${course_id}&sy_id=${school_year_id}">
                                    <button class='btn btn-sm btn-success'>Show Students</button>
                                </a>
                            </div>

                        <div class="card-body">
                            <table  class="table table-striped table-bordered table-hover " 
                                style="font-size:13px" cellspacing="0"  > 
                                <thead>
                                    <tr class="text-center">
                                        <th>Code</th>
                                        <th>Subject</th>
                                        <th>Semester</th>
                                        <th>Pre-Requisite</th>
                                        <th>Type</th>
                                        <th>Enrolled</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                        `;
                    $.each(response, function(index, value) {
                    
                        var subject_id = value.subject_id;
                        var subject_code = value.subject_code === null ? "N/A" : value.subject_code
                        var subject_title = value.subject_title;
                        var semester = value.semester;
                        var course_level = value.course_level;
                        var subject_type = value.subject_type;
                        var pre_requisite = value.pre_requisite;
                        var status = value.statuss;

                        html += `
                            <body>
                                <tr class='text-center'>
                                    <td>${subject_code}</td>
                                    <td>${value.subject_title}</td>
                                    <td>${value.semester}</td>
                                    <td>${value.pre_requisite}</td>
                                    <td>${value.subject_type}</td>
                                    <td>${value.enrolled_students}</td>
                                    <td>${status}</td>
                                </tr>
                            </body>
                        `;
                    });

                    html += `
                                </table>
                            </div>
                        </div>
                    `;

                    $('#insert_table').html(html); 
                },
            });
        });
    });
 
</script>
