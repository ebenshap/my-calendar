<?php
/**
 * @package eben_calendar
 * @version 1
 */
/*
Plugin Name: Eben's Simple Calendar
Description: This is a really simple calendar that only marks the current date.
Author: Eben Shapiro
Version: 1
Author URI: http://ebenshapiro.com/
*/

class EbenCalendar{

  public $monthString;
  public $monthNum;
  public $year;
  
  public $todayDay;
  public $todayMonthNum;
  public $todayYear;
  
  public $firstOfMonth;
  public $daysInMonth;
  public $dayOfTheWeek;

  function __construct($timeToFind=null){
    
    //get today's day number
    $today= date('d-m-Y', time());
    $today = preg_split('/-/', $today);
    $this->todayDay=(int)$today[0];
    $this->todayMonthNum=(int)$today[1];
    $this->todayYear=(int)$today[2];
    
    if($timeToFind==null){
      $timeToFind=time();
    }
    
    $dateToFind = date('d-m-Y', time());
    
    $dateToFindArray = preg_split('/-/', $dateToFind);
    $this->day=(int)$dateToFindArray[0];
    $this->monthNum=(int)$dateToFindArray[1];
    $this->year=(int)$dateToFindArray[2];
    
    //Month as string
    $this->monthString=date( 'F', $timeToFind);
  
    $length=strpos($dateToFind, '-');
    $this->firstOfMonth= '01'.substr($dateToFind, $length);
    
    //convert the date of the first of the month to figure out how many days are in the month and
    //the day of the week that the month starts on
    $firstInTime=strtotime($this->firstOfMonth);
    $this->daysInMonth=date('t', $firstInTime);
    
    $this->startDayOfTheWeek= date('w',$firstInTime);
    
  }

  function draw($callBack=null){
  
    echo '<div id="eb-calendar">';
    echo '<table>';
    echo '<caption>'.$this->monthString.' '.$this->year.'</caption>';
    //print the header row
    echo '<tr class="days"><td>Su</td>
        <td>Mo</td>
        <td>Tu</td>
        <td>We</td>
        <td>Th</td>
        <td>Fr</td>
        <td>Sa</td>
        </tr>';
    $weekCount=0;
    //start from negative the day num of the week the month starts on
    //these negative numbers will print blanks
    //if i is less than the number of days in the week
    //or weekCount is not 0, then keep going
    //weekCount keeps track of how many days of a week have been printed
    for($i=(-($this->startDayOfTheWeek))+1; $i<$this->daysInMonth || $weekCount!=0; $i++){
      if($weekCount==0){
        echo '<tr>';
      }
        //mark the cell that contains today's date
        
        if($i===$this->todayDay && $this->todayYear===$this->year && $this->todayMonthNum === $this->monthNum ){
          echo '<td class="today">';
        }else{
          echo '<td>';
        }
        //print a number if i still matches the number of days in a month
        if ($i>0 &&$i<=$this->daysInMonth){
          echo ($i);
        }
        echo '</td>';
      if($weekCount==6){
        echo '</tr>';
        //finished printing a week
        $weekCount=0;
        continue;
      }
      $weekCount++;
    }
    echo '</table>';
    echo '</div>';
  }
}

?>
