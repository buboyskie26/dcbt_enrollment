<?php

    include('../registrar_enrollment_header.php');
    include('../../enrollment/classes/StudentEnroll.php');
    include('../../admin/classes/Subject.php');
    require_once('../../admin/classes/AdminUser.php');

    if(!AdminUser::IsRegistrarAuthenticated()){
        header("Location: /dcbt/registrar_login.php");
        exit();
    }

    if(isset($_GET['id'])){

        $enroll = new StudentEnroll($con);

        $school_year_obj = $enroll->GetActiveSchoolYearAndSemester();

        $current_school_year_id = $school_year_obj['school_year_id'];
        $current_school_year_term = $school_year_obj['term'];
        $current_school_year_period = $school_year_obj['period'];

        $subject_id = $_GET['id'];

        $stmt = $con->prepare("SELECT * FROM subject WHERE subject_id = ?");

        $stmt->execute([$subject_id]);

        $subjectObj = $stmt->fetch();

        if (!$subjectObj) {
            // handle error, the 'id' value does not exist in the database
            echo "error id";
            exit();
        }

        $course_id = $subjectObj['course_id'];

    if(isset($_POST['create_section_subject_schedule_btn'])
        // && isset($_POST['course_id'])
        && isset($_POST['teacher_id'])
        && isset($_POST['room'])
        && isset($_POST['schedule_day'])
        && isset($_POST['time_from'])
        && isset($_POST['time_to'])
        && isset($_POST['time_from_am_pm'])
        && isset($_POST['time_to_am_pm'])
        ){
            // echo "qwe";

        $subject= new Subject($con, $registrarLoggedIn, $subject_id);

        $subject_name = $subject->GetSubjectName();
        
        $room = $_POST['room'];

        $schedule_day = $_POST['schedule_day'];

        $time_from = $_POST['time_from'];

        $time_to = $_POST['time_to'];

        $schedule_time = $time_from . "-" . $time_to;

        $teacher_id = $_POST['teacher_id'];

        // echo $teacher_id;

        $teacher_id = $teacher_id;

        $time_from_am_pm = $_POST['time_from_am_pm'];
        $time_to_am_pm = $_POST['time_to_am_pm'];


        $sql = $con->prepare("INSERT INTO subject_schedule
            (room, schedule_day, time_from, time_to, schedule_time, school_year_id, subject_id, teacher_id)
            VALUES(:room, :schedule_day, :time_from, :time_to, :schedule_time, :school_year_id, :subject_id, :teacher_id)");
        

        $schedule_time = $time_from . ' '. $time_from_am_pm . ' - ' . $time_to. ' ' . $time_to_am_pm;

        $sql->bindValue(":room", $room);
        $sql->bindValue(":schedule_day", $schedule_day);
        $sql->bindValue(":time_from", $time_from);
        $sql->bindValue(":time_to", $time_to);
        $sql->bindValue(":schedule_time", $schedule_time);
        $sql->bindValue(":school_year_id", $current_school_year_id);
        $sql->bindValue(":subject_id", $subject_id);
        $sql->bindValue(":teacher_id", $teacher_id);

        // $sql->execute();
        // if(false){
        if($sql->execute()){

            # First Step. Assigning schedule
            # Second Step. Adding subject_period -> Prelim, Midterm, Pre-finals, Finals
            
            $insert_subject_period = $con->prepare("INSERT INTO subject_period
                (term, title, description, subject_id, school_year_id)
                VALUES(:term, :title, :description, :subject_id, :school_year_id)");
        
            $insert_subject_period->bindValue(":term", "Prelim");
            $insert_subject_period->bindValue(":title", "Topic Name for $subject_name (Prelim)");
            $insert_subject_period->bindValue(":description", "");
            $insert_subject_period->bindValue(":subject_id", $subject_id);
            $insert_subject_period->bindValue(":school_year_id", $current_school_year_id);
            $insert_subject_period->execute();

            $insert_subject_period->bindValue(":term", "Midterm");
            $insert_subject_period->bindValue(":title", "Topic Name for $subject_name (Midterm)");
            $insert_subject_period->bindValue(":description", "");
            $insert_subject_period->bindValue(":subject_id", $subject_id);
            $insert_subject_period->bindValue(":school_year_id", $current_school_year_id);
            $insert_subject_period->execute();

            AdminUser::success("Subject $subject_id has been assign to Teacher: $teacher_id",
                "section_subject_list.php?id=$course_id");
        }
            

            //     $section_query = $con->prepare("SELECT program_section, room FROM course
            //         WHERE course_id=:course_id
            //         LIMIT 1");
            //     $section_query->bindValue(":course_id", $course_id);
            //     $section_query->execute();

            // }

            // $sql = $con->prepare("INSERT INTO subject_schedule
            //     (room, schedule_day, time_from, time_to, schedule_time, school_year_id, course_id, subject_id, teacher_id)
            //     VALUES(:room, :schedule_day, :time_from, :time_to, :schedule_time, :school_year_id, :course_id, :subject_id, :teacher_id)");
            

            // $schedule_time = $time_from . ' '. $time_from_am_pm . ' - ' . $time_to. ' ' . $time_to_am_pm;
            // $sql->bindValue(":room", $room);
            // $sql->bindValue(":schedule_day", $schedule_day);
            // $sql->bindValue(":time_from", $time_from);
            // $sql->bindValue(":time_to", $time_to);
            // $sql->bindValue(":schedule_time", $schedule_time);
            // $sql->bindValue(":school_year_id", $current_school_year_id);
            // $sql->bindValue(":course_id", $course_id);
            // $sql->bindValue(":subject_id", $subject_id);
            // $sql->bindValue(":teacher_id", $teacher_id);
            // // $sql->execute();

            // if($sql->execute()){
            // // if(true){
            //     AdminUser::success("Subject $subject_id has been inserted to the teacher", 'registrar_access_index.php');
            //     // header("Location: registrar_access_index.php");
            // }
        
        
        }

        ?>

        <div class='col-md-10 row offset-md-1'>

            <div class="card">
                <div class="card-header">
                    <h4 class='mb-3'>Assigning schedule of <?php echo $subjectObj['subject_title']?> </h4>
                    <h5 class="text-muted text-center">S.Y (<?php echo $current_school_year_term;?>) <?php echo $current_school_year_period;?> Semester</h5>
                </div>

                <div class="card-body">

                    <form method='POST'>
                        <div class="modal-body">

                            <div class="mb-3">
                                <label for="">Instructor</label>

                                <select class="form-control" name="teacher_id" id="teacher_id">
                                    <?php
                                        $query = $con->prepare("SELECT * FROM teacher");
                                        $query->execute();
                                        
                                        echo "<option value='' disabled selected>Choose Teacher</option>";

                                        if ($query->rowCount() > 0) {
                                            while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
                                                $selected = "";  

                                                // Add condition to check if the option should be selected
                                                if ($row['teacher_id'] == $selectedTeacherId) {
                                                    $selected = "selected";
                                                }

                                                echo "<option value='" . $row['teacher_id'] . "' $selected>" . $row['firstname'] . " " . $row['lastname'] . "</option>";
                                            }
                                        }
                                    ?>
                                </select>
                            </div>

                            <div class="mb-3">
                                <label for="">Subject</label>
                                <input type="text" name="subject_id" readonly value="<?php echo $subjectObj['subject_title']?>" class="form-control">
                            </div>

                            <div class="mb-3">
                                <label for="">Room number</label>
                                <input value='55' type="text" placeholder="(Room: 501)" name="room" id="room" class="form-control" />
                            </div>

                            <div class="mb-3">
                                <label for="">Time From</label>
                                <input type="text" value="8:00" placeholder="(7:00)" name="time_from" id="time_from" class="form-control" />
                            </div>

                            <div class="mb-3">
                                <label for="">Time From AM/PM</label>
                                <select name="time_from_am_pm" id="time_from_am_pm" class="form-control">
                                    <option value="AM">AM</option>
                                    <option value="PM">PM</option>
                                </select>
                            </div>

                            <div class="mb-3">
                                <label for="">Time to</label>
                                <input type="text" value="9:30" placeholder="(7:00)" name="time_to" id="time_to" class="form-control" />
                            </div>

                            <div class="mb-3">
                                <label for="">Time to AM/PM</label>
                                <select name="time_to_am_pm" id="time_to_am_pm" class="form-control">
                                    <option value=AM">AM</option>
                                    <option selected value="PM">PM</option>
                                </select>
                            </div>
    
                            <div class="mb-3">
                                <label for="schedule_day">Day</label>
                                <select name="schedule_day" id="schedule_day" class="form-control">
                                    <option value="">-- Select Day --</option>
                                    <option value="M">Monday</option>
                                    <option value="T">Tuesday</option>
                                    <option value="W">Wednesday</option>
                                    <option value="Th">Thursday</option>
                                    <option value="F">Friday</option>
                                </select>
                            </div>



                            <div class="mb-3">
                                <label for="">Semester</label>
                                <input type="text" readonly value="<?php echo $current_school_year_period?>" placeholder="Semester Period" name="semester" id="semester" class="form-control" />
                            </div>

                            <!-- <div class="form-group mb-4">
                                <label for="subject_id">Subject:</label>

                                <select class="form-control" name="subject_id" id="subject_id">
                                    <option value="">Pick Subject</option>
                                </select>
                            </div> -->
                        

                            <!-- <div class="form-group mb-4">
                                <label for="teacher">Select Teacher:</label>
                                <select class="form-control" name="teacher_id">
                                    <?php
                                        $query = $con->prepare("SELECT * FROM teacher");
                                        $query->execute();

                                        echo "<option value='' disabled selected>Select Teacher</option>";

                                        while($row = $query->fetch(PDO::FETCH_ASSOC)) {
                                            echo "<option value='" . $row['teacher_id'] . "'>" . $row['firstname'] . " " . $row['lastname'] . "</option>";
                                        }
                                    ?>
                                </select>
                            </div> -->

                        </div>
                        <div class="modal-footer">
                            <button name="create_section_subject_schedule_btn" type="submit" class="btn btn-primary">Save Schedule</button>
                        </div>
                    </form>

                </div>
            </div>


        </div>
        <?php
    }
?>

<!-- <script>
    
    $('#course_id').on('change', function() {

        var course_id = parseInt($(this).val());

        // var course_id = parseInt($("#course_id").val());
        // console.log(course_id);
        
        if (!course_id) {
            $('#select_semester').html('<option value="">Select Semester</option>');
            return;
        }

        $.ajax({
            url: '../ajax/get_semester.php',
            type: 'POST',
            data: {
                course_id: course_id},
            dataType: 'json',
            success: function(response) {

                // console.log(response)
                var options = '<option value="">Select a Subject</option>';

                $.each(response, function(index, value) {
                    options += '<option value="' + value.subject_id + '">' + value.subject_title +'</option>';
                });
                $('#subject_id').html(options);
            }
        });
    });

    // When the teacher select changes
    // $('#select_semester').on('change', function() {
        
    //     var school_year_id = parseInt($(this).val());

    //     var course_id = parseInt($("#course_id").val());

    //     if (!school_year_id) {
    //         $('#subject_id').html('<option value="">Select Subject</option>');
    //         return;
    //     }

    //     $.ajax({
    //         url: '../ajax/get_semester_subject.php',
    //         type: 'POST',
    //         data: {
    //             school_year_id: school_year_id,
    //             course_id: course_id},

    //         dataType: 'json',
    //         success: function(response) {

    //             console.log(response)

    //             var options = '<option value="">Select a Subject</option>';

    //                 $.each(response, function(index, value) {

    //                 options += '<option value="' + value.subject_id + '">' + value.subject_code + '</option>';

    //                 // console.log(value)
    //             });
    //             $('#subject_id').html(options);


    //         }
    //     });

    // });
</script> -->
