<?php

$now_date = date('Y-m-d H:i:s');
$errors=[];
$lines=[];
define('FILE_PATH','./bbs.txt');
$comment='';
$name='';

if($_SERVER['REQUEST_METHOD']==='POST'){
    if(isset($_POST['comment'])===true){
        $comment=trim($_POST['comment']);
    }
    if(mb_strlen($comment)>100){
        $errors[]='コメント文字数オーバーエラー';
    }else if($comment===''){
        $errors[]='コメントが入力されていません';
    }
    if(isset($_POST['name'])===true){
        $name=trim($_POST['name']);
    }
    if(mb_strlen($name)>20){
        $errors[]='名前文字数オーバーエラー';
    }else if($name===''){
        $errors[]='名前が入力されていません';
    }
    if (count($errors) === 0) {
        $fp=fopen(FILE_PATH,'a');
        if($fp!==false){
            $log=$name.' : '.$comment.' - '.$now_date."\n";
            $result=fwrite($fp,$log);
            if ($result ===false){
                $errors[]='書き込み失敗';
            }
            fclose($fp);
        }
    }
}

// 最新の書き込みデータを読み込む
$lines=[];
if (is_readable(FILE_PATH)===true){
    $fp=fopen(FILE_PATH,'r');
    if($fp !==false){
        $text =fgets($fp);
        while($text !== false){
            $lines[]= htmlspecialchars($text,ENT_QUOTES);
            $text=fgets($fp);
        }
        fclose($fp);
        $lines = array_reverse($lines);
    }
}else{
    $errors[]='ファイルがありません';
}
?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>ひとこと掲示板</title>
</head>
<body>
    <h1>ひとこと掲示板</h1>
    <form method="post">
        お名前：<input type="text" name="name"/>
        ひとこと：<input type="text" name="comment"/>
        <input type="submit" value="送信"/>
    </form>
    <?php foreach ($errors as $error) { ?>
        <p><?php print $error; ?></p>
    <?php } ?>
     <?php foreach ($lines as $line) { ?>
        <p><?php print '・'.$line; ?></p>
    <?php } ?>
    
</body>
</html>