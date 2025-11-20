<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Email Verification</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f4f6f9;
            margin: 0;
            padding: 40px;
            color: #333;
        }
        .container {
            max-width: 500px;
            margin: 0 auto;
            background: #ffffff;
            border-radius: 15px;
            padding: 30px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
            text-align: center;
        }
        .title {
            font-size: 24px;
            font-weight: bold;
            margin-bottom: 15px;
            color: #2c3e50;
        }
        .subtitle {
            font-size: 16px;
            margin-bottom: 25px;
            color: #555;
        }
        .otp-box {
            display: inline-block;
            /* padding: 15px 25px;
            background: #e5e7e9;
            color: white; */
            border-radius: 10px;
            font-size: 28px;
            font-weight: bold;
            letter-spacing: 4px;
            margin-bottom: 20px;
        }
        .footer {
            margin-top: 25px;
            font-size: 14px;
            color: #777;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="title">Verification Code</div>
        <div class="subtitle">Use the OTP code below to verify your account:</div>

        <div class="otp-box">{{ $user->code }}</div>

        <div class="footer">
            If you didn’t request this code, please ignore this message.
        </div>
    </div>
</body>
</html>
