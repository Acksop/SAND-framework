@extends('body')

@section('top-css')
    @parent
    <style>
        #countUp {
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            height: 100%;
        }
        #countUp .number {
            font-size: 4rem;
            font-weight: 500;
        }
        #countUp .number + .text {
            margin: 0 0 1rem;
        }
        #countUp .text {
            font-weight: 300;
            text-align: center;
        }
    </style>
@endsection

@section('content')
    <div class="container">
        <div class="row">
            <div class="xs-12 md-6 mx-auto">
                <div id="countUp">
                    <div class="number" data-count="404">0</div>
                    <div class="text">Page not found</div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('bottom-javascript')
    @parent
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="https://cdn.3up.dk/in-view@0.6.1"></script>
    <script type="text/javascript">
        var formatThousandsNoRounding = function(n, dp){
            var e = '', s = e+n, l = s.length, b = n < 0 ? 1 : 0,
                i = s.lastIndexOf(','), j = i == -1 ? l : i,
                r = e, d = s.substr(j+1, dp);
            while ( (j-=3) > b ) { r = '.' + s.substr(j, 3) + r; }
            return s.substr(0, j + 3) + r +
                (dp ? ',' + d + ( d.length < dp ?
                    ('00000').substr(0, dp - d.length):e):e);
        };

        var hasRun = false;

        inView('#countUp').on('enter', function() {
            if (hasRun == false) {
                $('.number').each(function() {
                    var $this = $(this),
                        countTo = $this.attr('data-count');

                    $({ countNum: $this.text()}).animate({
                            countNum: countTo
                        },
                        {
                            duration: 2000,
                            easing:'linear',
                            step: function() {
                                $this.text(formatThousandsNoRounding(Math.floor(this.countNum)));
                            },
                            complete: function() {
                                $this.text(formatThousandsNoRounding(this.countNum));
                            }
                        });
                });
                hasRun = true;
            }
        });
    </script>
@endsection