<?php  

    require_once "libs/config.php";
    require_once "libs/functions.php";

    if( isset($_SESSION['fname']) || isset($_SESSION['uname']) || isset($_SESSION['email'])  ){

        header("location:dashboard.php");
    }

    include 'index.html';


      if( isset($_POST['submit']) ){
        $user_email = $_POST['user_email'];
        $pass = $_POST['pass'];

        if( empty($user_email) || empty($pass) ){
            $mess = "<p class='alert alert-danger'> Field Must not be Empty !<button class='close' data-dismiss='alert'>&times;</button></p>";
        }else{


          $sql = "SELECT * FROM users WHERE uname='$user_email' OR email='$user_email'";
          $data = $conn -> query($sql);

          $num = $data -> num_rows;


          if( $num == 1 ){

            $user_login_data = $data -> fetch_assoc();

            if( password_verify( $pass , $user_login_data['pass']  ) == false ){
              $mess = "<p class='alert alert-danger'> Incorrect Password !<button class='close' data-dismiss='alert'>&times;</button></p>";
            }else{

              $_SESSION['pic'] = $user_login_data['photo'];
              $_SESSION['fname'] = $user_login_data['fname'];
              $_SESSION['uname'] = $user_login_data['uname'];
              $_SESSION['cell'] = $user_login_data['cell'];
              $_SESSION['email'] = $user_login_data['email'];

              header("location:dashboard.php");

            }


          }else{

            $mess = "<p class='alert alert-danger'> Wrong Username or Email !<button class='close' data-dismiss='alert'>&times;</button></p>";

          }




        }
      }

?>
