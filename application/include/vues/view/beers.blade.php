@extends('body')

@section('sidebar')
    @parent

    <p>This is appended to the master sidebar.</p>
@endsection

@section('content')
    <h2 class="section-title" id="donate">Buy me some beers</h2>

    <div class="donate-container">
        <div class="donate-description">
            <p>
                SAND FrameWork is an CC Universal-licensed open source project and completely free to use.
            </p>
            <p>
                However, the amount of effort needed to maintain and develop new features for the project is not sustainable
                without proper financial backing.
                You can support its ongoing development by being a backer or a sponsor on
                <a href="https://www.patreon.com/"> Patreon campaign</a>
                (recurring, with perks for different tiers), and get your company logo here.
            </p>
            <p>
                Also, you can make a <a href="https://www.paypal.me/">one time donation via PayPal</a>.
            </p>
        </div>

        <div class="donate-footer">
            <a href="https://www.patreon.com/" class="beer-button button-ghost">Be a sponsor on Patreon</a>
            <a href="https://www.paypal.me/" class="beer-button button-secondary">Donate via PayPal</a>
        </div>

        <img src="assets/img/beer.svg" alt="" class="donate-beer">
    </div>

@endsection