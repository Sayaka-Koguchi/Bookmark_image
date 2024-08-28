<?php
//XSS対応（ echoする場所で使用！それ以外はNG ）
function h($str){
    return htmlspecialchars($str, ENT_QUOTES);
}

//DB接続関数：db_conn()
function db_conn(){
    try {
        //ID:'root', Password: xamppは 空白 ''
        $db_name = "gs_db_kadai4"; //データベース名
        $db_id = "root"; //アカウント名
        $db_pw = ""; //パスワード：MAMPは‘root’
        $db_host = "localhost"; //DBホスト
        $pdo = new PDO('mysql:dbname='.$db_name.';charset=utf8;host='.$db_host, $db_id, $db_pw); //外部へ値を出す
        return $pdo;
      } catch (PDOException $e) {
        exit('DBConnectError:'.$e->getMessage());
      }
  }



//SQLエラー関数：sql_error($stmt)
function sql_error($stmt){
    $error = $stmt->errorInfo();
    exit('ErrorMessage:'.$error[2]);
  }



//リダイレクト関数: redirect($file_name)
function redirect($file_name){
    header("Location: ".$file_name);
    exit();
  }

// ログインチェク処理 loginCheck()
function loginCheck(){

  if(!isset($_SESSION['chk_ssid']) || $_SESSION['chk_ssid'] != session_id()){
      header('Location: login.php');
      exit('login error');
  
  }
  
  // session idを変更して保存
  session_regenerate_id();
  $_SESSION['chk_ssid'] = session_id();

}