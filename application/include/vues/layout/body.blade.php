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
                            <li><a href="{{ \MVC\Classe\Url::link_rewrite( false, 'Policy', []) }}">Policy</a></li>
                            <li><a href="{{ \MVC\Classe\Url::link_rewrite( false, 'CGU', []) }}"> CGU Terms</a></li>
                        </ul>
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
                        <a href="{{ \MVC\Classe\Url::link_rewrite( false, 'index', []) }}">
                            <span class="logo-default">APP</span>
                            <span class="logo-dark">APP</span>
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
                                    <li><a href="{{ \MVC\Classe\Url::link_rewrite( false, 'Admin', []) }}">Admin</a></li>
                                    <li><a href="{{ \MVC\Classe\Url::link_rewrite( false, 'Create', []) }}">Create</a></li>
    {{--@if (\MVC\Classe\Session::isRegistered())
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
    @endif--}}
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
<div class="widget-title">SAND Framework</div>
<p class="mb-5">Built with love in Besançon, Doubs, France<br>
    All rights reserved. Copyright © 2020. Info[ARTS]Media</p>
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