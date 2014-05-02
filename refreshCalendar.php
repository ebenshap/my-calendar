<?php
require_once('config.php');
require_once('calendar/eventCalendar.php');
require_once('eventModel.php');
$model = new EventModel($dbHost, $dbName, $dbUser, $dbPass);
  
  $calendar = new EventCalendar(null, $model);
  $calendar->draw();
  
?>