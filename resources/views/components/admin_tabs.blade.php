<ul class="nav nav-tabs" role="tablist">
	<li class="nav-item" role="presentation">
    	<a class="nav-link @if($active === 'equipments') active @endif" href="#">Оборудование</a>
	</li>
    @if(\Auth::user()->is_admin)
    <li class="nav-item" role="presentation">
	    <a class="nav-link @if($active === 'departments') active @endif" href="{{ route('departments.index') }}">Службы</a>
	</li>
	<li class="nav-item" role="presentation">
	    <a class="nav-link @if($active === 'users') active @endif" href="users.php">Пользователи</a>
	</li>
	<li class="nav-item" role="presentation">
	    <a class="nav-link @if($active === 'settings') active @endif" href="settings.php">Настройки</a>
	</li>
    @endif
</ul>
