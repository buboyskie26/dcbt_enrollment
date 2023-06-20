


<?php
 
    include('../registrar_enrollment_header.php');
    include('../../enrollment/classes/StudentEnroll.php');
    include('../../enrollment/classes/OldEnrollees.php');
    include('../../enrollment/classes/Section.php');
    include('../../enrollment/classes/Enrollment.php');
    include('../../includes/classes/Student.php');
    include('../classes/Course.php');
    ?>
        <head>
            <link
                href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css"
                rel="stylesheet"
                integrity="sha384-KK94CHFLLe+nY2dmCWGMq91rCGa5gtU4mk92HdvYe+M/SXH301p5ILy+dN9+nJOZ"
                crossorigin="anonymous"
                />
            <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" />
            
            <!-- <style>

                .process-status{
                    display: flex;
                    flex-direction: row;
                    justify-content: center;
                    font-style: normal;
                    font-weight: 700;
                    align-items: center;
                    margin-top: 80px;
                    padding: 0px 53px;
                    gap: 1px;
                    isolation: isolate;
                    width: 100%;
                    height: 74px;
                    background: #1A0000;
                }

                .process-status .selection{
                    margin-top: 5px;
                }

                .process-status .checkDetails{
                    color: white;
                }

                .process-status #line-1, #line-2{
                    color: #888888;
                }

                .findSection, .subConfirm{
                    color: #888888;
                }

                .form-header h2{
                    font-style: normal;
                    font-weight: 700;
                    font-size: 36px;
                    line-height: 43px;
                    display: flex;
                    align-items: center;
                    color: #BB4444;
                }   

                .header-content{
                    display: flex;
                    flex-direction: row;
                    justify-content: space-between;
                    margin-top: 50px;
                    padding: 0px;
                    gap: 10px;
                    width: 90%;
                    height: 43px;
                    align-items: center;
                }

                .action{
                    border: none;
                    background: transparent;
                    color: #E85959;
                }

                .action:hover{
                    color: #9b3131;
                }

                .student-table{
                    display: flex;
                    flex-direction: row;
                    align-items: flex-start;
                    padding: 5px 0px;
                    width: 100%;
                    height: 58px;
                }

                table{
                    table-layout: fixed;
                    border-collapse: collapse;
                    width: 100%;
                    text-align: center;
                }

                tbody{
                    font-style: normal;
                    font-weight: 400;
                    font-size: 17px;
                    align-items: center;
                }

                .choices{
                    display: flex;
                    flex-direction: row;
                    justify-content: center;
                    align-items: center;
                    margin-top: 80px;
                    padding: 20px 53px 0px;
                    gap: 1px;
                    width: 100%;
                    height: 74px;
                    background: #1A0000;
                    flex: none;
                    order: 2;
                    align-self: stretch;
                    flex-grow: 0;
                }
                .selection-btn{
                    display: flex;
                    flex-direction: row;
                    justify-content: center;
                    align-items: center;
                    padding: 5px 20px;
                    gap: 10px;
                    width: 340px;
                    height: 54px;
                    background: #EFEFEF;
                    border: none;
                    font-style: normal;
                    font-weight: 400;
                    font-size: 20px;
                }

                .bg-content{
                    display: flex;
                    flex-direction: column;
                    align-items: center;
                    padding: 50px 0px;
                    width: 100%;
                    height: auto;
                    background: #EFEFEF;
                }

                .form-details{
                    display: flex;
                    flex-direction: column;
                    justify-content: center;
                    align-items: flex-start;
                    padding: 32px 26px;
                    gap: 19px;
                    width: 85%;
                    height: auto;
                    background: #FFFFFF;
                    box-shadow: 0px 5px 20px rgba(0, 0, 0, 0.25);
                    border-radius: 10px;
                    margin-top: 30px;
                }

                .form-details h3{
                    display: flex;
                    align-items: center;
                    font-style: normal;
                    font-weight: 700;
                    font-size: 36px;
                    line-height: 43px;
                    color: #BB4444;
                }

                form{
                    flex: none;
                    order: 1;
                    align-self: stretch;
                    flex-grow: 0;
                }
                .back-menu{
                    display: flex;
                    flex: row;
                    align-items: center;
                    padding: 8px 40px;
                    gap: 8px;
                    width: 100%;
                    height: 46px;
                }
                .admission-btn{
                    border: none;
                    background: none;
                    color: #BB4444;
                    font-style: normal;
                    font-weight: 700;
                    font-size: 16px;
                }
                .admission-btn:hover{
                    color: #863131;
                }

            </style> -->

            <!-- <link rel="stylesheet" href="../../admin/assets/css/enrollees/subject_insertion.css"> -->

            <link rel="stylesheet" href="../../admin/assets/css/admission/process_enrollment.css">

        </head>
 
    <?php



    if(!AdminUser::IsRegistrarAuthenticated()){
        header("Location: /dcbt/adminLogin.php");
        exit();
    }


    $studentEnroll = new StudentEnroll($con);
    $old_enrollees = new OldEnrollees($con, $studentEnroll);

    $school_year_obj = $studentEnroll->GetActiveSchoolYearAndSemester();

    $current_school_year_id = $school_year_obj['school_year_id'];
    $current_school_year_term = $school_year_obj['term'];
    $current_school_year_period = $school_year_obj['period'];

    if(isset($_GET['id'])){

        $pending_enrollees_id = $_GET['id'];

        unset($_SESSION['pending_enrollees_id']);
        unset($_SESSION['process_enrollment']);

        $enrollment = new Enrollment($con, $studentEnroll);
        
        $enrollment_form_id = $enrollment->GenerateEnrollmentFormId();

        if (!isset($_SESSION['enrollment_form_id'])) {
            $enrollment_form_id = $enrollment->GenerateEnrollmentFormId();
            $_SESSION['enrollment_form_id'] = $enrollment_form_id;

        } else {
            $enrollment_form_id = $_SESSION['enrollment_form_id'];
        }


        $sql = $con->prepare("SELECT * FROM pending_enrollees
                WHERE pending_enrollees_id=:pending_enrollees_id
            ");

        $sql->bindValue(":pending_enrollees_id", $pending_enrollees_id);
        $sql->execute();

        $row = null;

        $course_id = 0;

        if($sql->rowCount() > 0){

            $row = $sql->fetch(PDO::FETCH_ASSOC);

            $program_id = $row['program_id'];

            $firstname = $row['firstname'];

            $middle_name = $row['middle_name'];
            $lastname = $row['lastname'];
            $birthday = $row['birthday'];
            $address = $row['address'];
            $sex = $row['sex'];
            $contact_number = $row['contact_number'];
            $date_creation = $row['date_creation'];
            $student_status = $row['student_status'];
            $email = $row['email'];
            $pending_enrollees_id = $row['pending_enrollees_id'];
            $password = $row['password'];
            $civil_status = $row['civil_status'];
            $nationality = $row['nationality'];
            $age = $row['age'];
            $guardian_name = $row['guardian_name'];
            $guardian_contact_number = $row['guardian_contact_number'];
            $lrn = $row['lrn'];
            $birthplace = $row['birthplace'];
            $religion = $row['religion'];
            $email = $row['email'];
            $type = $row['type'];

            $program = $con->prepare("SELECT acronym FROM program
                WHERE program_id=:program_id
                LIMIT 1
            ");
            $program->bindValue(":program_id", $program_id);
            $program->execute();

            $program_acronym = $program->fetchColumn();

            $student_fullname = $firstname . " " . $lastname;

            $section = new Section($con, null);

            // $program_id = $section->GetProgramIdBySectionId($student_course_id);
            $strand_name = $section->GetAcronymByProgramId($program_id);
            $track_name = $section->GetTrackByProgramId($program_id);
    
            if(isset($_GET['id']) && isset($_GET['step1'])){

                ?>
                    <div class="row col-md-12">


                        <!--  -->

                        <div class="back-menu">
                            <button type="button" class="admission-btn" onclick="find_form()">
                            <i class="bi bi-arrow-left-circle"></i> Admission
                            </button>
                        </div>
                        
                        <div class="enrollment-form-container">

                            <div class="enrollment-form-upper">
                                <h3>Enrollment Form</h3>
                                <i class="fa-solid fa-ellipsis-vertical"></i>
                            </div>

                            <div class="form-upper-second-container">
                                <div class="form-inner">
                                    <h5>Form ID</h5>
                                    <p><?php echo $enrollment_form_id;?></p>
                                </div>
                                <div class="form-inner">
                                    <h5>Admission Type</h5>
                                    <p>New</p>
                                </div>
                                <div class="form-inner">
                                    <h5>Student No</h5>
                                    <p>N/A</p>
                                </div>
                                <div class="form-inner">
                                    <h5>Status</h5>
                                    <p>Evaluation</p>
                                </div>
                                <div class="form-inner">
                                    <h5>Submitted on</h5>
                                    <p>
                                        <?php
                                            $date = new DateTime($date_creation);
                                            $formattedDate = $date->format('m/d/Y H:i');
                                            echo $formattedDate;
                                        ?>
                                    </p>
                                </div>
                            </div>

                            <div class="process-status">
                                <table class="selection">
                                    <tr>
                                        <th class="checkDetails" id="icon-1">
                                            <i  style="color: #FFFF;" class="bi bi-clipboard-check"></i>
                                        </th>

                                        <th style="color: #333;" id="line-1">___________________________</th>

                                        <th  class="findSection" id="icon-2">
                                            <i style="color: #FFFF;" class="bi bi-building"></i>
                                        </th>

                                        <th style="color: #333;" id="line-2">___________________________</th>

                                        <th class="subConfirm" id="icon-3">
                                            <i class="bi bi-journal"></i>
                                        </th>

                                    </tr>
                                    <tr>
                                        <td style="color: #FFFF;" class="checkDetails" id="process-1">Check details</td>
                                        <td></td>
                                        <td style="color: #FFFF;" class="findSection" id="process-2">Find section</td>
                                        <td></td>
                                        <td class="subConfirm" id="process-3">Subject Confirmation</td>
                                    </tr>
                                </table>
                            </div>

                        </div>

                        <div class="height-student-details">
                            <div class="step-3-section-container">

                                <div class="form-details-enrolled" id="subjects-details">
                                    <div class="form-details" id="check-details">
                                        <h3>Student details</h3>

                                        <form class="row g-3">
                                            <div class="col-md-4">
                                            <label for="inputFirstName"  class="form-label">First name</label>
                                            <input type="text" value='<?php echo $firstname;?>' class="form-control" id="inputFirstName" />
                                            </div>
                                            <div class="col-md-4">
                                            <label for="inputLastName"  class="form-label">Last name</label>
                                            <input type="text" value='<?php echo $lastname;?>' class="form-control" id="inputLastName" />
                                            </div>
                                            <div class="col-md-4">
                                            <label for="inputMiddleName"  class="form-label">Middle name</label
                                            >
                                            <input type="text" value='<?php echo $middle_name;?>' class="form-control" id="inputMiddleName" />
                                            </div>
                                            <div class="col-md-4">
                                                <label for="inputBirthdate" class="form-label">Birthdate</label>
                                                <input type="date" value='<?php echo $birthday;?>' class="form-control" id="inputBirthdate" />
                                            </div>

                                            <div class="col-md-4">
                                            <label for="inputGender" class="form-label">Gender</label>
                                            <select id="inputGender" class="form-select">
                                                <option <?php if ($sex == 'Male') echo 'selected'; ?>>Male</option>
                                                <option <?php if ($sex == 'FeMale') echo 'selected'; ?>>Male</option>
                                            </select>
                                            </div>
                                            <div class="col-md-4">
                                            <label for="inputContactNo" class="form-label">Contact no.</label>
                                            <input type="text" value='<?php echo $contact_number;?>' class="form-control" id="inputContactNo" />
                                            </div>
                                            <div class="col-12">
                                            <label for="inputAddress" class="form-label">Address</label>
                                            <input type="text" value='<?php echo $address;?>' class="form-control" id="inputAddress" />
                                            </div>
                                        
                                        </form>
                                    </div>


                                </div>
                            </div>

                            <?php
                            
                                
                            ?>
                            <div class="mt-3 row col-md-12 text-right">
                                <a style='text-align: end;' href='process_enrollment.php?step2=true&id=<?php echo $pending_enrollees_id;?>'>
                                    <button type='button'class='button-style-primary primary'>Proceed</button>
                                </a>
                            </div>
                            
                                 
                        </div>




                        <!--  -->




                        <div class="content">

                            <div class="form-header">
                                <div class="header-content">
                                    <h2>Enrollment form</h2>
                                </div>

                                <div class="student-table">
                                    <table>
                                        <tr>
                                        <th>Form ID</th>
                                        <th>Admission type</th>
                                        <th>Student no</th>
                                        <th>Status</th>
                                        <th>Submitted on:</th>
                                        </tr>
                                        <tr>
                                        <td><?php echo $enrollment_form_id;?></td>
                                        <td>New</td>
                                        <td>N/A</td>
                                        <td>Evaluation</td>

                                        <td><?php
                                            $date = new DateTime($date_creation);
                                            $formattedDate = $date->format('m/d/Y H:i');

                                            echo $formattedDate;
                                        ?></td>
                                       
                                        </tr>
                                    </table>
                                </div>
                            </div>
                        </div>

                        <!-- First 2nd -->
                        <div class="process-status">
                            <table class="selection">
                                <tr>
                                    <th class="checkDetails" id="icon-1">
                                    <i class="bi bi-clipboard-check"></i>
                                    </th>
                                    <th id="line-1">___________________________</th>
                                    <th class="findSection" id="icon-2">
                                    <i class="bi bi-building"></i>
                                    </th>
                                    <th id="line-2">___________________________</th>
                                    <th class="subConfirm" id="icon-3">
                                    <i class="bi bi-journal"></i>
                                    </th>
                                </tr>
                                <tr>
                                    <td class="checkDetails" id="process-1">Check details</td>
                                    <td></td>
                                    <td class="findSection" id="process-2">Find section</td>
                                    <td></td>
                                    <td class="subConfirm" id="process-3">Subject Confirmation</td>
                                </tr>
                            </table>
                        </div>

                        <!-- Enrollment Form -->
                        <div style="display: none;" class="container">
                            <h3 class="text-center text-primary">Enrollment Form</h3>
                            <div class="card ">
                                <div class="card-body">
                                    <div class="row col-md-12">
                                        <div class="mb-4 col-md-3">
                                            <label for="">Form Id</label>
                                            <input readonly style="width: 100%;" type="text" 
                                                value='<?php echo $enrollment_form_id;?>' 
                                                class="form-control">
                                        </div>
                                    
                                        <div class="mb-4 col-md-3">
                                            <label for="">Admission Type</label>
                                            <input readonly style="width: 100%;" type="text" 
                                                value="<?php echo ($student_status == 'Regular') ? 'New' : $student_status; ?>" 
                                                class="form-control">
                                        </div>

                                        <div class="mb-4 col-md-3">
                                            <label for="">Status</label>
                                            <input readonly style="width: 100%;" type="text" 
                                                value='Evaluation' class="form-control">
                                        </div>

                                        <div class="mb-4 col-md-3">
                                            <label for="">Date Submitted</label>
                                            <input readonly style="width: 100%;" type="text" 
                                                value='<?php echo $date_creation?>' class="form-control">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="bg-content">
                            <div class="form-details" id="check-details">
                                <h3>Student details</h3>

                                <form class="row g-3">
                                    <div class="col-md-4">
                                    <label for="inputFirstName"  class="form-label">First name</label>
                                    <input type="text" value='<?php echo $firstname;?>' class="form-control" id="inputFirstName" />
                                    </div>
                                    <div class="col-md-4">
                                    <label for="inputLastName"  class="form-label">Last name</label>
                                    <input type="text" value='<?php echo $lastname;?>' class="form-control" id="inputLastName" />
                                    </div>
                                    <div class="col-md-4">
                                    <label for="inputMiddleName"  class="form-label">Middle name</label
                                    >
                                    <input type="text" value='<?php echo $middle_name;?>' class="form-control" id="inputMiddleName" />
                                    </div>
                                    <div class="col-md-4">
                                        <label for="inputBirthdate" class="form-label">Birthdate</label>
                                        <input type="date" value='<?php echo $birthday;?>' class="form-control" id="inputBirthdate" />
                                    </div>

                                    <div class="col-md-4">
                                    <label for="inputGender" class="form-label">Gender</label>
                                    <select id="inputGender" class="form-select">
                                         <option <?php if ($sex == 'Male') echo 'selected'; ?>>Male</option>
                                         <option <?php if ($sex == 'FeMale') echo 'selected'; ?>>Male</option>
                                    </select>
                                    </div>
                                    <div class="col-md-4">
                                    <label for="inputContactNo" class="form-label">Contact no.</label>
                                    <input type="text" value='<?php echo $contact_number;?>' class="form-control" id="inputContactNo" />
                                    </div>
                                    <div class="col-12">
                                    <label for="inputAddress" class="form-label">Address</label>
                                    <input type="text" value='<?php echo $address;?>' class="form-control" id="inputAddress" />
                                    </div>
                                   
                                </form>
                            </div>

                            <?php
                                echo "
                                    <a style='text-align: end;' href='process_enrollment.php?step2=true&id=$pending_enrollees_id'>
                                        <button type='button'class='btn-sm btn-primary'>Proceed</button>
                                    </a>
                                ";
                            ?>
                        </div>
                    </div>
                <?php
            }

            # SHS STEP 2
            if(isset($_GET['id']) && isset($_GET['step2'])){

                if(isset($_POST['pending_choose_section'])
                    && isset($_POST['selected_course_id'])
                    ){

                    $selected_course_id_value = $_POST['selected_course_id'];

                    $sectionFull = $section->CheckSectionIsFull($selected_course_id_value);

                    if($sectionFull) {
                        // AdminUser::error("Selected section is full, Choose other or Create new section.", "");
                        // return;

                        $section_url = "process_enrollment.php?step3=true&id=$pending_enrollees_id&selected_course_id=$selected_course_id_value";
                        header("Location: $section_url");
                    }else{
                        // echo "qweqwe";

                        $section_url = "process_enrollment.php?step3=true&id=$pending_enrollees_id&selected_course_id=$selected_course_id_value";
                        header("Location: $section_url");
                    // exit();
                    }

                   
                }


                // echo $program_id;
                ?>
                    <div class="col-md-12 row">

                        <!-- START 2nd  -->

                        <div class="back-menu">
                            <button type="button" class="admission-btn" onclick="find_form()">
                            <i class="bi bi-arrow-left-circle"></i> Admission
                            </button>
                        </div>
                        
                        <div class="enrollment-form-container">

                            <div class="enrollment-form-upper">
                                <h3>Enrollment Form</h3>
                                <i class="fa-solid fa-ellipsis-vertical"></i>
                            </div>

                            <div class="form-upper-second-container">
                                <div class="form-inner">
                                    <h5>Form ID</h5>
                                    <p><?php echo $enrollment_form_id;?></p>
                                </div>
                                <div class="form-inner">
                                    <h5>Admission Type</h5>
                                    <p>New</p>
                                </div>
                                <div class="form-inner">
                                    <h5>Student No</h5>
                                    <p>N/A</p>
                                </div>
                                <div class="form-inner">
                                    <h5>Status</h5>
                                    <p>Evaluation</p>
                                </div>
                                <div class="form-inner">
                                    <h5>Submitted on</h5>
                                    <p>
                                        <?php
                                            $date = new DateTime($date_creation);
                                            $formattedDate = $date->format('m/d/Y H:i');
                                            echo $formattedDate;
                                        ?>
                                    </p>
                                </div>
                            </div>

                            <div class="process-status">
                                <table class="selection">
                                    <tr>
                                        <th class="checkDetails" id="icon-1">
                                            <i  style="color: #FFFF;" class="bi bi-clipboard-check"></i>
                                        </th>

                                        <th style="color: #FFFF;" id="line-1">___________________________</th>

                                        <th  class="findSection" id="icon-2">
                                            <i style="color: #FFFF;" class="bi bi-building"></i>
                                        </th>

                                        <th style="color: #333;" id="line-2">___________________________</th>

                                        <th class="subConfirm" id="icon-3">
                                            <i class="bi bi-journal"></i>
                                        </th>

                                    </tr>
                                    <tr>
                                        <td style="color: #FFFF;" class="checkDetails" id="process-1">Check details</td>
                                        <td></td>
                                        <td style="color: #FFFF;" class="findSection" id="process-2">Find section</td>
                                        <td></td>
                                        <td class="subConfirm" id="process-3">Subject Confirmation</td>
                                    </tr>
                                </table>
                            </div>

                        </div>

                        <div class="height-enrolled-details">
                            <div class="enrolled-details-container">
                                <!--enrolled-subjects-->
                                <div class="form-details-enrolled" id="enrollment-form">
                                    <h3>Enrollment Details</h3>

                                    <?php
                                        if($type == "SHS"){

                                            ?>
                                            <form class="row g-3">
                                                <div class="col-md-4">
                                                <label for="inputSchoolYear" class="form-label">S.Y.</label>
                                                <input readonly type="text" value="<?php echo $current_school_year_term; ?>" class="form-control" id="inputSchoolYear" />
                                                </div>
                                                <div class="col-md-4">
                                                    <label for="inputTrack" class="form-label">Track</label>

                                                    <select id="inputTrack" class="form-select">
                                                        <?php 

                                                            $SHS_DEPARTMENT = 4;
                                                        
                                                            $track_sql = $con->prepare("SELECT 
                                                            program_id, track, acronym 
                                                                
                                                                FROM program 
                                                            WHERE department_id =:department_id
                                                            GROUP BY track
                                                            ");

                                                            $track_sql->bindValue(":department_id", $SHS_DEPARTMENT);
                                                            $track_sql->execute();
                                                            

                                                            while($row = $track_sql->fetch(PDO::FETCH_ASSOC)){

                                                                $row_program_id = $row['program_id'];

                                                                $track = $row['track'];

                                                                $selected = ($row_program_id == $program_id) ? "selected" : "";

                                                                echo "<option value='$row_program_id' $selected>$track</option>";
                                                            }
                                                        ?>
                                                        <!-- <option selected>TVL</option>
                                                        <option>Arts and Design</option> -->
                                                    </select>
                                                </div>

                                                <div class="col-md-4">
                                                    <label for="inputStrand" class="form-label">Strand</label>

                                                    <select onchange="chooseStrand(this, <?php echo $pending_enrollees_id;?>)" 
                                                        name="strand" id="strand" class="form-select">
                                                        <?php 

                                                            $SHS_DEPARTMENT = 4;
                                                        
                                                            $track_sql = $con->prepare("SELECT 
                                                            program_id, track, acronym 
                                                                
                                                                FROM program 
                                                            WHERE department_id =:department_id
                                                            GROUP BY acronym
                                                            ");

                                                            $track_sql->bindValue(":department_id", $SHS_DEPARTMENT);
                                                            $track_sql->execute();

                                                            while($row = $track_sql->fetch(PDO::FETCH_ASSOC)){

                                                                $row_program_id = $row['program_id'];

                                                                $acronym = $row['acronym'];

                                                                $selected = ($row_program_id == $program_id) ? "selected" : "";

                                                                echo "<option value='$row_program_id' $selected>$acronym</option>";
                                                            }
                                                        ?>

                                                    </select>
                                                </div>
                                                <div class="col-md-6">
                                                    <label for="inputYear" class="form-label">Year</label>
                                                
                                                    <select id="inputYear" class="form-select">

                                                        <option value="11" selected>Grade 11</option>
                                                        <option  value="12">Grade 12</option>
                                                    
                                                    </select>
                                                </div>
                                                <div class="col-md-6">
                                                <label for="inputSemester" class="form-label">Semester</label>
                                                <input readonly type="text" value="<?php echo $current_school_year_period; ?>" class="form-control" id="inputSchoolYear" />
                                                <!-- <select id="inputSemester" class="form-select">
                                                    <option <?php if ($current_school_year_period == 'First') echo 'selected'; ?>>First</option>
                                                    <option <?php if ($current_school_year_period == 'Second') echo 'selected'; ?>>Second</option>
                                                </select> -->
                                                </div>
                                            </form>
                                            <?php
                                        }
                                        else if($type == "Tertiary"){
                                            ?>

                                            <form class="row g-3">
                                                <div class="col-md-4">
                                                    <label for="inputSchoolYear" class="form-label">S.Y.</label>
                                                    <input readonly type="text" value="<?php echo $current_school_year_term; ?>" class="form-control" id="inputSchoolYear" />
                                                </div>
                                                <div class="col-md-4">
                                                    <label for="inputTrack" class="form-label">Track</label>

                                                    <select id="inputTrack" class="form-select">
                                                        <?php 

                                                            $SHS_DEPARTMENT = 4;
                                                        
                                                            $track_sql = $con->prepare("SELECT 
                                                            program_id, track, acronym 
                                                                
                                                                FROM program 
                                                            WHERE department_id !=:department_id
                                                            GROUP BY track
                                                            ");

                                                            $track_sql->bindValue(":department_id", $SHS_DEPARTMENT);
                                                            $track_sql->execute();
                                                            
                                                            while($row = $track_sql->fetch(PDO::FETCH_ASSOC)){

                                                                $row_program_id = $row['program_id'];

                                                                $track = $row['track'];

                                                                $selected = ($row_program_id == $program_id) ? "selected" : "";

                                                                echo "<option value='$row_program_id' $selected>$track</option>";
                                                            }
                                                        ?>
                                                    
                                                    </select>
                                                </div>

                                                <div class="col-md-4">
                                                    <label for="inputStrand" class="form-label">Strand</label>

                                                    <select onchange="chooseStrand(this, <?php echo $pending_enrollees_id;?>)" 
                                                        name="strand" id="strand" class="form-select">
                                                        <?php 

                                                            $SHS_DEPARTMENT = 4;
                                                        
                                                            $track_sql = $con->prepare("SELECT 
                                                            program_id, track, acronym 
                                                                
                                                                FROM program 
                                                            WHERE department_id !=:department_id
                                                            GROUP BY acronym
                                                            ");

                                                            $track_sql->bindValue(":department_id", $SHS_DEPARTMENT);
                                                            $track_sql->execute();

                                                            while($row = $track_sql->fetch(PDO::FETCH_ASSOC)){

                                                                $row_program_id = $row['program_id'];

                                                                $acronym = $row['acronym'];

                                                                $selected = ($row_program_id == $program_id) ? "selected" : "";

                                                                echo "<option value='$row_program_id' $selected>$acronym</option>";
                                                            }
                                                        ?>

                                                    </select>
                                                </div>

                                                <div class="col-md-6">
                                                    <label for="inputYear" class="form-label">Year</label>
                                                
                                                    <select id="inputYear" class="form-select">

                                                        <option value="1" selected>1st Year</option>
                                                        <option value="2">2nd Year</option>
                                                        <option value="3">3rd Year</option>
                                                        <option value="4">4th Year</option>
                                                    </select>
                                                </div>

                                                <div class="col-md-6">
                                                    <label for="inputSemester" class="form-label">Semester</label>
                                                    <input readonly type="text" value="<?php echo $current_school_year_period; ?>" class="form-control" id="inputSchoolYear" />
                                                    <!-- <select id="inputSemester" class="form-select">
                                                        <option <?php if ($current_school_year_period == 'First') echo 'selected'; ?>>First</option>
                                                        <option <?php if ($current_school_year_period == 'Second') echo 'selected'; ?>>Second</option>
                                                    </select> -->
                                                    </div>
                                            </form>

                                            <?php
                                        }
                                    ?>

                                </div>
                                <form style="width: 100%;margin-left: 21px;" method="POST">

                                    <div class="form-details-enrolled" id="subjects-details">
                                            <h3>Available Section</h3>

                                            <?php 
                                                if($section->CheckIfProgramHasSection($program_id,
                                                    $current_school_year_term) == false){

                                                    echo "
                                                        <a href='../section/create.php'>
                                                            <button class='btn btn-success'>Add Section</button>
                                                        </a>
                                                    ";
                                                }
                                            
                                            ?>
                                                <table style="font-size: 15px;"> 
                                                    <thead>
                                                        <tr class="text-center"> 
                                                            <th rowspan="2">Section Id</th>
                                                            <th rowspan="2">Section Name</th>
                                                            <th rowspan="2">Student</th>
                                                            <th rowspan="2">Capacity</th>
                                                            <th rowspan="2">Term</th>
                                                            <th rowspan="2"></th>
                                                        </tr>
                                                    </thead> 
                                                    <tbody>
                                                        <?php
                                                            $course_level = 11;
                                                            $active = "yes";

                                                            # Only Available now.
                                                            $sql = $con->prepare("SELECT * FROM course

                                                                WHERE program_id=:program_id
                                                                AND active=:active
                                                                AND school_year_term=:school_year_term");

                                                            $sql->bindValue(":program_id", $program_id);
                                                            $sql->bindValue(":active", $active);
                                                            $sql->bindValue(":school_year_term", $current_school_year_term);

                                                            $sql->execute();
                                                        
                                                            if($sql->rowCount() > 0){

                                                                while($get_course = $sql->fetch(PDO::FETCH_ASSOC)){

                                                                    $course_id = $get_course['course_id'];
                                                                    $program_section = $get_course['program_section'];
                                                                    $capacity = $get_course['capacity'];
                                                                    $school_year_term = $get_course['school_year_term'];
                                                                    $section = new Section($con, $course_id);

                                                                    $section_obj = $section->GetSectionObj($course_id);

                                                                    $totalStudent = $section->GetTotalNumberOfStudentInSection($course_id, $current_school_year_id);
                                                                    $capacity = $section_obj['capacity'];

                                                                    $program_id = $section_obj['program_id'];
                                                                    $course_level = $section_obj['course_level'];

                                                                    $removeSection = "removeSection($course_id, \"$program_section\")";

                                                                    // echo $totalStudent;
                                                                    // echo $program_id;

                                                                    $new_program_section = $section->AutoCreateAnotherSection($program_section);

                                                                    // echo $program_section;
                                                                    // echo "<br>";
                                                                    // echo $new_program_section;

                                                                    // $isCloseddisabled = "<input name='selected_course_id' 
                                                                    //     class='radio' value='$course_id' 
                                                                    //     type='radio' " . ($course_id == $student_course_id ? "checked" : "") . " " . ($isClosed ? "disabled" : "") . ">";   
                                                                


                                                                    if($totalStudent == $capacity){

                                                                    }
                                                                    echo "
                                                                    <tr class='text-center'>
                                                                        <td>$course_id</td>
                                                                        <td>$program_section</td>
                                                                        <td>$totalStudent</td>
                                                                        <td>$capacity</td>
                                                                        <td>$school_year_term</td>
                                                                        <td>
                                                                            <input name='selected_course_id' class='radio' value='$course_id' type='radio' " . (($totalStudent == $capacity) ? "disabled" : "") . ">
                                                                            <i onclick='$removeSection' style='cursor: pointer; color: orange; " . ($totalStudent != 0 ? "display: none;" : "") . "' class='fas fa-times'></i>

                                                                        </td>
                                                                    </tr>
                                                                ";
                                                                }
                                                                
                                                            }else{
                                                                echo "
                                                                    <div class='col-md-12'>
                                                                        <h4 class='text-center text-muted'>No currently available section for $program_acronym</h4>
                                                                    </div>
                                                                ";
                                                            }
                                                        ?>
                                                    </tbody>
                                                </table>                                                                 
                                    </div>

                                    <div class="available-section-bottom">

                                        <a href="process_enrollment.php?step1=true&id=<?php echo $pending_enrollees_id;?> ">
                                            <button type="button" class="button-style-primary primary">Return</button>
                                        </a>

                                        <button button type="submit" name="pending_choose_section"
                                            class="button-style-success success">Proceed
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>

                        <!--  -->
                            
                        <div class="content">

                            <div class="form-header">
                                <div class="header-content">
                                    <h2>Enrollment form</h2>
                                </div>

                                <div class="student-table">
                                    <table>
                                        <tr>
                                        <th>Form ID</th>
                                        <th>Admission type</th>
                                        <th>Student no</th>
                                        <th>Status</th>
                                        <th>Submitted on:</th>
                                        </tr>
                                        <tr>
                                        <td><?php echo $enrollment_form_id;?></td>
                                        <td>New</td>
                                        <td>N/A</td>
                                        <td>Evaluation</td>

                                        <td><?php
                                            $date = new DateTime($date_creation);
                                            $formattedDate = $date->format('m/d/Y H:i');

                                            echo $formattedDate;
                                        ?></td>
                                        
                                        </tr>
                                    </table>
                                </div>
                            </div>

                        </div>

                        <div class="process-status">
                            <table class="selection">
                                <tr>
                                    <th class="checkDetails" id="icon-1">
                                    <i  style="color: #FFFF;" class="bi bi-clipboard-check"></i>
                                    </th>
                                    <th style="color: #FFFF;" id="line-1">___________________________</th>
                                    <th  class="findSection" id="icon-2">
                                    <i style="color: #FFFF;" class="bi bi-building"></i>
                                    </th>
                                    <th   id="line-2">___________________________</th>
                                    <th class="subConfirm" id="icon-3">
                                    <i class="bi bi-journal"></i>
                                    </th>
                                </tr>
                                <tr>
                                    <td style="color: #FFFF;" class="checkDetails" id="process-1">Check details</td>
                                    <td></td>
                                    <td style="color: #FFFF;" class="findSection" id="process-2">Find section</td>
                                    <td></td>
                                    <td class="subConfirm" id="process-3">Subject Confirmation</td>
                                </tr>
                            </table>
                        </div>

                        <div class="card">
                            <div class="card-body">
                                <div  style="width: 100%;" class="form-details"
                                    id="enrollment-details">
                                    <h3>Enrollment Details</h3>

                                    <?php
                                        if($type == "SHS"){

                                            ?>
                                            <form class="row g-3">
                                                <div class="col-md-4">
                                                <label for="inputSchoolYear" class="form-label">S.Y.</label>
                                                <input readonly type="text" value="<?php echo $current_school_year_term; ?>" class="form-control" id="inputSchoolYear" />
                                                </div>
                                                <div class="col-md-4">
                                                    <label for="inputTrack" class="form-label">Track</label>

                                                    <select id="inputTrack" class="form-select">
                                                        <?php 

                                                            $SHS_DEPARTMENT = 4;
                                                        
                                                            $track_sql = $con->prepare("SELECT 
                                                            program_id, track, acronym 
                                                                
                                                                FROM program 
                                                            WHERE department_id =:department_id
                                                            GROUP BY track
                                                            ");

                                                            $track_sql->bindValue(":department_id", $SHS_DEPARTMENT);
                                                            $track_sql->execute();
                                                            

                                                            while($row = $track_sql->fetch(PDO::FETCH_ASSOC)){

                                                                $row_program_id = $row['program_id'];

                                                                $track = $row['track'];

                                                                $selected = ($row_program_id == $program_id) ? "selected" : "";

                                                                echo "<option value='$row_program_id' $selected>$track</option>";
                                                            }
                                                        ?>
                                                        <!-- <option selected>TVL</option>
                                                        <option>Arts and Design</option> -->
                                                    </select>
                                                </div>

                                                <div class="col-md-4">
                                                    <label for="inputStrand" class="form-label">Strand</label>

                                                    <select onchange="chooseStrand(this, <?php echo $pending_enrollees_id;?>)" 
                                                        name="strand" id="strand" class="form-select">
                                                        <?php 

                                                            $SHS_DEPARTMENT = 4;
                                                        
                                                            $track_sql = $con->prepare("SELECT 
                                                            program_id, track, acronym 
                                                                
                                                                FROM program 
                                                            WHERE department_id =:department_id
                                                            GROUP BY acronym
                                                            ");

                                                            $track_sql->bindValue(":department_id", $SHS_DEPARTMENT);
                                                            $track_sql->execute();

                                                            while($row = $track_sql->fetch(PDO::FETCH_ASSOC)){

                                                                $row_program_id = $row['program_id'];

                                                                $acronym = $row['acronym'];

                                                                $selected = ($row_program_id == $program_id) ? "selected" : "";

                                                                echo "<option value='$row_program_id' $selected>$acronym</option>";
                                                            }
                                                        ?>

                                                    </select>
                                                </div>
                                                <div class="col-md-6">
                                                    <label for="inputYear" class="form-label">Year</label>
                                                
                                                    <select id="inputYear" class="form-select">

                                                        <option value="11" selected>Grade 11</option>
                                                        <option  value="12">Grade 12</option>
                                                    
                                                    </select>
                                                </div>
                                                <div class="col-md-6">
                                                <label for="inputSemester" class="form-label">Semester</label>
                                                <input readonly type="text" value="<?php echo $current_school_year_period; ?>" class="form-control" id="inputSchoolYear" />
                                                <!-- <select id="inputSemester" class="form-select">
                                                    <option <?php if ($current_school_year_period == 'First') echo 'selected'; ?>>First</option>
                                                    <option <?php if ($current_school_year_period == 'Second') echo 'selected'; ?>>Second</option>
                                                </select> -->
                                                </div>
                                            </form>
                                            <?php
                                        }
                                        else if($type == "Tertiary"){
                                            ?>

                                            <form class="row g-3">
                                                <div class="col-md-4">
                                                    <label for="inputSchoolYear" class="form-label">S.Y.</label>
                                                    <input readonly type="text" value="<?php echo $current_school_year_term; ?>" class="form-control" id="inputSchoolYear" />
                                                </div>
                                                <div class="col-md-4">
                                                    <label for="inputTrack" class="form-label">Track</label>

                                                    <select id="inputTrack" class="form-select">
                                                        <?php 

                                                            $SHS_DEPARTMENT = 4;
                                                        
                                                            $track_sql = $con->prepare("SELECT 
                                                            program_id, track, acronym 
                                                                
                                                                FROM program 
                                                            WHERE department_id !=:department_id
                                                            GROUP BY track
                                                            ");

                                                            $track_sql->bindValue(":department_id", $SHS_DEPARTMENT);
                                                            $track_sql->execute();
                                                            
                                                            while($row = $track_sql->fetch(PDO::FETCH_ASSOC)){

                                                                $row_program_id = $row['program_id'];

                                                                $track = $row['track'];

                                                                $selected = ($row_program_id == $program_id) ? "selected" : "";

                                                                echo "<option value='$row_program_id' $selected>$track</option>";
                                                            }
                                                        ?>
                                                    
                                                    </select>
                                                </div>

                                                <div class="col-md-4">
                                                    <label for="inputStrand" class="form-label">Strand</label>

                                                    <select onchange="chooseStrand(this, <?php echo $pending_enrollees_id;?>)" 
                                                        name="strand" id="strand" class="form-select">
                                                        <?php 

                                                            $SHS_DEPARTMENT = 4;
                                                        
                                                            $track_sql = $con->prepare("SELECT 
                                                            program_id, track, acronym 
                                                                
                                                                FROM program 
                                                            WHERE department_id !=:department_id
                                                            GROUP BY acronym
                                                            ");

                                                            $track_sql->bindValue(":department_id", $SHS_DEPARTMENT);
                                                            $track_sql->execute();

                                                            while($row = $track_sql->fetch(PDO::FETCH_ASSOC)){

                                                                $row_program_id = $row['program_id'];

                                                                $acronym = $row['acronym'];

                                                                $selected = ($row_program_id == $program_id) ? "selected" : "";

                                                                echo "<option value='$row_program_id' $selected>$acronym</option>";
                                                            }
                                                        ?>

                                                    </select>
                                                </div>

                                                <div class="col-md-6">
                                                    <label for="inputYear" class="form-label">Year</label>
                                                
                                                    <select id="inputYear" class="form-select">

                                                        <option value="1" selected>1st Year</option>
                                                        <option value="2">2nd Year</option>
                                                        <option value="3">3rd Year</option>
                                                        <option value="4">4th Year</option>
                                                    </select>
                                                </div>

                                                <div class="col-md-6">
                                                    <label for="inputSemester" class="form-label">Semester</label>
                                                    <input readonly type="text" value="<?php echo $current_school_year_period; ?>" class="form-control" id="inputSchoolYear" />
                                                    <!-- <select id="inputSemester" class="form-select">
                                                        <option <?php if ($current_school_year_period == 'First') echo 'selected'; ?>>First</option>
                                                        <option <?php if ($current_school_year_period == 'Second') echo 'selected'; ?>>Second</option>
                                                    </select> -->
                                                    </div>
                                            </form>

                                            <?php
                                        }
                                    ?>

                                </div>
                            </div>

                        </div>

                        <!-- Enrollment Form -->
                        <div style="display: none;" class="container">
                            <h3 class="mt-2 text-center text-primary">Enrollment Form</h3>
                            <div class="bg-content">
                                <div class="form-details" id="check-details">
                                    <div class="mb-4 col-md-3">
                                        <label for="">Form Id</label>
                                        <input readonly style="width: 100%;" type="text" 
                                            value='<?php echo $enrollment_form_id;?>' class="form-control">
                                    </div>
                                    <div class="mb-4 col-md-3">
                                        <label for="">Name</label>
                                        <input readonly style="width: 100%;" type="text" 
                                            value='<?php echo $student_fullname;?>' class="form-control">
                                    </div>
                                    <div class="mb-4 col-md-3">
                                        <label for="">Admission Type</label>
                                        <input readonly style="width: 100%;" type="text" 
                                            value='New' class="form-control">
                                        </div>
                                    <div class="mb-4 col-md-3">
                                        <label for="">Date Submitted</label>
                                        <input readonly style="width: 100%;" type="text" 
                                            value='<?php echo $date_creation?>' class="form-control">
                                    </div>
                                </div>
                            </div> 


                        </div>

                        <div class="card ">
                                
                            <div class="card-header">
                                <h3 class="text-center text-success">Available Section</h3>

                                <?php 

                                    if($section->CheckIfProgramHasSection($program_id,
                                        $current_school_year_term) == false){

                                        echo "
                                            <a href='../section/create.php'>
                                                <button class='btn btn-success'>Add Section</button>
                                            </a>
                                        ";
                                    }
                                
                                ?>
                                
                            </div>

                            <div class="card-body">

                            <form method="POST">
                                <table id="availableTransfereeSectionTable" class="table table-striped table-bordered table-hover "  style="font-size:13px" cellspacing="0"  > 
                                    <thead>
                                        <tr class="text-center"> 
                                            <th rowspan="2">Section Id</th>
                                            <th rowspan="2">Section Name</th>
                                            <th rowspan="2">Student</th>
                                            <th rowspan="2">Capacity</th>
                                            <th rowspan="2">Term</th>
                                            <th rowspan="2"></th>
                                        </tr>	
                                    </thead> 	
                                    <!-- REFF -->
                                    <tbody>
                                        <?php
                                            $course_level = 11;
                                            $active = "yes";

                                            # Only Available now.
                                            $sql = $con->prepare("SELECT * FROM course

                                                WHERE program_id=:program_id
                                                AND active=:active
                                                AND school_year_term=:school_year_term");

                                            $sql->bindValue(":program_id", $program_id);
                                            $sql->bindValue(":active", $active);
                                            $sql->bindValue(":school_year_term", $current_school_year_term);

                                            $sql->execute();
                                        
                                            if($sql->rowCount() > 0){

                                                while($get_course = $sql->fetch(PDO::FETCH_ASSOC)){

                                                    $course_id = $get_course['course_id'];
                                                    $program_section = $get_course['program_section'];
                                                    $capacity = $get_course['capacity'];
                                                    $school_year_term = $get_course['school_year_term'];
                                                    $section = new Section($con, $course_id);

                                                    $section_obj = $section->GetSectionObj($course_id);

                                                    $totalStudent = $section->GetTotalNumberOfStudentInSection($course_id, $current_school_year_id);
                                                    $capacity = $section_obj['capacity'];

                                                    $program_id = $section_obj['program_id'];
                                                    $course_level = $section_obj['course_level'];

                                                    $removeSection = "removeSection($course_id, \"$program_section\")";

                                                    // echo $totalStudent;
                                                    // echo $program_id;

                                                    $new_program_section = $section->AutoCreateAnotherSection($program_section);

                                                    // echo $program_section;
                                                    // echo "<br>";
                                                    // echo $new_program_section;

                                                    // $isCloseddisabled = "<input name='selected_course_id' 
                                                    //     class='radio' value='$course_id' 
                                                    //     type='radio' " . ($course_id == $student_course_id ? "checked" : "") . " " . ($isClosed ? "disabled" : "") . ">";   
                                                


                                                    if($totalStudent == $capacity){

                                                    }
                                                    echo "
                                                    <tr class='text-center'>
                                                        <td>$course_id</td>
                                                        <td>$program_section</td>
                                                        <td>$totalStudent</td>
                                                        <td>$capacity</td>
                                                        <td>$school_year_term</td>
                                                        <td>
                                                            <input name='selected_course_id' class='radio' value='$course_id' type='radio' " . (($totalStudent == $capacity) ? "disabled" : "") . ">
                                                        <td>
                                                            <i onclick='$removeSection' style='cursor: pointer; color: orange; " . ($totalStudent != 0 ? "display: none;" : "") . "' class='fas fa-times'></i>
                                                        </td>
                                                    </tr>
                                                ";
                                                }
                                                
                                            }else{
                                                echo "
                                                    <div class='col-md-12'>
                                                        <h4 class='text-center text-muted'>No currently available section for $program_acronym</h4>
                                                    </div>
                                                ";
                                            }
                                        ?>
                                    </tbody>
                                </table>

                                <a href="process_enrollment.php?step1=true&id=<?php echo $pending_enrollees_id;?> ">
                                    <button type="button" class="btn btn-outline-primary">Return</button>
                                </a>
                                <button type="submit" name="pending_choose_section"
                                    class="btn btn-primary">Proceed
                                </button>

                            </form>                 
                            </div>
                        </div>

                    </div>

                    <script>
                        function chooseStrand(entity, pending_enrollees_id){

                            var strand = document.getElementById("strand").value;

                            // console.log("Selected value: " + strand);

                            Swal.fire({
                                icon: 'question',
                                title: `Update Strand?`,
                                showCancelButton: true,
                                confirmButtonText: 'Yes',
                                cancelButtonText: 'Cancel'
                            }).then((result) => {

                                if (result.isConfirmed) {
                                    // REFX
                                    $.ajax({
                                        url: '../ajax/pending/update_student_strand.php',
                                        type: 'POST',
                                        data: {
                                            strand, pending_enrollees_id
                                        },
                                        success: function(response) {

                                            console.log(response);

                                            // enrollment-details
                                            if(response == "success"){
                                                $('#enrollment-details').load(
                                                    location.href + ' #enrollment-details'
                                                );
                                                $('#regular_available_section').load(
                                                    location.href + ' #regular_available_section'
                                                );
                                            }


                                        }
                                    });
                                }

                            });
                        }


                        function removeSection(course_id, program_section){
                            Swal.fire({
                                    icon: 'question',
                                    title: `I agreed to removed ${program_section}.`,
                                    showCancelButton: true,
                                    confirmButtonText: 'Yes',
                                    cancelButtonText: 'No'
                                }).then((result) => {
                                if (result.isConfirmed) {
                                    // console.log("nice")
                                    $.ajax({
                                        url: '../ajax/section/remove_section.php',
                                        type: 'POST',
                                        data: {
                                            course_id
                                        },
                                        success: function(response) {

                                            $('#availableTransfereeSectionTable').load(
                                                location.href + ' #availableTransfereeSectionTable'
                                            );

                                        },
                                        error: function(xhr, status, error) {
                                            // handle any errors here
                                        }
                                    });
                                } else {
                                    // User clicked "No," perform alternative action or do nothing
                                }
                            });
                        }
                    </script>
                <?php

            }

            if(isset($_GET['id'])  && isset($_GET['step3']) && isset($_GET['selected_course_id'])
                ){

                $selected_course_id = $_GET['selected_course_id'];

                $section = new Section($con, $selected_course_id);

                $section_name = $section->GetSectionName();
                $section_course_level = $section->GetSectionGradeLevel();
                
                // echo $section_course_level;
                
                ?>
                    <div class="row col-md-12">

                        <!-- START THIRD -->

                        <div class="back-menu">
                            <button type="button" class="admission-btn" onclick="find_form()">
                            <i class="bi bi-arrow-left-circle"></i> Admission
                            </button>
                        </div>
                        
                        <div class="enrollment-form-container">

                            <div class="enrollment-form-upper">
                                <h3>Enrollment Form</h3>
                                <i class="fa-solid fa-ellipsis-vertical"></i>
                            </div>

                            <div class="form-upper-second-container">
                                <div class="form-inner">
                                    <h5>Form ID</h5>
                                    <p><?php echo $enrollment_form_id;?></p>
                                </div>
                                <div class="form-inner">
                                    <h5>Admission Type</h5>
                                    <p>New</p>
                                </div>
                                <div class="form-inner">
                                    <h5>Student No</h5>
                                    <p>N/A</p>
                                </div>
                                <div class="form-inner">
                                    <h5>Status</h5>
                                    <p>Evaluation</p>
                                </div>
                                <div class="form-inner">
                                    <h5>Submitted on</h5>
                                    <p>
                                        <?php
                                            $date = new DateTime($date_creation);
                                            $formattedDate = $date->format('m/d/Y H:i');
                                            echo $formattedDate;
                                        ?>
                                    </p>
                                </div>
                            </div>

                            <div class="process-status">
                                <table class="selection">
                                    <tr>
                                        <th class="checkDetails" id="icon-1">
                                            <i  style="color: #FFFF;" class="bi bi-clipboard-check"></i>
                                        </th>

                                        <th style="color: #FFFF;" id="line-1">___________________________</th>

                                        <th  class="findSection" id="icon-2">
                                            <i style="color: #FFFF;" class="bi bi-building"></i>
                                        </th>

                                        <th id="line-2">___________________________</th>

                                        <th class="subConfirm" id="icon-3">
                                            <i style="color: #FFFF;" class="bi bi-journal"></i>
                                        </th>

                                    </tr>
                                    <tr>
                                        <td style="color: #FFFF;" class="checkDetails" id="process-1">Check details</td>
                                        <td></td>
                                        <td style="color: #FFFF;" class="findSection" id="process-2">Find section</td>
                                        <td></td>
                                        <td class="subConfirm" id="process-3">Subject Confirmation</td>
                                    </tr>
                                </table>
                            </div>

                        </div>

                        <!-- REFv3 -->
                        <!--  -->
                        <div class="height-section-details">
                            <div class="step-3-section-container">

                                <div class="form-details-enrolled" id="subjects-details">
                                    <h3><?php echo $section_name;?> Subjects</h3>
                                        
                                    <table style="font-size: 15px;"> 
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

                                                    $sql = $con->prepare("SELECT 
                                                        t1.*,
                                                        t3.room, t3.schedule_time, t3.schedule_day ,
                                                        t4.firstname,
                                                        t4.lastname
                                                        
                                                        FROM subject as t1

                                                        INNER JOIN course as t2 ON t2.course_id = t1.course_id
                                                        LEFT JOIN subject_schedule as t3 ON t3.subject_id = t1.subject_id
                                                        LEFT JOIN teacher as t4 ON t4.teacher_id = t3.teacher_id

                                                        WHERE t1.course_id=:course_id
                                                        AND t1.semester=:semester
                                                        ");

                                                    $sql->bindValue(":course_id", $selected_course_id);
                                                    $sql->bindValue(":semester", $current_school_year_period);
                                                    // $sql->bindValue(":course_level", $course_level);

                                                    $sql->execute();
                                                
                                                    if($sql->rowCount() > 0){

                                                        while($row = $sql->fetch(PDO::FETCH_ASSOC)){

                                                            $subject_id = $row['subject_id'];
                                                            $subject_code = $row['subject_code'];
                                                            $subject_title = $row['subject_title'];
                                                            $unit = $row['unit'];
                                                            $subject_type = $row['subject_type'];

                                                            $room = $row['room'];
                                                            $schedule_time = $row['schedule_time'];
                                                            $schedule_day = $row['schedule_day'];
                                                            
                                                            $teacher_firstname = $row['firstname'];
                                                            $teacher_lastname = $row['lastname'];

                                                            // echo $subject_id;
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
                                            </tbody>
                                    </table>

                                    
                                </div>
                                <div class="step-3-subject-bottom">
                                        <a href="process_enrollment.php?step2=true&id=<?php echo $pending_enrollees_id;?>">
                                            <button type="button" class="button-style-primary primary">Return</button>
                                        </a>
                                        <button onclick='confirmPendingValidation("<?php echo $type ?>", "<?php echo $firstname ?>", "<?php echo $lastname ?>", "<?php echo $middle_name ?>", "<?php echo $password ?>", "<?php echo $program_id ?>", "<?php echo $civil_status ?>", "<?php echo $nationality ?>", "<?php echo $contact_number ?>", "<?php echo $birthday ?>", "<?php echo $age ?>", "<?php echo $guardian_name ?>", "<?php echo $guardian_contact_number ?>", "<?php echo $sex ?>", "<?php echo $student_status ?>", "<?php echo $pending_enrollees_id ?>", "<?php echo $address; ?>", "<?php echo $lrn; ?>", "<?php echo $selected_course_id; ?>", "<?php echo $enrollment_form_id; ?>", "<?php echo $religion; ?>", "<?php echo $birthplace; ?>", "<?php echo $email; ?>")'
                                            name='confirm_validation_btn'
                                            class='button-style-success success'>
                                            Confirm
                                        </button>
                                    </div>
                            </div>

                        </div>


                        <!--  -->

                        <div class="content">

                            <div class="form-header">
                                <div class="header-content">
                                    <h2>Enrollment form</h2>
                                </div>

                                <div class="student-table">
                                    <table>
                                        <tr>
                                        <th>Form ID</th>
                                        <th>Admission type</th>
                                        <th>Student no</th>
                                        <th>Status</th>
                                        <th>Submitted on:</th>
                                        </tr>
                                        <tr>
                                        <td><?php echo $enrollment_form_id;?></td>
                                        <td>New</td>
                                        <td>N/A</td>
                                        <td>Evaluation</td>

                                        <td><?php
                                            $date = new DateTime($date_creation);
                                            $formattedDate = $date->format('m/d/Y H:i');

                                            echo $formattedDate;
                                        ?></td>
                                        
                                        </tr>
                                    </table>
                                </div>
                            </div>
                        </div>

                        <div class="process-status">
                            <table class="selection">
                                <tr>
                                    <th  class="checkDetails" id="icon-1">
                                    <i style="color: #FFFF;" class="bi bi-clipboard-check"></i>
                                    </th>
                                    <th style="color: #FFFF;" id="line-1">___________________________</th>
                                    <th style="color: #FFFF;"  class="findSection" id="icon-2">
                                    <i style="color: #FFFF;" class="bi bi-building"></i>
                                    </th>
                                    <th style="color: #FFFF;" id="line-2">___________________________</th>
                                    <th class="subConfirm" id="icon-3">
                                    <i style="color: #FFFF;" class="bi bi-journal"></i>
                                    </th>
                                </tr>
                                <tr>
                                    <td style="color: #FFFF;" class="checkDetails" id="process-1">Check details</td>
                                    <td></td>
                                    <td style="color: #FFFF;" class="findSection" id="process-2">Find section</td>
                                    <td></td>
                                    <td style="color: #FFFF;" class="subConfirm" id="process-3">Subject Confirmation</td>
                                </tr>
                            </table>
                        </div>

                        <div class="container mt-4 mb-2">
                            <h4 style="color: #BB4444;" class="mb-3 text-start"><?php echo $section_name;?> Subjects </h4>

                            <table id="courseTable" class="table table-striped table-bordered table-hover "  style="font-size:13px" cellspacing="0"  > 
                                <thead>
                                    <tr class="text-center"> 
                                        <th rowspan="2">Id</th>
                                        <th rowspan="2">Code</th>
                                        <th rowspan="2">Description</th>
                                        <th rowspan="2">Unit</th>
                                        <th rowspan="2">Type</th>
                                        <!-- <th colspan="4">Schedule</th>  -->
                                    </tr>	
                                    <!-- <tr class="text-center">
                                        <th>Day</th>
                                        <th>Time</th>
                                        <th>Room</th> 
                                        <th>Instructor</th> 
                                    </tr>	 -->
                                </thead> 	
                                <tbody>
                                    <?php
                                        $course_level = 11;
                                        $active = "yes";

                                        # Only Available now.

                                        $sql = $con->prepare("SELECT 
                                            t1.*,
                                            t3.room, t3.schedule_time, t3.schedule_day ,
                                            t4.firstname,
                                            t4.lastname
                                            
                                            FROM subject as t1

                                            INNER JOIN course as t2 ON t2.course_id = t1.course_id
                                            LEFT JOIN subject_schedule as t3 ON t3.subject_id = t1.subject_id
                                            LEFT JOIN teacher as t4 ON t4.teacher_id = t3.teacher_id

                                            WHERE t1.course_id=:course_id
                                            AND t1.semester=:semester
                                            ");

                                        $sql->bindValue(":course_id", $selected_course_id);
                                        $sql->bindValue(":semester", $current_school_year_period);
                                        // $sql->bindValue(":course_level", $course_level);

                                        $sql->execute();
                                    
                                        if($sql->rowCount() > 0){

                                            while($row = $sql->fetch(PDO::FETCH_ASSOC)){

                                                $subject_id = $row['subject_id'];
                                                $subject_code = $row['subject_code'];
                                                $subject_title = $row['subject_title'];
                                                $unit = $row['unit'];
                                                $subject_type = $row['subject_type'];

                                                $room = $row['room'];
                                                $schedule_time = $row['schedule_time'];
                                                $schedule_day = $row['schedule_day'];
                                                
                                                $teacher_firstname = $row['firstname'];
                                                $teacher_lastname = $row['lastname'];

                                                // echo $subject_id;
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
                                </tbody>
                            </table>
                  
                            <a href="process_enrollment.php?step2=true&id=<?php echo $pending_enrollees_id;?>">
                                <button type="button" class="btn btn-outline-primary">Return</button>
                            </a>
                            <button onclick='confirmPendingValidation("<?php echo $type ?>", "<?php echo $firstname ?>", "<?php echo $lastname ?>", "<?php echo $middle_name ?>", "<?php echo $password ?>", "<?php echo $program_id ?>", "<?php echo $civil_status ?>", "<?php echo $nationality ?>", "<?php echo $contact_number ?>", "<?php echo $birthday ?>", "<?php echo $age ?>", "<?php echo $guardian_name ?>", "<?php echo $guardian_contact_number ?>", "<?php echo $sex ?>", "<?php echo $student_status ?>", "<?php echo $pending_enrollees_id ?>", "<?php echo $address; ?>", "<?php echo $lrn; ?>", "<?php echo $selected_course_id; ?>", "<?php echo $enrollment_form_id; ?>", "<?php echo $religion; ?>", "<?php echo $birthplace; ?>", "<?php echo $email; ?>")'
                                name='confirm_validation_btn'
                                class='btn btn-success btn-sm'>Confirm</button>
                        </div> 

                    <script>

                        function confirmPendingValidation(type, firstname, lastname, middle_name, password,
                            program_id, civil_status, nationality, contact_number, birthday, age,
                            guardian_name, guardian_contact_number, sex, student_status,
                            pending_enrollees_id, address, lrn,
                            selected_course_id, enrollment_form_id,
                            religion, birthplace, email){

                            selected_course_id = parseInt(selected_course_id);
                            program_id = parseInt(program_id);
                            age = parseInt(age);
                            pending_enrollees_id = parseInt(pending_enrollees_id);

                            Swal.fire({
                                icon: 'question',
                                title: `Confirm Enrollment?`,
                                showCancelButton: true,
                                confirmButtonText: 'Yes',
                                cancelButtonText: 'Cancel'

                            }).then((result) => {
                                if (result.isConfirmed) {
                                    $.ajax({
                                        url: '../ajax/enrollee/pending_steps_confirmation.php',
                                        type: 'POST',
                                        data: {
                                            type,
                                            firstname, lastname, middle_name,
                                            password, program_id, civil_status, nationality, 
                                            contact_number, birthday, age, guardian_name, 
                                            guardian_contact_number, sex, student_status, 
                                            pending_enrollees_id, address, lrn, selected_course_id,
                                            enrollment_form_id, religion, birthplace, email
                                        },
                                        dataType: "json",
                                        success: function(response) {

                                            // console.log(response);
                                            // console.log(response['status'])
                                            
                                            if(response['status'] == "full"){
                                                    Swal.fire({
                                                    title: "Selected section is full.",
                                                    icon: "error",
                                                    showCancelButton: false,
                                                    confirmButtonText: "I Understand",
                                                    });
                                            }else{
                                                // console.log(response)
                                                var student_id = response['student_id']
                                                var student_username = response['student_username']
                
                                                Swal.fire({
                                                    title: "Confirmation Approved",
                                                    icon: "success",
                                                    showCancelButton: false,
                                                    confirmButtonText: "OK",
                                                }).then((result) => {
                                                    if (result.isConfirmed) {

                                                        var url = `../enrollees/subject_insertion.php?enrolled_subjects=true&id=${student_id}`;
                                                        window.location.href = url;
                                                    } else {
                                                        // User clicked Cancel or closed the dialog
                                                    }
                                                });
                                            }

                                            // location.reload();
                                        },
                                        error: function(xhr, status, error) {
                                            // handle any errors here
                                        }
                                    });
                                }
                            });
                        }
                    </script>
                <?php
            }

        }
    }
    // else {
    //     // Unset the enrollment form ID if the condition is not met
    //     unset($_SESSION['enrollment_form_id']);
    // }


    if(isset($_GET['student_id'])  && isset($_GET['manual'])
        && $_GET['manual'] == "true"){

            $student_id = $_GET['student_id'];

            $studentEnroll = new StudentEnroll($con);

            $student_username = $studentEnroll->GetStudentUsername($student_id);

            // echo $student_username;
            $student = new Student($con, $student_username);

            if($student != null){

                $enrollment = new Enrollment($con, $studentEnroll);
                $enrollment_form_id = $enrollment->GenerateEnrollmentFormId();

                $enrollment_form_id = $enrollment->GenerateEnrollmentFormId();

                $student_unique_id = $student->GetStudentUniqueId();

                $firstname = $student->GetFirstName();
                $lastname = $student->GetLastName();
                $middle_name = $student->GetMiddleName();
                $birthday = $student->GetStudentBirthdays();
                $sex = $student->GetStudentSex();
                $contact_number = $student->GetContactNumber();
                $address = $student->GetStudentAddress();
                $student_type = $student->CheckIfTertiary();
                $student_course_level = $student->GetStudentCourseLevel();

                $student_course_id = $studentEnroll->GetStudentCourseId($student_username);

                // echo $student_course_id;
                $student_program_id = $studentEnroll->GetStudentProgramId($student_course_id);
                $section_name = 
                $date_creation = date("Y-m-d H:i:s");

                $section = new Section($con, $student_course_id);

                $section_name = $section->GetSectionName();

                if (!isset($_SESSION['enrollment_form_id_manual'])) {
                    $_SESSION['enrollment_form_id_manual'] = $enrollment_form_id;

                } else {
                    $enrollment_form_id = $_SESSION['enrollment_form_id_manual'];
                }
        

                if(isset($_GET['student_id'])  && isset($_GET['manual']) && $_GET['manual'] == "true" &&
                    isset($_GET['step1']) && $_GET['step1'] == "true"){

                    ?>
                        <div class="row col-md-12"> 

                            <div class="content">

                                <div class="form-header">
                                    <div class="header-content">
                                        <h2>Enrollment form</h2>
                                    </div>

                                    <div class="student-table">
                                        <table>
                                            <tr>
                                            <th>Form ID</th>
                                            <th>Admission type</th>
                                            <th>Student no</th>
                                            <th>Status</th>
                                            <th>Submitted on:</th>
                                            </tr>
                                            <tr>
                                            <td><?php echo $enrollment_form_id;?></td>
                                            <td>Old</td>
                                            <td><?php echo $student_unique_id;?></td>
                                            <td>Evaluation</td>

                                            <td><?php
                                                $date = new DateTime($date_creation);
                                                $formattedDate = $date->format('m/d/Y H:i');

                                                echo $formattedDate;
                                            ?></td>
                                            
                                            </tr>
                                        </table>
                                    </div>
                                </div>
                            </div>

                            <!-- First 2nd -->
                            <div class="process-status">
                                <table class="selection">
                                    <tr>
                                        <th class="checkDetails" id="icon-1">
                                        <i class="bi bi-clipboard-check"></i>
                                        </th>
                                        <th id="line-1">___________________________</th>
                                        <th class="findSection" id="icon-2">
                                        <i class="bi bi-building"></i>
                                        </th>
                                        <th id="line-2">___________________________</th>
                                        <th class="subConfirm" id="icon-3">
                                        <i class="bi bi-journal"></i>
                                        </th>
                                    </tr>
                                    <tr>
                                        <td class="checkDetails" id="process-1">Check details</td>
                                        <td></td>
                                        <td class="findSection" id="process-2">Find section</td>
                                        <td></td>
                                        <td class="subConfirm" id="process-3">Subject Confirmation</td>
                                    </tr>
                                </table>
                            </div>

                            <div class="bg-content">
                                <div class="form-details" id="check-details">
                                    <h3>Student Details</h3>

                                    <form class="row g-3">
                                        <div class="col-md-4">
                                        <label for="inputFirstName"  class="form-label">First name</label>
                                        <input type="text" value='<?php echo $firstname;?>' class="form-control" id="inputFirstName" />
                                        </div>
                                        <div class="col-md-4">
                                        <label for="inputLastName"  class="form-label">Last name</label>
                                        <input type="text" value='<?php echo $lastname;?>' class="form-control" id="inputLastName" />
                                        </div>
                                        <div class="col-md-4">
                                        <label for="inputMiddleName"  class="form-label">Middle name</label
                                        >
                                        <input type="text" value='<?php echo $middle_name;?>' class="form-control" id="inputMiddleName" />
                                        </div>
                                        <div class="col-md-4">
                                            <label for="inputBirthdate" class="form-label">Birthdate</label>
                                            <input type="date" value='<?php echo $birthday;?>' class="form-control" id="inputBirthdate" />
                                        </div>

                                        <div class="col-md-4">
                                        <label for="inputGender" class="form-label">Gender</label>
                                        <select id="inputGender" class="form-select">
                                                <option <?php if ($sex == 'Male') echo 'selected'; ?>>Male</option>
                                                <option <?php if ($sex == 'FeMale') echo 'selected'; ?>>Male</option>
                                        </select>
                                        </div>
                                        <div class="col-md-4">
                                        <label for="inputContactNo" class="form-label">Contact no.</label>
                                        <input type="text" value='<?php echo $contact_number;?>' class="form-control" id="inputContactNo" />
                                        </div>
                                        <div class="col-12">
                                        <label for="inputAddress" class="form-label">Address</label>
                                        <input type="text" value='<?php echo $address;?>' class="form-control" id="inputAddress" />
                                        </div>
                                        
                                    </form>
                                    
                                </div>

                                <div class="mt-3">
                                    <a style='text-align: end;' href='process_enrollment.php?student_id=<?php echo $student_id;?>&step2=true&manual=true'>
                                        <button type='button'class='btn-sm btn-primary'>Proceed</button>
                                    </a>
                                </div>

                            </div>

                        </div>
                    <?php

                }

                if(isset($_GET['student_id'])  && isset($_GET['manual']) && $_GET['manual'] == "true" &&
                    isset($_GET['step2']) && $_GET['step2'] == "true"){

                    if(isset($_POST['old_manual_section']) 
                        && isset($_POST['selected_course_id'])){

                        $selected_course_id_manual = $_POST['selected_course_id'];
                        
                        // $section_url = "process_enrollment.php?step3=true&id=$pending_enrollees_id&selected_course_id=$selected_course_id_value";
                        $section_url = "process_enrollment.php?student_id=$student_id&step3=true&manual=true&selected_course_id=$selected_course_id_manual";
                        header("Location: $section_url");

                        // echo $selected_course_id_manual;
                    }

                    // echo $student_course_id;

                    $checkAligned = $old_enrollees->CheckIfAlignedSection($student_course_id, $student_course_level);
                    $finishedGrade11Subjects = $student->CheckShsEligibleForMoveUp($student_id, $student_program_id);
                    
                    # If not aligned and TODO. Check if finished the Grade 11 2nd Sem.

                    // if($checkAligned == true && 
                    //     $finishedGrade11Subjects == true){
                    //     # Can Enroll through grade 12, the automatic enrollment
                    //     # just failed.

                    //     // echo "eligible to enroll for grade 12 manual enroll";
                    // }else if($checkAligned == false && 
                    //     $finishedGrade11Subjects == true){
                    //     # Aligned the section
                    //     // echo "should aligned its section first";

                    // }

                    ?>

                        <div class="row col-md-12"> 

                            <div class="content">

                                <div class="form-header">
                                    <div class="header-content">
                                        <h2>Enrollment form</h2>
                                    </div>

                                    <div class="student-table">
                                        <table>
                                            <tr>
                                            <th>Form ID</th>
                                            <th>Admission type</th>
                                            <th>Student no</th>
                                            <th>Status</th>
                                            <th>Submitted on:</th>
                                            </tr>
                                            <tr>
                                            <td><?php echo $enrollment_form_id;?></td>
                                            <td>Old</td>
                                            <td><?php echo $student_unique_id;?></td>
                                            <td>Evaluation</td>

                                            <td><?php
                                                $date = new DateTime($date_creation);
                                                $formattedDate = $date->format('m/d/Y H:i');

                                                echo $formattedDate;
                                            ?></td>
                                            
                                            </tr>
                                        </table>
                                    </div>
                                </div>
                            </div>

                            <!-- First 2nd -->
                            <div class="process-status">
                                <table class="selection">
                                    <tr>
                                        <th class="checkDetails" id="icon-1">
                                        <i  class="bi bi-clipboard-check"></i>
                                        </th>
                                        <th style="color: #FFFF;" id="line-1">___________________________</th>
                                        <th class="findSection" id="icon-2">
                                        <i style="color: #FFFF;" class="bi bi-building"></i>
                                        </th>
                                        <th id="line-2">___________________________</th>
                                        <th class="subConfirm" id="icon-3">
                                        <i class="bi bi-journal"></i>
                                        </th>
                                    </tr>
                                    <tr>
                                        <td class="checkDetails" id="process-1">Check details</td>
                                        <td></td>
                                        <td class="checkDetails" id="process-2">Find section</td>
                                        <td></td>
                                        <td class="subConfirm" id="process-3">Subject Confirmation</td>
                                    </tr>
                                </table>
                            </div>


                            <div class="card">
                                <div class="card-body">
                                    <div  style="width: 100%;" class="form-details"
                                        id="enrollment-details">
                                        <h3>Enrollment Details</h3>

                                        <?php
                                            if($student_type == 0){

                                                ?>
                                                <form class="row g-3">
                                                    <div class="col-md-4">
                                                    <label for="inputSchoolYear" class="form-label">S.Y.</label>
                                                    <input readonly type="text" value="<?php echo $current_school_year_term; ?>" class="form-control" id="inputSchoolYear" />
                                                    </div>
                                                    <div class="col-md-4">
                                                        <label for="inputTrack" class="form-label">Track</label>

                                                        <select id="inputTrack" class="form-select">
                                                            <?php 

                                                                $SHS_DEPARTMENT = 4;
                                                            
                                                                $track_sql = $con->prepare("SELECT 
                                                                program_id, track, acronym 
                                                                    
                                                                    FROM program 
                                                                WHERE department_id =:department_id
                                                                GROUP BY track
                                                                ");

                                                                $track_sql->bindValue(":department_id", $SHS_DEPARTMENT);
                                                                $track_sql->execute();

                                                                while($row = $track_sql->fetch(PDO::FETCH_ASSOC)){

                                                                    $row_program_id = $row['program_id'];

                                                                    $track = $row['track'];

                                                                    $selected = ($row_program_id == $program_id) ? "selected" : "";

                                                                    echo "<option value='$row_program_id' $selected>$track</option>";
                                                                }
                                                            ?>
                                                        
                                                        </select>
                                                    </div>

                                                    <div class="col-md-4">
                                                        <label for="inputStrand" class="form-label">Strand</label>

                                                        <select onchange="chooseStrand(this, <?php echo $pending_enrollees_id;?>)" 
                                                            name="strand" id="strand" class="form-select">
                                                            <?php 

                                                                $SHS_DEPARTMENT = 4;
                                                            
                                                                $track_sql = $con->prepare("SELECT 
                                                                    program_id, track, acronym 
                                                                        
                                                                        FROM program 
                                                                    WHERE department_id =:department_id
                                                                    GROUP BY acronym
                                                                ");

                                                                $track_sql->bindValue(":department_id", $SHS_DEPARTMENT);
                                                                $track_sql->execute();

                                                                while($row = $track_sql->fetch(PDO::FETCH_ASSOC)){

                                                                    $row_program_id = $row['program_id'];

                                                                    $acronym = $row['acronym'];

                                                                    $selected = ($row_program_id == $student_program_id) ? "selected" : "";

                                                                    echo "<option value='$row_program_id' $selected>$acronym</option>";
                                                                }
                                                            ?>

                                                        </select>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <label for="inputYear" class="form-label">Year</label>
                                                    
                                                        <select id="inputYear" class="form-select">

                                                            <option <?php if ($student_course_level == '11') echo 'selected'; ?>>Grade 11</option>
                                                            <option <?php if ($student_course_level == '12') echo 'selected'; ?>>Grade 12</option>
                                                        
                                                        </select>
                                                    </div>
                                                    <div class="col-md-6">
                                                    <label for="inputSemester" class="form-label">Semester</label>
                                                    <input readonly type="text" value="<?php echo $current_school_year_period; ?>" class="form-control" id="inputSchoolYear" />
                                                    <!-- <select id="inputSemester" class="form-select">
                                                        <option <?php if ($current_school_year_period == 'First') echo 'selected'; ?>>First</option>
                                                        <option <?php if ($current_school_year_period == 'Second') echo 'selected'; ?>>Second</option>
                                                    </select> -->
                                                    </div>
                                                </form>
                                                <?php
                                            }
                                            else if($student_type == 1){
                                                ?>

                                                <form class="row g-3">
                                                    <div class="col-md-4">
                                                        <label for="inputSchoolYear" class="form-label">S.Y.</label>
                                                        <input readonly type="text" value="<?php echo $current_school_year_term; ?>" class="form-control" id="inputSchoolYear" />
                                                    </div>
                                                    <div class="col-md-4">
                                                        <label for="inputTrack" class="form-label">Track</label>

                                                        <select id="inputTrack" class="form-select">
                                                            <?php 

                                                                $SHS_DEPARTMENT = 4;
                                                            
                                                                $track_sql = $con->prepare("SELECT 
                                                                program_id, track, acronym 
                                                                    
                                                                    FROM program 
                                                                WHERE department_id !=:department_id
                                                                GROUP BY track
                                                                ");

                                                                $track_sql->bindValue(":department_id", $SHS_DEPARTMENT);
                                                                $track_sql->execute();
                                                                
                                                                while($row = $track_sql->fetch(PDO::FETCH_ASSOC)){

                                                                    $row_program_id = $row['program_id'];

                                                                    $track = $row['track'];

                                                                    $selected = ($row_program_id == $program_id) ? "selected" : "";

                                                                    echo "<option value='$row_program_id' $selected>$track</option>";
                                                                }
                                                            ?>
                                                        
                                                        </select>
                                                    </div>

                                                    <div class="col-md-4">
                                                        <label for="inputStrand" class="form-label">Course</label>

                                                        <select onchange="chooseStrand(this, <?php echo $pending_enrollees_id;?>)" 
                                                            name="strand" id="strand" class="form-select">
                                                            <?php 

                                                                $SHS_DEPARTMENT = 4;
                                                            
                                                                $track_sql = $con->prepare("SELECT 
                                                                program_id, track, acronym 
                                                                    
                                                                    FROM program 
                                                                WHERE department_id !=:department_id
                                                                GROUP BY acronym
                                                                ");

                                                                $track_sql->bindValue(":department_id", $SHS_DEPARTMENT);
                                                                $track_sql->execute();

                                                                while($row = $track_sql->fetch(PDO::FETCH_ASSOC)){

                                                                    $row_program_id = $row['program_id'];

                                                                    $acronym = $row['acronym'];

                                                                    $selected = ($row_program_id == $program_id) ? "selected" : "";

                                                                    echo "<option value='$row_program_id' $selected>$acronym</option>";
                                                                }
                                                            ?>

                                                        </select>
                                                    </div>

                                                    <div class="col-md-6">
                                                        <label for="inputYear" class="form-label">Year</label>
                                                    
                                                        <select id="inputYear" class="form-select">

                                                            <option <?php if ($student_course_level == '1') echo 'selected'; ?>>1st Year</option>
                                                            <option <?php if ($student_course_level == '2') echo 'selected'; ?>>2nd Year</option>
                                                            <option <?php if ($student_course_level == '3') echo 'selected'; ?>>3rd Year</option>
                                                            <option <?php if ($student_course_level == '4') echo 'selected'; ?>>4th Year</option>
                                                        </select>
                                                    </div>

                                                    <div class="col-md-6">
                                                        <label for="inputSemester" class="form-label">Semester</label>
                                                        <input readonly type="text" value="<?php echo $current_school_year_period; ?>" class="form-control" id="inputSchoolYear" />
                                                        <!-- <select id="inputSemester" class="form-select">
                                                            <option <?php if ($current_school_year_period == 'First') echo 'selected'; ?>>First</option>
                                                            <option <?php if ($current_school_year_period == 'Second') echo 'selected'; ?>>Second</option>
                                                        </select> -->
                                                        </div>
                                                </form>

                                                <?php
                                            }
                                        ?>

                                    </div>
                                </div>
                            </div>

                            <div class="card ">
                                <div class="card-header">
                                    <h3 class="text-center text-success">Available Section</h3>
                                </div>
                                
                                <?php 

                                # SHS ONLY
                                if($checkAligned == false && 
                                    $finishedGrade11Subjects == true){
                                    # Aligned the section
                                    ?>
                                        <div style="
                                            display: flex;
                                            flex-direction: column;
                                            align-items: end;
                                            text-align: right;
                                            " class="col-md-12">

                                                <a href="student_alignment.php?id=<?php echo $student_id;?>">
                                                    <button name="aligned_section_btn" type="submit" style="margin-right: 15px;" class=" btn btn-primary btn-sm">
                                                        Aligned Section.
                                                    </button>
                                                </a>
                                        </div>
                                    <?php
                                }

                                ?>

                                <div class="card-body">

                                    <form method="POST">
                                        <table id="availableTransfereeSectionTable" class="table table-striped table-bordered table-hover "  style="font-size:13px" cellspacing="0"  > 
                                            <thead>
                                                <tr class="text-center"> 
                                                    <th rowspan="2">Section Id</th>
                                                    <th rowspan="2">Section Name</th>
                                                    <th rowspan="2">Student</th>
                                                    <th rowspan="2">Capacity</th>
                                                    <th rowspan="2">Term</th>
                                                    <th rowspan="2"></th>
                                                </tr>	
                                            </thead> 	
                                            <tbody>
                                                <?php

                                                    $active = "yes";

                                                    # Only Available now.
                                                    $sql = $con->prepare("SELECT * FROM course

                                                        WHERE program_id=:program_id
                                                        AND active=:active
                                                        AND school_year_term=:school_year_term
                                                    ");

                                                    $sql->bindValue(":program_id", $student_program_id);
                                                    $sql->bindValue(":active", $active);
                                                    $sql->bindValue(":school_year_term", $current_school_year_term);

                                                    $sql->execute();

                                                    // echo $current_school_year_term;
                                                
                                                    if($sql->rowCount() > 0){

                                                        while($get_course = $sql->fetch(PDO::FETCH_ASSOC)){

                                                            $course_id = $get_course['course_id'];
                                                            $program_section = $get_course['program_section'];
                                                            $capacity = $get_course['capacity'];
                                                            $school_year_term = $get_course['school_year_term'];
                                                            $section = new Section($con, $course_id);

                                                            $section_obj = $section->GetSectionObj($course_id);

                                                            $totalStudent = $section->GetTotalNumberOfStudentInSection($course_id, $current_school_year_id);
                                                            $capacity = $section_obj['capacity'];

                                                            $program_id = $section_obj['program_id'];
                                                            $course_level = $section_obj['course_level'];

                                                            $removeSection = "removeSection($course_id, \"$program_section\")";

                                                            // echo $totalStudent;
                                                            // echo $program_id;

                                                            $new_program_section = $section->AutoCreateAnotherSection($program_section);

                                                            // echo $program_section;
                                                            // echo "<br>";
                                                            // echo $new_program_section;

                                                            // $isCloseddisabled = "<input name='selected_course_id' 
                                                            //     class='radio' value='$course_id' 
                                                            //     type='radio' " . ($course_id == $student_course_id ? "checked" : "") . " " . ($isClosed ? "disabled" : "") . ">";   
                                                        

                                                            if($totalStudent == $capacity){
                                                            }
                                                          
                                                            echo "
                                                                <tr class='text-center'>
                                                                    <td>$course_id</td>
                                                                    <td>$program_section</td>
                                                                    <td>$totalStudent</td>
                                                                    <td>$capacity</td>
                                                                    <td>$school_year_term</td>
                                                                    <td>
                                                                    <input name='selected_course_id' class='radio' value='$course_id' type='radio' " . ($student_course_id == $course_id ? 'checked' : '') . ">
                                                                    <td>
                                                                        <i onclick='$removeSection' style='cursor: pointer; color: orange; " . ($totalStudent != 0 ? "display: none;" : "") . "' class='fas fa-times'></i>
                                                                    </td>
                                                                </tr>
                                                            ";
                                                        }
                                                        
                                                    } 
                                                ?>
                                            </tbody>
                                        </table>

                                        <a href="process_enrollment.php?student_id=<?php echo $student_id;?>&step1=true&manual=true">
                                            <button type="button" class="btn btn-outline-primary">Return</button>
                                        </a>

                                        <button name="old_manual_section" type="submit" class="btn btn-primary">Proceed</button>
                                         

                                    </form>                 
                                </div>
                            </div>

                        </div>
                    <?php
                }

                if(isset($_GET['student_id'])  && isset($_GET['manual']) && $_GET['manual'] == "true" &&
                    isset($_GET['step3']) && $_GET['step3'] == "true" && isset($_GET['selected_course_id'])){
                    

                    $selected_course_id = $_GET['selected_course_id'];


                    if(isset($_POST['enrollment_manual_btn'])){

                        // $wasSuccess = $enrollment->EnrolledOSRegular($student_id, $student_course_id,
                        //     $current_school_year_id, $enrollment_form_id);
                        
                        echo $enrollment_form_id;
                        
                    }
                        
                    ?>

                        <div class="row col-md-12"> 

                            <div class="content">

                                <div class="form-header">
                                    <div class="header-content">
                                        <h2>Enrollment form</h2>
                                    </div>

                                    <div class="student-table">
                                        <table>
                                            <tr>
                                            <th>Form ID</th>
                                            <th>Admission type</th>
                                            <th>Student no</th>
                                            <th>Status</th>
                                            <th>Submitted on:</th>
                                            </tr>
                                            <tr>
                                            <td><?php echo $enrollment_form_id;?></td>
                                            <td>Old</td>
                                            <td><?php echo $student_unique_id;?></td>
                                            <td>Evaluation</td>

                                            <td><?php
                                                $date = new DateTime($date_creation);
                                                $formattedDate = $date->format('m/d/Y H:i');

                                                echo $formattedDate;
                                            ?></td>
                                            
                                            </tr>
                                        </table>
                                    </div>
                                </div>
                            </div>

                            <!-- First 2nd -->
                            <div class="process-status">
                                <table class="selection">
                                    <tr>
                                        <th class="checkDetails" id="icon-1">
                                        <i  class="bi bi-clipboard-check"></i>
                                        </th>
                                        <th style="color: #FFFF;" id="line-1">___________________________</th>
                                        <th class="findSection" id="icon-2">
                                        <i style="color: #FFFF;" class="bi bi-building"></i>
                                        </th>
                                        <th style="color: #FFFF;" id="line-2">___________________________</th>
                                        <th class="subConfirm" id="icon-3">
                                        <i style="color: #FFFF;" class="bi bi-journal"></i>
                                        </th>
                                    </tr>
                                    <tr>
                                        <td class="checkDetails" id="process-1">Check details</td>
                                        <td></td>
                                        <td class="checkDetails" id="process-2">Find section</td>
                                        <td></td>
                                        <td style="color: #FFFF;" class="subConfirm" id="process-3">Subject Confirmation</td>
                                    </tr>
                                </table>
                            </div>


                            <div class="card">
                                <div class="card-body">
                                    <div  style="width: 100%;" class="form-details"
                                        id="enrollment-details">
                                        <h3>Enrollment Details</h3>

                                        <?php
                                            if($student_type == 0){

                                                ?>
                                                <form class="row g-3">
                                                    <div class="col-md-4">
                                                    <label for="inputSchoolYear" class="form-label">S.Y.</label>
                                                    <input readonly type="text" value="<?php echo $current_school_year_term; ?>" class="form-control" id="inputSchoolYear" />
                                                    </div>
                                                    <div class="col-md-4">
                                                        <label for="inputTrack" class="form-label">Track</label>

                                                        <select id="inputTrack" class="form-select">
                                                            <?php 

                                                                $SHS_DEPARTMENT = 4;
                                                            
                                                                $track_sql = $con->prepare("SELECT 
                                                                program_id, track, acronym 
                                                                    
                                                                    FROM program 
                                                                WHERE department_id =:department_id
                                                                GROUP BY track
                                                                ");

                                                                $track_sql->bindValue(":department_id", $SHS_DEPARTMENT);
                                                                $track_sql->execute();

                                                                while($row = $track_sql->fetch(PDO::FETCH_ASSOC)){

                                                                    $row_program_id = $row['program_id'];

                                                                    $track = $row['track'];

                                                                    $selected = ($row_program_id == $program_id) ? "selected" : "";

                                                                    echo "<option value='$row_program_id' $selected>$track</option>";
                                                                }
                                                            ?>
                                                        
                                                        </select>
                                                    </div>

                                                    <div class="col-md-4">
                                                        <label for="inputStrand" class="form-label">Strand</label>

                                                        <select onchange="chooseStrand(this, <?php echo $pending_enrollees_id;?>)" 
                                                            name="strand" id="strand" class="form-select">
                                                            <?php 

                                                                $SHS_DEPARTMENT = 4;
                                                            
                                                                $track_sql = $con->prepare("SELECT 
                                                                    program_id, track, acronym 
                                                                        
                                                                        FROM program 
                                                                    WHERE department_id =:department_id
                                                                    GROUP BY acronym
                                                                ");

                                                                $track_sql->bindValue(":department_id", $SHS_DEPARTMENT);
                                                                $track_sql->execute();

                                                                while($row = $track_sql->fetch(PDO::FETCH_ASSOC)){

                                                                    $row_program_id = $row['program_id'];

                                                                    $acronym = $row['acronym'];

                                                                    $selected = ($row_program_id == $student_program_id) ? "selected" : "";

                                                                    echo "<option value='$row_program_id' $selected>$acronym</option>";
                                                                }
                                                            ?>

                                                        </select>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <label for="inputYear" class="form-label">Year</label>
                                                    
                                                        <select id="inputYear" class="form-select">

                                                            <option <?php if ($student_course_level == '11') echo 'selected'; ?>>Grade 11</option>
                                                            <option <?php if ($student_course_level == '12') echo 'selected'; ?>>Grade 12</option>
                                                        
                                                        </select>
                                                    </div>
                                                    <div class="col-md-6">
                                                    <label for="inputSemester" class="form-label">Semester</label>
                                                    <input readonly type="text" value="<?php echo $current_school_year_period; ?>" class="form-control" id="inputSchoolYear" />
                                                    <!-- <select id="inputSemester" class="form-select">
                                                        <option <?php if ($current_school_year_period == 'First') echo 'selected'; ?>>First</option>
                                                        <option <?php if ($current_school_year_period == 'Second') echo 'selected'; ?>>Second</option>
                                                    </select> -->
                                                    </div>
                                                </form>
                                                <?php
                                            }
                                            else if($student_type == 1){
                                                ?>

                                                <form class="row g-3">
                                                    <div class="col-md-4">
                                                        <label for="inputSchoolYear" class="form-label">S.Y.</label>
                                                        <input readonly type="text" value="<?php echo $current_school_year_term; ?>" class="form-control" id="inputSchoolYear" />
                                                    </div>
                                                    <div class="col-md-4">
                                                        <label for="inputTrack" class="form-label">Track</label>

                                                        <select id="inputTrack" class="form-select">
                                                            <?php 

                                                                $SHS_DEPARTMENT = 4;
                                                            
                                                                $track_sql = $con->prepare("SELECT 
                                                                program_id, track, acronym 
                                                                    
                                                                    FROM program 
                                                                WHERE department_id !=:department_id
                                                                GROUP BY track
                                                                ");

                                                                $track_sql->bindValue(":department_id", $SHS_DEPARTMENT);
                                                                $track_sql->execute();
                                                                
                                                                while($row = $track_sql->fetch(PDO::FETCH_ASSOC)){

                                                                    $row_program_id = $row['program_id'];

                                                                    $track = $row['track'];

                                                                    $selected = ($row_program_id == $program_id) ? "selected" : "";

                                                                    echo "<option value='$row_program_id' $selected>$track</option>";
                                                                }
                                                            ?>
                                                        
                                                        </select>
                                                    </div>

                                                    <div class="col-md-4">
                                                        <label for="inputStrand" class="form-label">Strand</label>

                                                        <select onchange="chooseStrand(this, <?php echo $pending_enrollees_id;?>)" 
                                                            name="strand" id="strand" class="form-select">
                                                            <?php 

                                                                $SHS_DEPARTMENT = 4;
                                                            
                                                                $track_sql = $con->prepare("SELECT 
                                                                program_id, track, acronym 
                                                                    
                                                                    FROM program 
                                                                WHERE department_id !=:department_id
                                                                GROUP BY acronym
                                                                ");

                                                                $track_sql->bindValue(":department_id", $SHS_DEPARTMENT);
                                                                $track_sql->execute();

                                                                while($row = $track_sql->fetch(PDO::FETCH_ASSOC)){

                                                                    $row_program_id = $row['program_id'];

                                                                    $acronym = $row['acronym'];

                                                                    $selected = ($row_program_id == $program_id) ? "selected" : "";

                                                                    echo "<option value='$row_program_id' $selected>$acronym</option>";
                                                                }
                                                            ?>

                                                        </select>
                                                    </div>

                                                    <div class="col-md-6">
                                                        <label for="inputYear" class="form-label">Year</label>
                                                    
                                                        <select id="inputYear" class="form-select">

                                                            <option value="1" selected>1st Year</option>
                                                            <option value="2">2nd Year</option>
                                                            <option value="3">3rd Year</option>
                                                            <option value="4">4th Year</option>
                                                        </select>
                                                    </div>

                                                    <div class="col-md-6">
                                                        <label for="inputSemester" class="form-label">Semester</label>
                                                        <input readonly type="text" value="<?php echo $current_school_year_period; ?>" class="form-control" id="inputSchoolYear" />
                                                        <!-- <select id="inputSemester" class="form-select">
                                                            <option <?php if ($current_school_year_period == 'First') echo 'selected'; ?>>First</option>
                                                            <option <?php if ($current_school_year_period == 'Second') echo 'selected'; ?>>Second</option>
                                                        </select> -->
                                                        </div>
                                                </form>

                                                <?php
                                            }
                                        ?>

                                    </div>
                                </div>
                            </div>

                            <div class="container mt-4 mb-2">
                                <h4 style="color: #BB4444;" class="mb-3 text-start"><?php echo $section_name;?> Subjects </h4>


                                <table id="courseTable" class="table table-striped table-bordered table-hover "  style="font-size:13px" cellspacing="0"  > 
                                    <thead>
                                        <tr class="text-center"> 
                                            <th rowspan="2">Id</th>
                                            <th rowspan="2">Code</th>
                                            <th rowspan="2">Description</th>
                                            <th rowspan="2">Unit</th>
                                            <th rowspan="2">Type</th>
                                            <!-- <th colspan="4">Schedule</th>  -->
                                        </tr>	
                                        <!-- <tr class="text-center">
                                            <th>Day</th>
                                            <th>Time</th>
                                            <th>Room</th> 
                                            <th>Instructor</th> 
                                        </tr>	 -->
                                    </thead> 	
                                    <tbody>
                                        <?php
                                            $course_level = 11;
                                            $active = "yes";

                                            # Only Available now.

                                            $sql = $con->prepare("SELECT 
                                                t1.*,
                                                t3.room, t3.schedule_time, t3.schedule_day ,
                                                t4.firstname,
                                                t4.lastname
                                                
                                                FROM subject as t1

                                                INNER JOIN course as t2 ON t2.course_id = t1.course_id
                                                LEFT JOIN subject_schedule as t3 ON t3.subject_id = t1.subject_id
                                                LEFT JOIN teacher as t4 ON t4.teacher_id = t3.teacher_id

                                                WHERE t1.course_id=:course_id
                                                AND t1.semester=:semester
                                                ");

                                            $sql->bindValue(":course_id", $selected_course_id);
                                            $sql->bindValue(":semester", $current_school_year_period);
                                            // $sql->bindValue(":course_level", $course_level);

                                            $sql->execute();
                                        
                                            if($sql->rowCount() > 0){

                                                while($row = $sql->fetch(PDO::FETCH_ASSOC)){

                                                    $subject_id = $row['subject_id'];
                                                    $subject_code = $row['subject_code'];
                                                    $subject_title = $row['subject_title'];
                                                    $unit = $row['unit'];
                                                    $subject_type = $row['subject_type'];

                                                    $room = $row['room'];
                                                    $schedule_time = $row['schedule_time'];
                                                    $schedule_day = $row['schedule_day'];
                                                    
                                                    $teacher_firstname = $row['firstname'];
                                                    $teacher_lastname = $row['lastname'];

                                                    // echo $subject_id;
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
                                    </tbody>
                                </table>

                    
                                <a href="process_enrollment.php?student_id=<?php echo $student_id?>&step2=true&manual=true">
                                    <button type="button" class="btn btn-outline-primary">Return</button>
                                </a>

                                    

                               <button onclick="enrollment_manual('<?php echo $student_id; ?>', '<?php echo $student_course_id; ?>', '<?php echo $current_school_year_id; ?>', '<?php echo $enrollment_form_id; ?>')" 
                                        type="button" class="btn btn-success btn-sm">
                                    Confirm Manual
                                </button>


                                <script>
                                    function enrollment_manual(student_id, student_course_id,
                                        current_school_year_id, enrollment_form_id){

                                        Swal.fire({
                                            icon: 'question',
                                            title: `Confirm Enrollment?`,
                                            showCancelButton: true,
                                            confirmButtonText: 'Yes',
                                            cancelButtonText: 'Cancel'

                                        }).then((result) => {
                                            if (result.isConfirmed) {
                                                $.ajax({
                                                    url: '../ajax/enrollee/confirm_manual_enrollment.php',
                                                    type: 'POST',
                                                    data: {
                                                        student_id, student_course_id,
                                                        current_school_year_id, enrollment_form_id
                                                    },
                                                    // dataType: "json",
                                                    success: function(response) {

                                                        console.log(response);
                                                        // console.log(response['status'])
                                                        if(response == "manual_enrollment_success"){
                                                            Swal.fire({

                                                                title: "Manual Enrollment Approved",
                                                                icon: "success",
                                                                showCancelButton: false,
                                                                confirmButtonText: "OK",

                                                            }).then((result) => {

                                                                if (result.isConfirmed) {

                                                                    // SHS Regular Standard
                                                                    var url = `../enrollees/subject_insertion.php?enrolled_subjects=true&id=${student_id}`;
                                                                    window.location.href = url;
                                                                } else {
                                                                    // User clicked Cancel or closed the dialog
                                                                }
                                                            });

                                                        }

                                                        },
                                                        error: function(xhr, status, error) {
                                                            // handle any errors here
                                                        }
                                                    });
                                                }
                                            });
                                        }
                                </script>
                            </div> 
                        </div>
                    <?php
                }

            }
            
    }else {
        // Unset the enrollment form ID if the condition is not met
        // unset($_SESSION['enrollment_form_id_manual']);
    }

?>

