<?php

    include('../admin_enrollment_header.php');
    include('../../enrollment/classes/StudentEnroll.php');
    include('../../enrollment/classes/Section.php');
    include('../classes/Subject.php');

    if(!AdminUser::IsAuthenticated()){
        header("Location: /dcbt/adminLogin.php");
        exit();
    }

    $subject = new Subject($con, $registrarLoggedIn, null);
    $form = $subject->createForm();
    $selectSubjectTitle = $subject->SelectSubjectTitle();

    if(isset($_GET['id'])){
        $program_id = $_GET['id'];

        $section = new Section($con, null);
        $strand_name = $section->GetAcronymByProgramId($program_id);
        

        if(isset($_POST['create_strand_subject_btn'])){

            $course_level = $_POST['course_level'];
            $semester = $_POST['semester'];
            $subject_template_id = $_POST['subject_template_id'];
            $subject_code = $_POST['subject_code'];

            $get_subject_template = $con->prepare("SELECT * FROM subject_template
                    WHERE subject_template_id=:subject_template_id
                    LIMIT 1");

            $get_subject_template->bindValue(":subject_template_id", $subject_template_id);
            $get_subject_template->execute();

            if($get_subject_template->rowCount() > 0){

                $row = $get_subject_template->fetch(PDO::FETCH_ASSOC);

                $subject_title = $row['subject_title'];
                $description = $row['description'];
                $unit = $row['unit'];
                $subject_type = $row['subject_type'];
                $pre_requisite_title = $row['pre_requisite_title'];

                $create = $con->prepare("INSERT INTO subject_program
                        (program_id, subject_code, pre_req_subject_title, 
                            subject_title, unit, description, 
                            course_level, semester, subject_type)

                        VALUES(:program_id, :subject_code, :pre_req_subject_title,
                            :subject_title, :unit, :description,
                            :course_level, :semester, :subject_type)");
                                            
                $create->bindParam(':program_id', $program_id);
                $create->bindParam(':subject_code', $subject_code);
                $create->bindParam(':pre_req_subject_title', $pre_requisite_title);
                $create->bindParam(':subject_title', $subject_title);
                $create->bindParam(':unit', $unit);
                $create->bindParam(':description', $description);
                $create->bindParam(':course_level', $course_level);
                $create->bindParam(':semester', $semester);
                $create->bindParam(':subject_type', $subject_type);

                if($create->execute()){

                    echo "success";
                }
            }
        }

        ?>
            <div class='col-md-10 row offset-md-1'>

                <h4 class='text-center mb-3'>Attach Subject on <?php echo $strand_name;?> Subject</h4>

                <form method='POST' >
                    <?php echo $selectSubjectTitle;?>

                    <div class='form-group mb-2 '>
                        <label for="" class="mb-2">Code</label>
                        <input class='form-control' type='text'
                        placeholder='Subject Code' name='subject_code'>
                    </div>

                    <div class='form-group mb-2'>
                        <label for="" class="mb-2">Grade Level</label>
                        <select class='form-control' name='course_level'>
                            <option value='11'>Grade 11</option>
                            <option value='12'>Grade 12</option>
                        </select>
                    </div>
                    
                    <div class='form-group mb-2'>
                        <label for="" class="mb-2">Semester</label>
                        <select class='form-control' name='semester'>
                            <option value='First'>First</option>
                            <option value='Second'>Second</option>
                        </select>
                    </div>

                    <button type='submit' class='btn btn-primary' name='create_strand_subject_btn'>Save</button>
                </form>
            </div>
        <?php
    }

?>
 


<!-- <script>
    // alert('qwe');
    $('#program_id').on('change', function() {

        var program_id = parseInt($(this).val());

           $.ajax({
            url: '../ajax/subject/populateSubject.php',
            type: 'POST',
            data: {
                program_id: program_id},
            dataType: 'json',
            success: function(response) {

                console.log(response)
                var options = '<option value="">Select Subject</option>';

                $.each(response, function(index, value) {
                    options += '<option value="' + value.subject_template_id + '">' + value.subject_title +'   (' + value.subject_type +')</option>';
                });
                $('#subject_template_id').html(options);
            }
        });
    });

</script> -->

 