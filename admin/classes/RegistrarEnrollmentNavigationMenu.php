<?php 

    class RegistrarEnrollmentNavigationMenu{
        
        private $con, $userLoggedInObj;

        public function __construct($con, $userLoggedInObj)
        {
            $this->con = $con;
            $this->userLoggedInObj = $userLoggedInObj;
        }

        public function create(){

            $base_url = 'http://localhost/dcbt/admin';

            $registrar_admission_url = $base_url . '/enrollment/index.php';
            $courses_admission_url = $base_url . '/courses/registrar_index.php';
            // $student_creation_url = $base_url . '/student/registrar_student_index.php';
            $student_creation_url = $base_url . '/student/index.php';
            $section_url = $base_url . '/student/index.php';
            $strand_section = $base_url . '/section/index.php';
            
            // $enrollment_url = $base_url . '/enrollment/index.php';
            $enrollment_url = $base_url . '/enrollment/history.php';

            $school_year_url = $base_url . '/school_year/indexv2.php';
            $admission_url = $base_url . '/admission/evaluation.php';
            $account_url = $base_url . '/account/index.php';
            // $courses_url = $base_url . '/subject/index.php';
            $courses_url = $base_url . '/courses/registrar_course_list.php';

            $teacher_url = $base_url . '/teacher/registrar_access_index.php';
   
            $grade__url = $base_url . '/grade/index.php';

            $result = $this->createNavigation("$admission_url",
                "../../assets/images/icons/home.png", "Admission");
            
            $result .= $this->createNavigationIcon("$student_creation_url",
                "fas fa-user", "Students");

            $result .= $this->createNavigationIcon("$strand_section",
                "fas fa-hotel", "Sections");

            $result .= $this->createNavigationIcon("$teacher_url",
                "fas fa-book-reader", "Teacher");

            // $result .= $this->createNavigation("$courses_url",
            //     "../../assets/images/icons/home.png", "Courses");


            $result .= $this->createNavigation("$enrollment_url",
                "../../assets/images/icons/home.png", "Enrollment");

            $result .= $this->createNavigation("$grade__url",
                "../../assets/images/icons/home.png", "Grade Module");

            $result .= $this->createNavigation("$school_year_url",
                "../../assets/images/icons/home.png", "School Year");
 
            
            // $result .= $this->createNavigation("$enrollees_url",
            //     "../../assets/images/icons/home.png", "New Enrollees");

            // $result .= $this->createNavigation("$old_enrollees_url",
            //     "../../assets/images/icons/history.png", "Old Enrollees (SHS)");
            
            // $result .= $this->createNavigation("$transferee_url",
            //     "../../assets/images/icons/history.png", "Transferee Enrollees");

            if(AdminUser::IsRegistrarAuthenticated()){
                    $result .= $this->createNavigation("../logout.php", 
                "../../assets/images/icons/logout.png", "Logout");
            }
            return "
                <div class='navigationItems'>
                    $result
                </div>
            ";
        }
                        // <img src='$profile'>
        
        public function createNavigation($link, $profile, $text){
            return "
                <div class='navigationItem'>
                    <a style='color: #333;' href='$link'>
                        <img src='$profile'>
                        <span>$text</span>
                    </a>
                </div>
            ";
        }
        
        public function createNavigationIcon($link, $profile, $text){
            return "
                <div class='navigationItem'>
                    <a href='$link'>
                        <i style='color: #333;' class='$profile'></i>
                        <span>$text</span>
                    </a>
                </div>
            ";
        }
    }

?>