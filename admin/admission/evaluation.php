

<?php  
 
    include('../../enrollment/classes/StudentEnroll.php');
    include('../../enrollment/classes/Enrollment.php');
    include('../../enrollment/classes/Section.php');
    // include('../../admin/assets/css/admission/evaluation.css');
    include('../registrar_enrollment_header.php');
    
    ?>  
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <!-- <link rel="stylesheet" href="./admission/evaluation.css"> -->
            <link rel="stylesheet" href="../../admin/assets/css/admission/evaluation.css">
        </head>
    <?php

    // require_once __DIR__ . '/../../vendor/autoload.php';
    // use Dompdf\Dompdf;
    // use Dompdf\Options;

    if(!AdminUser::IsRegistrarAuthenticated()){

        header("Location: /dcbt/adminLogin.php");
        exit();
    }

    $enroll = new StudentEnroll($con);

    $section = new Section($con, null);


    $school_year_obj = $enroll->GetActiveSchoolYearAndSemester();

    $current_school_year_id = $school_year_obj['school_year_id'];
    $current_school_year_term = $school_year_obj['term'];
    $current_school_year_period = $school_year_obj['period'];

    $enrollment = new Enrollment($con, null);

    $pendingEnrollment = $enrollment->PendingEnrollment();
    $ongoingEnrollment = $enrollment->OngoingEnrollment();

    $unionEnrollment = $enrollment->UnionEnrollment();

    $pendingEnrollment = $enrollment->PendingEnrollment();
    $waitingPaymentEnrollment = $enrollment->WaitingPaymentEnrollment($current_school_year_id);
    $waitingApprovalEnrollment = $enrollment->WaitingApprovalEnrollment($current_school_year_id);
    $enrolledStudentsEnrollment = $enrollment->EnrolledStudentsWithinSYSemester($current_school_year_id);

    $pendingEnrollmentCount = count($pendingEnrollment);
    $unionEnrollmentCount = count($unionEnrollment);
    $waitingPaymentEnrollmentCount = count($waitingPaymentEnrollment);
    $waitingApprovalEnrollmentCount = count($waitingApprovalEnrollment);
    $enrolledStudentsEnrollmentCount = count($enrolledStudentsEnrollment);

    if(isset( $_SESSION['enrollment_form_id'])){
        unset($_SESSION['enrollment_form_id']);
    }
