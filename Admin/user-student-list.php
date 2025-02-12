<?php
include_once("../connection/conn.php");
$pdoConnect = connection();

session_start(); // Start the session

// Check if the session variable is set
if (!isset($_SESSION["admin_number"])) {
    header("Location: ../index.php");
    exit(); // Prevent further execution after redirection
} else {
    $id = $_SESSION["admin_number"];

    $pdoUserQuery = "SELECT * FROM mis_employees WHERE admin_number = :number";
    $pdoResult = $pdoConnect->prepare($pdoUserQuery);
    $pdoResult->bindParam(':number', $id);
    $pdoResult->execute();

    $Data = $pdoResult->fetch(PDO::FETCH_ASSOC);

    if ($Data) {
        $Name = $Data['f_name'];
        $Position = $Data['position'];
        $U_T = $Data['user_type'];

        $nameParts = explode(' ', $Name);
        $firstName = $nameParts[0];
    } else {
        // Handle the case where no results are found
        echo "No student found with the given student number.";
    }

try {

    $pdoCountQuery = "SELECT * FROM tb_tickets";
    $pdoResult = $pdoConnect->prepare($pdoCountQuery);
    $pdoResult->execute();
    $allTickets = $pdoResult->rowCount();

    $pdoCountQuery = "SELECT * FROM tb_tickets WHERE status = 'Pending'";
    $pdoResult = $pdoConnect->prepare($pdoCountQuery);
    $pdoResult->execute();
    $pendingTickets = $pdoResult->rowCount();

    $pdoCountQuery = "SELECT * FROM tb_tickets WHERE status = 'Returned'";
    $pdoResult = $pdoConnect->prepare($pdoCountQuery);
    $pdoResult->execute();
    $returnedTickets = $pdoResult->rowCount();

    $pdoCountQuery = "SELECT * FROM tb_tickets WHERE status = 'Completed'";
    $pdoResult = $pdoConnect->prepare($pdoCountQuery);
    $pdoResult->execute();
    $completedTickets = $pdoResult->rowCount();

    $pdoCountQuery = "SELECT * FROM tb_tickets WHERE status = 'Due'";
    $pdoResult = $pdoConnect->prepare($pdoCountQuery);
    $pdoResult->execute();
    $dueTickets = $pdoResult->rowCount();

} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}

}

?>

<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>DHVSU MIS - HelpHub</title>
  
	<!-- BOOTSTRAP STYLES-->
    <link href="assets/css/bootstrap.css" rel="stylesheet" />
     <!-- FONTAWESOME STYLES-->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
     <!-- MORRIS CHART STYLES-->
    <link href="assets/js/morris/morris-0.4.3.min.css" rel="stylesheet" />
        <!-- CUSTOM STYLES-->
    <link href="assets/css/custom.css" rel="stylesheet" />
     <!-- GOOGLE FONTS-->
   <link href='http://fonts.googleapis.com/css?family=Open+Sans' rel='stylesheet' type='text/css' />

   <style>
        .modal-dialog {
            max-width: 80%; /* Adjust the modal width as needed */
        }
        .modal-content {
            overflow: hidden; /* Ensure the content doesn't overflow */
        }
        .modal-body img {
            width: 100%;
            height: auto; /* Maintain aspect ratio */
            max-height: 70vh; /* Adjust the maximum height as needed */
            object-fit: contain; /* Ensure the image is contained within the modal */
        }
    .img-avatar{
        width:45px;
        height:45px;
        object-fit:cover;
        object-position:center center;
        border-radius:100%;
        margin: 0;
    }
</style>
</head>
<body>
    <div id="wrapper">
        <!-- NAV SIDE  -->
         <?php include 'nav.php'; ?> 
        <!-- /. NAV SIDE  -->
        <div id="page-wrapper" >
            <div id="page-inner">
                <div class="row">
                    <div class="col-md-9">
                     <h2>User List</h2>
                     <h5>This page shows all the Student and Employee Accounts.</h5>             
                    </div>
                    <div class="card-tools col-md-3">
			<a href="action\add-user.php" class="btn btn-flat btn-primary" style="float: right;"><span class="fas fa-plus"></span>  Create New</a>
		</div>
                </div>
                 <!-- /. ROW  -->
                 <div class="row">
                <div class="col-md-12">
                    <!-- Advanced Tables -->
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            Student's Account
                        </div>
                        <div class="panel-body-ticket">
                            <div class="table-responsive">

<?php
$user = "Student";

$pdoQuery = "SELECT * FROM tb_user WHERE user_type = :user ORDER BY user_id ASC";
$pdoResult = $pdoConnect->prepare($pdoQuery);
$pdoResult->bindParam(':user', $user, PDO::PARAM_STR);
$pdoExec = $pdoResult->execute();

