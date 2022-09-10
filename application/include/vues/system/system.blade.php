<!DOCTYPE html>
<html>

<head>
    <title>{{$page_title}}</title>
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta http-equiv="content-type" content="text/html; charset=utf-8" />
    <meta name="author" content="" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="description" lang="fr" content="{{$description}}"/>

    <link rel="apple-touch-icon" sizes="57x57" href="/apple-icon-57x57.png">
    <link rel="apple-touch-icon" sizes="60x60" href="/apple-icon-60x60.png">
    <link rel="apple-touch-icon" sizes="72x72" href="/apple-icon-72x72.png">
    <link rel="apple-touch-icon" sizes="76x76" href="/apple-icon-76x76.png">
    <link rel="apple-touch-icon" sizes="114x114" href="/apple-icon-114x114.png">
    <link rel="apple-touch-icon" sizes="120x120" href="/apple-icon-120x120.png">
    <link rel="apple-touch-icon" sizes="144x144" href="/apple-icon-144x144.png">
    <link rel="apple-touch-icon" sizes="152x152" href="/apple-icon-152x152.png">
    <link rel="apple-touch-icon" sizes="180x180" href="/apple-icon-180x180.png">
    <link rel="icon" type="image/png" sizes="192x192"  href="/android-icon-192x192.png">
    <link rel="icon" type="image/png" sizes="32x32" href="/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="96x96" href="/favicon-96x96.png">
    <link rel="icon" type="image/png" sizes="16x16" href="/favicon-16x16.png">
    <link rel="manifest" href="/manifest.json">
    <meta name="msapplication-TileColor" content="#ffffff">
    <meta name="msapplication-TileImage" content="/ms-icon-144x144.png">
    <meta name="theme-color" content="#ffffff">

    @section('top-css')
        <link rel="stylesheet" href="{{ \MVC\Classe\Url::asset_rewrite('assets/git-submodules/bootstrap/dist/css/bootstrap.min.css')}}">
        <link rel="stylesheet" href="{{ \MVC\Classe\Url::asset_rewrite('assets/css/custom.css')}}">
        @if(\MVC\Classe\Browser::get() == 'Internet Explorer')
            <link rel="stylesheet" href="{{\MVC\Classe\Url::asset_rewrite('assets/git-submodules/html5-simple-date-input-polyfill/html5-simple-date-input-polyfill.css')}}">
        <!--<link rel="stylesheet" href="{{\MVC\Classe\Url::asset_rewrite('assets/git-submodules/bootstrap/hyperform/css/hyperform.css')}}">-->
        @endif
    @show

</head>

<body>

@section('top-javascript')
    @if(\MVC\Classe\Browser::get() == 'Internet Explorer')
        <!--
        INCLUSION DE SCRIPT JS permettant de corriger les erreurs de navigateurs ancien, particulièrement Internet Explorer
        qui as été abandonné pour Edge depuis Windows10.
        -->

        <!-- Polyfill.io will load polyfills your browser needs -->
        <script src="https://polyfill.io/v3/polyfill.min.js?features=default%2CNumber.parseInt%2CNumber.parseFloat%2CArray.prototype.find%2CArray.prototype.includes"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/babel-polyfill/7.12.1/polyfill.min.js" integrity="sha512-uzOpZ74myvXTYZ+mXUsPhDF+/iL/n32GDxdryI2SJronkEyKC8FBFRLiBQ7l7U/PTYebDbgTtbqTa6/vGtU23A==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
        <script src="{{\MVC\Classe\Url::asset_rewrite('assets/git-submodules/promise-polyfill/dist/polyfill.min.js')}}"></script>
        <script src="{{\MVC\Classe\Url::asset_rewrite('assets/git-submodules/html5-simple-date-input-polyfill/html5-simple-date-input-polyfill.js')}}"></script>
        <script src="{{\MVC\Classe\Url::asset_rewrite('assets/git-submodules/hyperform/dist/hyperform.js')}}"></script>
        <script>hyperform(window);</script>
    @endif
@show

@yield('body')

@section('bottom-javascript')
    <script src="{{ \MVC\Classe\Url::asset_rewrite('assets/git-submodules/bootstrap/dist/js/bootstrap.min.js')}}"></script>
    <script src="{{ \MVC\Classe\Url::asset_rewrite('assets/js/custom.js')}}"></script>

    @if(\MVC\Classe\Browser::get() !== 'Internet Explorer')
    <script>

        /*
        SCRIPT JS permettant de ne valider qu'une seule fois un formulaire
            ATTENTION tous les formulaires sont affecté
            Lors d'une validation bootstrap personnalisé veuillez utiliser
             la class do-resubmit sur le formulaire afin de permettre
             l'activation supplémentaire du bouton.
        */

        window.onload = function() {
            let PreventAllforms = document.querySelectorAll("form");
            Array.prototype.slice.call(PreventAllforms)
                .forEach(function (PreventForm) {
                    PreventForm.onsubmit = submitted.bind(PreventForm);
                });
        }

        function submitted(event) {
            if (event.target.classList.contains('do-resubmit')) {
                event.submitter.disabled = false;
            }else{
                event.submitter.disabled = true;
            }
        }
    </script>
    @endif
@show

</body>

</html>