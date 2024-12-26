 <!-- Main Sidebar Container -->
  <aside class="main-sidebar sidebar-dark-primary elevation-4" style="background-color: #107DC4; !important">
    <!-- Brand Logo -->
    <a href="dashboard.php" class="brand-link" style="font-weight:bold; font-size:22px;">
    

      <span class="brand-text font-weight-light">TomasiKnows | <?php echo ($_SESSION['usertype']) == 1 ? "Admin" : "B.O."?></span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
      <!-- Sidebar user panel (optional) -->
      <div class="user-panel mt-3 pb-3 mb-3 d-flex">
        <div class="image">
          <img src="dist/img/download.png" class="img-circle elevation-2" alt="User Image">
        </div>
        <div class="info">
<?php
$aid=$_SESSION['lssemsaid'];
$sql="SELECT AdminName,Business_name  from  tbladmin where ID=:aid";
$query = $dbh -> prepare($sql);
$query->bindParam(':aid',$aid,PDO::PARAM_STR);
$query->execute();
$results=$query->fetchAll(PDO::FETCH_OBJ);
$cnt=1;
if($query->rowCount() > 0)
{
foreach($results as $row)
{               ?>
          <a href="admin-profile.php" class="d-block">Welcome :    <?php  echo  ($_SESSION['usertype']) == 1 ? $row->AdminName :  $row->Business_name  ?></a>
          <?php $cnt=$cnt+1;}} ?>
        </div>
      </div>

      <nav class="mt-2">

        <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">

            <li class="nav-item has-treeview">
            <a href="#" class="nav-link">
              <i class="nav-icon fas fa-plus-square"></i>
              <p>
              <?php echo ($_SESSION['usertype']) == 1 ? "Admin" : "Business Owner"?> Setting
                <i class="fas fa-angle-left right"></i>
                
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="admin-profile.php" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Profile Update</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="change-password.php" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Change Password</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="logout.php" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Logout</p>
                </a>
              </li>
             </ul>
          </li>   
          <li class="nav-item">
            <a href="dashboard.php" class="nav-link">
              <i class="nav-icon fas fa-tachometer-alt"></i>
              <p>
                Dashboard
               </p>
            </a>
        
          </li>
          
          <li class="nav-item has-treeview d-none">
            <a href="#" class="nav-link">
              <i class="nav-icon fas fa-copy"></i>
              <p>
                Service Category
                <i class="fas fa-angle-left right"></i>
                
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="add-category.php" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Add Category</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="manage-category.php" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Manage Category</p>
                </a>
              </li>
             </ul>
          </li>




           <li class="nav-item has-treeview menu-open  <?php echo ($_SESSION['usertype']) == 1 ? "" : "d-none"?>" >
            <a href="listing.php" class="nav-link">
              <i class="nav-icon fas fa-book"></i>
              <p>
                View Listing
               </p>
            </a>
        
          </li>


          <li class="nav-item has-treeview  <?php echo ($_SESSION['usertype']) == 1 ? "" : "d-none"?>">
            <a href="reg-users.php" class="nav-link">
              <i class="nav-icon fas fa-users"></i>
              <p>
                Reg Users
               </p>
            </a>
        
          </li>

          <li class="nav-item has-treeview ">
            <a href="all-review.php" class="nav-link">
              <i class="nav-icon fas fa-users"></i>
              <p>
                All Review
               </p>
            </a>
        
          </li>
       
