<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Error occurred!</title>
</head>

<style>
    body {
        margin: 0;
        padding: 0 40px;
        background-color: #fdfdfd;
        font-family: sans-serif;
        direction: ltr;
        text-align: left;
    }

    .message-box {
        max-width: 1200px;
        margin: 40px auto;
        background-color: #fff;
        border-radius: 7px;
        -webkit-box-shadow: 0 5px 18px 0 rgba(0, 0, 0, 0.15);
        -moz-box-shadow: 0 5px 18px 0 rgba(0, 0, 0, 0.15);
        box-shadow: 0 5px 18px 0 rgba(0, 0, 0, 0.15);
        /*border-top: 7px solid #e55555;*/
    }

    .message-header {
        border-bottom: 3px solid #eee;
        font-size: 16px;
        padding: 25px 20px;
        color: #666;
    }

    .message-content {
        font-size: 13px;
        color: #333;
        padding: 15px;
        margin: 0;
        line-height: 21px;
    }

    .message-info {
        display: block;
        background-color: #f3f3f3;
        border-radius: 7px;
        color: #000;
        padding: 20px;
        line-height: 1.9;
    }
</style>
<body>
<div class="message-box">
    <div class="message-header">
        <h2 style="margin: 0;">
            An error with below information is occurred:
        </h2>
    </div>
    <div class="message-content">
        <h3 style="line-height: 1.9; color: #444;">
            <?= $Exceptions_message ?? ''; ?>
        </h3>
        <br>
        <h4>
            More information:
        </h4>
        <code class="message-info">
            type: <?= $Exceptions_detail['type'] ?? ''; ?> - <?= $Exceptions_detail['typeStr'] ?? ''; ?>
            <br>
            message: <?= $Exceptions_detail['message'] ?? ''; ?>
            <br>
            file: <?= $Exceptions_detail['file'] ?? ''; ?>
            <br>
            line: <?= $Exceptions_detail['line'] ?? ''; ?>
            <br>
            trace: <?= $Exceptions_detail['trace'] ?? ''; ?>
        </code>
    </div>
</div>
</body>
</html>