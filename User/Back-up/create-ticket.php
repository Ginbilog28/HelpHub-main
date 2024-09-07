<?php
//include_once("../connection/conn.php");
//$pdoConnect = connection();

//session_start(); // Start the session

// Check if the session variable is set
//if (!isset($_SESSION["student_number"])) {
//    header("Location: ../index.php");
//   exit(); // Prevent further execution after redirection
//} else {
//    $id = $_SESSION["student_number"];
//
//    $pdoUserQuery = "SELECT * FROM student_user WHERE student_number = :number";
//    $pdoResult = $pdoConnect->prepare($pdoUserQuery);
//    $pdoResult->bindParam(':number', $id);
//    $pdoResult->execute();

//    $Data = $pdoResult->fetch(PDO::FETCH_ASSOC);

//    if ($Data) {
//        $Email_Add = $Data['email_address'];
//        $Name = $Data['name'];
//        $Department = $Data['department'];
//        $Course = $Data['course'];
//        $Y_S = $Data['year_section'];
//        $P_P = $Data['profile_picture'];
//        $Sex = $Data['sex'];

//        $nameParts = explode(' ', $Name);
//        $firstName = $nameParts[0];
//    } else {
        // Handle the case where no results are found
//        echo "No student found with the given student number.";
//    }
//}
?>

<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
      <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>USER</title>
	<!-- BOOTSTRAP STYLES-->
    <link href="../assets/css/bootstrap.css" rel="stylesheet" />
  <!-- FONTAWESOME STYLES-->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
     <!-- MORRIS CHART STYLES-->
    <link href="../assets/js/morris/morris-0.4.3.min.css" rel="stylesheet" />
        <!-- CUSTOM STYLES-->
    <link href="../assets/css/custom.css" rel="stylesheet" />
     <!-- GOOGLE FONTS-->
   <link href='http://fonts.googleapis.com/css?family=Open+Sans' rel='stylesheet' type='text/css' />
   <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
</head>

