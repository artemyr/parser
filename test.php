<?header('Content-type: text/html; charset=utf-8');?>

<form method="POST" action="">
    <input name="fild_to_ilitirate" type="text" placeholder="поле для подразделения">
    <input name="seporator" type="text" placeholder="символ подразделения" value="|">
    <textarea name="text" type="text" placeholder="вводи текст"></textarea>
    <input type="submit" value="сделать!!">
</form>

<?php
echo 'id;name<br>
1;comp|hyemp|monitor<br>
2;mish<br>
3;huish|gavnish<br><br><br><br>';
if (empty($_POST['text'])) exit;

$curfild = $_POST['fild_to_ilitirate'];
$text = $_POST['text'];
$seporator = $_POST['seporator'];
$ready_answer = '';

function fild_index_to_separate($row,$curfild){
    $cols = explode(';', $row);
    foreach ($cols as $key => $col){
        $col = trim($col);
        echo $col.' '.$curfild.'<br>';
        if($col == $curfild) return $key;
    }
    die('not find fild to separate');
}

$rows = explode(PHP_EOL, $text);
$curfild = fild_index_to_separate($rows[0],$curfild);

foreach ($rows as $key => $row) {
    $cols = explode(';', $row);
    if(!strripos($cols[$curfild], $seporator)) {
        $ready_answer = $ready_answer.$row;
        continue;
    }
    
    $siparateCols = explode($seporator, $cols[$curfild]);
    foreach ($siparateCols as $key => $siparateCol) {
        $siparateCol = trim($siparateCol);
        foreach ($cols as $key => $col) {
            if ($key == $curfild) {$ready_answer = $ready_answer.$siparateCol.';';}
            else {$ready_answer = $ready_answer.$col.';';}
        }
        $ready_answer = $ready_answer.'<br>';
    }
}

echo 'отово:<br><pre>'.$ready_answer.'</pre>';