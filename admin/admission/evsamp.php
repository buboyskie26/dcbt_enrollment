

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
    <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }
        .content{
            width: 100%;
        }
        .head{
            background-color: black ;
            width: 100%;
            padding-top: 10px;
            padding-bottom: 10px;
            padding-left: 40px;
            min-height: 188px;
            position: relative;
        }
        .head h2{
            font-size: normal;
            font-weight: 700;
            font-size: 36px;
        }

        .head p {
            font-style: normal;
            font-weight: 300;
            font-size: 14px;
            line-height: 17px;
            color: #FFF;
        }

        .back-menu{
            width: 100%;
            height: 50px;
            /* background-color: orange; */
            position: relative;
        }



        .back-menu button{
            width: 150px;
            margin-left: 34px;
            height: 100%;
            background-color: transparent;
            outline: none;
            border: none;
        }
        .button-container{
            display: flex;
            flex-direction: row;
            justify-content: center;
            align-items: center;
            margin-top: 35px;
            padding-bottom: -10px;
            /* margin-bottom: -10px; */
            position: absolute;
            bottom: 0;
            left: 50%;
        transform: translateX(-50%);

        }

        .button-container .selection-btn{
            width: 200px;
            height: 53px;
            background: #EFEFEF;
            border: none;
            font-style: normal;
            font-weight: 400;
            font-size: 16px;
        }
        

        main.table {
            /* width: 82vw; */
            /* min-width: 90vw; */
            height: 90vh;
            margin-top: 35px;
            background-color: #fff5;

            backdrop-filter: blur(7px);
            box-shadow: 0 .4rem .8rem #0005;
            border-radius: .8rem;
            overflow: hidden;
            padding: 4px 8px;
        }


        .table__header {
            width: 100%;
            height: 62px;
            background-color: #fff4;
            padding: .8rem 1rem;

            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-top: 30px;
            margin-bottom: 30px;
        }

        .table__header .input-group {
            width: 35%;
            height: 100%;
            background-color: #fff5;
            padding: 0 .8rem;
            border-radius: 2rem;

            display: flex;
            justify-content: center;
            align-items: center;

            /* transition: .2s; */

            width: 45%;
            background-color: #fff8;
            box-shadow: 0 .1rem .4rem #0002;
        }


        /* .table__header .input-group:hover {
            width: 45%;
            background-color: #fff8;
            box-shadow: 0 .1rem .4rem #0002;
        } */



        .table__header .input-group img {
            width: 1.2rem;
            height: 1.2rem;
        }

        .table__header .input-group input {
            width: 100%;
            padding: 0 .5rem 0 .3rem;
            
            background-color: transparent;
            border: none;
            outline: none;
        }


        /* SCROLLING, SHOULD DISABLE &&  REPLACED WITH PAGINATION. */
        .table__body {
            width: 100%;
            max-height: calc(89% - 1.6rem);
            background-color: #fffb;

            margin: .8rem auto;
            border-radius: .6rem;
            overflow: auto;
            overflow: overlay;
            margin-left: 0;
        }

        .table__body::-webkit-scrollbar{
            width: 0.5rem;
            height: 0.5rem;
        }

        .table__body::-webkit-scrollbar-thumb{
            border-radius: .5rem;
            background-color: #0004;
            visibility: hidden;
        }

        .table__body:hover::-webkit-scrollbar-thumb{ 
            visibility: visible;
        }

        table {
            width: 100%;
        }

        td img {
            width: 36px;
            height: 36px;
            margin-right: .5rem;
            border-radius: 50%;

            vertical-align: middle;
        }

        table, th, td {
            border-collapse: collapse;
            padding: 1rem;
            text-align: left;
        }

        thead th {
            position: sticky;
            top: 0;
            left: 0;
            background-color: #d5d1defe;
            cursor: pointer;
            text-transform: capitalize;
            text-align: center;
        }

        tbody tr:nth-child(even) {
            background-color: #0000000b;
        }

        tbody tr {
            --delay: .1s;
            transition: .5s ease-in-out var(--delay), background-color 0s;
        }

        tbody tr.hide {
            opacity: 0;
            transform: translateX(100%);
        }

        tbody tr:hover {
            background-color: #fff6 !important;
        }

        tbody tr td,
        tbody tr td p,
        tbody tr td img {
            transition: .2s ease-in-out;
            text-align: center;
        }

        tbody tr.hide td,
        tbody tr.hide td p {
            padding: 0;
            font: 0 / 0 sans-serif;
            transition: .2s ease-in-out .5s;
        }

        tbody tr.hide td img {
            width: 0;
            height: 0;
            transition: .2s ease-in-out .5s;
        }

        .status {
            padding: .2rem 0;
            border-radius: 2rem;
            text-align: center;
            margin-left: -18px;
            margin-right: 18px;
            margin-top: 16.5px;
        }

        .status.delivered {
            background-color: #86e49d;
            color: #006b21;
        }

        .status.cancelled {
            background-color: #d893a3;
            color: #b30021;
        }

        .status.pending {
            background-color: #ebc474;
        }

        .status.shipped {
            background-color: #6fcaea;
        }


        @media (max-width: 1000px) {
            td:not(:first-of-type) {
                min-width: 12.1rem;
            }
        }

        thead th span.icon-arrow {
            display: inline-block;
            width: 1.3rem;
            height: 1.3rem;
            border-radius: 50%;
            border: 1.4px solid transparent;
            
            text-align: center;
            font-size: 1rem;
            
            margin-left: .5rem;
            transition: .2s ease-in-out;
        }

        thead th:hover span.icon-arrow{
            border: 1.4px solid #6c00bd;
        }

        thead th:hover {
            color: #6c00bd;
        }

        thead th.active span.icon-arrow{
            background-color: #6c00bd;
            color: #fff;
        }

        thead th.asc span.icon-arrow{
            transform: rotate(180deg);
        }

        thead th.active,tbody td.active {
            color: #6c00bd;
        }

        .export__file {
            position: relative;
        }

        .export__file .export__file-btn {
            display: inline-block;
            width: 2rem;
            height: 2rem;
            background: #fff6 url(images/export.png) center / 80% no-repeat;
            border-radius: 50%;
            transition: .2s ease-in-out;
        }

        .export__file .export__file-btn:hover { 
            background-color: #fff;
            transform: scale(1.15);
            cursor: pointer;
        }

        .export__file input {
            display: none;
        }

        .export__file .export__file-options {
            position: absolute;
            right: 0;
            
            width: 12rem;
            border-radius: .5rem;
            overflow: hidden;
            text-align: center;

            opacity: 0;
            transform: scale(.8);
            transform-origin: top right;
            
            box-shadow: 0 .2rem .5rem #0004;
            
            transition: .2s;
        }

        .export__file input:checked + .export__file-options {
            opacity: 1;
            transform: scale(1);
            z-index: 100;
        }

        .export__file .export__file-options label{
            display: block;
            width: 100%;
            padding: .6rem 0;
            background-color: #f2f2f2;
            
            display: flex;
            justify-content: space-around;
            align-items: center;

            transition: .2s ease-in-out;
        }

        .export__file .export__file-options label:first-of-type{
            padding: 1rem 0;
            background-color: #86e49d !important;
        }

        .export__file .export__file-options label:hover{
            transform: scale(1.05);
            background-color: #fff;
            cursor: pointer;
        }

        .export__file .export__file-options img{
            width: 2rem;
            height: auto;
        }

        .fixed-width {
            word-wrap: break-word;
            max-width: 170px;
        }
    </style>
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
                    <tr>
                        <td> 1 </td>
                        <td class='fixed-width'> <img src="images/Zinzu Chan Lee.jpg" alt="">Zinzu Chan Lee</td>
                        <td> Seoul </td>
                        <td> 17 Dec, 2022 </td>
                        <td>
                            <p class="status delivered">Delivered</p>
                        </td>
                        <td> <strong> $128.90 </strong></td>
                    </tr>
                    <tr>
                        <td> 2 </td>
                        <td class='fixed-width'><img src="images/Jeet Saru.jpg" alt=""> Jeet Saru </td>
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
                    <!-- <tr>
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
                    </tr> -->
                </tbody>
            </table>
        </section>
    </main>

</div>
