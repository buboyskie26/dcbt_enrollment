
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        .selection-btn {
        display: flex;
        flex-direction: row;
        justify-content: center;
        align-items: center;
        padding: 5px 10px;
        gap: 10px;
        width: 160px;
        height: 54px;
        background: #EFEFEF;
        border: none;
        font-style: normal;
        font-weight: 400;
        font-size: 16px;
    }
    </style>
</head>

<?php 

    include('../registrar_enrollment_header.php');
    include('../../enrollment/classes/StudentEnroll.php');
    include('../../enrollment/classes/Enrollment.php');
    include('../../enrollment/classes/OldEnrollees.php');

    $createUrl = base_url . "/create.php";
    $manualCreateUrl = base_url . "/manual_create.php";

    $enroll = new StudentEnroll($con);
    $old_enrollee = new OldEnrollees($con, $enroll);
    $enrollment = new Enrollment($con, $enroll);
    
    // echo "im in subject enroll";

    $school_year_obj = $enroll->GetActiveSchoolYearAndSemester();

    $current_school_year_id = $school_year_obj['school_year_id'];
    $current_school_year_term = $school_year_obj['term'];
    $current_school_year_period = $school_year_obj['period'];

    $enrolledStudentsEnrollment = $enrollment->EnrolledStudentsWithinSYSemester($current_school_year_id);
?>

    <div class="row col-md-12">
        <div class="card">
            <div class="card-header">
                <h6 class="text-end">S.Y <?php echo $current_school_year_term;?> <?php echo $current_school_year_period;?> Semester Period</h6>
                <h2 class="text-start text-muted">Form Details</h2>
            </div>

            <div class="card-body">
                <table  class="table table-striped table-bordered table-hover "  style="font-size:13px" cellspacing="0"  > 
                    <thead>
                        <tr class="text-center"> 
                            <th rowspan="2">Id</th>
                            <th rowspan="2">Name</th>
                            <th rowspan="2">Status</th>
                            <th rowspan="2">Grade Level</th>
                            <th rowspan="2">Section</th>
                            <th rowspan="2">Action</th>
                        </tr>	
                    </thead> 	 
                    <tbody>
                        <?php 
                            $active = 1;

                            if(count($enrolledStudentsEnrollment) > 0){

                                foreach ($enrolledStudentsEnrollment as $key => $row) {
                                    
                                    $fullName = $row['firstname']." ". $row['lastname']; 
                                    $student_id = $row['student_id'];
                                    $student_unique_id = $row['student_unique_id'];
                                    $course_level = $row['course_level'];
                                    $course_id = $row['course_id'];
                                    $status = $row['student_status'];
                                    $program_section = $row['program_section'];

                                    $gradeUrl = "http://localhost/dcbt/admin/enrollees/student_grade_report.php?id=$student_id";

                                    $view_url = directoryPath . "../student/view_details.php?profile=show&id=$student_id";

                                    $trans_url = directoryPath . "../student/shs_view_transferee_details.php?profile=show&id=$student_id";

                                    // $section_url = "http://localhost/dcbt/admin/section/strand_show.php?id=$course_id";
                                    $section_url = "../section/section_show.php?id=$course_id";

                                    $view_btn = "
                                        <a href='$view_url'>
                                            <button class='btn btn-secondary btn-sm'>
                                                View
                                            </button>
                                        </a>
                                    ";

                                    if($status == "Transferee"){

                                        $view_btn = "
                                            <a href='$view_url'>
                                                <button class='btn btn-outline-secondary btn-sm'>
                                                    View
                                                </button>
                                            </a>
                                        ";
                                    }

                                    echo '<tr class="text-center">'; 
                                            echo '<td>'.$student_unique_id.'</td>';
                                            echo '<td>
                                                <a style= "color: whitesmoke;" href="edit.php?id='.$student_id.'">
                                                    '.$fullName.'
                                                </a>
                                            </td>';
                                            echo '<td>'.$status.'</td>';
                                            echo '<td>'.$course_level.'</td>';
                                            echo '<td>
                                                <a href="'.$section_url.'">
                                                    '.$program_section.'</td>
                                                </a>
                                            ';
                                            echo '
                                                <td> 
                                                    '.$view_btn.'
                                                </td>
                                            ';
                                    echo '</tr>';
                                }
                            }else{
                                echo "
                                    <div class='col-md-12'>
                                        <h4 class='text-info'>No Enrolled in this Semester.</h4>
                                    </div>
                                ";
                            }

                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script>
        function confirmAsReturneeBtn(username) {
            // $.ajax({
            //     url: '../ajax/enrollee/markAsReturned.php', // replace with your PHP script URL
            //     type: 'POST',
            //     data: {
            //         // add any data you want to send to the server here
            //         username
            //     },
            //     success: function(response) {
            //         // console.log(response);
            //         alert(response)
            //         location.reload();
            //     },
            //     error: function(xhr, status, error) {
            //     }
            // });
        }

    </script>