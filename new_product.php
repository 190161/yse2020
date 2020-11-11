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
function getByid($con){
    $sql = "SELECT * FROM books ORDER BY id DESC LIMIT 1";
    $query=$con->query($sql);
    return $query->fetch(PDO::FETCH_ASSOC);
}
function insertByid($id,$con,$title,$author,$salesDate,$price,$stock){
	$new_date = date('Y年m月d日', strtotime($salesDate));
	$sql = "INSERT INTO books(id, title, author,salesDate, price, stock)VALUES($id,'$title','$author','$new_date',$price,$stock)";
	$query=$con->query($sql);
}


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
$index = 0;
if((isset($_POST['add']) && is_numeric($_POST['stock'][$index]) && is_numeric($_POST['price'][$index]))){
	foreach($_POST['books'] as $book_id){
		$goukei = $_POST['stock'][$index];
		if($goukei > 100){
			//⑲SESSIONの「error」に「最大在庫数を超える数は入力できません」と設定する。
			$_SESSION['error']= "最大在庫数を超える数は入力できません";
			//⑳「include」を使用して「nyuka.php」を呼び出す。
			header('Location:new_product.php');
			//㉑「exit」関数で処理を終了する。
			exit;
		}else{
			// ⑯「getId」関数を呼び出し、変数に戻り値を入れる。その際引数に⑮の処理で取得した値と⑥のDBの接続情報を渡す。
			$book= getByid($pdo);
			$book=insertByid($book_id + 1,$pdo,$_POST['title'],$_POST['author'],$_POST['salesDate'],$_POST['price'][$index],$_POST['stock'][$index]);
			$index++;
		}
		$index++;
	}
	//㉚SESSIONの「success」に「入荷が完了しました」と設定する。
	$_SESSION['success']='新商品の追加が完了しました。';
	//㉛「header」関数を使用して在庫一覧画面へ遷移する。
	header("Location:zaiko_ichiran.php");
}else if((isset($_POST['add']) && !is_numeric($_POST['stock'][$index]))){
		//⑬SESSIONの「error」に「数値以外が入力されています」と設定する。
		$_SESSION['error']= "数値以外が入力されています";
		//⑭「include」を使用して「nyuka.php」を呼び出す。
		header('Location:new_product.php');
		//⑮「exit」関数で処理を終了する。
		exit;		
}
?>
<!DOCTYPE html>
<html lang="ja">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
	<title>新商品追加</title>
	<link rel="stylesheet" href="css/ichiran.css" type="text/css" />
</head>
<body>
	<!-- ヘッダ -->
	<div id="header">
		<h1>新商品追加</h1>
	</div>

	<!-- メニュー -->
	<div id="menu">
		<nav>
			<ul>
				<li><a href="zaiko_ichiran.php?page=1">追加書籍</a></li>
			</ul>
		</nav>
	</div>

	<form action="new_product.php" method="post">
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
    					// ⑯「getId」関数を呼び出し、変数に戻り値を入れる。その際引数に⑮の処理で取得した値と⑥のDBの接続情報を渡す。
						$book= getByid($pdo);
					?>
					<input type="hidden" value="<?php echo $book['id']/* ⑰ ⑯の戻り値からidを取り出し、設定する */;?>" name="books[]">
					<tr>
                        <td><?php echo	$book["id"] + 1/* ㉟ ㉞で取得した書籍情報からtitleを表示する。 */;?></td>
						<td><input type='text' name='title' size='5' maxlength='11' required></td>
                        <td><input type='text' name='author' size='5' maxlength='11' required></td>
						<td><input type='date' name='salesDate' value="<?php echo date('Y-m-d'); ?>" required></td>
						<td><input type='text' name='price[]' size='5' maxlength='11' required></td>
						<td><input type='text' name='stock[]' size='5' maxlength='11' required></td>
                        <td><input type='text' name='stock2[]' size='5' maxlength='11' required></td>
					</tr>
					<?php
					?>
				</table>
				<button type="submit" id="kakutei" formmethod="POST" name="add" value="1">確定</button>
			</div>
		</div>
	</form>
	<!-- フッター -->
	<div id="footer">
		<footer>株式会社アクロイト</footer>
	</div>
</body>
</html>
