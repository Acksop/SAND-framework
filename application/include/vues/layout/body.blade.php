@extends('system')

@section('body')
    <!-- Body Inner -->
    <div class="body-inner">
        <!-- Topbar -->
        <div id="topbar" class="d-none d-xl-block d-lg-block topbar-transparent topbar-fullwidth dark"
             style="background: rgba(0, 0, 0, 0.59);">
            <div class="container">
                <ul class="top-menu">
                    <li @if($name == 'policy') class="actual" @endif ><a href="{{ \MVC\Classe\Url::link_rewrite( false, 'Policy', []) }}">Policy</a></li>
                    <li @if($name == 'cgu') class="actual" @endif ><a href="{{ \MVC\Classe\Url::link_rewrite( false, 'CGU', []) }}"> CGU Terms</a></li>
                </ul>
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
                        <a href="#">
                            <span class="logo-default"></span>
                            <span class="logo-dark"></span>
                        </a>
                    </div>
                    <!--End: Logo-->
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
                                    <li @if($name == 'search') class="actual" @endif ><a href="{{ \MVC\Classe\Url::link_rewrite( false, 'Index', []) }}">Index</a></li>
                                    <li @if($name == 'admin') class="actual" @endif ><a href="{{ \MVC\Classe\Url::link_rewrite( false, 'Admin', []) }}">Admin</a></li>
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
                        <div class="col-lg-5">
                            <div class="widget">
                                <div class="widget-title"></div>
                                <p class="mb-5">
                                    <img src="{{ \MVC\Classe\Url::asset_rewrite('assets/img/beer.svg') }}" width="150">
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="copyright-content">
                    <div class="container">
                        <div class="copyright-text text-center">&copy; 2020-2021 Built with SAND Framework - Responsive FrameWork Template.</div>
                    </div>
                </div>
            </div>
        </footer>
        <!-- end: Footer -->

    </div>
    <!-- end: Body Inner -->
@endsection