<body>
    <div id="wrapper">
        <nav class="navbar navbar-default navbar-cls-top " role="navigation" style="margin-bottom: 0">
            <div class="navbar-header">
                <button type="button" class="navbar-toggle" data-toggle="collapse"
                    data-target=".sidebar-collapse">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a class="navbar-brand" href="dashboard.php">USER</a>
            </div>
            <div style="color: white;
            padding: 15px 50px 5px 50px;
            float: right;
            font-size: 16px;"> Last access : <?php echo date('d F Y')?> &nbsp; 
            <div class="btn-group nav-link">
              <button type="button" class="btn btn-rounded badge badge-light dropdown-toggle dropdown-icon" data-toggle="dropdown">
                <span class="ml-3"><?php echo $Name?></span>
            <span class="fa fa-caret-down">
            <span class="sr-only">Toggle Dropdown</span>
          </button>
          <div class="dropdown-menu" role="menu">
            <a class="dropdown-item" href="profile.php"><span class="fa fa-user"></span> MY ACCOUNT</a>
            <hr style="margin-top: 5px; margin-bottom: 5px;">
            <a class="dropdown-item" href="http://localhost/sms//classes/Login.php?f=logout"><span class="fa fa-sign-out"></span> LOG OUT </a>
          </div>
        </nav>
        <!-- /. NAV TOP  -->
        <nav class="navbar-default navbar-side" role="navigation">
            <div class="sidebar-collapse">
                <ul class="nav" id="main-menu">
                    <li class="text-center">
                        <img src="assets/img/find_user.png" class="user-image img-responsive" />
                    </li>
				
					
                    <li>
                        <a href="dashboard.php"><i class="bx bxs-dashboard fa" style="font-size:36px;color:rgb(255, 255, 255)"></i>  DASHBOARD </a>
                    </li>

                    <li>
                        <a href="profile.php"><i class="bx bx-user" style="font-size:36px;color:rgb(255, 255, 255)"></i> PROFILE </a>
                        </li>

                        <li>
                            <a class="active-menu" href="ticket.php">
                            <i class="fa fa-ticket" style="font-size: 36px; color: rgb(255, 255, 255)"></i> TICKET <span class="fa arrow"></span>
                        </a>
                        <ul class="nav nav-second-level">

                            <li>
                                <a href="create-ticket.php"><i class="fa fa-plus"></i>CREATE NEW TICKET</a>
                            </li>
                          <li>
                              <a href="ticket-pending.php"><i class="fa fa-refresh"></i>PENDING TICKET</a>
                          </li>

                          <li>
                              <a href="ticket-inprocess.php"><i class="fa fa-spinner"></i> IN PROCESS</a>
                          </li>

                          <li>
                            <a href="ticket-returned.php"><i class="fa fa-undo"></i> RETURNED TICKET</a>
                            </li>

                            <li>
                            <a href="ticket-finished.php"><i class="fa fa-check"></i> COMPLETE TICKET</a>
                            </li>
                      </ul>
                    </li> 
                    <li>
                        <a href="history.php"><i class="bx bx-history" style="font-size:36px"></i> HISTORY </a>
                    </li>
						   <li  >
                            <a href="downloadableform.php"><i class="fa fa-download" style="font-size:36px"></i> DOWNLOADABLE FORM </a>
                    </li>	
                    <li>
                        <a href="about.php"><i class="fa fa-question-circle" style="font-size:36px"></i> ABOUT </a>
                    </li>
                </ul>
               
            </div>
            
        </nav>  
        <!-- /. NAV SIDE  -->
        <div id="page-wrapper" >
            <div id="page-inner">
                <div class="row">
                    <div class="col-md-12">
                     <h2> CREATE NEW TICKET</h2>   
                    </div>
                </div>


                <div class="container-center">
                    <div class="modal-header">
                        <img src="../assets/pic/head.png" alt="Technical support for DHVSU students">  
                <div class="container-create">
                    <form class="issue-form">
                        <div class="form-group">
                            <label for="category">ISSUE</label>
                            <select id="category" name="category" class="form-control">
                                <option value="">SELECT PROBLEM</option>
                                <option value="DHVSU EMAIL">DHVSU EMAIL</option>
                                <option value="DHVSU PORTAL">DHVSU PORTAL</option>
                                <option value="DHVSU SMS">DHVSU SMS</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="issue-description">Issue Description</label>
                            <textarea id="issue-description" name="issue-description" class="form-control"></textarea>
                        </div>
                        <div class="form-group">
                            <label for="" class="control-label">Upload Screenshot</label>
                            <div class="custom-file">
                                <input type="file" class="custom-file-input" id="coverFile" name="img" onchange="displayImg2(this,$(this))">
                                <label class="custom-file-label" for="coverFile"></label>
                            </div>
                        </div>
                        <div class="form-group d-flex justify-content-center">
                            <img src="../assets/pic/pics.jpg" alt="" id="cimg2" class="img-fluid img-thumbnail">
                        </div>
                
                <div class="modal-body"> 
                    <div class="letter">
                        <main>
                            <style>
                                .modal-body p {
                                    line-height: 1.5;
                                    margin-bottom: 10px;
                                }

                                .modal-body h1 {
                                    line-height: 1.2;
                                    margin-bottom: 15px;
                                }
                                .modal-footer {
                                    display: flex;
                                    justify-content: flex-end;
                                    padding: 0px;
                                    border-top: 1px solid #ccc;
                                }
                            </style>
                            <p>By completing this form, I allow Don Honorio Ventura State University, 
                            specifically the Management Information Systems Office, to gather, store, and handle the information 
                            I provide regarding my SMS/LMS/@dhvsu Google account concerns.</p>

                            <label>
                        <input type="radio" name="consent" value="yes"> Yes
                    </label>
                    <label>
                        <input type="radio" name="consent" value="no"> No
                    </label>
                        </main>
                    </div>

                    </form>

                    </div>
            <div class="modal-footer">
                <a href="receive-ticket-response.php"><button type="button" class="btn btn-primary">SUBMIT</button></a>
        </div>
        </div>
        </div>
                 <!-- /. ROW  -->
                 
                        
             <!-- /. PAGE INNER  -->
            </div>
         <!-- /. PAGE WRAPPER  -->
        </div>
    <!-- /. WRAPPER -->
    <!-- SCRIPTS - AT THE BOTTOM TO REDUCE THE LOAD TIME -->
    <!-- JQUERY SCRIPTS -->
    <script src="../assets/js/jquery-1.10.2.js"></script>
    <!-- BOOTSTRAP SCRIPTS -->
    <script src="../assets/js/bootstrap.min.js"></script>
    <!-- METISMENU SCRIPTS -->
    <script src="../assets/js/jquery.metisMenu.js"></script>
    <!-- DATA TABLE SCRIPTS -->
    <script src="../assets/js/dataTables/jquery.dataTables.js"></script>
    <script src="../ssets/js/dataTables/dataTables.bootstrap.js"></script>
    <!-- CUSTOM SCRIPTS -->
    <script src="../assets/js/custom.js"></script>
    <!--preview-->
    <script>
    function displayImg2(input,_this) {
	    if (input.files && input.files[0]) {
	        var reader = new FileReader();
	        reader.onload = function (e) {
	        	$('#cimg2').attr('src', e.target.result);
	        }

	        reader.readAsDataURL(input.files[0]);
	    }
	}
</script>
</body>
</html>
