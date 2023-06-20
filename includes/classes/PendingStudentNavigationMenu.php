<?php 

    class PendingStudentNavigationMenu{
        
        private $con, $userLoggedInObj;

        public function __construct($con, $userLoggedInObj)
        {
            $this->con = $con;
            $this->userLoggedInObj = $userLoggedInObj;
        }

        public function create(){
      
            // HARD-CODED
            $base_url = 'http://localhost/dcbt/enrollment/';

            // Set the dynamic part of the URL using a global variable
                
            $student_profile = $base_url . 'process.php?new_student=true&step=1';

            $sql = $this->con->prepare("SELECT * FROM pending_enrollees
                WHERE firstname=:firstname
                AND is_finished=:is_finished
                ");

            $sql->bindValue(":firstname", $this->userLoggedInObj);
            $sql->bindValue(":is_finished", 1);
            $sql->execute();

            if($sql->rowCount() > 0){
                
                $student_profile = $base_url . 'profile.php?fill_up_state=finished';
            }

            $result = "";

            if(AdminUser::IsStudentPendingAuthenticated()){
                $result .= $this->createNavigation("$student_profile",
                "../assets/images/icons/home.png", "Application");

                // $result .= $this->createNavigation("$registration",
                //     "../assets/images/icons/history.png", "Registration");

                // $result .= $this->createNavigation("../logout.php", 
                //     "../assets/images/icons/logout.png", "Logout");

                $result .= $this->createNavigation("../student_logout.php", 
                    "../assets/images/icons/logout.png", "Logout"); 
            }

            
            // if(AdminUser::IsStudentEnrolledAuthenticated()){

            //     $result .= $this->createNavigation("$student_profile",
            //         "../assets/images/icons/home.png", "Profile");
                
            //     $result .= $this->createNavigation("$registration",
            //         "../assets/images/icons/history.png", "Registration");

            //     if(isset($_SESSION['status']) == "enrolled"){
            //         $result .= $this->createNavigation($student_schedule,
            //             "../assets/images/icons/history.png", "Schedule");
            //     }

            //     $result .= $this->createNavigation("$student_grade_list",
            //         "../assets/images/icons/history.png", "Prospectus");
            //             $result .= $this->createNavigation("../logout.php", 
            //         "../assets/images/icons/logout.png", "Logout");
            // }

            return "
                <div class='navigationItems'>
                    $result
                </div>
            ";
        }
        
        public function createNavigation($link, $profile, $text){
            return "
                <div class='navigationItem'>
                    <a href='$link'>
                        <img src='$profile'>
                        <span>$text</span>
                    </a>
                </div>
            ";
        }
    }

?>