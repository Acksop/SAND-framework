@extends('body')

@section('top-javascript')
    @parent
    <script src="https://unpkg.com/react@^16/umd/react.production.min.js"></script>
    <script src="https://unpkg.com/react-dom@16.13.0/umd/react-dom.production.min.js"></script>
    <script src="https://unpkg.com/babel-standalone@6.26.0/babel.js"></script>
@endsection

@section('content')
    <h1>%%PAGE%% - REACT.js Controlleur</h1>
	<br/><br/><br/>
    <div id="root"></div>
        @endsection

@section('bottom-javascript')
    @parent
    <script type="text/babel">
        class App extends React.Component {
            state = {
                data: [],
            }

            // Code is invoked after the component is mounted/inserted into the DOM tree.
            componentDidMount() {
                const url =
                    'https://ghibliapi.herokuapp.com/films'

                fetch(url)
                    .then((result) => result.json())
                    .then((result) => {
                        this.setState({
                            data: result,
                        })
                    })
            }

            render() {
                const {data} = this.state

                const result = data.map(obj => {
                    return (
                        <a href="#"  key="{obj.id}">
                        <div>
                            <div>
                                <h2>
                                    {obj.title}
                                </h2>
                            </div>
                        </div>
                        <div>
                            <p>
                                {obj.description.slice(0, 300) + "..." }
                            </p>
                        </div>
                        <div>
                            <span>Year : {obj.release_date }</span>
                            <span>Director : {obj.director }</span>
                            <span>Producer : {obj.producer }</span>
                        </div>
                    </a>);
                })
                return <div>{result}</div>
            }
        }

        ReactDOM.render(<App />, document.getElementById('root'))
    </script>
@endsection