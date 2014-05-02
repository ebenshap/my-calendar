<?php
include_once('form/form.php');
require_once('config.php');
require_once('eventModel.php');

  $model = new EventModel($dbHost, $dbName, $dbUser, $dbPass);
  $form = new Form($model);
  
  $result = $form->save($_POST);
if(is_numeric($result)){
  echo $result;
}else{
  echo json_encode($result);
}
?>