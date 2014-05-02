<?php
require_once(dirname(__FILE__).'/calendar.php');

class EventCalendar extends Calendar{
  private $eventArray;
  private $model;
  
  public function __construct($timeToFind=null, EventModel $model){
    parent::__construct($timeToFind);
    $this->model = $model;
  }
  
  public function draw(){
    $this->fetchEvents();
    parent::draw('printEvents');
  }
  
  public function printEvents($day){
    for($i = 0; $i <sizeof($this->eventArray); $i++ ){
      $parsedDate = preg_split( '/-/' ,$this->eventArray[$i]['eventDate'] );
      $parsedDay = intval($parsedDate[2]);
      if($parsedDay==$day){
        echo '<p>'.$this->eventArray[$i]['eventTitle'].'</p>';
        
      }
    }
  }
  
  private function fetchEvents(){
    $this->eventArray = $this->model->getEventsByDateRange($this->start, $this->end);
    
  }

}

?>