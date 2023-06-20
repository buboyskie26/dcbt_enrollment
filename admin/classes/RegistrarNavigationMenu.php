<?php 

    class RegistrarNavigationMenu{
        
        private $con, $userLoggedInObj;

        public function __construct($con, $userLoggedInObj)
        {
            $this->con = $con;
            $this->userLoggedInObj = $userLoggedInObj;
        }

        public function create(){

            // $result = $this->createNavigation("school_year.php",
            //     "../assets/images/icons/home.png", "School Year");
            
            // HARD-CODED
            $base_url = 'http://localhost/dcbt/admin';

            // Set the dynamic part of the URL using a global variable
            $registrar_url = $base_url . '/registrar_index.php';
            $courses_url = $base_url . '/courses/registrar_course_list.php';
            // $student_creation_url = $base_url . '/student/registrar_student_index.php';
            $student_creation_url = $base_url . '/student/index.php';
            
            $strand_section = $base_url . '/section/index.php';
                
            $school_year_url = $base_url . '/school_year/indexv2.php';
            $admission_url = $base_url . '/admission/evaluation.php';
            $account_url = $base_url . '/account/index.php';
            // $enrollment_url = $base_url . '/enrollment/index.php';
            $enrollment_url = $base_url . '/enrollment/history.php';

            $enrollees_url = $base_url . '/enrollees/index.php';
            $transferee_url = $base_url . '/enrollees/transfee_enrollees.php';
            $old_enrollees_url = $base_url . '/enrollees/old_enrollees.php';
          
            $teacher_url = $base_url . '/teacher/registrar_access_index.php';
            $subject_url = $base_url . '/subject/index.php';
            $grade__url = $base_url . '/grade/index.php';


            $current_url = "http://" . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];

            // $result = $this->createNavigationv2("$admission_url",
            //     "fas", "Admission");

            $result = $this->createNavigation("$admission_url",
                "../assets/images/icons/home.png", "Admission");
            
            $result .= $this->createNavigation("$student_creation_url",
                "../assets/images/icons/home.png", "Students");

            $result .= $this->createNavigation("$strand_section",
                "../assets/images/icons/home.png", "Sections");

            $result .= $this->createNavigation("$teacher_url",
                "../assets/images/icons/home.png", "Teacher");

            // $result .= $this->createNavigation("$courses_url",
            //     "../assets/images/icons/home.png", "Courses");
           

            $result .= $this->createNavigation("$enrollment_url",
                "../assets/images/icons/home.png", "Enrollment");

            $result .= $this->createNavigation("$grade__url",
                "../assets/images/icons/home.png", "Grade Module");

            $result .= $this->createNavigation("$school_year_url",
                "../assets/images/icons/home.png", "School Year");

            // $result .= $this->createNavigation("$enrollees_url",
            //     "../assets/images/icons/home.png", "New Enrollees");
            
            // $result .= $this->createNavigation("$old_enrollees_url",
            //     "../assets/images/icons/history.png", "Old Enrollees (SHS)");
            
            // $result .= $this->createNavigation("$transferee_url",
            //     "../assets/images/icons/history.png", "Transferee Enrollees");
 
            if(AdminUser::IsRegistrarAuthenticated()){
                    $result .= $this->createNavigation("logout.php", 
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
        public function createNavigationv2($link, $profile, $text){
            return "
                <div class='navigationItem'>
                    <a href='$link'>
                        <i class='$profile'></i>
                        <span>$text</span>
                    </a>
                </div>
            ";
        }
    }

?>