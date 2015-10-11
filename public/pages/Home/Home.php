<br><br><br><br>
<div class="container">
    <div class="col-sm-12" style="text-align: center"> </div>
    <form method="post" action="" style="position: relative; left:170px">
        <span class="col-sm-1"><label> Category  </label></span>
        <span class="col-sm-5">
            <input class="form-control" type="text">
        </span>
         <span class="col-sm-2">
         <button class="btn btn-primary pull-left" type="submit">Go!</button>
         </span>
    </form>
</div>
<hr>

</div>

<div class="row">
    <div class="col-sm-1"></div>
    <div class="col-sm-10">

        <?php
        $grades = $My_DB->get_grades()['result'];
        foreach($grades as $grade){
            include 'pages\include_tags\Grade_View.php';
        }
        ?>
    </div>
</div>