<?php

require_once('Tweet_bot_20180417.php');

try
{
$dsn='mysql:host=127.0.0.1;dbname=DBname;charset=utf8';
$user='root';
$password='root';
$pdo = new PDO($dsn, $user, $password, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    ]);

$stmt = $pdo->prepare('INSERT INTO bitcoin_price (zaif, bitflyer, coincheck, created_at) VALUES (:zaif, :bitflyer, :coincheck, :created_at)');

$buy_zaif       =   $doc->find("tbody")->find("tr:eq(5)")->find("td:eq(1)")->text();
$buy_bitflyer   =   $doc->find("tbody")->find("tr:eq(6)")->find("td:eq(1)")->text();
$buy_coincheck  =   $doc->find("tbody")->find("tr:eq(4)")->find("td:eq(1)")->text();

$buy_zaif       =   str_replace( array("円", ","), "", $buy_zaif);
$buy_bitflyer   =   str_replace( array("円", ","), "", $buy_bitflyer);
$buy_coincheck  =   str_replace( array("円", ","), "", $buy_coincheck);
$created_at = new Datetime();
$created_at = $created_at->format('Y-m-d H:i:s');

$stmt->bindParam(':zaif', $buy_zaif, PDO::PARAM_INT);
$stmt->bindParam(':bitflyer', $buy_bitflyer, PDO::PARAM_INT);
$stmt->bindParam(':coincheck', $buy_coincheck, PDO::PARAM_INT);
$stmt->bindParam(':created_at', $created_at, PDO::PARAM_STR);

$stmt->execute();

}
catch (Exception $e)
{
        print 'データーベース接続エラー発生';
        exit();
}
