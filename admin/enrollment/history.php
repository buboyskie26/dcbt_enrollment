<?php 
    include('../registrar_enrollment_header.php');
    include('../../enrollment/classes/StudentEnroll.php');
    include('../../enrollment/classes/Schedule.php');
    include('../classes/Course.php');

    $studentEnroll = new StudentEnroll($con);
    $schedule = new Schedule($con, $studentEnroll);
  
    $course = new Course($con, $studentEnroll);

    $school_year_obj = $studentEnroll->GetActiveSchoolYearAndSemester();

    $current_school_year_id = $school_year_obj['school_year_id'];
    $current_school_year_term = $school_year_obj['term'];
    $current_school_year_period = $school_year_obj['period'];

    $createUrl = base_url . "/create.php";
?>


<div class="row col-md-12">

    <div class="card">


        <div class="card-header">
            <h4 class="text-center">Enrollment History</h4>

            <div class="row">
            <a href="<?php echo directoryPath . "create.php"; ?>">
                <button class="mb-3 btn btn-success">
                   <i class='fas fa-plus-circle'></i> Manual Enrollment
                </button>
            </a> 
        </div>
        </div>

        <div class="card-body">

            <table class="table table-responsive">
                <tr class='bg-dark text-center'> 
                    <th rowspan="2">ID</th>
                    <th rowspan="2">Fullname</th>
                    <th rowspan="2">Semester</th>  
                    <th rowspan="2">Term</th>  
                    <th rowspan="2">Department</th>  
                    <th rowspan="2">Level</th>  
                    <th rowspan="2">Section</th>  
                    <!-- <th rowspan="2">Action</th>   -->
                </tr>	

                <tbody>
                    <?php 

                        $enrolled  = "enrolled";
                    
                        $query = $con->prepare("SELECT 
                            t1.*,

                            t2.*,

                            t3.program_section,

                            t4.term, t4.period
                            FROM enrollment as t1

                            LEFT JOIN student as t2 ON t2.student_id = t1.student_id
                            LEFT JOIN course as t3 ON t3.course_id = t1.course_id
                            LEFT JOIN school_year as t4 ON t4.school_year_id = t1.school_year_id

                            WHERE t1.enrollment_status=:enrolled
                            
                            ORDER BY t1.enrollment_date DESC");

                        $query->bindValue(":enrolled", $enrolled);
                        $query->execute();

                    if($query->rowCount() > 0){

                        while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
                            
                            $course_id = $row['course_id'];
                            $student_id = $row['student_id'];
                            $firstname = $row['firstname'];
                            $student_unique_id = $row['student_unique_id'];
                            $lastname = $row['lastname'];
                            $fullname = $firstname . " " . $lastname;
                            $program_section = $row['program_section'];
                            $term = $row['term'];
                            $period = $row['period'];
                            $is_tertiary = $row['is_tertiary'];
                            $school_year_id = $row['school_year_id'];
                            $enrollment_status = $row['enrollment_status'];


                            $btn1 = "";
                            $btn2 = "";

                            if($enrollment_status == "tentative"){
                                $btn1 = "
                                <a href=''>
                                    <button class='btn btn-primary'>
                                        <i class='fas fa-address-card'></i>
                                    </button>
                                </a>
                                ";
                            }
                            
                            if($enrollment_status == "enrolled"){
                                $btn2 = "
                                <a href='../student/view_details.php?profile=show&id=473'>
                                    <button class='btn btn-primary'>
                                        <i class='fas fa-eye'></i>
                                    </button>
                                </a>
                                ";
                            }

                            $department_name = $is_tertiary == 0 ? "SHS" : "Tertiary";
                            
                            
                            $button = "";

                            $url = directoryPath . "strand_show.php?id=$course_id";

                            $section_url = "$program_section";

                            $editUrl = directoryPath . "edit.php?section_id=$course_id";
                            echo "<tr class='text-center'>";
                                echo "<td>$student_id</td>";
                                echo "<td>$fullname</td>";
                                echo "<td>$period</td>";
                                echo "<td>$term</td>";
                                echo "<td>$department_name</td>";
                                echo "<td>Program</td>";
                                echo "<td>$program_section</td>";
                                 
                                 
                            echo "</tr>";
                        }

                    }
                    
                    ?>
                </tbody>
            </table>

            <!-- <table id="historyTable"
                class="table table-bordered table-hover " 
                style="font-size:15px" cellspacing="0"> 
                <thead>
                    <tr class="text-center">
                        <th>Name</th>
                        <th>Semester</th>
                        <th>Term</th>
                        <th>Department</th>
                        <th>Level</th>
                        <th>Section</th>
                        <th>Department</th>
                    </tr>
                </thead>
            </table> -->

        </div>
    </div>
</div>


<script>

    $(document).ready(function(){

        var table = $('#historyTable').DataTable({
            'processing': true,
            'serverSide': true,
            'serverMethod': 'POST',
            'ajax': {
                'url':'history_datatable.php'
            },
            'columns': [
                { data: 'student_id'},
                { data: 'student_name'},
                { data: 'course_level'},
                { data: 'section'},
                { data: 'period'},
                { data: 'term'},
                { data: 'department'}
                
                // { data: 'section' }
            ]
        });
    });

</script>