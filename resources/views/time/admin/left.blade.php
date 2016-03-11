<div class="list-group">
    <a class="list-group-item active">控制面板</a>
    <a class="list-group-item" href="{{ url('admin') }}">系统统计</a>
    <a class="list-group-item" href="{{ route('admin.system') }}">系统设置</a>
    <a class="list-group-item" href="{{ route('admin.categories') }}">分类管理</a>
    <a class="list-group-item" href="{{ route('admin.articles') }}">文章管理</a>
    <a class="list-group-item" href="{{ url('admin/projects') }}">项目管理</a>
    <a class="list-group-item" href="{{ url('admin/persons') }}">人物管理</a>
    <a class="list-group-item" href="{{ route('admin.pages') }}">单页管理</a>
    <a class="list-group-item" href="{{ route('admin.users') }}">用户管理</a>
    <a class="list-group-item" href="{{ url('auth/logout') }}">退出</a>
</div>
