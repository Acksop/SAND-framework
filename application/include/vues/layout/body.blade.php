@extends('system')

@section('body')
    <!-- Body Inner -->
    <div class="body-inner">
        <!-- Topbar -->
        <div id="topbar" class="d-none d-xl-block d-lg-block topbar-transparent topbar-fullwidth dark"
             style="background: rgba(0, 0, 0, 0.59);">
            <div class="container">
                <ul class="top-menu">
                    {{\MVC\Object\Environment::getTextEnvironment()}}
                    <li @if($name == 'docs_route' || $name == 'docs_name_route') class="actual" @endif ><a href="{{ \MVC\Classe\Url::link_rewrite( false, 'docs', []) }}">Documentation</a></li>
                    <li @if($name == 'depots') class="actual" @endif ><a href="{{ \MVC\Classe\Url::link_rewrite( false, 'gitlist/SAND-FrameWork', []) }}">Dépot</a></li>
                    <li @if($name == 'donate') class="actual" @endif ><a href="{{ \MVC\Classe\Url::link_rewrite( false, 'Donate', []) }}">Donate</a></li>
                    <li @if($name == 'cgu') class="actual" @endif ><a href="{{ \MVC\Classe\Url::link_rewrite( false, 'CGU', []) }}"> CGU Terms</a></li>
                    <li @if($name == 'policy') class="actual" @endif ><a href="{{ \MVC\Classe\Url::link_rewrite( false, 'Policy', []) }}">Policy</a></li>
                    <li @if($name == 'feedback') class="actual" @endif ><a href="{{ \MVC\Classe\Url::link_rewrite( false, 'Feedback', []) }}">Feedback</a></li>
                </ul>
            </div>
        </div>
        <!-- end: Topbar -->
        <!-- Header -->
        <header id="header" class="dark" data-transparent="true" data-fullwidth="true"
                style="background: rgba(0, 0, 0, 0.59);">
            <div class="header-inner">
                <div class="container">
                    <!--Navigation Resposnive Trigger-->
                    <div id="mainMenu-trigger">
                        <a class="lines-button x"><span class="lines"></span></a>
                    </div>
                    <!--end: Navigation Resposnive Trigger-->
                    <!--Navigation-->
                    <div id="mainMenu">
                        <div class="container">
                            <!--Logo-->
                            <div id="logo">
                                <a href="#">
                                    SAND Framework
                                </a>
                            </div>
                            <!--end: logo-->
                            <nav>
                                <ul>
                                    <li @if($name == 'index') class="actual" @endif ><a href="{{ \MVC\Classe\Url::link_rewrite( false, 'Index', []) }}">Index</a></li>
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
        <!-- Subbar -->
        @if(isset($authentification) && $authentification == 'yes')
        <div id="subbar" class="fullwidth">
            <div class="container">
                <span style="float:left;">Vous êtes connecté en tant que {{$_SESSION['user_login']}}</span>
                <span style="float:right;"><a href="{{ \MVC\Classe\Url::link_rewrite( false, 'Logout', []) }}">Se Deconnecter</a></span>
            </div>
        </div>
        @endif
        <!-- end: Subbar -->

        <!-- Breadcrumbs -->
        @if (isset($ariane))
            <div id="breadcrumbs" class="fullwidth">
                <div class="container">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb-sand">
                            @foreach($ariane as $value)
                                @if($value == end($ariane))
                                    <li class="breadcrumb-item active" aria-current="page">{{$value}}</li>
                                @else
                                    <li class="breadcrumb-item"><a href="{{\MVC\Classe\Url::link_rewrite(false,$arianelink[array_search($value,$ariane)])}}">{{$value}}</a></li>
                                @endif
                            @endforeach
                        </ol>
                    </nav>
                </div>
            </div>
        @endif
        <!-- end: BreadCrumbs -->

        <section id="page-content">
            <div class="container">

                <!--Alerts-->
                @if(isset($_SESSION['alerts']))
                    @foreach($_SESSION['alerts'] as $alert)
                        <div class="alert alert-{{$alert['type']}} alert-dismissible fade show" role="alert">
                            <strong>{{$alert['title']}}</strong> {{$alert['message']}}.
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endforeach
                @endif
                <!--end: Alerts-->
                @yield('content')

            </div>
        </section>

        <!-- Footer -->
        <footer id="footer">
            <div class="footer-content">
                <div class="container">
                    <div class="row">
                        <div class="col-lg-3">
                            <div class="widget">
                                <div class="widget-title"></div>
                                <p class="mb-5">
                                    <img src="{{ \MVC\Classe\Url::asset_rewrite('assets/img/1007698-ffeb3b.svg') }}" width="150">
                                </p>
                            </div>
                        </div>
                        <div class="col-lg-9">
                            <p>
                                SAND FrameWork is an CC-licensed or MIT-licenced open source project and completely free to use.
                            </p>
                            <p>
                                However, the amount of effort needed to maintain and develop new features for the project is not
                                sustainable without proper financial backing.
                                You can support its ongoing development by being a backer or a sponsor on
                                <a href="https://www.patreon.com/">Patreon campaign</a>
                                (recurring, with perks for different tiers), and get your company logo here.
                            </p>
                            <p>
                                Also, you can make a <a href="https://www.paypal.me/">one time donation via PayPal</a>.
                            </p>
                        </div>
                    </div>
                </div>
                <div class="copyright-content">
                    <div class="container">
                        <div class="copyright-text text-center">&copy; 2020-2021 Built with SAND Framework - Responsive SAND Template.</div>
                    </div>
                </div>
            </div>
        </footer>
        <!-- end: Footer -->

    </div>
    <!-- end: Body Inner -->
@endsection
