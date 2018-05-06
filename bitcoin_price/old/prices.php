<?php
//参考
//https://qiita.com/norizou4/items/04a99de3f4eaccbddb5b
//https://webkaru.net/php/twitter-bot/


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

$tweets = array($buy_sell, $zaif, $bitflyer, $coincheck, $scale, $date, $hashtags);

//半角スペース、全角スペースを取り除く（置換）
$tweets = str_replace(array(" ", "  "), "", $tweets);
$tweets = str_replace("#B", " #B", $tweets);
$tweets = str_replace("#b", " #b", $tweets);
//改行を削除
$tweets = str_replace(PHP_EOL, " ", $tweets);
//改行
$tweets = str_replace("enter", PHP_EOL, $tweets);

//各取引所ごと、データ加工用の変数
$time = date('Y-m-d H:i:s');
$zaif_data        =   $time." => ".$tweets[1];
$bitflyer_data    =   $time." => ".$tweets[2];
$coincheck_data   =   $time." => ".$tweets[3];
$scale_data       =   $time." => ".$tweets[4];

//取引所ごとの価格をログに記録
$filename = date('Y-m-d').'.txt';
$fp = fopen("bot_log/${filename}", 'a');
fwrite($fp, $logs);

$logs = array(
    $zaif_data,
    $bitflyer_data,
    $coincheck_data,
    $scale_data,
		PHP_EOL,
		);

$logs = str_replace(array(",", "円", "約", "兆"), "", $logs);

foreach($logs as $log){
	echo $log."<br>";
	fwrite($fp, $log);
}
fclose($fp);

//MySQLに入れるために分割する
$zaif_datas      =  explode(" ", $zaif_data);
$bitflyer_datas  =  explode(" ", $bitflyer_data);
$coincheck_datas =  explode(" ", $coincheck_data);
$scale_data      =  explode(" ", $scale_data);

echo $zaif_datas[3];


// $logs = $date.PHP_EOL.$tweets[1].$tweets[2].$tweets[3];

// $filename = date('Y-m-d').'.txt';

// $fp = fopen("bot_log/${filename}", 'a');

// fwrite($fp, $logs);
// fclose($fp);


// tweetを送信する
// $result = $twitter->post("statuses/update", array("status" => $tweet ));

 
// Tweetの成否を判定する
// if($twitter->getLastHttpCode() == 200) {
//     print "tweeted\n";
// } else {
//     print "tweet failedPHP_EOL";
// }
?>

<!-- 30分ごとにページを更新するJavaScript -->
<!-- <script type="text/javascript">var term = 1*60000;setTimeout('location.reload();',term-Date.now()%term);</script> -->
