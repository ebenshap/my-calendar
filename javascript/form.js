submitEvent function(){
  var formDate = $('form input[name="date"]').val()
  var formTitle = $('form input[name="title"]').val()
  var formDescription = $('form input[name="description"]').val()
  $.ajax({
type: "POST",
url: url,
data: data,
success: success,
dataType: dataType
});
}


$(document).ready(function(){

  $(function() {
    $( "#datepicker" ).datepicker();
  });

  $("form input[type='submit']").click(function(){
    alert('hellooo')
    return false;
  })

})
