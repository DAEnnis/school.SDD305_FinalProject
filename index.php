	<!DOCTYPE html>
	<html>
	<head>
		<!-- Mobile Specific Meta -->
		<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
		<!-- meta character set -->
		<meta charset="UTF-8">
		<!-- Site Title -->
		<title>Consent Form</title>

		<link href="https://fonts.googleapis.com/css?family=Poppins:300,500,600" rel="stylesheet">
		<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.1/css/bootstrap.min.css" integrity="sha384-WskhaSGFgHYWDcbwN70/dfYBj47jz9qbsMId/iRN3ewGhXQFZCSftd1LZCfmhktB" crossorigin="anonymous">
		<link rel="stylesheet" href="https://xdsoft.net/scripts/datetimepicker/build/jquery.datetimepicker.min.css">

		<script src="https://code.jquery.com/jquery-2.2.4.min.js" integrity="sha256-BbhdlvQf/xTY9gja0Dq3HiwQF8LaCRTXxZKRutelT44=" crossorigin="anonymous"></script>
		<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
		<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.1/js/bootstrap.min.js" integrity="sha384-smHYKdLADwkXOn1EmN1qk/HfnUcbVRZyYmZ4qpPea6sjB/pTJ0euyQp0Mk8ck+5T" crossorigin="anonymous"></script>
		<script src="https://xdsoft.net/scripts/datetimepicker/build/jquery.datetimepicker.full.min.js"></script>
		<style>

			body {
				height: 100vh;
				background: #f7f7f7;
			}

			.date-group {
				padding: 4px;
			}

			.date-group img{
				height: 23px;
			}

			.xdsoft_datetimepicker .xdsoft_timepicker {
    		width: 75px;
			}

			.required {
				color: red;
				padding-left: 5px;
    		float: right;
			}

			.logo {
				width: 250px;
				padding: 15px;
			}

			.content-block {
				box-shadow: 2px 2px 1px 0px rgba(0,0,0,0.45);
				border-top: 4px solid #17a2b8;
			}

			.right-btn {
				padding-top: 30px;
			}

			.red {
				color: red;
			}

			.info {
				color: gray;
			}

			.bg-light {
				background-color: white !important;
				box-shadow: 1px 1px 1px 0px rgba(0,0,0,0.45);
			}

			.success-header {
				display: none;
			}

		</style>
	</head>

	<body>
		<nav class="navbar-expand-lg navbar-light bg-light">
	    <div class="container-fluid">
	      <div class="navbar-header">
					<img src="http://www.wilmu.edu/images/logos/wilmu-logo-color-350x92.svg" class="logo">
	        <a class="float-right right-btn" href="admin.php">View Appointments</a>
	      </div>
	    </div>
	  </nav>
		<br>
		<div class="container">
			<div class="row d-flex justify-content-center">
				<div class="col-lg-6">
					<div class="card content-block">
						<div class="card-body">
							<div class="info-form">
								<h2>Participant Consent Form</h2>
								<p>Wilmington University research department wants an application that would enable
	potential subjects of a research to consent to participate in a research using semi-
	structured interviews.</p>
								<small class="red">* All fields are Required</small>
							</div>
							<div class="success-header">
								<h2>Participant Consent Response</h2>
							</div>
							<br>
							<?php
								include("helpers/consent.php");
								$cf = new consentForm();
								$slots = $cf->getFixedSlots();
								$submitted = isset($_POST['details-submitted']);
								$success = false;
								if ($submitted){
									$cf->participate();
									$success = $cf->isSuccessFullySubmitted();
								}

								if(!$success){
							?>
							<form action="<?php echo $_SERVER['REQUEST_URI']; ?>" method="POST">
									<div class="form-group">
										<label for="mail">
											<span class="required">*</span>Email address</label>
										<input type="email" class="form-control" name="mail" id="mail" value="<?php echo isset($_POST['mail']) ? $_POST['mail'] : '' ?>">
									</div>
									<div class="form-group">
										<label for="phone"><span class="required">*</span>Phone</label>
										<input type="text" class="form-control" name="phone" id="phone" value="<?php echo  isset($_POST['phone']) ? $_POST['phone'] : '' ?>">
									</div>
									<div class="form-group">
										<label for="name"><span class="required">*</span>Full Name</label>
										<input type="text" class="form-control" name="name" id="name" value="<?php echo isset($_POST['name']) ? $_POST['name'] : '' ?>">
									</div>
									<div class="form-group">
										<label for="slot"><span class="required">*</span>Preferred Interview Date & Time</label>
										<div class="input-group">
											<input type="text" class="form-control" name="slot" id="slot" value="<?php echo  isset($_POST['slot']) ? $_POST['slot'] : '' ?>">
										  <div class="input-group-append">
										    <span class="input-group-text date-group">
													<img src="https://cdn4.iconfinder.com/data/icons/social-communication/142/calendar-128.png"></img>
												</span>
										  </div>
										</div>
										<small class="info">Click on date and then time to select the slot.</small>

									</div>
									<div class="text-center">
										<input class="check-agr" type="checkbox" checked/ >
										<span>I agree to participate in study conducted by Wilmington University.</span>
										<br>
										<br>
										<button type="submit" name="details-submitted" class="btn btn-info">Consent to Participate in Study</button>
									</div>
								</form>

								<?php
							} else {
								?>
								<script>
										$(".success-header").show();
										$(".info-form").hide();
								</script>
								<?php
										}
								 ?>

						</div>
					</div>
				</div>
			</div>
		</div>

		<script>
			let slots = [
				'09:00',
				'10:00',
				'11:00',
				'12:00',
				'13:00',
				'14:00',
				'15:00',
				'16:00',
				'17:00',
				'18:00'
			];
		 	let data = <?php echo json_encode($slots); ?>;
			function getDate (date) {
				let d = new Date(date),
						month = '' + (d.getMonth() + 1),
						day = '' + d.getDate(),
						year = d.getFullYear();

			 if (month.length < 2) month = '0' + month;
			 if (day.length < 2) day = '0' + day;

			 return [year, month, day].join('-');
			}
			function getAvailableSlotsInDate(date){
				let formattedDate = getDate(date);
				let slotsCopy = JSON.parse(JSON.stringify(slots));
				data.map((record) => {
					if(record.InterviewDate == formattedDate) {
							let index = slotsCopy.indexOf(record.InterviewTime.substr(0, 5));
							if(index > -1){
								slotsCopy.splice(index, 1);
							}
					}
				});
				return slotsCopy;
			}
			$(".check-agr").change(() => {
				$(".btn").attr("disabled",!$(".check-agr").is(":checked"))
			});
			let currentDate = new Date();
			currentDate.setMonth(currentDate.getMonth() + 1);

			$("#slot").datetimepicker({
				minDate:0,
				maxDate: currentDate,
				allowTimes:slots,
				format: 'd F Y H:i',
				step: 60,
				roundTime:'floor',
				onSelectDate: function(ct) {
					let slots = getAvailableSlotsInDate(ct);
					let firstSlot = slots[0];
					if(firstSlot) {
						$("#slot").val('');
					}
	        this.setOptions({ allowTimes: slots});
    		},
				onShow : function (ct) {
					let disabledDates = [];
					let slotDates = {};
					data.map((record) => {
						slotDates[record.InterviewDate] = slotDates[record.InterviewDate] || [];
						slotDates[record.InterviewDate].push(record.InterviewTime);
					});
					for(let date in slotDates){
						let slotsCopy = JSON.parse(JSON.stringify(slots));
						slotDates[date].map((value) => {
							let index = slotsCopy.indexOf(value.substr(0, 5));
							if(index > -1){
								slotsCopy.splice(index, 1);
							}
							if(!slotsCopy.length){
								disabledDates.push(date.replace(/-/g, '/'));
							}
						});
					}

					this.setOptions({
						allowTimes: getAvailableSlotsInDate(ct),
						disabledDates: disabledDates
					});
				}
			});
		</script>
	</body>

	</html>
