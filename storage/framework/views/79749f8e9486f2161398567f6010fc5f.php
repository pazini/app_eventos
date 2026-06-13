
<!DOCTYPE html>
<html>
<head>
    <style>
        body {
            background-color: #ffe6e6;
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
        }
        .container {
            background-color: #ffffff;
            border-radius: 10px;
            padding: 20px;
            width: 100%;
            margin: 20px auto;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        .logo {
            text-align: center;
            margin-bottom: 20px;
        }
        .logo img {
            max-height: 50px;
            max-width: 200px;
        }
        h1 {
            font-size: 24px;
            color: #333;
            text-align: center;
        }
        p {
            font-size: 16px;
            color: #333;
            line-height: 1.5;
        }
        .greeting {
            font-size: 18px;
            font-weight: bold;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            margin-bottom: 20px;
        }
        table, th, td {
            border: 1px solid #ddd;
        }
        th, td {
            padding: 10px;
            text-align: left;
            text-transform: uppercase;
        }
        td {
            font-size: medium;
        }
        ul {
            list-style-type: none;
            padding: 0;
        }
        ul li {
            background: #ffcccc;
            margin: 5px 0;
            padding: 10px;
            border-radius: 5px;
        }
        .button {
            display: block;
            width: 200px;
            margin: 20px auto;
            padding: 10px;
            text-align: center;
            background-color: #d41a1a;
            color: #ffffff;
            text-decoration: none;
            border-radius: 5px;
        }
        .footer {
            text-align: center;
            font-size: 14px;
            color: #666;
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <div class="container" style="border: 1px solid #c2c2c2">

        <div class="logo">
            <img src="<?php echo e(appUrl()); ?>/<?php echo e(appLogo()); ?>" alt="<?php echo e(appName()); ?>">
        </div>

        <h2 style="width:100%; text-align:center; text-transform: uppercase;"><?php echo e($title ? $title : 'TÍTULO'); ?></h2>

        <div style="width:100%; text-align:center;"><?php echo $textBody ? $textBody : 'EMAIL'; ?></div>

    </div>
</body>
</html>
<?php /**PATH /home/igrnc24/public_html_sistemas/campanhas-eventos/resources/views/_email/teste/email-teste.blade.php ENDPATH**/ ?>