<?php

class EventModel{

  private $conn ;

  function __construct($dbHost, $dbName, $dbUser, $dbPass){
    $this->conn= new PDO("mysql:host=$dbHost;dbname=$dbName", $dbUser, $dbPass);
    $this->conn->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING );
  }
  
  function insertEvent($date, $title, $description){
    $data = array($date, $title, $description);
    try{
      $statement = $this->conn->prepare("insert into calendarEvents (eventDate, eventTitle, eventDescription) values (?, ?, ?)");
      $statement->execute($data);
      return 1;
    }catch(Exception $e){
      return $e->getMessage();
    }
  }
  
  function getEventsByDateRange($start, $end){
    try{
      $data = array($start, $end);
      $statement = $this->conn->prepare("select eventDate, eventTitle, eventDescription from calendarEvents where eventDate>=? and eventDate<=?");
      $statement->execute($data);
      return $statement->fetchAll(PDO::FETCH_ASSOC);
     
    }catch(Exception $e){
      return $e->getMessage();
    }
  }
  
}

?>