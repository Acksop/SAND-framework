@extends('body')


@section('content')
    <div class="flex-center-container">
        De 1 à 10, combien d'étoiles donneriez-vous pour le framework SAND?
        <br/><br/><br/>
        <div class="form-field box">
            <form method="POST" action="{{\SAND\Classe\Url::link_rewrite(true,"send-feedback")}}">
            <select name="glsr-custom-options" id="glsr-custom-options" class="star-rating" data-options='{"clearable":false, "tooltip":true}'>
                <option value="">Select a rating</option>
                <option value="10">10</option>
                <option value="9">9</option>
                <option value="8" selected>8</option>
                <option value="7">7</option>
                <option value="6">6</option>
                <option value="5">5</option>
                <option value="4">4</option>
                <option value="3">3</option>
                <option value="2">2</option>
                <option value="1">1</option>
            </select>
                <br/>
                <input type="submit" class="btn btn-outline-primary" value="Noter">
            </form>
        </div>
    </div>
    <center>
    <br/><br/>
    <br/><br/>
    <br/><br/>
    Un bug, une question ? envoyez-moi un courriel !
    </center>
@endsection

@section('top-css')
    @parent
    <link rel="stylesheet" href="{{\SAND\Classe\Url::asset_rewrite('assets/git-submodules/star-rating/dist/star-rating.css')}}">
@endsection

@section('top-javascript')
    @parent
    <script src="{{\SAND\Classe\Url::asset_rewrite('assets/git-submodules/star-rating/dist/star-rating.js')}}"></script>
@endsection

@section('bottom-javascript')
    @parent
    <script>
        var stars = new StarRating('.star-rating');
    </script>
@endsection