<?php 

    class StudentNavigationMenu{
        
        private $con, $userLoggedInObj;

        public function __construct($con, $userLoggedInObj)
        {
            $this->con = $con;
            $this->userLoggedInObj = $userLoggedInObj;
        }

        public function create(){
      
            // HARD-CODED
            $base_url = 'http://localhost/dcbt/enrollment/';

            $elms_base_url = 'http://localhost/dcbt/elms/';

            // Set the dynamic part of the URL using a global variable
            $registrar_url = $base_url . '/registrar_index.php';
            $courses_url = $base_url . '/courses/registrar_course_list.php';
            $student_creation_url = $base_url . '/student/registrar_student_index.php';
            
            $strand_section = $base_url . '/section/index.php';
                
            $student_profile = $base_url . 'profile.php';
            $student_schedule = $base_url . 'student_schedule.php';
            $registration = $base_url . 'current_semester_subject.php';
            $student_grade_list = $base_url . 'student_grade_list.php';
            $user_dashboard_url = $elms_base_url . 'user_dashboard.php';
           

            $current_url = "http://" . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];

            $result = "";

            if(AdminUser::IsStudentPendingAuthenticated()){
                $result .= $this->createNavigation("$student_profile",
                "../assets/images/icons/home.png", "Profile");

                $result .= $this->createNavigation("$registration",
                    "../assets/images/icons/history.png", "Registration");

                $result .= $this->createNavigation("../logout.php", 
                    "../assets/images/icons/logout.png", "Logout");

            }

            
            if(AdminUser::IsStudentEnrolledAuthenticated()){

                $result .= $this->createNavigation("$student_profile",
                    "../assets/images/icons/home.png", "Profile");
                
                $result .= $this->createNavigation("$registration",
                    "../assets/images/icons/history.png", "Registration");

                if(isset($_SESSION['status']) == "enrolled"){
                    $result .= $this->createNavigation($student_schedule,
                        "../assets/images/icons/history.png", "Schedule");
                }



                $result .= $this->createNavigation("$student_grade_list",
                    "../assets/images/icons/history.png", "Prospectus");

                $result .= $this->createNavigation("$user_dashboard_url",
                    "../assets/images/icons/history.png", "My Class");

                // $result .= $this->createNavigation("../logout.php", 
                //     "../assets/images/icons/logout.png", "Logout");

                $result .= $this->createNavigation("../student_logout.php", 
                "../assets/images/icons/logout.png", "Logout");  
            }

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