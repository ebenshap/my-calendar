<?php
include_once('calendar/eventCalendar.php');
include_once('form/form.php');
require_once('eventModel.php');
require_once('config.php')
?><!DOCTYPE html>
<html>
<head>
  <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1"> 
  <link rel="stylesheet" type="text/css" href="css/style.css">
   <script src="javascript/jquery-1.11.0.js"></script>
   <script src="javascript/jquery-ui-1.10.4.js"></script>
   <script src="javascript/form.js"></script>
 </head>
<body>
<div id='header'>
  <h1>Enter An Event</h1>
</div>
<?php
  $model = new EventModel($dbHost, $dbName, $dbUser, $dbPass);
  
  $calendar = new EventCalendar(null, $model);
  echo '<div id="calendar">';
  $calendar->draw();
  echo '</div>';
  $form = new Form($model);
  $form->printForm($_POST);

?>


</body>
</html>
