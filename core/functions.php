<?php

class Aplication{
    private $config;
    private $urls;

    function __construct(){
        $this->config = include_once($_SERVER["DOCUMENT_ROOT"].'/config.php');
        if(empty($this->config)) $this->denied('wrong config');

        $this->check_auth($_GET['guard_key']);

        if(!empty($this->config['urls_from_file'])){
            $this->urls =  $this->read_file($_SERVER["DOCUMENT_ROOT"].$this->config['urls_from_file'].".txt");
        } else {
            $this->urls = $this->config['urls_to_pase'];
        }
    }

    public function print_config(){
        var_dump($this->config);
    }

    private function check_auth($key){
        if (empty($this->config['guard_key']))  $this->denied('wrong config');
            
        if(empty($_COOKIE['key'])){
            if (empty($key)) $this->denied('access denied');
            if ($key === $this->config['guard_key']) {
                setcookie("key", $key, time()+(60*60*24),'/');
                header('location: /parser.php');
            }
        }

        if( $_COOKIE['key'] === $this->config['guard_key'] ){
            return true;
        } else {
            $this->denied('old access data');
        }
    }

    public function check_switch($switch){
        return $this->config[$switch];
    }

    public function get_body(){
        ?>
        <button onclick="set_status_parser(1);">activate parser</button>
        <button onclick="set_status_parser(0);">deactivate parser</button>
        <?
        
        if ($this->check_switch('show_result')) {
            $fp = fopen("output/parsedatabase.txt", "r"); // Открываем файл в режиме чтения
            if ($fp){
                while (!feof($fp)){
                    $mytext = fgets($fp, 99999);
                }
            }
            else {
                echo "open file failure";
                fclose($fp);
                return;
            }
            fclose($fp);
            
            $data = json_decode($mytext);
            foreach ($data as $key => $value){
                $output += $key . ';';
                foreach ($value as $key => $param) {
                    $output += $param . ';';
                }
                $output += '<br>';
            }
            echo $output;
        }
    }

    public function get_scripts(){
        if ($this->check_switch('need_jquery')) {
            ?><script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.0/jquery.min.js"></script><?
        }
        $this->get_general_parse_script();
    }

    public function get_head(){
        if($this->config['theme'] === 'dark'){
            ?>
            <style>
                body {
                    background:black;
                    color:white;
                }
            </style>
            <?
        }
        ?><meta charset="utf-8" /><?
    }

    private function get_general_parse_script(){
        ?>
        <script>
            var active = <?=$this->config['active']?>;
            function set_status_parser(st){
                active = st;
                console.log('parser ' + st);
            }
        </script>
        <script>
            function rq(url){
                $.ajax({
                    url: url,
                    success: function(answer){
                        console.log('ans: ' + answer);

                        try {
                            answer = JSON.parse(answer);
                            console.log('parse: ' + answer);

                            if (answer.next) {
                                next = true;
                                page++;
                                console.log('next go');
                            } else {
                                next = false;
                            }
                        }
                        catch (e) {
                            // console.log(e);
                            next = false;
                        }
                    }
                });
            }    

            var next = true;
            var page = 1;
            var urls = [
                <?foreach($this->urls as $url):?>
                "<?=$url?>",
                <?endforeach?>
            ];

            setInterval(() => {        
                if (active && next){
                    if(!urls[page-1]){
                        active = false;
                        console.log('все ссылки спарсины!!!');
                        return;
                    }

                    console.log('запрос страница: ' + page);
                    rq('/core/quary.php?url=' + urls[page-1]);
                    next = false;
                }
            }, <?=$this->config['timeout']?>);

        </script>
        <?
    }

    private function denied($str){
        die('<br><br>'.$str);
    }


    // query functions
    public function get_content2($url){
        $ch = curl_init();
        curl_setopt($ch,CURLOPT_URL,$url);
        curl_setopt($ch,CURLOPT_FOLLOWLOCATION,true);
        curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);
        $res = curl_exec($ch);
        curl_close($ch);
        return $res;
    }
    public function get_content($url){
        $agent= 'Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; SV1; .NET CLR 1.0.3705; .NET CLR 1.1.4322)';
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_VERBOSE, true);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_USERAGENT, $agent);
        curl_setopt($ch, CURLOPT_URL,$url);
        $res = curl_exec($ch);
        curl_close($ch);
        return $res;
    }
    public function ebr($cont){echo $cont.'<br>';}
    public function ebrd($cont){echo $cont.'<br>';die();}
    public function emasd($cont){
        foreach ($cont as $item){
            var_dump($item).'<br>';
        }
        die();
    }

    public function read_file($path){
        if ( file_exists($path)) {
            $fp = fopen($path, 'r'); // Открываем файл в режиме чтения
            if ($fp){
                while (!feof($fp)){
                    $mytext = fgets($fp, 9999999);
                }
                fclose($fp);
            }
            else {
                fclose($fp);
                $this->denied('Ошибка при открытии файла: '.$path);
            }

            if(!empty($mytext)){
                if($j = json_decode($mytext)){
                    return $j;
                }
                $this->denied('Ошибка парсинга файла');
            }
            return [];
        } else {
            return [];
        }
    }

    public function save_links($arr){
        $out = false;
        $data = $this->read_file($_SERVER["DOCUMENT_ROOT"]."/output/".$this->config['output_file_name'].".txt");

        if(!empty($data)){
            foreach ($arr as $key => $el) {
                if(!in_array($el, $data)){
                    array_push($data, $el);
                    $out['push'][] = $el;
                } else {
                    $out['update'][] = $el;
                }
            }
        } else {
            $data = $arr;
            $out['push'][] = $arr;
        }
        $data = json_encode($data);

        $fp = fopen($_SERVER["DOCUMENT_ROOT"]."/output/".$this->config['output_file_name'].".txt", "w");
        fwrite($fp, $data);
        fclose($fp);
        return $out;
    }

    public function save_data($arr){
        $out = false;
        $data = $this->read_file($_SERVER["DOCUMENT_ROOT"]."/output/".$this->config['output_file_name'].".txt");

        if(!empty($data)){
            foreach ($arr as $key => $el) {
                if(empty($data->$key)){
                    $data->$key = $el;
                    $out['push'][$key] = $el;
                } else {
                    $data->$key = $el;
                    $out['update'][$key] = $el;
                }
            }
        } else {
            $data = $arr;
            $out['push'][] = $arr;
        }
        $data = json_encode($data);

        $fp = fopen($_SERVER["DOCUMENT_ROOT"]."/output/".$this->config['output_file_name'].".txt", "w");
        fwrite($fp, $data);
        fclose($fp);
        return $out;
    }

}