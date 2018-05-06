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
$tweet =  $doc->find("tbody")->find("tr:eq(0)")->text()."new"
          .$doc->find("tbody")->find("tr:eq(5)")->text()."new"
          .$doc->find("tbody")->find("tr:eq(6)")->text()."new"
          .$doc->find("tbody")->find("tr:eq(4)")->text()."new"
          .$doc->find("tbody")->find("tr:eq(20)")->text()."new"
          .date("Y年m月d日 H時i分s秒")."現在"."new"
          ."#ビットコイン"."new"
          ."#Bitcoin"."new"
          ."#btc"
          ;

//半角スペース、全角スペース、開業を取り除く（置換）
$tweet = str_replace(array(" ", "  ", PHP_EOL), "", $tweet);
//改行
$tweet = str_replace("new", PHP_EOL, $tweet);

echo $tweet;

//tweetを送信する
$result = $twitter->post("statuses/update", array("status" => $tweet ));

 
//Tweetの成否を判定する
if($twitter->getLastHttpCode() == 200) {
    print "tweeted\n";
} else {
    print "tweet failed\n";
}

echo exec('which php');

?>

<!-- ページを30分ごとに更新するJavaScript 
<script type="text/javascript">var term = 5*60000;setTimeout('location.reload();',term-Date.now()%term);</script>
-->