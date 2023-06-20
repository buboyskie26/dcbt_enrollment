<?php 

    class AdminEnrollmentNavigationMenu{
        
        private $con, $userLoggedInObj;

        public function __construct($con, $userLoggedInObj)
        {
            $this->con = $con;
            $this->userLoggedInObj = $userLoggedInObj;
        }

        public function create(){

            $base_url = 'http://localhost/dcbt/admin';
                
            // Set the dynamic part of the URL using a global variable
 
            $enrollees_subject = $base_url . '/subject/index.php';
            $courses_url = $base_url . '/courses/course_list.php';   

            $student_creation_url = $base_url . '/student/index.php';
            $department_url = $base_url . '/department/index.php';

            $unassigned_strand_section = $base_url . '/section/admin_strand_sections.php';
            $admin_find_account = $base_url . '/account/admin_find_account.php';

            $teacher_url = $base_url . '/teacher/index.php';
            $enrollment_url = $base_url . '/enrollment/index.php';
            $school_year_url = $base_url . '/school_year/index.php';

            $schedule_url = $base_url . '/schedule/index.php';
        
            // echo $enrollees_url;
            
            // Search to become dynamic to avoid possible redirect error
            // when it was launch in the internet.
            
            $current_url = "http://" . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
            // header("Location: " . $current_url . "profile.php");

            // echo $current_url;
            // echo $current_url;
            // echo "<br>";
            // echo $_SERVER['HTTP_HOST'];
            // echo "<br>";
            // echo $_SERVER['REQUEST_URI'];

            $school_year_url = $base_url . '/school_year/index.php';

            $result = $this->createNavigation("$school_year_url",
                "../../assets/images/icons/home.png", "School Year");

            $result .= $this->createNavigation("$teacher_url",
                "../../assets/images/icons/home.png", "Teacher");
            
            $result .= $this->createNavigation("$courses_url",
                "../../assets/images/icons/home.png", "Courses");

            $result .= $this->createNavigation("$enrollees_subject",
                "../../assets/images/icons/home.png", "Subjects");

            $result .= $this->createNavigation("$unassigned_strand_section",
                "../../assets/images/icons/home.png", "Sections");

           $result .= $this->createNavigation("$admin_find_account",
                "../../assets/images/icons/home.png", "Accounts");

            $result .= $this->createNavigation("$schedule_url",
                "../../assets/images/icons/home.png", "Schedule");


            if(AdminUser::IsAuthenticated()){
                    $result .= $this->createNavigation("../logout.php", 
                "../../assets/images/icons/logout.png", "Logout");
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