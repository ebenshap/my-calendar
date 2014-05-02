function submitEvent(){
  var formDate = $('form input[name="date"]').val()
  var formTitle = $('form input[name="title"]').val()
  var formDescription = $('form textarea[name="description"]').val()
  $.ajax({
    type: "POST",
    url: 'submitEvent.php',
    data: {
      formDate : formDate,
      formTitle: formTitle,
      formDescription:formDescription
    },
    success: function(data){
      console.log(data)
      if(data==1){
        refreshCalendar();
        alert('Event saved.')
      }else{
        alert('Event could not be saved.')
      }
    },
    dataType: 'text'
});
}

function refreshCalendar(){

$.ajax({
    type: "GET",
    url: 'refreshCalendar.php',
    success: function(data){
      $('#calendar').html(data)
    },
    dataType: 'html'
});

}

$(document).ready(function(){

  $(function() {
    $( "#datepicker" ).datepicker();
  });

  $("form input[type='submit']").click(function(){
    submitEvent()
    return false;
  })

})
