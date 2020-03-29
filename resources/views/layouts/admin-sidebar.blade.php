<aside class="left-sidebar">
    <!-- Sidebar scroll-->
    <div class="scroll-sidebar">
        <!-- Sidebar navigation-->
        <nav class="sidebar-nav">
            <ul id="sidebarnav">
                <li class="nav-small-cap">Меню администратора</li>
                <li>
                    <a class="has-arrow" href="#" aria-expanded="false"><i class="mdi mdi-account"></i><span class="hide-menu">Пользователи</span></a>
                    <ul aria-expanded="false" class="collapse">
                        <li><a href="/user?non_activate=1">Неактивированные</a></li>
                        <li><a href="/user">Все пользователи</a></li>
                        <li><a href="">История Upgrade</a></li>
                        <li><a href="/user?upgrade_request=1">Заявки на Upgrade</a></li>
                        <li><a href="/user/create">Добавить</a></li>
                    </ul>
                </li>
                <li>
                    <a class="has-arrow" href="#" aria-expanded="false"><i class="mdi mdi-settings"></i><span class="hide-menu">Настройки</span></a>
                    <ul aria-expanded="false" class="collapse">
                        <li><a href="/package">Пакеты</a></li>
                        <li><a href="/office">Офисы</a></li>
                        <li><a href="/city">Города</a></li>
                        <li><a href="/country">Страны</a></li>
                        <li><a href="#">Статусы</a></li>
                        <li><a href="#">Виды бонусов</a></li>
                    </ul>
                </li>
                {{--<li>
                    <a class="has-arrow" href="#" aria-expanded="false"><i class="mdi mdi-account-multiple"></i><span class="hide-menu">Новые партнеры</span></a>
                    <ul aria-expanded="false" class="collapse">
                        <li><a href="/client">Все</a></li>
                        <li><a href="/client/create">Добавить</a></li>
                    </ul>
                </li>--}}
                <li>
                    <a class="has-arrow" href="#" aria-expanded="false"><i class="mdi mdi-cash-multiple"></i><span class="hide-menu">Доходы</span></a>
                    <ul aria-expanded="false" class="collapse">
                        <li><a href="/overview-money">Обзор</a></li>
                    </ul>
                </li>
                <li>
                    <a class="has-arrow" href="#" aria-expanded="false"><i class="mdi mdi-shopping"></i><span class="hide-menu">Магазин</span></a>
                    <ul aria-expanded="false" class="collapse">
                        <li><a href="/store">Товары</a></li>
                        <li><a href="/order">Заказы</a></li>
                    </ul>
                </li>
                <li>
                    <a class="has-arrow" href="#" aria-expanded="false"><i class="mdi mdi-newspaper"></i><span class="hide-menu">Новости</span></a>
                    <ul aria-expanded="false" class="collapse">
                        <li><a href="/news">Все новости</a></li>
                        <li><a href="/news/create">Добавить новость</a></li>
                    </ul>
                </li>
                <li>
                    <a class="has-arrow" href="#" aria-expanded="false"><i class="mdi mdi-help"></i><span class="hide-menu">FAQ</span></a>
                    <ul aria-expanded="false" class="collapse">
                        <li><a href="/faqgetguest">FAQ для гостя</a></li>
                        <li><a href="/faqgetadmin">FAQ для админа</a></li>
                        <li><a href="/faqadmin/create">Добавить FAQ</a></li>
                    </ul>
                </li>
                <li>
                    <a class="has-arrow" href="#" aria-expanded="false"><i class="mdi mdi-apps"></i><span class="hide-menu">Дополнительно</span></a>
                    <ul aria-expanded="false" class="collapse">
                        <li><a href="/progress">Лидеры</a></li>
                        <li><a href="/not_cash_bonuses">Happy Travel</a></li>
                        <li><a href="/not_cash_bonuses">Бонус признания</a></li>
                        <li><a href="/offices_bonus">Бонус развития офисов</a></li>
                    </ul>
                </li>
                {{--<li>
                    <a class="has-arrow" href="#" aria-expanded="false"><i class="mdi mdi-book"></i><span class="hide-menu">Курс</span></a>
                    <ul aria-expanded="false" class="collapse">
                        <li><a href="/course">Все курсы</a></li>
                        <li><a href="/course/create">Добавить курс</a></li>
                    </ul>
                </li>
                <li>
                    <a class="has-arrow" href="#" aria-expanded="false"><i class="mdi mdi-book"></i><span class="hide-menu">Рекомендации</span></a>
                    <ul aria-expanded="false" class="collapse">
                        <li><a href="/recommendations">Все рекомендации</a></li>
                        <li><a href="/recommendations/create">Добавить рекомендацию</a></li>
                    </ul>
                </li>--}}
                <li class="nav-devider"></li>
                <li class="nav-small-cap">{{ __('app.menu') }}</li>
                <li>
                    <a href="/home" aria-expanded="false">
                        <i class="mdi mdi-account"></i>
                        <span class="hide-menu">Профиль</span>
                    </a>
                </li>
                <li>
                    <a href="{{ url('/logout') }}" aria-expanded="false" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                        <i class="mdi mdi-logout"></i>
                        <span class="hide-menu">{{ __('app.logout') }}</span>
                    </a>
                    <form id="logout-form" action="{{ url('/logout') }}" method="POST" style="display: none;">
                        {{ csrf_field() }}
                    </form>
                </li>
            </ul>
        </nav>
        <!-- End Sidebar navigation -->
    </div>
    <!-- End Sidebar scroll-->
</aside>
