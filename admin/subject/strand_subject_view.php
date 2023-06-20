<?php 

    include('../../enrollment/classes/Section.php');
    include('../admin_enrollment_header.php');
    include('../classes/Subject.php');


    $templateUrl = directoryPath . "template.php";

    // echo "im in subject enroll";
    if(!AdminUser::IsAuthenticated()){
        header("Location: /dcbt/adminLogin.php");
        exit();
    }
    if(isset($_GET['id'])){

        $program_id = $_GET['id'];

        $section = new Section($con, null);

        $strand_name = $section->GetAcronymByProgramId($program_id);
        $createUrl = directoryPath . "strand_subject_add.php?id=$program_id";

        $subject = new Subject($con, $registrarLoggedIn, null);
        $form = $subject->createForm();
        $selectSubjectTitle = $subject->SelectSubjectTitle();
        $selectSubjectEdit = $subject->SelectSubjectTitleEdit();

        require_once('./strand_subject_add_modal.php');
        require_once('./strand_subject_edit_modal.php');
        ?>
            <div class="row col-md-12">
                <div class="col-md-10 offset-md-1">
                    <h3 class="text-center"><?php echo $strand_name;?> Subjects</h3>
                    <div class="row justify-content-end">

                        <!-- <a class="mb-2" href="<?php echo $createUrl?>">
                            <button class="btn btn-sm btn-success">Attach Subject</button>
                        </a>     -->
                       
                        <button style="width: 150px; margin-bottom: 15px;" type="button" 
                            data-bs-target="#subjectAddModal" 
                            data-bs-toggle="modal"
                            data-program-id="<?php echo intval($program_id); ?>"
                            class="btn btn-sm btn-success attach-subject-button">Attach Subject
                        </button>

                    </div>
                 
                    <table id="strand_subject_view_table" class="table table-striped table-bordered table-hover "  style="font-size:13px" cellspacing="0"  > 
                        <thead>
                            <tr class="text-center"> 
                                <th rowspan="2">Subject</th>
                                <th rowspan="2">Code</th>
                                <th rowspan="2">Grade Level</th>
                                <th rowspan="2">Semester</th>
                                <th rowspan="2">Action</th>
                            </tr>	
                        </thead> 	
                        <tbody>
                            <?php 

                                $query = $con->prepare("SELECT * FROM subject_program

                                    WHERE program_id=:program_id
                                    ORDER BY course_level,
                                    semester");

                                $query->bindValue("program_id", $program_id);
                                $query->execute();

                                if($query->rowCount() > 0){
                                
                                    while ($row = $query->fetch(PDO::FETCH_ASSOC)) {

                                        $subject_program_id = $row['subject_program_id'];
                                        $subject_title = $row['subject_title'];
                                        $course_level = $row['course_level'];
                                        $semester = $row['semester'];
                                        $subject_code = $row['subject_code'];

                                        echo "
                                            <tr class='text-center'>
                                                <td>$subject_title</td>
                                                <td>$subject_code</td>
                                                <td>$course_level</td>
                                                <td>$semester</td>
                                                <td>
                                                    <button type='button' value='$subject_program_id'
                                                        class='editSubjectStrandBtn btn btn-success btn-sm'>

                                                        Edit
                                                    </button>
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
        <?php
    }
?>

<!-- <script>
    $(document).ready(function() {
        // Handler for the button click event
        $(".attach-subject-button").click(function() {
            // Retrieve the data-program-id attribute value
            var programId = $(this).data('program-id');
            
            // alert(programId);

            // Make an Ajax request to add.php

            $.ajax({
                url: 'add.php',
                method: 'POST',
                data: { program_id: programId },
                success: function(response) {
                    // Populate the form in the modal with the response
                    // $("#modal-form").html(response);
                    // console.log(response)
                },
                error: function() {
                    // Handle error case
                    alert("Error occurred. Please try again.");
                }
            });
        });
    });
</script> -->
