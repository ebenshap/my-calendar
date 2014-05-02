<?php
require_once('eventModel.php');
require_once('helpers/validator.php');
class Form{
  
  private $model;
  private $validator;
  private $fieldMeta = array(
    
    'formDate'=>array( 'validations'=>'notEmpty_dateSlash', 'human'=>'date'),
    'formTitle'=>array( 'validations'=>'notEmpty', 'human'=>'title'),
    'formDescription'=>array( 'validations'=>'notEmpty', 'human'=>'description'),
  );
  
  public function  __construct(EventModel $model){
    $this->model = $model;
    $this->validator = new Validator();
  }
  
  public function printForm($input=null, $errors=null){
    include('templates/enterEvent.php');
  }
  
  public function save($post){
    $post = $this->filter($this->fieldMeta, $post);
    $error = $this->validate($this->fieldMeta, $post);
    
    if($error){
      return $error;
    }else{
      $model = $this->model;
      return $model->insertEvent($this->convertDateString($post['formDate']), $post['formTitle'], $post['formDescription']);
    }
  }
  
  public function convertDateString($dateString){
    $dateArray = preg_split('/\//', $dateString);
    
    return $dateArray[2].'-'.$dateArray[0].'-'.$dateArray[1];
  }
  
  public function filter($metaArray, $input){
    
    $filtered = array();
    foreach($metaArray as $fieldName=>$array){
      
      //if the field is set and the field is not empty
      if(isset($input[$fieldName]) && $input[$fieldName]){
        $filtered[$fieldName] = strip_tags($input[$fieldName]);
      }  
      
    }
    return $filtered;
  }
  
  public function validate($metaArray, $input){
    $errors = array();
    foreach($metaArray as $fieldName=>$array){
      if( isset($array['validations']) && isset($array['human']) ){
        $validations = $array['validations'];
        $human = $array['human'];
        $userValue = '';
        if(isset($input[$fieldName])){
          $userValue = $input[$fieldName];
        }  
        $result = $this->validator->validate($userValue, $human, $validations);
        //if the validator finds an error in the input then it returns the error message
        if($result['error']){
          $errors[$fieldName]=$result['message'];
        }
      }
    }
    return $errors;
  }
  
}

?>