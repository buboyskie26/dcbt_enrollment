<?php

include('../admin_enrollment_header.php');
include('../../enrollment/classes/StudentEnroll.php');
include('../../enrollment/classes/Section.php');
require_once('../../admin/classes/AdminUser.php');

    if(!AdminUser::IsAuthenticated()){
        header("Location: /dcbt/adminLogin.php");
        exit();
    }

    if(isset($_GET['id'])){

        $subject_template_id = $_GET['id'];

        $section = new Section($con, null);

        $enroll = new StudentEnroll($con);
        $school_year_obj = $enroll->GetActiveSchoolYearAndSemester();

        $current_school_year_term = $school_year_obj['term'];
        $current_school_year_period = $school_year_obj['period'];

        $get_subject_template = $con->prepare("SELECT * 

            FROM subject_template

            WHERE subject_template_id=:subject_template_id
            LIMIT 1");
        
        $get_subject_template->bindValue(":subject_template_id", $subject_template_id);
        $get_subject_template->execute();


        // <select class='form-control' name='subject_type'>
        //                         <option value='CORE SUBJECTS'" . ($subject_type == 'CORE SUBJECTS' ? " selected" : "") . ">CORE SUBJECTS</option>
        //                         <option value='APPLIED SUBJECTS'" . ($subject_type == 'APPLIED SUBJECTS' ? " selected" : "") . ">APPLIED SUBJECTS</option>
        //                         <option value='SPECIALIZED_SUBJECTS'" . ($subject_type == 'SPECIALIZED_SUBJECTS' ? " selected" : "") . ">SPECIALIZED_SUBJECTS</option>
        //                     </select>

        if($get_subject_template->rowCount() > 0){

            $row = $get_subject_template->fetch(PDO::FETCH_ASSOC);


            $subject_title = $row['subject_title'];
            $subject_code = $row['subject_code'];
            $unit = $row['unit'];
            $description = $row['description'];
            $pre_requisite_title = $row['pre_requisite_title'];
            $subject_type = $row['subject_type'];


            // $program_id = $row['program_id'];
            
            if(isset($_POST['edit_subject_template'])){
                
                $subject_title = $_POST['subject_title'];
                $subject_code = $_POST['subject_code'];
                $description = $_POST['description'];
                $pre_requisite_title = $_POST['pre_requisite_title'];
                $unit = $_POST['unit'];
                $subject_type = $_POST['subject_type'];

                // Update the record in the database
                $query = $con->prepare("UPDATE subject_template 

                    SET subject_title = :subject_title,
                    subject_code = :subject_code,
                    description = :description,
                    pre_requisite_title = :pre_requisite_title,
                    unit = :unit,
                    subject_type = :subject_type
                    WHERE subject_template_id = :subject_template_id");

                $query->bindValue(":subject_title", $subject_title);
                $query->bindValue(":subject_code", $subject_code);
                $query->bindValue(":description", $description);
                $query->bindValue(":pre_requisite_title", $pre_requisite_title);
                $query->bindValue(":unit", $unit);
                $query->bindValue(":subject_type", $subject_type);
                $query->bindValue(":subject_template_id", $subject_template_id);

                if($query->execute()){
                    AdminUser::success("Template Successfully Edited", "list.php");
                    exit();
                }
            }

            echo "
                <div class='col-md-12 row'>
                <h4 class='text-center mb-3'>Edit Subject Program</h4>

                    <form method='POST'>
                        <div class='form-group mb-2'>
                            <input class='form-control' value='$subject_code' type='text' placeholder='Subject Code' name='subject_code'>
                        </div>

                        <div class='form-group mb-2'>
                            <input class='form-control' value='$subject_title' type='text' placeholder='Subject Title' name='subject_title'>
                        </div>

                        <div class='form-group mb-2'>
                            <textarea class='form-control'
                            placeholder='Subject Description'
                            name='description'>$description</textarea>
                        </div>

                        <div class='form-group mb-2'>
                            <input class='form-control' value='$pre_requisite_title' type='text'
                                placeholder='Pre-Requisite' name='pre_requisite_title'>
                        </div>
    
                        <div class='form-group mb-2'>

                            <select class='form-control' name='subject_type'>
                                <option value='Core'" . ($subject_type == 'Core' ? " selected" : "") . ">Core</option>
                                <option value='Applied'" . ($subject_type == 'Applied' ? " selected" : "") . ">Applied</option>
                                <option value='Specialized'" . ($subject_type == 'Specialized' ? " selected" : "") . ">Specialized</option>
                            </select>
                        </div>

                        <div class='form-group mb-2'>
                            <input value='$unit' class='form-control' type='text' placeholder='Unit' name='unit'>
                        </div>

                        <button type='submit' class='btn btn-primary'
                            name='edit_subject_template'>Save</button>
                    </form>
                </div>

            ";
 
        }
    }

?>