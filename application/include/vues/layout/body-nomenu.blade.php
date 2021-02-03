@extends('system')

@section('body')
    <!-- Body Inner -->
    <div class="body-inner">

        <section id="page-content">
            <div class="container">
                @yield('content')
            </div>
        </section>

    </div>
    <!-- end: Body Inner -->
@endsection
