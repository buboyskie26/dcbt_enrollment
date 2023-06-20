<?php

    include('../admin_enrollment_header.php');
    include('../../enrollment/classes/StudentEnroll.php');
    include('../classes/Subject.php');


    if(!AdminUser::IsAuthenticated()){
        header("Location: /dcbt/adminLogin.php");
        exit();
    }

    $subject = new Subject($con, $registrarLoggedIn, null);

    $form = $subject->createFormModified();

    echo "
        <div class='col-md-10 row offset-md-1'>
            $form
        </div>
    ";

?>