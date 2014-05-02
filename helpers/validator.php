<?php

class Validator{
  
  //this is the engine that runs all of the validations on an item
  //if an item is required then it has to be first in the validation string 
  public function validate($value, $human,$validations){
    
    $error=0;
    $message='';
    
    $validationsArray = preg_split('/_/', $validations);
    $numOfValidations = sizeof($validationsArray);
    //if the value is not required and it's blank then skip it
    if($validationsArray[0]!='notEmpty' && ($value===null || $value ==='' ) ){
      return array('error'=>$error, 'message'=>$message);
    }
    
    //go through each of the underscore separated items
    for($i=0; $i<$numOfValidations; $i++){
      $paramsArray = preg_split('/-/', $validationsArray[$i]);
      
      //if the method exists then run the validation
      if(method_exists($this, $paramsArray[0])){
        $paramsArraySize = sizeof($paramsArray);
        if($paramsArraySize==1){
          $message = call_user_func( array( $this, $paramsArray[0]), $value, $human, null  );  
        }elseif($paramsArraySize==2){
          $message = call_user_func( array( $this, $paramsArray[0]), $value, $human, null, $paramsArray[1] );
        }elseif($paramsArraySize>2){
          
          $firstItem = array_shift($paramsArray);
          $message = call_user_func( array( $this, $firstItem), $value, $human, null, $paramsArray );
        }
        
        if($message){
          $error=1;
          break;
        }
      }
    }
    return array('error'=>$error, 'message'=>$message);
  }
  
  private function notEmpty($value, $human, $noMessage=null){
    if(empty($value)){
      if($noMessage){
        return 1;
      }else{
        return 'Please fill in your '.$human.'.';
      }
    }else{
      return 0;
    }
  }
  
  private function isNum($value, $human, $noMessage=null){
    
    $regex = '/^\d+$/';
    $value = (string)$value;
    if(preg_match($regex, $value) ){
      return 0;
    }else{
      if($noMessage){
        return 1;
      }else{
        return 'Please fill in a valid number.';
      }
    }
  }
  
  private function none($value, $human, $noMessage=null){
    return 0;
  }
  
  //requires (PHP 5 >= 5.2.0)
  private function isEmail($value, $human, $noMessage=null){
    if(filter_var($value, FILTER_VALIDATE_EMAIL)){
      return 0;
    }else{
      if($noMessage){
        return 1;
      }else{
        return 'Please fill in a valid email address.';
      }
    }
  }
  
  private function enum($value, $human, $noMessage=null, $enumArray){
    $enumArraySize=sizeof($enumArray);
    $match=0;
    for($i=0; $i<$enumArraySize; $i++){
      if($value == $enumArray[$i]){
        $match=1;
      }
    }
    if($match){
      return 0;
    }else{
      if($noMessage){
        return 1;
      }else{
        return 'The '.$human.' field doesn\'t match any of the available options.';
      }
    }  
  }
  
  private function isCreditCard($value, $human, $noMessage=null){
    if(gettype($value)!='string'){
      throw new Exception('parameter to "isCreditCard" must be in string format');
    }
    
    $nCheck = 0;
    $nDigit = 0;
    $bEven = false;
    
    if( $this->isNum($value, $human, 1) ){
      if($noMessage){
        return 1;
      }else{
        return 'Please fill in a valid credit card number.';
      }
    }
    
    //starting from the end of the string 
    for ($n = strlen($value)-1; $n >= 0; $n--) {
      $cDigit = substr($value,  $n,  1);
      $nDigit = intval($cDigit);
      if ( $bEven ) {
        if ( ($nDigit *= 2) > 9 ) {
          $nDigit -= 9;
        }
      }
      $nCheck += $nDigit;
      $bEven = !$bEven;
    }
    
    $result = ($nCheck % 10) === 0;
    
    if($result){
      return 0;
    }else{
      if($noMessage){
        return 1;
      }else{
        return 'Please fill in a valid credit card number.';
      }
    }
  }
  
