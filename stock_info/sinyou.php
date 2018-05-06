<?php

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
 

//松井証券のHPから評価損益率の信用残を取得する
 $html = file_get_contents("https://www.matsui.co.jp/market/stock/netstock-info/");
 $doc = phpQuery::newDocument($html);

//
$title  = "   "."信用残(億円)"." "."評価損益率(%)";
$tweets = array($title);

for($i=0; $i<3; ++$i){
  $sinyou_info   =   $doc->find("tbody:eq(3)")->find("tr:eq($i)")->text();
  $tweets[] = $sinyou_info;
}

//半角スペース、全角スペース、改行を取り除く（置換）
$tweets = str_replace(PHP_EOL, "  ", $tweets);
$tweet = implode(PHP_EOL, $tweets);

$tweet =  '信用残速報(前営業日時点)' . PHP_EOL . PHP_EOL . $tweet;

var_dump($tweet);

// tweetを送信する
$result = $twitter->post("statuses/update", array("status" => $tweet ));


//Tweetの成否を判定する
if($twitter->getLastHttpCode() == 200) {
    print "tweeted\n";
} else {
    print "tweet failed\n";
}



