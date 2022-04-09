<?php
include_once('functions.php');
class AplicationExt extends Aplication{
    public function extend($field, $el){
        switch ($field) {
            case 'table':
                foreach($el->find('tr') as $selector){        
                    $selector = pq($selector);
                    switch ($selector->find('td:eq(0)')->text()) {
                        case 'Код товара: ':
                            $data['code'] = $selector->find('td:eq(1)')->text();
                            break;
        
                        case 'Цвет:':
                            $data['color'] = $selector->find('td:eq(1)')->text();
                            break;
                    }
                }
                break;

            case 'specifications':
                foreach($el->find('li') as $selector){        
                    $selector = pq($selector);
                    switch ($selector->find('span:eq(0)')->text()) {
                        case 'Ширина':
                            $data['shirina'] = $selector->find('span:eq(1)')->text();
                            break;
        
                        case 'Длина':
                            $data['dlina'] = $selector->find('span:eq(1)')->text();
                            break;

                        case 'Тип раскладки:':
                            $data['tip_rascladci'] = $selector->find('span:eq(1)')->text();
                            break;
        
                        case 'Вставка:':
                            $data['vstavka'] = $selector->find('span:eq(1)')->text();
                            break;

                        case 'Материалы:':
                            $data['matirials'] = $selector->find('span:eq(1)')->text();
                            break;
        
                        case 'Особенность:':
                            $data['osobennost'] = $selector->find('span:eq(1)')->text();
                            break;

                        case 'Диаметр подставки:':
                            $data['diametr_podstavki'] = $selector->find('span:eq(1)')->text();
                            break;
        
                        case 'Вес':
                            $data['ves'] = $selector->find('span:eq(1)')->text();
                            break;

                        case 'Объём в упаковке':
                            $data['obuom_v_upacovke'] = $selector->find('span:eq(1)')->text();
                            break;
        
                        case 'Гарантия':
                            $data['garantia'] = $selector->find('span:eq(1)')->text();
                            break;
                    }
                }
                break;
            
            case 'images':
                // var_dump($el->html());die;
                foreach($el->find('div img') as $key => $selector){        
                    $selector = pq($selector);
                    if($key === 0){
                        $data['main_image'] = $this->check_switch('domain_to_parse').$selector->attr('src');
                    } else {
                        $data['more_photo'][] = $this->check_switch('domain_to_parse').$selector->attr('src');
                    }
                }
                break;

            default:
                return [];
                break;
        }
        
        return $data;
    }
}
$app = new AplicationExt;
require_once('phpQuery.php');

$parseUrl = $_GET['url'];
$doc = phpQuery::newDocument($app->get_content($app->check_switch('domain_to_parse').$parseUrl));

if($app->check_switch('parse_foreach') === 1){
    if($doc->find($app->check_switch('phpquery_foreach_selector'))->length() > 0) {
        foreach($doc->find( $app->check_switch('phpquery_foreach_selector') ) as $product){        
            $product = pq($product);
        
            $href = $product->find($app->check_switch('phpquery_get_field'))->attr('href');
            $newData[] = $href;
        }
        // var_dump($newData);
        if($i = $app->save_links($newData)){
            $data["next"] = true;
            $data["info"] = $i;
        } else {
            $data["next"] = false;
        }
        echo json_encode($data);
    }
}
if($app->check_switch('parse_foreach') === 0){
    if(gettype($app->check_switch('phpquery_get_field')) === 'array'){
        foreach ($app->check_switch('phpquery_get_field') as $key => $field) {
            if($field['type'] === 'text'){
                $newData[$parseUrl][$field['name']] = $doc->find( $field['selector'] )->text();
            }
            if($field['type'] === 'attr'){
                $newData[$parseUrl][$field['name']] = $doc->find( $field['selector'] )->attr($field['attr_name']);
            }
            if($field['type'] === 'html'){
                $newData[$parseUrl][$field['name']] = $doc->find( $field['selector'] )->html();
            }
            if($field['type'] === 'extend'){
                $el = $doc->find( $field['selector'] );
                $el = pq($el);
                if($fields = $app->extend($field['name'], $el)){
                    foreach ($fields as $key => $field) {
                        $newData[$parseUrl][$key] = $field;
                    }
                }
            }
        }
        if($i = $app->save_data($newData)){
            $data["next"] = true;
            $data["info"] = $i;
        } else {
            $data["next"] = false;
        }
        echo json_encode($data);
    }
    if(gettype($app->check_switch('phpquery_get_field')) === 'string'){
        
    }
}