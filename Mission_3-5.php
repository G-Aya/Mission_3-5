<!DOCTYPE html>
<html lang = "ja">
    <head>
        <meta charset = "UTF-8">
        <title>Mission_3-5</title>
    </head>
    
    <?php
    /*valueの後付け忘れずに*/
    $filename = "Mission_3-5.txt";
     /*投稿機能*/
    /*投稿フォームが空でないとき    */
    if(empty($_POST["name"]) === false && empty($_POST["comment"]) ===false){
        /*入力データの受信を変数に代入*/
        $name = $_POST["name"];
        $comment = $_POST["comment"];
        $pass = $_POST["pass"];
        /*日付データを変数に代入*/
        $date = date("Y/m/d H:m:s");
        
        /*editの有無で条件分岐*/
        /*editがないとき→新規投稿*/ 
        if(empty($_POST["edit"])===true &&isset($_POST["pass"])){
            /*ファイルの有無で条件分岐*/
            /*ファイルがあるときは、$number+1*/
            if(file_exists($filename)){
                /*読み込んだファイルを配列に格納*/
                $strs = file($filename);
                /*配列の数だけループ*/
                foreach($strs as $str){
                    /*それぞれの値の取得*/
                    $log = explode("<>",$str);
                    $num = $log[0]+1;
                    }
                
            }
            else{
                $num = 1 ;
            }
             /*書き込む文字列*/
        $str = "$num<>$name<>$comment<>$date<>$pass";
            /*ファイルを編集追記モードで開く*/
            $fp = fopen($filename , "a");
            /*入力データをファイルに書き込む*/
            fwrite($fp , $str .PHP_EOL);
            fclose($fp);
        }
            /*editがあるとき→編集*/
            else{
                /*入力データを変数に代入*/
                $edit = $_POST["edit"];
                /*読み込んだファイルの中身を配列に格納*/
                $strs = file($filename,FILE_IGNORE_NEW_LINES);
                /*ファイルを書き込みモードで開き、中身を空にする*/
                $fp = fopen($filename , "w");
                /*配列の数だけループ*/
                foreach($strs as $str){
                 /*explode関数でそれぞれの値を取得*/
                 $log = explode("<>",$str);
                 /*パスワードの一致のとき*/
                 
                  if($log[4] === $pass){
                      if($log[0] === $edit) {
                          /*投稿番号をなおす*/
                          fwrite($fp,"$edit<>$name<>$comment<>$date<>$pass".PHP_EOL);
                      }
                      else{
                          /*フォームから編集されたものを上書き*/
                          fwrite($fp,$str.PHP_EOL);
                        }
                   }
                    else{
                        /*パスワード一致しないところはそのまま書き込む*/
                        fwrite($fp,$str.PHP_EOL);
                    }
                }
            
                fclose($fp);
            }
    }

    /*削除機能*/
    /*dnum(削除番号)とdpass(削除フォーム内パス)有*/
    if(empty($_POST["dnum"]) === false && empty($_POST["dpass"]) === false){
        /*入力データを変数に代入*/
        $dnum = $_POST["dnum"];
        $dpass = $_POST["dpass"];
        /*読み込みファイルの中身を入れてへ格納*/
        $strs = file($filename,FILE_IGNORE_NEW_LINES);
        /*ファイルを書きこみモードで開き、中身を空に*/
        $fp = fopen($filename , "w");
        /*配列の数だけループ*/
        foreach($strs as $str){
            /*explode関数でそれぞれの値取得*/
            $log = explode("<>",$str);
            
          /*パスワードが一致しているとき*/
            if($log[4] == $dpass){
                /*削除番号と一致しないもの*/
                if($log[0] != $dnum){
                   /*入力データのファイル書き込み*/
                   fwrite($fp,$str.PHP_EOL);  
                }
            }
            /*パスワードが一致しないとき、そのままにする
            ここでfwriteをしないと全部なくなる*/
            else{
                /*入力データのファイル書き込み*/
                fwrite($fp,$str.PHP_EOL);
            }
        }
        fclose($fp);
    }
    /*編集選択機能*/
    /*編集フォームの送信の有無で条件分岐*/
    /*送信があるとき*/
    if(!empty($_POST["enum"]) && !empty($_POST["epass"])){
    /*入力データの受け取りを変数に代入*/    
   /* $enum = $_POST["enum"];
    $epass = $_POST["epass"];*/
        
    
    /*読み込んだファイルの中身を配列に格納*/
    $strs = file($filename);
    /*配列の数だけループ*/
        foreach($strs as $str){
        /*explode関数でそれぞれの値を取得*/
        $log = explode("<>",$str);
        /*投稿番号と編集番号、パスワードの一致のとき
        該当投稿の名前とコメントの取得*/
        $log[0] = $enum;
        $log[4] = $epass;
        $enum = $_POST["enum"];
    $epass = $_POST["epass"];
            if($epass == $log[4]){
                if($enum == $log[0]){
            $eenum = $log[0];
            $ename = $log[1];
            $ecomment = $log[2];
            }
            }
        }
    }
   
    ?>
    <body>
    <form action = " " method = "post">
    <!--投稿フォーム -->
        <input type = "text" name = "name" placeholder = "名前"
        value = "<?php if(!empty($_POST["esub"])&&!empty($_POST["epass"])){echo $ename;}?>">
        <input type = "text" name = "comment" placeholder = "コメント"
        value = "<?php if(!empty($_POST["esub"])&&!empty($_POST["epass"])){echo $ecomment ;}?>">
        <input type = "text" name = "pass" placeholder = "パスワード">
        <input type = "text" name = "edit"
        value = "<?php if(!empty($_POST["esub"])&&!empty($_POST["epass"])){echo $eenum ;}?>">
        <input type = "submit" name = "submit">
        </form>
        <!--削除フォーム-->
    <form action = " " method = "post">
        <input type = "number" name = "dnum" placeholder = "削除番号">
        <input type = "text" name = "dpass" placeholder = "削除パスワード">
        <input type = "submit" name = "dsub" value = "削除">
        </form>
        <!--編集フォーム -->
    <form action = " " method = "post">
        <input type = "number" name = "enum" placeholder = "編集番号">
        <input type = "text" name = "epass" placeholder = "編集パスワード">
        <input type = "submit" name = "esub" value = "編集"></form>
   
<?php
$filename = "Mission_3-5.txt";
/*表示機能*/
/*ファイルがあるときのみ行う*/
if(file_exists($filename)){
    /*ファイルの中身を変数に格納する*/
    $strs = file($filename);
    /*配列を変数に格納*/
    foreach($strs as $str){
        /*explode関数でそれぞれの値を取得*/
        $log = explode("<>",$str);
        /*取得した値を反映*/
        echo $log[0]." ".$log[1]." ".$log[2]." ".$log[3]."<br>";
    }
}
?>
</body>
</html>