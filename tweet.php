<?php

// ライブラリ読み込み
require __DIR__.'/vendor/autoload.php';

// テキストとして結果を表示
header('Content-Type: text/plain; charset=utf-8');

// 実行時間を無制限にする
set_time_limit(0);

incrude ('api-keys.php');

// インスタンス生成
$to = new TwistOAuth($ck, $cs, $at, $as);

try {
    // 自分のユーザIDを取得
    $my_id = $to->get('account/verify_credentials')->id_str;

    // ストリーミングに接続
    $to->streaming(
       'user',
        function ($status) use ($to, $my_id) {
            // comePiを検知
            if (isset($status->text) && strstr($status->text, 'comePi') && (!strstr($status->text, ':')))  {
                $t = exec('cat /sys/class/thermal/thermal_zone0/temp');
                $t = ($t / 1000);
                $t = round($t, 1);
                $moji = array(
                    '(´・ω・｀)',
                    '(*´﹃｀*)',
                    '(ヾﾉ･∀･`)',
                    '(((((((((((っ･ω･)っ',
                    '(σ・∀・)σ',
                    '(人´∀｀)．☆．。．:*･ﾟ',
                    'ヽ(･ω･)/',
                    '・ω・',
                    '(ﾟ∀ﾟ)',
                    '(-_-;)',
                    '(*´﹃｀*)',
                );
                $count  = count($moji);
                $random = rand(0, $count - 1);
                $to->post('statuses/update', ['status' => '@'.$status->user->screen_name."\n".'comePiのcpu温度は現在 '.$t.' ℃です'."\n".$moji[$random].date( "Y/m/d/ H:i:s")]);
            }
        }
    );

} catch (TwistException $e) {

    echo "Fatal Error: {$e->getMessage()}\n";

}
