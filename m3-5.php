<!DOCTYPE html>
<html>
    <head>
        <meta charset = "UTF-8">
        <title>mission3-5</title>
    </head>
    <body>
        <?php
        /*変数・配列名の整理
        編集：e
        新規：n
        削除：d
        ex) 新規のコメントは$n_comで順にn_com1, n_com2...としていく*/
        
        $filename = "mission3-5.txt";
        
        //【投稿フォーム】投稿機能！！編集してから送信されたものと区別する！！
        if(!empty($_POST["name"]) && !empty($_POST["comment"]) && !empty($_POST["pass"])){
            //formの名前・コメント・日付・パスワードを取得
            $name = $_POST["name"];
            $comment = $_POST["comment"];
            $date = date("Y/m/d H:i:s");
            $pass = $_POST["pass"];
            $e_num = $_POST["editnum"];
            
            //編集番号がない場合は新規投稿
            if(empty($e_num)){
                //新規投稿
                //投稿番号取得
                if(file_exists($filename)){
                    $n_num = file($filename, FILE_IGNORE_NEW_LINES);
                    $n_num2 = end($n_num);
                    $n_num3 = $n_num2[0]+1; //$n_num3が今後投稿番号になる
                }else{
                    $n_num3 = 1;
                }
                //１つにまとめる
                $n_all = $n_num3. "<>". $name. "<>". $comment. "<>". $date. "<>". $pass. "<>";
                //fileを追記モードでオープン
                $fp = fopen($filename, "a");
                fwrite($fp, $n_all. PHP_EOL);
                fclose($fp);
                
            }elseif(!empty($e_num)){
                //編集後
                //もともとのファイルの中身を変数ごとにバラバラで取得
                $e_lines = file($filename, FILE_IGNORE_NEW_LINES);
                $fp = fopen($filename, "w");
                //file内の変数を新しい名前で取得してループ処理
                foreach($e_lines as $e_line){
                    $e_str = explode("<>", $e_line);
                    $e_num2 = $e_str[0];
                    $e_pass = $e_str[4];
                    //fileとformの編集番号と投稿番号が同じ場合formで書いたものに書き換え
                    if($e_num2 == $e_num){
                        fwrite($fp, $e_num2. "<>". $name. "<>". $comment. "<>". $date. "<>". $pass. "<>". PHP_EOL);
                    //編集番号と投稿番号が違う場合はそのままfile内を表示
                    }else{
                        fwrite($fp, $e_line. PHP_EOL);
                    }
                }
                fclose($fp);
            }
        }
        
        //【編集フォーム】編集番号とパスワードを取得
        if(!empty($_POST["edit"]) && !empty($_POST["editpass"])){
            $e_num3 = $_POST["edit"];
            $e_pass = $_POST["editpass"];
            if(file_exists($filename)){
                //もともとのファイルの中身を変数ごとにバラバラで取得
               $e_lines2 = file($filename, FILE_IGNORE_NEW_LINES);
               //ループ処理で投稿番号と編集番号、パスワードが一致するものを探す
               //一致したものは新しい変数名として取得
               //file内の変数を新しい名前で取得してループ処理
               foreach($e_lines2 as $e_line2){
                   $e_str2 = explode("<>", $e_line2);
                   $e_num4 = $e_str2[0];
                   $e_pass2 = $e_str2[4];
                   //formとfileのNoが一致しているがpassが異なる場合はエラー表示させてファイルの中身を取得しない
                   if($e_num3 == $e_num4 && $e_pass != $e_pass2){
                       echo "パスワードが違います";
                    //formとfileのNoとpassが一致している場合はファイルの中身を取得
                   }else if($e_num3 == $e_num4 && $e_pass == $e_pass2){
                       $e_name = $e_str2[1];
                       $e_com = $e_str2[2];
                       break;
                   }
                }  
            }
           
        }
        
        
        /*deleteのパスワードがまだ*/
        //deleteが押され、パスワードが入力されたとき
        if(!empty($_POST["delete"]) && !empty($_POST["deletepass"])){
            $d_num = $_POST["delete"];
            $d_pass = $_POST["deletepass"];
            if(file_exists($filename)){
                //行ごとに配列を読み込む
                $d_lines = file($filename, FILE_IGNORE_NEW_LINES);
                $fp = fopen($filename, "w");

                //もともとのファイルの中身を変数ごとにばらばらにして番号を検索
                foreach($d_lines as $d_line){
                    $d_num2 = explode("<>", $d_line);
                    $d_num3 = $d_num2[0];
                    $d_pass2 = $d_num2[4];
                
                    //formとfileのNoが一致しているがpassが異なる場合はエラー表示させてファイルの中身は変えない
                    if($d_num == $d_num3 && $d_pass != $d_pass2){
                        echo "パスワードが違います";
                        fwrite($fp, $d_line. PHP_EOL);
                        
                    }else if($d_num == $d_num3 && $d_pass == $d_pass2){
                          
                    }else{
                        fwrite($fp, $d_line. PHP_EOL);

                    }
                    
                }
                fclose($fp);
                
                
            }
        }
        
        ?>
        
        <!--フォーム作成-->
        <form action = "" method = "post">
            <table>
                <!--投稿フォーム-->
                <tr>
                    <!--Name form-->
                    <th>名前</th>
                    <td>
                        <input type = "text"
                                name = "name"
                                value = <?php if(isset($e_name)){
                                                 echo $e_name;
                                                 }
                                        ?> 
                        >
                    </td>
                    
                    <!--comment form-->
                    <th>コメント</th>
                    <td>
                        <input type = "text"
                                name = "comment"
                                value = <?php if(isset($e_com)){
                                                 echo $e_com;
                                                 }
                                        ?> 
                        >
                    </td>
                    
                    <th>パスワード</th>
                    <td>
                        <input type = "password"
                                name = "pass">
                    </td>
                    
                    <!--send form-->
                    <td colspan = "2">
                        <input type = "submit"
                                value = "送信">
                    </td>
                    
                    <!--edit.no form-->
                    <td>
                        <input type = "hidden"
                                name = "editnum"
                                value = <?php if(isset($e_num3)){
                                                 echo $e_num3;
                                                 }
                                        ?> 
                        >
                    </td>
                </tr>
                
                <!--削除フォーム-->
                <tr>
                    <th>削除</th>
                    <td>
                        <input type = "number"
                                name = "delete">
                    </td>
                    
                    <th>パスワード</th>
                    <td>
                        <input type = "password"
                                name = "deletepass">
                    </td>
                    
                    <td colspan = "2">
                        <input type = "submit"
                                value = "削除">
                    </td>
                </tr>
                
                <!--編集フォーム-->
                <tr>
                    <th>編集</th>
                    <td>
                        <input type = "number"
                                name = "edit">
                    </td>
                    
                    <th>パスワード</th>
                    <td>
                        <input type = "password"
                                name = "editpass">
                    </td>
                    
                    <td colspan = "2">
                        <input type = "submit"
                                value = "編集">
                    </td>
                </tr>
            </table>
        </form>
        
        <!--ファイルにデータがあれば配列数分だけ表示させる-->
        <?php
        if(file_exists($filename)){
                $lines = file($filename, FILE_IGNORE_NEW_LINES);
                //要素の取得
                foreach($lines as $line){
                    $str = explode("<>", $line);
                    echo $str[0]. $str[1]. $str[2]. $str[3]. "<br>";
                }
            }
        ?>
    </body>
</html>
        