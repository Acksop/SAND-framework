@extends('body')

@section('sidebar')
    @parent

    <p>This is appended to the master sidebar.</p>
@endsection

@section('content')
        <div class="donate-description">
            <h2 class="section-title" id="donate">Become a Sponsor !</h2>
        </div>

        <div class="donate-footer" style="margin:auto;">
            <a href="https://www.patreon.com/" class="beer-button button">Be a sponsor on Patreon</a>
            <a href="https://www.paypal.me/" class="beer-button button-secondary">Donate via PayPal</a>
        </div>


@endsection