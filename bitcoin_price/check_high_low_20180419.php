<?php

// twitter 本アカでのつぶやき

// 日本時間を表示する
date_default_timezone_set('Asia/Tokyo');

// つぶやくためのライブラリを使用
require_once('twitteroauth/autoload.php');
require_once('twitteroauth/src/TwitterOAuth.php');

use Abraham\TwitterOAuth\TwitterOAuth;

// APIトークンを取得
 $consumerKey         = "トークンを取得";
 $consumerSecret      = "トークンを取得";
 $accessToken         = "トークンを取得";
 $accessTokenSecret   = "トークンを取得";

// インスタンスを生成する
$twitter = new TwitterOAuth($consumerKey, $consumerSecret, $accessToken, $accessTokenSecret);

// 文字化けを直す
header('Content-Type: text/html; charset=UTF-8');

// ビットコインの現在価格をデータベースから取得する
try
{
$dsn='mysql:host=127.0.0.1;dbname=DBname;charset=utf8';
$user='root';
$password='root';
$pdo = new PDO($dsn, $user, $password, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    ]);

// 最高値のデータ（カラム名をキーにした連想配列）	
$sql = 'SELECT * FROM bitcoin_exchange WHERE price = (SELECT MAX(price) FROM bitcoin_exchange WHERE created_at < CURRENT_DATE() AND created_at >= DATE_SUB(CURRENT_DATE(), INTERVAL 1 DAY))';

$max_row = $pdo->query($sql)->fetch(PDO::FETCH_ASSOC);

// 最安値のデータ（カラム名をキーにした連想配列）	
$sql = 'SELECT * FROM bitcoin_exchange WHERE price = (SELECT MIN(price) FROM bitcoin_exchange WHERE created_at < CURRENT_DATE() AND created_at >= DATE_SUB(CURRENT_DATE(), INTERVAL 1 DAY) AND NOT price = 0) ';

$min_row = $pdo->query($sql)->fetch(PDO::FETCH_ASSOC);

// 日付を加工
$date = date('n月d日', strtotime($max_row['created_at']));
$time_max = date('H時i分', strtotime($max_row['created_at']));
$time_min = date('H時i分', strtotime($min_row['created_at']));

// 金額をカンマ区切りにする
$price_max = number_format($max_row['price']);
$price_min = number_format($min_row['price']);

// Twitterでつぶやく内容
$tweets = [];

$tweets[] = $date . 'のビットコインの金額をお知らせします💰';
$tweets[] = '最高値📈' . $price_max . '円' . '(' . $time_max . '/' . $max_row['name'] . ')';
$tweets[] = '最安値📉' . $price_min . '円' . '(' . $time_min . '/' . $min_row['name'] . ')';
$tweets[] = '#Bitcoin_range';

$tweet = implode(PHP_EOL, $tweets);

echo $tweet;

//tweetを送信する
$result = $twitter->post("statuses/update", array("status" => $tweet ));
 
//Tweetの成否を判定する
if($twitter->getLastHttpCode() == 200) {
    print "tweeted\n";
} else {
    print "tweet failed\n";
}

}
catch (Exception $e)
{
	print 'データーベース接続エラー発生';
	exit();
}