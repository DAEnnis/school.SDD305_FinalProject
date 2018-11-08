<?php

/*
  * Enable error reporting
 */
ini_set( 'display_errors', 0 );
error_reporting( E_ALL );


class Consentform {

    /**
     * Configuration for database connection
     *
    */
    private $host       = "localhost";
    private $username   = "phpuser";
    private $password   = "phpuser";
    private $dbname     = "participantconsent";
    private $dsn = 'mysql:host=localhost;dbname=participantconsent;';

    private $options    = array(
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
                  );


    private $errors = array();
    private $success = false;
    private $connection;

    function __construct() {
      try {
       $this->connection = new PDO($this->dsn, $this->username, $this->password, $this->options);
      } catch(PDOException $error) {
       echo $error->getMessage();
      }
    }

    function getAllConsents() {
      $sql = "SELECT * FROM consents ORDER By InterviewDate";
      if($stmt = $this->connection->prepare($sql)){
        $stmt->execute();
    		return $stmt->fetchAll();
      }
    }

    function getFixedSlots() {
      $sql = "SELECT InterviewDate, InterviewTime FROM consents";
      if($stmt = $this->connection->prepare($sql)){
        $stmt->execute();
    		return $stmt->fetchAll();
      }
    }

    function participate () {
      if (isset($_POST['details-submitted'])) {
          // Validate the details
          $this->validate($_POST['name'], $_POST['phone'], $_POST['mail'], $_POST['slot']);

          // display form error if it exist
          if (is_array($this->errors) && count($this->errors) > 0) {
              echo "<div class='alert alert-danger' role='alert'>";
              echo '“Please make sure you provide values for the following fields:”';
              foreach ($this->errors as $error) {
                  echo '<div style="padding-left: 30px;">';
                  echo $error . '<br/>';
                  echo '</div>';
              }
              echo '</div>';
          }

          // Check the errors
          if (count($this->errors) === 0) {
            $this->submit($_POST['name'], $_POST['phone'], $_POST['mail'], $_POST['slot']);
          }
      }
    }

    function validate($name, $phone, $email, $slot) {

      // Email
      if (empty($email)) {
          array_push($this->errors, 'Email');
      }

      // $phone
      if (empty($phone)) {
          array_push($this->errors, 'Phone');
      }

      // name
      if (empty($name)) {
          array_push($this->errors, 'Full Name');
      }

      // Date & Time
      if (empty($slot)) {
          array_push($this->errors, 'Date & Time');
      }


      // Validate Email
      if (!empty($phone) && !empty($name) && !empty($email) && !filter_var($email, FILTER_SANITIZE_EMAIL)) {
          array_push($this->errors, 'Email is not valid');
      }
    }

    function validateEmail ($email) {
      $sql = "SELECT COUNT(*) FROM consents WHERE Email = :email";
      if($stmt = $this->connection->prepare($sql)){
          $stmt->bindParam(':email', $email, PDO::PARAM_STR);
          if($stmt->execute()){
              if($stmt->fetchColumn() == 1){
                return false;
              }
          }
      }
      return true;
    }

    function validateSlot ($slot) {

      $date = date('Y-m-d',strtotime($slot));
      $time = date('H:i:s',strtotime($slot));

      $sql = "SELECT Email FROM consents WHERE InterviewDate = :date AND InterviewTime = :time";
      if($stmt = $this->connection->prepare($sql)){
          $stmt->bindParam(':date', $date, PDO::PARAM_STR);
          $stmt->bindParam(':time', $time, PDO::PARAM_STR);
          if($stmt->execute()){
              if($stmt->rowCount() > 0){
                return false;
              }
          }
      }
      return true;
    }

    function submit($name, $phone, $email, $slot) {
      try {
        if(!$this->validateEmail($email)){
          echo "<div class='alert alert-danger' role='alert'>";
          echo '“An user with same email already participated.”';
          echo "</div>";
        }
        else if(!$this->validateSlot($slot)){
          echo "<div class='alert alert-danger' role='alert'>";
          echo '“The slot is reserved for other user.”';
          echo "</div>";
        }
        else {
            $date = date('Y-m-d',strtotime($slot));
            $time = date('H:i:s',strtotime($slot));
            $new_user =[
                  "ID"           => "",
                  "Email"        => $email,
                  "InterviewDate" => $date,
                  "InterviewTime"   => $time,
                  "ParticipantName"   => $name,
                  "Phone"        => $phone,
                  "Status"        => "",
                  "DateConsented"   => date('Y-m-d H:i:s')
                ];

            $sql = sprintf(
                "INSERT INTO %s (%s) values (%s)",
                "consents",
                implode(", ", array_keys($new_user)),
                ":" . implode(", :", array_keys($new_user))
            );

            $statement = $this->connection->prepare($sql);
            $statement->execute($new_user);

            $from_name = "wilmington";
            $from_address = "admin@wilmington.com";
            $endTime = $this->getNextHour($slot); // one hour
            $subject = "Invitation to participate in study";
            $description = "Kindly Attend";
            $location = "Wilmington University";
            $this->sendIcalEvent($from_name, $from_address, $name, $email, $slot, $endTime, $subject, $description, $location);
            $this->success = true;

            echo "<div class='alert alert-success' role='alert'>";
            echo '“Thanks ';
            echo $name;
            echo ' for participating,';
            echo ' Your slot has been confirmed at ';
            echo date('l,d F Y h:i A', strtotime($slot));
            echo ".”.</div>";

            echo "<a href='index.php'>Back to consent Form.</a>";

          }

      }
      catch(PDOException $error) {
        echo "<div class='alert alert-danger' role='alert'>";
        echo "Failed to submit, Please try again.";
        echo "</div>";
        echo $error->getMessage();
      }
    }

