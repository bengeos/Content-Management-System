<br><br><br>
<div class="container">
    <!--<div class="row">-->
    <!--    <div class="col-sm-9 well" style="height: 30px; text-align: center"> <strong style="position: relative; bottom: 10px;"> General Topics </strong> </div>-->
    <!--</div>-->

<style>
    #rep{display: none;}
</style>
    <div class="col-sm-9">
        <br>
        <br>
        <table class="tabbable table-bordered table-hoverable" style="width:100%">

            <tr style="background-color: #e3e3e3; text-align: center; height: 30px">
                <td><strong> Sender</strong></td>
                <td><strong> Post</strong></td>
                <td><strong> date</strong></td>
            </tr>
            <tr>
                <td style="white-space:nowrap; width:55px; ">  Solomon Gebreslasie <br> <h5 style="opacity:0.7; position: relative; bottom: 12px;"> Teacher </h5>
                </td>
                <td style="white-space:nowrap; text-align: center;"> <span class="glyphicon glyphicon-check"> <strong> Title ?? </strong> </span>
                <td style="white-space:nowrap; text-align: center "><?php echo date("D-M-Y");?> <br><small><?php echo date("h:m:s");?></small> </td>
            </tr>
            <tr>
                <td style="white-space:nowrap; width:55px; ">  Solomon Gebreslasieccxxx xxx <br> <h5 style="opacity:0.7; position: relative; bottom: 12px;"> Teacher </h5>
                </td>
                <td style="white-space:nowrap; text-align: center"> <span class="glyphicon glyphicon-check"> <strong> What is Title ?? </strong> </span>
                <td style="white-space:nowrap; text-align: center; width:170px ; "><?php echo date("D-M-Y");?> <br><small><?php echo date("h:m:s");?></small> </td>
            </tr>
            <tr id="rep">
                <td> Current User's name</td>
                <td> <form> &nbsp;&nbsp;&nbsp;
                        <label style="text-align: center">Your Message below:</label>
                       <textarea class="form-control" placeholder="Enter Message Here" cols="50"> </textarea>
                    </td>
                <td> <br><input type="submit" class="btn btn- btn-block pull-right" value="Post">  </td></form>
            </tr>
        </table>
        <div class="col-sm-12"><button id="reply" class="btn btn-primary pull-right">Reply</button></div>

    </div>
    <div class="col-sm-3 well">
        <!--      //  <div class="well">-->
        <div style="text-align: center;"><h3>Login..</h3> <hr></div>
        <form method="post" action="">
            <div class="col-sm-10">
                <label>Username</label>
                <input class="form-group" type="text" name="username" id="usen" placeholder="Enter Username">
            </div>
            <div class="col-sm-10">
                <label>Password</label>
                <input class="form-group" type="password" name="password" id="pass" placeholder="Enter Password">
            </div>
            <div class="col-sm-3" style="position: relative; left:40px;">

                <input class="form-group" type="submit" name="login" id="login" value="LogIn ">
            </div>
        </form>
        <div class="col-sm-12">
            <a href=""><h5> <small>Don't have an account? | </small>Sign Up</h5></a>
        </div>
        <!--        </div>-->

    </div>
</div>
<script>
    $( "#reply" ).click(function() {
        $( "#rep" ).show( "slow", function() {});
        $("#hide").show("slow", function() {});
        $("#reply").hide();
    });
    $( "#hide" ).click(function() {
        $( "#reply" ).show( "slow", function() {});
        $("#hide").hide("slow", function() {});
        $("#rep").hide();
    });
</script>