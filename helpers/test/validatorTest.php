<?php
require('../validator.php');
class ValidatorTest extends PHPUnit_Framework_TestCase{

//-----------------------------------------Not empty tests
  public function testNotEmpty(){
    $validator = new Validator();
    $result = $validator->validate(null, 'test', 'notEmpty');
    $this->assertEquals(1, $result['error']);
    
    $result = $validator->validate(0, 'test', 'notEmpty');
    $this->assertEquals(1, $result['error']);
    
    $result = $validator->validate(1, 'test', 'notEmpty');
    $this->assertEquals(0, $result['error']);
    
  }

//-----------------------------------------IsNum tests
  public function testIsNumString(){
    $validator = new Validator();
    $result = $validator->validate('this is a string', 'test', 'isNum');
    $this->assertEquals(1, $result['error']);
    
  }
  
  public function testIsNumEmptyNotRequiredString(){
    $validator = new Validator();
    $result = $validator->validate('', 'test', 'isNum');
    $this->assertEquals(0, $result['error']);
    
  }  

  public function testIsNumEmptyNotRequiredNull(){
    $validator = new Validator();
    $result = $validator->validate(null, 'test', 'isNum');
    $this->assertEquals(0, $result['error']);
    
  }
  
  public function testIsNumNumber(){
    $validator = new Validator();
    $result = $validator->validate(1, 'test', 'isNum');
    $this->assertEquals(0, $result['error']);
    
    $result = $validator->validate(1000, 'test', 'isNum');
    $this->assertEquals(0, $result['error']);
  }

  public function testIsNumNumericString(){
    $validator = new Validator();
    $result = $validator->validate('1', 'test', 'isNum');
    $this->assertEquals(0, $result['error']);
    
    $result = $validator->validate('1000', 'test', 'isNum');
    $this->assertEquals(0, $result['error']);
  }

  public function testIsNumEmptyRequiredString(){
    $validator = new Validator();
    $result = $validator->validate('', 'test', 'notEmpty_isNum');
    $this->assertEquals(1, $result['error']);
    
  }
//----------------------------------- isEmail test

  public function testIsEmailBadEmail(){
    $validator = new Validator();
    $result = $validator->validate('edanshapearthlink.net', 'test', 'notEmpty_isEmail');
    $this->assertEquals(1, $result['error']);  
  }
  
  public function testIsEmailGoodEmail(){
    $validator = new Validator();
    $result = $validator->validate('edanshap@earthlink.net', 'test', 'notEmpty_isEmail');
    $this->assertEquals(0, $result['error']);  
  }
  
  public function testIsEmailNotRequired(){
    $validator = new Validator();
    $result = $validator->validate('', 'test', 'isEmail');
    $this->assertEquals(0, $result['error']);  
  }

  public function testIsEmailNotRequiredButGiven(){
    $validator = new Validator();
    $result = $validator->validate('edanshap@earthlink.net', 'test', 'isEmail');
    $this->assertEquals(0, $result['error']);  
  }

//------------------------------------- isCreditCard test

  public function testIsCreditCardGoodValue(){
    $validator = new Validator();
    $result = $validator->validate('4111111111111111', 'test', 'isCreditCard');
    $this->assertEquals(0, $result['error']);
    $result = $validator->validate('79927398713', 'test', 'isCreditCard');
    $this->assertEquals(0, $result['error']);
  }

  public function testIsCreditCardBadValue(){
    $validator = new Validator();
    $result = $validator->validate('4111111111111112', 'test', 'isCreditCard');
    $this->assertEquals(1, $result['error']);
    $result = $validator->validate('79927398714', 'test', 'isCreditCard');
    $this->assertEquals(1, $result['error']);
  }

  public function testIsCreditCardWord(){
    $validator = new Validator();
    $result = $validator->validate('not a credit card', 'test', 'isCreditCard');
    $this->assertEquals(1, $result['error']);
  }
  
  public function testIsCreditCardWithNegString(){
    $validator = new Validator();
    $result = $validator->validate('-1000', 'test', 'isCreditCard');
    $this->assertEquals(1, $result['error']);
  }
  
  /*public function testIsCreditCardWithNegNum(){
    $validator = new Validator();
    $result = $validator->validate(-1000, 'test', 'isCreditCard');
    $this->assertEquals(1, $result['error']);
  }*/
//------------------------------------------- ccRecognized

  public function testccRecognizedVisaGood(){
    $validator = new Validator();
    $result = $validator->validate('4111111111111112', 'test', 'ccRecognized');
    $this->assertEquals(0, $result['error']);
  }
  
  public function testccRecognizedVisaBad(){
    $validator = new Validator();
    $result = $validator->validate('411111111111112', 'test', 'ccRecognized');
    $this->assertEquals(1, $result['error']);
  }
  
  public function testccRecognizedMasterGood(){
    $validator = new Validator();
    $result = $validator->validate('5111111111111111', 'test', 'ccRecognized');
    $this->assertEquals(0, $result['error']);
  }
  
  public function testccRecognizedMasterBad(){
    $validator = new Validator();
    $result = $validator->validate('511111111112', 'test', 'ccRecognized');
    $this->assertEquals(1, $result['error']);
  }
  
  public function testccRecognizedDiscoverGood(){
    $validator = new Validator();
    $result = $validator->validate('6011111111111111', 'test', 'ccRecognized');
    $this->assertEquals(0, $result['error']);
    $result = $validator->validate('6521111111111111', 'test', 'ccRecognized');
    $this->assertEquals(0, $result['error']);
  }
  
  public function testccRecognizedDiscoverBad(){
    $validator = new Validator();
    $result = $validator->validate('601111111111', 'test', 'ccRecognized');
    $this->assertEquals(1, $result['error']);
    $result = $validator->validate('652111111111', 'test', 'ccRecognized');
    $this->assertEquals(1, $result['error']);
  }
  
  public function testccRecognizedAmexGood(){
    $validator = new Validator();
    $result = $validator->validate('341111111111111', 'test', 'ccRecognized');
    $this->assertEquals(0, $result['error']);
  }
  
  public function testccRecognizedAmexBad(){
    $validator = new Validator();
    $result = $validator->validate('3411111111111121', 'test', 'ccRecognized');
    $this->assertEquals(1, $result['error']);
  }
  
  public function testccRecognizedTotallyBad(){
    $validator = new Validator();
    $result = $validator->validate('89111111111121', 'test', 'ccRecognized');
    $this->assertEquals(1, $result['error']);
  }
  
  //-------------------------------------- expiration date 
  public function testexpirationDateGood(){
    $validator = new Validator();
    $result = $validator->validate('0215', 'test', 'notExpired');
    $this->assertEquals(0, $result['error']);
  }

  public function testexpirationDateBad(){
    $validator = new Validator();
    $result = $validator->validate('0213', 'test', 'notExpired');
    $this->assertEquals(1, $result['error']);
  }  
  
  public function testDateStringGood(){
    $validator = new Validator();
    $result = $validator->validate('02/33/2222', 'test', 'dateSlash');
    $this->assertEquals(0, $result['error']);
  }
  
  public function testDateStringBad(){
    $validator = new Validator();
    $result = $validator->validate('0213', 'test', 'dateSlash');
    $this->assertEquals(1, $result['error']);
  }  

}
?>