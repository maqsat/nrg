<aside class="left-sidebar">
    <!-- Sidebar scroll-->
    <div class="scroll-sidebar">
        <!-- User profile -->
    {{--      <div class="user-profile">
              <!-- User profile image -->
              <div class="profile-img"> <img src="/monster_admin/assets/images/users/1.jpg" alt="user" /> </div>
              <!-- User profile text-->
              <div class="profile-text">
                  <a href="#">{{ Auth::user()->login }}</a>
              </div>
          </div>--}}
    <!-- End User profile text-->
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
                            <li><a href="/invitations">Лично пригалшенные</a></li>
                            <li><a href="/tree/{{ Auth::user()->id }}">Мое дерево</a></li>
                            <li><a href="/hierarchy">Иерархия</a></li>
                            <li><a href="/team">Моя команда</a></li>
                        </ul>
                    </li>
                    <li>
                        <a class="has-arrow" href="#" aria-expanded="false">
                            <i class="mdi mdi-currency-usd"></i>
                            <span class="hide-menu">Мои финансы</span>
                        </a>
                        <ul aria-expanded="false" class="collapse">
                            <li><a href="/user_processing">{{ __('app.processing') }}</a></li>
                            <li><a href="/user_processing?weeks=1">По неделям</a></li>
                            {{--<li><a href="/rang-history">История ранга</a></li>--}}
                        </ul>
                    </li>
                    {{--<li>
                        <a class="has-arrow" href="#" aria-expanded="false">
                            <i class="mdi mdi-shopping"></i>
                            <span class="hide-menu">Интернет магазин</span>
                        </a>
                        <ul aria-expanded="false" class="collapse">
                            <li><a href="/main-store">Магазин</a></li>
                            <li><a href="/userorders">Мои заказы</a></li>
                            <li><a href="/basket">Корзина</a></li>
                            <li><a href="/story-store">Покупки</a></li>
                            <li><a href="/activation-store">История активации</a></li>
                        </ul>
                    </li>--}}
                    <li>
                        <a href="/programs" aria-expanded="false">
                            <i class="mdi mdi-package"></i>
                            <span class="hide-menu">Апгрейт</span>
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
                        <a href="/notifications" aria-expanded="false">
                            <i class="mdi mdi-bell"></i>
                            <span class="hide-menu">Уведомлении</span>
                        </a>
                    </li>
                    <li>
                        <a href="/profile" aria-expanded="false">
                            <i class="mdi mdi-account"></i>
                            <span class="hide-menu">{{ __('app.profile') }}</span>
                        </a>
                    </li>
                    <li>
                        <a href="/faq-profile" aria-expanded="false">
                            <i class="mdi mdi-comment-alert"></i>
                            <span class="hide-menu">База знаний</span>
                        </a>
                    </li>
                    {{--<li>
                        <a href="/marketing" aria-expanded="false">
                            <i class="mdi mdi-weight"></i>
                            <span class="hide-menu">{{ __('app.marketing') }}</span>
                        </a>
                    </li>--}}
                    @if(Auth::user()->admin == 1)
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
