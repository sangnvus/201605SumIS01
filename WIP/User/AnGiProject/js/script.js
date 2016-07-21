/**
 * Created by HIEUVH on 6/13/2016.
 */
$(document).ready(function(){
    $('input:radio[name="autho"]').change(
        function(){
            if ($(this).is(':checked') && $(this).val() == '2') {
                $('#myAddress').show();
            }
            else{
                $('#myAddress').hide();
            }
        });
});

 
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

$(function() {
    $(window).scroll(sticky_relocate);
    sticky_relocate();
});


