<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta
        name="viewport"
        content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0"
    >
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Подтверждение электронной почты</title>
</head>
<body
    style="
        background-color: #EEEEEE;
        font-family: Tahoma, sans-serif;
        font-size: 14px;
        padding: 1rem;
        height: auto;
        color: #000000;
    "
    >
    <div
        style="
            background-color: #fff;
            margin: auto;
            width: 500px;
            height: 200px;
            padding: 1rem;
        "
    >
        <h2
            style="text-align: center"
        >
            {{ __('auth.email_subject') }}
        </h2>
        <p class="center">Для продолжения регистрации нажмите на кнопку</p>
        <a
            style="
                font-size: 14px;
                margin: 20px auto auto;
                display: block;
                text-decoration: none;
                text-transform: uppercase;
                width: 75%;
                text-align: center;
                border-radius: 5px;
                padding: 10px;
                color: #FFFFFF;
                background-color: #4a5568;
            "
            href="{{ $url }}">Подтверждение
        </a>
        <div
            style="margin-top: 25px;"
        >
            <small>
                <i>*В случае, если произошла ошибка просьба проигнорировать данное письмо</i>
            </small>
        </div>
    </div>
</body>
</html>
