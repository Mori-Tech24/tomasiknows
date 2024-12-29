<?php
// session_start();
//error_reporting(0);
include('includes/dbconnection.php');
if(isset($_POST['review']))
  {
    $name=$_POST['name'];
    $review=$_POST['reviewmessage'];
 
    $reviewtitle=$_POST['reviewtitle'];
    $lid=$_GET['lid'];
    $st = "Review Accept";
 

$sql="insert into tblreview(ListingID,ReviewTitle,Review,Status,Name) values(:lid,:reviewtitle,:review,:status,:name)";
$query=$dbh->prepare($sql);

$query->bindParam(':lid',$lid,PDO::PARAM_STR);
$query->bindParam(':reviewtitle',$reviewtitle,PDO::PARAM_STR);
$query->bindParam(':review',$review,PDO::PARAM_STR);
$query->bindParam(':status',$st,PDO::PARAM_STR);
$query->bindParam(':name',$name,PDO::PARAM_STR);



 $query->execute();
   $LastInsertId=$dbh->lastInsertId();
   if ($LastInsertId>0) {

   echo "<script>alert('Your review was sent successfully!.');</script>";
   echo "<script>window.location.href ='listing-detail-copy.php?lid=$lid'</script>";
   
// echo "<script>window.location.href ='index.php'</script>";
  }
  else
    {
       echo '<script>alert("Something Went Wrong. Please try again")</script>';
    }
}
  


if(isset($_GET['lid'])) {

    $stmtGEtListingname = $dbh->prepare("SELECT ListingTitle FROM tbllisting WHERE ID = :ID");
    $stmtGEtListingname->execute(['ID'=>$_GET['lid']]);

    $ftcstmtGEtListingname = $stmtGEtListingname->fetch();
    $listing_name = $ftcstmtGEtListingname['ListingTitle'];




}

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <!-- Required meta tags -->
   
    <!-- Page Title -->
    <title>TomasiKnows || Listing Detail</title>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700,900" rel="stylesheet">
    <!-- Themify Icon -->
    <link rel="stylesheet" href="css/themify-icons.css">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="css/font-awesome.min.css">
    <!-- line icons -->
    <link rel="stylesheet" href="css/simple-line-icons.css">
    <!-- Swipper Slider -->
    <link rel="stylesheet" href="css/swiper.min.css">
    <!-- Magnific Popup CSS -->
    <link rel="stylesheet" href="css/magnific-popup.css">
    <!-- Hover Effects -->
    <link href="css/set1.css" rel="stylesheet">
    <!-- Main CSS -->
    <link rel="stylesheet" href="css/style.css">
</head>
<style>
        .carousel-item img {
            max-height: 500px; /* Adjust the max height as needed */
            width: auto;  /* Maintain the image's aspect ratio */
            margin: 0 auto; /* Center the image horizontally */
            object-fit: contain; /* Ensure the entire image fits without cropping */
        }
        #logoCarousel {
            max-width: 800px; /* Limit the carousel width */
            margin: 0 auto; /* Center the carousel */
        }
    </style>
<body>
<?php include_once('includes/header.php');?>
    <!--============================= BOOKING =============================-->


    <?php
$lid = intval($_GET['lid']);
$sql = "SELECT * from tbllisting 
        JOIN tblcategory ON tblcategory.ID = tbllisting.Category
        WHERE tbllisting.ID = :lid";
$query = $dbh->prepare($sql);
$query->bindParam(':lid', $lid, PDO::PARAM_STR);
$query->execute();
$results = $query->fetchAll(PDO::FETCH_OBJ);
$cnt = 1;

