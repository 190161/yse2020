<?php

/*
 * ①session_status()の結果が「PHP_SESSION_NONE」と一致するか判定する。
 * 一致した場合はif文の中に入る。
 */
if (session_status() == PHP_SESSION_NONE/* ①の処理を行う */) {
	//②セッションを開始する
	session_start();
	session_regenerate_id();
};

//③SESSIONの「login」フラグがfalseか判定する。「login」フラグがfalseの場合はif文の中に入る。
if (isset($_SESSION['login'])=== false/* ③の処理を書く */){
	//④SESSIONの「error2」に「ログインしてください」と設定する。
	$_SESSION['error2']="ログインしてください";
	//⑤ログイン画面へ遷移する。
	header('Location:login.php');
}
//⑥データベースへ接続し、接続情報を変数に保存する
$db_name='zaiko2020_yse';
$host='localhost';
$user_name='zaiko2020_yse';
$password='2020zaiko';
$dsn = "mysql:dbname={$db_name};host={$host}";
try {
    $pdo = new PDO($dsn, $user_name, $password);
} catch (PDOException $e) {
	exit;
}

//⑦データベースで使用する文字コードを「UTF8」にする
$dbh = new PDO("mysql:host=localhost;db_name=zaiko2020_yse;charset=utf8;",  $user_name,  $password );

?>
<!DOCTYPE html>
<html lang="ja">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
	<title>入荷</title>
	<link rel="stylesheet" href="css/ichiran.css" type="text/css" />
</head>
<body>
	<!-- ヘッダ -->
	<div id="header">
		<h1>新機能追加</h1>
	</div>

	<!-- メニュー -->
	<div id="menu">
		<nav>
			<ul>
				<li><a href="zaiko_ichiran.php?page=1">追加書籍</a></li>
			</ul>
		</nav>
	</div>

	<form action="nyuka_kakunin.php" method="post">
		<div id="pagebody">
			<!-- エラーメッセージ -->
			<div id="error">
			<?php
			/*
			 * ⑬SESSIONの「error」にメッセージが設定されているかを判定する。
			 * 設定されていた場合はif文の中に入る。
			 */
			if(isset($_SESSION['error'])){
			 //⑭SESSIONの「error」の中身を表示する。
			$error = $_SESSION['error'];
			echo $error;
			 }
			?>
			</div>
			<div id="center">
				<table>
					<thead>
						<tr>
							<th id="id">ID</th>
							<th id="book_name">書籍名</th>
							<th id="author">著者名</th>
							<th id="salesDate">発売日</th>
							<th id="itemPrice">金額(円)</th>
							<th id="stock">在庫数</th>
							<th id="in">入荷数</th>
						</tr>
					</thead>
					<?php 
					/*
					 * ⑮POSTの「books」から一つずつ値を取り出し、変数に保存する。
					 */
    				//foreach($_POST['books'] as $book_id){
    					// ⑯「getId」関数を呼び出し、変数に戻り値を入れる。その際引数に⑮の処理で取得した値と⑥のDBの接続情報を渡す。
						//$book= getId($book_id,$pdo);
					?>
					<input type="hidden" value="<?php echo $book['id']/* ⑰ ⑯の戻り値からidを取り出し、設定する */;?>" name="books[]">
					<tr>
                        <td><input type='text' name='stock[]' size='5' maxlength='11' required></td>
						<td><input type='text' name='stock[]' size='5' maxlength='11' required></td>
                        <td><input type='text' name='stock[]' size='5' maxlength='11' required></td>
						<td><input type='text' name='stock[]' size='5' maxlength='11' required></td>
						<td><input type='text' name='stock[]' size='5' maxlength='11' required></td>
						<td><input type='text' name='stock[]' size='5' maxlength='11' required></td>
                        <td><input type='text' name='stock[]' size='5' maxlength='11' required></td>
					</tr>
					<?php
					 //}
					?>
				</table>
				<button type="submit" id="kakutei" formmethod="POST" name="decision" value="1">確定</button>
			</div>
		</div>
	</form>
	<!-- フッター -->
	<div id="footer">
		<footer>株式会社アクロイト</footer>
	</div>
</body>
</html>
