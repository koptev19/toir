<ul class="nav nav-tabs" role="tablist">
	<li class="nav-item" role="presentation">
    	<a class="nav-link @if($active === 'equipments') active @endif" href="/toir/equipment.php">Оборудование</a>
	</li>
    @if(\Auth::user()->is_admin)
    <li class="nav-item" role="presentation">
	    <a class="nav-link @if($active === 'departments') active @endif" href="{{ route('departments.index') }}">Службы</a>
	</li>
	<li class="nav-item" role="presentation">
	    <a class="nav-link @if($active === 'users') active @endif" href="/toir/users.php">Пользователи</a>
	</li>
	<li class="nav-item" role="presentation">
	    <a class="nav-link @if($active === 'settings') active @endif" href="{{ route('settings.index') }}">Настройки</a>
	</li>
	<li class="nav-item" role="presentation">
    	<a class="nav-link @if($active === 'accept') active @endif" href="/toir/accept.php">Приемка оборудования</a>
	</li>
    @endif
</ul>
