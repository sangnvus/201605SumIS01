/**
 * Created by HIEUVH on 7/9/2016.
 */

$(function() {
    $('.manage-sb>li>a').click(function(){
        if($(this).parent().hasClass("active")){
            if($(this).parent().children("ul").length > 0){
                $(this).parent().removeClass("active");
                $(this).parent().removeClass("show-child");
                $(this).parent().children('.sub-menu').slideUp();
            }

        }else{
            $('.manage-sb>li>a').parent().removeClass("active");
            $('.manage-sb>li>a').parent().removeClass("show-child");
            $('.sub-menu>li>a').removeClass('active');
            $('.sub-menu').slideUp();
            $(this).parent().addClass("active");
            if($(this).parent().children("ul").length > 0){
                $(this).parent().addClass("show-child");
            }
            $(this).parent().children('.sub-menu').slideDown();
        }
    });

    $('.sub-menu>li>a').click(function(){
        $('.sub-menu>li>a').removeClass('active');
        $(this).addClass('active');
    });
    
});

