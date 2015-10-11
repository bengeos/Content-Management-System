<br xmlns="http://www.w3.org/1999/html"><br><br><br><br>
<div class="container">
 <div class="col-sm-12" style="text-align: center"> </div>
    <form method="post" action="" style="position: relative; left:170px">
        <span class="col-sm-1"><label> Category  </label></span>
       <span class="col-sm-5"> <select class="form-control" name="xx">
            <option value=""> Browse </option>
            <option value="school"> School </option>
            <option value="Clubs"> Clubs </option>
        </select> </span>
         <span class="col-sm-2">
         <button class="btn btn-primary pull-left" type="submit">Go!</button>
         </span>
    </form>
</div>
<hr>

<div class="row">
    <?php if(isset($_POST['xx'])){?>
    <div class="col-sm-10 pull-right"> <strong> <?php echo $_POST['xx']; ?> </strong> <br></div>
         xXx,xXx Club
    </div>
</div>
   <?php } else{ ?>
<div class="row">
    <div class="col-sm-1"></div>
    <div class="col-sm-10">
        <div class="col-sm-3" id="cropable" style="padding: 0 10px 10px 0">
         <a href=""> <label class="control-label">Grade One</label>  <img src="images/Big.jpg" width="300" class="img-responsive img-thumbnail" style="clip: rect(0px,60px,200px,0px);"> </a>
        </div>
        <div class="col-sm-3" style="padding: 0 10px 10px 0">
            <img src="images/Big2.jpg" width="300"class="img-responsive img-thumbnail" style="clip: rect(0px,60px,200px,0px);">
        </div>
        <div class="col-sm-3" style="padding: 0 10px 10px 0">
            <img src="images/Stjoseph.jpg" width="300"class="img-responsive img-thumbnail" style="clip: rect(0px,60px,200px,0px);">
        </div>
        <div class="col-sm-3" style="padding: 0 10px 10px 0">
            <img src="images/a.jpg" width="300"class="img-responsive img-thumbnail" style="clip: rect(0px,60px,200px,0px);">
        </div>
        <div class="col-sm-3" style="padding: 0 10px 10px 0">
            <img src="images/b.jpg" width="300"class="img-responsive img-thumbnail" style="clip: rect(0px,60px,200px,0px);">
        </div>
        <div class="col-sm-3" style="padding: 0 10px 10px 0">
            <img src="images/c.jpg" width="300"class="img-responsive img-thumbnail" style="clip: rect(0px,60px,200px,0px);">
        </div>
        <div class="col-sm-3" style="padding: 0 10px 10px 0">
            <img src="images/d.jpg" width="300"class="img-responsive img-thumbnail" style="clip: rect(0px,60px,200px,0px);">
        </div>

    </div>
<?php }?>



</div>
</div>
<script>
    $('.col-sm-3 > img').cropper({
        aspectRatio: 16 / 9,
        autoCropArea: 0.65,
        strict: false,
        guides: false,
        highlight: false,
        dragCrop: true,
        cropBoxMovable: false,
        cropBoxResizable: false
    });
</script>