<?php
namespace App\View\Helper;

use Cake\Core\Configure;
use Cake\Utility\Security;
use Cake\View\Helper;
use Cake\View\View;
use Cake\ORM\TableRegistry;
use Cake\Routing\Router;
use Cake\Cache\Cache;
use Hashids\Hashids;

/**
 * My helper
 * created by: Mazba Kamal
 */
class MyHelper extends Helper
{

    /**
     * Default configuration.
     *
     * @var array
     */
    protected $_defaultConfig = [];
    /*
     * string encode for URL
     */
    public function hashids()
    {
        $security = Configure::read('security');
        return $hashids = new Hashids(
            $security['salt'],
            $security['min_hash_length'],
            $security['alphabet']
        );
    }
    public function get_tree_menu()
    {
        $tasks = TableRegistry::get('tasks')->find('all')->order(['ordering'=>'ASC'])->where(['status'=>1])->toArray();
        $menu_tree = $this->buildTree($tasks);
        $this->print_tree_menu($menu_tree);
    }
    private function print_tree_menu($tasks,$self=false)
    {
        foreach ($tasks as $task)
        {
            ?>

                <?php
                if(!isset($task['sub_menu']))
                {
                    $a_class = '';
                    if(strtolower($this->request->param('controller')) == strtolower($task['controller'])
                        && strtolower($this->request->param('action')) == strtolower($task['method']))
                    {
                        $a_class = 'active';
                    }
                    ?>
                    <li class="<?= $a_class; ?>" >
                        <a href="<?= Router::url(['controller' => $task['controller'], 'action' => $task['method']]) ; ?>">
                            <i class="<?= $task['icon'] ?>"></i> <?= $task['name_en'] ?>
                        </a>
                    </li>
                    <?php
                }
                else
                {
                    ?>
                    <li>
                        <a href="javascript:;">
                            <i class="<?= $task['icon'] ?>"></i> <?= $task['name_en'] ?> <span class="arrow"></span>
                        </a>
                        <ul class="sub-menu">
                            <?php
                            $this->print_tree_menu($task['sub_menu'],true)
                            ?>
                        </ul>
                    </li>
                    <?php
                }
                ?>

            <?php
        }
        if($self)
        {
            return 0;
        }

    }
    private function buildTree($rows, $parent_id = 0)
    {
        $user = $this->request->Session()->read('Auth')['User'];
        $cache_variable = Configure::read('user_role_cache_var').Security::hash($user['user_group_id']);
        $roles = Cache::read($cache_variable,'mcake');
        $arrange_menus = [];
        $i = 0;
        foreach ($rows as $row)
        {
            $i++;
            if($row['parent_id'] == $parent_id)
            {
                $children = $this->buildTree($rows, $row['id']);
                if ($children)
                {
                    $row['sub_menu'] = $children;
                    $arrange_menus[] = $row;
                }
                else
                {
                    if(isset($roles[strtolower($row['controller'])][strtolower($row['method'])]))
                        $arrange_menus[] = $row;
                }
            }
        }
        return $arrange_menus;
    }
}
