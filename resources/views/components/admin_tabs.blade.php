<ul class="nav nav-tabs" role="tablist">
    <li class="nav-item" role="presentation">
        <a class="nav-link @if($active === 'equipments') active @endif" href="{{ route('equipments.index') }}">Оборудование</a>
    </li>
    @if(\Auth::user()->is_admin)
        <li class="nav-item" role="presentation">
            <a class="nav-link @if($active === 'departments') active @endif" href="{{ route('departments.index') }}">Службы</a>
        </li>
        <li class="nav-item" role="presentation">
            <a class="nav-link @if($active === 'users') active @endif" href="{{ route('users.index') }}">Пользователи</a>
        </li>
        <li class="nav-item" role="presentation">
            <a class="nav-link @if($active === 'settings') active @endif" href="{{ route('settings.index') }}">Настройки</a>
        </li>
        <li class="nav-item" role="presentation">
            <a class="nav-link @if($active === 'accepts') active @endif" href="{{ route('accepts.index') }}">Приемка оборудования</a>
        </li>
    @endif
    <li class="d-flex ms-auto me-3 mt-2">
        <h5>{{ \Auth::user()->fullname }}</h5>
    </li>
</ul>
