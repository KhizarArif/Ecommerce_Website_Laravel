<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title> Reset Password </title>

</head>

<body>

    <h1> Reset Your Password </h1>
    <h2>Click the link below to reset your password</h2>

    <a href="{{ isset($formData['token']) ? route('account.userResetPassword', $formData['token']) : '#' }}"> Click Here </a>

</body>

</html>
