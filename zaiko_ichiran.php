<?php
/* 
【機能】
書籍テーブルより書籍情報を取得し、画面に表示する。
商品をチェックし、ボタンを押すことで入荷、出荷が行える。
ログアウトボタン押下時に、セッション情報を削除しログイン画面に遷移する。

【エラー一覧（エラー表示：発生条件）】
入荷する商品が選択されていません：商品が一つも選択されていない状態で入荷ボタンを押す
出荷する商品が選択されていません：商品が一つも選択されていない状態で出荷ボタンを押す
*/

//①セッションを開始する
session_start();
session_regenerate_id();

//②SESSIONの「login」フラグがfalseか判定する。「login」フラグがfalseの場合はif文の中に入る。
if (isset($_SESSION['login'])===false/* ②の処理を書く */){
	//③SESSIONの「error2」に「ログインしてください」と設定する。
	$_SESSION['error2']="ログインしてください";
	//④ログイン画面へ遷移する。
	header('Location:login.php');
}

//⑤データベースへ接続し、接続情報を変数に保存する
$db_name='zaiko2020_yse';
$host='localhost';
$user_name='zaiko2020_yse';
$password='2020zaiko';
$dsn = "mysql:dbname={$db_name};host={$host};cherset=utf8";
//⑥データベースで使用する文字コードを「UTF8」にする
try {
    $pdo = new PDO($dsn, $user_name, $password);
} catch (PDOException $e) {
	exit;
}
//⑦書籍テーブルから書籍情報を取得するSQLを実行する。また実行結果を変数に保存する
$sql="SELECT * FROM books WHERE deleteflg=0";//WHERE以降を外せば削除ファイルも閲覧可能
$row = $pdo->query($sql)->fetch(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="ja">
<head>
	<meta charset="UTF-8">
	<title>書籍一覧</title>
	<link rel="stylesheet" href="css/ichiran.css" type="text/css" />
</head>
<body>
	<div id="header">
		<h1>書籍一覧</h1>
	</div>
	<form action="zaiko_ichiran.php" method="post" id="myform" name="myform">
		<div id="pagebody">
			<!-- エラーメッセージ表示 -->
			<div id="error">
				<?php
				/*
				 * ⑧SESSIONの「success」にメッセージが設定されているかを判定する。
				 * 設定されていた場合はif文の中に入る。
				 */ 
				if(isset($_SESSION['success'])/* ⑧の処理を書く */){
					//⑨SESSIONの「success」の中身を表示する。
					$success = $_SESSION['success'];
			 		echo $success;
				}
				?>
			</div>
			
			<!-- 左メニュー -->
			<div id="left">
				<p id="ninsyou_ippan">
					<?php
						echo @$_SESSION["account_name"];
					?><br>
					<button type="button" id="logout" onclick="location.href='logout.php'">ログアウト</button>
				</p>
				<button type="submit" id="btn1" formmethod="POST" name="decision" value="3" formaction="nyuka.php">入荷</button>

				<button type="submit" id="btn1" formmethod="POST" name="decision" value="4" formaction="syukka.php">出荷</button>

				<button type="submit" id="btn1" formmethod="POST" name="decision" value="4" formaction="new_product.php">新標品追加</button>

				<button type="submit" id="btn1" formmethod="POST" name="decision" value="4" formaction="delete_product.php">商品削除</button>
			</div>
			<!-- 中央表示 -->
			<div id="center">

				<!-- 書籍一覧の表示 -->
				<table>
					<thead>
						<tr>
							<th id="check"></th>
							<th id="id">ID</th>
							<th id="book_name">書籍名</th>
							<th id="author">著者名</th>
							<th id="salesDate">発売日</th>
							<th id="itemPrice">金額</th>
							<th id="stock">在庫数</th>
							<!-- <th id="deleteflg">削除フラグ</th> -->
						</tr>
					</thead>
					<tbody>
						<?php
						//⑩SQLの実行結果の変数から1レコードのデータを取り出す。レコードがない場合はループを終了する。
						$query = $pdo->query($sql);
						while($row = $query->fetch(PDO::FETCH_ASSOC)/* ⑩の処理を書く */){
						// 	//⑪extract変数を使用し、1レコードのデータを渡す。
							extract($row);
						 	echo "<tr id='book'>";
							echo "<td id='check'><input type='checkbox' name='books[]'value=".$id."></td>";
						 	echo "<td id='id'>$id</td>";
						 	echo "<td id='title'>$title</td>";
						 	echo "<td id='author'>$author</td>";
						 	echo "<td id='date'>$salesDate</td>";
						 	echo "<td id='price'>$price</td>";
							echo "<td id='stock'>$stock</td>";
							// echo "<td id='deletefl'>$deleteflg</td>";
						 	echo "</tr>";
						 }
						?>
					</tbody>
				</table>
			</div>
		</div>
	</form>
	<div id="footer">
		<footer>株式会社アクロイト</footer>
	</div>
</body>
</html>
