#timeCMS
    开源协议：[MIT License](http://opensource.org/licenses/MIT)
    演示网站：[探索者日志]（http://www.obday.com）
    时光CMS，基于laravel5.1的开源CMS系统。时光流逝那些朦胧的回忆，只留下最值得珍惜的瞬间。

###单元测试：
    在网站根目录运行以下命令即可
    vendor\bin\phpunit tests\****
    ****为要测试的类

###主题功能：
    同一个主题的模板都放在resources\views的同一个目录下面，比如time
    需要使用主题模板的控制器引用下Theme类，使用Theme类的view方法，例子如下
    use Theme;
    class WelcomeController extends Controller
    {
        public function index()
        {
            return Theme::view('welcome.index');
        }
    }
    Theme类的view方法，语法与laravel原来的view一样。
    view自动向模板传递参数$theme，模板引用的地方写成@include($theme.'/xxx')格式即可
    模板类文件为 app/Libs/Theme.php
    目前主题直接定义在该文件中，后期将改为数据库保存模式，方便用户在后台修改
    增加主题功能的目的是为了方便用户快速切换主题
    既用户下载主题包后放在resources\views文件夹下，就可以直接在后台切换主题
    主题对应样式文件，建议放在public文件的相应目录下面，比如time
