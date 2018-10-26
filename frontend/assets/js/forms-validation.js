'use strict';


$('.ask-form').submit(function(event) {
  // var data = $( this ).serializeArray();
  
  // var errors = validateForm(data);
  
  // var title = '';
  // var name = '';
  // var email = '';
  // var message = '';
  // $('.form-inline p').remove();
  // $(".title").removeClass('form-control-error');
  // $(".name").removeClass('form-control-error');
  // $(".email").removeClass('form-control-error');
  // $(".message").removeClass('form-control-error');

  // if (errors['title'] || errors['name'] || errors['email'] || errors['message']) {
  //   if (errors['title']) {
  //     title += '<p class="w-100 order-2 form-message">' + errors['title'] + '</p>';
  //     $(".title").after(title);
  //     $(".title").addClass('form-control-error');
  //   }

  //   if (errors['name'] || errors['email']) {
  //     name += '<p class="order-2 order-sm-3 form-message d-inline-block">';
      
  //     if (errors['name']) {
  //       name += errors['name'];
  //       $(".name").addClass('form-control-error');
  //     } 
  //     name += '</p>';
  //     $(".name").after(name);
      
  //   }    

  //   if (errors['email'] || errors['name']) {
  //     email += '<p class="order-4 order-sm-4 form-message d-inline-block">';
  //     if (errors['email']) {
  //       email += errors['email'];
  //       $(".email").addClass('form-control-error');
  //     } 
  //     email += '</p>';
  //     $(".email").after(email);
      
  //   }

  //   if (errors['message']) {
  //     message = '';
  //     message += '<p class="w-100 form-message order-5">' + errors['message'] + '</p>';
  //     $(".message").after(message);
  //     $(".message").addClass('form-control-error');
  //   }

  // } else {
    
  //   $(".title").val('');
  //   $(".name").val('');
  //   $(".email").val('');
  //   $(".message").val('');
  //   $(".title").removeClass('form-control-error');
  //   $(".name").removeClass('form-control-error');
  //   $(".email").removeClass('form-control-error');
  //   $(".message").removeClass('form-control-error');
  // }
  
  event.preventDefault();
})