<?php
 
    include('../cashier_enrollment_header.php');
    include('../../enrollment/classes/StudentEnroll.php');
    include('../../enrollment/classes/Section.php');
    include('../../enrollment/classes/Enrollment.php');
    include('../classes/Course.php');

    if(!AdminUser::IsCashierAuthenticated()){
        header("Location: /dcbt/cashierLogin.php");
        exit();
    }

    if(isset($_GET['id'])){

        $student_id = $_GET['id'];

        $studentEnroll = new StudentEnroll($con);
        $enrollment = new Enrollment($con, $studentEnroll);

        $student_course_id = $studentEnroll->GetStudentCourseIdById($student_id);
        // $student_username = $studentEnroll->GetStudentUsername($student_id);
        
        $enrollment_form_id = $enrollment->GenerateEnrollmentFormId();
       
        $school_year_obj = $studentEnroll->GetActiveSchoolYearAndSemester();

        $current_school_year_id = $school_year_obj['school_year_id'];
        $current_school_year_term = $school_year_obj['term'];
        $current_school_year_period = $school_year_obj['period'];

            

        // print_r($sub);

        $sql = $con->prepare("SELECT 
        
                t1.*, t2.*
        
                 FROM student as t1

                INNER JOIN course as t2 ON t2.course_id = t1.course_id
                WHERE t1.student_id=:student_id
                LIMIT 1
            ");

        $sql->bindValue(":student_id", $student_id);
        $sql->execute();

        $row = null;

        if($sql->rowCount() > 0){

            $row = $sql->fetch(PDO::FETCH_ASSOC);
            
            $date_creation = $row['date_creation'];
            $student_username = $row['username'];
            $section_name = $row['program_section'];
            $selected_course_id = $row['course_id'];
            $course_level = $row['course_level'];
            $student_unique_id = $row['student_unique_id'];
            $student_status = $row['student_status'];

            $course_section_subjects = $studentEnroll->GetStudentsStrandSubjectsPerLevelSemester($student_username);
 

            // print_r($course_section_subjects);
            
            $program_id = $row['program_id'];
            $program = $con->prepare("SELECT acronym FROM program
                WHERE program_id=:program_id
                LIMIT 1
            ");
            $program->bindValue(":program_id", $program_id);
            $program->execute();

            $program_acronym = $program->fetchColumn();

            $track = "";

            if($program_acronym == "STEM" || $program_acronym == "HUMMS"){
                $track = "Academic";
            }

            $enrollment_id = $enrollment->GetEnrollmentId($student_id, $selected_course_id, $current_school_year_id);
            $student_enrollment_form_id = $enrollment->GetEnrollmentFormId($student_id, $selected_course_id, $current_school_year_id);


            $my_course_subjects = [];


            if(isset($_POST['mark_as_paid_btn'])){

                // echo "paid";

                $wasSuccess = $enrollment->MarkAsCashierEvaluatedByEnrollmentId($enrollment_id);
                if($wasSuccess){

                    AdminUser::success("Successfully evaluted", "cashier_index.php");
                    exit();
                }
            }

            ?>

            <div class="row col-md-12">
                <h3 class="text-center text-primary">Enrollment Form</h3>
                <div class="row col-md-12">
                    <div class="mb-4 col-md-3">
                        <label for="">Form Id</label>
                        <input readonly style="width: 100%;" type="text" 
                            value='<?php echo $student_enrollment_form_id;?>' class="form-control">
                    </div>
                    
                    <div class="mb-4 col-md-3">
                        <label for="">Admission Type</label>
                        <input readonly style="width: 100%;" type="text" 
                            value='<?php echo $student_status ?>' class="form-control">
                    </div>

                    <div class="mb-4 col-md-2">
                        <label for="">Student No</label>
                        <input readonly style="width: 100%;" type="text" 
                            value='<?php echo $student_unique_id?>' class="form-control">
                    </div>

                    <div class="mb-4 col-md-2">
                        <label for="">Status</label>
                        <input readonly style="width: 100%;" type="text" 
                            value='Waiting' class="form-control">
                    </div>

                    <div class="mb-4 col-md-2">
                        <label for="">Submission</label>
                        <input readonly style="width: 100%;" type="text" 
                            value='<?php echo $date_creation?>' class="form-control">
                    </div>
                </div>

                <hr>
                <hr>

                <div class="container">
                    <div class="row col-md-12">
                        <div class="text-center col-md-6">
                            <button class="btn btn-outline-primary btn-lg">Student Details</button>

                        </div>
                        <div class="text-center col-md-6">
                            <button class="btn btn-primary btn-lg">Subject Details</button>
                        </div>
                    </div>
                </div>
            
                <hr>
                <hr>

                <div class="container mt-4">
                    <h3 class="text-center mb-3 text-success">Enrollment Details</h3>
                    <div class="row mb-3">
                        <div class=" mb-3 col-md-4">
                            <label for="">Term</label>
                            <input readonly style="width: 100%;" type="text" 
                                value='<?php echo $current_school_year_term;?>' class="form-control">
                        </div>
                        <div class="mb-4 col-md-4">
                            <label for="">Track</label>
                            <input readonly style="width: 100%;" type="text" value='<?php echo $track?>' class="form-control">
                        </div>
                        
                        <div class="mb-4 col-md-4">
                            <label for="">Strand</label>
                            <input readonly style="width: 100%;" type="text"
                                value='<?php echo $program_acronym;?>' class="form-control">
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class=" mb-3 col-md-6">
                            <label for="">Year</label>
                            <input readonly style="width: 100%;" type="text" 
                                value='Grade <?php echo $course_level;?>' class="form-control">
                        </div>
                        <div class="mb-4 col-md-6">
                            <label for="">Semester</label>
                            <input readonly style="width: 100%;" type="text"
                                value='<?php echo $current_school_year_period?>' class="form-control">
                        </div>
                        
                    
                    </div>

                </div>

                <div class="row col-md-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="container mt-4 mb-2">
                                <h4 class="mb-3 text-center text-success"><?php echo $section_name;?> Subjects </h4>
                                <h5 class="mb-3 text-center text-muted">S.Y <?php echo $current_school_year_term;?> <?php echo $current_school_year_period;?> Semester</h5>

                                <table id="" class="table table-striped table-bordered table-hover "  style="font-size:13px" cellspacing="0"  > 
                                    <thead>
                                        <tr class="text-center"> 
                                            <th rowspan="2">Id</th>
                                            <th rowspan="2">Code</th>
                                            <th rowspan="2">Description</th>
                                            <th rowspan="2">Unit</th>
                                            <th rowspan="2">Type</th>
                                        </tr>	
                                    </thead> 	
                                    <tbody>
                                        <?php
                                            $course_level = 11;
                                            $active = "yes";
                                            # Only Available now.

                                            $section_subjects_id = implode(',', $course_section_subjects);


                                            $sql = $con->prepare("SELECT t2.*,

                                                -- t3.student_subject_id as t3_student_subject_id,
                                                -- t4.student_subject_id as t4_student_subject_id,

                                                t1.is_transferee

                                                FROM student_subject as  t1
                                                INNER JOIN subject as t2 ON t2.subject_id = t1.subject_id

                                                WHERE t1.student_id=:student_id
                                                AND t1.subject_id IN ($section_subjects_id)
                                                ");
                                            $sql->bindValue(":student_id", $student_id);
                                            // $sql->bindValue(":course_level", $course_level);

                                            $sql->execute();

                                            $totalUnits = 0;
                                        
                                            if($sql->rowCount() > 0){
                                                
                                                while($row = $sql->fetch(PDO::FETCH_ASSOC)){


                                                    $subject_id = $row['subject_id'];

                                                    $subject_course_id = $row['course_id'];

                                                    $subject_code = $row['subject_code'];
                                                    $subject_title = $row['subject_title'];
                                                    $unit = $row['unit'];
                                                    $subject_type = $row['subject_type'];
                                                    $is_transferee = $row['is_transferee'];
    

                                                    $status = "Ongoing";
                                                    $totalUnits += $unit;
                                                  

                                                    
                                                    if($subject_course_id == $student_course_id){

                                                        array_push($my_course_subjects, $subject_id);
                                                    }
                                                    
                                                    echo "
                                                    <tr class='text-center'>
                                                        <td>$subject_id</td>
                                                        <td>$subject_code</td>
                                                        <td>$subject_title</td>
                                                        <td>$unit</td>
                                                        <td>$subject_type</td>
                                                    </tr>
                                                ";
                                                }
                                            }
                                        ?>
                                        <tr class="text-center">
                                            <td colspan="3"  style="text-align: right;" >Total Units</td>
                                            <td><?php echo $totalUnits;?></td>
                                        </tr> 
                                    </tbody>
                                </table>
                                
                                <!--  REMOVE SUBJECTS -->
                                <h5 class="mb-3 text-center text-warning">Removed Subjects</h5>
                                <table id="" class="table table-striped table-bordered table-hover "  style="font-size:13px" cellspacing="0"  > 
                                    <thead>
                                        <tr class="text-center"> 
                                            <th rowspan="2">Id</th>
                                            <th rowspan="2">Code</th>
                                            <th rowspan="2">Description</th>
                                            <th rowspan="2">Unit</th>
                                            <th rowspan="2">Type</th>
                                        </tr>	
                                    </thead> 	
                                    <tbody>
                                        <?php

                                            $subjectIds = implode(',', $my_course_subjects);

                                            $sql = $con->prepare("SELECT * FROM 
                                            
                                                subject as t1

                                                WHERE t1.subject_id NOT IN ($subjectIds)
                                                -- WHERE t1.student_id=:student_id
                                                AND t1.course_id=:course_id
                                                AND t1.course_level=:course_level
                                                AND t1.semester=:semester

                                                ");

                                            $sql->bindValue(":course_id", $student_course_id);
                                            $sql->bindValue(":course_level", $course_level);
                                            $sql->bindValue(":semester", $current_school_year_period);
                                            $sql->execute();

                                        
                                            $totalUnits = 0;
                                        
                                            if($sql->rowCount() > 0){
                                                
                                                while($row = $sql->fetch(PDO::FETCH_ASSOC)){

                                                    $subject_id = $row['subject_id'];
                                                    $subject_code = $row['subject_code'];
                                                    $subject_title = $row['subject_title'];
                                                    $unit = $row['unit'];
                                                    $subject_type = $row['subject_type'];
                                                    // $is_transferee = $row['is_transferee'];

                                                    $totalUnits += $unit;

                                                    $status = "Ongoing";
                                                    
                                                    echo "
                                                        <tr class='text-center'>
                                                            <td>$subject_id</td>
                                                            <td>$subject_code</td>
                                                            <td>$subject_title</td>
                                                            <td>$unit</td>
                                                            <td>$subject_type</td>
                                                        </tr>
                                                    ";
                                                }
                                            }
                                        ?>
                                        <tr class="text-center">
                                            <td colspan="3"  style="text-align: right;" >Remove Units</td>
                                            <td><?php echo $totalUnits;?></td>
                                        </tr> 
                                    </tbody>
                                </table>
                            </div> 
                        </div>
                    </div>

                    <div class="card">
                        <div class="card-body">
                            <div class="container mt-4 mb-2">
                                <h3 class="text-center text-primary">Added Subjects</h3>

                                <table id="" class="table table-striped table-bordered table-hover "  style="font-size:13px" cellspacing="0"  > 
                                    <thead>
                                        <tr class="text-center"> 
                                            <th rowspan="2">Id</th>
                                            <th rowspan="2">Code</th>
                                            <th rowspan="2">Description</th>
                                            <th rowspan="2">Unit</th>
                                            <th rowspan="2">Type</th>
                                        </tr>	
                                    </thead> 	
                                    <tbody>
                                        <?php
                                            $course_level = 11;
                                            $active = "yes";
                                            # Only Available now.

                                            $section_subjects_id = implode(',', $course_section_subjects);


                                            $sql = $con->prepare("SELECT t2.*,

                                                -- t3.student_subject_id as t3_student_subject_id,
                                                -- t4.student_subject_id as t4_student_subject_id,

                                                t1.is_transferee

                                                FROM student_subject as  t1
                                                INNER JOIN subject as t2 ON t2.subject_id = t1.subject_id

                                                WHERE t1.student_id=:student_id
                                                AND t1.subject_id NOT IN ($section_subjects_id)
                                                ");
                                            $sql->bindValue(":student_id", $student_id);
                                            // $sql->bindValue(":course_level", $course_level);

                                            $sql->execute();

                                            $totalUnits = 0;
                                        
                                            if($sql->rowCount() > 0){
                                                
                                                while($row = $sql->fetch(PDO::FETCH_ASSOC)){


                                                    $subject_id = $row['subject_id'];

                                                    $subject_course_id = $row['course_id'];

                                                    $subject_code = $row['subject_code'];
                                                    $subject_title = $row['subject_title'];
                                                    $unit = $row['unit'];
                                                    $subject_type = $row['subject_type'];
                                                    $is_transferee = $row['is_transferee'];
    
                                                        $totalUnits += $unit;


                                                    $status = "Ongoing";

                                                    
                                                    echo "
                                                    <tr class='text-center'>
                                                        <td>$subject_id</td>
                                                        <td>$subject_code</td>
                                                        <td>$subject_title</td>
                                                        <td>$unit</td>
                                                        <td>$subject_type</td>
                                                    </tr>
                                                ";
                                                }
                                            }
                                        ?>
                                        <tr class="text-center">
                                            <td colspan="3"  style="text-align: right;" >Total Units</td>
                                            <td><?php echo $totalUnits;?></td>
                                        </tr> 
                                    </tbody>
                                </table>

                                <form method="POST">

                                    <button type="submit" 
                                        name="mark_as_paid_btn" 
                                        onclick="return confirm('Are you sure you want to mark as paid the student?')"
                                        class='btn btn-outline-primary'>Mark as Paid</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <?php
        }

    }

?>

