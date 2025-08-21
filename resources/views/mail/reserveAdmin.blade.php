<html>
    <head>
        <meta charset="UTF-8">
    </head>
    <body>
        <div>
			<p>{{ $siteData->name }}様</p>
            <br/>
            <p>----------------------------------------</p>
            <p>！本メールはシステムより自動送信している予約通知メールです！</p>
            <p>※ご返信されても予約者様には送信されません。</p>
            <p>※本メールにご返信いただいてもお答えできませんので、ご了承ください。</p>
            <p>----------------------------------------</p>
            <br/>
            <p>コスモ天国ネットから新しい予約が入りました。</p>
            <p>予約内容確認後、お客様へご連絡をお願いいたします。</p>
            <br>
            <p>◆予約内容◆</p>
            <p>▼来店日時</p>
            <p>{{ date('Y/m/d H:i',strtotime($startTime)) }}</p>
            <p>▼店舗名</p>
            <p>{{ $shopData->short_name }}</p>
            <p>▼店舗電話番号</p>
            <p>{{ $shopData->tel }}</p>
            <p>▼女の子</p>
            @if($castData)
            <p>{{ $castData->source_name }}</p>
            @else
            <p>フリー予約</p>
            @endif
            <p>▼コース</p>
            <p>{{ $courseName }}</p>
            <p>▼料金</p>
            <p>{{ $amount }}</p>
            <p>※表記料金は目安となります。</p>
            <br>
            <p>◆お客様の情報◆</p>
            <p>▼お名前</p>
            <p>{{ $userData->name }}</p>
            <p>▼お客様電話番号</p>
            <p>{{ str_replace('+81',0,$userData->phone) }}</p>
            <p>▼お客様メールアドレス</p>
            <p>{{ $userData->email }}</p>
            <p>▼住所</p>
            <p>{{ $adr }}</p>
            <p>▼ご要望</p>
            <p>{{ $note }}</p>
        </div>
    </body>
</html>
