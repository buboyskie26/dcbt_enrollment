<?php 

    require_once('../includes/studentHeader.php');
    require_once('./classes/StudentEnroll.php');
    require_once('./classes/Pending.php');
    require_once('./classes/Section.php');
    require_once('./classes/SectionTertiary.php');
    require_once('../includes/classes/Student.php');
    require_once('../includes/classes/form-helper/Account.php');

    if(isset($_SESSION['username'])
        && isset($_SESSION['status']) 
        && $_SESSION['status'] == 'pending'
        && $_SESSION['status'] != 'enrolled'
    ){
        # Pending firstname;

        $username = $_SESSION['username'];

        $pending = new Pending($con);
        // echo $username;

        $sql = $con->prepare("SELECT * FROM pending_enrollees
            WHERE firstname=:firstname
            AND is_finished=:is_finished
            ");

        $sql->bindValue(":firstname", $username);
        $sql->bindValue(":is_finished", 1);
        $sql->execute();

        if($sql->rowCount() > 0){

            $row = $sql->fetch(PDO::FETCH_ASSOC);

            # STEP 1
            $pending_enrollees_id = empty($row['pending_enrollees_id']) ? null : $row['pending_enrollees_id'];
            $program_id = empty($row['program_id']) ? 0 : $row['program_id'];
            $type = empty($row['type']) ? '' : $row['type'];
            $student_status = empty($row['student_status']) ? '' : $row['student_status'];

            // STEP 2
            $firstname = empty($row['firstname']) ? '' : $row['firstname'];
            $middle_name = empty($row['middle_name']) ? '' : $row['middle_name'];
            $lastname = empty($row['lastname']) ? '' : $row['lastname'];
            $civil_status = empty($row['civil_status']) ? '' : $row['civil_status'];
            $nationality = empty($row['nationality']) ? '' : $row['nationality'];
            $sex = empty($row['sex']) ? '' : $row['sex'];
            $birthday = empty($row['birthday']) ? '' : $row['birthday'];
            $religion = empty($row['religion']) ? '' : $row['religion'];
            $address = empty($row['address']) ? '' : $row['address'];
            $contact_number = empty($row['contact_number']) ? '' : $row['contact_number'];
            $email = empty($row['email']) ? '' : $row['email'];
            $birthplace = empty($row['birthplace']) ? '' : $row['birthplace'];


            if(isset($_GET['fill_up_state']) && $_GET['fill_up_state'] == "finished"){

                $isFinishedForm = $pending->CheckStudentFinishedForm($pending_enrollees_id);

                ?>
                    <div class="row col-md-12">
                        <div class="row">

                            <?php
                                if($isFinishedForm == false){

                                    echo "
                                    <div class='col-md-6'>
                                        <a href='process.php?new_student=true&step=1'>
                                            <button class='btn btn-outline-primary'>New Student Process</button>
                                        </a>
                                    </div>
                                    ";

                                }
                            ?>

                            <?php

                                $url = "./process.php?new_student=true&step=3";

                                if($isFinishedForm == true){
                                echo "
                                    <div class='col-md-6'>
                                        <a href='$url'>
                                            <button class='btn btn-outline-info'>View Form</button>
                                        </a>
                                    </div>
                                    ";
                                }
                            ?>

                        </div>

                        <div class="card">
                            <div class="card-header">
                                <div class="text-center container">
                                        <h3>Successfully filled-up the form</h3>
                                        <p>Please walk-in for registrar accomodation.</p>
                                </div>
                            
                            </div>
                            <div class="card-body"></div>
                        </div>
                    </div>
                <?php
            }

            if(isset($_GET['view_form']) && $_GET['view_form'] == "true"
                && isset($_GET['id'])){


                    

                    ?>
                        <div class="row col-md-12">
                            <div class="card">
                                <div class="card-header">
                                    <h3 class="text-center text-primary">My Submission Form</h3>
                                </div>
                                <div class="card-body">
                                        <div class="container mb-4">
                                                <div class="form-group">
                                                    <label for="firstname">First Name</label>
                                                    <!-- <input type="text" id="firstname" name="firstname" class="form-control"> -->
                                                    <input type="firstname" name="firstname" class="form-control" 
                                                        value="<?php echo ($firstname != "") ? $firstname : ''; ?>">
                                                </div>

                                                <div class="form-group">
                                                    <label for="middlename">Middle Name</label>
                                                    <input type="middle_name" name="middle_name" class="form-control" 
                                                        value="<?php echo ($middle_name != "") ? $middle_name : ''; ?>">
                                                </div>

                                                <div class="form-group">
                                                    <label for="lastname">Last Name</label>
                                                    <input type="lastname" name="lastname" class="form-control" 
                                                        value="<?php echo ($lastname != "") ? $lastname : ''; ?>">
                                                </div>

                                                <div class="form-group">
                                                    <label for="status">Status</label>
                                                    <select id="status" name="civil_status" class="form-control">
                                                        <option value="Single"<?php echo ($civil_status == "Single") ? " selected" : ""; ?>>Single</option>
                                                        <option value="Married"<?php echo ($civil_status == "Married") ? " selected" : ""; ?>>Married</option>
                                                        <option value="Divorced"<?php echo ($civil_status == "Divorced") ? " selected" : ""; ?>>Divorced</option>
                                                        <option value="Widowed"<?php echo ($civil_status == "Widowed") ? " selected" : ""; ?>>Widowed</option>
                                                    </select>
                                                </div>

                                                <div class="form-group">
                                                    <label for="citizenship">Citizenship</label>
                                                    <input type="nationality" name="nationality" class="form-control" 
                                                        value="<?php echo ($nationality != "") ? $nationality : ''; ?>">
                                                </div>

                                                <div class="form-group">
                                                    <label for="sex">Gender</label>
                                                    <div>
                                                        <!-- <input type="radio" id="male" name="sex" value="Male"> -->
                                                        <input type="radio" name="sex"
                                                            value="Male" <?php echo ($sex == "Male") ? ' checked' : ''; ?>>
                                                        <label for="male">Male</label>
                                                    </div>
                                                    <div>
                                                        <input type="radio" name="sex"
                                                            value="Female" <?php echo ($sex == "Female") ? ' checked' : ''; ?>>
                                                        <label for="female">Female</label>
                                                    </div>
                                                </div>

                                                <div class="form-group">
                                                    <label for="birthday">Birth Date</label>

                                                    <!-- <input type="date" id="birthday" name="birthday" class="form-control"> -->
                                                    <input type="date" id="birthday" name="birthday" class="form-control" value="<?php echo ($birthday != "") ? $birthday : ""; ?>">
                                                </div>

                                                <div class="form-group">
                                                    <label for="birthplace">Birth Place</label>
                                                    <input type="text" id="birthplace" name="birthplace" class="form-control" value="<?php echo ($birthplace != "") ? $birthplace : ""; ?>">

                                                </div>

                                                <div class="form-group">
                                                    <label for="religion">Religion</label>
                                                    <input type="text" id="religion" name="religion" class="form-control" value="<?php echo ($religion != "") ? $religion : ""; ?>">

                                                </div>

                                                <div class="form-group">
                                                    <label for="address">Address</label>
                                                    
                                                    <input type="text" id="address" name="address" class="form-control" value="<?php echo ($address != "") ? $address : ""; ?>">
                                                
                                                </div>

                                                <div class="form-group">
                                                    <label for="contact_number">Phone Number</label>
                                                
                                                    <input type="tel" id="contact_number" name="contact_number" class="form-control" value="<?php echo ($contact_number != "") ? $contact_number : ""; ?>">
                                                </div>

                                                <div class="form-group">
                                                    <label for="email">Email</label>
                                                    <!-- <input type="email" id="email" name="email" class="form-control"> -->
                                                    <input type="email" name="email" class="form-control" 
                                                        value="<?php echo ($email != "") ? $email : ''; ?>">
                                                </div>

                                                <button name="new_step3_btn" type="submit" 
                                                    class="btn btn-primary">Go back</button>

                                        </div>
                                    </div>
                                </div>
                        </div>
                    <?php
            }
        }else{

            header("Location: process.php?new_student=true&step=1");
            exit();
        }

    }else{


        $studentEnroll = new StudentEnroll($con);

        $student_id = $studentEnroll->GetStudentId($_SESSION['username']);

        // echo $_SESSION['username'];


        if(isset($_POST['save_resetted_password'])){

            $account = new Account($con);

            $new_password = $_POST['new_password'];
            $confirm_password = $_POST['confirm_password'];

            if ($new_password === $confirm_password) {

                $doesSave = $account->SavePassword($new_password, $student_id);
                if($doesSave){
                    echo "change successfully";

                AdminUser::success("Password has been save.", "");

                }
            }else{
                AdminUser::error("Password wont match", "");
            }

        }

        ?>
            <div class="col-md-12 row">

                <div class="col-md-8 offset-md-2">
                    
                    <div class="card">
                        <div class="card-header">
                            <h3 class="text-center text-muted">Credentials</h3>
                        </div>
                        
                        <div class="card-body">

                            <form method="post">

                                <div class="form-group mb-3 ">
                                    <label for="">New Password</label>
                                    <input class="form-control" type="password" 
                                        name="new_password" 
                                        placeholder="Set New Password" autocomplete="off">
                                </div>

                                <div class="form-group mb-3 ">
                                    <label for="">Confirm Password</label>
                                    <input class="form-control" type="password" 
                                        name="confirm_password" 
                                        placeholder="Confirm Password" autocomplete="off">
                                </div>

                                <button name="save_resetted_password" type="submit" class="btn btn-primary">Save</button>
                            </form>

                        </div>
                    </div>
                </div>

            </div>

        <?php
    }
?>