<!-- <li class="nav-item has-treeview  <?php echo ($_SESSION['usertype']) == 1 ? "" : "d-none"?>">
            <a href="#" class="nav-link">
              <i class="nav-icon fas fa-users"></i>
              <p>
                Review
                <i class="right fas fa-angle-left"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="all-review.php" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>All Review</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="accepted-review.php" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Accepted Review</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="rejected-review.php" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Rejected Review</p>
                </a>
              </li>
            </ul>
          </li> -->

          <li class="nav-item has-treeview <?php echo ($_SESSION['usertype']) == 1 ? "" : "d-none"?>">
            <a href="#" class="nav-link">
              <i class="nav-icon fas fa-book"></i>
              <p>
                Pages
                <i class="fas fa-angle-left right"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="about-us.php" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>About Us</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="contact-us.php" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Contact Us</p>
                </a>
              </li>
            
            </ul>
          </li>
       <li class="nav-item has-treeview <?php echo ($_SESSION['usertype']) == 1 ? "" : "d-none"?>">
            <a href="#" class="nav-link">
              <i class="nav-icon fas fa-book"></i>
              <p>
                Enquiry
                <i class="fas fa-angle-left right"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="read-enquiry.php" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Read Enquiry</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="unread-enquiry.php" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Unread Enquiry</p>
                </a>
              </li>
            
            </ul>
          </li>

<!-- Reservations -->
<?php
$sql = "SELECT COUNT(*) AS pending_count FROM tbl_reservations WHERE reservation_status = 0 AND listing_id IN (SELECT GROUP_CONCAT(id) FROM tbllisting WHERE UserID = ".$_SESSION['lssemsaid']." )";
$query = $dbh->prepare($sql);
$query->execute();
$result = $query->fetch(PDO::FETCH_ASSOC);

// Get the count of pending reservations
$pendingCount = $result['pending_count'];
?>

<li class="nav-item has-treeview">
  <a href="reservations.php" class="nav-link">
    <i class="nav-icon fas fa-list"></i>
    <p>
      Reservations
      <?php if ($pendingCount > 0): ?>
        <span class="badge badge-danger ml-2"><?php echo $pendingCount; ?></span>
      <?php endif; ?>
    </p>
  </a>
</li>

<!-- <li class="nav-item has-treeview">
  <a href="add_listing.php" class="nav-link">
    <i class="nav-icon fas fa-list"></i>
    <p>
      Add Listing
    </p>
  </a>
</li> -->


<li class="nav-item has-treeview <?php echo ($_SESSION['usertype']) == 1 ? "d-none" : ""?>">
  <a href="#" class="nav-link">
    <i class="nav-icon fas fa-book"></i>
    <p>
      Listings
      <i class="fas fa-angle-left right"></i>
    </p>
  </a>
  <ul class="nav nav-treeview">
    <li class="nav-item">
      <a href="add_listing.php" class="nav-link">
        <i class="far fa-circle nav-icon"></i>
        <p>Add Listing</p>
      </a>
    </li>
    <li class="nav-item">
      <a href="manage_listing.php" class="nav-link">
        <i class="far fa-circle nav-icon"></i>
        <p>Manage Listing</p>
      </a>
    </li>
  
  </ul>
</li>


<li class="nav-item has-treeview <?php echo ($_SESSION['usertype']) == 1 ? "d-none" : ""?>">
  <a href="search.php" class="nav-link <?php echo (in_array(basename(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH)), ['search.php', 'search_listing.php']) ? 'active' : ''); ?>">
    <i class="nav-icon fas fa-list"></i>
    <p>
      Search & View Business
    </p>
  </a>
</li>


<!-- ./ Reservations -->


<li class="nav-item has-treeview <?php echo ($_SESSION['usertype']) != 1 ? "d-none" : ""?>">
            <a href="#" class="nav-link">
              <i class="nav-icon fas fa-history"></i>
              <p>
                Reports
                <i class="fas fa-angle-left right"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="rep_most_book_business.php" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Most Book Business</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="rep_most_book_category.php" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Most Book Category</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="rep_most_book_business_per_category.php" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Most Book Business <br>per Category</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="rep_bookings.php" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Bookings</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="rep_list_of_business.php" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>List of Business</p>
                </a>
              </li>

            </ul>
          </li>



        </ul>
      </nav>
    </div>

  </aside>