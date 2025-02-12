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

    if (isset($_GET["id"]) && $_GET["id"] == 1) {
        $ticket_user = "Student";
    } elseif (isset($_GET["id"]) && $_GET["id"] == 2) {
        $ticket_user = "Employee";
    }
    


    try {

        $pdoCountQuery = "SELECT * FROM tb_tickets";
        $pdoResult = $pdoConnect->prepare($pdoCountQuery);
        $pdoResult->execute();
        $allTickets = $pdoResult->rowCount();
    
        $pdoCountQuery = "SELECT * FROM tb_tickets WHERE status = 'Pending' && user_type = :user";
        $pdoResult = $pdoConnect->prepare($pdoCountQuery);
        $pdoResult->bindParam(':user', $ticket_user, PDO::PARAM_STR);
        $pdoResult->execute();

        $pendingTickets = $pdoResult->rowCount();

        $pdoCountQuery = "SELECT * FROM tb_tickets WHERE status = 'Processing' && user_type = :user";
        $pdoResult = $pdoConnect->prepare($pdoCountQuery);
        $pdoResult->bindParam(':user', $ticket_user, PDO::PARAM_STR);
        $pdoResult->execute();
        $openedTickets = $pdoResult->rowCount();
    
        $pdoCountQuery = "SELECT * FROM tb_tickets WHERE status = 'Returned' && user_type = :user";
        $pdoResult = $pdoConnect->prepare($pdoCountQuery);
        $pdoResult->bindParam(':user', $ticket_user, PDO::PARAM_STR);
        $pdoResult->execute();
        $returnedTickets = $pdoResult->rowCount();
    
        $pdoCountQuery = "SELECT * FROM tb_tickets WHERE status = 'Completed' && user_type = :user";
        $pdoResult = $pdoConnect->prepare($pdoCountQuery);
        $pdoResult->bindParam(':user', $ticket_user, PDO::PARAM_STR);
        $pdoResult->execute();
        $completedTickets = $pdoResult->rowCount();
    
        $pdoCountQuery = "SELECT * FROM tb_tickets WHERE status = 'Due' && user_type = :user";
        $pdoResult = $pdoConnect->prepare($pdoCountQuery);
        $pdoResult->bindParam(':user', $ticket_user, PDO::PARAM_STR);
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
</head>
<body>
    <div id="wrapper">
        <!-- NAV SIDE  -->
         <?php include 'nav.php'; ?>
        <!-- /. NAV SIDE  -->
        <div id="page-wrapper" >
            <div id="page-inner">
               
                    <div class="col-md-12">
                     <h2>All Tickets</h2>   
                    </div>
               
              
                    <div class="col-md-2 col-sm-6 col-xs-6">           
            <div class="panel panel-back noti-box">
                <span class="icon-box bg-color-yellow set-icon">
                <i class="fa fa-hourglass-half fa-xs" aria-hidden="true"></i>
                </span>
                <div class="text-box" >
                    <p class="main-text"><?php echo $pendingTickets?> Pending</p>
                   <!-- <p class="text-muted">Tickets</p> -->
                </div>
             </div>
            </div>
                    <div class="col-md-2 col-sm-6 col-xs-6">           
            <div class="panel panel-back noti-box">
                <span class="icon-box bg-color-green set-icon">
                <i class="fa fa-envelope-open fa-xs" aria-hidden="true"></i>
                </span>
                <div class="text-box" >
                    <p class="main-text"><?php echo $openedTickets?> Opened </p>
                   <!-- <p class="text-muted">Tickets</p> -->
                </div>
             </div>
            </div>
                    <div class="col-md-2 col-sm-6 col-xs-6">           
            <div class="panel panel-back noti-box">
                <span class="icon-box bg-color-brown set-icon">
                <i class="fa fa-check fa-xs" aria-hidden="true"></i>
                </span>
                <div class="text-box" >
                    <p class="main-text"><?php echo $completedTickets?> Closed</p>
                   <!-- <p class="text-muted">Tickets</p> -->
                </div>
             </div>
            </div>
            
               
                    <div class="col-md-2 col-sm-6 col-xs-6">           
			<div class="panel panel-back noti-box">
                <span class="icon-box bg-color-black set-icon">
                <i class="fa fa-reply fa-xs" aria-hidden="true"></i>
                </span>
                <div class="text-box" >
                    <p class="main-text"><?php echo $returnedTickets?> Returned</p>
                   <!-- <p class="text-muted">Tickets</p> -->
                </div>
             </div>
		     </div>
                    <div class="col-md-2 col-sm-6 col-xs-6">           
			<div class="panel panel-back noti-box">
                <span class="icon-box bg-color-blue set-icon">
                <i class="fa fa-upload fa-xs" aria-hidden="true"></i>
                </span>
                <div class="text-box" >
                    <p class="main-text">0 Transferred</p>
                   <!-- <p class="text-muted">Tickets</p> -->
                </div>
             </div>
		     </div>
		
                 <!-- /. ROW  -->
                 <div class="row">
                <div class="col-md-12">
                    <!-- Advanced Tables -->
                    <div class="panel panel-default">
                        
                        <div class="panel-body-ticket">
                            <div class="table-responsive">
                                <table class="table table-striped table-bordered table-hover" id="dataTables-example">
                                    <thead>
                                        <tr>
                                            <th>Ticket ID</th>
                                            <th>Status</th>
                                            <th>Employee</th>
                                            <th>Date Submitted</th>
                                            <th>Name</th>
                                            <th>Duration</th>
                                            <th>Issue</th>
                                      
                                            <th>Details</th>
                                        </tr>
                                    </thead>
                                    <tbody>
            <?php

                $pdoQuery = "SELECT * FROM tb_tickets WHERE user_type = :user";
                $pdoResult = $pdoConnect->prepare($pdoQuery);
                $pdoResult->bindParam(':user', $ticket_user, PDO::PARAM_STR);
                $pdoExec = $pdoResult->execute();
                while ($row = $pdoResult->fetch(PDO::FETCH_ASSOC)) {
                    $statusClass = ($row['status'] === 'Completed') ? 'success' :
                                  (($row['status'] === 'Pending') ? 'danger' :
                                  (($row['status'] === 'Transferred') ? 'info' :
                                  (($row['status'] === 'Processing') ? 'warning' : '')));
                
                    extract($row);
                    echo "<tr class='odd gradeX . $statusClass . '>";
                    echo "<td>$ticket_id</td>"; 
                    echo "<td>$status</td>";
                    echo "<td>$employee</td>";
                    echo "<td>$created_date</td>";
                    echo "<td>$full_name</td>";
                    echo "<td>$issue</td>";
                    echo "<td>$description</td>";
                   
                    echo "<td>

                        <div class='panel-body-ticket'>
                            <button class='btn btn-primary btn-xs' data-toggle='modal' data-target='#myModal'>
                                View Details
                            </button>
                        </div>
                        
                        </td>";

                    echo "</tr>";
                }
            ?>
                                        
                                        
                          <div class="modal fade" id="myModal">
    <div class="modal-dialog2">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                	<h4 class="modal-title">Overview</h4>

            </div>
            <div class="container"></div>
            <div class="modal-body">
                                          <div class="row">
                                <div class="col-md-4">
                                    <h3>User Information</h3>
                                    <form role="form">
                                       
                                      
                                        <div class="form-group">
                                            <label>Full Name‎ ‎ ‎ ‎ ‎ </label>
                                            <input class="form-control" />
                                            <br><br>
                                        </div>
                                      
                                        <div class="form-group">
                                            <label>Student ID‎ ‎ ‎ </label>
                                            <input class="form-control" />
                                         <br><br>
                                        </div>
                                       
                                        <div class="form-group">
                                            <label>College‎ ‎ ‎ ‎ ‎ ‎ ‎ ‎ ‎ </label>
                                            <input class="form-control" />
                                         <br><br>
                                        </div>
                                       
                                        <div class="form-group">
                                            <label>Course‎ ‎ ‎ ‎ ‎ ‎ ‎ ‎ ‎ ‎ </label>
                                            
                                            <input class="form-control" />
                                            <br><br>
                                        </div>
                                        
                                        <div class="form-group">
                                            <label>Year‎ ‎ ‎ ‎ ‎ ‎ ‎ ‎ ‎ ‎ ‎ ‎ ‎ ‎ </label>
                                            <input class="form-control" />
                                            <br><br>
                                        </div>
                                        
                                    </form>      
                                </div>
                                
                                <div class="col-md-4">
                                    <h3>Ticket Details</h3>
                                    
                                    <form role="form">
                                    <div class="form-group">
                                            <label>Ticket ID‎ ‎ ‎ ‎ ‎ ‎ ‎ ‎ ‎ ‎ ‎ ‎ ‎ ‎ ‎ ‎ ‎ ‎ </label>
                                            <input class="form-control" />
                                            <br><br>
                                        </div>
                                    <div class="form-group">
                                            <label>Issue/Problem  ‎ ‎ ‎ ‎ ‎ ‎ ‎ </label>
                                            <input class="form-control" />
                                            <br><br>
                                        </div>
                                        <div class="form-group">
                                            <label>Description ‎ ‎ ‎ ‎ ‎ ‎ ‎ ‎ ‎ ‎ ‎ ‎ ‎ </label>
                                            <input class="form-control" />
                                            <br><br>
                                        </div>
                                        <div class="form-group">
                                            <label>Screenshot ‎ ‎ ‎ ‎ ‎ ‎ ‎ ‎ ‎ ‎ ‎ ‎ ‎ </label>
                                            <input class="form-control" />
                                            <br><br>
                                        </div>
                                    </form>
                                </div>

                                <div class="col-md-4">
                                    <h3>Closing Summary</h3>
                                    <form role="form">
                                       
                                      
                                        <div class="form-group">
                                            <label>Employee Name </label>
                                            <input class="form-control" />
                                            <br><br>
                                        </div>
                                      
                                        <div class="form-group">
                                            <label>Opened‎ ‎ ‎ ‎ ‎ ‎ ‎ ‎ ‎ ‎ ‎ ‎ ‎ ‎ ‎ </label>
                                            <input class="form-control" />
                                         <br><br>
                                        </div>
                                       
                                        <div class="form-group">
                                            <label>Closed‎ ‎ ‎ ‎ ‎ ‎ ‎ ‎ ‎ ‎ ‎ ‎ ‎ ‎ ‎ ‎ ‎ </label>
                                            <input class="form-control" />
                                         <br><br>
                                        </div>
                                       
                                        <div class="form-group">
                                            <label>Duration‎ ‎ ‎ ‎ ‎ ‎ ‎ ‎ ‎ ‎ ‎ ‎ ‎ </label>
                                            
                                            <input class="form-control" />
                                            <br><br>
                                        </div>
                                        
                                        <div class="form-group">
                                            <label>Resolution‎ ‎ ‎ ‎ ‎ ‎ ‎ ‎ ‎ ‎ </label>
                                            <input class="form-control" />
                                            <br><br>
                                        </div>
                                        
                                    </form>      
                                </div>
                            </div>
                     

            </div>
        </div>
        
    </div>
</div>
                              </div>
                  
<div class="modal fade" id="myModalA">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                	<h4 class="modal-title">Pending Ticket</h4>

            </div>
            <div class="container"></div>
            <div class="modal-body">
                                          <div class="row">
                                <div class="col-md-6">
                                    <h3>User Information</h3>
                                    <form role="form">
                                       
                                      
                                        <div class="form-group">
                                            <label>Full Name‎ ‎ ‎ ‎ ‎ </label>
                                            <input class="form-control" />
                                            <br><br>
                                        </div>
                                      
                                        <div class="form-group">
                                            <label>Student ID‎ ‎ ‎ </label>
                                            <input class="form-control" />
                                         <br><br>
                                        </div>
                                       
                                        <div class="form-group">
                                            <label>College‎ ‎ ‎ ‎ ‎ ‎ ‎ ‎ ‎ </label>
                                            <input class="form-control" />
                                         <br><br>
                                        </div>
                                       
                                        <div class="form-group">
                                            <label>Course‎ ‎ ‎ ‎ ‎ ‎ ‎ ‎ ‎ ‎ </label>
                                            
                                            <input class="form-control" />
                                            <br><br>
                                        </div>
                                        
                                        <div class="form-group">
                                            <label>Year‎ ‎ ‎ ‎ ‎ ‎ ‎ ‎ ‎ ‎ ‎ ‎ ‎ ‎ </label>
                                            <input class="form-control" />
                                            <br><br>
                                        </div>
                                        
                                    </form>      
                                </div>
                                
                                <div class="col-md-6">
                                    <h3>Ticket Details</h3>
                                    
                                    <form role="form">
                                    <div class="form-group">
                                            <label>Ticket ID‎ ‎ ‎ ‎ ‎ ‎ ‎ ‎ ‎ ‎ ‎ ‎ ‎ ‎ ‎ ‎ ‎ ‎ </label>
                                            <input class="form-control" />
                                            <br><br>
                                        </div>
                                    <div class="form-group">
                                            <label>Issue/Problem  ‎ ‎ ‎ ‎ ‎ ‎ ‎ </label>
                                            <input class="form-control" />
                                            <br><br>
                                        </div>
                                        <div class="form-group">
                                            <label>Description ‎ ‎ ‎ ‎ ‎ ‎ ‎ ‎ ‎ ‎ ‎ ‎ ‎ </label>
                                            <input class="form-control" />
                                            <br><br>
                                        </div>
                                        <div class="form-group">
                                            <label>Screenshot ‎ ‎ ‎ ‎ ‎ ‎ ‎ ‎ ‎ ‎ ‎ ‎ ‎ </label>
                                            <input class="form-control" />
                                            <br><br>
                                        </div>
                                    </form>
                                </div>
                            </div>
                            <div class="modal-footer">	<a href="#" data-dismiss="modal" class="btn">Back</a>
                            <a data-toggle="modal" href="#myModal4" class="btn btn-primary">Open Ticket</a>

            </div>
        </div>
        
    </div>
</div>
                              </div>
                              <div class="modal fade" id="myModal4">
    <div class="modal-dialog3">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                	<h4 class="modal-title">Open Ticket</h4>

            </div>
            <div class="container"></div>
            <div class="modal-body">Confirm opening ticket</div>
            <div class="modal-footer">	<a href="#" data-dismiss="modal" class="btn">Cancel</a>
	<a href="#" class="btn btn-primary">Confirm</a>

            </div>
        </div>
    </div>
</div>
                              <!-- form general example -->
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            
                        </div>
                    </div>
                    <!--End Advanced Tables -->
                </div>
            </div>
                 <hr />
               <!-- /. ROW  -->
               <div class="row">                     
                      
               <div class="col-md-4 col-sm-4 col-xs-4">                     
           <div class="panel panel-default">
               <div class="panel-heading">
                   Age
               </div>
               <div class="panel-body">
                   <div id="morris-donut-chart"></div>
               </div>
           </div>            
       </div>
       <div class="col-md-4 col-sm-4 col-xs-4">                     
           <div class="panel panel-default">
               <div class="panel-heading">
                   Gender
               </div>
               <div class="panel-body">
                   <div id="morris-donut-chart2"></div>
               </div>
           </div>            
       </div>
       <div class="col-md-4 col-sm-4 col-xs-4">                     
           <div class="panel panel-default">
               <div class="panel-heading">
                   Campus
               </div>
               <div class="panel-body">
                   <div id="morris-donut-chart3"></div>
               </div>
           </div>            
       </div>
      
      
  </div>
    </div>
             <!-- /. PAGE INNER  -->
            </div>
         <!-- /. PAGE WRAPPER  -->
        </div>
     <!-- /. WRAPPER  -->
    <!-- SCRIPTS -AT THE BOTTOM TO REDUCE THE LOAD TIME-->
  
    <!-- JQUERY SCRIPTS -->
    <script src="assets/js/jquery-1.10.2.js"></script>
      <!-- BOOTSTRAP SCRIPTS -->
    <script src="assets/js/bootstrap.min.js"></script>
    <!-- METISMENU SCRIPTS -->
    <script src="assets/js/jquery.metisMenu.js"></script>
    <!-- MORRIS CHART SCRIPTS -->
    <script src="assets/js/morris/raphael-2.1.0.min.js"></script>
    <script src="assets/js/morris/morris.js"></script>

<script>
    $(document).ready(function() {
      // Function to create a donut chart
      function createDonutChart(elementId, dataUrl) {
        $.getJSON(dataUrl, function(data) {
          if (data.error) {
            console.error('Error fetching data:', data.error);
          } else {
            Morris.Donut({
              element: elementId,
              data: data
            });
          }
        }).fail(function(jqxhr, textStatus, error) {
          console.error('Request Failed: ' + textStatus + ', ' + error);
        });
      }

      // Create charts with dynamic data
      createDonutChart('morris-donut-chart', 'action/data.php?chart=age-groups&id=<?php echo $_GET['id']?>');
      createDonutChart('morris-donut-chart2', 'action/data.php?chart=genders&id=<?php echo $_GET['id']?>');
      createDonutChart('morris-donut-chart3', 'action/data.php?chart=locations&id=<?php echo $_GET['id']?>');
    });
</script>

 <!-- DATA TABLE SCRIPTS -->
<script> 


</script>
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