?>

    <div class="row col-md-12">


        <div class="content">
            <div class="back-menu">
                <button type="button" class="admission-btn" onclick="admission()">
                    <i class="fas fa-arrow-left"></i> Admission
                </button>
            </div>
            
            <div style="color: #fff;" class="head">
                <h3 class="mt-2">Enrollment form finder (SHS)</h3>
                <p>Note: Numbers on tabs only count current school year and semester</p>
                
                <div class="button-container">
                    <div class="evaluation">
                        <a href="evaluation.php">
                            <button type="button" class="selection-btn" id="evaluation" onclick="evaluation_btn()" style="background: rgb(239, 239, 239); color: black;">
                                Evaluation (<?php echo $unionEnrollmentCount;?>)
                            </button>
                        </a>

                    </div>
                    <div class="waiting-payment">
                        <a href="waiting_payment.php">
                            <button type="button" class="selection-btn" id="waiting-payment" onclick="waiting_payment_btn()" style="background: rgb(2, 0, 28); color: white;">
                                Waiting payment (<?php echo $waitingPaymentEnrollmentCount;?>)
                            </button>
                        </a>

                    </div>
                    <div class="waiting-approval">
                        <a href="waiting_approval.php">
                            <button type="button" class="selection-btn" id="waiting-approval" onclick="waiting_approval_btn()" style="background: rgb(2, 0, 28); color: white;">
                                Waiting approval (<?php echo $waitingApprovalEnrollmentCount;?>)
                            </button>
                        </a>
                    </div>
                    <div class="enrolled">
                        <a href="enrolled.php">
                            <button type="button" class="selection-btn" id="enrolled" onclick="enrolled_btn()" style="background: rgb(2, 0, 28); color: white;">
                                Enrolled (<?php echo $enrolledStudentsEnrollmentCount;?>)
                            </button>
                        </a>
                    </div>
                </div>
            </div>
            
            <main class="table">

                <section class="table__header">
                    <h1 >Form Details</h1>
                    <div class="input-group">
                        <input type="search" placeholder="Search for student...">
                        <img src="images/search.png" alt="">
                    </div>
                    <!-- <div class="export__file">
                        <label for="export-file" class="export__file-btn" title="Export File"></label>
                        <input type="checkbox" id="export-file">
                        <div class="export__file-options">
                            <label>Export As &nbsp; &#10140;</label>
                            <label for="export-file" id="toPDF">PDF <img src="images/pdf.png" alt=""></label>
                            <label for="export-file" id="toJSON">JSON <img src="images/json.png" alt=""></label>
                            <label for="export-file" id="toCSV">CSV <img src="images/csv.png" alt=""></label>
                            <label for="export-file" id="toEXCEL">EXCEL <img src="images/excel.png" alt=""></label>
                        </div>
                    </div> -->
                </section>
                
                <section class="table__body">
                    <table>
                        <thead>
                            <tr>
                                <th> Name <span class="icon-arrow">&UpArrow;</span></th>
                                <th> Student No. <span class="icon-arrow">&UpArrow;</span></th>
                                <th> Type <span class="icon-arrow">&UpArrow;</span></th>
                                <th> Strand <span class="icon-arrow">&UpArrow;</span></th>
                                <th> Date Submitted <span class="icon-arrow">&UpArrow;</span></th>
                                <th> Action <span class="icon-arrow">&UpArrow;</span></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                                if(count($unionEnrollment) > 0){
                                    foreach ($unionEnrollment as $key => $row) {

                                        $submission_creation = $row['submission_creation'];

                                        $student_status_pending = $row['student_status_pending'];
                                        $pending_enrollees_id = $row['pending_enrollees_id'];
                                        $student_course_id = $row['student_course_id'];
                                        $student_id = $row['student_id'];
                                        // $student_unique_id = $row['student_unique_id'];

                                        $student_unique_id = empty($student_unique_id) ? $row['student_unique_id'] : "N/A";
                                        $program_id = $row['program_id'];
                                        $student_statusv2 = $row['student_statusv2'];
                                        $admission_status = $row['admission_status'];
                                        $student_classification = $row['student_classification'];

                                        $identity = "";
                                        $type = "";

                                        $button_url = "";

                                        if($student_classification != NULL){
                                            # 1 -> Tertiary, 0 -> SHS
                                            $identity = $student_classification == 1 ? "Tertiary" 
                                                : ($student_classification == 0 ? "SHS" : "Pending");
                                        }

                                        $process_url = "process_enrollment.php?step1=true&id=$pending_enrollees_id";
                                        $url_trans = "transferee_process_enrollment.php?step1=true&id=$pending_enrollees_id";
                                        
                                        $fullname = $row['firstname'] . " " . $row['lastname'];

                                        $acronym = $section->GetAcronymByProgramId($program_id);

                                        $trans_url = "transferee_process_enrollment.php?step3=true&st_id=$student_id&selected_course_id=$student_course_id";

                                        if(empty($identity) && $student_status_pending == "Regular"){
                                            # Comes from Pending Table.
                                            $type = "New Regular";
                                            $button_url = "
                                                <a href='$process_url'>
                                                    <button class='button-style-primary primary'>Evaluate</button>
                                                </a>
                                            ";
                                        }
                                        else if(empty($identity) && $student_status_pending == "Transferee"){
                                            # Comes from Pending Table.
                                            $type = "New Transferee";
                                            $button_url = "
                                                <a href='$url_trans'>
                                                    <button class='button-style-primary primary'>Evaluate</button>
                                                </a>
                                            ";
                                        }
                                        else if($identity == "Tertiary"){
                                            // $type = "O.S $admission_status (Tertiary Irregular)";
                                            // $type = "O.S $admission_status (Tertiary)";
                                            # TRANSFEREE Irregular
                                            if($student_statusv2 == "Regular"){
                                                $type = "O.S $admission_status (Tertiary) Regular";

                                            }else if($student_statusv2 == "Irregular"){
                                                $type = "O.S $admission_status (Tertiary) Irregular";

                                                $button_url = "
                                                <a href='$trans_url'>
                                                    <button class='button-style-primary primary'>
                                                        Evaluate
                                                    </button>
                                                </a>
                                                ";
                                            }
                                        }
                                        else if($identity == "SHS"){
                                            // $type = "O.S $admission_status (Tertiary Irregular)";
                                            // $type = "O.S $admission_status (Tertiary)";
                                            # TRANSFEREE Irregular
                                            if($student_statusv2 == "Regular"){
                                                $type = "O.S $admission_status (SHS) Regular";

                                            }else if($student_statusv2 == "Irregular"){
                                                $type = "O.S $admission_status (SHS) Irregular";

                                                $button_url = "
                                                <a href='$trans_url'>
                                                    <button class='button-style-primary primary'>
                                                        Evaluate
                                                    </button>
                                                </a>
                                                ";
                                            }
                                        }
                                        else if($identity == "SHS"){
                                            $type = "O.S $admission_status (Senior High)";
                                        }
                                        $image = "<img src='images/Zinzu Chan Lee.jpg' alt=''>";

                                        $btnn = "
                                            <button class='button-style-primary primary'>Click</button>
                                        ";
                                        echo "
                                            <tr class='text-center'>
                                                <td>$fullname</td>
                                                <td>$student_unique_id</td>
                                                <td>$type</td>
                                                <td>$acronym</td>
                                                <td>$submission_creation</td>
                                                <td>$button_url</td>
                                            </tr>
                                        ";
                                    }
                                }
                            ?>
                            
                        </tbody>
                        <!-- <tbody>
                            <tr>
                                <td> 1 </td>
                                <td> <img src="images/Zinzu Chan Lee.jpg" alt="">Zinzu Chan Lee</td>
                                <td> Seoul </td>
                                <td> 17 Dec, 2022 </td>
                                <td>
                                    <p class="status delivered">Delivered</p>
                                </td>
                                <td> <strong> $128.90 </strong></td>
                            </tr>
                            <tr>
                                <td> 2 </td>
                                <td><img src="images/Jeet Saru.jpg" alt=""> Jeet Saru </td>
                                <td> Kathmandu </td>
                                <td> 27 Aug, 2023 </td>
                                <td>
                                    <p class="status cancelled">Cancelled</p>
                                </td>
                                <td> <strong>$5350.50</strong> </td>
                            </tr>
                            <tr>
                                <td> 3</td>
                                <td><img src="images/Sonal Gharti.jpg" alt=""> Sonal Gharti </td>
                                <td> Tokyo </td>
                                <td> 14 Mar, 2023 </td>
                                <td>
                                    <p class="status shipped">Shipped</p>
                                </td>
                                <td> <strong>$210.40</strong> </td>
                            </tr>
                            <tr>
                                <td> 4</td>
                                <td><img src="images/Alson GC.jpg" alt=""> Alson GC </td>
                                <td> New Delhi </td>
                                <td> 25 May, 2023 </td>
                                <td>
                                    <p class="status delivered">Delivered</p>
                                </td>
                                <td> <strong>$149.70</strong> </td>
                            </tr>
                            <tr>
                                <td> 5</td>
                                <td><img src="images/Sarita Limbu.jpg" alt=""> Sarita Limbu </td>
                                <td> Paris </td>
                                <td> 23 Apr, 2023 </td>
                                <td>
                                    <p class="status pending">Pending</p>
                                </td>
                                <td> <strong>$399.99</strong> </td>
                            </tr>
                            <tr>
                                <td> 6</td>
                                <td><img src="images/Alex Gonley.jpg" alt=""> Alex Gonley </td>
                                <td> London </td>
                                <td> 23 Apr, 2023 </td>
                                <td>
                                    <p class="status cancelled">Cancelled</p>
                                </td>
                                <td> <strong>$399.99</strong> </td>
                            </tr>
                            <tr>
                                <td> 7</td>
                                <td><img src="images/Alson GC.jpg" alt=""> Jeet Saru </td>
                                <td> New York </td>
                                <td> 20 May, 2023 </td>
                                <td>
                                    <p class="status delivered">Delivered</p>
                                </td>
                                <td> <strong>$399.99</strong> </td>
                            </tr>
                            <tr>
                                <td> 8</td>
                                <td><img src="images/Sarita Limbu.jpg" alt=""> Aayat Ali Khan </td>
                                <td> Islamabad </td>
                                <td> 30 Feb, 2023 </td>
                                <td>
                                    <p class="status pending">Pending</p>
                                </td>
                                <td> <strong>$149.70</strong> </td>
                            </tr>
                            <tr>
                                <td> 9</td>
                                <td><img src="images/Alex Gonley.jpg" alt=""> Alson GC </td>
                                <td> Dhaka </td>
                                <td> 22 Dec, 2023 </td>
                                <td>
                                    <p class="status cancelled">Cancelled</p>
                                </td>
                                <td> <strong>$249.99</strong> </td>
                            </tr>
                        </tbody> -->
                    </table>
                </section>
            </main>

        </div>


        <hr>
        <hr>

        <div style="display: flex;
            flex-direction: row;
            flex-wrap: wrap;
            justify-content: center;
            align-items: center;
            padding: 20px 20px 0px;
            gap: 1px;
            width: 100%;
            height: 74px;
            background: #02001C;
            margin-bottom: 15px;
            
            "">
            <div class="evaluation">
                <a href="evaluation.php">
                    <button type="button" class="selection-btn" id="evaluation" onclick="evaluation_btn()" style="background: rgb(239, 239, 239); color: black;">
                        Evaluation (<?php echo $pendingEnrollmentCount;?>)
                    </button>
                </a>

            </div>
            <div class="waiting-payment">
                <a href="waiting_payment.php">
                    <button type="button" class="selection-btn" id="waiting-payment" onclick="waiting_payment_btn()" style="background: rgb(2, 0, 28); color: white;">
                        Waiting Payment (<?php echo $waitingPaymentEnrollmentCount;?>)
                    </button>
                </a>

            </div>
            <div class="waiting-approval">
                <a href="waiting_approval.php">
                    <button type="button" class="selection-btn" id="waiting-approval" onclick="waiting_approval_btn()" style="background: rgb(2, 0, 28); color: white;">
                        Waiting Approval (<?php echo $waitingApprovalEnrollmentCount;?>)
                    </button>
                </a>
            </div>
            <div class="enrolled">
                <a href="enrolled.php">
                    <button type="button" class="selection-btn" id="enrolled" onclick="enrolled_btn()" style="background: rgb(2, 0, 28); color: white;">
                        Enrolled (<?php echo $enrolledStudentsEnrollmentCount;?>)
                    </button>
                </a>
            </div>
        </div>


        <div class="row col-md-12">
            <h3 class="mb-2 text-center text-primary">Union Table</h3>

            <table id="admission_evaluation" 
                class="table table-striped table-bordered table-hover "
                style="font-size:13px" cellspacing="0"  > 
                <thead>
                    <tr class="text-center"> 
                        <th rowspan="2">Name</th>
                        <th rowspan="2">Student No.</th>
                        <th rowspan="2">Type</th>
                        <th rowspan="2">Strand</th>
                        <th rowspan="2">Date Submitted</th>
                        <th rowspan="2">Action</th>
                    </tr>	
                </thead> 	

                <tbody>
                    <?php 
                        if(count($unionEnrollment) > 0){
                            foreach ($unionEnrollment as $key => $row) {

                                $submission_creation = $row['submission_creation'];

                                $student_status_pending = $row['student_status_pending'];
                                $pending_enrollees_id = $row['pending_enrollees_id'];
                                $student_course_id = $row['student_course_id'];
                                $student_id = $row['student_id'];
                                // $student_unique_id = $row['student_unique_id'];

                                $student_unique_id = empty($student_unique_id) ? $row['student_unique_id'] : "N/A";
                                $program_id = $row['program_id'];
                                $student_statusv2 = $row['student_statusv2'];
                                $admission_status = $row['admission_status'];
                                $student_classification = $row['student_classification'];

                                $identity = "";
                                $type = "";

                                $button_url = "";

                                if($student_classification != NULL){
                                    # 1 -> Tertiary, 0 -> SHS
                                    $identity = $student_classification == 1 ? "Tertiary" 
                                        : ($student_classification == 0 ? "SHS" : "Pending");
                                }

                                $process_url = "process_enrollment.php?step1=true&id=$pending_enrollees_id";
                                $url_trans = "transferee_process_enrollment.php?step1=true&id=$pending_enrollees_id";
                                
                                $fullname = $row['firstname'] . " " . $row['lastname'];

                                $acronym = $section->GetAcronymByProgramId($program_id);

                                $trans_url = "transferee_process_enrollment.php?step3=true&st_id=$student_id&selected_course_id=$student_course_id";

                                if(empty($identity) && $student_status_pending == "Regular"){
                                    # Comes from Pending Table.
                                    $type = "New Regular";
                                    $button_url = "
                                        <a href='$process_url'>
                                            <button class='btn btn-primary btn-sm'>View</button>
                                        </a>
                                    ";

                                }
                                else if(empty($identity) && $student_status_pending == "Transferee"){
                                    # Comes from Pending Table.
                                    $type = "New Transferee";
                                    $button_url = "
                                        <a href='$url_trans'>
                                            <button class='btn btn-outline btn-sm'>View</button>
                                        </a>
                                    ";
                                }
                                else if($identity == "Tertiary"){
                                    // $type = "O.S $admission_status (Tertiary Irregular)";
                                    // $type = "O.S $admission_status (Tertiary)";
                                    # TRANSFEREE Irregular
                                    if($student_statusv2 == "Regular"){
                                        $type = "O.S $admission_status (Tertiary) Regular";

                                    }else if($student_statusv2 == "Irregular"){
                                        $type = "O.S $admission_status (Tertiary) Irregular";

                                        $button_url = "
                                        <a href='$trans_url'>
                                            <button class='btn btn-outline-success btn-sm'>
                                                Evaluate
                                            </button>
                                        </a>
                                        ";
                                    }
                                }
                                else if($identity == "SHS"){
                                    // $type = "O.S $admission_status (Tertiary Irregular)";
                                    // $type = "O.S $admission_status (Tertiary)";
                                    # TRANSFEREE Irregular
                                    if($student_statusv2 == "Regular"){
                                        $type = "O.S $admission_status (SHS) Regular";

                                    }else if($student_statusv2 == "Irregular"){
                                        $type = "O.S $admission_status (SHS) Irregular";

                                        $button_url = "
                                        <a href='$trans_url'>
                                            <button class='btn btn-outline-success btn-sm'>
                                                Evaluate
                                            </button>
                                        </a>
                                        ";
                                    }
                                }
                                else if($identity == "SHS"){
                                    $type = "O.S $admission_status (Senior High)";
                                }
                                echo "
                                    <tr class='text-center'>
                                        <td>$fullname</td>
                                        <td>$student_unique_id</td>
                                        <td>$type</td>
                                        <td>$acronym</td>
                                        <td>$submission_creation</td>
                                        <td>$button_url</td>
                                    </tr>
                                ";
                            }
                        }
                    ?>
                </tbody>
            </table>
        </div>

        <hr>
        <hr>
        <?php
            if(count($pendingEnrollment) > 0){
                ?>
                    <div class="row col-md-12">
                        
                        <h3 class="mb-2 text-center text-primary">Pending Enrollees</h3>

                        <table id="admission_evaluation" 
                            class="table table-striped table-bordered table-hover "
                            style="font-size:13px" cellspacing="0"  > 
                            <thead>
                                <tr class="text-center"> 
                                    <th rowspan="2">Name</th>
                                    <th rowspan="2">Type</th>
                                    <th rowspan="2">Strand</th>
                                    <th rowspan="2">Date Submitted</th>
                                    <th rowspan="2">Status</th>
                                    <th rowspan="2">Action</th>
                                </tr>	
                            </thead> 	

                            <tbody>
                                <?php 
                                    $sql = $con->prepare("SELECT t1.*, t2.acronym 
                                        FROM pending_enrollees as t1

                                        LEFT JOIN program as t2 ON t2.program_id = t1.program_id
                                        WHERE t1.student_status !='APPROVED'
                                        AND t1.is_finished = 1

                                        ");

                                    $sql->execute();


                                    if(count($pendingEnrollment) > 0){
                                        foreach ($pendingEnrollment as $key => $row) {

                                            $fullname = $row['firstname'] . " " . $row['lastname'];
                                            $date_creation = $row['date_creation'];
                                            $acronym = $row['acronym'];
                                            $pending_enrollees_id = $row['pending_enrollees_id'];
                                            $student_unique_id = "N/A";

                                            $type = "";
                                            $url = "";
                                            $status = "Evaluation";
                                            $button_output = "";

                                            $process_url = "process_enrollment.php?step1=true&id=$pending_enrollees_id";
                                            $url_trans = "transferee_process_enrollment.php?step1=true&id=$pending_enrollees_id";

                                            if($row['student_status'] == "Regular"){
                                                $type = "New Regular";
                                                // $url = "../enrollees/view_student_new_enrollment.php?id=$pending_enrollees_id";
                                                $button_output = "
                                                    <a href='$process_url'>
                                                        <button class='btn btn-primary btn-sm'>View</button>
                                                    </a>
                                                ";
                                                
                                            }else  if($row['student_status'] == "Transferee"){
                                                $type = "New Transferee";
                                                // $url = "../enrollees/view_student_transferee_enrollment.php?id=$pending_enrollees_id";

                                                $button_output = "
                                                    <a href='$url_trans'>
                                                        <button class='btn btn-outline-primary btn-sm'>View</button>
                                                    </a>
                                                ";
                                            }

                                            echo "
                                                <tr class='text-center'>
                                                    <td>$fullname</td>
                                                    <td>$type</td>
                                                    <td>$acronym</td>
                                                    <td>$date_creation</td>
                                                    <td>$status</td>
                                                    <td>
                                                        $button_output
                                                    </td>
                                                </tr>
                                            ";
                                        }
                                    }
                                ?>
                            </tbody>
                        </table>

                    </div>
                <?php
            }else{
                echo "
                    <div class='col-md-12 row'>
                        <h3 class='text-info text-center'>No Data found For Pending Enrollees.</h3>
                        <hr>
                        <hr>
                    </div>
                ";
            }

            if(count($ongoingEnrollment) > 0){
                ?>
                    <div class="row col-md-12">

                        <h3 class="mb-2 text-center text-primary">Ongoing Student</h3>

                        <table id="admission_evaluation" 
                            class="table table-striped table-bordered table-hover "
                            style="font-size:13px" cellspacing="0"  > 
                            <thead>
                                <tr class="text-center"> 
                                    <th rowspan="2">Name</th>
                                    <th rowspan="2">Type</th>
                                    <th rowspan="2">Strand</th>
                                    <th rowspan="2">Date Submitted</th>
                                    <th rowspan="2">Status</th>
                                    <th rowspan="2">Action</th>
                                </tr>	
                            </thead> 	

                            <tbody>
                                <?php 
                                    // $sql = $con->prepare("SELECT t1.*, t2.acronym 
                                    //     FROM pending_enrollees as t1

                                    //     LEFT JOIN program as t2 ON t2.program_id = t1.program_id
                                    //     WHERE t1.student_status !='APPROVED'
                                    //     AND t1.is_finished = 1

                                    //     ");

                                    // $sql->execute();
                                    if(count($ongoingEnrollment) > 0){
                                        foreach ($ongoingEnrollment as $key => $row) {

                                            $fullname = $row['firstname'] . " " . $row['lastname'];
                                            $enrollment_date = $row['enrollment_date'];
                                            $student_id = $row['student_id'];
                                            $student_course_id = $row['course_id'];

                                            # O.S
                                            $admission_status = $row['admission_status'];
                                            $student_statusv2 = $row['student_statusv2'];
                                            $type = "O.S $admission_status (SHS $student_statusv2)";

                                            $username = $row['username'];

                                            $acronym = $row['acronym'];
                                            // $pending_enrollees_id = $row['pending_enrollees_id'];
                                            $student_unique_id = "N/A";

                                            $url = "";
                                            $status = "Evaluation";
                                            $button_output = "";

                                            // $process_url = "process_enrollment.php?step1=true&id=$pending_enrollees_id";
                                            $process_url = "";

                                            $regular_insertion_url = "../enrollees/subject_insertion.php?username=$username&id=$student_id";
                                            $trans_url = "transferee_process_enrollment.php?step3=true&st_id=$student_id&selected_course_id=$student_course_id";

                                            $evaluateBtn = "";


                                            if($admission_status == "Transferee" 
                                                && $student_statusv2 == "Regular"){

                                                $evaluateBtn = "
                                                    <a href='$regular_insertion_url'>
                                                        <button class='btn btn-success btn-sm'>
                                                            Evaluate
                                                        </button>
                                                    </a>
                                                ";
                                            }
                                            
                                            else if($admission_status == "Transferee"
                                                && $student_statusv2 == "Irregular"){
                                                $evaluateBtn = "
                                                    <a href='$trans_url'>
                                                        <button class='btn btn-outline-success btn-sm'>
                                                            Evaluate
                                                        </button>
                                                    </a>
                                                ";
                                            }

                                            // $evaluateBtn = "
                                            //         <a href='$trans_url'>
                                            //             <button class='btn btn-outline-success btn-sm'>
                                            //                 Evaluate
                                            //             </button>
                                            //         </a>
                                            //     ";



                                            echo "
                                                <tr class='text-center'>
                                                    <td>$fullname</td>
                                                    <td>$type</td>
                                                    <td>$acronym</td>
                                                    <td>$enrollment_date</td>
                                                    <td>$status</td>
                                                    <td>
                                                        $evaluateBtn
                                                    </td>
                                                </tr>
                                            ";
                                            }
                                    }
                                ?>
                            </tbody>
                        </table>

                    </div>
                <?php
            }else{
                echo "
                    <div class='col-md-12 row'>
                        <h3 class='text-info text-center'>No Ongoing Enrollees to be evaluated.</h3>
                    </div>
                ";
            }
        ?>

        

    </div>


    
