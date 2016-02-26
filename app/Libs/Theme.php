<?php
/**
 * Created by Joy.
 * User: Joy
 */
namespace App\Libs;

use App\Model\System;
use App\Model\Project;
use App\Model\Article;
use App\Model\Category;
use App\Model\Person;
use Cache;
use Carbon\Carbon;

define('findAll', 0);   //查找全部
define('findCategory', 1);   //按分类查找，人物无该参数
define('findRecommend', 3); //按推荐查找
define('byId', 0); //按id排序
define('bySort', 1); //按sort排序(排序字段)
define('byViews', 2); //按浏览量排序，人物无该参数
define('byCost', 3); //按花费排序，仅项目有该参数
define('byPoint', 4); //按贡献排序，仅人物有该参数

/**模板功能
 * 实现快速切换主题模板功能
 * 实现数据缓存功能
 * $theme：主题所在目录
 * $system：系统参数，缓存
 * $types：根分类信息，缓存
 * article_data：按规则读取文章，缓存
 * project_data：按规则读取项目，缓存
 * person_data：按规则读取人物，缓存
 */

class Theme
{
    public static function view($view, $data = array())
    {
        $key_system = 'system_info';
        if (Cache::has($key_system)) {
            $system = Cache::get($key_system);
        } else {
            $system = System::getValue();
            $expiresAt = Carbon::now()->addMinutes(60*24);
            Cache::add($key_system, $system, $expiresAt);
        }

        $key_type = 'categories_info';
        if (Cache::has($key_type)) {
            $types = Cache::get($key_type);
        } else {
            $types = Category::where('parent_id',0)->isNavShow()->sortByDesc('sort')->get();
            $expiresAt = Carbon::now()->addMinutes(60);
            Cache::add($key_type, $types, $expiresAt);
        }

        if(!isset($system['theme'])) $system['theme'] = '';
        $theme = $system['theme'] == '' ? 'time' : $system['theme'];
        $data['theme'] = $theme;
        $data['system'] = $system;
        $data['types'] = $types;
        return view($theme . '/' . $view, $data);
    }

    /**调用文章数据
     * @param $num 查询数量
     * @param $order 排序规则
     * @param $where 查询条件
     * @param $type 当查询有参数时，该项为参数
     * @param $offset 从第几个开始查询
     * 返回项目数组
     */
    public static function article_data($num,$order = null,$where = null,$type = 0,$offset = 0)
    {
        $num = intval($num);
        $offset = intval($offset);
        $key = 'article_'.$num.'_'.$order.'_'.$where.'_'.$type.'_'.$offset;
        if (Cache::has($key)) {
            $date = Cache::get($key);
            return $date;
        } else {
            switch ($order) {
                case byId:
                    $order_str = 'id';
                    break;
                case bySort:
                    $order_str = 'sort';
                    break;
                case byViews:
                    $order_str = 'views';
                    break;
                default:
                    $order_str = 'id';
                    break;
            }
            $type = intval($type);
            switch ($where) {
                case findAll:
                    $date = Article::sortByDesc($order_str)->take($num)->Offset($offset)->get();
                    break;
                case findRecommend:
                    $date = Article::where('is_recommend', '>', 0)->sortByDesc($order_str)->take($num)->Offset($offset)->get();
                    break;
                case findCategory:
                    $date = Article::where('category_id', $type)->orderBy($order_str, 'desc')->take($num)->Offset($offset)->get();
                    break;
                default:
                    $date = Article::sortByDesc($order_str)->take($num)->Offset($offset)->get();
                    break;
            }
            $expiresAt = Carbon::now()->addMinutes(60);//设置缓存时间
            Cache::add($key, $date, $expiresAt);
            return $date;
        }
    }

    /**调用项目数据
     * @param $num 查询数量
     * @param $order 排序规则
     * @param $where 查询条件
     * @param $type 当查询有参数时，该项为参数
     * @param $offset 从第几个开始查询
     * 返回项目数组
     */
    public static function project_data($num,$order = null,$where = null,$type = 0,$offset = 0)
    {
        $num = intval($num);
        $offset = intval($offset);
        $key = 'project_'.$num.'_'.$order.'_'.$where.'_'.$type.'_'.$offset;
        if (Cache::has($key)) {
            $date = Cache::get($key);
            return $date;
        } else {
            switch ($order) {
                case byId:
                    $order_str = 'id';
                    break;
                case bySort:
                    $order_str = 'sort';
                    break;
                case byViews:
                    $order_str = 'views';
                    break;
                case byCost:
                    $order_str = 'cost';
                    break;
                default:
                    $order_str = 'id';
                    break;
            }
            $type = intval($type);
            switch ($where) {
                case findAll:
                    $date = Project::sortByDesc($order_str)->take($num)->Offset($offset)->get();
                    break;
                case findRecommend:
                    $date = Project::where('is_recommend', '>', 0)->sortByDesc($order_str)->take($num)->Offset($offset)->get();
                    break;
                case findCategory:
                    $date = Project::where('category_id', $type)->orderBy($order_str, 'desc')->take($num)->Offset($offset)->get();
                    break;
                default:
                    $date = Project::sortByDesc($order_str)->take($num)->Offset($offset)->get();
                    break;
            }
            $expiresAt = Carbon::now()->addMinutes(60);//设置缓存时间
            Cache::add($key, $date, $expiresAt);
            return $date;
        }
    }

    /**调用人物数据
     * @param $num 查询数量
     * @param $order 排序规则
     * @param $where 查询条件
     * @param $type 该参数暂时无用
     * @param $offset 从第几个开始查询
     * 返回项目数组
     */
    public static function person_data($num,$order = null,$where = null,$type = 0,$offset = 0)
    {
        $num = intval($num);
        $offset = intval($offset);
        $key = 'person_'.$num.'_'.$order.'_'.$where.'_'.$type.'_'.$offset;
        if (Cache::has($key)) {
            $date = Cache::get($key);
            return $date;
        } else {
            switch ($order) {
                case byId:
                    $order_str = 'id';
                    break;
                case bySort:
                    $order_str = 'sort';
                    break;
                case byPoint:
                    $order_str = 'point';
                    break;
                default:
                    $order_str = 'id';
                    break;
            }
            switch ($where) {
                case findAll:
                    $date = Person::sortByDesc($order_str)->take($num)->Offset($offset)->get();
                    break;
                case findRecommend:
                    $date = Person::where('is_recommend', '>', 0)->sortByDesc($order_str)->take($num)->Offset($offset)->get();
                    break;
                default:
                    $date = Person::sortByDesc($order_str)->take($num)->Offset($offset)->get();
                    break;
            }
            $expiresAt = Carbon::now()->addMinutes(60);//设置缓存时间
            Cache::add($key, $date, $expiresAt);
            return $date;
        }
    }
}