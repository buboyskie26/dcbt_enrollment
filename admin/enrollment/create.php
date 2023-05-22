<?php

    include('../registrar_enrollment_header.php');
    include('../../enrollment/classes/StudentEnroll.php');
    include('../../enrollment/classes/Enrollment.php');
    include('../../enrollment/classes/OldEnrollees.php');

    $studentEnroll = new StudentEnroll($con);
    $enrollment = new Enrollment($con, $studentEnroll);
    $old_enroll = new OldEnrollees($con, $studentEnroll);

    if(isset($_POST['enroll_student_registrar_btn'])){

        $school_year_obj = $studentEnroll->GetActiveSchoolYearAndSemester();

        $current_school_year_id = $school_year_obj['school_year_id'];

        $student_course_id = $_POST['student_course_id'];
        $student_id = $_POST['studentId'];
        $enrollment_status = "tentative";
        $registrar_evaluated = "yes";

        $insert = $con->prepare("INSERT INTO enrollment
            (student_id, course_id, school_year_id, enrollment_status, registrar_evaluated)
            VALUES (:student_id, :course_id, :school_year_id, :enrollment_status, :registrar_evaluated)");

        $insert->bindValue(":student_id", $student_id);
        $insert->bindValue(":course_id", $student_course_id);
        $insert->bindValue(":school_year_id", $current_school_year_id);
        $insert->bindValue(":enrollment_status", $enrollment_status);
        $insert->bindValue(":registrar_evaluated", $registrar_evaluated);
        if($insert->execute()){

            $url = "../enrollees/old_enrollees.php";
            header("Location: $url");
            exit();
        }

    }
if(isset($_POST['enroll_student_registrar_btn_v2'])){

    
    $studentId = filter_input(INPUT_POST, 'studentId', FILTER_SANITIZE_NUMBER_INT);
    $studentId = intval($studentId);
    $active = 1;
    $stmt = $con->prepare("SELECT student_id FROM student 
        WHERE student_id = ?
        AND active = ?");
    
    $stmt->bindParam(1, $studentId, PDO::PARAM_INT);
    $stmt->bindParam(2, $active, PDO::PARAM_INT);
    $stmt->execute();
    
    // Fetch the result of the query
    $result = $stmt->fetch(PDO::FETCH_ASSOC);

    if(!$result) {
        echo "Invalid student ID";
        return;
    }

    $year_semester_obj = $studentEnroll->GetActiveSchoolYearAndSemester();

    $active_school_year_id = $year_semester_obj['school_year_id'];
    $semester = $year_semester_obj['period'];

    $username = $studentEnroll->GetStudentUsername($studentId);

    $student_obj = $studentEnroll->GetStudentCourseLevelYearIdCourseId($username);
    $student_course_level = $student_obj['course_level'];
    $course_id = $student_obj['course_id'];

    // echo $username;
    
    $checkIfEligibleToApply = $old_enroll->CheckIfStudentApplicableToApplyNextSemester($username, $active_school_year_id);
    $checkIfStudentAlreadyApplied = $old_enroll->checkIfStudentAlreadyApplied($username, $active_school_year_id);
    $checkAlignedSectionGr12 = $old_enroll->CheckGrade12AlignedSections($studentId);

    $GRADE_ELEVEN = 11;
    $GRADE_TWELVE = 12;
    $FIRST_SEMESTER = "First";
    $SECOND_SEMESTER = "Second";
    $in_active  = "no";

    $enroll_tentative = $con->prepare("INSERT INTO enrollment 
            (student_id, course_id, school_year_id, enrollment_status, registrar_evaluated)
            VALUES (:student_id,:course_id, :school_year_id, :enrollment_status, :registrar_evaluated)");

    $tentative_status = "tentative";
    $registrar_evaluated = "yes";
    $registrar_not_evaluated = "no";

    if($checkIfEligibleToApply == true 
        && $checkIfStudentAlreadyApplied == false
        && $checkAlignedSectionGr12 == false
        && $student_course_level == 11
        ){

        if($student_course_level == $GRADE_ELEVEN && $semester == $SECOND_SEMESTER){

            # Check if student_id and school_year_id is the same
            // Each student can only be assigned to a unique school year.
            
            $enroll_tentative->bindValue(":student_id", $studentId);
            # Course id will changed based
            # Grade 11 Section 101 
            # Grade 12 Section 101 will change depend on the capacity of the room.
            # and student volume inside.
            $enroll_tentative->bindValue(":course_id", $course_id);
            $enroll_tentative->bindValue(":school_year_id", $active_school_year_id);
            $enroll_tentative->bindValue(":enrollment_status", $tentative_status);
            $enroll_tentative->bindValue(":registrar_evaluated", $registrar_evaluated);

            if($enroll_tentative->execute()){
                echo "Able to apply Grade 11 (Process)";
            }else{
                echo "Able to apply Grade 11 (Process) went wrong.";
            }

        } 
    }

    else if($checkIfEligibleToApply == true 
        && $checkIfStudentAlreadyApplied == false
        && $checkAlignedSectionGr12 == false
        && $student_course_level == 12
        ){

        if($student_course_level == $GRADE_TWELVE && $semester === $FIRST_SEMESTER){

            $get_student_course = $con->prepare("SELECT course_id FROM course
                WHERE previous_course_id=:previous_course_id
                AND active=:active");

            $get_student_course->bindValue(":previous_course_id", $course_id);
            $get_student_course->bindValue(":active", "yes");
            $get_student_course->execute();
            
            if($get_student_course->rowCount() > 0){

                $moveUpCourseId = $get_student_course->fetchColumn();

                $enroll_tentative->bindValue(":student_id", $studentId);
                # Course id will changed based
                # Grade 11 Section 101 
                # Grade 12 Section 101 will change depend on the capacity of the room.
                # and student volume inside.
                $enroll_tentative->bindValue(":course_id", $moveUpCourseId);
                $enroll_tentative->bindValue(":school_year_id", $active_school_year_id);
                $enroll_tentative->bindValue(":enrollment_status", $tentative_status);
                $enroll_tentative->bindValue(":registrar_evaluated", $registrar_evaluated);

                if($enroll_tentative->execute()){
                    // echo "Able to apply (Process & Move Up)";

                    // Update Move_Up in Student Course_Id
                    // echo $moveUpCourseId;
                    $update_student_course = $con->prepare("UPDATE student
                        SET course_id=:move_up_course_id
                        WHERE course_id=:course_id
                        AND student_id=:student_id
                        ");
                    
                    $update_student_course->bindValue(":move_up_course_id", $moveUpCourseId);
                    $update_student_course->bindValue(":course_id", $course_id);
                    $update_student_course->bindValue(":student_id", $studentId);
                    $update_student_course->execute();

                    if($update_student_course->execute()){
                        echo "success registrar evaluated this ongoing enrollee";
                        echo "student course_id moves up to $moveUpCourseId";
                    }
                }
            }
        }
    }
    else  if($checkIfEligibleToApply == true 
        && $checkIfStudentAlreadyApplied == false
        && $checkAlignedSectionGr12 == true
        && $student_course_level == 12
    ){
 
        if($student_course_level == $GRADE_TWELVE && $semester === $SECOND_SEMESTER){

            $enroll_tentative->bindValue(":student_id", $studentId);
            # Course id will changed based
            # Grade 11 Section 101 
            # Grade 12 Section 101 will change depend on the capacity of the room.
            # and student volume inside.
            $enroll_tentative->bindValue(":course_id", $course_id);
            $enroll_tentative->bindValue(":school_year_id", $active_school_year_id);
            $enroll_tentative->bindValue(":enrollment_status", $tentative_status);
            $enroll_tentative->bindValue(":registrar_evaluated", $registrar_evaluated);

            if($enroll_tentative->execute()){
                echo "Able to apply Grade 12 (Process)";
            }
        }
    }

    else{
        echo "hmm";
    }
}

?>

<div class="row col-md-12">

    <div class="row col-md-10">
        
        <div class="row offset-md-1">
        <h4 class="text-center">Old Enrollees Enrollment</h4>
        <form class="row" action="" method="POST">
            <div class="col-md-6">
                <div class="form-group">
                    <label for="enrollmentId">Enrollment ID</label>
                    <input type="text" class="form-control" id="enrollmentId">
                </div>

                <div class="form-group">
                    <label for="studentId">Student ID</label>
                    <input value="205" type="text" class="form-control" id="studentId" name="studentId"> 
                </div>

                <!-- <div class="form-group">
                    <label for="studentId">Student Name</label>
                    <select class="form-control" id="student_name_id" name="student_name_id">
                        <?php

                            echo "<option value=''>Select Student</option>";
                            
                            $students = $enrollment->StudentQualifiedEnrollment();
                            foreach ($students as $key => $student) {
                                $firstname = $student['firstname'];
                                $lastname = $student['lastname'];
                                $student_id = $student['student_id'];
                                # code...
                                echo "<option value='".$student_id."'>".$firstname." ".$lastname."</option>";
                            }
                        ?>
                    </select>
                </div> -->

                <div class="form-group">
                    <label for="studentName">Student Name</label>
                    <input type="text" class="form-control" id="studentName" name="studentName"> 
                </div>
                
                <div class="form-group">
                    <label for="studentType">Student Type</label>
                    <!-- <select class="form-control" id="studentType">
                        <option>Regular</option>
                        <option>Irregular</option>
                    </select> -->
                    <input type="text" readonly class="form-control" name="studentType" id="studentType">

                </div>
            </div>
            <div class="col-md-6">
            
                <div class="form-group">
                <label for="gradeLevel">Grade Level</label>
            
                    <input type="text" name="gradeLevel" readonly class="form-control" id="gradeLevel">

                </div>
                <div class="form-group">
                    <label for="section">Section</label>
                
                    <input type="text" name="student_course_section" readonly class="form-control" id="student_course_section">

                </div>
                <div class="form-group">
                    <label for="term">Semester</label>
                    <!-- <select class="form-control" id="term">
                        <option>1st Term</option>
                        <option>2nd Term</option>
                    </select> -->

                    <input type="text" readonly class="form-control" id="term">

                </div>
            </div>
            <input type="hidden" id="student_course_id" name="student_course_id">

            <button type="submi" name="enroll_student_registrar_btn_v2" class="mt-3 btn btn-primary">Process</button>
        
        </form>
    <div class="form-group">
                <button id="getStudentNameBtn" class="btn btn-secondary">Get Student Name</button>
            </div>

    </div>

    <div class="mt-4 col-md-12" id="insert_table"></div>


</div>


<script>
    
    // $('#student_name_id').on('change', function() {

    //     var student_name_id = parseInt($(this).val());

    //     // var course_id = parseInt($("#course_id").val());
    //     // console.log(course_id);
        
    //     if (!student_name_id) {
    //         // $('#select_semester').html('<option value="">Select Semester</option>');
    //         return;
    //     }
    //     function myFunction() {
    //         console.log("Function executed!");
    //     }
    //     $.ajax({
    //         url: '../ajax/enrollee/show_student_level.php',
    //         type: 'POST',
    //         data: {
    //             student_id: student_name_id},

    //         // dataType: 'json',
    //         success: function(response) {

    //             if(response == "checkAlignedSectionGr12"){
    //                 if (confirm("Are you sure you want to execute this function?")) {
    //                     myFunction();
    //                     location.reload();
    //                 }
    //             }

    //             $('#student_course_section').val(response[0].program_section);
    //             $('#student_course_id').val(response[0].course_course_id);
    //             $('#gradeLevel').val(response[0].course_level);
    //             $('#studentType').val(response[0].student_status);
    //             $('#term').val(response[0].current_semester + " Semester");
    //             $('#studentId').val(response[0].student_id);
                
    //             // var options = '<option value="">Select a Subject</option>';
	// 			var html = `
	// 				<table  class="table table-striped table-bordered table-hover "  style="font-size:13px" cellspacing="0"  > 
    //                     <thead>
    //                         <tr class='text-center'> 
    //                             <th rowspan="2">Subject Id</th>
    //                             <th rowspan="2">Code</th>
    //                             <th rowspan="2">Title</th>  
    //                             <th rowspan="2">Unit</th>  
    //                             <th rowspan="2">Semester</th>  
    //                         </tr>	
    //                     </thead>
	// 			`;

    //             // $.each(response, function(index, value) {
    //             //     // options += '<option value="' + value.course_course_id + '">' + value.program_section +'</option>';

    //             //     // $('#student_course_id').val(value.program_section);
    //             //     // $('#gradeLevel').val(value.course_level);
    //             //     // $('#studentType').val(value.student_status);
    //             //     // $('#term').val(value.current_semester + " Semester");
    //             //     // $('#studentId').val(value.student_id);

	// 			// 	html += `
    //             //         <body>
    //             //             <tr class='text-center'>
    //             //                 <td>${value.subject_id}</td>
	// 			// 				<td>${value.subject_code}</td>
    //             //                 <td>${value.subject_title}</td>
    //             //                 <td>${value.unit}</td>
    //             //                 <td>${value.semester}</td>
    //             //             </tr>
	// 			// 	`;
    //             // });

                
	// 			html += `
    //                 </table>
    //             `;
    //             // $('#insert_table').html(html);
    //         }
    //     });
    // });

    $(document).ready(function() {

        $('#getStudentNameBtn').on('click', function() {
            var studentId = $('#studentId').val();

            $.ajax({
                url: '../ajax/enrollee/registrarManualConfirmProcessMoveUp.php', // replace with your PHP script URL

                method: 'POST',
                data: { studentId: studentId },
                dataType: 'json',

                success: function(response) {

                    $('#studentName').val(response.firstname + ' ' + response.lastname);
                    $('#gradeLevel').val(response.course_level);
                    $('#student_course_section').val(response.program_section);
                    $('#term').val(response.period);
                    $('#studentType').val(response.student_status);

                },
                error: function(xhr, status, error) {
                    if (xhr.status === 404) {
                        alert('Student not found!');
                    } else {
                        console.error(xhr.responseText);
                    }
                }
            });
        });
    });

    // function registrarManualConfirmProcessMoveUp(course_id, student_id) {
    //     $.ajax({
    //         url: '../ajax/enrollee/registrarManualConfirmProcessMoveUp.php', // replace with your PHP script URL
    //         type: 'POST',
    //         data: {
    //             // add any data you want to send to the server here
    //             course_id,
    //             student_id
    //         },
    //         success: function(response) {
    //             // console.log(response);
    //             alert(response)
    //             location.reload();
    //         },
    //         error: function(xhr, status, error) {
    //         }
    //     });
    // }
</script>

