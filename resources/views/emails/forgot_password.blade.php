<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

   <head>
      <meta name="viewport" content="width=device-width, initial-scale=1.0" />
      <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
   </head>

   <body>
      <div style="width: 100%; margin: auto; font-family: sans-serif;">
         <p>Your updated password is {{$new_pass}}.</p>
         <p>Please head over to <a href="{{route('frontend.signIn')}}">Sign In</a> to access your account.</p>
      </div>
   </body>

</html>