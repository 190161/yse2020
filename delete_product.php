<?php
    if (session_status() == PHP_SESSION_NONE) {
//セッションを開始
	session_start();
    }

//ログイン確認
    if (isset($_SESSION['login'])=== false){
	$_SESSION['error2']="ログインしてください";
	//ログイン画面へ遷移
	header('Location:login.php');
    }

//データベース接続
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
    //文字コード「utf-8」
    $dbh = new PDO("mysql:host=localhost;db_name=zaiko2020_yse;charset=utf8;",  $user_name,  $password );

//書籍が選択されていない場合
    if(!isset($_POST['books'])){
        $_SESSION['success']= "削除する商品が選択されていません";
        //⑩在庫一覧画面へ遷移する。
        header('Location:zaiko_ichiran.php');
        exit;
    }

//書籍情報の取得
    function getId($id,$con){
    $sql= "SELECT * FROM books WHERE id = {$id}";
    $query=$con->query($sql);
        //実行した結果から1レコード取得し、returnで値を返す。
        return $query->fetch(PDO::FETCH_ASSOC);
	}
	
//削除フラグ
	function updateByid($id,$con,$flg){
		$sql = "UPDATE books SET deleteflg = {$flg} WHERE id = {$id}";
		$query= $con->query($sql);
	}

	//削除フラグを1にする
	foreach($_POST['books'] as $book_id){
		$book= getId($book_id,$pdo);
		$delete_flg=1;//1にすると削除
		$book=updateByid($book_id,$pdo,$delete_flg);
	}
	
?>
<!DOCTYPE html>
<html lang="ja">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
	<title>商品削除</title>
	<link rel="stylesheet" href="css/ichiran.css" type="text/css" />
</head>
<body>
	<!-- ヘッダ -->
	<div id="header">
		<h1>商品削除</h1>
	</div>

	<!-- メニュー -->
	<div id="menu">
		<nav>
			<ul>
				<li><a href="zaiko_ichiran.php?page=1">書籍一覧</a></li>
			</ul>
		</nav>
	</div>

	<form action="delete_product.php" method="post">
		<div id="pagebody">
			<!-- エラーメッセージ -->
			<div id="error">
			<?php
			if(isset($_SESSION['error3'])){
			echo $_SESSION['error3'];
			 }
			?>

            <!-- 書籍情報の表示 -->
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
						</tr>
					</thead>

					<?php
                    //書籍情報の受け渡し
    				foreach($_POST['books'] as $book_id){
						$book= getId($book_id,$pdo);
					
                   	//  在庫数が0でない場合エラーメッセージ エラー→ひとつ前の情報を取ってしまう
					 if($book['stock']>0){
						$_SESSION['error3']="在庫数が0ではありません";
					}else{
						$_SESSION['error3']="";
					}
    
                        
					?>

                    <!-- 書籍情報表示 -->
					<!-- <input type="hidden" value="<?php //echo $book['id'];?>" name="books[]"> -->
					<tr>
						<td><?php echo $book['id'];?></td>
						<td><?php echo $book['title'];?></td>
						<td><?php echo $book['author'];?></td>
						<td><?php echo $book['salesDate'];?></td>
						<td><?php echo $book['price'];?></td>
						<td><?php echo $book['stock'];?></td>

						<input type="hidden" name="stock[]" value="<?php echo $book['stock']; ?>">


					<?php
					}
					?>
				</table>
				<!-- <button type="submit" id="kakutei" formmethod="POST" name="add" value="ok">確定</button> -->
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
