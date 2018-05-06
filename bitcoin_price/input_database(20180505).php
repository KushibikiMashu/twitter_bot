<?php

//日本時間を表示する
date_default_timezone_set('Asia/Tokyo');

//スクレイピングのライブラリを使用
require_once('phpQuery-onefile.php');

//ビットコインの現在価格を取得する
$html = file_get_contents("http://xn--eck3a9bu7cul981xhp9b.com/");
$doc = phpQuery::newDocument($html);

try
{
$dsn='mysql:host=127.0.0.1;dbname=DBname;charset=utf8';
$user='root';
$password='root';
$pdo = new PDO($dsn, $user, $password, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    ]);

$stmt = $pdo->prepare('INSERT INTO bitcoin_exchange (name, price, created_at) VALUES (:name, :price, :created_at)');

$zaif = $bitflyer = $coincheck = 0;

$exchanges = array(
	'zaif'		=> $zaif,
	'bitflyer'	=> $bitflyer,
	'coincheck'	=> $coincheck
	);

$exchanges['zaif']       =   $doc->find("tbody")->find("tr:eq(5)")->find("td:eq(1)")->text();
$exchanges['bitflyer']   =   $doc->find("tbody")->find("tr:eq(6)")->find("td:eq(1)")->text();
$exchanges['coincheck']  =   $doc->find("tbody")->find("tr:eq(4)")->find("td:eq(1)")->text();

$created_at = new Datetime();
$created_at = $created_at->format('Y-m-d H:i:s');

// zaifが0円の場合、bitflyerの金額を登録する
if (str_replace( array("円", ","), "", $exchanges['zaif']) === '0') {
	$exchanges['zaif'] = $exchanges['bitflyer'];
}

foreach ($exchanges as $name => $price) {
	$price  =  str_replace( array("円", ","), "", $price);

	$stmt->bindParam(':name', $name, PDO::PARAM_STR);
	$stmt->bindParam(':price', $price, PDO::PARAM_INT);
	$stmt->bindParam(':created_at', $created_at, PDO::PARAM_STR);

	$stmt->execute();
}

}
catch (Exception $e)
{
        print 'データーベース接続エラー発生';
        exit();
}
