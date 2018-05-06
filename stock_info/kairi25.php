<?php

//日経平均の25日移動平均線乖離率をつぶやく

//日本時間を表示する
date_default_timezone_set('Asia/Tokyo');

//つぶやくためのライブラリを使用
require_once('phpQuery-onefile.php');
require_once('twitteroauth/autoload.php');
require_once('twitteroauth/src/TwitterOAuth.php');

use Abraham\TwitterOAuth\TwitterOAuth;

//APIトークンを取得
 $consumerKey 			= "トークンを取得";
 $consumerSecret 		= "トークンを取得";
 $accessToken 			= "トークンを取得";
 $accessTokenSecret 	= "トークンを取得";

//インスタンスを生成する
$twitter = new TwitterOAuth($consumerKey, $consumerSecret, $accessToken, $accessTokenSecret);


//文字化けを直す
header('Content-Type: text/html; charset=UTF-8');


//下記HPから25日移動平均線の乖離率を取得する
$html = file_get_contents("http://kabusensor.com/nk/");
$doc = phpQuery::newDocument($html);
// var_dump($doc);

$estrangements = array();


//当日の乖離率
$today	=	$doc->find("div.span4")->find("ul:eq(9)")->find("li.fs20")->text();
$today_date = str_replace("0", "", date("m/d"));
$today = $today_date."：".$today.PHP_EOL;

// var_dump(estrangements$today);
echo "<br>";


//9営業日前までのデータを抽出
for($i=5; $i<10; ++$i){
$dates =  $doc->find("div.span4")->find("ul:eq(10)")->find("li:eq($i)")->find("span:eq(0)")->text();
$percentages =  $doc->find("div.span4")->find("ul:eq(10)")->find("li:eq($i)")->find("span:eq(2)")->text();
	$estrangements[] = $dates.":".$percentages." ";
}

//配列の要素を逆順にして、文字列に変換する。
$tweet = implode("%, ", array_reverse($estrangements));
$tweet = "日経平均".PHP_EOL.PHP_EOL." 25日移動平均線 乖離率".PHP_EOL.$today."（".$tweet."%"."）";

var_dump($tweet);

// tweetを送信する
$result = $twitter->post("statuses/update", array("status" => $tweet ));

 
//Tweetの成否を判定する
if($twitter->getLastHttpCode() == 200) {
    print "tweeted\n";
} else {
    print "tweet failed\n";
}

