    // register date
    $(document).ready(function(){
            $('#editDate').hide();
            $('#changeDate').click(function(){
            $('#regDate').hide();
            $('#editDate').show();
        });
    });

    // username
     $(document).ready(function(){
            $('#editfname').hide();
            $('#changefname').click(function(){
            $('#userfname').hide();
            $('#editfname').show();
        });
    });

     $(document).ready(function(){
            $('#editlname').hide();
            $('#changelname').click(function(){
            $('#userlname').hide();
            $('#editlname').show();
        });
    });

    // phone number
    $(document).ready(function(){
            $('#editPhoneNo').hide();
            $('#changePhoneNo').click(function(){
            $('#phoneNo').hide();
            $('#editPhoneNo').show();
        });
    });

    // Email
    $(document).ready(function(){
            $('#editEmail').hide();
            $('#changeEmail').click(function(){
            $('#email').hide();
            $('#editEmail').show();
        });
    });

    // date of birth
    $(document).ready(function(){
            $('#editDob').hide();
            $('#changeDob').click(function(){
            $('#Dob').hide();
            $('#editDob').show();
        });
    });

    // gender
    $(document).ready(function(){
            $('#correctSex').click(function(){
            $('input[name=sex]').removeAttr('disabled');
        });
    });

    // address
    $(document).ready(function(){
            $('#editAddr').hide();
            $('#changeAddr').click(function(){
            $('#address').hide();
            $('#editAddr').show();
        });
    });

    // password
    $(document).ready(function(){
            $('#editPass').hide();
            $('#changePass').click(function(){
            $('#editPass').show();
            $('#changePass').hide();
        });
    });

    // description
    $(document).ready(function(){
            $('#editDes').hide();
            $('#changeDes').click(function(){
            $('#description').hide();
            $('#editDes').show();
        });
    });
