<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>【天国ネット】認証URLをご確認ください</title>
</head>

<style>
    .main-wrapper {
        width: 310px;
        border: 1px solid #e8e8e8;
        margin: 30px auto;
    }
    
    .password-box {
        width: 90%;
        margin: 20px auto;
        padding: 10px 0;
        text-align: center;
        border-radius: 8%;
        background-color: #f8f9f9;
    }

    .password-text {
        margin: 10px 0 0 0;
        color: #54595d;
        font-size: 28px;
        font-weight: 600;
        letter-spacing: 6px;
        line-height: 34px;
        text-align: center;
    }

    .warning-text {
        text-align: center;
        color: tomato;
    }

    .content-wrapper {
        width: 90%;
        margin-top: 40px;
        margin: auto;
        color: #54595d
    }

    .mail-wrapper {
        width: 100%;
        text-align: center;
    }

    .mail-text {
        margin: auto;
        color: #71c3c8;
    }

    hr {
        width: 90%;
        opacity: 50%;
        margin: 30px auto;
    }
</style>

<body>
    <div class="main-wrapper">
        <div class="password-box">
            <div>
                <small>{{ $title }}</small>
            </div>
        </div>
        <!-- <p class="warning-text">有効期限 : 発行より5分間</p> -->
        <div class="content-wrapper">
            <div class="mail-wrapper">
                <small><a href="{{ $url }}">{{ $url }}</a></small>
            </div>
        </div>
        <hr>
        <div class="content-wrapper" style="margin-bottom: 30px;">
            <!-- <small>※5分以上経過した場合は、再度メールアドレス変更フォームよりをご登録ください。</small>
            <br> -->
            <small>※本メールにお心当たりがない場合は破棄していただけますようお願いいたします。</small>
        </div>
    </div>
</body>

</html>