    function sendIcalEvent($from_name, $from_address, $to_name, $to_address, $startTime, $endTime, $subject, $description, $location) {
        $domain = 'exchangecore.com';

        //Create Email Headers
        $mime_boundary = "----Meeting Booking----".MD5(TIME());

        $headers = "From: ".$from_name." <".$from_address.">\n";
        $headers .= "Reply-To: ".$from_name." <".$from_address.">\n";
        $headers .= "MIME-Version: 1.0\n";
        $headers .= "Content-Type: multipart/alternative; boundary=\"$mime_boundary\"\n";
        $headers .= "Content-class: urn:content-classes:calendarmessage\n";

        //Create Email Body (HTML)
        $message = "--$mime_boundary\r\n";
        $message .= "Content-Type: text/html; charset=UTF-8\n";
        $message .= "Content-Transfer-Encoding: 8bit\n\n";
        $message .= "<html>\n";
        $message .= "<body>\n";
        $message .= '<p>Dear '.$to_name.',</p>';
        $message .= '<p>'.$description.'</p>';
        $message .= "</body>\n";
        $message .= "</html>\n";
        $message .= "--$mime_boundary\r\n";

        $ical = 'BEGIN:VCALENDAR' . "\r\n" .
        'PRODID:-//Microsoft Corporation//Outlook 10.0 MIMEDIR//EN' . "\r\n" .
        'VERSION:2.0' . "\r\n" .
        'METHOD:REQUEST' . "\r\n" .
        'BEGIN:VTIMEZONE' . "\r\n" .
        'TZID:Eastern Time' . "\r\n" .
        'BEGIN:STANDARD' . "\r\n" .
        'DTSTART:20091101T020000' . "\r\n" .
        'RRULE:FREQ=YEARLY;INTERVAL=1;BYDAY=1SU;BYMONTH=11' . "\r\n" .
        'TZOFFSETFROM:-0400' . "\r\n" .
        'TZOFFSETTO:-0500' . "\r\n" .
        'TZNAME:EST' . "\r\n" .
        'END:STANDARD' . "\r\n" .
        'BEGIN:DAYLIGHT' . "\r\n" .
        'DTSTART:20090301T020000' . "\r\n" .
        'RRULE:FREQ=YEARLY;INTERVAL=1;BYDAY=2SU;BYMONTH=3' . "\r\n" .
        'TZOFFSETFROM:-0500' . "\r\n" .
        'TZOFFSETTO:-0400' . "\r\n" .
        'TZNAME:EDST' . "\r\n" .
        'END:DAYLIGHT' . "\r\n" .
        'END:VTIMEZONE' . "\r\n" .
        'BEGIN:VEVENT' . "\r\n" .
        'ORGANIZER;CN="'.$from_name.'":MAILTO:'.$from_address. "\r\n" .
        'ATTENDEE;CN="'.$to_name.'";ROLE=REQ-PARTICIPANT;RSVP=TRUE:MAILTO:'.$to_address. "\r\n" .
        'LAST-MODIFIED:' . date("Ymd\TGis") . "\r\n" .
        'UID:'.date("Ymd\TGis", strtotime($startTime)).rand()."@".$domain."\r\n" .
        'DTSTAMP:'.date("Ymd\TGis"). "\r\n" .
        'DTSTART;TZID="Eastern Time":'.date("Ymd\THis", strtotime($startTime)). "\r\n" .
        'DTEND;TZID="Eastern Time":'.date("Ymd\THis", strtotime($endTime)). "\r\n" .
        'TRANSP:OPAQUE'. "\r\n" .
        'SEQUENCE:1'. "\r\n" .
        'SUMMARY:' . $subject . "\r\n" .
        'LOCATION:' . $location . "\r\n" .
        'CLASS:PUBLIC'. "\r\n" .
        'PRIORITY:5'. "\r\n" .
        'BEGIN:VALARM' . "\r\n" .
        'TRIGGER:-PT15M' . "\r\n" .
        'ACTION:DISPLAY' . "\r\n" .
        'DESCRIPTION:Reminder' . "\r\n" .
        'END:VALARM' . "\r\n" .
        'END:VEVENT'. "\r\n" .
        'END:VCALENDAR'. "\r\n";
        $message .= 'Content-Type: text/calendar;name="meeting.ics";method=REQUEST'."\n";
        $message .= "Content-Transfer-Encoding: 8bit\n\n";
        $message .= $ical;

        if (mail($to_address, $subject, $message, $headers)){
          echo '.An email with a calender invite has been sent to your mail.';
        }else{
          echo "<div class='alert alert-danger' role='alert'>";
          echo 'Unable to send an email invite, Please configure your mail server';
          echo '</div>';
        }
    }

    function isSuccessFullySubmitted () {
      return $this->success;
    }

    function getNextHour ($slot) {
      $date = strtotime($slot) + 1*60*60;
      $date = date('Y/m/d H:i', $date);
      return $date;
    }

    function getDateInFullFormat ($slot) {
      $date = date('Y-m-d H:i:s', strtotime($slot));
      return $date;
    }

}
 ?>