  private function notExpired($value, $human, $noMessage=null){
    // value should be in format mmyy
    if(!gettype($value)=='string' && strlen($value)!=4){
      throw new Exception('Please feed in the expiration date in this format: mmyy');
    }
    
    $exMonth = substr($value, 0, 2);
    $exYear = substr($value, 2, 2);
    $result = 0;
  
    $exYear1st = substr($exYear, 1, 1);
    if($exYear1st==0){
      $exYear = $exYear1st;
    }
    $exMonth1st = substr($exMonth, 1, 1);
    if($exMonth1st==0){
      $exMonth = $exMonth1st;
    }
    $exMonth = intval($exMonth);
    $exYear = intval($exYear);
    
    //get the date in php
    $dateOb = new DateTime();
    $curMonth = $dateOb->format('m');
    $curYear = $dateOb->format('y');
    
    //and from that get the month in the year without 0 padding to the left
    $curYear1st = substr($curYear, 1, 1);
    if($curYear1st==0){
      $curYear = $curYear1st;
    }
    $curMonth1st = substr($curMonth, 1, 1);
    if($curMonth1st==0){
      $curMonth = $curMonth1st;
    }
    $curMonth = intval($curMonth);
    $curYear = intval($curYear);
    
    //if the years are equal then the validity will have to be
    //determined by the month
    if($exYear == $curYear){
      if($exMonth>=$curMonth){
        $result = 0;
      }else{
        $result = 1;
      }
    }else if($exYear>$curYear){
      //if the expire year is greater than the curYear then expiration
      //date is valid 
      $result = 0;
    }else{
      $result = 1;
    }
    
    if($result){
      if($noMessage){
        return 1;
      }else{
        return 'Please fill in a date that hasn\'t expired';
      }
    }else{
      return 0;
    }
  }
  
  private function ccRecognized($value, $human, $noMessage=null){
    $type = null;
    $cardTypes = array(
      'visa'=>'/^4[0-9]{12}(?:[0-9]{3})?$/',
      'amex'=>'/^3[47][0-9]{13}$/',
      'master'=>'/^5[1-5][0-9]{14}$/',
      'discover'=>'/^6(?:011|5[0-9]{2})[0-9]{12}$/'
    );
   
    foreach($cardTypes as $cardType=>$cardRegex){
      if(preg_match($cardRegex, $value)){
        $type = $cardType;
        break;
      }
    }
    if($type){
      return 0;
    }else{
      if($noMessage){
        return 1;
      }else{
        return 'Please enter either a Visa, Mastercard, Discovery, or American Express card.';
      }
    }
  }
  
  private function minLength($value, $human, $noMessage=null, $min){
    $noun='character';
    if(is_numeric($value)){
      $noun = 'numeral';
    }
    if($min > 1){
      $noun.='s';
    }
    
    if(strlen((string)$value) >= $min){
      return 0;
    }else{
      if($noMessage){
        return 1;
      }else{
        return 'The '.$human.' must be atleast '.$min.' '.$noun.'.';
      }
    }
  }
  
  private function maxLength($value, $human, $noMessage=null, $max){
    $noun='character';
    if(is_numeric($value)){
      $noun = 'numeral';
    }
    if($max > 1){
      $noun.='s';
    }
    
    if(strlen((string)$value) <= $max){
      return 0;
    }else{
      if($noMessage){
        return 1;
      }else{
        return 'The '.$human.' must be at most '.$max.' '.$noun.'.';
      }
    }  
  }
  
  private function eqLength($value, $human, $noMessage=null, $val){
    $noun='character';
    if(is_numeric($value)){
      $noun = 'numeral';
    }
    if($val > 1){
      $noun.='s';
    }
    
    if(strlen((string)$value) <= $val){
      return 0;
    }else{
      if($noMessage){
        return 1;
      }else{
        return 'The '.$human.' must be at most '.$val.' '.$noun.'.';
      }
    }  
  }
  
  private function dateSlash($value, $human, $noMessage=null){
    $dateRegex ='/[0-9]{2}\/[0-9]{2}\/[0-9]{4}/';
    
    if(preg_match($dateRegex, $value)){
      return 0;
    }else{
      if($noMessage){
        return 1;
      }else{
        return 'The date is not in the right format, dd/mm/yyyy.';
      }
    }
  }
}

?>
