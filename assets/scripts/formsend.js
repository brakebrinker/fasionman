$(document).ready(function() {
  $('form#contactform').submit(function(e) {
    e.preventDefault();
    var msg = $(this).serialize();
    var action = $(this).attr('action');
    
    $.ajax({
      type: "POST",
      usr: action,
      data: msg,
      success: function(data) {
        $('#order-window').modal('hide');
        $('#thanks').modal('show');
        if(data == '') {
          $('#result').html('<div class="success">Thank you for your email</div>');
        }
      }
    });
  })
});