if ($query->rowCount() > 0) {
    foreach ($results as $row) {
        // Split the Logo field into an array of image paths
        $logos = explode(",", $row->Logo);
        ?>
        <div align="center">
                <?php if (count($logos) > 1): ?>
                <!-- Bootstrap Carousel for multiple images -->
                <div id="logoCarousel" class="carousel slide" data-ride="carousel">
                    <div class="carousel-inner">
                        <?php
                        foreach ($logos as $index => $logo) {
                            $logo = ltrim($logo, 'images/');
                            $activeClass = ($index === 0) ? 'active' : ''; 
                            ?>
                            <div class="carousel-item <?php echo $activeClass; ?>">
                                <img src="images/<?php echo $logo; ?>" alt="Logo Image">
                            </div>
                        <?php } ?>
                    </div>
                    <a class="carousel-control-prev" href="#logoCarousel" role="button" data-slide="prev">
                        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                        <span class="sr-only">Previous</span>
                    </a>
                    <a class="carousel-control-next" href="#logoCarousel" role="button" data-slide="next">
                        <span class="carousel-control-next-icon" aria-hidden="true"></span>
                        <span class="sr-only">Next</span>
                    </a>
                </div>
            <?php else: ?>
                <!-- Single Image if there is only one Logo -->
                <img src="images/<?php echo ltrim($row->Logo, 'images/'); ?>" height="400" width="800" alt="Logo Image">
            <?php endif; ?>
        </div>
        <?php
    }
}
?>



    <?php
