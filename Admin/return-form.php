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
</head>
<body>
    <div id="wrapper">
        <!-- NAV SIDE  -->
         <?php include 'nav.php'; ?>
        <!-- /. NAV SIDE  -->
        <div id="page-wrapper" >
            <div id="page-inner">
                <div class="row">
                    <div class="col-md-12">
                     <h2>Return Ticket</h2>   
                        <h5>Welcome Jhon Deo , Love to see you back. </h5>
                    </div>
                </div>              
                 <!-- /. ROW  -->
                  <hr />
                  <div class="row">
                                <div class="col-md-12">
<form role="form" method="post" action="ticket-resolution.php?id=<?php echo $_GET['id']?>&form=return">
                                       
                        <div class="form-group">
                                <div class="col-md-2">
                                    <label>Details:‎ ‎ ‎ ‎ ‎ ‎ ‎ ‎ </label>
                                </div>
                            <div class="col-md-8">
                            <textarea name="resolution" id="detailsTextarea" class="form-controlb" rows="5" required></textarea>
                                <br>
                            <select id="fileSelector" class="form-control">
                                <option value="">Select a file</option>

                            </select>    
                                <br>
                            </div>
                        </div>
                                    <div class="col-md-9">

                                    </div>

                <div class="col-md-2">
                    <a data-toggle="modal" href="#myModalReturn" class="btn btn-primary">Return</a>
                        <div class="modal fade" id="myModalReturn">
                            <div class="modal-dialog3">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                                        <h4 class="modal-title">Return Ticket</h4>

                                    </div>
                                    <div class="container"></div>
                                    <div class="modal-body">Confirm Returning ticket</div>
                                    <div class="modal-footer">	
                                        <button data-dismiss="modal" class="btn">Cancel</button>
                                        <button data-toggle="modal"  class="btn btn-primary">Confirm</button>
                            
                                    </div>
                                </div>
                            </div>
                        </div>
                </div>
</form>     
<script>
    // Function to populate the dropdown with files
    function populateDropdown() {
        fetch('http://localhost/HelpHub/list_files.php')
            .then(response => {
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                return response.json();
            })
            .then(fileList => {
                const dropdown = document.getElementById('fileSelector');
                fileList.forEach(fileName => {
                    const option = document.createElement('option');
                    option.value = `http://localhost/HelpHub/Templates/${fileName}`;
                    option.textContent = fileName;
                    dropdown.appendChild(option);
                });
            })
            .catch(error => {
                console.error('There was a problem with the fetch operation:', error);
            });
    }

    // Call the function to populate the dropdown on page load
    window.onload = populateDropdown;

    document.getElementById('fileSelector').addEventListener('change', function() {
        const selectedUrl = this.value;

        if (selectedUrl) {
            // Fetch the file content from the selected URL
            fetch(selectedUrl)
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Network response was not ok');
                    }
                    return response.text();
                })
                .then(data => {
                    document.getElementById('detailsTextarea').value = data;
                })
                .catch(error => {
                    console.error('There was a problem with the fetch operation:', error);
                    document.getElementById('detailsTextarea').value = "Failed to load content. Please try again.";
                });
        } else {
            // Clear the textarea if no file is selected
            document.getElementById('detailsTextarea').value = '';
        }
    });
</script>

                                </div>
                                
                                
                            </div>
                           
                            
      
                   

<br>
<br>
                 
        </div>
    
           </div>   
                 <!-- /. ROW  -->
                         
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
     <!-- MORRIS CHART SCRIPTS -->
     
     <script src="assets/js/morris/raphael-2.1.0.min.js"></script>
    <script src="assets/js/morris/morris.js"></script>
   
      <!-- CUSTOM SCRIPTS -->
    <script src="assets/js/custom.js"></script>
    
   
</body>
</html>




