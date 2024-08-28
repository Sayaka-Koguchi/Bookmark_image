<?php 

//1.  POSTデータ取得
$category = $_POST['category']; // カテゴリーを取得
$book_name = $_POST['book_name']; // 書籍名を取得
$book_url = $_POST['book_url']; // URLを取得
$book_comment = $_POST['book_comment']; // コメントを取得
$book_status = $_POST['book_status']; // 読書状況を取得

// 画像アップロードの処理
$image_path = null; // 初期値をNULLに設定

// isset: 該当するデータが存在するかをチェックする
// ファイルデーが送られてきた場合のみ、画像保存に関連する処理を行う
// if(isset($_FILES['image'])){
// ファイルがアップロードされているか、エラーチェックも含めて確認
if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {  
   
  // imageの部分はinput type=fileのnameに相当
  // 必要に応じて書き換えるべき場所
  // tmp_nameは固定
  $upload_file = $_FILES['image']['tmp_name'];
   // var_dump($_FILES['image]);→中身を確認

   // フォルダ名を取得、今回は直書き
   $dir_name = 'img/';

   // 画像の拡張子を取得
   $extension = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);

   // 画像名を取得。今回はuniqid()を使って独自のIDを付与している
   $file_name = uniqid(). '.' . $extension;

   //画像の保存場所を設定
   $image_path = $dir_name. $file_name;

   // move_uploaded_file()で、一時的に保管されているファイルをimage_pathに移動させる。
   // if文の中で関数自体が実行される書き方をする場合、成功か失敗かが条件に設定
   // 失敗した場合はエラー表示を出して終了
      if(!move_uploaded_file($upload_file, $image_path)){
          exit('ファイルの保存に失敗しました');
      }

} else {
  // 画像がアップロードされなかった場合、imageカラムにはNULLをセットする
  $image_path = null;
}



//2. DB接続ー関数化　
include("func.php"); // 外部ファイルを読み込む
$pdo = db_conn();

//３．データ登録SQL作成

// 1. SQL文を用意
$sql = "INSERT INTO gs_bookmark_table(category,book_name,book_url,book_comment,book_status, image, date)VALUES(:category,:book_name,:book_url,:book_comment,:book_status, :image, NOW());";
$stmt = $pdo->prepare($sql);

//  2. バインド変数
// Integer 数値の場合 PDO::PARAM_INT
// String文字列の場合 PDO::PARAM_STR

$stmt->bindValue(':category', $category, PDO::PARAM_STR);
$stmt->bindValue(':book_name', $book_name, PDO::PARAM_STR);
$stmt->bindValue(':book_url', $book_url, PDO::PARAM_STR);
$stmt->bindValue(':book_comment', $book_comment, PDO::PARAM_STR);
$stmt->bindValue(':book_status', $book_status, PDO::PARAM_STR);
$stmt->bindValue(':image', $image_path, $image_path === null ? PDO::PARAM_NULL : PDO::PARAM_STR);


//  3. 実行
$status = $stmt->execute();

//４．データ登録処理後
if($status === false){
//SQL実行時にエラーがある場合（エラーオブジェクト取得して表示）
//関数化
  sql_error($stmt);

  }else{
//５．index.phpへリダイレクト
//関数化
redirect("index.php");

}    

?>