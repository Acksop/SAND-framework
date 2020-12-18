@extends('system')

@section('body')
    <!-- Body Inner -->
    <div class="body-inner">
        <!-- Topbar -->
        <div id="topbar" class="d-none d-xl-block d-lg-block topbar-transparent topbar-fullwidth dark"
             style="background: rgba(0, 0, 0, 0.59);">
            <div class="container">
                <div class="row">
                    <div class="col-md-6">
                        <ul class="top-menu">
                            <li><a href="#">About</a></li>
                            <li><a href="#">Features</a></li>
                            <li><a href="{{ \MVC\Classe\Url::link_rewrite( false, 'Beers', []) }}">Pricing</a></li>
                            <li><a href="{{ \MVC\Classe\Url::link_rewrite( false, 'Policy', []) }}">Policy</a></li>
                            <li><a href="{{ \MVC\Classe\Url::link_rewrite( false, 'CGU', []) }}"> CGU Terms</a></li>
                        </ul>
                    </div>
                    <div class="col-md-6 d-none d-sm-block">
                        <div class="social-icons social-icons-colored-hover">
                            <ul>
                                <li class="social-facebook"><a href="#"><i class="fab fa-facebook-f"></i></a></li>
                                <li class="social-twitter"><a href="#"><i class="fab fa-twitter"></i></a></li>
                                <li class="social-google"><a href="#"><i class="fab fa-google-plus-g"></i></a></li>
                                <li class="social-pinterest"><a href="#"><i class="fab fa-pinterest"></i></a></li>
                                <li class="social-vimeo"><a href="#"><i class="fab fa-vimeo"></i></a></li>
                                <li class="social-linkedin"><a href="#"><i class="fab fa-linkedin"></i></a></li>
                                <li class="social-dribbble"><a href="#"><i class="fab fa-dribbble"></i></a></li>
                                <li class="social-youtube"><a href="#"><i class="fab fa-youtube"></i></a></li>
                                <li class="social-rss"><a href="#"><i class="fa fa-rss"></i></a></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- end: Topbar -->
        <!-- Header -->
        <header id="header" class="dark" data-transparent="true" data-fullwidth="true"
                style="background: rgba(0, 0, 0, 0.59);">
            <div class="header-inner">
                <div class="container">
                    <!--Logo-->
                    <div id="logo">
                        <a href="{{ \MVC\Classe\Url::link_rewrite( false, 'accueil', []) }}">
                            <span class="logo-default">SAND framework</span>
                            <span class="logo-dark">SAND framework</span>
                        </a>
                    </div>
                    <!--End: Logo-->
                    <!-- Search -->
                    <div id="search"><a id="btn-search-close" class="btn-search-close" aria-label="Close search form"><i
                                    class="icon-x"></i></a>
                        <form class="search-form" action="#" method="get">
                            <input class="form-control" name="q" type="search" placeholder="Type & Search..."/>
                            <span class="text-muted">Start typing & press "Enter" or "ESC" to close</span>
                        </form>
                    </div>
                    <!-- end: search -->
                    <!--Header Extras-->
                    <div class="header-extras">
                        <ul>
                            <li>
                                <a id="btn-search" href="#"> <i class="icon-search"></i></a>
                            </li>
                            <li>
                                <div class="p-dropdown">
                                    <a href="#"><i class="icon-globe"></i><span>FR</span></a>
                                    <ul class="p-dropdown-content">
                                        <li><a href="#">Français</a></li>
                                        <li><a href="#">Deutsch</a></li>
                                        <li><a href="#">English</a></li>
                                    </ul>
                                </div>
                            </li>
                        </ul>
                    </div>
                    <!--end: Header Extras-->
                    <!--Navigation Resposnive Trigger-->
                    <div id="mainMenu-trigger">
                        <a class="lines-button x"><span class="lines"></span></a>
                    </div>
                    <!--end: Navigation Resposnive Trigger-->
                    <!--Navigation-->
                    <div id="mainMenu">
                        <div class="container">
                            <nav>
                                <ul>
                                    @if (\MVC\Classe\Session::isRegistered())
                                        <li>
                                            <a href="{{ \MVC\Classe\Url::module_link_rewrite( 'prestashop', ['admin-dev']) }}">E-commerce</a>
                                        </li>
                                        <li>
                                            <a href="{{ \MVC\Classe\Url::module_link_rewrite( 'wordpress', ['wp-admin']) }}">Blog</a>
                                        </li>
                                        <li><a href="{{ \MVC\Classe\Url::module_link_rewrite( 'phplist', ['admin']) }}">Newsletter</a>
                                        </li>
                                    @else
                                        <li><a href="{{ \MVC\Classe\Url::module_link_rewrite( 'prestashop', ['']) }}">E-commerce</a>
                                        </li>
                                        <li><a href="{{ \MVC\Classe\Url::module_link_rewrite( 'wordpress', ['']) }}">Blog</a>
                                        </li>
                                        <li><a href="{{ \MVC\Classe\Url::module_link_rewrite( 'phplist', ['']) }}">Newsletter</a>
                                        </li>
                                    @endif
                                    <li><a href="{{ \MVC\Classe\Url::link_rewrite( false, 'symfony', []) }}">Symfony
                                            5.1</a></li>

                                    <li class="dropdown"><a
                                                href="#">Documentation</a>
                                        {{\MVC\Classe\ControlleurAction::inserer('menudocs.default',[])}}
                                    </li>
                                </ul>
                            </nav>
                        </div>
                    </div>
                    <!--end: Navigation-->
                </div>
            </div>
        </header>
        <!-- end: Header -->

        <section id="page-content">
            <div class="container">
            @yield('content')
            </div>
        </section>

        <!-- Footer -->
        <footer id="footer">
            <div class="footer-content">
                <div class="container">
                    <div class="row">
                        <div class="col-lg-2">
                            <div class="widget">
                                <p class="mb-2">
                                    <img src="{{ \MVC\Classe\Url::asset_rewrite('assets/img/beer.svg') }}" width="150">
                                </p>
                            </div>
                        </div>
                        <div class="col-lg-3">
                            <div class="widget">
                                <div class="widget-title">SAND Framework</div>
                                <p class="mb-3">Built with love in Besançon, Doubs, France<br>
                                    All rights reserved. Copyright © 2020. Info[ARTS]Media</p>
                                <a href="#"
                                   class="btn btn-inverted">Purchase Now</a>
                            </div>
                        </div>
                        <div class="col-lg-7">
                            <div class="row">
                                <div class="col-lg-3">
                                    <div class="widget">
                                        <div class="widget-title">Discover</div>
                                        <ul class="list">
                                            <li><a href="#">Features</a></li>
                                            <li><a href="#">Layouts</a></li>
                                            <li><a href="#">Corporate</a></li>
                                            <li><a href="#">Updates</a></li>
                                            <li><a href="#">Pricing</a></li>
                                            <li><a href="#">Customers</a></li>
                                        </ul>
                                    </div>
                                </div>
                                <div class="col-lg-3">
                                    <div class="widget">
                                        <div class="widget-title">Features</div>
                                        <ul class="list">
                                            <li><a href="#">Layouts</a></li>
                                            <li><a href="#">Headers</a></li>
                                            <li><a href="#">Widgets</a></li>
                                            <li><a href="#">Footers</a></li>
                                        </ul>
                                    </div>
                                </div>
                                <div class="col-lg-3">
                                    <div class="widget">
                                        <div class="widget-title">Pages</div>
                                        <ul class="list">
                                            <li><a href="#">Portfolio</a></li>
                                            <li><a href="#">Blog</a></li>
                                            <li><a href="#">Shop</a></li>
                                        </ul>
                                    </div>
                                </div>
                                <div class="col-lg-3">
                                    <div class="widget">
                                        <div class="widget-title">Support</div>
                                        <ul class="list">
                                            <li><a href="#">Help Desk</a></li>
                                            <li><a href="#">Documentation</a></li>
                                            <li><a href="#">Contact Us</a></li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="copyright-content">
                <div class="container">
                    <div class="copyright-text text-center">&copy; 2020 SAND Framework - Responsive FrameWork Template.
                        All Rights Reserved.<a href="http://infoartsmedia.fr" target="_blank"> Info[ARTS]Media</a></div>
                </div>
            </div>
        </footer>
        <!-- end: Footer -->

    </div>
    <!-- end: Body Inner -->
@endsection