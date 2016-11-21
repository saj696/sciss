<?php
namespace App\View\Helper;

use App\Model\Table\AdministrativeUnitsTable;
use Cake\View\Helper;
use Cake\View\View;
use Cake\Core\Configure;
use Cake\I18n\Time;
use Cake\ORM\TableRegistry;

/**
 * System helper
 */
class SystemHelper extends Helper
{

    /**
     * Default configuration.
     *
     * @var array
     */
    protected $_defaultConfig = [];
    public function display_date($date)
    {
        if(strlen($date)<1)
        {
            return '';
        }
        $display_string=date('d-M-Y',$date);
        if($display_string===false)
        {
            return '';
        }
        else
        {
            return $display_string;
        }
    }
    public function display_date_time($date)
    {
        if(strlen($date)<1)
        {
            return '';
        }
        $display_string=date('d-M-Y H:m:s',$date);
        if($display_string===false)
        {
            return '';
        }
        else
        {
            return $display_string;
        }
    }

    public function Get_Bng_to_Eng($str = NULL) {
        $engNumber = array(1, 2, 3, 4, 5, 6, 7, 8, 9, 0, '');
        $bangNumber = array('১', '২', '৩', '৪', '৫', '৬', '৭', '৮', '৯', '০', '');
        $converted = str_replace($bangNumber, $engNumber, $str);
        return $converted;
    }

    public function get_date_diff($date1, $date2)
    {
        $diff = abs($date1 - $date2);

        $years = floor($diff / (365*60*60*24));
        $months = floor(($diff - $years * 365*60*60*24) / (30*60*60*24));
        $days = floor(($diff - $years * 365*60*60*24 - $months*30*60*60*24)/ (60*60*24));

        printf("%d years, %d months, %d days\n", $years, $months, $days);
    }

    public function asked_level_global_id($asked_level, $own_global_id)
    {
        return ((pow(2, ($asked_level*5)+1)-1)*(pow(2, (Configure::read('max_level_no')-$asked_level)*5))) & $own_global_id;
    }

    public function get_current_financial_year()
    {
        $year = TableRegistry::get('financial_years')->find('all', ['conditions'=>['status'=>1]])->first();
        return $year['year'];
    }

    public function get_unit_name($id)
    {
        $info = TableRegistry::get('administrative_units')->find('all', ['conditions'=>['id'=>$id]])->first();
        return $info['unit_name'];
    }

    public function get_level_name($level_no)
    {
        $info = TableRegistry::get('administrative_levels')->find('all', ['conditions'=>['level_no'=>$level_no]])->first();
        return $info['level_name'];
    }

    public function get_items_by_resource($resource_id)
    {
        $items = TableRegistry::get('transfer_items')->find('all', ['conditions'=>['transfer_resource_id'=>$resource_id]])
            ->select(['items.name', 'items.pack_size', 'items.unit', 'transfer_items.item_id', 'transfer_items.quantity'])
            ->innerJoin('items', 'items.id = transfer_items.item_id')
            ->hydrate(false)->toArray();
        return $items;
    }


    public function generate_code($prefix, $type, $padding)
    {
        if($type=='customer'):
            $lastCode = TableRegistry::get('customers')->find('all', ['conditions'=>['code like'=>$prefix. '%'], 'order'=>['id'=>'desc'], 'limit'=>1])->first();
            if(sizeof($lastCode)>0):
                $arr = explode($prefix, $lastCode['code']);
                $lastSerial = $arr[1];
                $newSerial = intval($lastSerial)+1;
                echo $prefix.str_pad($newSerial, $padding, 0, STR_PAD_LEFT);
            else:
                $newSerial = 1;
                echo $prefix.str_pad($newSerial, $padding, 0, STR_PAD_LEFT);
            endif;
        else:
            $lastCode = TableRegistry::get('items')->find('all', ['conditions'=>['code like'=>$prefix. '%'], 'order'=>['id'=>'desc'], 'limit'=>1])->first();
            if(sizeof($lastCode)>0):
                $arr = explode($prefix, $lastCode['code']);
                $lastSerial = $arr[1];
                $newSerial = intval($lastSerial)+1;
                echo $prefix.str_pad($newSerial, $padding, 0, STR_PAD_LEFT);
            else:
                $newSerial = 1;
                echo $prefix.str_pad($newSerial, $padding, 0, STR_PAD_LEFT);
            endif;
        endif;
    }
}