$lid=intval($_GET['lid']);
$sql="SELECT * from  tbllisting 
join tblcategory on tblcategory.ID=tbllisting.Category
where tbllisting.ID=:lid";
$query = $dbh -> prepare($sql);
$query-> bindParam(':lid', $lid, PDO::PARAM_STR);
$query->execute();
$results=$query->fetchAll(PDO::FETCH_OBJ);
$cnt=1;
if($query->rowCount() > 0)
{
foreach($results as $row)
{               ?>
    <div align="center" class="d-none">
        <!-- Swiper -->
    
                        <img src="images/<?php echo $row->Logo;?>"  height="500"  width="300">
             
      
    </div>
    <!--//END BOOKING -->
    <!--============================= RESERVE A SEAT =============================-->


    <div class="modal fade" id="modal_reservation" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Book a Reservation</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="reservationForm">
                        <div class="form-group">
                            <label for="reservationDate">Reservation Date <span class="text-danger">*</span></label>
                            <input type="date" class="form-control" id="reservationDate" name="reservationDate" required>
                        </div>
                        <div class="form-group">
                            <label for="reservationTime">Reservation Time <span class="text-danger">*</span></label>
                            <input type="time" class="form-control" id="reservationTime" name="reservationTime" required>
                        </div>
                        <div class="form-group">
                            <label for="reservationPurpose">Purpose <span class="text-danger">*</span></label>
                            <textarea class="form-control" id="reservationPurpose" name="reservationPurpose" rows="3" placeholder="Enter the purpose of your reservation" required></textarea>
                        </div>
                    </form>
                </div>

                <div id="output"></div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-success" id="saveReservation">Save changes</button>
                </div>
            </div>
        </div>
    </div>



    <section class="reserve-block">
        <div class="container">
            <div class="row">
                <div class="col-md-9">
                    <h5><?php echo $row->ListingTitle;?></h5>
                 
                    
                </div>
                <div class="col-md-3">
                    <div class="pull-right">
                        <button type="button" class="btn btn-success" id="button_reservation" name="button_reservation">Book a Reservation</button>
                    </div>
                </div>
            </div>
          
        </div>
    </section>
    <!--//END RESERVE A SEAT -->
    <!--============================= BOOKING DETAILS =============================-->
    <section class="gray-dark booking-details_wrap">
        <div class="container">
            <div class="row">
                <div class="col-md-8 responsive-wrap">
                    <div class="booking-checkbox_wrap">
                       
                        <div class="booking-checkbox">
                            
                            <p><?php echo $row->Description;?>.</p>
                            
                            <p> Category: <?php echo $row->Category;?>.</p>


                            <hr>
                        </div>
                        
                   </div>
                    <div class="booking-checkbox_wrap booking-your-review d-none" id="div_review_one">
                        <h5>Write a Review</h5>
                        <hr> <form enctype="multipart/form-data" method="post">
                        <div class="customer-review_wrap">
                            <div class="customer-img">
                                <img src="images/avatar.jpg" class="img-fluid" alt="#">
                            </div>
                            <div class="customer-content-wrap">
                                
                               
                                <div class="your-comment-wrap">
                                     <label for="form-message">Title for your review*</label>
                                        <input type="text" id="reviewtitle" name="reviewtitle" required="true" class="form-control add-listing_form">
                                  
                                </div>
                                <div class="your-comment-wrap">
                                     <label for="form-message">Your review*</label>
                        <textarea  rows="5" name="reviewmessage" required="true" class="form-control"></textarea>
                            
                                </div>
                        <div class="your-comment-wrap">
                                     <label for="form-message">Your name*</label>
                                         <input type="text" id="name" name="name" required="true" class="form-control add-listing_form">
                                  
                                </div>
                                <div class="row">
                                    
                                    <div class="col-md-4">
                                        <div class="your-rating-btn" style="padding-top: 20px;">
                                           <button class="btn btn-primary" type="submit" name="review" style="background-color: #007bff; color: white; border: none; padding: 10px 20px; font-size: 16px; border-radius: 4px;">Send</button>
                                        </div>
                                    </div>
                                </div>
                            </div></form>
                        </div>
                    </div>
                    <div class="booking-checkbox_wrap my-4" id="div_review_two">
                        <?php
                        $lid=$_GET['lid'];
                                            
                        $ret="SELECT * FROM tblreview WHERE ListingID='$lid' AND STATUS='Review Accept' ORDER BY ID DESC LIMIT 1";

                        $query1 = $dbh -> prepare($ret);
                        // $query1-> bindParam(':lid', $lid, PDO::PARAM_STR);
                        $query1->execute();
                        $results1=$query1->fetchAll(PDO::FETCH_OBJ);
                        $cnt=1;
                        if($query1->rowCount() > 0)
                        {
                        foreach($results1 as $row1)
                        {               ?>   
                                                <hr> 
                                                <div class="customer-review_wrap">
                                                

                                                    <div class="customer-img">
                                                        <img src="images/customer-img1.jpg" class="img-fluid" alt="#">
                                                        
                                                        <p><?php echo $row1->Name;?></p>
                                                        
                                                    </div>
                                                    <div class="customer-content-wrap">
                                                        <div class="customer-content">
                                                            <div class="customer-review">
                                                                <h5 ><?php echo $row1->ReviewTitle;?></h5>
                                                            
                                                                <p><?php echo $row1->DateofReview;?></p>
                                                            </div>
                                                        
                                                        </div>
                                                        <p class="customer-text" ><?php echo $row1->Review;?>. </p>
                                                    
                                                    
                                                    </div>
                                                </div>
                                                <hr>
                                            
                                            <?php $cnt=$cnt+1;}} ?>
                    </div>
                </div>
                <div class="col-md-4 responsive-wrap">
                    <div class="contact-info">
                       
                        <!-- <img src="images/map.jpg" class="img-fluid" alt="#"> -->
                        <div class="map-container">
        <!-- Embed Google Map -->
                            <iframe src="https://www.google.com/maps/embed/v1/place?key=AIzaSyBFw0Qbyq9zTFTd-tUY6dZWTgaQzuU17R8&q=<?php echo $row->State. " ".$row->Address;?>&zoom=10&maptype=roadmap" width="100%" height="100%" style="border:0;" allowfullscreen="" loading="lazy"></iframe>
                        </div>
                        <div class="address">
                            
                            
                            <span class="icon-location-pin"></span><p><strong>Permanent Address: </strong><?php echo $row->Address;?></p>
                            


                           <br>Barangay: <?php echo $row->State;?> <br><br>City: <?php echo $row->City;?> <br><br>Zipcode: <?php echo $row->Zipcode;?>
                        </div>
                        <div class="address">
                            <span class="icon-screen-smartphone"></span>
                            <p> +<?php echo $row->Phone;?></p>
                        </div>
                        <div class="address">
                            <span class="fa fa-envelope"></span>
                            <p><?php echo $row->Email;?></p>
                        </div>
                     
                        
                   
                    </div>
                    
                            </li>
                         
                        </ul>
                      
                    </div><?php $cnt=$cnt+1;}}?>
                </div>
            </div>
        </div>
    </section>
    <!--//END BOOKING DETAILS -->
   <?php include_once('includes/footer.php');?>

    <!-- jQuery, Bootstrap JS. -->
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="js/jquery-3.2.1.min.js"></script>
    <script src="js/popper.min.js"></script>
    <script src="js/bootstrap.min.js"></script>

    <!-- Magnific popup JS -->
    <script src="js/jquery.magnific-popup.js"></script>
    <!-- Swipper Slider JS -->
    <script src="js/swiper.min.js"></script>

    <script>
        $(function() {
            let checkifaaproved = "";
            let userid = "<?php echo $_SESSION['lssemsuid']; ?>";
            let listing_id = "<?php echo $_GET['lid']; ?>";
            let listing_name ="<?php echo $listing_name; ?>";

            if(userid == "") {

                $("#div_review_one").remove();
               // $("#div_review_two").remove();
                
            }else {


                // check if with approved 
                $.ajax({
                    url: "checkifhaveApproved.php",
                    method : "post",
                    dataType : "json",
                    data : {
                        checkifaaproved, userid, listing_id

                    },
                    success :function(response) {
                        // console.log(response);
                        if(response[1] > 0) {
                            $("#div_review_one").remove()
                           
                            // $("#div_review_two").remove();

                        }
                        // else {
                        //     $("#div_review_one").remove();
                    
                        //     // $("#div_review_two").removeClass("d-none");

                        // }
                    }

                });


            }

          

            $(document).on("click","#button_reservation", function() {

                if(userid == "") {
                    $("#modal_reservation").modal("hide");
                    $("#exampleModal").modal("show");
                }else {
                    $("#modal_reservation").modal("show");
                    $("#exampleModal").modal("hide");
                }

        
            });
            $(document).on("click","#saveReservation", function() {
                let act = "book_reservation";
                let reservation_date = $("#reservationDate").val();
                let reservation_time = $("#reservationTime").val();
                let reservation_purpose = $("#reservationPurpose").val();
                
                $.ajax({
                    url : "actions.php",
                    method : "post",
                    dataType : "json",
                    data : {
                        act, userid, listing_id, listing_name, reservation_date, reservation_time, reservation_purpose
                    },
                    beforeSend : function() {
                        $("#saveReservation").addClass("d-none");
                        $("#output").html( responseResult("warning", "Submitting", "Please Wait..."));
                    },
                    success : function(response) {
                        // console.log(response);
                        $("#output").html( responseResult(response[0], response[1], response[2]));
                        $("#saveReservation").removeClass("d-none");

                        if(response[0] == "success") {
                            $("#reservationForm").trigger("reset");
                            setTimeout(function() { 
                                $("#modal_reservation").modal("hide");
                            }, 1000);
                           
                        }

                    }

                });

            });



        });
    </script>
    <script>
        var swiper = new Swiper('.swiper-container', {
            slidesPerView: 3,
            slidesPerGroup: 3,
            loop: true,
            loopFillGroupWithBlank: true,
            pagination: {
                el: '.swiper-pagination',
                clickable: true,
            },
            navigation: {
                nextEl: '.swiper-button-next',
                prevEl: '.swiper-button-prev',
            },
        });
    </script>
    <script>
        if ($('.image-link').length) {
            $('.image-link').magnificPopup({
                type: 'image',
                gallery: {
                    enabled: true
                }
            });
        }
        if ($('.image-link2').length) {
            $('.image-link2').magnificPopup({
                type: 'image',
                gallery: {
                    enabled: true
                }
            });
        }
    </script>

</body>

</html>