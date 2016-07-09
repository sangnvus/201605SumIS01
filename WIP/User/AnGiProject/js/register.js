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