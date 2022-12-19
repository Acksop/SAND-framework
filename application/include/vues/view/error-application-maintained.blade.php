{{-- Vue Blade de la page d'erreur lors d'une maintenance de l'application--}}
@extends('body-nomenu')

@section('top-css')
    @parent
    <style>
        .crane__list,
        .skyscrappers__list,
        .tree__container {
            position: absolute;
            width: 100%;
            bottom: 0;
        }

        .advice {
            display: flex;
            height: 50vh;
            width: 100vw;
            flex-flow: column nowrap;
            justify-content: center;
            align-items: center;
        }
        .advice__title {
            font-size: 3rem;
            text-align: center;
        }
        .advice__description {
            margin-top: 1rem;
            font-size: 2rem;
            text-align: center;
        }
        .advice__description span:first-child {
            margin-right: -0.7rem;
        }
        .advice__description span:last-child {
            margin-left: -0.7rem;
        }

        .city-stuff {
            display: flex;
            position: absolute;
            justify-content: center;
            width: 100%;
            height: 100%;
            bottom: 0;
            overflow: hidden;
            box-shadow: inset 0 -60px 0 -30px #969696;
            left: 0px;
        }

        .skyscrappers__list {
            width: 100%;
            height: 86.6666666667px;
            left: 0;
        }
        .skyscrappers__list .skyscrapper__item {
            position: absolute;
            height: inherit;
            bottom: 15%;
            width: 43.3333333333px;
            background: linear-gradient(115deg, #969696 73%, #7d7d7d 73%, #7d7d7d 100%);
        }
        .skyscrappers__list .skyscrapper__item::after {
            content: "";
            position: absolute;
            width: 80%;
            height: 80%;
            left: 10%;
            bottom: 10%;
            background: url("data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAMAAAAGCAYAAAAG5SQMAAAAFElEQVQImWP4////fwYYIJKDEwAAfPsP8eFXG40AAAAASUVORK5CYII=") space;
        }
        .skyscrappers__list .skyscrapper__item:last-child:not(:only-child) {
            background: #969696;
        }
        .skyscrappers__list .skyscrapper-1 {
            width: 121.3333333333px;
            height: 138.6666666667px;
            right: 25%;
            bottom: 0;
            z-index: 10;
            transform: rotate(180deg);
        }
        @media screen and (max-width: 450px) {
            .skyscrappers__list .skyscrapper-1 {
                display: none;
            }
        }
        .skyscrappers__list .skyscrapper-2 {
            width: 60.6666666667px;
            height: 69.3333333333px;
            right: 35%;
            bottom: 0;
            z-index: 10;
            transform: rotate(180deg);
            bottom: 10%;
        }
        @media screen and (max-width: 900px) {
            .skyscrappers__list .skyscrapper-2 {
                display: none;
            }
        }
        @media screen and (max-width: 450px) {
            .skyscrappers__list .skyscrapper-1 {
                display: none;
            }
        }
        .skyscrappers__list .skyscrapper-3 {
            width: 40.4444444444px;
            height: 46.2222222222px;
            right: 45%;
            bottom: 0;
            z-index: 10;
            transform: rotate(180deg);
            height: 115.5555555556px;
        }
        @media screen and (max-width: 900px) {
            .skyscrappers__list .skyscrapper-3 {
                display: none;
            }
        }
        @media screen and (max-width: 450px) {
            .skyscrappers__list .skyscrapper-1 {
                display: none;
            }
        }
        .skyscrappers__list .skyscrapper-4 {
            width: 30.3333333333px;
            height: 34.6666666667px;
            right: 55%;
            bottom: 0;
            z-index: 10;
            transform: rotate(180deg);
            height: 86.6666666667px;
        }
        .skyscrappers__list .skyscrapper-4::after {
            width: 20%;
            height: 60%;
            left: 25%;
        }
        @media screen and (max-width: 450px) {
            .skyscrappers__list .skyscrapper-1 {
                display: none;
            }
        }
        .skyscrappers__list .skyscrapper-5 {
            width: 24.2666666667px;
            height: 27.7333333333px;
            right: 65%;
            bottom: 0;
            z-index: 10;
            transform: rotate(180deg);
            width: 7%;
            right: 67%;
            height: 50%;
            z-index: 11;
        }
        .skyscrappers__list .skyscrapper-5::after {
            height: 0;
        }
        @media screen and (max-width: 450px) {
            .skyscrappers__list .skyscrapper-1 {
                display: none;
            }
        }

        .crane-cabin, .crane-arm, .crane-picker {
            transform-origin: 80% center;
            -webkit-animation: crane__movement 12s infinite alternate;
            animation: crane__movement 12s infinite alternate;
        }

        .crane__list {
            width: 260px;
            height: 173.3333333333px;
            z-index: 0;
            perspective: 600px;
        }
        .crane__list .crane__item {
            position: absolute;
            border: solid 1px #969696;
            border-radius: 2px;
        }
        .crane__list .crane-cable {
            width: 1px;
            height: 1px;
            border: none;
            outline: 1px solid transparent;
            background: #969696;
            z-index: 0;
        }
        .crane__list .crane-cable-1 {
            width: 60%;
            top: 0;
            left: 11%;
            transform-origin: right 0;
            -webkit-animation: cable-1__movement 12s infinite alternate;
            animation: cable-1__movement 12s infinite alternate;
        }
        .crane__list .crane-cable-2 {
            width: 19%;
            top: 0;
            right: 8%;
            transform-origin: top left;
            -webkit-animation: cable-2__movement 12s infinite alternate;
            animation: cable-2__movement 12s infinite alternate;
        }
        .crane__list .crane-cable-3 {
            height: 30%;
            top: 22%;
            left: 9%;
            transform-origin: right center;
            -webkit-animation: cable-3__movement 12s ease-in-out infinite alternate;
            animation: cable-3__movement 12s ease-in-out infinite alternate;
        }
        .crane__list .crane-cable-3::after {
            content: "";
            display: block;
            position: absolute;
            height: 0.2em;
            width: 9000%;
            bottom: 0;
            left: -4500%;
            background: #c9c9c9;
            border: 1px solid #969696;
        }
        .crane__list .crane-stand {
            width: 5%;
            height: 100%;
            right: 25%;
            z-index: 1;
            background: linear-gradient(to top, #969696, #e3e3e3);
        }
        .crane__list .crane-weight {
            width: 8%;
            height: 20%;
            right: 4%;
            top: 12%;
            z-index: 2;
            background: #d6d6d6;
            transform-origin: 0 center;
            -webkit-animation: crane-weight__movement 12s infinite alternate;
            animation: crane-weight__movement 12s infinite alternate;
        }
        .crane__list .crane-cabin {
            width: 12%;
            height: 9%;
            right: 24%;
            top: 20%;
            z-index: 2;
            background: #d6d6d6;
        }
        .crane__list .crane-cabin::after {
            content: "";
            display: block;
            position: absolute;
            width: 100%;
            height: 10%;
            top: 60%;
            left: 0;
            background: white;
        }
        .crane__list .crane-arm {
            width: 100%;
            height: 7%;
            top: 15%;
            border-top-left-radius: 10px;
            z-index: 3;
            background: #d6d6d6;
        }

        .crane-1 {
            left: 20%;
            z-index: 10;
        }

        .crane-2 {
            left: 30%;
            z-index: 10;
            bottom: -1rem;
            z-index: -1;
            transform: scale(0.75) scaleX(-1);
        }
        @media screen and (max-width: 900px) {
            .crane-2 {
                display: none;
            }
        }
        .crane-2 .crane-cable-3 {
            -webkit-animation-delay: 3s;
            animation-delay: 3s;
        }

        .crane-3 {
            left: 40%;
            z-index: 10;
            bottom: -0.5rem;
            transform: scale(0.8);
        }
        @media screen and (max-width: 900px) {
            .crane-3 {
                z-index: -1;
                transform: scale(0.75) scaleX(-1);
            }
        }
        @media screen and (max-width: 900px) {
            .crane-3 {
                display: none;
            }
        }
        .crane-3 .crane-cable-3 {
            -webkit-animation-delay: 4.5s;
            animation-delay: 4.5s;
        }

        .tree__container {
            width: 100%;
            height: 62.6666666667px;
            left: 0;
            margin-bottom: 4px;
        }

        .tree__item {
            display: flex;
            flex-flow: column nowrap;
            position: absolute;
            justify-content: flex-end;
            align-items: center;
            left: 60%;
        }

        .tree__trunk {
            order: 2;
            display: block;
            position: absolute;
            width: 4px;
            height: 8px;
            margin-top: 8px;
            border-radius: 2px;
            background: #57473d;
        }

        .tree__leaves {
            order: 1;
            position: relative;
            border-top: 0 solid transparent;
            border-right: 4px solid transparent;
            border-bottom: 32px solid #969696;
            border-left: 4px solid transparent;
        }
        .tree__leaves::after {
            content: "";
            position: absolute;
            height: 100%;
            left: -4px;
            border-top: 0;
            border-right: 0;
            border-bottom: 32px solid #7d7d7d;
            border-left: 4px solid transparent;
        }

        .tree-1 {
            left: 66%;
        }
        @media screen and (max-width: 768px) {
            .tree-1 {
                display: none;
            }
        }

        .tree-2 {
            left: 67%;
        }
        @media screen and (max-width: 768px) {
            .tree-2 {
                display: none;
            }
        }

        .tree-4 {
            left: 57%;
        }

        .tree-5 {
            left: 58%;
        }

        .tree-7 {
            left: 51%;
        }
        @media screen and (max-width: 450px) {
            .tree-7 {
                display: none;
            }
        }

        .tree-8 {
            left: 52%;
        }
        @media screen and (max-width: 450px) {
            .tree-8 {
                display: none;
            }
        }

        @-webkit-keyframes cable-1__movement {
            0%, 20% {
                transform: rotateY(0) rotateZ(-10deg);
            }
            70%, 100% {
                transform: rotateY(45deg) rotateZ(-10deg);
            }
        }

        @keyframes cable-1__movement {
            0%, 20% {
                transform: rotateY(0) rotateZ(-10deg);
            }
            70%, 100% {
                transform: rotateY(45deg) rotateZ(-10deg);
            }
        }
        @-webkit-keyframes cable-2__movement {
            0%, 20% {
                transform: rotateY(0) rotateZ(29deg);
            }
            70%, 100% {
                transform: rotateY(15deg) rotateZ(29deg);
            }
        }
        @keyframes cable-2__movement {
            0%, 20% {
                transform: rotateY(0) rotateZ(29deg);
            }
            70%, 100% {
                transform: rotateY(15deg) rotateZ(29deg);
            }
        }
        @-webkit-keyframes cable-3__movement {
            0% {
                transform: translate(0, 0);
            }
            20% {
                transform: translate(2500%, -18%);
            }
            60% {
                transform: translate(11000%, -25%);
            }
            70% {
                height: 30%;
                transform: translate(9100%, -25%);
            }
            90%, 100% {
                height: 80%;
                transform: translate(9100%, -15%);
            }
        }
        @keyframes cable-3__movement {
            0% {
                transform: translate(0, 0);
            }
            20% {
                transform: translate(2500%, -18%);
            }
            60% {
                transform: translate(11000%, -25%);
            }
            70% {
                height: 30%;
                transform: translate(9100%, -25%);
            }
            90%, 100% {
                height: 80%;
                transform: translate(9100%, -15%);
            }
        }
        @-webkit-keyframes crane__movement {
            0%, 20% {
                transform: rotateY(0);
            }
            70%, 100% {
                transform: rotateY(45deg);
            }
        }
        @keyframes crane__movement {
            0%, 20% {
                transform: rotateY(0);
            }
            70%, 100% {
                transform: rotateY(45deg);
            }
        }
        @-webkit-keyframes crane-weight__movement {
            0%, 20% {
                transform: rotateY(0) translateX(0);
            }
            70%, 100% {
                transform: rotateY(45deg) translateX(-50%);
            }
        }
        @keyframes crane-weight__movement {
            0%, 20% {
                transform: rotateY(0) translateX(0);
            }
            70%, 100% {
                transform: rotateY(45deg) translateX(-50%);
            }
        }
    </style>
@endsection

@section('content')
    <div id="access-denied" style="overflow: hidden;">
        <div class="container">
            <h1>:) Oops!</h1>
            <br /><br />
            <h2>Temporairement fermée pour maintenance</h2>
            @if(\SAND\Classe\Application::is_under_update())
                <pre>{{MAINTENANCE_MESSAGE}}</pre>
            @endif
            <br /><br />
            <h3>L'application est de retour bientôt!</h3>
            <p>
                Désolé pour cette gêne mais il se trouve que l'application est en maintenance à ce moment !
                <br/>
                    — Le service
                <br/>
                <a href="mailto:application@localhost.fr">application@localhost.fr</a>
            </p>
            <br /><br />
            <br /><br />
            <br /><br />
            <br /><br />
            <br /><br />
            <br /><br />
            <div id="gears">
                <section class="city-stuff">
                    <ul class="skyscrappers__list">
                        <li class="skyscrapper__item skyscrapper-1"></li>
                        <li class="skyscrapper__item skyscrapper-2"></li>
                        <li class="skyscrapper__item skyscrapper-3"></li>
                        <li class="skyscrapper__item skyscrapper-4"></li>
                        <li class="skyscrapper__item skyscrapper-5"></li>
                    </ul>
                    <ul class="tree__container">
                        <li class="tree__list">
                            <ul class="tree__item tree-1">
                                <li class="tree__trunk"></li>
                                <li class="tree__leaves"></li>
                            </ul>
                            <ul class="tree__item tree-2">
                                <li class="tree__trunk"></li>
                                <li class="tree__leaves"></li>
                            </ul>
                            <ul class="tree__item tree-3">
                                <li class="tree__trunk"></li>
                                <li class="tree__leaves"></li>
                            </ul>
                            <ul class="tree__item tree-4">
                                <li class="tree__trunk"></li>
                                <li class="tree__leaves"></li>
                            </ul>
                            <ul class="tree__item tree-5">
                                <li class="tree__trunk"></li>
                                <li class="tree__leaves"></li>
                            </ul>
                            <ul class="tree__item tree-6">
                                <li class="tree__trunk"></li>
                                <li class="tree__leaves"></li>
                            </ul>
                            <ul class="tree__item tree-7">
                                <li class="tree__trunk"></li>
                                <li class="tree__leaves"></li>
                            </ul>
                            <ul class="tree__item tree-8">
                                <li class="tree__trunk"></li>
                                <li class="tree__leaves"></li>
                            </ul>
                        </li>
                    </ul>
                    <ul class="crane__list crane-1">
                        <li class="crane__item crane-cable crane-cable-1"></li>
                        <li class="crane__item crane-cable crane-cable-2"></li>
                        <li class="crane__item crane-cable crane-cable-3"></li>
                        <li class="crane__item crane-stand"></li>
                        <li class="crane__item crane-weight"></li>
                        <li class="crane__item crane-cabin"></li>
                        <li class="crane__item crane-arm"></li>
                    </ul>
                    <ul class="crane__list crane-2">
                        <li class="crane__item crane-cable crane-cable-1"></li>
                        <li class="crane__item crane-cable crane-cable-2"></li>
                        <li class="crane__item crane-cable crane-cable-3"></li>
                        <li class="crane__item crane-stand"></li>
                        <li class="crane__item crane-weight"></li>
                        <li class="crane__item crane-cabin"></li>
                        <li class="crane__item crane-arm"></li>
                    </ul>
                    <ul class="crane__list crane-3">
                        <li class="crane__item crane-cable crane-cable-1"></li>
                        <li class="crane__item crane-cable crane-cable-2"></li>
                        <li class="crane__item crane-cable crane-cable-3"></li>
                        <li class="crane__item crane-stand"></li>
                        <li class="crane__item crane-weight"></li>
                        <li class="crane__item crane-cabin"></li>
                        <li class="crane__item crane-arm"></li>
                    </ul>
                </section>
            </div>
        </div>
    </div>

@endsection