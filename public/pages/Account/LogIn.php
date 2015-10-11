<hr>
<div class="container">
    <div class="col-sm-12" style="text-align: center"> </div>
    <span class="col-sm-12"><label><h3>User Login Form</h3><hr> </label>

    </span>

    <div class="col-sm-4">
        <?php
        if(isset($res)){
            echo "<h4 style='color: red'> Invalid User name or password used</h4>";
        }
        ?>
        <form method="GET" action="">
            <input hidden="hidden" type="text" name="page" value="u_login">
            <label class="label control-label">User Name</label>
            <input class="form-control" type="text" name="user_name"><br>
            <label class="label control-label">User Password</label>
            <input class="form-control" type="password" name="user_pass"><br>
            <div class="col-sm-12">
                <input class="form-control" type="submit" value="Log in">
            </div>
        </form>
    </div>
</div>
<hr>

</div>

<div class="row">
    <div class="col-sm-1"></div>
    <div class="col-sm-10">


    </div>
</div>