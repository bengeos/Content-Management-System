<!DOCTYPE html>

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
                <li class="active"><a href="#home">  Home </a> </li>
                <li><a href="#about"> About US </a></li>
                <li><a href="?page=gallery"> Gallery </a></li>
                <li><a href="#event"> Events </a></li>
                <li><a href="?page=forum"> Forum </a></li>
                <li><a href="#club"> Clubs </a></li>
                <li><a href="#contact"> Contact Us </a> </li>
            </ul>

            <ul class="nav navbar-nav pull-right">
              <li><a href="?page=Tnoti"> Log in </a></li>
              <li><a href="?page=Tnoti"> Register </a></li>
            </ul>


        </div>
    </div>

</div>
<?php
if(isset($_GET['page']) AND $_GET['page'] == 'event'){
include'pages/non_loggedin/events.php';
}elseif(isset($_GET['page']) AND $_GET['page'] == 'forum'){
include'pages/non_loggedin/forum.php';
}
elseif(isset($_GET['foru']) AND $_GET['foru'] == 'disp'){
    include'pages/non_loggedin/display_forum.php';
}
elseif(isset($_GET['page']) AND $_GET['page'] == 'gallery'){
    include'pages/non_loggedin/gallery.php';
}
else{
?>

<div id="home" data-stellar-background-ratio="0.6>
      <div class=" container
"><br><br><br>

<div class="row" id="spacer"></div>
<div class="row">
    <div class="col-sm-3"></div>
    <div class="col-sm-6" id="hinner">
        <h3 style="color: #ffffff"> SSSSSSSSSSSSSSSWSSSSSS <br>
            <small>kjahsdjkfhasdjkhfkajsdhfjkh</small>
        </h3>
        <br>
        <button class="btn btn- "> About US!</button>

    </div>
    <div class="col-sm-3"></div>
</div>
</div>
</div>
<div id="hcontent">
    <div class="container">
        <div class="row">
            <div class="col-sm-2"></div>
            <div class="col-sm-8">
                <h2>SSSSSSSSSsdfsadfasdf sdf dasf sdf asdf asdf ds da asdf as asdf asdsdfsdf asdf dasf asd asd asd sadf
                    asf </h2>

            </div>
            <div class="col-sm-2"></div>

        </div>
    </div>
</div>
<div id="about" data-stellar-background-ratio="0.6">
    <div class="container">
        <div class="row" id="spacer"></div>
        <div class="row">
            <div class="col-sm-2"></div>

            <div class="col-sm-8"><h3 style="color: #ffffff; text-align: center;"> About Us </h3></div>

            <div class="col-sm-2"></div>
        </div>
    </div>
</div>

<div id="hcontent">
    <div class="container">
        <div class="row">
            <div class="col-sm-2"></div>
            <div class="col-sm-8">
                <h2>SSSSSSSSSsdfsadfasdf sdf dasf sdf asdf asdf ds da asdf as asdf asdsdfsdf asdf dasf asd asd asd sadf
                    asf </h2>

            </div>
            <div class="col-sm-2"></div>

        </div>
    </div>
</div>
<div id="gallery" data-stellar-background-ratio="0.5">
    <div class="container">
        <div class="row" id="spacer"></div>
        <div class="row">
            <div class="col-sm-2"></div>

            <div class="col-sm-8"><h3 style="color: #ffffff; text-align: center;"> Gallery </h3></div>

            <div class="col-sm-2"></div>
        </div>
    </div>
</div>

<div id="hcontent">
    <div class="container">
        <div class="row">
            <div class="col-sm-2"></div>
            <div class="col-sm-8">
                <h2>SSSSSSSSSsdfsadfasdf sdf dasf sdf asdf asdf ds da asdf as asdf asdsdfsdf asdf dasf asd asd asd sadf
                    asf </h2>

            </div>
            <div class="col-sm-2"></div>

        </div>
    </div>
</div>
<div id="event" data-stellar-background-ratio="0.5">
    <div class="container">
        <div class="row" id="spacer"></div>
        <div class="row">
            <div class="col-sm-2"></div>

            <div class="col-sm-8"><h3 style="color: #ffffff; text-align: center;"> Events </h3></div>

            <div class="col-sm-2"></div>
        </div>
    </div>
</div>

<div id="hcontent">
    <div class="container">
        <div class="row">
            <div class="col-sm-2"></div>
            <div class="col-sm-8">
                <h2 style="text-decoration: underline; text-align: center"> Our Calander: </h2>


            </div>
            <div class="col-sm-2">
                <a href="?page=event" class="btn btn-primary"> See more events..!! </a></div>

        </div>
    </div>
</div>
<div id="club" data-stellar-background-ratio="0.5">
    <div class="container">
        <div class="row" id="spacer"></div>
        <div class="row">
            <div class="col-sm-2"></div>

            <div class="col-sm-8"><h3 style="color: #ffffff; text-align: center;"> Club </h3></div>

            <div class="col-sm-2"></div>
        </div>
    </div>
</div>

<div id="hcontent">
    <div class="container">
        <div class="row">
            <div class="col-sm-2"></div>
            <div class="col-sm-8">
                <h2 style="text-decoration: underline; text-align: center">Our Clubs!</h2>

            </div>
            <div class="col-sm-2"></div>

        </div>
    </div>
</div>

<div id="ftr">

</div>
<footer id="contact" style="background-color: blu">
    <div class="container">
        <div class="row">
            <div class="col-lg-2">
                <br><br>
                <h5>Copyright &copy; 2015 |
                    <small>Aleka</small>
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
<?php
}?>

<script>
    $.stellar();
</script>
</body>
</html>