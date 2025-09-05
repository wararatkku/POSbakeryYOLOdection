<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>PromptPay QR-Code</title>
    <style>
        body {
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            background-color: #dcdcdc;
            /* background-image: url('assets/images/freepik__expand__8304.png');
            background-size: 100%; */
            font-family: Arial, sans-serif;
            text-align: center;
        }
        .content {
            background: #ffffff;
            padding: 20px;
            border-radius: 10px;
            border: 2px solid #7c6247;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        .QR-pre {
            max-width: 100%;
            height: auto;
            margin-top: 20px;
            border: 2px solid #7c6247;
            border-radius: 10px;
            display: block;
        }
        p {
            margin: 0;
            padding: 10px 0;
            color: #333;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <div class="content">
        <img src="images/prompt-pay-logo.png" alt="PromptPayLogo" style="height: 60px">
        <img src="{{ $qrSrc }}" alt="QR Code" class="QR-pre">
        <p>จำนวนเงิน: {{ number_format((float)$amount, 2) }} บาท</p>
    </div>
</body>
</html>