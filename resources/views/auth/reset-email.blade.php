<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta
        name="viewport"
        content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0"
    >
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Восстановление пароля</title>
</head>
<style>
    body{
        background-color: #EEEEEE;
        font-family: Tahoma, sans-serif;
        font-size: 14px;
        padding: 1rem;
        height: auto;
        color: #000000;
    }
    .content{
        background-color: #fff;
        margin: auto;
        width: 500px;
        height: 200px;
        padding: 1rem;
    }
    h2{
        text-align: center;
    }
    a{
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
    }
    .center{
        text-align: center;
    }
    .small{
        margin-top: 25px;
    }
</style>
<body>
    <div class="content">
        <h2>{{ __('auth.password_subject') }}</h2>
        <p class="center">Вы получили это электронное письмо, потому что мы получили запрос на сброс пароля для вашей учетной записи.</p>
        <a href="{{ route('password.reset', ['email' => $email, 'token' => $token]) }}">Изменить пароль</a>
        <div class="small">
            <small>
                <i>*Срок действия этой ссылки для сброса пароля истечет через 60 минут.</i>
            </small>
        </div>
    </div>
</body>
</html>
