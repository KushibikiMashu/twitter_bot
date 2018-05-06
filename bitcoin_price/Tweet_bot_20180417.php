<?php
//参考
//https://qiita.com/norizou4/items/04a99de3f4eaccbddb5b
//https://webkaru.net/php/twitter-bot/

// @Bitcoin_Exchgでの15分ごとのつぶやき

//日本時間を表示する
date_default_timezone_set('Asia/Tokyo');

//つぶやくためのライブラリを使用
 require_once('phpQuery-onefile.php');
 require_once('twitteroauth/autoload.php');
 require_once('twitteroauth/src/TwitterOAuth.php');

 use Abraham\TwitterOAuth\TwitterOAuth;

//APIトークンを取得
$consumerKey         = "トークンを取得";
$consumerSecret      = "トークンを取得";
$accessToken         = "トークンを取得";
$accessTokenSecret   = "トークンを取得";

//インスタンスを生成する
$twitter = new TwitterOAuth($consumerKey, $consumerSecret, $accessToken, $accessTokenSecret);


//文字化けを直す
 header('Content-Type: text/html; charset=UTF-8');


//ビットコインの現在価格を取得する
 $html = file_get_contents("http://xn--eck3a9bu7cul981xhp9b.com/");
 $doc = phpQuery::newDocument($html);


//つぶやく内容を書く
$buy_sell   =   $doc->find("tbody")->find("tr:eq(0)")->text()."enter";
$zaif       =   $doc->find("tbody")->find("tr:eq(5)")->text()."enter";
$bitflyer   =   $doc->find("tbody")->find("tr:eq(6)")->text()."enter";
$coincheck  =   $doc->find("tbody")->find("tr:eq(4)")->text()."enter";
$scale      =   $doc->find("tbody")->find("tr:eq(20)")->text()."enter";
$date       =   date("Y年m月d日 H時i分s秒")." 現在"."enter";
$hashtags   =   "#ビットコイン #Bitcoin #btc";

$tweets = array(
	$buy_sell,
	$zaif,
	$bitflyer,
	$coincheck,
	$scale,
	$date,
	$hashtags,
);

//半角スペース、全角スペースを取り除く（置換）
$tweets = str_replace(array(" ", "  "), "", $tweets);
$tweets = str_replace("#B", " #B", $tweets);
$tweets = str_replace("#b", " #b", $tweets);
//改行を削除
$tweets = str_replace(PHP_EOL, " ", $tweets);
//改行
$tweets = str_replace("enter", PHP_EOL, $tweets);


// foreach($tweets as $tweet){
//   echo $tweet;
// }

$tweet = implode($tweets);

//tweetを送信する
$result = $twitter->post("statuses/update", array("status" => $tweet ));

 
//Tweetの成否を判定する
if($twitter->getLastHttpCode() == 200) {
    print "tweeted\n";
} else {
    print "tweet failed\n";
}

// //タイムゾーンをデフォルトに戻す
// date_default_timezone_set('UTC');
?>