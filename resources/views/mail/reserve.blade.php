<html>
    <head>
        <meta charset="UTF-8">
    </head>
    <body>
        <div>
            <p>{{ $userData->name }}様</p>

            <p>ご予約ありがとうございます。仮予約を受け付けました。</p>
            <p>店舗から内容確認のご連絡をいたします。</p>
            <p>また、実際にお遊びの際には口コミ投稿のご協力をお願いいたします。</p>

            <p>もし連絡がない場合はお手数ですが以下の連絡先までお問い合わせください。</p>

            <p>◆ご予約内容◆</p>
            <p>▼ご予約日時</p>
            <p>{{ $startTime }}</p>
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
            <p>{{ number_format($amount) }}円</p>
            <p>※表記料金は目安となります。</p>
            <p>実際の料金は直接お店にご確認ください。</p>
        </div>
    </body>
</html>
