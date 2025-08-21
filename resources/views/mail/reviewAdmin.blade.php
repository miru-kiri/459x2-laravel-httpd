<html>
    <head>
        <meta charset="UTF-8">
    </head>
    <body>
        <div>
			<p>{{ $siteData->name }}様</p>
            <br/>
            <p>----------------------------------------</p>
            <p>！本メールはシステムより自動送信している通知メールです！</p>
            <p>※ご返信されても口コミ投稿者様には送信されません。</p>
            <p>※本メールにご返信いただいてもお答えできませんので、ご了承ください。</p>
            <p>----------------------------------------</p>
            <br/>
            <p>コスモ天国ネットから新しい口コミ投稿が投稿されました。</p>
            <p>口コミ内容確認後、管理画面よりお客様へご返信をお願いいたします。</p>
            <br>
            <p>◆口コミ内容◆</p>
            <p>▼タイトル</p>
            <p>{{ $title }}</p>
            <p>▼口コミ投稿日</p>
            <p>{{ $createdAt }}</p>
            <p>▼女の子</p>
            @if($castData)
            <p>{{ $castData->source_name }}</p>
            @else
            <p>フリー予約</p>
            @endif
            <p>▼プレイ日</p>
            <p>{{ $timePlay }}</p>
            <p>▼評価</p>
            @foreach($criterialContent as $criterial)
            <p>{{ $criterial['label'] }}: {{ $criterial['evaluate'] }}</p>
            @endforeach
            <p>▼内容</p>
            <p>{{ $content }}</p>
            <p>◆お客様の情報◆</p>
            <p>▼お名前</p>
            <p>{{ $userData->name }}</p>
            <p>▼お客様電話番号</p>
            <p>{{ str_replace('+81',0,$userData->phone) }}</p>
            <p>▼お客様メールアドレス</p>
            <p>{{ $userData->email }}</p>
        </div>
    </body>
</html>
