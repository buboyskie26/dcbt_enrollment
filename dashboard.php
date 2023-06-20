<?php
    include('includes/config.php');

    // require_once('./classes/StudentEnroll.php');
    require_once('enrollment/classes/StudentEnroll.php');
    require_once('includes/classes/form-helper/Constants.php');

    // require_once('../includes/config.php');
    // require_once('../includes/classes/form-helper/Constants.php');

    $enroll = new StudentEnroll($con);


    if(isset($_POST['samp_btn'])
      && isset($_POST['username'])
      && isset($_POST['password'])
      ){
      // echo "qwe";

      $username =  $_POST['username'];
      $password =  $_POST['password'];

      // echo $username;
      // echo $password;


      $wasSuccess = $enroll->loginStudentUser($username, $password);

      // if(sizeof($object) > 0 && $object[1] == true){
      if(sizeof($wasSuccess) > 0 && $wasSuccess[1] == true && $wasSuccess[2] == "enrolled"){

          $_SESSION['username'] = $wasSuccess[0];
          $_SESSION['status'] = "enrolled";

          // $current_url = "http://" . $_SERVER['HTTP_HOST'] ;
          $current_url = "http://" . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];

          $url = "http://localhost/dcbt/enrollment/profile.php";
          header("Location: " . $url . "");
          // echo $current_url;
          // header("Location: " . $current_url . "profile.php");
      }

      if(sizeof($wasSuccess) > 0 && $wasSuccess[1] == true && $wasSuccess[2] != "enrolled"){
          $_SESSION['username'] = $wasSuccess[0];
          $_SESSION['status'] = "pending";

          // $current_url = "http://" . $_SERVER['HTTP_HOST'] ;
          $current_url = "http://" . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];

          $url = "http://localhost/dcbt/enrollment/profile.php?fill_up_state=finished";
          header("Location: " . $url . "");
      }

    }


?>