?>
                                <table class="table table-striped table-bordered table-hover" id="dataTables-example">
                                    <thead>
                                        <tr>
                                            <th>User Id</th>
                                            <th>Avatar</th>
                                            <th>Full Name</th>
                                            <th>Campus</th>
                                            <th>Year & Section</th>
                                            <th>Gender</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
            <?php
                while ($row = $pdoResult->fetch(PDO::FETCH_ASSOC)){
                    extract($row);
                    $P_PBase64 = base64_encode($profile_picture);
                    $date = new DateTime($birthday);
                    $formattedDate = $date->format('F j, Y')
            ?>
                    <tr class='odd gradeX'>
                    <td><?php echo htmlspecialchars($user_id); ?></td>
                    <td class="py-1 px-2 align-middle"><img src="data:image/jpeg;base64,<?php echo $P_PBase64?>" class="img-avatar img-thumbnail p-0 border-2" alt="user_avatar"></td>
                    <td><?php echo htmlspecialchars($name); ?></td>
                    <td><?php echo htmlspecialchars($campus); ?></td>
                    <td><?php echo htmlspecialchars($year_section); ?></td>
                    <td><?php echo htmlspecialchars($sex); ?></td>    
                    <td align="center" class="py-1 px-2 align-middle">
	<div class="panel-body-ticket btn-group" >
	<button type="button" class="btn btn-flat btn-default btn-sm dropdown-toggle dropdown-icon" data-toggle="dropdown">
	    Action
	<span class="sr-only">Toggle Dropdown</span>
				                  </button>
				                  <div class="dropdown-menu" role="menu">
				                    <a class="dropdown-item" data-toggle='modal' data-target='#myModal<?php echo $user_id; ?>' style="cursor:pointer"><span class="fa fa-edit text-primary"></span> Edit</a>
				                    <div class="dropdown-divider"></div>
                                    <a class="dropdown-item delete_data" href="javascript:void(0)" data-id="11" onclick="Delete()"><span class="fa fa-trash text-danger"></span> Delete</a>
    <script>
        function Delete() {
            alert("The Delete function is currently not usable.");
        }
    </script>
				                  </div>
								  </div>
							</td>

<div class="modal fade" id="myModal<?php echo $user_id; ?>" >
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                	<h4 class="modal-title">User Information</h4>

            </div>
            <div class="container"></div>
            <div class="modal-body">
                <div class="row">

                                <div class="col-md-3">
                                    <div class="text-center">
                                        <img src="data:image/jpeg;base64,<?php echo $P_PBase64?>" class="avatar img-circle img-thumbnail" alt="avatar">
                                        <h3><?php echo $name?></h3>
                                        <h5 style="text-transform: uppercase;"><?php echo $user_type?></h5>
                                    </div>
                                </div>
                                <div class="col-md-9 personal-info">
                                    <div> <h3>PERSONAL INFORMATION</h3>
                                    </div>
                                    <form class="form-horizontal" role="form">
                                        <div class="form-group">
                                            <label class="col-lg-3 control-label">STUDENT NUMBER</label>
                                            <div class="col-lg-8">
                                                <input class="form-control" type="text" value="<?php echo $user_id?>" disabled>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-lg-3 control-label">STUDENT EMAIL</label>
                                            <div class="col-lg-8">
                                                <input class="form-control" type="text" value="<?php echo $email_address?>" disabled>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-lg-3 control-label">GENDER</label>
                                            <div class="col-lg-8">
                                                <input class="form-control" type="text" value="<?php echo $sex?>" disabled>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-lg-3 control-label">BIRTHDAY</label>
                                            <div class="col-lg-8">
                                                <input class="form-control" type="text" value="<?php echo $formattedDate?>" disabled>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-lg-3 control-label">AGE</label>
                                            <div class="col-lg-8">
                                                <input class="form-control" type="text" value="<?php echo $age?>" disabled>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-lg-3 control-label">CAMPUS </label>
                                            <div class="col-lg-8">
                                                <input class="form-control" type="text" value="<?php echo $campus?>" disabled>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-lg-3 control-label">DEPARTMENT </label>
                                            <div class="col-lg-8">
                                                <input class="form-control" type="text" value="<?php echo $department?>" disabled>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-lg-3 control-label">COURSE </label>
                                            <div class="col-lg-8">
                                                <input class="form-control" type="text" value="<?php echo $course?>" disabled>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-lg-3 control-label">YEAR AND SECTION </label>
                                            <div class="col-lg-8">
                                                <input class="form-control" type="text" value="<?php echo $year_section?>" disabled>
                                            </div>
                                        </div>
                                </div>
                                
                            
                </div>
            </div>
        </div>
    </div>
</div>
        
                          </div>
        <?php
        }
        ?>
                                        

                                        
                                    </tbody>
                                </table>
                            </div>
                            
                        </div>
                    </div>
                    </div>
                    </div>
                    </div>
                    <!--End Advanced Tables -->
                            
                        </div>
                    </div>
                </div>
            </div>
                 <hr />
               
    </div>
             <!-- /. PAGE INNER  -->
            </div>
         <!-- /. PAGE WRAPPER  -->
        </div>
     <!-- /. WRAPPER  -->
    <!-- SCRIPTS -AT THE BOTOM TO REDUCE THE LOAD TIME-->
  
    <!-- JQUERY SCRIPTS -->
    <script src="assets/js/jquery-1.10.2.js"></script>
      <!-- BOOTSTRAP SCRIPTS -->
    <script src="assets/js/bootstrap.min.js"></script>
    <!-- METISMENU SCRIPTS -->
    <script src="assets/js/jquery.metisMenu.js"></script>
    <!-- DATA TABLE SCRIPTS -->
    <script src="assets/js/dataTables/jquery.dataTables.js"></script>
    <script src="assets/js/dataTables/dataTables.bootstrap.js"></script>
        <script>
            $(document).ready(function () {
                $('#dataTables-example').dataTable();
            });
    </script>
      <!-- CUSTOM SCRIPTS -->
    <script src="assets/js/custom.js"></script>
    
    
   
</body>
</html>

