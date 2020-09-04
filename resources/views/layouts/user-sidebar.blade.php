<aside class="left-sidebar">
    <div class="scroll-sidebar">
        <!-- Sidebar navigation-->
        <nav class="sidebar-nav">
            <ul id="sidebarnav">
                <li class="nav-small-cap">{{ __('app.menu') }}</li>
                <!--Если юзер из магазина из сайта то ему показываем только магазин и корзину-->
                @if(Auth::user()->type == 1)
                    <li>
                        <a href="/main-store" aria-expanded="false">
                            <i class="mdi mdi-cart"></i>
                            <span class="hide-menu">В магазин</span>
                        </a>
                    </li>
                @else
                    <li>
                        <a href="/home" aria-expanded="false">
                            <i class="mdi mdi-bank"></i>
                            <span class="hide-menu">Главная </span>
                        </a>
                    </li>
                    <li>
                        <a class="has-arrow" href="#" aria-expanded="false">
                            <i class="mdi mdi-account-multiple"></i>
                            <span class="hide-menu">Моя команда</span>
                        </a>
                        <ul aria-expanded="false" class="collapse">
                            <li><a href="/invitations">Лично приглашенные</a></li>
                            <li><a href="/tree/{{ Auth::user()->id }}">Мое дерево</a></li>
                            <li><a href="/hierarchy" target="_blank">Иерархия</a></li>
                            <li><a href="/team">Моя команда</a></li>
                            <li><a href="/team?upgrade=1">Апгрейд команды</a></li>
                            <li><a href="/team?move=1">Прогресс команды</a></li>
                            <li><a href="/team?own=1">Моя команда(без перелива)</a></li>
                        </ul>
                    </li>
                    <li>
                        <a class="has-arrow" href="#" aria-expanded="false">
                            <i class="mdi mdi-currency-usd"></i>
                            <span class="hide-menu">Мои финансы</span>
                        </a>
                        <ul aria-expanded="false" class="collapse">
                            <li><a href="/user_processing">{{ __('app.processing') }}</a></li>
                            <li><a href="/user_processing?weeks=1">Еженедельная  выплата</a></li>
                            {{--<li><a href="/rang-history">История ранга</a></li>--}}
                        </ul>
                    </li>
                    <li>
                        <a class="has-arrow" href="#" aria-expanded="false">
                            <i class="mdi mdi-shopping"></i>
                            <span class="hide-menu">Интернет магазин</span>
                        </a>
                        <ul aria-expanded="false" class="collapse">
                            <li><a href="/main-store">Магазин</a></li>
                            {{--<li><a href="/userorders">Мои заказы</a></li>--}}
                            <li><a href="/basket">Корзина</a></li>
                            <li><a href="/story-store">Покупки</a></li>
                            <li><a href="/activation-store">История Повторной покупки</a></li>
                        </ul>
                    </li>
                    <li>
                        <a href="/programs" aria-expanded="false">
                            <i class="mdi mdi-package"></i>
                            <span class="hide-menu">Апгрейд</span>
                        </a>
                    </li>
                    {{--<li>
                        <a class="has-arrow" href="#" aria-expanded="false">
                            <i class="mdi mdi-account-plus"></i>
                            <span class="hide-menu">Новые партнеры</span>
                        </a>
                        <ul aria-expanded="false" class="collapse">
                            <li><a href="/clientswithoutphone">Купить контакт</a></li>
                        </ul>
                    </li>--}}
                    <li>
                        <a class="has-arrow" href="#" aria-expanded="false"><i class="mdi mdi-apps"></i><span class="hide-menu">Дополнительно</span></a>
                        <ul aria-expanded="false" class="collapse">
                            <li><a href="/profile">{{ __('app.profile') }}</a></li>
                            <li><a href="/notifications">Уведомления</a></li>
                            <li><a href="/faq-profile">База знаний</a></li>
                            @if(Gate::allows('admin_user_create'))
                                <li><a href="{{ route('partner_create') }}">{{ __('app.add_partner') }}</a></li>
                            @endif
                        </ul>
                    </li>
                    {{--<li>
                        <a href="/marketing" aria-expanded="false">
                            <i class="mdi mdi-weight"></i>
                            <span class="hide-menu">{{ __('app.marketing') }}</span>
                        </a>
                    </li>--}}
                    <li>
                        <a class="has-arrow" href="#" aria-expanded="false"><i class="mdi mdi-star"></i><span class="hide-menu">{{ __('app.reviews') }}</span></a>
                        <ul aria-expanded="false" class="collapse">
                            <li><a href="{{ route('reviews') }}">{{ __('app.all_reviews') }}</a></li>
                            <li><a href="{{ route('my_reviews') }}">{{ __('app.my_reviews') }}</a></li>
                            <li><a href="{{ route('review_add') }}">{{ __('app.add_video_review') }}</a></li>
                        </ul>
                    </li>
                    @if(Gate::allows('admin_access'))
                        <li>
                            <a href="/user" aria-expanded="false">
                                <i class="mdi mdi-bank"></i>
                                <span class="hide-menu">Админ панель</span>
                            </a>
                        </li>
                    @endif
                    @if(Auth::user()->admin == 2)
                        <li>
                            <a class="has-arrow" href="#" aria-expanded="false">
                                <i class="mdi mdi-book"></i>
                                <span class="hide-menu">Курс</span>
                            </a>
                            <ul aria-expanded="false" class="collapse">
                                <li><a href="/course">Все курсы</a></li>
                                <li><a href="/course/create">Добавить курс</a></li>
                            </ul>
                        </li>
                        <li>
                            <a class="has-arrow" href="#" aria-expanded="false">
                                <i class="mdi mdi-book"></i>
                                <span class="hide-menu">Рекомендации</span>
                            </a>
                            <ul aria-expanded="false" class="collapse">
                                <li><a href="/recommendations">Все рекомендации</a></li>
                                <li><a href="/recommendations/create">Добавить рекомендацию</a></li>
                            </ul>
                        </li>
                    @endif
                @endif
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
