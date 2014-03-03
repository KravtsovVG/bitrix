<?
abstract class DA {

    //public $page_number = 1;
    //public $item_count_per_page = 100;
    public $all_items = false;
    public $arr_sort = array(); // arOrder - массив для сортировки // примерный вид array("sort"=>"asc" [, ...]);
    public $arr_filter = array();  // arFilter - Массив вида array("фильтруемое поле"=>"значения фильтра" [, ...]). 
    public $arr_select = array(); // arSelectFields - Массив возвращаемых полей элемента. 
    // для составления конечного результата ($w - 'рабочий' массив) // @todo
    public $w = array(
        "count_all_items" => null,
        "count_all_page" => null,
        'page_number' => 1,
        'item_count_per_page' => 100,
        'err' => null,
        'items' => array()
    );

    // какую страницу получить
    public function page($page_number) {
        //@del
        //$this->page_number = $page_number;
        $this->w['page_number'] = $page_number;
        return $this;
    }

    // сколько элеменотов на странице
    public function count($item_count_per_page) {
        //@del
        //$this->item_count_per_page = $item_count_per_page;
        $this->w['item_count_per_page'] = $item_count_per_page;
        return $this;
    }

    // получить ли все элементы? (без постраничной навигации)
    public function all($all) {
        if (!empty($all)) {
            $this->all_items = true;
        }
        return $this;
    }

    //----------------------------->>>
    // сетеры для сортирвки делятся на два метода asc и desc
    /*
      @todo http://dev.1c-bitrix.ru/api_help/iblock/classes/ciblockelement/getlist.php (для элементов инфоблока)
      order - порядок сортировки, может принимать значения:
      asc - по возрастанию;
      nulls,asc - по возрастанию с пустыми значениями в начале выборки;
      asc,nulls - по возрастанию с пустыми значениями в конце выборки;
      desc - по убыванию;
      nulls,desc - по убыванию с пустыми значениями в начале выборки;
      desc,nulls - по убыванию с пустыми значениями в конце выборки;
     */
    public function asc($by) { // по возрастанию
        $by = strtolower($by);
        $this->arr_sort[$by] = 'asc';
        return $this;
    }

    public function desc($by) { // по возрастанию
        $by = strtolower($by);
        $this->arr_sort[$by] = 'desc';
        return $this;
    }

    //----------------------------->>>
    // фильтр     
    // ...->filter('ACTIVE', 'Y')->filter('IBLOCK_ID', 10)....
    public function filter($field, $val_filter = '') {
        $field = strtoupper($field);
        $this->arr_filter[$field] = $val_filter;
        return $this;
    }

    // какие поля будут возвращены // параметры через запятую ->select('ID', 'PRICE' [, ...])->
    public function select() {
        $arg_list = func_get_args();
        foreach ($arg_list as $field_name) {
            $field_name = strtoupper($field_name);
            $this->arr_select[] = $field_name;
        }
        return $this;
    }

    // возвращает массив с результатами
    abstract function get();

}

/* $rj = new Rj();
  $rj->count(15)->page(3)->asc('id')->desc('sort');
  new dbug ($rj);

  exit(); */

class IBlockElement extends DA {

    public $arr_select_prop = array(); // массив дополниельных свойств 

    public function get() {
        // >>определяем количество элементов и страниц
        $CIBlockElement = CIBlockElement::GetList(
            $arOrder = array(), $arFilter = $this->arr_filter, $arGroupBy = false, $arNavStartParams = false, $arSelectFields = Array('ID')
        );
        $all_items_count = $CIBlockElement->SelectedRowsCount();
        $this->w['count_all_items'] = $all_items_count;

        $count_all_page = ceil($all_items_count / $this->w['item_count_per_page']);
        $this->w['count_all_page'] = $count_all_page;
        // <<
        $start_elem = $this->w['item_count_per_page'] * ($this->w['page_number'] - 1);
        //echo "$start_elem";
        if ($start_elem >= $all_items_count) {
            // если запрашивается страница которой нет(т.е. элементы закончились - возвращаем пустой массив $items)
        } else {
            // >>получаем товары 
            $items = array();

            $_arNavStartParams = Array(
                //@del
                //"nPageSize"=>$this->item_count_per_page, 'iNumPage'=>$this->page_number 
                "nPageSize" => $this->w['item_count_per_page'], 'iNumPage' => $this->w['page_number']
            );
            if ($this->all_items == true) {
                $_arNavStartParams = false; // получить все элементы без пагинации
            }

            $CIBlockElement = CIBlockElement::GetList(
                            $arOrder = $this->arr_sort, $arFilter = $this->arr_filter, $arGroupBy = false, $arNavStartParams = $_arNavStartParams, $arSelectFields = $this->arr_select
            );

            while ($ar_result = $CIBlockElement->GetNext()) {
                // получаем множественные свойства
                if (!empty($this->arr_filter['IBLOCK_ID']) && !empty($ar_result['ID']) && count($this->arr_select_prop)) {
                    $arr_res_prop = array();
                    foreach ($this->arr_select_prop as $code_prop) {
                        // получим множ свойство (связанные товары )
                        $arr_val_prop = array();
                        $db_props = CIBlockElement::GetProperty(
                            $IBLOCK_ID = $this->arr_filter['IBLOCK_ID'], $PROD_ID = $ar_result['ID'], array("sort" => "asc"), Array("CODE" => $code_prop)
                        );
                        $arr_val_prop = array();
                        while ($prop = $db_props->GetNext()) {
                            if (!empty($prop['VALUE'])) {
                                $arr_val_prop[] = $prop['VALUE'];
                            }
                        }

                        $arr_res_prop[$code_prop] = $arr_val_prop; // пополняем результат
                    }
                    $ar_result['_prop'] = $arr_res_prop;
                }
                $items[] = $ar_result;
            }
            $this->w['items'] = $items;
        }




        // <<получаем товары 
        return $this->w;
    }

    //--->
    // какие доп свойства свойства будут получены методом CIBlockElement::GetProperty (хорошо годиться для множ свойств)
    public function prop() {
        $arg_list = func_get_args();
        foreach ($arg_list as $prop_code) {
            $prop_code = strtoupper($prop_code);
            $this->arr_select_prop[] = $prop_code;
        }
        return $this;
    }

    //<---
}