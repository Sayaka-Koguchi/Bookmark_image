<?php
// エラーメッセージ表示
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();

// 関数群の読み込み
require_once('func.php');
loginCheck();

//1.  DB接続ー関数化
// include("func.php"); // 外部ファイルを読み込む
$pdo = db_conn();


//2. ステータスごとのデータカウントSQL作成
$sql_count = "SELECT book_status, COUNT(*) as count FROM gs_bookmark_table GROUP BY book_status";
$stmt_count = $pdo->prepare($sql_count);
$status_count = $stmt_count->execute(); //実行：true or false

// ステータスごとのデータカウント表示
// 関数化
if($status_count == false){
//execute（SQL実行時にエラーがある場合）
sql_error($stmt);
}


// book_statusをカウント
$Statuses = [];
while($result_count = $stmt_count->fetch(PDO::FETCH_ASSOC)) {
  $statuses[$result_count['book_status']] = $result_count['count'];
}
// ステータスが存在しない場合の初期化
$statuses = array_merge(['notStarted' => 0, 'inProgress' => 0, 'completed' => 0], $statuses);

//3. 検索クエリの取得
$query = isset($_GET['query']) ? $_GET['query'] : '';
$category = isset($_GET['category']) ? $_GET['category'] : '';
$status_filter = isset($_GET['status']) ? $_GET['status'] : '';

//4．データ取得SQL作成
$sql = "SELECT * FROM gs_bookmark_table WHERE 1=1";
$params = [];

if ($query) {
    $sql .= " AND (book_name LIKE ? OR book_url LIKE ? OR category LIKE ?)";
    $search_term = "%" . $query . "%";
    array_push($params, $search_term, $search_term, $search_term);
}

if ($category) {
    $sql .= " AND category = ?";
    array_push($params, $category);
}

if ($status_filter) {
    $sql .= " AND book_status = ?";
    array_push($params, $status_filter);
}

$stmt = $pdo->prepare($sql);
$stmt->execute($params);


//5. データ表示エラーチェック
$status = $stmt->execute(); //実行：true or false
if($status == false){
  sql_error($stmt);
}

// //3．データ取得SQL作成
// $sql = "SELECT * FROM gs_bookmark_table";
// $stmt = $pdo->prepare($sql);
// $status = $stmt->execute(); //実行：true or false

// //4．データ表示
// if($status == false){
//    //execute（SQL実行時にエラーがある場合） 
//    //関数化 
//     sql_error($stmt);
// }

//5. ステータスとカテゴリーの日本語ラベルマッピング
$status_labels = [
  'notStarted' => '未着手',
  'inProgress' => '進行中',
  'completed' => '完了',
];

$category_labels = [
  'literature' => '文学',
  'politics' => '政治',
  'society' => '社会',
  'economy' => '経済',
  'science' => '科学',
  'history' => '歴史',
  'art' => '芸術',
  'Entertainment' => 'エンタメ',
  'others' => 'その他'
];


?>


<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ブックマーク表示</title>
    <style>
       body {
        font-family: Arial, sans-serif; /* 全体のフォントをArialに設定 */
    }
        table {
            width: 100%;
            border-collapse: collapse;
            table-layout: fixed; /* テーブルのレイアウトを固定 */
            
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
        }
        th {
            background-color: #f2f2f2;
        }
        .book_url {
            width: 20% !important; /* book urlの幅を設定 */
            overflow-wrap: break-word; /* 長いURLを折り返す */
        }
        .book_status{
          font-weight: bold;
          font-size: 18px;
        }

        /* 検索フォームのスタイル */
        #search-form {
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 10px;
            margin-bottom: 20px;
            flex-wrap: wrap;
        }
        #search-form input[type="text"],
        #search-form select {
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 16px;
        }
        #search-form button {
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            font-size: 16px;
            cursor: pointer;
        }
        #search-form button[type="submit"] {
            background-color: #0000FF;
            color: white;
        }
        #search-form button[type="button"] {
            background-color: #FF0033;
            color: white;
        }

        #search-form button:hover {
        opacity: 0.8; /* ホバー時の透明度 */
        }

         /* ヘッダーとナビゲーションのスタイル */
         header {
            background-color: #333;
            color: white;
            padding: 10px 0;
            text-align: center;
        }
        header nav {
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 20px;
        }
        header nav a {
            color: white;
            text-decoration: none;
            padding: 10px 20px;
            border-radius: 5px;
            transition: background-color 0.3s;
        }
        header nav a:hover {
            background-color: #555;
        }
        header nav a:active {
            background-color: #777;
        }

    </style>

    <script>
      // aタグからPOSTリクエストするため、JS POSTメソッドを使用
      function logout(){
        var form = document.createElement('form');
        form.method = 'POST';
        form.action = 'logout.php';
        document.body.appendChild(form);
        form.submit();

      }

      // リセットボタンの動作を制御
      // window.location.hrefを使用してselect.phpにリダイレクトし、ページをリロード
      function resetForm() {
        window.location.href = 'select.php';
      }



    </script>


