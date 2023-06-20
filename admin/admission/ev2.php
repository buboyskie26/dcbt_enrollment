<?php  
 
    include('../../enrollment/classes/StudentEnroll.php');
    include('../../enrollment/classes/Enrollment.php');
    include('../../enrollment/classes/Section.php');

    include('../registrar_enrollment_header.php');

    $enroll = new StudentEnroll($con);

    $school_year_obj = $enroll->GetActiveSchoolYearAndSemester();

    $current_school_year_id = $school_year_obj['school_year_id'];
    $current_school_year_term = $school_year_obj['term'];
    $current_school_year_period = $school_year_obj['period'];

    $enrollment = new Enrollment($con, null);
    
    $section = new Section($con, null);

    $pendingEnrollment = $enrollment->PendingEnrollment();
    $ongoingEnrollment = $enrollment->OngoingEnrollment();
    $unionEnrollment = $enrollment->UnionEnrollment();

    $pendingEnrollment = $enrollment->PendingEnrollment();
    $waitingPaymentEnrollment = $enrollment->WaitingPaymentEnrollment($current_school_year_id);
    $waitingApprovalEnrollment = $enrollment->WaitingApprovalEnrollment($current_school_year_id);
    $enrolledStudentsEnrollment = $enrollment->EnrolledStudentsWithinSYSemester($current_school_year_id);

    $pendingEnrollmentCount = count($pendingEnrollment);
    $waitingPaymentEnrollmentCount = count($waitingPaymentEnrollment);
    $waitingApprovalEnrollmentCount = count($waitingApprovalEnrollment);
    $enrolledStudentsEnrollmentCount = count($enrolledStudentsEnrollment);
?>

<head>
    <link rel="stylesheet" href="./admission/evaluation.css">
</head>

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
                    <button type="button" class="selection-btn" id="evaluation" onclick="evaluation_btn()" style="background: rgb(239, 239, 239); color: black;">
                        Evaluation
                    </button>
                </a>
            </div>
            <div class="waiting-payment">
                <a href="waiting_payment.php">
                    <button type="button" class="selection-btn" id="waiting-payment" onclick="waiting_payment_btn()" style="background: rgb(2, 0, 28); color: white;">
                        Waiting payment
                    </button>
                </a>

            </div>
            <div class="waiting-approval">
                <a href="waiting_approval.php">
                    <button type="button" class="selection-btn" id="waiting-approval" onclick="waiting_approval_btn()" style="background: rgb(2, 0, 28); color: white;">
                        Waiting approval
                    </button>
                </a>
            </div>
            <div class="enrolled">
                <a href="enrolled.php">
                    <button type="button" class="selection-btn" id="enrolled" onclick="enrolled_btn()" style="background: rgb(2, 0, 28); color: white;">
                        Enrolled
                    </button>
                </a>
            </div>
        </div>
    </div>
    
    <main class="table">

        <section class="table__header">
            <h1  >Form Details</h1>
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
                                            <button class='button-36'>Evaluate</button>
                                        </a>
                                    ";

                                }
                                else if(empty($identity) && $student_status_pending == "Transferee"){
                                    # Comes from Pending Table.
                                    $type = "New Transferee";
                                    $button_url = "
                                        <a href='$url_trans'>
                                            <button class='button-36'>Evaluate</button>
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
                                            <button class='button-36'>
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
                                            <button class='button-36'>
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
                                    <button class='button-36'>Click</button>
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
