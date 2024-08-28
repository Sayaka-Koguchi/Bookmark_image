<?php 
session_start();

//PHP:コード記述/修正の流れ
//1. insert.phpの処理をマルっとコピー。
//   POSTデータ受信 → DB接続 → SQL実行 → 前ページへ戻る
//2. $id = POST["id"]を追加
//3. SQL修正
//   "UPDATE テーブル名 SET 変更したいカラムを並べる WHERE 条件"
//   bindValueにも「id」の項目を追加
//4. header関数"Location"を「select.php」に変更

//1.  POSTデータ取得
$category = $_POST['category']; // カテゴリーを取得
$book_name = $_POST['book_name']; // 書籍名を取得
$book_url = $_POST['book_url']; // URLを取得
$book_comment = $_POST['book_comment']; // コメントを取得
$book_status = $_POST['book_status']; // 読書状況を取得
$id = $_POST['id']; // idを取得

//2. DB接続ー関数化　
// include("func.php"); // 外部ファイルを読み込む
// 関数群の読み込み
// 画像アップロード処理の前に移動
require_once('func.php');
loginCheck();
$pdo = db_conn();

// 画像アップロードの処理
$image_path = '';
if(isset($_FILES['image']) && $_FILES['image']['error'] == 0){
   $upload_file = $_FILES['image']['tmp_name'];
   $dir_name = 'img/';
   $extension = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
   $file_name = uniqid(). '.' . $extension;
   $image_path = $dir_name. $file_name;
   if(!move_uploaded_file($upload_file, $image_path)){
      exit('ファイルの保存に失敗しました');
   }
} else {
   // 新しい画像がアップロードされていない場合、既存の画像パスを保持
   $stmt = $pdo->prepare("SELECT image FROM gs_bookmark_table WHERE id=:id");
   $stmt->bindValue(':id', $id, PDO::PARAM_INT);
   $stmt->execute();
   $row = $stmt->fetch(PDO::FETCH_ASSOC);
   $image_path = $row['image']; // 既存の画像パスをセット
}



//３．データ登録SQL作成

// 1. SQL文を用意
$sql = "UPDATE gs_bookmark_table SET category=:category,book_name=:book_name,book_url=:book_url,book_comment=:book_comment,book_status=:book_status,image=:image WHERE id=:id";
$stmt = $pdo->prepare($sql);

//  2. バインド変数
// Integer 数値の場合 PDO::PARAM_INT
// String文字列の場合 PDO::PARAM_STR

$stmt->bindValue(':category', $category, PDO::PARAM_STR);
$stmt->bindValue(':book_name', $book_name, PDO::PARAM_STR);
$stmt->bindValue(':book_url', $book_url, PDO::PARAM_STR);
$stmt->bindValue(':book_comment', $book_comment, PDO::PARAM_STR);
$stmt->bindValue(':book_status', $book_status, PDO::PARAM_STR);
$stmt->bindValue(':image', $image_path, PDO::PARAM_STR);
$stmt->bindValue(':id', $id, PDO::PARAM_INT);

//  3. 実行
$status = $stmt->execute();

//４．データ登録処理後
if($status === false){
//SQL実行時にエラーがある場合（エラーオブジェクト取得して表示）
//関数化
  sql_error($stmt);

  }else{
//５．select.phpへリダイレクト
//関数化
redirect("select.php");

}    





?>