<?php
    $dsn = 'データベース名';
	$user = 'ユーザー名';
	$pw = 'パスワード';
	$pdo = new PDO($dsn, $user, $pw, array(PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING));

    if(isset($_POST["comment"]) && isset($_POST["name"]) && isset($_POST["password"])){
        if(empty($_POST["edit_number"])){
            $sql=$pdo -> prepare("INSERT INTO message (name,comment,password) VALUES (:name,:comment,:password)");
            $sql -> bindParam(':name',$name,PDO::PARAM_STR);
            $sql -> bindParam(':comment',$comment,PDO::PARAM_STR);
            $sql -> bindParam(':password',$password,PDO::PARAM_STR);
            $name=$_POST["name"];
            $comment=$_POST["comment"];
            $password=$_POST["password"];
            $sql -> execute();
        }
        else{
            $id=$_POST["edit_number"];
            $name=$_POST["name"];
            $comment=$_POST["comment"];
            $sql='UPDATE message SET name=:name,comment=:comment,password=:password WHERE id=:id';
            $stmt=$pdo -> prepare($sql);
            $stmt ->bindParam(':name',$name,PDO::PARAM_STR);
            $stmt ->bindParam(':comment',$comment,PDO::PARAM_STR);
            $stmt ->bindParam(':password',$password,PDO::PARAM_STR);
            $stmt ->bindParam(':id',$id,PDO::PARAM_INT);
            $stmt ->execute();
        }
    }
    
    if(isset($_POST["delete"]) && isset($_POST["delete_password"])){
        $delete=$_POST["delete"];
        $delete_password=$_POST["delete_password"];
        $sql='SELECT * FROM message WHERE id=:delete';
        $stmt=$pdo -> prepare($sql);
        $stmt -> bindParam(':delete',$delete,PDO::PARAM_INT);
        $stmt -> execute();
        $results=$stmt -> fetchAll();
        foreach($results as $row){
            $password=$row['password'];
        }
        
        if($password==$delete_password){
            $sql='delete from message where id=:delete';
            $stmt=$pdo -> prepare($sql);
            $stmt -> bindParam(':delete',$delete,PDO::PARAM_INT);
            $stmt -> execute();
        }
    }
    
    if(isset($_POST["edit"]) && isset($_POST["edit_password"])){
        $edit_number=$_POST["edit"];
        $edit_password=$_POST["edit_password"];
        $sql='SELECT * FROM message WHERE id=:edit_number';
        $stmt=$pdo -> prepare($sql);
        $stmt -> bindParam(':edit_number',$edit_number,PDO::PARAM_INT);
        $stmt -> execute();
        $results=$stmt ->fetchAll();
        foreach($results as $row){
            $password=$row['password'];
        }
        
        if($password==$edit_password){
            $sql='SELECT * FROM message WHERE id=:edit_number';
            $stmt=$pdo -> prepare($sql);
            $stmt -> bindParam(':edit_number',$edit_number,PDO::PARAM_INT);
            $stmt -> execute();
            $results=$stmt -> fetchAll();
            foreach($results as $row){
                $edit_name=$row['name'];
                $edit_comment=$row['comment'];
            }
        }
    }
?>

<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>mission_5-1</title>
</head>
<body>
    <form action="" method="POST">
        <input type="text" name="name" placeholder="名前" 
        value="<?php if(isset($edit_name)){echo $edit_name;} ?>" required>
        <input type="text" name="comment" placeholder="コメント" 
        value="<?php if(isset($edit_comment)){echo $edit_comment;} ?>" required>
        <input type="text" name="password" placeholder="パスワード" required>
        <input type="submit" value="送信">
        <input type="hidden" name="edit_number" 
        value="<?php if(isset($edit_number)){echo $edit_number;} ?>">
    </form>
    <form action="" method="POST">
        <input type="text" name="delete" placeholder="削除対象番号">
        <input type="text" name="delete_password" placeholder="パスワード">
        <input type="submit" value="削除">
    </form>
    <form action="" method="POST">
        <input type="text" name="edit" placeholder="編集対象番号">
        <input type="text" name="edit_password" placeholder="パスワード">
        <input type="submit" value="編集">
    </form>
    <p>
        <?php
            $sql='SELECT * FROM message';
            $stmt=$pdo -> query($sql);
            $results=$stmt -> fetchAll();
            foreach($results as $row){
                echo $row['id'];
                echo $row['name'];
                echo $row['comment'];
                echo $row['created_at'].'<br>';
            }
        ?>
    </p>
</body>
</html>