<!DOCTYPE html>
<html lang="en">
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

    <link rel="stylesheet" href="assets/css/mainWeb.css" />

    <title>Daehan College of Business and Technology</title>
  </head>
  <body>
    <nav class="navbar navbar-expand-lg navbar-light">
      <a class="navbar-brand" href="Main.html">
        <img
          src="img/DCBT-Logo.jpg"
          alt="DCBT-Logo"
        />
      </a>
      <button
        class="navbar-toggler"
        type="button"
        data-toggle="collapse"
        data-target="#navbarSupportedContent"
        aria-controls="navbarSupportedContent"
        aria-expanded="false"
        aria-label="Toggle navigation"
      >
        <span class="navbar-toggler-icon"></span>
      </button>

      <div class="collapse navbar-collapse" id="navbarSupportedContent">
        <ul class="navbar-nav ml-auto">
          <li class="nav-item">
            <a class="nav-link" href="#"><span>ADMISSIONS</span></a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="#"><span>ACADEMICS</span></a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="#"><span>ABOUT</span></a>
          </li>
          <li class="nav-item">
            <a class="nav-link" id="show-login"><span>LOGIN</span></a>
            <!-- <a href="enrollment/student_login.php" class="nav-link"><span>LOGIN</span></a> -->
          </li>
        </ul>
      </div>
    </nav>

    <div class="header">
      <img
        src="img/DCBT-Logo.jpg"
        alt="DCBT-Logo"
      />
      <h2>DAEHAN COLLEGE OF BUSINESS AND TECHNOLOGY</h2>
    </div>

    <div class="slide-1">
      <div class="content-1">
        <div class="content-img1">
          <img
            src="img/DCBT-Cover.jpg"
            alt="DCBT-Cover"
          />
        </div>
        <div class="content-text1">
          <h3>Be a DAEHAN student TODAY!</h3>
          <p>
            Lorem ipsum dolor sit amet, consectetur adipisicing elit. Obcaecati
            dignissimos sapiente, officiis iusto saepe itaque delectus beatae
            quo, vitae sint veniam quos deleniti rem veritatis quasi, eveniet
            laudantium distinctio ipsa?
          </p>
          <button class="enroll" onclick="enroll()">Enroll now!</button>
        </div>
      </div>
    </div>

    <div class="slide-2">
      <h3>Courses Offered</h3>

      <div class="container">
        <div class="row">
          <div class="col">
            <div class="course-header">
              <img
                src="img/DCBT-SHS-Logo.jpg"
              />
              <h3>Senior High</h3>
            </div>

            <div class="shs-courses">
              <h4>ACADEMIC TRACK</h4>
              <p>ABM (Accountancy, Business and Management)</p>
              <p>HUMMS (Humanities and Social Science)</p>
              <p>GAS (General Academic Strand)</p>
              <h4>TECH-VOCATIONAL TRACK</h4>
              <p>ICT (Information and Communication Technology)</p>
              <p>
                IA (Industrial Arts - Consumer Electronics/Electrical
                Installation Maintenance)
              </p>
              <h4>ARTS & DESIGN TRACK</h4>
            </div>
          </div>
        </div>

        <div class="row">
          <div class="col">
            <div class="course-header">
              <img
                src="img/DCBT-Logo.jpg"
              />
              <h3>College</h3>
            </div>

            <div class="college-courses">
              <h4>BACHELOR'S DEGREE PROGRAMS</h4>
              <p>BCM (Bachelor of Christian Ministries)</p>
              <p>ABE (Bachelor of Arts in English)</p>
              <p>BSENTREP (Bachelor of Science in Entrepreneurship)</p>
              <p>BTTE (Bachelor of Science in Teachers Education)</p>
              <p>BPE (Bachelor of Physical Education)</p>
            </div>
          </div>
        </div>
      </div>
    </div>

    <div class="slide-3">
      <h3>News and Events!</h3>

      <div class="slideshow-container">
        <div class="slide-img fade">
          <img
            src="img/DCBT-Building.jpg"
            style="width: 100%"
          />
        </div>
        <div class="slide-img fade">
          <img
            src="img/DCBT-Building.jpg"
            style="width: 100%"
          />
        </div>
        <div class="slide-img fade">
          <img
            src="img/DCBT-Building.jpg"
            style="width: 100%"
          />
        </div>
        <div class="slide-img fade">
          <img
            src="img/DCBT-Building.jpg"
            style="width: 100%"
          />
        </div>

        <a class="prev" onclick="plusSlides(-1, 0)">&#10094;</a>
        <a class="next" onclick="plusSlides(1, 0)">&#10095;</a>
      </div>
    </div>

    <div class="slide-4">
      <h3>About</h3>

      <div class="container">
        <div class="row">
          <div class="col">
            <div class="mission">
              <h3>Mission</h3>
              <p>
                Lorem ipsum dolor sit amet consectetur adipisicing elit. Hic,
                odio impedit, excepturi quod a molestiae accusamus saepe ab
                quisquam fuga autem similique nisi aspernatur perspiciatis
                tenetur eum, nesciunt ratione et.
              </p>
            </div>
          </div>
          <div class="col">
            <div class="vision">
              <h3>Vision</h3>
              <p>
                Lorem ipsum dolor, sit amet consectetur adipisicing elit. In
                rerum facilis sunt eum consequatur quasi recusandae repellendus
                accusamus, voluptate illo nihil minima qui. Laborum eaque,
                dolore error ab possimus quas.
              </p>
            </div>
          </div>
        </div>
      </div>
    </div>

    <div class="slide-5">
      <h3>Facilities</h3>

      <div class="slideshow-container">
        <div class="slide-img2 fade">
          <img
            src="img/DCBT-Cover.jpg"
            style="width: 100%"
          />
        </div>
        <div class="slide-img2 fade">
          <img
            src="img/DCBT-Cover.jpg"
            style="width: 100%"
          />
        </div>
        <div class="slide-img2 fade">
          <img
            src="img/DCBT-Cover.jpg"
            style="width: 100%"
          />
        </div>
        <div class="slide-img2 fade">
          <img
            src="img/DCBT-Cover.jpg"
            style="width: 100%"
          />
        </div>

        <a class="prev" onclick="plusSlides(-1, 1)">&#10094;</a>
        <a class="next" onclick="plusSlides(1, 1)">&#10095;</a>
      </div>
    </div>

    

    <footer>
      <div class="contact">
        <h4>DAEHAN COLLEGE OF BUSINESS AND TECHNOLOGY</h4>
        <p>Nicanor Reyes Street, Sampaloc, Manila</p>
        <p>Tel No: +63(2)-87777-338</p>
        <p>Terms and condition | Privacy Policy</p>
      </div>
      <div class="copyright">
        <h4>Copyright © 2019. All Rights Reserved</h4>
      </div>
    </footer>

    <div class="popup" id="login-form">

      <div class="close-btn">&times;</div>
      <form method="POST">
        <div class="form">
        <h2>Log-in</h2>
        <p>Log-in with your school email</p>
        <div class="form-element">
          <label for="email">Email</label>
          <input name="username" type="text" id="email">
        </div>
        <div class="form-element">
          <a class="forgot-email">Forgot email?</a>
        </div>
        <div class="form-element">
          <label for="password">Password</label>
          <input value="123456" type="password" name="password" id="password">
        </div>
        <div class="form-element">
          <a class="forgot-password">Forgot password?</a>
        </div>
        <div class="form-element">
          <button name="samp_btn" type="submit">Confirm</button>
        </div>
      </form>

    </div>

    <script>
      let slideIndex = [1, 1];
      let slideId = ["slide-img", "slide-img2"];
      showSlides(1, 0);
      showSlides(1, 1);

      function plusSlides(n, no) {
        showSlides((slideIndex[no] += n), no);
      }

      function showSlides(n, no) {
        let i;
        let x = document.getElementsByClassName(slideId[no]);
        if (n > x.length) {
          slideIndex[no] = 1;
        }
        if (n < 1) {
          slideIndex[no] = x.length;
        }
        for (i = 0; i < x.length; i++) {
          x[i].style.display = "none";
        }
        x[slideIndex[no] - 1].style.display = "block";
      }
    </script>

    <script>
      document.querySelector("#show-login").addEventListener("click",function(){
        document.querySelector("#login-form").classList.add("active");
      });
      document.querySelector(".popup .close-btn").addEventListener("click",function(){
        document.querySelector("#login-form").classList.remove("active");
      });
    </script>

    <script>
      function enroll(){
        window.location.href = "/dcbt/enrollment/app.php"
        // window.location.href = "../enrollment-page-main/Enrollment-page.html"
      }
    </script>

    <script
      src="https://cdn.jsdelivr.net/npm/jquery@3.5.1/dist/jquery.slim.min.js"
      integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj"
      crossorigin="anonymous"
    ></script>
    <script
      src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"
      integrity="sha384-Fy6S3B9q64WdZWQUiU+q4/2Lc9npb8tCaSX9FK7E8HnRr0Jz8D6OP9dO5Vg3Q9ct"
      crossorigin="anonymous"
    ></script>
  </body>
</html>