</head>
<body id="main">
<header>
    <nav>
      <?=$_SESSION["name"]?>さん、こんにちは！
      <a href="index.php">ブックマーク</a>
      <a href="javascript:void(0);" onclick="logout();">ログアウト</a>

    </nav>
  </header>

  <main>
  <div class="container">
    <h1>ブックマーク一覧</h1>
        <div class="bookmark-list">

      <!-- 読書状況の詳細を表示 -->
        <p class="book_status">＊読書状況一覧＊</p>
        <p>未着手：　<?= $statuses['notStarted']?></p>
        <p>進行中：　<?= $statuses['inProgress']?></p>
        <p>完了：　<?= $statuses['completed']?></p>

       <!-- 検索フォームの追加 -->
      <form id="search-form" action="select.php" method="GET">
        <input type="text" name="query" placeholder="タイトル、URL、タグで検索" value="<?= htmlspecialchars($query, ENT_QUOTES, 'UTF-8') ?>">
        
        <select name="category">
          <option value="">カテゴリーを選択</option>
          <?php foreach ($category_labels as $key => $value): ?>
            <option value="<?= $key ?>" <?= $category == $key ? 'selected' : '' ?>><?= $value ?></option>
          <?php endforeach; ?>
        </select>
        
        <select name="status">
          <option value="">読書状況を選択</option>
          <?php foreach ($status_labels as $key => $value): ?>
            <option value="<?= $key ?>" <?= $status_filter == $key ? 'selected' : '' ?>><?= $value ?></option>
          <?php endforeach; ?>
        </select>

        <button type="submit">検索</button>
        <button type="button" onclick="resetForm()">リセット</button> <!-- リセットボタンの追加 -->
      </form>


        <!-- PHP でデータを取得し、以下の形式で表示する -->
        <table>
            <tr>
                <th>登録日</th>
                <th>カテゴリー</th>
                <th>書籍名</th>
                <th class="book_url">URL</th> <!-- クラスを追加 -->
                <th>コメント</th>
                <th>読書状況</th>
                <th>画像</th>
                <th>更新</th>
                <th>削除</th>
            </tr>


        <?php while ($result = $stmt->fetch(PDO::FETCH_ASSOC)): ?> 
          <!-- <tr> 
          <td><?= htmlspecialchars($result['date'], ENT_QUOTES, 'UTF-8') ?></td>
          <td><?= htmlspecialchars($category_labels[$result['category']], ENT_QUOTES, 'UTF-8') ?></td>
          <td><?= htmlspecialchars($result['book_name'], ENT_QUOTES, 'UTF-8') ?></td>
          <td class="book_url">
          <a href="<?= htmlspecialchars($result['book_url'], ENT_QUOTES, 'UTF-8') ?>
            " target="_blank" rel="noopener noreferrer">
          <?= htmlspecialchars($result['book_url'], ENT_QUOTES, 'UTF-8') ?>
          </a></td>
          <td><?= htmlspecialchars($result['book_comment'], ENT_QUOTES, 'UTF-8') ?></td>
          <td><?= htmlspecialchars($status_labels[$result['book_status']], ENT_QUOTES, 'UTF-8') ?></td>
          </tr> -->
          <!-- 関数化 -->
          <tr>
            <!-- <td><?= h($result['id']) ?></td>   -->
            <td><?= h($result['date']) ?></td>
            <td><?= h($category_labels[$result['category']]) ?></td>
            <td><?= h($result['book_name']) ?></td>
            <td class="book_url">
                <a href="<?= h($result['book_url']) ?>" target="_blank" rel="noopener noreferrer">
                    <?= h($result['book_url']) ?>
                </a>
            </td>
            
            <td><?= h($result['book_comment']) ?></td>
            <td><?= h($status_labels[$result['book_status']]) ?></td>

            <td>
              <?php if (!empty($result['image'])): ?>
                <img src="<?= h($result['image']) ?>" class="image-class" style="max-width:100px; max-height:100px;">
              <?php endif; ?>
            </td>
            
            <td>
                <form method="GET" action="detail.php" class="action-form">
                    <input type="hidden" name="id" value="<?= h($result['id']) ?>">
                    <input type="submit" value="更新">
                </form>
            </td>
            
            <td>
                <form method="POST" action="delete.php" style="display:inline;">
                    <input type="hidden" name="id" value="<?= h($result['id']) ?>">
                    <?php if($_SESSION["kanri_flg"]=="1"){ ?>
                    <input type="submit" value="削除" onclick="return confirm('本当に削除しますか？')">
                    <?php } else { ?>
                      管理者専用
                    <?php }?>
                </form>
            </td>
          </tr>

        <?php endwhile; ?> 

      </table>

       

        </div>

    </div>

  </main>


    
</body>
</html>