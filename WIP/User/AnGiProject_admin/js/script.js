/**
 * Created by HIEUVH on 6/13/2016.
 */
function sticky_relocate() {
    var window_top = $(window).scrollTop();
    var div_top = $('.stick-point').offset().top;
    if (window_top > div_top) {
        $('.restaurant-place').addClass('stick');
        $('.stick-point').height($('.restaurant-place').outerHeight());
        $('.restaurant-place').width($('.restaurant-rate').width());
    } else {
        $('.restaurant-place').removeClass('stick');
        $('.stick-point').height(0);
    }
}

function signinEventBinding(){
     $('.signinform').submit(function() { 
       $.ajax({ 
           type : "POST",
           data : $(this).serialize(),
           dataType:'json',
           url: 'home/login', // target element(s) to be updated with server response 
           cache : false,
           //check this in firefox browser
           success : function(response){ console.log(response); 
               if (response.type == 'success'){
                   location.href = location.href;
               }
               else if (response.type == 'error'){
                   alert(response.text);
               }
           },             
           error: function(response){ console.log(response); },
       });        
       return false; 
    }); 
    $('#submit_login').click(function(e){             
        e.preventDefault();
        $('.signinform').submit();
    })
}

$(function() {
    signinEventBinding(); 
    $(window).scroll(sticky_relocate);
    sticky_relocate();               
});
