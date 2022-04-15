<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

   <head>
      <meta name="viewport" content="width=device-width, initial-scale=1.0" />
      <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
   </head>

   <body>
      <div style="width: 100%; margin: auto; font-family: sans-serif;">
         <p><strong>From: </strong> <a href="mailto:{{$email}}">{{$email}}</a></p>
         <p><strong>Name: </strong> {{$name}}</p>
         <p><strong>Message: </strong> {{$enquiry}}</p>
         <p>This e-mail was sent from a contact form on {{getConstant('APP_NAME')}} ({{route('frontend.showContactUs')}})</p>
      </div>
   </body>

</html>