<?php

	// Initialize the session
	session_start();

  if(!isset($_SESSION['user'])) {
     $validuser = 'admin';
     $validpw = 'admin';
     if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        if(isset($_POST['login-check'])){
         if (!empty($_POST['username'] && $_POST['password'])) {
             if (strtolower($_POST['username'] == $validuser) && $_POST['password'] == $validpw) {
                 $_SESSION['user'] = $_POST['username'];
             }else{
               $error = true;
             }
         }
       }
     }
  }else{
    if(isset($_POST['logout'])){
      $_SESSION['user'] = null;
   }
  }

?>

<!DOCTYPE html>
<html>
<head>
  <!-- Mobile Specific Meta -->
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <!-- meta character set -->
  <meta charset="UTF-8">
  <!-- Site Title -->
  <title>Admin</title>

  <link href="https://fonts.googleapis.com/css?family=Poppins:300,500,600" rel="stylesheet">
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.1/css/bootstrap.min.css" integrity="sha384-WskhaSGFgHYWDcbwN70/dfYBj47jz9qbsMId/iRN3ewGhXQFZCSftd1LZCfmhktB" crossorigin="anonymous">

  <script src="https://code.jquery.com/jquery-2.2.4.min.js" integrity="sha256-BbhdlvQf/xTY9gja0Dq3HiwQF8LaCRTXxZKRutelT44=" crossorigin="anonymous"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.1/js/bootstrap.min.js" integrity="sha384-smHYKdLADwkXOn1EmN1qk/HfnUcbVRZyYmZ4qpPea6sjB/pTJ0euyQp0Mk8ck+5T" crossorigin="anonymous"></script>

  <style>

    .modal.in {
      display: block;
    }
    .logo {
      width: 250px;
      padding: 15px;
    }
    .right-btn {
      padding-top: 30px;
    }
    .inline {
      display:inline;
    }
    .content-block {
      box-shadow: 2px 2px 1px 0px rgba(0,0,0,0.45);
      border-top: 4px solid #17a2b8;
    }
  </style>
</head>

<body>
  <?php
    if(!isset($_SESSION['user'])) {
  ?>
  <style>
    body {
      background: #f1f0f0;
    }
    .logo {
      width: 150px;
      padding: 10px;
    }
    .login-info{
      display: inline-block;
      width: 200px;
    }
  </style>
  <div class="modal fade show in" id="loginModal" tabindex="-1" role="dialog" aria-hidden="true">
   <div class="modal-dialog" role="document">
  	 <div class="modal-content">
  		 <div class="modal-header">
  			 <h5 class="modal-title">
           <img src="http://www.wilmu.edu/images/logos/wilmu-logo-color-350x92.svg" class="logo">
           Admin Login</h5>
  			 <button type="button" class="close" data-dismiss="modal" aria-label="Close">
  				 <span aria-hidden="true">&times;</span>
  			 </button>
  		 </div>
  		 <div class="modal-body">
  			 <div>
  				 <div class="card-body">
              <?php
                  if(isset($error)){
              ?>
  					       <div class="alert alert-danger" role="alert">
                     Username or Password is wrong.
                   </div>
               <?php
                }
               ?>

  					 <form action="<?php echo $_SERVER['REQUEST_URI']; ?>" method="post" id="loginForm">
  							 <div class="form-group">
  									 <label>Username</label>
  									 <input type="text" name="username" class="form-control" required>
  									 <span class="form-text text-muted"></span>
  							 </div>
  							 <div class="form-group">
  									 <label>Password</label>
  									 <input type="password" name="password" class="form-control" required>
  									 <span class="form-text text-muted"></span>
  							 </div>
  							 <div class="form-group">
  									 <input type="submit" name="login-check" class="btn btn-info" value="Login">

                     <div class="login-info float-right">
                          <p class="text-right">
                            <button class="btn btn-link" type="button" data-toggle="collapse" data-target="#collapseExample" aria-expanded="false" aria-controls="collapseExample">
                              Need Admin Login ?
                            </button>
                          </p>
                          <div class="collapse" id="collapseExample">
                          <div class="card card-body">
                              Username - admin <br>
                              Password - admin
                          </div>
                          </div>
                     </div>

  							 </div>
  					 </form>

  				 </div>
  			 </div>
  		 </div>
  	 </div>
   </div>
  </div>

  <?php
      } else {
  ?>
  <style>
    body {
      height: 100vh;
      background: #f7f7f7;
    }
    .bg-light {
      background-color: white !important;
      box-shadow: 1px 1px 1px 0px rgba(0,0,0,0.45);
    }
  </style>
  <nav class="navbar-expand-lg navbar-light bg-light">
    <div class="container-fluid">
      <div class="navbar-header">
        <img src="http://www.wilmu.edu/images/logos/wilmu-logo-color-350x92.svg" class="logo">
        <form class="inline" action="<?php echo $_SERVER['REQUEST_URI']; ?>" method="post">
          <input type="submit" name="logout" class="float-right right-btn btn btn-link" value="Logout">
        </form>
        <a class="float-right right-btn" href="index.php">Home</a>
      </div>
    </div>
  </nav>
  <br>
  <div class="container">
    <div class="row d-flex justify-content-center">
      <div class="col-lg-12">
        <div class="card">
          <?php

              function escape($html) {
                return htmlspecialchars($html, ENT_QUOTES | ENT_SUBSTITUTE, "UTF-8");
              }

              function formatDate($date, $time) {
                return date('l,d F Y h:i A', strtotime("$date $time"));
              }

              include("helpers/consent.php");
              $cf = new consentForm();
              $results = $cf->getAllConsents();
          ?>

          <div class="card-body content-block results-list">

            <h4>
                <span>Registered Consents</span>
              <?php
                  if(count($results) > 0) {
                    echo "<b>(" . count($results) .  ")</b>";
                  }
              ?>
            </h4>
            <br>
            <br>

            <?php
                if ($results && $results.length) {
            ?>

            		<table class="table table-bordered table-hover">
            			<thead>
            				<tr class="table-primary">
                      <th scope="col">#</th>
                      <th>Full Name</th>
                      <th>Email</th>
                      <th>Phone</th>
            					<th>Slot</th>
            				</tr>
            			</thead>
            			<tbody>
            	<?php $index = 1; foreach ($results as $row) { ?>
            			<tr>
                    <td><?php echo $index; ?></td>
            				<td><?php echo escape($row['ParticipantName']); ?></td>
            				<td><a href="mailto:<?php echo escape($row["Email"]); ?>"><?php echo escape($row["Email"]); ?></a></td>
                    <td><?php echo escape($row["Phone"]); ?></td>
            				<td><?php echo formatDate($row["InterviewDate"], $row["InterviewTime"]); ?></td>
            			</tr>
            		<?php $index++; } ?>
            			</tbody>
            	</table>
            	<?php } else { ?>
            		<blockquote>No consents registered yet.</blockquote>
            	<?php } ?>

          </div>
        </div>
      </div>
    </div>
  </div>
  <?php
    }
  ?>
</body>

</html>
