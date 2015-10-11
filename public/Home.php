<!DOCTYPE html>
<?php
require_once ('../private/My_Database.php');
$My_DB = new My_Database();

?>
<html>
<head>
    <title>Content Management System </title>
    <meta name="description" content="">

    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <meta http-equiv="content-type" content="text-html; charset=utf-8">

    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="css/bootstrap-glyphicons.css" rel="stylesheet">

    <link href="css/css/styles.css" rel="stylesheet">

    <script src="js/jquery-2.0.2.js"></script>
    <script src="js/modernizr-2.6.2.min.js"></script>
    <script src="js/jquery.stellar.js"></script>


</head>

<body>
<div class="navbar navbar-fixed-top navbar-default" id="nav">
    <div class="container" id="dd">
        <button class="navbar-toggle" data-target="#dnav" data-toggle="collapse" type="button">
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
        </button>
        <div class="nav-collapse collapse navbar-responsive-collapse" id="dnav">
            <ul class="nav navbar-nav pull-left">
                <li class="active"><a href="?page=p_home">  Home </a> </li>
                <li><a href="#about"> About US </a></li>
            </ul>
            <ul class="nav navbar-nav pull-right">
                <li><a href="?page=p_login"> Log in </a></li>
                <li><a href="?page=p_sign_up"> Register </a></li>
            </ul>
        </div>
    </div>

</div>
<?php

if(isset($_GET['page'])){
    if($_GET['page'] == 'p_home'){
        include'pages/Home/Home.php';
    }elseif($_GET['page'] == 'p_login'){
        include'pages\Account\LogIn.php';
    }elseif($_GET['page'] == 'p_gallery'){
        include'pages\Gallery\Gallery.php';
    }elseif($_GET['page'] == 'p_sign_up') {
        include 'pages\Account\SignUp.php';
    }elseif($_GET['page'] == 'user_page'){
        include'pages\Account\users.php';
    }elseif($_GET['page'] == 'u_login'){
        if(isset($_GET['user_name']) && isset($_GET['user_pass'])){
            $res = $My_DB->is_valid_user($_GET['user_name'],$_GET['user_pass'])['result'];
            if(isset($res['id'])){
                if($res['user_type'] == 'Admin'){
                    include'pages\Account\Admin.php';
                }else{
                    include'pages\Account\users.php';
                }
            }else{
                include'pages\Account\LogIn.php';
            }
        }else{
            include'pages\Account\LogIn.php';
        }
    }elseif($_GET['page'] == 'u_sign_up'){
        if(isset($_GET['full_name']) && isset($_GET['user_name']) && isset($_GET['user_pass'])){
            $res = $My_DB->is_valid_user($_GET['user_name'],$_GET['user_pass'])['result'];
            if(isset($res['id'])){
                $res = null;
            }else{
                $My_DB->add_user($_GET['full_name'],$_GET['user_name'],$_GET['user_pass'],'User',1);
                $res = $My_DB->is_valid_user($_GET['user_name'],$_GET['user_pass'])['result'];
                if(isset($res['id'])){
                    include'pages\Account\users.php';
                }else{
                    include'pages\Account\SignUp.php';
                }
            }

        }else{
            include'pages\Account\SignUp.php';
        }
    }
}else{
    include'pages/Home/Home.php';
}
?>
<footer class="navbar-fixed-bottom" id="contact" style="background-color: beige; position: relative; bottom: 0px;text-align: center ">
    <div class="container">
        <div class="row">
            <div class="col-lg-2">
                <br><br>
                <h5>Copyright &copy; 2015 |
                    <small>Content Management System</small>
                </h5>

            </div>
            <div class="col-sm-2">

            </div>
            <div class="col-sm-2">
                <h4>Navigation</h4>
                <ul class="list-unstyled">
                    <li><a href="home.html">Home </a></li>
                    <li><a href="about.html">About </a></li>
                    <li><a href="Donate.html">Help </a></li>
                    <li><a href="Contact.html">Contact Us </a></li>
                </ul>
            </div>

            <div class="col-sm-2">
                <h4> Follow us on:</h4>
                <ul class="list-unstyled">
                    <li><a href="https://www.facebook.com">Facebook </a></li>
                    <li><a href="https://twitter.com">Twitter </a></li>
                    <li><a href="www.plus.google.com">Google Plus </a></li>
                </ul>
            </div>

        </div>

    </div>

</footer>
<script>
    $.stellar();
</script>
</body>
</html>