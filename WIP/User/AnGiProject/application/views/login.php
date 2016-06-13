<!DOCTYPE html>
<html>
    <head>
        <title><?php echo $title ?></title>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    </head>
    <body>
        <?php echo $message ?>
        <form method="POST" action="">
            Username : <input type="text" name="username" value=""/> <br/>
            Password : <input type="password" name="password" value=""/> <br/>
            <input type="submit" name="submit_login" value="Login"/>
        </form>


    </body>
</html>