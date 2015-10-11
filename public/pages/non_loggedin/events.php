<br><br><br>
<script type="text/javascript" src="https://addthisevent.com/libs/1.6.0/ate.min.js"></script>
<div class="row">
    <div class="col-sm-12" style="text-align: center">
    <form method="post" action="" style="position: relative; left:200px">
        <span class="col-sm-1"><label> Select Year  </label></span>
       <span class="col-sm-3"> <select class="form-control" name="xx">
               <option value=""> Select </option>
               <option value="2015"> 2015 </option>
               <option value="2016"> 2016 </option>
           </select> </span>
         <span class="col-sm-2">
         <button class="btn btn-primary pull-left" type="submit">Go!</button>
         </span>
    </form> </div> </div>
<hr>
<br>
<div class="row">
    <?php if(isset($_POST['xx'])){?>
    <div class="col-sm-10 pull-right"> <strong> <?php echo $_POST['xx']; ?> </strong> <br></div>
    xXx,xXx Club
</div>
</div>
<?php } else{ ?>
    <div class="col-sm-2">

    </div>

    <div class="col-sm-8">

        <div class="col-sm-2 img-circle" style="background-color: #005580; color: #ffffff;">

            <h2 style="text-align: center"> 25 </h2>
            <h5 style="text-align:center;">March</h5>

        </div>
        <div class="col-sm-8" style="border-bottom: 1px solid black">
            <h5  style="opacity: 0.7">  <small> Thursday </small> </h5>
            <p><lead> Parents day: Parents are supposed to come to :P their kids..!! </lead></p>

        </div>
        <div class="col-sm-2" style="height: 40px">
            <div title="Add to Calendar" class="addthisevent" data-direct="google">
                <small>Add to Calendar</small>
                <span class="start">06/18/2015 09:00 AM</span>
                <span class="end">06/18/2015 11:00 AM</span>
                <span class="timezone">Africa/Nairobi</span>
                <span class="title">Summary of the event</span>
                <span class="description">Description of the event</span>
                <span class="location">Location of the event</span>
                <span class="date_format">MM/DD/YYYY</span>
            </div>
        </div>

    </div>
    <div class="col-sm-2"></div>

</div>
<br><br><br>
<div class="row">

    <div class="col-sm-2">

    </div>

    <div class="col-sm-8">

        <div class="col-sm-2 img-circle" style="background-color: #005580; color: #ffffff;">

            <h2 style="text-align: center"> 25 </h2>
            <h5 style="text-align:center;">March</h5>

        </div>
        <div class="col-sm-8" style="border-bottom: 1px solid black">
            <h5  style="opacity: 0.7">  <small> Thursday </small> </h5>
            <p><lead> Parents day: Parents are supposed to come to :P their kids..!! </lead></p>
            <small style="opacity: 0.7"> Hosted by: Sport Club</small>

        </div>
        <div class="col-sm-2" style="height: 40px">
            <div title="Add to Calendar" class="addthisevent" data-direct="google">
                <small>Add to Calendar</small>
                <span class="start">06/18/2015 09:00 AM</span>
                <span class="end">06/18/2015 11:00 AM</span>
                <span class="timezone">Africa/Nairobi</span>
                <span class="title">Parents day</span>
                <span class="description"> Parents are supposed to come to :P their kids..!</span>
                <span class="location">Saint Joseph basketball court</span>
                <span class="date_format">MM/DD/YYYY</span>
            </div>
        </div>

    </div>
    <div class="col-sm-2"></div>
  <?php }?>
</div>