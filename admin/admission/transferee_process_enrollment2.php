<!DOCTYPE html>
<html>
<head>
    <style>
        #addedSubjectsTable tr td {
            text-align: center;
        }
    </style>
</head>

<?php
 
    include('../registrar_enrollment_header-2.php');
    include('../../enrollment/classes/StudentEnroll.php');
    include('../../enrollment/classes/StudentSubject.php');
    include('../../enrollment/classes/Section.php');
    include('../..//includes/classes/Student.php');
    include('../../admin/classes/Subject.php');
    include('../../enrollment/classes/Enrollment.php');
    include('../../enrollment/classes/OldEnrollees.php');
    include('../../enrollment/classes/Pending.php');
    
    include('../classes/Course.php');

    ?>
          <head>
            <meta charset="utf-8" />
            <meta
            name="viewport"
            content="width=device-width, inital-scale=1, shrink-to-fit=no"
            />

            <link rel="preconnect" href="https://fonts.googleapis.com" />
            <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
            <link
            href="https://fonts.googleapis.com/css2?family=Chakra+Petch:ital,wght@0,300;0,400;0,500;0,600;0,700;1,300;1,400;1,500;1,700&family=Lato:wght@100;300;700&display=swap"
            rel="stylesheet"
            />
            <link
            rel="stylesheet"
            href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css"
            integrity="sha384-xOolHFLEh07PJGoPkLv1IbcEPTNtaed2xpHsD9ESMhqIYd0nLMwNLD69Npy4HI+N"
            crossorigin="anonymous"
            />
            <link
            rel="stylesheet"
            href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css"
            />
            <script>
                let btn = document.querySelector("#tab-btn");
                    let sidebar = document.querySelector(".sidebar");
                    let selectionBtn = document.querySelectorAll(".selection-btn");

                    btn.onclick = function () {
                        sidebar.classList.toggle("active");
                        selectionBtn.forEach((button) => {
                        button.classList.toggle("active");
                        });
                    };
            </script>
            <style>
                * {
                    margin: 0;
                    padding: 0;
                    box-sizing: border-box;
                    font-family: 'Lato', sans-serif;
                }

                body {
                    background: #EFEFEF;
                }

                .bi-info-circle {
                    padding-right: 5px;
                    color: #42B4D8;
                    cursor: pointer;
                }

                /*====Nav-items====*/
                .sidebar {
                    position: fixed;
                    top: 0;
                    left: 0;
                    height: 100vh;
                    width: 80px;
                    background-color: #02001C;
                    padding: .4rem .8rem;
                    transition: all 0.5s ease;
                    z-index: 20;
                }

                .user img {
                    width: 50px;
                    border-radius: 100px;
                }

                .sidebar.active~.content {
                    left: 250px;
                    width: calc(100% - 250px);
                }

                .sidebar.active {
                    width: 250px;
                }

                .sidebar #tab-btn {
                    position: absolute;
                    color: #FFF;
                    top: .4rem;
                    left: 50%;
                    font-size: 1.2rem;
                    line-height: 50px;
                    transform: translateX(-50%);
                    cursor: pointer;
                }

                .sidebar.active #tab-btn {
                    left: 90%;
                }

                .sidebar .top .logo {
                    color: #FFF;
                    display: flex;
                    height: 50px;
                    width: 100%;
                    align-items: center;
                    justify-content: center;
                    pointer-events: none;
                    opacity: 0;
                }

                .sidebar.active .top .logo {
                    opacity: 1;
                }

                .top .logo i {
                    font-size: 2rem;
                    margin-right: 5px;
                }

                .user {
                    display: flex;
                    align-items: center;
                    margin: 1rem 0;
                }

                .user p {
                    color: #FFF;
                    opacity: 1;
                    margin-left: 1rem;
                }

                .bold {
                    font-weight: 600;
                }

                .sidebar p {
                    opacity: 0;
                }

                .sidebar.active p {
                    opacity: 1;
                }

                .sidebar ul li {
                    position: relative;
                    list-style-type: none;
                    height: 50px;
                    width: 90%;
                    margin: 0.8rem auto;
                    line-height: 20px;
                }

                .sidebar ul li a {
                    color: #FFF;
                    display: flex;
                    align-items: center;
                    text-decoration: none;
                    border-radius: 0.8rem;
                    font-style: normal;
                    font-weight: 400;
                    font-size: 20px;
                }

                .sidebar ul li .active {
                    background-color: #FFF;
                    color: #12171e;
                    font-style: normal;
                    font-weight: 800;
                    font-size: 20px;
                }

                .sidebar ul li a:hover {
                    background-color: #FFF;
                    color: #12171e;

                }

                .sidebar ul li a i {
                    min-width: 50px;
                    text-align: center;
                    height: 50px;
                    border-radius: 12px;
                    line-height: 50px;
                }

                .sidebar .nav-item {
                    opacity: 0;
                }

                .sidebar.active .nav-item {
                    opacity: 1;
                }

                .sidebar ul li .tooltip {
                    position: absolute;
                    left: 125px;
                    top: 50%;
                    transform: translate(-50%, -50%);
                    box-shadow: 0 0.5rem 0.8rem rgba(0, 0, 0, 0.2);
                    border-radius: .6rem;
                    padding: .4rem 1.2rem;
                    line-height: 1.8rem;
                    opacity: 0;
                    z-index: 20;
                    background-color: #FFF;
                }

                .sidebar ul li:hover .tooltip {
                    opacity: 1;
                }

                .sidebar.active ul li .tooltip {
                    display: none;
                }

                /*====admission-enrollment-form====*/
                .content {
                    position: relative;
                    min-height: 100vh;
                    top: 0;
                    left: 80px;
                    transition: all 0.5s ease;
                    width: calc(100% - 80px);
                    padding: 1rem;
                }

                .back-menu {
                    display: flex;
                    flex: row;
                    align-items: center;
                    padding: 8px 40px;
                    gap: 8px;
                    width: 100%;
                    height: 46px;
                    cursor: pointer;
                }

                .admission-btn {
                    border: none;
                    background: none;
                    color: #070065;
                    font-style: normal;
                    font-weight: 700;
                    font-size: 18px;
                    cursor: pointer;
                }

                .admission-btn:hover {
                    color: #863131;
                }

                .form-header {
                    display: flex;
                    flex-direction: column;
                    justify-content: center;
                    align-items: flex-start;
                    padding: 32px 26px;
                    gap: 20px;
                    width: 100%;
                    height: auto;
                }

                .form-header h2 {
                    font-style: normal;
                    font-weight: 700;
                    font-size: 36px;
                    line-height: 43px;
                    display: flex;
                    align-items: center;
                    color: #FFF;
                }

                .header-content {
                    display: flex;
                    flex-direction: row;
                    justify-content: space-between;
                    padding: 0px;
                    gap: 10px;
                    width: 90%;
                    height: 43px;
                    align-items: center;
                }

                #shs-waiting-payment,
                #shs-waiting-approval,
                #shs-enrolled {
                    display: none;
                }

                #waiting-payment,
                #waiting-approval,
                #enrolled {
                    background: #02001C;
                    color: #FFF;
                }

                .header-content h3 {
                    font-style: normal;
                    font-weight: 700;
                    font-size: 36px;
                    line-height: 43px;
                    color: #070065;
                    text-shadow: 0px 0px 4px rgba(0, 0, 0, 0.25);
                }

                .header-content p {
                    font-weight: 400;
                    font-size: 16px;
                    margin-bottom: 10px;
                    padding-bottom: 0;
                    padding-left: 20px;
                }

                .dropdown {
                    display: flex;
                    flex-direction: row;
                    justify-content: center;
                    align-items: center;
                    gap: 10px;
                }

                .dropdown-toggle {
                    cursor: pointer;
                    margin-right: auto;
                    color: #FFF;
                }

                .dropdown-menu {
                    display: none;
                    position: absolute;
                    background-color: #f9f9f9;
                    width: 170px;
                    height: auto;
                    box-shadow: 0px 8px 16px 0px rgba(0, 0, 0, 0.2);
                    padding: 12px 16px;
                    z-index: 1;
                    left: 0;
                }

                .dropdown-menu li {
                    list-style-type: none;
                }

                .table-content {
                    display: flex;
                    flex-direction: column;
                    align-items: flex-start;
                    padding: 0px;
                    width: 100%;
                    height: auto;
                }

                .table-content th {
                    background: #DCDCDC;
                }

                .select-buttons {
                    display: flex;
                    flex-direction: row;
                    align-items: flex-start;
                    padding: 10px;
                    gap: 10px;
                }

                .select-buttons .select-all-btn {
                    font-style: normal;
                    font-weight: 400;
                    font-size: 18px;
                    line-height: 18px;
                    border: none;
                    color: #FABF6D;
                    background: transparent;
                    text-decoration: underline;
                }

                .select-buttons .unselect-all-btn {
                    font-style: normal;
                    font-weight: 400;
                    font-size: 18px;
                    line-height: 18px;
                    border: none;
                    color: #A4A7FC;
                    background: transparent;
                    text-decoration: underline;
                }

                .select-buttons .select-all-btn:hover {
                    color: #8a693b;
                }

                .select-buttons .unselect-all-btn:hover {
                    color: #595b92;
                }

                .action-btn {
                    display: inline-block;
                    flex-direction: row;
                    justify-content: center;
                    align-items: center;
                    padding: 0px;
                    gap: 10px;
                    width: 67px;
                    height: 19px;
                    background: #62BFDC;
                    font-style: normal;
                    font-weight: 400;
                    font-size: 16px;
                    line-height: 19px;
                    color: white;
                    border: none;
                }

                .action-btn:hover {
                    background: #478ca1;
                }

                .checkbox {
                    margin-left: 30px;
                }

                .action {
                    border: none;
                    background: transparent;
                    color: #E85959;
                }

                .action:hover {
                    color: #9b3131;
                }

                .student-table {
                    display: flex;
                    flex-direction: row;
                    align-items: flex-start;
                    padding: 5px 0px;
                    width: 100%;
                    height: 58px;
                }

                .student-table table {
                    color: #FFF;
                }

                table {
                    table-layout: fixed;
                    border-collapse: collapse;
                    width: 100%;
                    text-align: center;
                }

                tbody {
                    font-style: normal;
                    font-weight: 400;
                    font-size: 17px;
                    align-items: center;
                }

                .selection {
                    margin-top: 5px;
                }

                .checkDetails {
                    color: white;
                }

                .findSection,
                .subConfirm {
                    color: #888888;
                }

                .bg-content {
                    display: flex;
                    flex-direction: column;
                    align-items: center;
                    padding: 50px 0px;
                    width: 100%;
                    height: auto;
                }

                .form-details {
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

                .form-details h3 {
                    display: flex;
                    align-items: center;
                    font-style: normal;
                    font-weight: 700;
                    font-size: 36px;
                    line-height: 43px;
                    color: #070065;
                }

                form {
                    flex: none;
                    order: 1;
                    align-self: stretch;
                    flex-grow: 0;
                }

                .delete-btn {
                    border: none;
                    background: none;
                    color: red;
                    font-style: normal;
                    font-weight: 700;
                    font-size: 16px;
                }

                .delete-btn:hover {
                    color: rgb(156, 11, 11);
                }

                .next {
                    display: flex;
                    flex-direction: row;
                    justify-content: flex-end;
                    padding: 0px 50px;
                    gap: 19px;
                    width: 100%;
                }

                .proceed {
                    display: flex;
                    flex-direction: row;
                    justify-content: center;
                    align-items: center;
                    padding: 6px 18px;
                    gap: 5px;
                    width: 99px;
                    height: 39px;
                    font-style: normal;
                    font-weight: 400;
                    font-size: 20px;
                    color: white;
                    background: #4D4BA5;
                    box-shadow: 0px 5px 5px rgba(0, 0, 0, 0.25);
                    border: none;
                    border-radius: 10px;
                    margin-top: 30px;
                }

                .proceed:hover {
                    background: #343375;
                }

                #track,
                #strand {
                    width: 281px;
                    align-items: center;
                    text-align: center;
                }

                #year,
                #sem {
                    width: 358px;
                    align-items: center;
                    text-align: center;
                }

                .return {
                    display: flex;
                    flex-direction: row;
                    justify-content: center;
                    align-items: center;
                    padding: 6px 18px;
                    gap: 5px;
                    width: 99px;
                    height: 39px;
                    font-style: normal;
                    font-weight: 400;
                    font-size: 20px;
                    color: white;
                    background: #4D4BA5;
                    box-shadow: 0px 5px 5px rgba(0, 0, 0, 0.25);
                    border: none;
                    border-radius: 10px;
                    margin-top: 30px;
                }

                .add-sub {
                    display: flex;
                    flex-direction: row;
                    justify-content: center;
                    align-items: center;
                    padding: 6px 18px;
                    gap: 5px;
                    width: 155px;
                    height: 39px;
                    font-style: normal;
                    font-weight: 400;
                    font-size: 20px;
                    color: white;
                    background: #4D4BA5;
                    box-shadow: 0px 5px 5px rgba(0, 0, 0, 0.25);
                    border: none;
                    border-radius: 10px;
                    margin-top: 30px;
                }

                .add-sub:hover {
                    background: #343375;
                }

                .return:hover {
                    background: #343375;
                }

                .sub-head th {
                    background: #DCDCDC;
                }

                .confirm {
                    display: flex;
                    flex-direction: row;
                    justify-content: center;
                    align-items: center;
                    padding: 6px 18px;
                    gap: 5px;
                    width: 99px;
                    height: 39px;
                    font-style: normal;
                    font-weight: 400;
                    font-size: 20px;
                    color: white;
                    background: #4DA54E;
                    box-shadow: 0px 5px 5px rgba(0, 0, 0, 0.25);
                    border: none;
                    border-radius: 10px;
                    margin-top: 30px;
                }

                .confirm:hover {
                    background: #2e662f;
                }

                #enrollment-details,
                #avail-sections,
                #subject-confirmation,
                #added-subjects,
                #enrollment-form,
                #subjects-details,
                #added-subjects {
                    display: none;
                }

                #return-check-details,
                #proceed-sub-confirm,
                #return-find-section,
                #confirm,
                #add-sub-btn {
                    display: none;
                }

                .choices {
                    display: flex;
                    flex-direction: row;
                    flex-wrap: wrap;
                    justify-content: center;
                    align-items: center;
                    padding: 20px 53px 0px;
                    gap: 1px;
                    width: 100%;
                    height: 74px;
                    background: #02001C;
                    flex: none;
                    order: 2;
                    align-self: stretch;
                    flex-grow: 0;
                }

                .selection-btn {
                    display: flex;
                    flex-direction: row;
                    justify-content: center;
                    align-items: center;
                    padding: 5px 20px;
                    gap: 10px;
                    width: 260px;
                    height: 54px;
                    background: #EFEFEF;
                    border: none;
                    font-style: normal;
                    font-weight: 400;
                    font-size: 20px;
                }

                .selection-btn.active {
                    width: 220px;
                    transition: all 0.5s ease;
                }

                .search-bar {
                    display: flex;
                    flex-direction: row;
                    align-items: center;
                    margin-top: 30px;
                    gap: 10px;
                    width: 100%;
                    height: 55px;
                    box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.25);
                    border-radius: 10px;
                }

                .search-bar i {
                    position: absolute;
                }

                .icon {
                    padding: 10px;
                }

                .search-bar button {
                    position: absolute;
                    right: 80px;
                    width: 100px;
                    height: 39px;
                    background: #A4A7FC;
                    color: #FFF;
                    border-radius: 10px;
                    border: none;
                    font-style: normal;
                    font-weight: 400;
                    font-size: 20px;
                    align-items: center;
                }

                .search-bar button:hover {
                    background: #6c6ea5;
                }

                .search-field {
                    width: 100%;
                    height: 55px;
                    border: none;
                    border-radius: 10px;
                    align-items: center;
                    text-align: center;
                    font-style: normal;
                    font-weight: 400;
                    font-size: 20px;
                }

                .head-table {
                    display: flex;
                    flex-direction: row;
                    justify-content: center;
                    align-items: center;
                    margin-top: 50px;
                    padding: 0px;
                    gap: 10px;
                    width: 100%;
                    height: auto;
                }

                .head-table h3 {
                    font-style: normal;
                    font-weight: 700;
                    font-size: 36px;
                    line-height: 43px;
                    display: flex;
                    align-items: center;
                    color: #070065;
                    text-shadow: 0px 0px 4px rgba(0, 0, 0, 0.25);
                }

                .content-box {
                    display: flex;
                    flex-direction: column;
                    justify-content: space-between;
                    align-items: flex-start;
                    left: 30px;
                    padding: 32px 26px;
                    margin-bottom: 30px;
                    width: calc(100% - 30px);
                    height: auto;
                    background: #FFFFFF;
                    box-shadow: 0px 5px 20px rgba(0, 0, 0, 0.25);
                    border-radius: 10px;
                }

                .content-box h3 {
                    line-height: 43.2px;
                    font-weight: 700;
                    font-size: 36px;
                    color: #070065;
                    text-shadow: 0px 0px 4px rgba(0, 0, 0, 0.25);
                    margin-bottom: 10px;
                    padding-bottom: 0;
                    padding-left: 20px;
                }

                .content-box p {
                    font-weight: 400;
                    font-size: 16px;
                    margin-bottom: 10px;
                    padding-bottom: 0;
                    padding-left: 20px;
                }

                #subjects-details th,
                #added-subjects th {
                    background: #DCDCDC;
                }

                #enrolled-subjects {
                    background: none;
                    color: white;
                }

                #student-form th {
                    padding-bottom: 10px;
                }

                #student-form td {
                    padding-bottom: 10px;
                }

                .removed-subjects {
                    display: flex;
                    flex-direction: column;
                    justify-content: center;
                    align-items: center;
                    margin-top: 40px;
                    padding: 5px 0px;
                    gap: 5px;
                    width: 100%;
                    height: 35px;
                }

                .removed-subjects h3 {
                    font-style: normal;
                    font-weight: 700;
                    font-size: 20px;
                }

                .removed-subjects table {
                    margin-bottom: 40px;
                }

                .approve-btn {
                    display: flex;
                    flex-direction: row;
                    justify-content: flex-end;
                    margin-top: 30px;
                    padding: 0px 50px;
                    gap: 19px;
                    width: 100%;
                }

                .approve-enrollment {
                    display: flex;
                    flex-direction: row;
                    justify-content: center;
                    align-items: center;
                    padding: 6px 18px;
                    gap: 5px;
                    width: 223px;
                    height: 39px;
                    background: #4EA64F;
                    border-radius: 10px;
                    border: none;
                    font-style: normal;
                    font-weight: 400;
                    font-size: 20px;
                    color: white;
                }

                .approve-enrollment:hover {
                    background: #2a582b;
                }

                .head {
                    display: flex;
                    flex-direction: column;
                    justify-content: center;
                    align-items: flex-start;
                    padding: 30px 50px;
                    width: 100%;
                    height: auto;
                    background: #02001C;
                }

                .head h3 {
                    font-style: normal;
                    font-weight: 700;
                    font-size: 36px;
                    line-height: 43px;
                    color: #FFF;
                    text-shadow: 0px 0px 4px rgba(0, 0, 0, 0.25);
                }

                .head p {
                    font-style: normal;
                    font-weight: 300;
                    font-size: 14px;
                    line-height: 17px;
                    color: #FFF;
                }

                .progress-bar {
                    display: flex;
                    justify-content: space-between;
                    align-items: center;
                    width: 100%;
                    margin-bottom: 20px;
                }

                .steps {
                    display: flex;
                    flex-direction: row;
                    justify-content: center;
                    line-height: 40px;
                    width: 100%;
                    height: 50px;
                }

                .step {
                    flex: 1;
                    padding: 5px;
                    text-align: center;
                    background-color: rgb(143, 143, 143);
                    font-style: normal;
                    font-weight: 500;
                    font-size: 18px;
                }

                .step-content {
                    display: flex;
                    flex-direction: column;
                    justify-content: center;
                    align-items: center;
                    padding: 1px 80px;
                    gap: 10px;
                    width: 100%;
                    height: auto;
                }

                .bg-content .step-content {
                    display: none;
                }

                .step-content.active {
                    display: flex;
                }

                .step.active {
                    background-color: dodgerblue;
                    color: white;
                }

                .student-info,
                .ParentGuardian-info,
                .enrollment-details {
                    display: flex;
                    flex-direction: column;
                    justify-content: center;
                    align-items: flex-start;
                    padding-left: 30px;
                    padding-right: 30px;
                    width: 100%;
                    height: auto;
                }

                .student-info h4,
                .ParentGuardian-info h4,
                .enrollment-details h4 {
                    font-style: normal;
                    font-weight: 700;
                    font-size: 18px;
                    line-height: 18px;
                    color: #070065;
                    text-shadow: 0px 0px 4px rgba(0, 0, 0, 0.25);
                }

                .ParentGuardian-info h5 {
                    font-style: normal;
                    font-weight: 700;
                    font-size: 16px;
                    line-height: 18px;
                    color: #070065;
                    text-shadow: 0px 0px 4px rgba(0, 0, 0, 0.25);
                }

                .info-box {
                    display: flex;
                    flex-direction: column;
                    justify-content: center;
                    align-items: center;
                    padding: 0px;
                    width: 100%;
                    height: auto;
                }

                .info-1,
                .info-2,
                .info-3,
                .info-4,
                .info-5,
                .info-6,
                .info-7 {
                    display: flex;
                    flex-direction: row;
                    align-items: flex-start;
                    padding: 10px;
                    gap: 10px;
                    width: 100%;
                    height: auto;
                }

                .info-1 input,
                .info-2 input,
                .info-3 input,
                .info-4 input,
                .info-5 input,
                .info-6 input,
                .info-7 input {
                    width: 100%;
                    text-align: center;
                    border: 1px solid #D9D9D9;
                    border-radius: 5px;
                }

                .selection-box-1 select {
                    width: 180px;
                    border: 1px solid #d9d9d9;
                    border-radius: 5px;
                    text-align: center;
                }

                .btn-placing {
                    display: flex;
                    flex-direction: row;
                    justify-content: flex-end;
                    width: 100%;
                    height: auto;
                }

                .btn-placing .next-btn,
                .btn-placing .prev-btn,
                .btn-placing .add-btn {
                    width: 113px;
                    height: 40px;
                    background: #4D4BA5;
                    box-shadow: 0px 5px 5px rgba(0, 0, 0, 0.25);
                    border-radius: 10px;
                    border: none;
                    font-style: normal;
                    font-weight: 400;
                    font-size: 18px;
                    color: #FFF;
                }

                .btn-placing .confirm-btn {
                    width: 113px;
                    height: 40px;
                    background: #4DA54E;
                    box-shadow: 0px 5px 5px rgba(0, 0, 0, 0.25);
                    border-radius: 10px;
                    border: none;
                    font-style: normal;
                    font-weight: 400;
                    font-size: 18px;
                    color: #FFF;
                }

                .confirm-btn:hover {
                    background: rgb(64, 122, 58);
                }

                .btn-placing .prev-btn,
                .btn-placing {
                    margin-right: 20px;
                }

                .next-btn:hover,
                .prev-btn:hover,
                .return-home:hover {
                    background: #34337c;
                }

                .step-content span {
                    font-style: normal;
                    font-weight: 700;
                    font-size: 16px;
                    width: 40%;
                    align-items: center;
                    justify-content: center;
                    text-align: center;
                }

                .addSubject {
                    display: flex;
                    flex-direction: row;
                    justify-content: center;
                    align-items: center;
                    padding: 6px 18px;
                    gap: 5px;
                    width: 165px;
                    height: 39px;
                    background: #4D4BA5;
                    border-radius: 10px;
                    border: none;
                    color: #FFF;
                    font-style: normal;
                    font-weight: 400;
                    font-size: 18px;
                }

                .addSubject:hover {
                    background: #34337c;
                }
            </style>
        </head>
    <?php


    if(!AdminUser::IsRegistrarAuthenticated()){
        header("Location: /dcbt/adminLogin.php");
        exit();
    }


    if(isset($_GET['id'])
        || isset($_GET['st_id'])
    ){

        $pending_enrollees_id = isset($_GET['id']) ?  $_GET['id'] : null;

        unset($_SESSION['pending_enrollees_id']);
        unset($_SESSION['process_enrollment']);

        $studentEnroll = new StudentEnroll($con);
        $enrollment = new Enrollment($con, $studentEnroll);
        $old = new OldEnrollees($con, $studentEnroll);
        $studentSubject = new StudentSubject($con);
        

        $enrollment_form_id = $enrollment->GenerateEnrollmentFormId();

        if (!isset($_SESSION['enrollment_form_id'])) {
            $enrollment_form_id = $enrollment->GenerateEnrollmentFormId();
            $_SESSION['enrollment_form_id'] = $enrollment_form_id;
        } else {
            $enrollment_form_id = $_SESSION['enrollment_form_id'];
        }

        $school_year_obj = $studentEnroll->GetActiveSchoolYearAndSemester();

        $current_school_year_id = $school_year_obj['school_year_id'];
        $current_school_year_term = $school_year_obj['term'];
        $current_school_year_period = $school_year_obj['period'];

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
            $religion = $row['religion'];
            $birthplace = $row['birthplace'];
            $email = $row['email'];
            $type = $row['type'];

            $student_fullname = $firstname . " " . $lastname;

            $program = $con->prepare("SELECT acronym FROM program
                WHERE program_id=:program_id
                LIMIT 1
            ");
            $program->bindValue(":program_id", $program_id);
            $program->execute();

            $program_acronym = $program->fetchColumn();
 
            // $enrollment_form_id = $enrollment->GetEnrollmentId();

            $student_program_section = "";
            $student_course_id = 0;
            $student_id = null;
            $student_course_level = null;

            $enrollment_id = null;

            $student = $con->prepare("SELECT *
                
                FROM student
                WHERE firstname=:firstname
                AND lastname=:lastname
                AND middle_name=:middle_name
                
                ");
            $student->bindValue(":firstname", $firstname);
            $student->bindValue(":lastname", $lastname);
            $student->bindValue(":middle_name", $middle_name);
            $student->execute();

            $enrollment = new Enrollment($con, $studentEnroll);

            if($student->rowCount() > 0){

                $row_student = $student->fetch(PDO::FETCH_ASSOC);

                $student_username = $row_student['username'];
                $student_id = $row_student['student_id'];
                $student_course_level = $row_student['course_level'];

                $student_course_id = $studentEnroll->GetStudentCourseId($student_username);

                $student_program_section = $studentEnroll->GetStudentProgramSection($student_course_id);

                $enrollment_id = $enrollment->GetEnrollmentId($student_id,
                    $student_course_id, $current_school_year_id);

                $enrollment_form_id = $enrollment->GetEnrollmentFormId($student_id,
                    $student_course_id, $current_school_year_id);

                // echo $enrollment_id;
            }


        }

        if(isset($_GET['id']) && isset($_GET['step1'])){
            ?>
                <div class="row col-md-12">


                    <div class="content">

                        <div class="back-menu">
                            <button type="button" class="admission-btn" onclick="admission()">
                            <i class="bi bi-arrow-left-circle"></i> Admission
                            </button>
                        </div>

                        <div class="head">
                            <div class="header-content">
                                <h3>Enrollment form</h3>

                                <div class="dropdown">
                                    <div class="dropdown-toggle" onclick="toggleDropdown()">
                                        <i class="bi bi-three-dots-vertical"></i>
                                    </div>
                                    <ul class="dropdown-menu">
                                        <li>
                                            <button type="button" class="action" onclick="edit_calendar()">
                                            <i class="bi bi-file-x"></i>Delete form
                                            </button>
                                        </li>
                                    </ul>
                                </div>
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
                                        <td>Transferee</td>
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
                        <div class="progress-bar">
                            <div class="steps">
                                <div class="step active">Check form details</div>
                                <div class="step">Find section</div>
                                <div class="step">Subject confirmation</div>
                            </div>
                        </div>
                        <div class="bg-content">
                            <div class="step-content active">
                                <div class="content-box">
                                    <h3>Student form details</h3>

                                    <div class="student-info">
                                        <h4>Student information</h4>

                                        <form action="" class="info-box">
                                            <div class="info-1">
                                                <label for="name"> Name </label>
                                                <input
                                                    type="text"
                                                    name="lastName"
                                                    id="lastName"
                                                    value="<?php echo $lastname; ?>"
                                                    placeholder="Last name"
                                                />
                                                <input
                                                    type="text"
                                                    name="firstName"
                                                    id="firstName"
                                                    value="<?php echo $firstname; ?>"
                                                    placeholder="First name"
                                                />
                                                <input
                                                    type="text"
                                                    name="middleName"
                                                    id="middleName"
                                                    value="<?php echo $middle_name; ?>"
                                                    placeholder="Middle name"
                                                />
                                                <input
                                                    type="text"
                                                    name="suffixName"
                                                    id="suffixName"
                                                    value="<?php echo "N/A"; ?>"
                                                    placeholder="Suffix name"
                                                />
                                            </div>
                                            <div class="info-2">
                                                <label for="status"> Status </label>
                                                <div class="selection-box-1">
                                                    <select name="status" id="status">
                                                    <option value="Single">Single</option>
                                                    <option value="Married">Married</option>
                                                    <option value="Divorced">Divorced</option>
                                                    <option value="Widowed">Widowed</option>
                                                    </select>
                                                </div>
                                                <label for="citizenship"> Citizenship </label>
                                                <input type="text" name="citizenship" id="citizenship" value="<?php echo "Filipino" ?>" />
                                                <label for="gender"> Gender </label>
                                                <div class="selection-box-1">
                                                    <select name="gender" id="gender">
                                                    <option value="Male">Male</option>
                                                    <option value="Female">Female</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="info-3">
                                                <label for="birthdate"> Birthdate </label>
                                                <input type="date" name="birthdate" id="birthdate" value="<?php echo $birthday; ?>" />
                                                <label for="birthplade"> Birthplace </label>
                                                <input
                                                    type="text"
                                                    name="birthplace"
                                                    id="birthplace"
                                                    value="<?php echo "Philippines"; ?>"
                                                />
                                                <label for="religion"> Religion </label>
                                                <input type="text" name="religion" id="religion" value="<?php echo "Catholic"; ?>" />
                                            </div>
                                            <div class="info-4">
                                                <label for="address"> Address </label>
                                                <input type="text" name="address" id="address" value="<?php echo $address; ?>" />
                                                </div>
                                                <div class="info-5">
                                                <label for="phoneNo"> Phone no. </label>
                                                <input type="text" name="phoneNo" id="phoneNo" value="<?php echo $contact_number; ?>" />
                                                <label for="email"> Email </label>
                                                <input type="text" name="email" id="email" value="<?php echo "@gmail.com" ?>" />
                                            </div>
                                        </form>
                                    </div>
                                    <div class="ParentGuardian-info">
                                        <h4>Parent/Guardian's Information</h4>
                                        <h5>Father's information</h5>

                                        <form action="" class="info-box">
                                            <div class="info-1">
                                                <label for="name"> Name </label>
                                                <input
                                                    type="text"
                                                    name="fathrLastName"
                                                    id="fatherLastName"
                                                    value=""
                                                    placeholder="Last name"
                                                />
                                                <input
                                                    type="text"
                                                    name="fatherFirstName"
                                                    id="fatherFirstName"
                                                    value=""
                                                    placeholder="First name"
                                                />
                                                <input
                                                    type="text"
                                                    name="fatherMiddleName"
                                                    id="fatherMiddleName"
                                                    value=""
                                                    placeholder="Middle name"
                                                />
                                                <input
                                                    type="text"
                                                    name="fatherSuffixName"
                                                    id="fatherSuffixName"
                                                    value=""
                                                    placeholder="Suffix name"
                                                />
                                            </div>
                                            <div class="info-2">
                                                <label for="phoneNo"> Phone no. </label>
                                                <input
                                                    type="text"
                                                    name="fatherPhoneNo"
                                                    id="fatherPhoneNo"
                                                    value=""
                                                />
                                                <label for="email"> Email </label>
                                                <input
                                                    type="text"
                                                    name="fatherEmail"
                                                    id="fatherEmail"
                                                    value=""
                                                />
                                                <label for="occupation"> Occupation </label>
                                                <input
                                                    type="text"
                                                    name="fatherOccupation"
                                                    id="fatherOccupation"
                                                />
                                            </div>
                                        </form>
                                    </div>

                                    <div class="ParentGuardian-info">
                                        <h5>Mother's information</h5>

                                        <form action="" class="info-box">
                                            <div class="info-3">
                                                <label for="name"> Name </label>
                                                <input
                                                    type="text"
                                                    name="motherLastName"
                                                    id="motherLastName"
                                                    value=""
                                                    placeholder="Last name"
                                                />
                                                <input
                                                    type="text"
                                                    name="motherFirstName"
                                                    id="motherFirstName"
                                                    value=""
                                                    placeholder="First name"
                                                />
                                                <input
                                                    type="text"
                                                    name="motherMiddleName"
                                                    id="motherMiddleName"
                                                    value=""
                                                    placeholder="Middle name"
                                                />
                                                <input
                                                    type="text"
                                                    name="motherSuffixName"
                                                    id="motherSuffixName"
                                                    value=""
                                                    placeholder="Suffix name"
                                                />
                                            </div>
                                            <div class="info-4">
                                                <label for="phoneNo"> Phone no. </label>
                                                <input
                                                    type="text"
                                                    name="motherPhoneNo"
                                                    id="motherPhoneNo"
                                                    value=""
                                                />
                                                <label for="email"> Email </label>
                                                <input
                                                    type="text"
                                                    name="motherEmail"
                                                    id="motherEmail"
                                                    value=""
                                                />
                                                <label for="occupation"> Occupation </label>
                                                <input
                                                    type="text"
                                                    name="motherOccupation"
                                                    id="motherOccupation"
                                                    value=""
                                                />
                                            </div>
                                        </form>
                                    </div>

                                        <div class="ParentGuardian-info">
                                            <h5>Guardian's information</h5>

                                            <form action="" class="info-box">
                                                <div class="info-5">
                                                    <label for="name"> Name </label>
                                                    <input
                                                        type="text"
                                                        name="guardianLastName"
                                                        id="guardianLastName"
                                                        value=""
                                                        placeholder="Last name"
                                                    />
                                                    <input
                                                        type="text"
                                                        name="guardianFirstName"
                                                        id="guardianFirstName"
                                                        value=""
                                                        placeholder="First name"
                                                    />
                                                    <input
                                                        type="text"
                                                        name="guardianMiddleName"
                                                        id="guardianMiddleName"
                                                        value=""
                                                        placeholder="Middle name"
                                                    />
                                                    <input
                                                        type="text"
                                                        name="guardianSuffixName"
                                                        id="guardianSuffixName"
                                                        value=""
                                                        placeholder="Suffix name"
                                                    />
                                                </div>
                                                <div class="info-6">
                                                    <label for="phoneNo"> Phone no. </label>
                                                    <input
                                                        type="text"
                                                        name="guardianPhoneNo"
                                                        id="guardianPhoneNo"
                                                        value=""
                                                    />
                                                    <label for="email"> Email </label>
                                                    <input
                                                        type="text"
                                                        name="guardianEmail"
                                                        id="guardianEmail"
                                                        value=""
                                                    />
                                                    </div>
                                                    <div class="info-7">
                                                    <label for="relationship"> Relationship </label>
                                                    <input
                                                        type="text"
                                                        name="guardianRelationship"
                                                        id="guardianRelationship"
                                                        value=""
                                                    />
                                                    <label for="occupation"> Occupation </label>
                                                    <input
                                                        type="text"
                                                        name="guardianOccupation"
                                                        id="guardianOccupation"
                                                        value=""
                                                    />
                                                </div>
                                            </form>
                                        </div>
                                </div>
                            </div>
                    <?php

                        echo "
                            <div class='btn-placing'>
                                <a href='transferee_process_enrollment-2.php?step2=true&id=$pending_enrollees_id'>
                                    <button class='next-btn'>Proceed</button>
                                </a>
                            </div>
                        ";
                    ?>
                        </div>
                    </div>
                </div>

                    

                    
            <?php
        }

        # FOR NON EVALUATED. STEP 2 (PENDING TABLE DEPEND)
        ##
        if(isset($_GET['id']) && isset($_GET['step2'])){
            
            unset($_SESSION['enrollment_id']);

            if(isset($_POST['transferee_pending_choose_section'])
                && isset($_POST['selected_course_id'])){

                $_SESSION['enrollment_id'] = $enrollment_id;

                $firstname = $_POST['firstname'];
                $lastname = $_POST['lastname'];
                $middle_name = $_POST['middle_name'];
                $password = $_POST['password'];
                $program_id = $_POST['program_id'];
                $civil_status = $_POST['civil_status'];
                $nationality = $_POST['nationality'];
                $contact_number = $_POST['contact_number'];
                $age = $_POST['age'];
                $guardian_name = $_POST['guardian_name'];
                $sex = $_POST['sex'];
                $guardian_contact_number = $_POST['guardian_contact_number'];
                $student_status = $_POST['student_status'];
                $pending_enrollees_id = $_POST['pending_enrollees_id'];
                $address = $_POST['address'];
                $lrn = $_POST['lrn'];
                $birthday = $_POST['birthday'];
                $religion = $_POST['religion'];
                $birthplace = $_POST['birthplace'];
                $email = $_POST['email'];

                $selected_course_id_value = $_POST['selected_course_id'];

                $course_id = intval($_POST['selected_course_id']); 

                ##
                $get_available_section = $con->prepare("SELECT 
                        course_id, capacity, course_level, program_section

                        FROM course
                        WHERE course_id=:course_id
                        LIMIT 1");

                $get_available_section->bindValue(":course_id", $course_id);
                $get_available_section->execute();

                # Update enrollment

                // echo $course_id;

                $isRedirectAuto = $course_id == $student_course_id;

                if($isRedirectAuto){
                    # Should not prompt.
                    $_SESSION['auto_redirect'] = true;
                    // echo $_SESSION['enrollment_id'];

                    # reef

                    header("Location: transferee_process_enrollment-2.php?step3=true&id=$pending_enrollees_id&selected_course_id=$student_course_id");
                    // header("Location: transferee_process_enrollment.php?step4=true&id=$pending_enrollees_id&selected_course_id=$student_course_id");
                    // header("Location: transferee_process_enrollment.php?step4=true&id=$pending_enrollees_id&selected_course_id=$student_course_id");

                    // header("Location: transferee_process_enrollment.php?step3=true&st_id=$student_id&selected_course_id=$student_course_id");
                    exit();
                }
                // else if(false){
                if($student_course_id != 0 && $course_id != $student_course_id){

                    # Edit
                    $available_section = $get_available_section->fetch(PDO::FETCH_ASSOC);

                    $course_level = $available_section['course_level'];
                    $program_section = $available_section['program_section'];

                    # Change student course_id & course_level

                    if($get_available_section->rowCount() > 0){

                        if($enrollment_id != null){

                            $isSuccessChangeStudentCourseId = $old->UpdateSHSStudentCourseId(
                            $student_id, $course_id, $course_level);
                        
                            if($isSuccessChangeStudentCourseId){

                                $wasChangingEnrollmentCourseId = $enrollment->UpdateSHSStudentEnrollmentCourseId(
                                    $enrollment_id, $course_id); 

                                if($wasChangingEnrollmentCourseId){
                                    
                                    AdminUser::success("Student is now changed  into $program_section section.",
                                        "transferee_process_enrollment-2.php?step3=true&id=$pending_enrollees_id&selected_course_id=$course_id");
                                    exit();
                                }
                            }else{
                                # Error.
                            }
                        }else{
                            # 
                            
                            AdminUser::success("Student is now changed  into $program_section section.", "transferee_process_enrollment.php?step2=true&id=$pending_enrollees_id");
                            exit();
                        }

                        

                    }

                }

                // else if(false){
                else if($student_course_id == 0){

                    $generateStudentUniqueId = $studentEnroll->GenerateUniqueStudentNumber();
                    $username = strtolower($lastname) . '.' . $generateStudentUniqueId . '@dcbt.ph';

                    $course_level = null;
                
                    $enrollment_form_id = $enrollment->GenerateEnrollmentFormId();

                    // if(false){
                    if($get_available_section->rowCount() > 0){

                        $available_section = $get_available_section->fetch(PDO::FETCH_ASSOC);

                        $capacity = $available_section['capacity'];
                        $course_level = $available_section['course_level'];
                        $program_section = $available_section['program_section'];

                        $enrolledStudents = $studentEnroll->CheckNumberOfStudentInSection($course_id,
                            "First");

                        if($capacity > $enrolledStudents){

                            # Depends on the subject inserted in STEP3
                            $student_statusv2 = NULL;

                            $admission_status = "Transferee";

                            $sql = "INSERT INTO student (firstname, lastname, middle_name, password, civil_status, nationality, contact_number, birthday, age, guardian_name, guardian_contact_number, sex, student_status,
                                        course_id, student_unique_id, course_level, username,
                                        address, lrn, religion, birthplace, email, student_statusv2, admission_status, is_tertiary) 
                                    VALUES (:firstname, :lastname, :middle_name, :password, :civil_status, :nationality, :contact_number, :birthday, :age, :guardian_name, :guardian_contact_number, :sex, :student_status,
                                        :course_id, :student_unique_id, :course_level, :username,
                                        :address, :lrn, :religion, :birthplace, :email, :student_statusv2, :admission_status, :is_tertiary)";

                            $stmt_insert = $con->prepare($sql);

                            if($type == "SHS"){

                                $stmt_insert->bindParam(':firstname', $firstname);
                                $stmt_insert->bindParam(':lastname', $lastname);
                                $stmt_insert->bindParam(':middle_name', $middle_name);
                                $stmt_insert->bindParam(':password', $password);
                                $stmt_insert->bindParam(':civil_status', $civil_status);
                                $stmt_insert->bindParam(':nationality', $nationality);
                                $stmt_insert->bindParam(':contact_number', $contact_number);
                                $stmt_insert->bindParam(':birthday', $birthday);
                                $stmt_insert->bindParam(':age', $age);
                                $stmt_insert->bindParam(':guardian_name', $guardian_name);
                                $stmt_insert->bindParam(':guardian_contact_number', $guardian_contact_number);
                                $stmt_insert->bindParam(':sex', $sex);
                                // 
                                // SHOULD BE NULL FIRST.
                                $stmt_insert->bindParam(':student_status', $student_status);
                                $stmt_insert->bindParam(':course_id', $course_id);
                                $stmt_insert->bindParam(':student_unique_id', $generateStudentUniqueId);
                                $stmt_insert->bindParam(':course_level', $course_level);
                                $stmt_insert->bindParam(':username', $username);
                                $stmt_insert->bindParam(':address', $address);
                                $stmt_insert->bindParam(':lrn', $lrn);
                                $stmt_insert->bindParam(':religion', $religion);
                                $stmt_insert->bindParam(':birthplace', $birthplace);
                                $stmt_insert->bindParam(':email', $email);
                                $stmt_insert->bindParam(':student_statusv2', $student_statusv2);
                                $stmt_insert->bindParam(':admission_status', $admission_status);
                                $stmt_insert->bindValue(':is_tertiary', 0);

                            }else if($type == "Tertiary"){


                                $stmt_insert->bindParam(':firstname', $firstname);
                                $stmt_insert->bindParam(':lastname', $lastname);
                                $stmt_insert->bindParam(':middle_name', $middle_name);
                                $stmt_insert->bindParam(':password', $password);
                                $stmt_insert->bindParam(':civil_status', $civil_status);
                                $stmt_insert->bindParam(':nationality', $nationality);
                                $stmt_insert->bindParam(':contact_number', $contact_number);
                                $stmt_insert->bindParam(':birthday', $birthday);
                                $stmt_insert->bindParam(':age', $age);
                                $stmt_insert->bindParam(':guardian_name', $guardian_name);
                                $stmt_insert->bindParam(':guardian_contact_number', $guardian_contact_number);
                                $stmt_insert->bindParam(':sex', $sex);
                                // 
                                // SHOULD BE NULL FIRST.
                                $stmt_insert->bindParam(':student_status', $student_status);
                                $stmt_insert->bindParam(':course_id', $course_id);
                                $stmt_insert->bindParam(':student_unique_id', $generateStudentUniqueId);
                                $stmt_insert->bindParam(':course_level', $course_level);
                                $stmt_insert->bindParam(':username', $username);
                                $stmt_insert->bindParam(':address', $address);
                                $stmt_insert->bindParam(':lrn', $lrn);
                                $stmt_insert->bindParam(':religion', $religion);
                                $stmt_insert->bindParam(':birthplace', $birthplace);
                                $stmt_insert->bindParam(':email', $email);
                                $stmt_insert->bindParam(':student_statusv2', $student_statusv2);
                                $stmt_insert->bindParam(':admission_status', $admission_status);
                                $stmt_insert->bindValue(':is_tertiary', 1);
                            }

                            if($stmt_insert->execute()){

                                $student_id = $con->lastInsertId();

                                $pending = new Pending($con);

                                # CHECK FIRST IF STUDENT HAS A PARENT.
                                # UPDATE IF YES.
                                $update_parent = $pending->GetParentMatchPendingStudentId($pending_enrollees_id,
                                    $student_id);
                                
                                if($update_parent == true){

                                    $enrollment_status = "tentative";
                                    $is_new_enrollee = 1;
                                    $is_transferee = 1;
                                    $registrar_evaluated = "yes";
                                    // $username = "generate";

                                    $insert_enrollment = $con->prepare("INSERT INTO enrollment
                                        (student_id, course_id, school_year_id, enrollment_status, is_new_enrollee, registrar_evaluated, is_transferee, enrollment_form_id, is_tertiary)
                                        VALUES (:student_id, :course_id, :school_year_id, :enrollment_status, :is_new_enrollee, :registrar_evaluated, :is_transferee, :enrollment_form_id, :is_tertiary)");
                                                    
                                    if($type == "SHS"){

                                        $insert_enrollment->bindValue(':student_id', $student_id);
                                        $insert_enrollment->bindValue(':course_id', $course_id);
                                        // $insert_enrollment->bindValue(':enrollment_date', null);
                                        $insert_enrollment->bindValue(':school_year_id', $current_school_year_id);
                                        $insert_enrollment->bindValue(':enrollment_status', $enrollment_status);
                                        $insert_enrollment->bindValue(':is_new_enrollee', $is_new_enrollee);

                                        # Modified
                                        $insert_enrollment->bindValue(':registrar_evaluated', "no");
                                        $insert_enrollment->bindValue(':is_transferee', $is_transferee);
                                        $insert_enrollment->bindValue(':enrollment_form_id', $enrollment_form_id);
                                        $insert_enrollment->bindValue(':is_tertiary', 0);

                                    }else{

                                        $insert_enrollment->bindValue(':student_id', $student_id);
                                        $insert_enrollment->bindValue(':course_id', $course_id);
                                        // $insert_enrollment->bindValue(':enrollment_date', null);
                                        $insert_enrollment->bindValue(':school_year_id', $current_school_year_id);
                                        $insert_enrollment->bindValue(':enrollment_status', $enrollment_status);
                                        $insert_enrollment->bindValue(':is_new_enrollee', $is_new_enrollee);

                                        # Modified
                                        $insert_enrollment->bindValue(':registrar_evaluated', "no");
                                        $insert_enrollment->bindValue(':is_transferee', $is_transferee);
                                        $insert_enrollment->bindValue(':enrollment_form_id', $enrollment_form_id);
                                        $insert_enrollment->bindValue(':is_tertiary', 1);
                                    }

                                    if($insert_enrollment->execute()){

                                        // Check enrollment course_id number with course_id capacity
                                        if($insert_enrollment->rowCount() > 0){

                                            $generated_enrollment_id = $con->lastInsertId();

                                            $_SESSION['enrollment_id'] = $generated_enrollment_id;

                                            $section_url = "transferee_process_enrollment-2.php?step3=true&id=$pending_enrollees_id&selected_course_id=$selected_course_id_value";

                                            // $section_url = "transferee_process_enrollment.php?step3=true&st_id=$student_id&selected_course_id=$selected_course_id_value";

                                            AdminUser::success("Student is now placed in $program_section section.",
                                                $section_url);
                                            exit();
                                        }
                                    }




                                }else{
                                    AdminUser::error("Update parent failed", "");
                                }


                            }
                        }else{
                            echo "Capacity is full";
                            return;
                        }
    
                    }
                }
            }

            ?>
            <div class="row col-md-12">

                <div class="content">

                    <div class="back-menu">
                            <button type="button" class="admission-btn" onclick="admission()">
                            <i class="bi bi-arrow-left-circle"></i> Admission
                            </button>
                    </div>

                    <div class="head">
                        <div class="header-content">
                            <h3>Enrollment form</h3>

                            <div class="dropdown">
                                <div class="dropdown-toggle" onclick="toggleDropdown()">
                                    <i class="bi bi-three-dots-vertical"></i>
                                </div>
                                <ul class="dropdown-menu">
                                    <li>
                                        <button type="button" class="action" onclick="edit_calendar()">
                                            <i class="bi bi-file-x"></i>Delete form
                                        </button>
                                    </li>
                                </ul>
                            </div>
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
                                    <td>Transferee</td>
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
                    <div class="progress-bar">
                        <div class="steps">
                            <div class="step active">Check form details</div>
                            <div class="step active">Find section</div>
                            <div class="step">Subject confirmation</div>
                        </div>
                    </div>

                <div class="step-content">
                    <div class="content-box">
                        <h3>Enrollment details</h3>

                        <?php
                            if($type =="SHS"){
                                ?>
                                <div class="enrollment-details">
                                    <form action="" class="info-box">
                                        <div class="info-1">
                                            <label for="sy"> S.Y. </label>
                                            <span id="currentSY"><?php echo $current_school_year_term; ?></span>
                                            <label for="track"> Track </label>
                                            <div class="selection-box-1">
                                                <select name="track" id="track">
                                                    <?php
                                                        $SHS_DEPARTMENT = 4;
                                                        $track_sql = $con->prepare("SELECT program_id, track, acronym
                                                            FROM program WHERE department_id =:department_id
                                                            GROUP BY track"
                                                        );

                                                        $track_sql->bindValue("department_id", $SHS_DEPARTMENT);
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
                                            <label for="strand">Strand</label>
                                            <div class="selection-box-1">
                                                <select onchange="chooseStrand(this, <?php echo $pending_enrollees_id; ?>)" name="strand" id="strand" >
                                                    <?php
                                                        $SHS_DEPARTMENT = 4;
                                                        $track_sql = $con->prepare("SELECT program_id, track, acronym
                                                            FROM program WHERE department_id =:department_id
                                                            GROUP BY acronym"
                                                        );

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
                                        </div>
                                        <div class="info-2">
                                            <label for="year">Year</label>
                                            <div class="selection-box-1">
                                                <select name="year" id="year">
                                                    <option value="11" <?php if($student_course_level == 11) echo 'selected'; ?>>Grade 11</option>
                                                    <option value="12" <?php if($student_course_level == 12) echo 'selected'; ?>>Grade 12</option>
                                                </select>
                                            </div>
                                            <label for="semester">Semester</label>
                                            <div class="selection-box-1">
                                                <select name="semester" id="semester">
                                                <option value="1">1</option>
                                                </select>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            <?php
                            }
                            else if($type == "Tertiary"){
                                ?>
                                <div class="enrollment-details">
                                    <form action="" class="info-box">
                                        <div class="info-1">
                                            <label for="sy"> S.Y. </label>
                                            <span id="currentSY"><?php echo $current_school_year_term; ?></span>
                                            <label for="track"> Track </label>
                                            <div class="selection-box-1">
                                                <select name="track" id="track">
                                                    <?php
                                                        $SHS_DEPARTMENT = 4;
                                                        $track_sql = $con->prepare("SELECT program_id, track, acronym
                                                            FROM program WHERE department_id =:department_id
                                                            GROUP BY track"
                                                        );

                                                        $track_sql->bindValue("department_id", $SHS_DEPARTMENT);
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
                                            <label for="strand">Strand</label>
                                            <div class="selection-box-1">
                                                <select onchange="chooseStrand(this, <?php echo $pending_enrollees_id; ?>)" name="strand" id="strand" >
                                                    <?php
                                                        $SHS_DEPARTMENT = 4;
                                                        $track_sql = $con->prepare("SELECT program_id, track, acronym
                                                            FROM program WHERE department_id =:department_id
                                                            GROUP BY acronym"
                                                        );

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
                                        </div>
                                        <div class="info-2">
                                            <label for="year">Year</label>
                                            <div class="selection-box-1">
                                                <select name="year" id="year">
                                                    <option value="11" <?php if($student_course_level == 11) echo 'selected'; ?>>Grade 11</option>
                                                    <option value="12" <?php if($student_course_level == 12) echo 'selected'; ?>>Grade 12</option>
                                                </select>
                                            </div>
                                            <label for="semester">Semester</label>
                                            <div class="selection-box-1">
                                                <select name="semester" id="semester">
                                                <option value="1">1</option>
                                                </select>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            <?php
                            }
                        ?>
                    </div>

                </div>

                <!-- Enrollment Form -->
                <div style="display: none;" class="container">
                    <h3 class="text-center text-primary">Enrollment Form</h3>
                    <div class="row col-md-12">

                        <?php
                            if($enrollment_id != null ){
                                ?>
                                    <div class="mb-4 col-md-3">
                                        <label for="">* Form Id</label>
                                        <input readonly style="width: 100%;" type="text" 
                                            value='<?php echo $enrollment_form_id;?>' class="form-control">
                                    </div>
                                <?php
                            }else if($enrollment_id == null){
                                ?>
                                    <div class="mb-4 col-md-3">
                                        <label for="">Form Id</label>
                                        <input readonly style="width: 100%;" type="text" 
                                            value='<?php echo $enrollment_form_id;?>' class="form-control">
                                    </div>
                                <?php
                            }
                        
                        ?>
                    
                        <div class="mb-4 col-md-3">
                            <label for="">Name</label>
                            <input readonly style="width: 100%;" type="text" 
                                value='<?php echo $student_fullname?>' class="form-control">
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

                <!-- Available Section -->

                <div class="content-box">
                    <h3>Available sections</h3>

                    <form method="POST">
                       <table>
                        <tr>
                            <th>Section ID</th>
                            <th>Section name</th>
                            <th>Capacity</th>
                            <th>Semester</th>
                            <th>Term</th>
                            <th>Choose</th>
                            <th>Action</th>
                        </tr>
                        </table> 
                        <table>
                            <?php
                                $selected_section = "";
                                $course_level = 11;
                                $active = "yes";

                                # Only Available now.

                                $sql = $con->prepare("SELECT * FROM course
                                WHERE program_id=:program_id
                                AND active=:active
                                -- AND course_level=:course_level
                                ");
                                $sql->bindValue(":program_id", $program_id);
                                $sql->bindValue(":active", $active);
                                // $sql->bindValue(":course_level", $course_level);

                                $sql->execute();

                                if($sql->rowCount() > 0){
                                    while($get_course = $sql->fetch(PDO::FETCH_ASSOC)){
                                        $course_id = $get_course['course_id'];
                                        $program_section = $get_course['program_section'];
                                        $school_year_term = $get_course['school_year_term'];
                                        $capacity = $get_course['capacity'];

                                        $section = new Section($con, $course_id);

                                        $selected_section= $program_section;

                                        $totalStudent = $section->GetTotalNumberOfStudentInSection(
                                            $course_id, $current_school_year_id);

                                        $removeSection = "removeSection($course_id, \"$program_section\")";
                                        // $student_course_id = 5;
                                                
                                        $isClosed = $totalStudent >= $capacity;

                                        $isCloseddisabled = "<input name='selected_course_id' 
                                            class='radio' value='$course_id' 
                                            type='radio' " . ($course_id == $student_course_id ? "checked" : "") . " " . ($isClosed ? "disabled" : "") . ">";
                                        echo "
                                            <tr>
                                                <td>$course_id</td>
                                                <td>$program_section</td>
                                                <td>$totalStudent / $capacity</td>
                                                <td>$current_school_year_period</td>
                                                <td>$school_year_term</td>
                                                <td>$isCloseddisabled</td>
                                                <td>
                                                    <i onclick='$removeSection' style='cursor: pointer; color: orange; " . ($totalStudent != 0 ? "display: none;" : "") . "' class='fas fa-times'></i>
                                                </td>
                                            </tr>";
                                    }
                                }
                            ?>
                        </table>

                        <input type="hidden" id="firstname" name="firstname" value="<?php echo $firstname; ?>">
                        <input type="hidden" id="lastname" name="lastname" value="<?php echo $lastname; ?>">
                        <input type="hidden" id="middle_name" name="middle_name" value="<?php echo $middle_name; ?>">
                        <input type="hidden" id="password" name="password" value="<?php echo $password; ?>">
                        <input type="hidden" id="program_id" name="program_id" value="<?php echo $program_id; ?>">
                        <input type="hidden" id="civil_status" name="civil_status" value="<?php echo $civil_status; ?>">
                        <input type="hidden" id="nationality" name="nationality" value="<?php echo $nationality; ?>">
                        <input type="hidden" id="contact_number" name="contact_number" value="<?php echo $contact_number; ?>">
                        <input type="hidden" id="age" name="age" value="<?php echo $age; ?>">
                        <input type="hidden" id="guardian_name" name="guardian_name" value="<?php echo $guardian_name; ?>">
                        <input type="hidden" id="sex" name="sex" value="<?php echo $sex; ?>">
                        <input type="hidden" id="guardian_contact_number" name="guardian_contact_number" value="<?php echo $guardian_contact_number; ?>">
                        <input type="hidden" id="student_status" name="student_status" value="<?php echo $student_status; ?>">
                        <!-- <input type="hidden" id="program_section" name="program_section" value="<?php echo $selected_section; ?>"> -->
                        <input type="hidden" id="pending_enrollees_id" name="pending_enrollees_id" value="<?php echo $pending_enrollees_id; ?>">
                        <input type="hidden" id="address" name="address" value="<?php echo $address; ?>">
                        <input type="hidden" id="lrn" name="lrn" value="<?php echo $lrn; ?>">
                        <input type="hidden" id="birthday" name="birthday" value="<?php echo $birthday; ?>">
                        <input type="hidden" id="religion" name="religion" value="<?php echo $religion; ?>">
                        <input type="hidden" id="birthplace" name="birthplace" value="<?php echo $birthplace; ?>">
                        <input type="hidden" id="email" name="email" value="<?php echo $email; ?>">

                        <div class="btn-placing">
                            <a href="transferee_process_enrollment-2.php?step1=true&id=<?php echo $pending_enrollees_id; ?>">
                                <button class="prev-btn">Return</button>
                            </a>
                            <button type="submit" name="transferee_pending_choose_section" class="next-btn">Proceed</button>
                        </div>
                    </form>
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
                                            $('#transferee_available_section').load(
                                                location.href + ' #transferee_available_section'
                                            );
                                        }


                                    }
                                });
                            }

                        });
                    }

                // $('#standing_level').on('change', function() {

                //     var standing_level = parseInt($(this).val());
                //     var program_id = parseInt($("#program_id").val());
                //     var student_course_id = parseInt($("#student_course_id").val());

                //     // Add a delay of 500ms
                //     setTimeout(function() {
                //                                 $.ajax({
                //         url: '../ajax/transferee_process/available_section.php',
                //         type: 'POST',
                //         data: {
                //             standing_level,program_id,student_course_id
                //         },
                //         dataType: 'json',
                //         success: function(response) {

                //             // console.log(response);

                //             if(response.length > 0){

                //                 var html = `
                //                 <table id="res" class="table table-striped table-bordered table-hover "  style="font-size:13px" cellspacing="0"  > 
                //                     <thead>
                //                         <tr class='text-center'> 
                //                             <th rowspan='2'>Section Id</th>
                //                             <th rowspan='2'>Section Name</th>
                //                             <th rowspan='2'>Capacity</th>
                //                             <th rowspan='2'>Semester</th>
                //                             <th rowspan='2'>Term</th>
                //                             <th rowspan='2'>Choose</th>
                //                             <th rowspan='2'>Action</th>
                //                         </tr>	
                //                     </thead> 	
                //                 `;

                //             $.each(response, function(index, value) {

                //                 var course_id = value.course_id;
                //                 var program_section = value.program_section;
                //                 var capacity = value.capacity;
                //                 var program_section = value.program_section;
                //                 var school_year_term = value.school_year_term;
                //                 var totalStudent = value.totalStudent;
                //                 var semester = value.semester;


                //                 var isClosed = totalStudent >= capacity;

                //                 // $removeSection = "removeSection($course_id, \"$program_section\")";

                //                 var removeSection = `removeSection(${course_id}, "${program_section}")`;

                //                 var isCloseddisabled = `<input name='selected_course_id' 
                //                                         class='radio' value='${course_id}' 
                //                                         type='radio' ${course_id == student_course_id ? 'checked' : ''} ${isClosed ? 'disabled' : ''}>`;

                //                 // var onClickFunction = '$removeSection';
                //                 var displayStyle = totalStudent !== 0 ? 'display: none;' : '';

                //                 var remove = `
                //                     <i onclick='${removeSection}' style='cursor: pointer; color: orange; ${displayStyle}' class='fas fa-times'></i>
                //                 `;

                //                 html += `
                //                     <body>
                //                         <tr class='text-center'>
                //                             <td>${course_id}</td>
                //                             <td>${program_section}</td>
                //                             <td>${totalStudent} / ${capacity}</td>
                //                             <td>${semester}</td>
                //                             <td>${school_year_term}</td>
                //                             <td>${isCloseddisabled}</td>
                //                             <td>${remove}</td>

                //                         </tr>
                //                     `;
               
                //             });

                //             html += `
                //                 </table>
                //                 `;

                //             html += `
                //                 <button type="submit" name="transferee_pending_choose_section"
                //                     class="btn btn-primary">
                //                     Proceed
                //                 </button>
                //             `;
                //             $('#availableTransfereeSectionTablev1').html(html);

                                
                //             }else{
                //                 var nothing = `
                //                     <div class="alert alert-warning text-center" role="alert">
                //                         No data found
                //                     </div>
                //                 `;
                //                 $('#availableTransfereeSectionTablev1').html(nothing);
                //             }

                            

                //             }
                //     });
                //     }, 150);



                // });

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

                                    $('#availableTransfereeSectionTablev1').load(
                                        location.href + ' #availableTransfereeSectionTablev1'
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


        # FOR EVALUATED (ONGOING) TRANSFEREE. STEP 2
        if(isset($_GET['st_id']) && isset($_GET['step2'])){

            $student_id = $_GET['st_id'];



            $student_get_step_2 = $con->prepare("SELECT *
                
                FROM student
                WHERE student_id=:student_id
                
                ");
            $student_get_step_2->bindValue(":student_id", $student_id);
            $student_get_step_2->execute();

            $student_course_id = $studentEnroll->GetStudentCourseIdById($student_id);
            $student_username = $studentEnroll->GetStudentUsername($student_id);

            $student = new Student($con, $student_username);

            $student_unique_id = $student->GetStudentUniqueId();

            $admission_status = $student->GetStudentAdmissionStatus();

            $checkEnrollmentEnrolled = $enrollment->CheckEnrollmentEnrolled($student_id,
                    $student_course_id, $current_school_year_id);

            $cashierEvaluated = $enrollment->CheckEnrollmentCashierApproved($student_id,
                    $student_course_id, $current_school_year_id);

            $payment_status = "";

            if($checkEnrollmentEnrolled == true && $cashierEvaluated == true){
                $payment_status = "Enrolled";

            }else if($checkEnrollmentEnrolled == false && $cashierEvaluated == true){
                $payment_status = "Approved";
            }else{
                $payment_status = "Waiting";
            }

            $getEnrollmentNonEnrolledDate = $enrollment
                ->GetEnrollmentNonEnrolledDate($student_id, $student_course_id,
                    $current_school_year_id);

            $getEnrollmentEnrolledDate = $enrollment
                ->GetEnrollmentEnrolledDate($student_id, $student_course_id,
                    $current_school_year_id);

            $proccess_date = null;

            // echo $getEnrollmentNonEnrolledDate;

            if($checkEnrollmentEnrolled == true){
                $proccess_date = $getEnrollmentEnrolledDate;
            }else{
                $proccess_date = $getEnrollmentNonEnrolledDate;
            }

            if($student_get_step_2->rowCount() > 0){

                # BASED.
                $student_row = $student_get_step_2->fetch(PDO::FETCH_ASSOC);

                $student_course_levelv2 = $student_row['course_level'];

                $student_course_idv2 = $student_row['course_id'];

                $student_firstnamev2 = $student_row['firstname'];
                $student_lastnamev2 = $student_row['lastname'];
                $student_fullnamev2 = $student_firstnamev2 . " " . $student_lastnamev2;
                
                $enrollment_id = $enrollment->GetEnrollmentId($student_id,
                    $student_course_idv2, $current_school_year_id);

                $enrollment_form_id = $enrollment->GetEnrollmentFormId($student_id,
                    $student_course_idv2, $current_school_year_id);

                $date_creationv2 = $enrollment->GetEnrollmenDate($student_id,
                    $student_course_idv2, $current_school_year_id);

                
                $student_program_id = $studentEnroll->GetStudentProgramId($student_course_idv2);

                $program_acronymv2 = $enrollment->GetEnrollmentProgram($student_program_id);
            }


            unset($_SESSION['enrollment_id']);

            if(isset($_POST['transferee_pending_choose_section_os'])
                && isset($_POST['selected_course_id'])){

                $_SESSION['enrollment_id'] = $enrollment_id;

                $firstname = $_POST['firstname'];
                $lastname = $_POST['lastname'];
                $middle_name = $_POST['middle_name'];
                $password = $_POST['password'];
                $program_id = $_POST['program_id'];
                $civil_status = $_POST['civil_status'];
                $nationality = $_POST['nationality'];
                $contact_number = $_POST['contact_number'];
                $age = $_POST['age'];
                $guardian_name = $_POST['guardian_name'];
                $sex = $_POST['sex'];
                $guardian_contact_number = $_POST['guardian_contact_number'];
                $student_status = $_POST['student_status'];
                $pending_enrollees_id = $_POST['pending_enrollees_id'];
                $address = $_POST['address'];
                $lrn = $_POST['lrn'];
                $birthday = $_POST['birthday'];
                $religion = $_POST['religion'];
                $birthplace = $_POST['birthplace'];
                $email = $_POST['email'];

                $selected_course_id_value = $_POST['selected_course_id'];


                $course_id = intval($_POST['selected_course_id']); 

                $section = new Section($con, $course_id);
                $doesSectionFull = $section->CheckSectionIsFull($course_id);

                if($doesSectionFull == true){
                    AdminUser::error("Selecrted Section is Full", "");
                    // return;
                }
                // echo $course_id;

                ##
                $get_available_section = $con->prepare("SELECT 
                        course_id, capacity, course_level, program_section

                        FROM course
                        WHERE course_id=:course_id
                        LIMIT 1");
                $get_available_section->bindValue(":course_id", $course_id);
                $get_available_section->execute();

                # Update enrollment

                // echo $course_id;

                $isRedirectAuto = $course_id == $student_course_idv2;

                if($doesSectionFull == false){

                    // if(false){
                    if($isRedirectAuto){
                        # Should not prompt.
                        $_SESSION['auto_redirect'] = true;
                        // echo $_SESSION['enrollment_id'];

                        # reef
                        // header("Location: transferee_process_enrollment-2.php?step3=true&id=$pending_enrollees_id&selected_course_id=$student_course_id");

                        // header("Location: transferee_process_enrollment-2.php?step3=true&st_id=$student_id&selected_course_id=$student_course_idv2");

                        header("Location: transferee_process_enrollment-2.php?step3=true&st_id=$student_id&selected_course_id=$student_course_idv2");
                        exit();
                    }
                    
                    // else if(false){
                    if($student_course_idv2 != 0 && $course_id != $student_course_idv2){

                        # Edit
                        $available_section = $get_available_section->fetch(PDO::FETCH_ASSOC);

                        $course_level = $available_section['course_level'];
                        $program_section = $available_section['program_section'];

                        # Change student course_id & course_level

                        if($get_available_section->rowCount() > 0){

                            if($enrollment_id != null){

                                $isSuccessChangeStudentCourseId = $old->UpdateSHSStudentCourseId(
                                $student_id, $course_id, $course_level);
                            
                                if($isSuccessChangeStudentCourseId){

                                    $wasChangingEnrollmentCourseId = $enrollment->UpdateSHSStudentEnrollmentCourseId(
                                        $enrollment_id, $course_id); 

                                    if($wasChangingEnrollmentCourseId){
                                        
                                        AdminUser::success("Student is now changed  into $program_section section.",
                                            "transferee_process_enrollment-2.php?step3=true&st_id=$student_id&selected_course_id=$course_id");
                                        exit();
                                    }
                                }else{
                                    # Error.
                                }
                            }else{
                                # 
                                
                                AdminUser::success("Student is now changed  into $program_section section.", "transferee_process_enrollment-2.php?step2=true&id=$pending_enrollees_id");
                                exit();
                            }

                            

                        }

                    }
                    // else if(false){
                    else if($student_course_idv2 == 0){

                        // echo "add";

                        $generateStudentUniqueId = $studentEnroll->GenerateUniqueStudentNumber();
                        $username = strtolower($lastname) . '.' . $generateStudentUniqueId . '@dcbt.ph';

                        $course_level = null;
                        // $get_available_section = $con->prepare("SELECT 
                        //     course_id, capacity, course_level, program_section

                        //     FROM course
                        //     WHERE course_id=:course_id
                        //     LIMIT 1");
                        // $get_available_section->bindValue(":course_id", $course_id);
                        // $get_available_section->execute();

                        $enrollment_form_id = $enrollment->GenerateEnrollmentFormId();

                        // if(false){
                        if($get_available_section->rowCount() > 0){

                            $available_section = $get_available_section->fetch(PDO::FETCH_ASSOC);

                            $capacity = $available_section['capacity'];
                            $course_level = $available_section['course_level'];
                            $program_section = $available_section['program_section'];

                            $enrolledStudents = $studentEnroll->CheckNumberOfStudentInSection($course_id,
                                "First");

                            if($capacity > $enrolledStudents && $doesSectionFull == false){

                                $sql = "INSERT INTO student (firstname, lastname, middle_name, password, civil_status, nationality, contact_number, birthday, age, guardian_name, guardian_contact_number, sex, student_status,
                                            course_id, student_unique_id, course_level, username,
                                            address, lrn, religion, birthplace, email) 
                                        VALUES (:firstname, :lastname, :middle_name, :password, :civil_status, :nationality, :contact_number, :birthday, :age, :guardian_name, :guardian_contact_number, :sex, :student_status,
                                            :course_id, :student_unique_id, :course_level, :username,
                                            :address, :lrn, :religion, :birthplace, :email)";

                                $stmt_insert = $con->prepare($sql);

                                $stmt_insert->bindParam(':firstname', $firstname);
                                $stmt_insert->bindParam(':lastname', $lastname);
                                $stmt_insert->bindParam(':middle_name', $middle_name);
                                $stmt_insert->bindParam(':password', $password);
                                $stmt_insert->bindParam(':civil_status', $civil_status);
                                $stmt_insert->bindParam(':nationality', $nationality);
                                $stmt_insert->bindParam(':contact_number', $contact_number);
                                $stmt_insert->bindParam(':birthday', $birthday);
                                $stmt_insert->bindParam(':age', $age);
                                $stmt_insert->bindParam(':guardian_name', $guardian_name);
                                $stmt_insert->bindParam(':guardian_contact_number', $guardian_contact_number);
                                $stmt_insert->bindParam(':sex', $sex);
                                $stmt_insert->bindParam(':student_status', $student_status);
                                $stmt_insert->bindParam(':course_id', $course_id);
                                $stmt_insert->bindParam(':student_unique_id', $generateStudentUniqueId);
                                $stmt_insert->bindParam(':course_level', $course_level);
                                $stmt_insert->bindParam(':username', $username);
                                $stmt_insert->bindParam(':address', $address);
                                $stmt_insert->bindParam(':lrn', $lrn);
                                $stmt_insert->bindParam(':religion', $religion);
                                $stmt_insert->bindParam(':birthplace', $birthplace);
                                $stmt_insert->bindParam(':email', $email);
                                
                                if($stmt_insert->execute()){

                                    // remove the existing pending table
                                    // Add to the enrollment with regostrar evaluated
                                    // tentative and aligned to the ccourse id

                                    $student_id = $con->lastInsertId();

                                    $enrollment_status = "tentative";
                                    $is_new_enrollee = 1;
                                    $is_transferee = 1;
                                    $registrar_evaluated = "yes";
                                    // $username = "generate";

                                    $insert_enrollment = $con->prepare("INSERT INTO enrollment
                                        (student_id, course_id, school_year_id, enrollment_status, is_new_enrollee, registrar_evaluated, is_transferee, enrollment_form_id)
                                        VALUES (:student_id, :course_id, :school_year_id, :enrollment_status, :is_new_enrollee, :registrar_evaluated, :is_transferee, :enrollment_form_id)");
                                                    
                                    $insert_enrollment->bindValue(':student_id', $student_id);
                                    $insert_enrollment->bindValue(':course_id', $course_id);
                                    $insert_enrollment->bindValue(':school_year_id', $current_school_year_id);
                                    $insert_enrollment->bindValue(':enrollment_status', $enrollment_status);
                                    $insert_enrollment->bindValue(':is_new_enrollee', $is_new_enrollee);

                                    # Modified
                                    $insert_enrollment->bindValue(':registrar_evaluated', "no");
                                    $insert_enrollment->bindValue(':is_transferee', $is_transferee);
                                    $insert_enrollment->bindValue(':enrollment_form_id', $enrollment_form_id);

                                    if($insert_enrollment->execute()){

                                        // Check enrollment course_id number with course_id capacity
                                        if($insert_enrollment->rowCount() > 0){

                                            $generated_enrollment_id = $con->lastInsertId();

                                            $_SESSION['enrollment_id'] = $generated_enrollment_id;

                                            // $section_url = "transferee_process_enrollment.php?step3=true&id=$pending_enrollees_id&selected_course_id=$selected_course_id_value";

                                            $section_url = "transferee_process_enrollment-2.php?step3=true&st_id=$student_id&selected_course_id=$selected_course_id_value";

                                            AdminUser::success("Student is now placed in $program_section section.", $section_url);
                                            exit();
                                        }
                                    }
                                }
                            }else{
                                echo "Capacity is full";
                                return;
                            }
        
                        }
                    }
                }
                
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
                                <td><?php echo $admission_status;?></td>
                                <td><?php echo $student_unique_id; ?></td>
                                <td>Evaluation</td>

                                <td><?php
                                    $date = new DateTime($proccess_date);
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

                <!-- Available Section -->
                <div id="transferee_available_section" class="container mt-4 mb-2">
                    <div class="container">
                    <h3 class="text-center text-success">Available Section For Transferee</h3>

                    <a href="../section/create.php">
                        <button class="mb-2 btn btn-sm btn-success" onclick="<?php 
                            $_SESSION['pending_enrollees_id'] = $pending_enrollees_id;
                            $_SESSION['process_enrollment'] = 'transferee';
                            ?>">
                            Section Maintenance
                        </button>
                    </a>

                    </div>

                    <form method="POST">
                        <table id="availableTransfereeSectionTable" class="table table-striped table-bordered table-hover "  style="font-size:13px" cellspacing="0"  > 
                            <thead>
                                <tr class="text-center"> 
                                    <th rowspan="2">Section Id</th>
                                    <th rowspan="2">Section Name</th>
                                    <th rowspan="2">Capacity</th>
                                    <th rowspan="2">Semester</th>
                                    <th rowspan="2">Term</th>
                                    <th rowspan="2">Choose</th>
                                    <th rowspan="2">Action</th>
                                </tr>	
                            </thead> 	
                            <tbody>
                                <?php

                                    $selected_section = "";
                                    $course_level = 11;
                                    $active = "yes";

                                    # Only Available now.

                                    $sql = $con->prepare("SELECT * FROM course
                                    WHERE program_id=:program_id
                                    AND active=:active
                                    -- AND course_level=:course_level
                                    ");
                                    $sql->bindValue(":program_id", $student_program_id);
                                    $sql->bindValue(":active", $active);
                                    // $sql->bindValue(":course_level", $course_level);

                                    $sql->execute();
                                
                                    if($sql->rowCount() > 0){

                                        while($get_course = $sql->fetch(PDO::FETCH_ASSOC)){

                                            $course_id = $get_course['course_id'];
                                            $program_section = $get_course['program_section'];
                                            $school_year_term = $get_course['school_year_term'];
                                            $capacity = $get_course['capacity'];
                                            $section = new Section($con, $course_id);

                                            $selected_section= $program_section;

                                            $totalStudent = $section->GetTotalNumberOfStudentInSection(
                                                $course_id, $current_school_year_id);

                                            $removeSection = "removeSection($course_id, \"$program_section\")";


                                            $isClosed = $totalStudent >= $capacity;
 
                                            $isCloseddisabled = "<input name='selected_course_id' class='radio' value='$course_id' type='radio' " . ($course_id == $student_course_idv2 ? "checked" : "") . " " . ($isClosed ? "disabled" : "") . ">";      
                                            
                                            // $student_course_id = 5;
                                            echo "
                                                <tr class='text-center'>
                                                    <td>$course_id</td>
                                                    <td>$program_section</td>
                                                    <td>$totalStudent / $capacity</td>
                                                    <td>$current_school_year_period</td>
                                                    <td>$school_year_term</td>
                                                    <td>
                                                    <input name='selected_course_id' class='radio'
                                                            value='$course_id' 
                                                            type='radio' " . ($course_id == $student_course_idv2 ? "checked" : "") . ">
                                                    </td>
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
                        <input type="hidden" id="firstname" name="firstname" value="<?php echo $firstname; ?>">
                        <input type="hidden" id="lastname" name="lastname" value="<?php echo $lastname; ?>">
                        <input type="hidden" id="middle_name" name="middle_name" value="<?php echo $middle_name; ?>">
                        <input type="hidden" id="password" name="password" value="<?php echo $password; ?>">
                        <input type="hidden" id="program_id" name="program_id" value="<?php echo $program_id; ?>">
                        <input type="hidden" id="civil_status" name="civil_status" value="<?php echo $civil_status; ?>">
                        <input type="hidden" id="nationality" name="nationality" value="<?php echo $nationality; ?>">
                        <input type="hidden" id="contact_number" name="contact_number" value="<?php echo $contact_number; ?>">
                        <input type="hidden" id="age" name="age" value="<?php echo $age; ?>">
                        <input type="hidden" id="guardian_name" name="guardian_name" value="<?php echo $guardian_name; ?>">
                        <input type="hidden" id="sex" name="sex" value="<?php echo $sex; ?>">
                        <input type="hidden" id="guardian_contact_number" name="guardian_contact_number" value="<?php echo $guardian_contact_number; ?>">
                        <input type="hidden" id="student_status" name="student_status" value="<?php echo $student_status; ?>">
                        <!-- <input type="hidden" id="program_section" name="program_section" value="<?php echo $selected_section; ?>"> -->
                        <input type="hidden" id="pending_enrollees_id" name="pending_enrollees_id" value="<?php echo $pending_enrollees_id; ?>">
                        <input type="hidden" id="address" name="address" value="<?php echo $address; ?>">
                        <input type="hidden" id="lrn" name="lrn" value="<?php echo $lrn; ?>">
                        <input type="hidden" id="birthday" name="birthday" value="<?php echo $birthday; ?>">
                        <input type="hidden" id="religion" name="religion" value="<?php echo $religion; ?>">
                        <input type="hidden" id="birthplace" name="birthplace" value="<?php echo $birthplace; ?>">
                        <input type="hidden" id="email" name="email" value="<?php echo $email; ?>">

                        <!-- !!! -->

                        <button type="submit"name="transferee_pending_choose_section_os"class="btn btn-primary">
                            Proceed
                        </button>

                    </form>
                </div> 
            </div>
            <script>
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


        # FOR EVALUATED (ONGOING) TRANSFEREE. STEP 3 TRUE
        if(isset($_GET['st_id']) 
            && isset($_GET['step3']) && $_GET['step3'] == "true"
             && isset($_GET['selected_course_id'])
            ){

            // echo "qweqwe";
            # Should not be dependent on pending table


            # PFP
            $student_id = $_GET['st_id'];
            $selected_course_id = $_GET['selected_course_id'];

            $student_get = $con->prepare("SELECT *
                
                FROM student
                WHERE student_id=:student_id
                
                ");
            $student_get->bindValue(":student_id", $student_id);
            $student_get->execute();

            if($student_get->rowCount() > 0){

                $student_row = $student_get->fetch(PDO::FETCH_ASSOC);

                $student_course_levelv2 = $student_row['course_level'];
                $student_course_idv2 = $student_row['course_id'];


                $enrollment_id = $enrollment->GetEnrollmentId($student_id,
                    $student_course_idv2, $current_school_year_id);

                $enrollment_form_id = $enrollment->GetEnrollmentFormId($student_id,
                    $student_course_idv2, $current_school_year_id);



            }

            if(isset($_SESSION['selected_course_id'])){
                unset($_SESSION['selected_course_id']);
            }
            
           
            $section = new Section($con, $selected_course_id);
            $subject = new Subject($con, $registrarLoggedIn, null);

            $section_name = $section->GetSectionName();

            $enroll = new StudentEnroll($con);

            $student_username = $enroll->GetStudentUsername($student_id);

            $student = new Student($con, $student_username);
            $enrollment = new Enrollment($con, $enroll);

            $student_unique_id = $student->GetStudentUniqueId();


            $enrollment_form_id = $enrollment->GetEnrollmentFormId($student_id,
                $selected_course_id, $current_school_year_id);

            $admission_status = $student->GetStudentAdmissionStatus();
                    $student_status = $student->GetStudentStatusv2();

            $checkEnrollmentEnrolled = $enrollment->CheckEnrollmentEnrolled($student_id,
                    $selected_course_id, $current_school_year_id);

            $cashierEvaluated = $enrollment->CheckEnrollmentCashierApproved($student_id,
                    $selected_course_id, $current_school_year_id);

            $payment_status = "";

            if($checkEnrollmentEnrolled == true && $cashierEvaluated == true){
                $payment_status = "Enrolled";

            }else if($checkEnrollmentEnrolled == false && $cashierEvaluated == true){
                $payment_status = "Approved";
            }else{
                $payment_status = "Waiting";
            }

            $getEnrollmentNonEnrolledDate = $enrollment
                ->GetEnrollmentNonEnrolledDate($student_id, $selected_course_id, $current_school_year_id);

            $getEnrollmentEnrolledDate = $enrollment
                ->GetEnrollmentEnrolledDate($student_id, $selected_course_id, $current_school_year_id);

            $proccess_date = null;

            if($checkEnrollmentEnrolled == true){
                $proccess_date = $getEnrollmentEnrolledDate;
            }else{
                $proccess_date = $getEnrollmentNonEnrolledDate;
            }


            // echo $section_name . " step 3";
            
            if(isset($_POST['selected_btn_student']) && $_POST['normal_selected_subject']){

                $subjects = $_POST['normal_selected_subject'];

                // echo $subjects;

                // $course_level = $_POST['course_level'];

                // echo $course_level;
                $isInserted = false;

                foreach ($subjects as $key => $value) {
                    # code...

                    $subject_id = $value;

                    $get_course_level = $subject->GetSubjectCourseLevel($subject_id);

                    # Check if inserted.

                    $getSubjectProgramId = $subject->GetSubjectProgramId($subject_id);

                    $check = $studentSubject->CheckStudentSubject($student_id, $subject_id,
                        $enrollment_id, $current_school_year_id);
                    
                    if($check == false){
                        $wasInserted = $studentSubject->InsertStudentSubject($student_id, $subject_id,
                            $enrollment_id, $get_course_level, $getSubjectProgramId, $current_school_year_id, "no", false);
                            
                        if($wasInserted == true){
                            $isInserted = true;
                        }
                    }

                }

                // if(false){
                if($isInserted == true){

                    // echo "success";
                    
                    // AdminUser::remove("Successfully added subjects",
                    //     "view_student_transferee_enrollment_review.php?inserted=true&id=$student_id&p_id=$pending_enrollees_id&e_id=$enrollment_id");

                    // $url = "../enrollees/view_student_transferee_enrollment_review.php?inserted=true&id=$student_id&p_id=$pending_enrollees_id&e_id=$enrollment_id";
                    $url = "../enrollees/view_student_transferee_enrollment_review_student.php?inserted=true&id=$student_id&e_id=$enrollment_id";

                    // AdminUser::success("Successfully added subjects",
                    //     "transferee_process_enrollment.php?step3=true&id=$pending_enrollees_id&selected_course_id=$selected_course_id");

                    AdminUser::success("Successfully added subjects",
                        "$url");

                    $_SESSION['selected_course_id'] = $selected_course_id;
                    exit();
                }
                
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
                                    <td>Transferee</td>
                                    <td><?php echo $student_unique_id;?></td>
                                    <td>Evaluation</td>

                                    <td><?php
                                        $date = new DateTime($proccess_date);
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

                                <th  style="color: #FFFF;"  id="line-2">___________________________</th>
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
                        <h3 class="mb-3 text-center text-muted">S.Y <?php echo $current_school_year_term;?> <?php echo $current_school_year_period;?> Semester</h3>
                        <h4 class="mb-3 text-center text-muted">Selected Section: <?php echo $section_name;?> Subjects </h4>

                    <form method="POST">

                            <table class="table table-hover "  style="font-size:15px" cellspacing="0"> 
                                <thead>
                                    <tr class="text-center"">
                                        <th>Id</th>
                                        <th>Code</th>
                                        <th>Description</th>
                                        <th>Unit</th>
                                        <th>Pre-Requisite</th>
                                        <th>Type</th>
                                        <th>
                                            Action
                                        </th>
                                    </tr>
                                </thead>

                                <tbody>
                                    <?php 
                                    
                                        $sql = $con->prepare("SELECT t2.* FROM course as t1

                                            INNER JOIN subject as t2 ON t2.course_id = t1.course_id

                                            WHERE t1.course_id=:course_id
                                            AND t1.course_level=:course_level
                                            AND t2.semester=:semester
                                            AND t1.active='yes'");

                                        $sql->bindValue(":course_id", $selected_course_id);
                                        $sql->bindValue(":course_level", $student_course_levelv2);
                                        $sql->bindValue(":semester", $current_school_year_period);
                                        $sql->execute();

                                        if($sql->rowCount() > 0){

                                            while($row = $sql->fetch(PDO::FETCH_ASSOC)){

                                                $subject_id = $row['subject_id'];
                                                $subject_code = $row['subject_code'];
                                                $subject_title = $row['subject_title'];
                                                $pre_requisite = $row['pre_requisite'];
                                                $subject_type = $row['subject_type'];
                                                $unit = $row['unit'];

                                                $student_student_subject_id = 0;

                                                $get_student_subject = $studentSubject->
                                                    GetNonFinalizedStudentSubject($student_id, $subject_id,
                                                    $enrollment_id, $current_school_year_id);

                                                
                                                if(count($get_student_subject) > 0){
                                                    $student_student_subject_id = $get_student_subject['subject_id'];
                                                    // echo $student_student_subject_id;
                                                }
                                                // $student_student_subject_id = 0;
                                                

                                                echo "
                                                    <tr class='text-center'>
                                                        <td>$subject_id</td>
                                                        <td>$subject_code</td>
                                                        <td>$subject_title</td>
                                                        <td>$unit</td>
                                                        <td>$pre_requisite</td>
                                                        <td>$subject_type</td>
                                                        <td>
                                                            <input name='normal_selected_subject[]' 
                                                                value='" . $subject_id . "'
                                                                type='checkbox'" . ($student_student_subject_id == $subject_id ? " checked" : "") . ">
                                                        </td>
                                                    </tr>
                                                ";
                                            }

                                        }
                                    ?>

                                </tbody>

                            </table>

                            <h3 class="mb-3 text-muted">Added Subjects</h3>
                            <table id="addedSubjectsTable" class="table table-bordered table-hover "  style="font-size:15px" cellspacing="0"> 
                                <thead>
                                    <tr class="text-center"">
                                        <th>Id</th>
                                        <th>Section</th>
                                        <th>Code</th>
                                        <th>Description</th>
                                        <th>Unit</th>
                                        <th>Pre-Requisite</th>
                                        <th>Type</th>
                                        <th>
                                            Action
                                        </th>
                                    </tr>
                                </thead>

                                <!-- <tbody>
                                    <?php 
                                    
                                        $sql = $con->prepare("SELECT 
                                            t1.program_section,
                                            t2.* 
                                            
                                            FROM course as t1

                                            INNER JOIN subject as t2 ON t2.course_id = t1.course_id

                                            WHERE t1.course_id !=:course_id
                                            AND t1.active='yes'
                                            AND t1.course_level=:course_level
                                            AND t2.semester=:semester
                                            ");

                                        $sql->bindValue(":course_id", $selected_course_id);
                                        $sql->bindValue(":course_level", $student_course_levelv2);
                                        $sql->bindValue(":semester", $current_school_year_period);
                                        $sql->execute();

                                        if($sql->rowCount() > 0){

                                            while($row = $sql->fetch(PDO::FETCH_ASSOC)){

                                                $subject_id = $row['subject_id'];
                                                $subject_code = $row['subject_code'];
                                                $subject_title = $row['subject_title'];
                                                $pre_requisite = $row['pre_requisite'];
                                                $subject_type = $row['subject_type'];
                                                $unit = $row['unit'];
                                                $course_level = $row['course_level'];
                                                $program_section = $row['program_section'];
                                                
                                                $student_student_subject_id = 0;

                                                $get_student_subject = $studentSubject->
                                                    GetNonFinalizedStudentSubject($student_id, $subject_id,
                                                    $enrollment_id, $current_school_year_id);

                                                
                                                if(count($get_student_subject) > 0){
                                                    $student_student_subject_id = $get_student_subject['subject_id'];
                                                    // echo $student_student_subject_id;
                                                }



                                                echo "
                                                    <tr class='text-center'>
                                                        <td>$subject_id</td>
                                                        <td>$program_section</td>
                                                        <td>$subject_code</td>
                                                        <td>$subject_title</td>
                                                        <td>$unit</td>
                                                        <td>$pre_requisite</td>
                                                        <td>$subject_type</td>
                                                        <td>
                                                            <input name='normal_selected_subject[]' 
                                                                value='" . $subject_id . "'
                                                                type='checkbox'" . ($student_student_subject_id == $subject_id ? " checked" : "") . ">
                                                        </td>
                                                    </tr>
                                                ";
                                            }

                                        }
                                    ?>

                                </tbody> -->
                                
                            </table>
                            <?php
                                if($student_id != null){

                                    $_SESSION['selected_course_id'] = $selected_course_id;

                                    ?>
                                        <button name="selected_btn_student" type="submit" 
                                            class="btn btn-primary btn">Add Subjects
                                        </button>

                                        <!-- <a href="../enrollees/view_student_transferee_enrollment_review.php?inserted=true&id=<?php echo $student_id?>&p_id=<?php echo $pending_enrollees_id?>&e_id=<?php echo $enrollment_id;?>">

                                            <button type="button" 
                                                class="btn btn-outline-info btn">Review Insertion
                                            </button>
                                        </a> -->

                                        <a href="../enrollees/view_student_transferee_enrollment_review_student.php?inserted=true&id=<?php echo $student_id?>&e_id=<?php echo $enrollment_id;?>">
                                        
                                            <button type="button" 
                                                class="btn btn-outline-info btn">Review Insertionv2
                                            </button>
                                        </a>
                                    <?php
                                }else{
                                    ?>
                                        <button type="button" disabled
                                            class="btn btn-outline-success btn">Select Section First
                                        </button>
                                    <?php 
                                }
                            ?>
                        <a href="transferee_process_enrollment-2.php?step2=true&st_id=<?php echo $student_id;?>">
                            <button type="button" class="btn-secondary btn">
                                Go backv2
                            </button>
                        </a>
                    </form>

                    </div> 
                </div>

                <script>
                    function add_non_transferee(student_id, subject_id,
                        course_level, school_year_id,
                        subject_code, enrollment_id){

                        Swal.fire({
                            icon: 'question',
                            title: `Inserting ${subject_code}`,
                            showCancelButton: true,
                            confirmButtonText: 'Yes',
                            cancelButtonText: 'No'
                        }).then((result) => {
                            if (result.isConfirmed) {
                                // console.log("nice")
                                $.ajax({
                                    url: '../ajax/enrollee/add_non_credit_subject.php',
                                    type: 'POST',
                                    data: {
                                        student_id, subject_id,
                                        course_level, school_year_id,
                                        enrollment_id
                                    },
                                    success: function(response) {
                                        // console.log(response);
                                        // alert(response);
                                        // window.location.href = 'transferee_process_enrollment.php?step3=true&id=101&selected_course_id=415';
                                        alert(response);

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

                    function add_credit_transferee(student_id, subject_id,
                        course_level, school_year_id, course_id,
                        subject_title, subject_code, enrollment_id){

                        Swal.fire({
                            icon: 'question',
                            title: `Inserting as credited ${subject_code}`,
                            showCancelButton: true,
                            confirmButtonText: 'Yes',
                            cancelButtonText: 'No'

                        }).then((result) => {
                            if (result.isConfirmed) {

                                $.ajax({
                                    url: '../ajax/enrollee/add_credit_subject.php',
                                    type: 'POST',
                                    data: {
                                        student_id, subject_id,
                                        course_level, school_year_id,
                                        course_id, subject_title, enrollment_id
                                    },
                                    success: function(response) {

                                        // console.log(response);
                                        alert(response);

                                        // Admin::success("", "");

                                        // window.location.href = 'transfee_enrollees.php';
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
                    $(document).ready(function(){

                        var course_id = `<?php echo $selected_course_id;?>`;
                        var course_level = `<?php echo $student_course_levelv2;?>`;
                        var semester = `<?php echo $current_school_year_period;?>`;
                        var student_id = `<?php echo $student_id;?>`;
                        var enrollment_id = `<?php echo $enrollment_id;?>`;
                        
                        var addedSubjectTable = $('#addedSubjectsTable').DataTable({
                            'processing': true,
                            'serverSide': true,
                            'serverMethod': 'POST',
                            'ajax': {
                                'url':`addedSubjectDataTable.php?id=${course_id}&level=${course_level}&semester=${semester}&st_id=${student_id}&e_id=${enrollment_id}`
                            },
                            'lengthChange': false,

                            'columns': [
                                { data: 'subject_id' },
                                { data: 'program_section' },
                                { data: 'subject_code' },
                                { data: 'subject_title' },
                                { data: 'unit' },
                                { data: 'pre_requisite' },
                                { data: 'subject_type' },
                                { data: 'actions1' }
                            ]
                        });

                        var id = '<?php echo $student_id; ?>';  
                    
                        var table = $('#transferee_selection_table').DataTable({
                            'processing': true,
                            'serverSide': true,
                            'serverMethod': 'POST',
                            'ajax': {
                                'url':'transfereeEnrollmentDataList.php?id=' + id
                            },
                            'columns': [
                                { data: 'subject_id' },
                                { data: 'subject_code' },
                                { data: 'subject_title' },
                                { data: 'unit' },
                                { data: 'semester' },
                                { data: 'subject_type' },
                                { data: 'actions2' },
                                { data: 'actions3' }
                            ]
                        });
                    });
                </script>
            <?php
        }
        # FOR NON EVALUATED (NEW TRANSFEREE) STEP 3 TRUE
        ##
        if(isset($_GET['id']) && isset($_GET['step3']) 
            && $_GET['step3'] == "true"
            && isset($_GET['selected_course_id'])){

            if(isset($_SESSION['selected_course_id'])){
                unset($_SESSION['selected_course_id']);
            }
            #
            $selected_course_id = $_GET['selected_course_id'];
           
            $section = new Section($con, $selected_course_id);
            $subject = new Subject($con, $registrarLoggedIn, null);

            $section_name = $section->GetSectionName();

            // echo $section_name . " step 3";
            
            if(isset($_POST['selected_btn']) && $_POST['normal_selected_subject']){

                $subjects = $_POST['normal_selected_subject'];

                // $course_level = $_POST['course_level'];

                // echo $course_level;
                $isInserted = false;

                foreach ($subjects as $key => $value) {
                    # code...

                    $subject_id = $value;
                    // echo $subject_id;
                    // echo "<br>";

                    $get_course_level = $subject->GetSubjectCourseLevel($subject_id);

                    # Check if inserted.

                    $getSubjectProgramId = $subject->GetSubjectProgramId($subject_id);

                    $checkSubjectExists = $studentSubject->CheckStudentSubject($student_id, $subject_id,
                        $enrollment_id, $current_school_year_id);
                    
                    if($checkSubjectExists == false){

                        $wasInserted = $studentSubject->InsertStudentSubject($student_id, $subject_id,
                            $enrollment_id, $get_course_level, $getSubjectProgramId, $current_school_year_id, "no", false);
                            
                        if($wasInserted == true){
                            $isInserted = true;
                        }
                    }

                }

                if($isInserted == true){

                    # ANALYZED IF TRANSFEREE STUDENT IS REGULAR OR IRREGULAR


                    // echo "success";
                    
                    // AdminUser::remove("Successfully added subjects",
                    //     "view_student_transferee_enrollment_review.php?inserted=true&id=$student_id&p_id=$pending_enrollees_id&e_id=$enrollment_id");

                    $url = "../enrollees/view_student_transferee_enrollment_review.php?inserted=true&id=$student_id&p_id=$pending_enrollees_id&e_id=$enrollment_id";

                    // AdminUser::success("Successfully added subjects",
                    //     "transferee_process_enrollment.php?step3=true&id=$pending_enrollees_id&selected_course_id=$selected_course_id");

                    AdminUser::success("Successfully added subjects",
                        "$url");
                    $_SESSION['selected_course_id'] = $selected_course_id;
                    exit();
                }
                
            }

            $credited_subject_array = [];


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
                                    <td>Transferee</td>
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

                                <th  style="color: #FFFF;"  id="line-2">___________________________</th>
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
                        <h4 class="mb-3 text-start text-muted">Choose Credited Subject(s)</h4>
                        <form method="POST">

                                <table id="credit_section_table" class="table table-bordered table-hover "  style="font-size:15px" cellspacing="0"> 
                                    <thead class="bg-dark">
                                        <tr class="text-center"">
                                            <th>Code</th>
                                            <th>Description</th>
                                            <th>Unit</th>
                                            <th>Pre-Requisite</th>
                                            <th>Level</th>
                                            <th>Semester</th>
                                            <th>
                                                Action
                                            </th>
                                        </tr>
                                    </thead>

                                    <tbody>
                                        <?php 

                                            $student_program_id = $studentEnroll->GetStudentProgramId($selected_course_id);

                                        
                                            # Depends on the PROGRAM CURRICULUM.
                                            
                                            $sql = $con->prepare("SELECT * FROM subject_program
                                            
                                            
                                                WHERE program_id=:program_id
                                                -- AND semester=:semester
                                                -- AND course_level=:course_level
                                                AND active='yes'
                                                ");

                                            $sql->bindValue(":program_id", $student_program_id);
                                            // $sql->bindValue(":semester", $current_school_year_period);
                                            // $sql->bindValue(":course_level", $student_course_level);
                                            $sql->execute();

                                            // $sql = $con->prepare("SELECT t2.* FROM course as t1

                                            //     INNER JOIN subject as t2 ON t2.course_id = t1.course_id

                                            //     WHERE t1.course_id=:course_id
                                            //     -- AND t1.course_level=:course_level
                                            //     -- AND t2.semester=:semester
                                            //     AND t1.active='yes'");

                                            // $sql->bindValue(":course_id", $selected_course_id);
                                            // // $sql->bindValue(":course_level", $student_course_level);
                                            // // $sql->bindValue(":semester", $current_school_year_period);
                                            // $sql->execute();

                                            if($sql->rowCount() > 0){

                                                while($row = $sql->fetch(PDO::FETCH_ASSOC)){

                                                    // $subject_id = $row['subject_id'];
                                                    $subject_code = $row['subject_code'];
                                                    $subject_title = $row['subject_title'];
                                                    // $pre_requisite = $row['pre_requisite'];
                                                    $pre_requisite = $row['pre_req_subject_title'];
                                                    $subject_type = $row['subject_type'];
                                                    $unit = $row['unit'];
                                                    $semester = $row['semester'];
                                                    $course_level = $row['course_level'];

                                                    $student_student_subject_id = 0;

                                                    // $hasAlreadyMarked = $studentSubject->
                                                    //     GetStudentTransfereeSubject($student_id, $subject_id);
                                                   
                                                    // $student_student_subject_id = 0;
                                                    
                                                //    <input name='credited_selected_subject[]' 
                                                //                     value='$subject_id'
                                                //                     type='checkbox'> 


                                                //   <input name='credited_selected_subject[]' 
                                                //                     value='" . $subject_id . "'
                                                //                     type='checkbox'" . ($hasAlreadyMarked ? " checked" : "") . ($hasAlreadyMarked ? " disabled" : "") . ">

                                                    // $creditBtn = "creditSubject($subject_id,
                                                    //     $student_id, \"$subject_title\", $enrollment_id)";

                                                    // $unCreditBtn = "unCreditSubject($subject_id,
                                                    //     $student_id, \"$subject_title\", $enrollment_id)";

                                                    $creditBtn = "creditSubject($student_id,
                                                        \"$subject_title\", $enrollment_id)";
                                                     
                                                    $unCreditBtn = "unCreditSubject($student_id,
                                                        \"$subject_title\", $enrollment_id)";

                                                    $btn = "";

                                                    $checkCreditedSubjectExists = $studentSubject->CheckAlreadyCreditedSubject(
                                                            $student_id, $subject_title);

                                                    if($checkCreditedSubjectExists){

                                                        $credited_subject_titles =  $subject_title;

                                                        $credited_subject_id = $studentSubject->GetSubjectIdFromCreditedSubjectTitle($subject_title,
                                                            $student_program_id, $selected_course_id);

                                                        // array_push($credited_subject_array, $credited_subject_titles);
                                                        array_push($credited_subject_array, $credited_subject_id);
                                                        
                                                        // echo $credited_subject_id;
                                                        // echo "<br>";

                                                        $btn = "
                                                            <button type='button' onclick='$unCreditBtn' class='btn btn-sm btn-danger'>
                                                                <i class='fas fa-undo'></i>
                                                            </button>
                                                        ";
                                                    }else if($checkCreditedSubjectExists == false){

                                                        $btn = "
                                                            <button type='button' onclick='$creditBtn' class='btn btn-sm btn-primary'>
                                                               <i class='fas fa-plus-circle'></i>
                                                            </button>
                                                        ";
                                                    }

                                                    echo "
                                                        <tr class='text-center'>
                                                            <td>$subject_code</td>
                                                            <td>$subject_title</td>
                                                            <td>$unit</td>
                                                            <td>$pre_requisite</td>
                                                            <td>$course_level</td>
                                                            <td>$semester</td>
                                                            <td>
                                                                $btn
                                                            </td>
                                                        </tr>
                                                    ";
                                                }

                                            }
                                        ?>
                                    </tbody>

                                    <script>

                                        function creditSubject(
                                            // subject_id, 
                                            student_id,
                                            subject_title, enrollment_id){


                                            console.log(subject_title)

                                            Swal.fire({
                                                icon: 'question',
                                                title: `Credit ${subject_title}`,
                                                showCancelButton: true,
                                                confirmButtonText: 'Yes',
                                                cancelButtonText: 'Cancel'

                                            }).then((result) => {

                                                if (result.isConfirmed) {

                                                    $.ajax({
                                                        url: '../ajax/subject/creditSubject.php',
                                                        type: 'POST',
                                                        data: {
                                                            // subject_id, 
                                                            student_id,
                                                            subject_title, enrollment_id
                                                        },
                                                        success: function(response) {

                                                            console.log(response)

                                                            if(response == "success_credit"){

                                                                Swal.fire({
                                                                    icon: 'success',
                                                                    title: `Successfully Credited`,
                                                                    showConfirmButton: false,
                                                                    timer: 800, // Adjust the duration of the toast message in milliseconds (e.g., 3000 = 3 seconds)
                                                                    toast: true,
                                                                    position: 'top-end',
                                                                    showClass: {
                                                                    popup: 'swal2-noanimation',
                                                                    backdrop: 'swal2-noanimation'
                                                                    },
                                                                    hideClass: {
                                                                    popup: '',
                                                                    backdrop: ''
                                                                    }
                                                                }).then((result) => {
                                                                    // location.reload();
                                                                    
                                                                    $('#selected_table').load(
                                                                        location.href + ' #selected_table'
                                                                    );

                                                                    $('#credit_section_table').load(
                                                                        location.href + ' #credit_section_table'
                                                                    );
                                                                });
                                                            }

                                                            if(response == "subject_exists"){
                                                                Swal.fire({
                                                                    icon: 'error',
                                                                    title: `Already Exists`,
                                                                    showConfirmButton: false,
                                                                    timer: 1200, // Adjust the duration of the toast message in milliseconds (e.g., 3000 = 3 seconds)
                                                                    toast: true,
                                                                    position: 'top-end',
                                                                    showClass: {
                                                                    popup: 'swal2-noanimation',
                                                                    backdrop: 'swal2-noanimation'
                                                                    },
                                                                    hideClass: {
                                                                    popup: '',
                                                                    backdrop: ''
                                                                    }
                                                                });
                                                            }
                                                        }
                                                    });
                                                }
                                            });
                                        }

                                        function unCreditSubject(
                                            // subject_id, 
                                            student_id,
                                            subject_title, enrollment_id){


                                            // console.log(subject_id)

                                            Swal.fire({
                                                icon: 'question',
                                                title: `Undo Credit ${subject_title}`,
                                                showCancelButton: true,
                                                confirmButtonText: 'Yes',
                                                cancelButtonText: 'Cancel'

                                            }).then((result) => {

                                                if (result.isConfirmed) {

                                                    $.ajax({
                                                        url: '../ajax/subject/unCreditSubject.php',
                                                        type: 'POST',
                                                        data: {
                                                            // subject_id,
                                                            student_id,
                                                            subject_title, enrollment_id
                                                        },

                                                        success: function(response) {
                                                            console.log(response);

                                                            if(response == "success_undo_credit"){
                                                            
                                                                Swal.fire({
                                                                icon: 'success',
                                                                title: `Successfully Undo the Credit Subject`,
                                                                showConfirmButton: false,
                                                                timer: 800, // Adjust the duration of the toast message in milliseconds (e.g., 3000 = 3 seconds)
                                                                toast: true,
                                                                position: 'top-end',
                                                                showClass: {
                                                                popup: 'swal2-noanimation',
                                                                backdrop: 'swal2-noanimation'
                                                                },
                                                                hideClass: {
                                                                popup: '',
                                                                backdrop: ''
                                                                }
                                                            }).then((result) => {
                                                                // location.reload();

                                                                // var tableContent = $(response).find('#credit_section_table').html();
                                                                // $('#credit_section_table').html(tableContent);

                                                                $('#selected_table').load(
                                                                    location.href + ' #selected_table'
                                                                );

                                                                $('#credit_section_table').load(
                                                                    location.href + ' #credit_section_table'
                                                                );
                                                            });

                                                            }
                                                        }
                                                    });
                                                }
                                            });
                                        }
                                    </script>
                                </table>


                                <!-- <button type="button" class="btn btn-sm btn-outline-primary" onclick="showDiv()">Add Subject</button> -->
                                <div  class="credited_subjects" 
                                    id="subjectDiv" style="display: none;">
                                    <h3 class="mb-3 text-primary text-center">Select Credit Subjects</h3>
                                    <table id="creditedSubjectsTable" class="table table-bordered table-hover "  style="font-size:15px" cellspacing="0"> 
                                        <thead>
                                            <tr class="text-center"">
                                                <th>Id</th>
                                                <th>Section</th>
                                                <th>Code</th>
                                                <th>Description</th>
                                                <th>Unit</th>
                                                <th>Pre-Requisite</th>
                                                <th>Type</th>
                                                <th>
                                                    Action
                                                </th>
                                            </tr>
                                        </thead>

                                        <!-- <tbody>
                                            <?php 
                                            
                                                $sql = $con->prepare("SELECT 
                                                    t1.program_section,
                                                    t2.* 
                                                    
                                                    FROM course as t1

                                                    INNER JOIN subject as t2 ON t2.course_id = t1.course_id

                                                    WHERE t1.course_id !=:course_id
                                                    AND t1.active='yes'
                                                    AND t1.course_level=:course_level
                                                    AND t2.semester=:semester
                                                    ");

                                                $sql->bindValue(":course_id", $selected_course_id);
                                                $sql->bindValue(":course_level", $student_course_level);
                                                $sql->bindValue(":semester", $current_school_year_period);
                                                $sql->execute();

                                                if($sql->rowCount() > 0){

                                                    while($row = $sql->fetch(PDO::FETCH_ASSOC)){

                                                        $subject_id = $row['subject_id'];
                                                        $subject_code = $row['subject_code'];
                                                        $subject_title = $row['subject_title'];
                                                        $pre_requisite = $row['pre_requisite'];
                                                        $subject_type = $row['subject_type'];
                                                        $unit = $row['unit'];
                                                        $course_level = $row['course_level'];
                                                        $program_section = $row['program_section'];
                                                        
                                                        $student_student_subject_id = 0;

                                                        $get_student_subject = $studentSubject->
                                                            GetNonFinalizedStudentSubject($student_id, $subject_id,
                                                            $enrollment_id, $current_school_year_id);

                                                        
                                                        if(count($get_student_subject) > 0){
                                                            $student_student_subject_id = $get_student_subject['subject_id'];
                                                            // echo $student_student_subject_id;
                                                        }



                                                        echo "
                                                            <tr class='text-center'>
                                                                <td>$subject_id</td>
                                                                <td>$program_section</td>
                                                                <td>$subject_code</td>
                                                                <td>$subject_title</td>
                                                                <td>$unit</td>
                                                                <td>$pre_requisite</td>
                                                                <td>$subject_type</td>
                                                                <td>
                                                                    <input name='normal_selected_subject[]' 
                                                                        value='" . $subject_id . "'
                                                                        type='checkbox'" . ($student_student_subject_id == $subject_id ? " checked" : "") . ">
                                                                </td>
                                                            </tr>
                                                        ";
                                                    }

                                                }
                                            ?>
                                        </tbody> -->
                                    </table>
                                </div>
                        </form>
                    </div> 

                    <div class="container mt-4 mb-2">
                        <h4 class="mb-3 text-center text-muted">Seelected Section: <?php echo $section_name;?> Subjects </h4>
                        <form method="POST">

                                <table id="selected_table" class="table table-hover table-responsive"  style="font-size:15px" cellspacing="0"> 
                                    <thead class="bg-dark">
                                        <tr class="text-center"">
                                            <th>Id</th>
                                            <th>Code</th>
                                            <th>Description</th>
                                            <th>Unit</th>
                                            <th>Pre-Requisite</th>
                                            <th>Type</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>

                                    <tbody>
                                        <?php 

                                            $creditedSubjectIds = implode(',', $credited_subject_array);

                                            // echo $creditedSubjectIds;

                                            if($creditedSubjectIds == null){
                                                $creditedSubjectIds = 0;
                                            }

                                            $sql = $con->prepare("SELECT t2.* FROM course as t1

                                                INNER JOIN subject as t2 ON t2.course_id = t1.course_id

                                                WHERE t2.subject_id NOT IN ($creditedSubjectIds)
                                                AND t1.course_id=:course_id
                                                AND t1.course_level=:course_level
                                                AND t2.semester=:semester
                                                AND t1.active='yes'");

                                            $sql->bindValue(":course_id", $selected_course_id);
                                            $sql->bindValue(":course_level", $student_course_level);
                                            $sql->bindValue(":semester", $current_school_year_period);
                                            $sql->execute();

                                            if($sql->rowCount() > 0){

                                                while($row = $sql->fetch(PDO::FETCH_ASSOC)){

                                                    $subject_id = $row['subject_id'];
                                                    $subject_code = $row['subject_code'];
                                                    $subject_title = $row['subject_title'];
                                                    $pre_requisite = $row['pre_requisite'];
                                                    $subject_type = $row['subject_type'];
                                                    $unit = $row['unit'];

                                                    $student_student_subject_id = 0;

                                                    $get_student_subject = $studentSubject->
                                                        GetNonFinalizedStudentSubject($student_id, $subject_id,
                                                        $enrollment_id, $current_school_year_id);

                                                    
                                                    if(count($get_student_subject) > 0){
                                                        $student_student_subject_id = $get_student_subject['subject_id'];
                                                        // echo $student_student_subject_id;
                                                    }
                                                    // $student_student_subject_id = 0;
                                                    
                                                    $hasAlreadyMarked = $studentSubject->
                                                        GetStudentTransfereeSubject($student_id, $subject_id);
                                                   
                                                    // $student_student_subject_id = 0;
                                                    
                                                //    <input name='credited_selected_subject[]' 
                                                //                     value='$subject_id'
                                                //                     type='checkbox'> 


                                                // <input name='normal_selected_subject[]' 
                                                //                     value='" . $subject_id . "'
                                                //                     type='checkbox'" . ($student_student_subject_id == $subject_id ? " checked" : "") . ">

                                                
                                                    $selectInput = "selectInput($subject_id, 
                                                        $enrollment_id, $student_id, $current_school_year_id)";

                                                    // <input onclick='$selectInput' name='select_selected_subject' 
                                                    //     type='checkbox'>

                                                    echo "
                                                        <tr class='text-center'>
                                                            <td>$subject_id</td>
                                                            <td>$subject_code</td>
                                                            <td>$subject_title</td>
                                                            <td>$unit</td>
                                                            <td>$pre_requisite</td>
                                                            <td>$subject_type</td>
                                                            <td>
                                                                <input name='normal_selected_subject[]' 
                                                                     value='" . $subject_id . "'
                                                                     type='checkbox'" . ($student_student_subject_id == $subject_id ? " checked" : "") . ">

                                                                
                                                                
                                                            </td>
                                                        </tr>
                                                    ";
                                                }

                                            }
                                        ?>

                                        <script>
                                            function selectInput(subject_id, 
                                                    enrollment_id, student_id, current_school_year_id){

                                                // var transferee_student_status = radio.value;

                                                console.log(subject_id)

                                                Swal.fire({
                                                    icon: 'success',
                                                    title: `Successfully Mark `,
                                                    showConfirmButton: false,
                                                    timer: 2000, // Adjust the duration of the toast message in milliseconds (e.g., 3000 = 3 seconds)
                                                    toast: true,
                                                    position: 'top-end',
                                                    showClass: {
                                                    popup: 'swal2-noanimation',
                                                    backdrop: 'swal2-noanimation'
                                                    },
                                                    hideClass: {
                                                    popup: '',
                                                    backdrop: ''
                                                    }
                                                });

                                                // Swal.fire({
                                                //     icon: 'question',
                                                //     title: `Chech`,
                                                //     showCancelButton: true,
                                                //     confirmButtonText: 'Yes',
                                                //     cancelButtonText: 'Cancel'
                                                // }).then((result) => {

                                                // });
                                            
                                            }
                                        </script>

                                    </tbody>

                                </table>
                                <!-- <button type="button" class="btn btn-sm btn-outline-primary" onclick="showDiv()">Add Subject</button> -->
                                <div class="added_subjects" id="subjectDiv" style="display: block;">

                                    <h3 class="mb-3 text-success">Add Subjects</h3>
                                    <table id="addedSubjectsTable" class="table table-bordered table-hover "  style="font-size:15px" cellspacing="0"> 
                                        <thead>
                                            <tr class="text-center"">
                                                <th>Id</th>
                                                <th>Section</th>
                                                <th>Code</th>
                                                <th>Description</th>
                                                <th>Unit</th>
                                                <th>Pre-Requisite</th>
                                                <th>Type</th>
                                                <th>
                                                    Action
                                                </th>
                                            </tr>
                                        </thead>

                                        <!-- <tbody>
                                            <?php 
                                            
                                                $sql = $con->prepare("SELECT 
                                                    t1.program_section,
                                                    t2.* 
                                                    
                                                    FROM course as t1

                                                    INNER JOIN subject as t2 ON t2.course_id = t1.course_id

                                                    WHERE t1.course_id !=:course_id
                                                    AND t1.active='yes'
                                                    AND t1.course_level=:course_level
                                                    AND t2.semester=:semester
                                                    ");

                                                $sql->bindValue(":course_id", $selected_course_id);
                                                $sql->bindValue(":course_level", $student_course_level);
                                                $sql->bindValue(":semester", $current_school_year_period);
                                                $sql->execute();

                                                if($sql->rowCount() > 0){

                                                    while($row = $sql->fetch(PDO::FETCH_ASSOC)){

                                                        $subject_id = $row['subject_id'];
                                                        $subject_code = $row['subject_code'];
                                                        $subject_title = $row['subject_title'];
                                                        $pre_requisite = $row['pre_requisite'];
                                                        $subject_type = $row['subject_type'];
                                                        $unit = $row['unit'];
                                                        $course_level = $row['course_level'];
                                                        $program_section = $row['program_section'];
                                                        
                                                        $student_student_subject_id = 0;

                                                        $get_student_subject = $studentSubject->
                                                            GetNonFinalizedStudentSubject($student_id, $subject_id,
                                                            $enrollment_id, $current_school_year_id);

                                                        
                                                        if(count($get_student_subject) > 0){
                                                            $student_student_subject_id = $get_student_subject['subject_id'];
                                                            // echo $student_student_subject_id;
                                                        }



                                                        echo "
                                                            <tr class='text-center'>
                                                                <td>$subject_id</td>
                                                                <td>$program_section</td>
                                                                <td>$subject_code</td>
                                                                <td>$subject_title</td>
                                                                <td>$unit</td>
                                                                <td>$pre_requisite</td>
                                                                <td>$subject_type</td>
                                                                <td>
                                                                    <input name='normal_selected_subject[]' 
                                                                        value='" . $subject_id . "'
                                                                        type='checkbox'" . ($student_student_subject_id == $subject_id ? " checked" : "") . ">
                                                                </td>
                                                            </tr>
                                                        ";
                                                    }

                                                }
                                            ?>

                                        </tbody> -->

                                        
                                    </table>
                                </div>

                                <?php
                                    if($student_id != null){

                                        $_SESSION['selected_course_id'] = $selected_course_id;

                                        ?>
                                            <button name="selected_btn" type="submit" 
                                                class="btn btn-primary btn">Add Subjects
                                            </button>

                                            <a href="../enrollees/view_student_transferee_enrollment_review.php?inserted=true&id=<?php echo $student_id?>&p_id=<?php echo $pending_enrollees_id?>&e_id=<?php echo $enrollment_id;?>">
                                                <button type="button" 
                                                    class="btn btn-outline-info btn">Review Insertion
                                                </button>
                                            </a>
                                        <?php
                                    }else{
                                        ?>
                                            <button type="button" disabled
                                                class="btn btn-outline-success btn">Select Section First
                                            </button>
                                        <?php 
                                    }
                                ?>
                            <a href="transferee_process_enrollment.php?step2=true&id=<?php echo $pending_enrollees_id;?>&selected_course_id=<?php echo $selected_course_id;?>">
                                <button type="button" class="btn-secondary btn">
                                    Go back
                                </button>
                            </a>
                        </form>

                    </div> 
                </div>

                <script>

                    function showDiv() {
                        var subjectDiv = document.getElementById("subjectDiv");
                            if (subjectDiv.style.display === "none") {
                                subjectDiv.style.display = "block";
                            } else {
                                subjectDiv.style.display = "none";
                            }
                    }

                    function add_non_transferee(student_id, subject_id,
                        course_level, school_year_id,
                        subject_code, enrollment_id){

                        Swal.fire({
                            icon: 'question',
                            title: `Inserting ${subject_code}`,
                            showCancelButton: true,
                            confirmButtonText: 'Yes',
                            cancelButtonText: 'No'
                        }).then((result) => {
                            if (result.isConfirmed) {
                                // console.log("nice")
                                $.ajax({
                                    url: '../ajax/enrollee/add_non_credit_subject.php',
                                    type: 'POST',
                                    data: {
                                        student_id, subject_id,
                                        course_level, school_year_id,
                                        enrollment_id
                                    },
                                    success: function(response) {
                                        // console.log(response);
                                        // alert(response);
                                        // window.location.href = 'transferee_process_enrollment.php?step3=true&id=101&selected_course_id=415';
                                        alert(response);

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

                    function add_credit_transferee(student_id, subject_id,
                        course_level, school_year_id, course_id,
                        subject_title, subject_code, enrollment_id){

                        Swal.fire({
                            icon: 'question',
                            title: `Inserting as credited ${subject_code}`,
                            showCancelButton: true,
                            confirmButtonText: 'Yes',
                            cancelButtonText: 'No'

                        }).then((result) => {
                            if (result.isConfirmed) {

                                $.ajax({
                                    url: '../ajax/enrollee/add_credit_subject.php',
                                    type: 'POST',
                                    data: {
                                        student_id, subject_id,
                                        course_level, school_year_id,
                                        course_id, subject_title, enrollment_id
                                    },
                                    success: function(response) {

                                        // console.log(response);
                                        alert(response);

                                        // Admin::success("", "");

                                        // window.location.href = 'transfee_enrollees.php';
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
                    $(document).ready(function(){

                        var course_id = `<?php echo $selected_course_id;?>`;
                        var course_level = `<?php echo $student_course_level;?>`;
                        var semester = `<?php echo $current_school_year_period;?>`;
                        var student_id = `<?php echo $student_id;?>`;
                        var enrollment_id = `<?php echo $enrollment_id;?>`;
                        
                        var addedSubjectTable = $('#addedSubjectsTable').DataTable({
                            'processing': true,
                            'serverSide': true,
                            'serverMethod': 'POST',
                            'ajax': {
                                'url':`addedSubjectDataTable.php?id=${course_id}&level=${course_level}&semester=${semester}&st_id=${student_id}&e_id=${enrollment_id}`
                            },
                            'lengthChange': false, // Disable the length change

                            'columns': [
                                { data: 'subject_id' },
                                { data: 'program_section' },
                                { data: 'subject_code' },
                                { data: 'subject_title' },
                                { data: 'unit' },
                                { data: 'pre_requisite' },
                                { data: 'subject_type' },
                                { data: 'actions1' }
                            ]
                        });

                        var id = '<?php echo $student_id; ?>';  
                    
                        var table = $('#transferee_selection_table').DataTable({
                            'processing': true,
                            'serverSide': true,
                            'serverMethod': 'POST',
                            'ajax': {
                                'url':'transfereeEnrollmentDataList.php?id=' + id
                            },
                            'columns': [
                                { data: 'subject_id' },
                                { data: 'subject_code' },
                                { data: 'subject_title' },
                                { data: 'unit' },
                                { data: 'semester' },
                                { data: 'subject_type' },
                                { data: 'actions2' },
                                { data: 'actions3' }
                            ]
                        });
                    });
                </script>
            <?php
        }

        # For Crediting Section *STEP 3 NON EVALUATED.
        if(isset($_GET['id']) && isset($_GET['step5']) 
            && $_GET['step5'] == "true"
            && isset($_GET['selected_course_id'])){

            if(isset($_SESSION['selected_course_id'])){
                unset($_SESSION['selected_course_id']);
            }

            #
            $selected_course_id = $_GET['selected_course_id'];
           
            $studentEnroll = new StudentEnroll($con);

            $student_program_id = $studentEnroll->GetStudentProgramId($selected_course_id);

            $section = new Section($con, $selected_course_id);
            $subject = new Subject($con, $registrarLoggedIn, null);

            $section_name = $section->GetSectionName();

            // echo $section_name . " step 3";
            
            if(isset($_POST['selected_btn']) 
                && $_POST['normal_selected_subject']){

                $subjects = $_POST['normal_selected_subject'];

                // $course_level = $_POST['course_level'];

                // echo $course_level;
                $isInserted = false;

                foreach ($subjects as $key => $value) {
                 

                    $subject_id = $value;
                    // echo $subject_id;
                    // echo "<br>";

                    $get_course_level = $subject->GetSubjectCourseLevel($subject_id);

                    # Check if inserted.

                    $getSubjectProgramId = $subject->GetSubjectProgramId($subject_id);

                    $checkSubjectExists = $studentSubject->CheckStudentSubject($student_id, $subject_id,
                        $enrollment_id, $current_school_year_id);
                    
                    if($checkSubjectExists == false){

                        $wasInserted = $studentSubject->InsertStudentSubject($student_id, $subject_id,
                            $enrollment_id, $get_course_level, $getSubjectProgramId, $current_school_year_id, "no", false);
                            
                        if($wasInserted == true){
                            
                            $isInserted = true;
                        }
                    }

                }

                if($isInserted == false){
                    $url = "../enrollees/view_student_transferee_enrollment_review.php?inserted=true&id=$student_id&p_id=$pending_enrollees_id&e_id=$enrollment_id";

                    // AdminUser::success("Successfully added subjects",
                    //     "transferee_process_enrollment.php?step3=true&id=$pending_enrollees_id&selected_course_id=$selected_course_id");

                    AdminUser::success("Successfully added subjects",
                        "$url");

                    $_SESSION['selected_course_id'] = $selected_course_id;
                    exit();

                }
            }

            if(isset($_POST['credited_selected_btn']) 
                && $_POST['credited_selected_subject']){


                $credited_subjects = $_POST['credited_selected_subject'];

                // var_dump($credited_subjects);
                $isCredited = false;

                $is_transferee = true;

                $alreadySelected = false;

                $is_final = "no";

                foreach ($credited_subjects as $key => $value) {

                    // $subject_title = $value;
                    $subject_id = $value;

                    // echo $subject_id;
                    // echo "<br>";

                    $get_course_level = $subject->GetSubjectCourseLevel($subject_id);
                    $subject_title = $subject->GetSubjectTitle($subject_id);

                    $getSubjectProgramId = $subject->GetSubjectProgramId($subject_id);

                    $checkSubjectExists = $studentSubject->CheckStudentSubject($student_id, $subject_id,
                        $enrollment_id, $current_school_year_id);

                    if($checkSubjectExists == false){

                        $wasInserted = $studentSubject->InsertStudentSubject($student_id, $subject_id,
                            $enrollment_id, $get_course_level, $getSubjectProgramId,
                                $current_school_year_id, $is_final, $is_transferee);
                            
                        if($wasInserted == true){

                            $student_subject_id = $con->lastInsertId();

                            // echo "$subject_title is credited";
                            // echo "<br>";

                            # Credit the subject.

                            $checkCreditedSubjectExists = $studentSubject->CheckAlreadyCreditedSubject(
                                $student_id, $subject_title);
                            
                            if(!$checkCreditedSubjectExists){

                                $wasCredited = $studentSubject->InsertStudentCreditedSubject($student_id,
                                    $subject_title, $student_subject_id, $subject_id);

                                if($wasCredited)
                                    $isCredited = true;

                            }else{
                                // Alert::error("Subject $subject_title is already credited", "");
                            }
                        }
                    }else{

                        $alreadySelected = true;
                        
                        // Alert::error("Subject $subject_title is already inserted",
                        //     "");

                            
                    }
                }

                // if($alreadySelected == true && $isCredited == false){
                //     header("Location: transferee_process_enrollment.php?step4=true&id=$pending_enrollees_id&selected_course_id=$selected_course_id");
                // }

                if($isCredited == true){

                    $url = "transferee_process_enrollment.php?step4=true&id=$pending_enrollees_id&selected_course_id=$student_course_id";

                    AdminUser::success("Successfully credited subjects",
                        "$url");

                    $_SESSION['selected_course_id'] = $selected_course_id;

                    exit();
                }



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
                                    <td>Transferee</td>
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

                                <th  style="color: #FFFF;"  id="line-2">___________________________</th>
                                <th class="subConfirm" id="icon-3">
                                <i style="color: #FFFF;" class="bi bi-journal"></i>
                                </th>

                                <th  style=""  id="line-2">___________________________</th>
                                <th class="subConfirm" id="icon-3">
                                <i style="color: #FFFF;" class="bi bi-journal"></i>
                                </th>

                            </tr>
                            <tr>
                                <td style="color: #FFFF;" class="checkDetails" id="process-1">Check details</td>
                                <td></td>
                                <td style="color: #FFFF;" class="findSection" id="process-2">Find section</td>
                                <td></td>
                                <td style="color: #FFFF;" class="subConfirm" id="process-3">Crediting Section</td>

                                <td></td>
                                <td style="" class="subConfirm" id="process-3">Subject Confirmation</td>
                            </tr>
                        </table>
                    </div>

                    <div class="container mt-4 mb-2">
                        <span class="text-dark">Crediting Sectionx</span>
                        <h3 class="mb-3 text-center text-muted">S.Y <?php echo $current_school_year_term;?> <?php echo $current_school_year_period;?> Semester</h3>
                        <h4 class="mb-3 text-center text-muted">Crediting Sectionx: <?php echo $section_name;?> Subjects </h4>

                        <form method="POST">

                                <table id="credit_section_table" class="table table-bordered table-hover "  style="font-size:15px" cellspacing="0"> 
                                    <thead>
                                        <tr class="text-center"">
                                            <th>Level</th>
                                            <th>Code</th>
                                            <th>Description</th>
                                            <th>Unit</th>
                                            <th>Pre-Requisite</th>
                                            <th>Type</th>
                                            <th>Semester</th>
                                            <th>
                                                Action
                                            </th>
                                        </tr>
                                    </thead>

                                    <tbody>
                                        <?php 
                                        
                                            # Depends on the PROGRAM CURRICULUM.
                                            
                                            // $sql = $con->prepare("SELECT * FROM subject_program
                                            
                                            
                                            //     WHERE program_id=:program_id
                                            //     AND semester=:semester
                                            //     AND course_level=:course_level
                                            //     AND active='yes'
                                            //     ");

                                            // $sql->bindValue(":program_id", $student_program_id);
                                            // $sql->bindValue(":semester", $current_school_year_period);
                                            // $sql->bindValue(":course_level", $student_course_level);
                                            // $sql->execute();

                                            $sql = $con->prepare("SELECT t2.* FROM course as t1

                                                INNER JOIN subject as t2 ON t2.course_id = t1.course_id

                                                WHERE t1.course_id=:course_id
                                                AND t1.course_level=:course_level
                                                AND t2.semester=:semester
                                                AND t1.active='yes'");

                                            $sql->bindValue(":course_id", $selected_course_id);
                                            $sql->bindValue(":course_level", $student_course_level);
                                            $sql->bindValue(":semester", $current_school_year_period);
                                            $sql->execute();

                                            if($sql->rowCount() > 0){

                                                while($row = $sql->fetch(PDO::FETCH_ASSOC)){

                                                    $subject_id = $row['subject_id'];
                                                    $subject_code = $row['subject_code'];
                                                    $subject_title = $row['subject_title'];
                                                    $pre_requisite = $row['pre_requisite'];
                                                    // $pre_requisite = $row['pre_req_subject_title'];
                                                    $subject_type = $row['subject_type'];
                                                    $unit = $row['unit'];
                                                    $semester = $row['semester'];
                                                    $course_level = $row['course_level'];

                                                    $student_student_subject_id = 0;



                                                    $hasAlreadyMarked = $studentSubject->
                                                        GetStudentTransfereeSubject($student_id, $subject_id);
                                                   
                                                    // $student_student_subject_id = 0;
                                                    
                                                //    <input name='credited_selected_subject[]' 
                                                //                     value='$subject_id'
                                                //                     type='checkbox'> 


                                                //   <input name='credited_selected_subject[]' 
                                                //                     value='" . $subject_id . "'
                                                //                     type='checkbox'" . ($hasAlreadyMarked ? " checked" : "") . ($hasAlreadyMarked ? " disabled" : "") . ">

                                                    $creditBtn = "creditSubject($subject_id,
                                                        $student_id, \"$subject_title\", $enrollment_id)";

                                                    $unCreditBtn = "unCreditSubject($subject_id,
                                                        $student_id, \"$subject_title\", $enrollment_id)";

                                                    $btn = "";

                                                    if($hasAlreadyMarked){
                                                        
                                                        $btn = "
                                                            <button type='button' onclick='$unCreditBtn' class='btn btn-sm btn-danger'>
                                                                <i class='fas fa-times'></i>
                                                            </button>
                                                        ";
                                                    }else{
                                                        $btn = "
                                                            <button type='button' onclick='$creditBtn' class='btn btn-sm btn-primary'>
                                                                    <i class='fas fa-plus-circle'></i>
                                                            </button>
                                                        ";
                                                    }

                                                    echo "
                                                        <tr class='text-center'>
                                                            <td>$course_level</td>
                                                            <td>$subject_code</td>
                                                            <td>$subject_title</td>
                                                            <td>$unit</td>
                                                            <td>$pre_requisite</td>
                                                            <td></td>
                                                            <td>$semester</td>
                                                            <td>
                                                                <input name='credited_selected_subject[]' 
                                                                    value='" . $subject_id . "'
                                                                    type='checkbox'" . ($hasAlreadyMarked ? " checked" : "") . ">
                                                                $btn
                                                            </td>
                                                        </tr>
                                                    ";
                                                }

                                            }
                                        ?>
                                    </tbody>

                                    <script>

                                        function creditSubject(subject_id, student_id,
                                            subject_title, enrollment_id){

                                            // console.log(subject_id)

                                            Swal.fire({
                                                icon: 'question',
                                                title: `Credit ${subject_title}`,
                                                showCancelButton: true,
                                                confirmButtonText: 'Yes',
                                                cancelButtonText: 'Cancel'

                                            }).then((result) => {

                                                if (result.isConfirmed) {

                                                    $.ajax({
                                                        url: '../ajax/subject/creditSubject.php',
                                                        type: 'POST',
                                                        data: {
                                                            subject_id, student_id,
                                                            subject_title, enrollment_id
                                                        },
                                                        success: function(response) {

                                                            console.log(response)

                                                            if(response == "success"){

                                                            Swal.fire({
                                                                icon: 'success',
                                                                title: `Successfully Credited`,
                                                                showConfirmButton: false,
                                                                timer: 800, // Adjust the duration of the toast message in milliseconds (e.g., 3000 = 3 seconds)
                                                                toast: true,
                                                                position: 'top-end',
                                                                showClass: {
                                                                popup: 'swal2-noanimation',
                                                                backdrop: 'swal2-noanimation'
                                                                },
                                                                hideClass: {
                                                                popup: '',
                                                                backdrop: ''
                                                                }
                                                            }).then((result) => {
                                                                location.reload();
                                                                $('#credit_section_table').load(
                                                                    location.href + ' #credit_section_table'
                                                                );
                                                            });
                                                        }
                                                        }
                                                    });
                                                }
                                            });
                                        }

                                        function unCreditSubject(subject_id, student_id,
                                            subject_title, enrollment_id){


                                            console.log(subject_id)

                                            Swal.fire({
                                                icon: 'question',
                                                title: `UnCredit ${subject_title}`,
                                                showCancelButton: true,
                                                confirmButtonText: 'Yes',
                                                cancelButtonText: 'Cancel'
                                            }).then((result) => {

                                                if (result.isConfirmed) {

                                                    $.ajax({
                                                        url: '../ajax/subject/unCreditSubject.php',
                                                        type: 'POST',
                                                        data: {
                                                            subject_id, student_id,
                                                            subject_title, enrollment_id
                                                        },

                                                        success: function(response) {
                                                            // console.log(response)

                                                            if(response == "success_deleted"){
                                                            
                                                            //     Swal.fire({
                                                            //     icon: 'success',
                                                            //     title: `Successfully Uncredited`,
                                                            //     showConfirmButton: false,
                                                            //     timer: 800, // Adjust the duration of the toast message in milliseconds (e.g., 3000 = 3 seconds)
                                                            //     toast: true,
                                                            //     position: 'top-end',
                                                            //     showClass: {
                                                            //     popup: 'swal2-noanimation',
                                                            //     backdrop: 'swal2-noanimation'
                                                            //     },
                                                            //     hideClass: {
                                                            //     popup: '',
                                                            //     backdrop: ''
                                                            //     }
                                                            // }).then((result) => {
                                                            //     // location.reload();

                                                            //     // var tableContent = $(response).find('#credit_section_table').html();
                                                            //     // $('#credit_section_table').html(tableContent);

                                                            //     $('#credit_section_table').load(
                                                            //         location.href + ' #credit_section_table'
                                                            //     );
                                                            // });

                                                            Swal.fire({
                                                                icon: 'error',
                                                                title: 'Undo',
                                                                text: 'Successfully Un-credited'
                                                            }).then((result) => {
                                                                if (result.isConfirmed) {
                                                                    $('#credit_section_table').load(
                                                                        location.href + ' #credit_section_table'
                                                                    );
                                                                }
                                                            });

                                                            }
                                                        }
                                                    });
                                                }
                                            });
                                        }
                                    </script>
                                </table>


                                <!-- <button type="button" class="btn btn-sm btn-outline-primary" onclick="showDiv()">Add Subject</button> -->
                                <div  class="credited_subjects" 
                                    id="subjectDiv" style="display: none;">
                                    <h3 class="mb-3 text-primary text-center">Select Credit Subjects</h3>
                                    <table id="creditedSubjectsTable" class="table table-bordered table-hover "  style="font-size:15px" cellspacing="0"> 
                                        <thead>
                                            <tr class="text-center"">
                                                <th>Id</th>
                                                <th>Section</th>
                                                <th>Code</th>
                                                <th>Description</th>
                                                <th>Unit</th>
                                                <th>Pre-Requisite</th>
                                                <th>Type</th>
                                                <th>
                                                    Action
                                                </th>
                                            </tr>
                                        </thead>

                                        <!-- <tbody>
                                            <?php 
                                            
                                                $sql = $con->prepare("SELECT 
                                                    t1.program_section,
                                                    t2.* 
                                                    
                                                    FROM course as t1

                                                    INNER JOIN subject as t2 ON t2.course_id = t1.course_id

                                                    WHERE t1.course_id !=:course_id
                                                    AND t1.active='yes'
                                                    AND t1.course_level=:course_level
                                                    AND t2.semester=:semester
                                                    ");

                                                $sql->bindValue(":course_id", $selected_course_id);
                                                $sql->bindValue(":course_level", $student_course_level);
                                                $sql->bindValue(":semester", $current_school_year_period);
                                                $sql->execute();

                                                if($sql->rowCount() > 0){

                                                    while($row = $sql->fetch(PDO::FETCH_ASSOC)){

                                                        $subject_id = $row['subject_id'];
                                                        $subject_code = $row['subject_code'];
                                                        $subject_title = $row['subject_title'];
                                                        $pre_requisite = $row['pre_requisite'];
                                                        $subject_type = $row['subject_type'];
                                                        $unit = $row['unit'];
                                                        $course_level = $row['course_level'];
                                                        $program_section = $row['program_section'];
                                                        
                                                        $student_student_subject_id = 0;

                                                        $get_student_subject = $studentSubject->
                                                            GetNonFinalizedStudentSubject($student_id, $subject_id,
                                                            $enrollment_id, $current_school_year_id);

                                                        
                                                        if(count($get_student_subject) > 0){
                                                            $student_student_subject_id = $get_student_subject['subject_id'];
                                                            // echo $student_student_subject_id;
                                                        }



                                                        echo "
                                                            <tr class='text-center'>
                                                                <td>$subject_id</td>
                                                                <td>$program_section</td>
                                                                <td>$subject_code</td>
                                                                <td>$subject_title</td>
                                                                <td>$unit</td>
                                                                <td>$pre_requisite</td>
                                                                <td>$subject_type</td>
                                                                <td>
                                                                    <input name='normal_selected_subject[]' 
                                                                        value='" . $subject_id . "'
                                                                        type='checkbox'" . ($student_student_subject_id == $subject_id ? " checked" : "") . ">
                                                                </td>
                                                            </tr>
                                                        ";
                                                    }

                                                }
                                            ?>
                                        </tbody> -->
                                    </table>
                                </div>

                                <?php
                                    if($student_id != null){

                                        $_SESSION['selected_course_id'] = $selected_course_id;

                                        ?>
                                            <button style="display: none;" name="credited_selected_btn" type="submit" 
                                                class="btn btn-primary btn">Add to Credit
                                            </button>

                                        <?php
                                    }else{
                                        ?>
                                            <button type="button" disabled
                                                class="btn btn-outline-success btn">Select Section First
                                            </button>
                                        <?php 
                                    }
                                ?>

                                <a href="transferee_process_enrollment.php?step4=true&id=<?php echo $pending_enrollees_id;?>&selected_course_id=<?php echo $selected_course_id;?>">
                                    <button type="button" class="btn-primary btn">
                                        Proceed
                                    </button>
                                </a>

                                <a href="transferee_process_enrollment.php?step4=true&id=<?php echo $pending_enrollees_id;?>&selected_course_id=<?php echo $student_course_id;?>">
                                    <button type="button" class="btn-success btn">
                                        Skip
                                    </button>
                                </a>

                                <a href="transferee_process_enrollment.php?step2=true&id=<?php echo $pending_enrollees_id;?>">
                                    <button type="button" class="btn-secondary btn">
                                        Go back
                                    </button>
                                </a>
                        </form>

                    </div> 
                </div>

                <script>

                    function showDiv() {
                        var subjectDiv = document.getElementById("subjectDiv");
                            if (subjectDiv.style.display === "none") {
                                subjectDiv.style.display = "block";
                            } else {
                                subjectDiv.style.display = "none";
                            }
                    }

                    function add_non_transferee(student_id, subject_id,
                        course_level, school_year_id,
                        subject_code, enrollment_id){

                        Swal.fire({
                            icon: 'question',
                            title: `Inserting ${subject_code}`,
                            showCancelButton: true,
                            confirmButtonText: 'Yes',
                            cancelButtonText: 'No'
                        }).then((result) => {
                            if (result.isConfirmed) {
                                // console.log("nice")
                                $.ajax({
                                    url: '../ajax/enrollee/add_non_credit_subject.php',
                                    type: 'POST',
                                    data: {
                                        student_id, subject_id,
                                        course_level, school_year_id,
                                        enrollment_id
                                    },
                                    success: function(response) {
                                        // console.log(response);
                                        // alert(response);
                                        // window.location.href = 'transferee_process_enrollment.php?step3=true&id=101&selected_course_id=415';
                                        alert(response);

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

                    function add_credit_transferee(student_id, subject_id,
                        course_level, school_year_id, course_id,
                        subject_title, subject_code, enrollment_id){

                        Swal.fire({
                            icon: 'question',
                            title: `Inserting as credited ${subject_code}`,
                            showCancelButton: true,
                            confirmButtonText: 'Yes',
                            cancelButtonText: 'No'

                        }).then((result) => {
                            if (result.isConfirmed) {

                                $.ajax({
                                    url: '../ajax/enrollee/add_credit_subject.php',
                                    type: 'POST',
                                    data: {
                                        student_id, subject_id,
                                        course_level, school_year_id,
                                        course_id, subject_title, enrollment_id
                                    },
                                    success: function(response) {

                                        // console.log(response);
                                        alert(response);

                                        // Admin::success("", "");

                                        // window.location.href = 'transfee_enrollees.php';
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

                    $(document).ready(function(){

                        var course_id = `<?php echo $selected_course_id;?>`;
                        var course_level = `<?php echo $student_course_level;?>`;
                        var semester = `<?php echo $current_school_year_period;?>`;
                        var student_id = `<?php echo $student_id;?>`;
                        var enrollment_id = `<?php echo $enrollment_id;?>`;
                        
                        var addedSubjectTable = $('#creditedSubjectsTable').DataTable({
                            'processing': true,
                            'serverSide': true,
                            'serverMethod': 'POST',
                            'ajax': {
                                'url':`creditedSubjectDataTable.php?id=${course_id}&level=${course_level}&semester=${semester}&st_id=${student_id}&e_id=${enrollment_id}`
                            },
                            'lengthChange': false, // Disable the length change

                            'columns': [
                                { data: 'subject_id' },
                                { data: 'program_section' },
                                { data: 'subject_code' },
                                { data: 'subject_title' },
                                { data: 'unit' },
                                { data: 'pre_requisite' },
                                { data: 'subject_type' },
                                { data: 'actions1' }
                            ]
                        });

                        var id = '<?php echo $student_id; ?>';  
                    
                        var table = $('#transferee_selection_table').DataTable({
                            'processing': true,
                            'serverSide': true,
                            'serverMethod': 'POST',
                            'ajax': {
                                'url':'transfereeEnrollmentDataList.php?id=' + id
                            },
                            'columns': [
                                { data: 'subject_id' },
                                { data: 'subject_code' },
                                { data: 'subject_title' },
                                { data: 'unit' },
                                { data: 'semester' },
                                { data: 'subject_type' },
                                { data: 'actions2' },
                                { data: 'actions3' }
                            ]
                        });
                    });
                </script>
            <?php
        }
    }


?>