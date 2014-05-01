<?php
include_once('calendar/ebenCalendar.php');
include_once('form/form.php');
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
  $basicCalendar = new EbenCalendar();
  $basicCalendar->draw();
  
  $form = new Form();
  $form->printForm();

?>


</body>
</html>
