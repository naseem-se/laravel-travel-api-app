<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Email Change OTP</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f6f9;
            padding: 20px;
        }

        .container {
            max-width: 600px;
            margin: 0 auto;
            background: #fff;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
            text-align: center;
        }

        .otp {
            font-size: 32px;
            font-weight: bold;
            color: #ff5722;
            letter-spacing: 4px;
            margin: 20px 0;
        }

        .footer {
            font-size: 14px;
            color: #777;
            margin-top: 25px;
        }
    </style>
</head>

<body>
    <div class="container">
        <h2>Email Change Request</h2>
        <p>Use the following OTP to confirm your new email address:</p>
        <div class="otp">{{ $otp }}</div>
        <p class="footer">If you did not request this change, please ignore this email.</p>
    </div>
</body>

</html>
