<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>mission_5-1</title>
</head>
<body>  
    <?php
    //PHPでPOSTデータを受け取り
            @$name=htmlspecialchars($_POST["name"],ENT_QUOTES);
            @$comment=htmlspecialchars($_POST["comment"],ENT_QUOTES);
            @$display=$_POST["display"];
            @$delete=$_POST["delete"];
            @$edit=$_POST["edit"];
            @$pass=htmlspecialchars($_POST["pass"],ENT_QUOTES);
            @$edipass=htmlspecialchars($_POST["edipass"],ENT_QUOTES);
            @$delpass=htmlspecialchars($_POST["delpass"],ENT_QUOTES);
    // 最初にDBに接続する
    $dsn = 'データベース名';
    $user = 'ユーザー名';
    $password = 'パスワード';
    $pdo = new PDO($dsn, $user, $password, array(PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING));
    //array以下はエラーが起きた場合に表示するためのもの
    
    //CREATE文：テーブルを作る
    $sql = "CREATE TABLE IF NOT EXISTS tb5"
    ." ("
    . "id INT AUTO_INCREMENT PRIMARY KEY," //新しいデータが追加されると自動的に番号が振られる
    . "name char(32),"
    . "comment TEXT,"
    . "date char(32),"
    . "pass char(32)"
    .");";
    $stmt = $pdo->query($sql); //クエリ実行
    //新規投稿機能
    if(!empty($_POST["name"]) && !empty($_POST["comment"])
     &&empty($_POST["display"]) && !empty($_POST["pass"])){//ディスプレイに値が入っていないときは新規投稿モード
            $date = date("Y/m/d h:i:s"); //日時を変数に定義
        //INSERT文：データを入力する
            $sql = $pdo -> prepare("INSERT INTO tb5(name, comment,date,pass) 
            VALUES (:name, :comment,:date,:pass)");
            //各変数を定義する
            $sql -> bindParam(':name', $name, PDO::PARAM_STR);
            $sql -> bindParam(':comment', $comment, PDO::PARAM_STR);
            $sql -> bindParam(':date', $date, PDO::PARAM_STR);
            $sql -> bindParam(':pass', $pass, PDO::PARAM_STR);
            $sql -> execute();//クエリの実行
        }
            
    //編集選択機能をつける
    if(!empty($edit)&&!empty($edipass)){ //編集対象番号とパスワードが送信されたとき
    
    //SELECT文：入力したデータレコードを抽出し、表示する
     $sql = 'SELECT * FROM tb5';
     $stmt = $pdo->query($sql);
     $results = $stmt->fetchAll();
     foreach ($results as $row){
         if($row['id'] == $edit && $row['pass'] == $edipass){ //投稿番号と編集対象番号が同じでパスワードも同じ場合
                        $ename = $row['name'];
                        $ecomment=$row['comment'];
         }
      }
    }
    
    
    //編集実行機能をつける
    if( !empty($_POST["pass"])
      &&  !empty($_POST["display"]) && !empty($_POST["name"])//ディスプレイに値が入っているときは編集モード
      && !empty($_POST["comment"])){
          $date = date("Y/m/d h:i:s"); //日時を変数に定義
          
    
    //UPDATE文：入力されているデータレコードの内容を編集
    $id =$_POST["display"]; //変更する投稿番号として編集対象番号を指定
    $sql = 'UPDATE tb5 SET name=:name,comment=:comment,date=:date,pass=:pass WHERE id=:id';
    $stmt = $pdo->prepare($sql);
        //各変数を定義
    $stmt->bindParam(':name', $name, PDO::PARAM_STR);
    $stmt->bindParam(':comment', $comment, PDO::PARAM_STR);
    $stmt -> bindParam(':date', $date, PDO::PARAM_STR);
    $stmt-> bindParam(':pass', $pass, PDO::PARAM_STR);
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
    $stmt->execute();//実行
      }
      
      //削除機能をつける
      //DELETE文：入力したデータレコードを削除
    if(!empty($_POST["delete"])&&!empty($_POST["delpass"])){  
            
            $pass=$_POST["delpass"];//削除パスワードの定義
            $id = $_POST["delete"];
            $sql = 'delete from tb5 where id=:id AND pass=:pass';
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->bindParam(':pass', $pass, PDO::PARAM_INT);
            $stmt->execute();
    }
    ?>
    
    <form action="" method="post">
        <input type="text" name="name" placeholder="名前"
        value="<?php if(!empty($ename)){echo $ename;}?>"> <br>
        <input type="text" name="comment" placeholder="コメント"
        value="<?php if(!empty($ecomment)){echo $ecomment;}?>"><br>
        <input type="text" name="pass" placeholder="パスワード"><br>
        
        <input type="hidden" name="display"
        value="<?php if(!empty($edit) && $row['id']==$edit){echo $edit;} ?>"><br>
        <input type="submit" name="submit" value="送信"><br>
        
        
        <input type="number" name="delete" placeholder="削除対象番号"><br>
        <input type="text" name="delpass" placeholder="削除パスワード">
        <input type="submit" name="delete_submit" value="削除"><br>
        
        <input type="number" name="edit" placeholder="編集対象番号"><br>
        <input type="text" name="edipass" placeholder="編集パスワード">
        <input type="submit" name="edit_submit" value="編集"><br>
        </form>
        
    <?php
    //SELLECT文：入力したデータを取得して表示する
            $sql = 'SELECT * FROM tb5';
            $stmt = $pdo->query($sql);
            $results = $stmt->fetchAll();
            foreach ($results as $row){
        
            echo $row['id'].',';
            echo $row['name'].',';
            echo $row['comment'].',';
            echo $row['date'].'<br>';
            echo "<hr>";
            }
    ?>
</body>
</html>
