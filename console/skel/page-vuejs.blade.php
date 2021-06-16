@extends('body')

@section('top-javascript')
    @parent
    <script src="https://unpkg.com/vue@2.6.12/dist/vue.js"></script>
    <script src="https://unpkg.com/axios@0.21.1/dist/axios.min.js"></script>
@endsection

@section('content')
    <h1>ghibli - VUE.js Controlleur</h1>
    <br/><br/><br/>
    <div id="app">
        <div>
            <input v-model="searchText" placeholder="Search...">
        </div>
        <div v-if="is_loading" id="is-loading">
            <div class="lds-ellipsis"><div></div><div></div><div></div><div></div></div>
        </div>
        <div v-if="items" >
            <a href="#" v-for="item in itemsSearched" :key="item.id">
                <div>
                    <div>
                        <h2>
                            @{{ item.title }}
                        </h2>
                    </div>
                </div>
                <div>
                    <p>
                        @{{ item.description.slice(0, 300) + "..." }}
                    </p>
                </div>
                <div>
                    <span>Year : @{{ item.release_date }}</span>
                    <span>Director : @{{ item.director }}</span>
                    <span>Producer : @{{ item.producer }}</span>
                </div>
            </a>
        </div>

    </div>
@endsection

@section('bottom-javascript')
    @parent
    <script>
        @if(\MVC\Classe\Browser::get() !== 'Internet Explorer')
        const vue = new Vue({
            el: '#app',
            data: {
                items: [],
                searchText: '',
                is_loading: true,
            },
            mounted() {
                axios
                    .get('https://ghibliapi.herokuapp.com/films')
                    .then(response => {
                        this.items = response.data;
                        this.is_loading = false
                    })
                    .catch(error => console.log(error))
            },
            computed : {
                itemsSearched : function(){
                    var self = this;
                    if( this.searchText == ''){
                        return this.items;
                    }
                    return this.items.filter(function(item){
                        // https://www.reddit.com/r/vuejs/comments/62kfae/how_do_i_create_very_simple_instant_search_filter/
                        // Must be of string type
                        return item.title.toLowerCase().indexOf(self.searchText) >= 0 ||
                            item.producer.toLowerCase().indexOf(self.searchText) >= 0 ||
                            item.director.toLowerCase().indexOf(self.searchText) >= 0 ||
                            item.release_date.toString().indexOf(self.searchText) >= 0;

                    });
                }
            }
        });
        @else
        const vue = new Vue({
            el: '#app',
            data: {
                items: [],
                searchText: '',
                is_loading: true,
            },
            mounted: function() {
                axios
                    .get('https://ghibliapi.herokuapp.com/films')
                    .then(function(response) {
                        this.items = response.data;
                        this.is_loading = false;
                        document.getElementById('is-loading').style.display = 'none';
                    })
                    .catch(function(error) {console.log(error)})
            },
            computed: {
                itemsSearched : function(){
                    var self = this;
                    if( this.searchText == ''){
                        return this.items;
                    }
                    return this.items.filter(function(item){
                        // https://www.reddit.com/r/vuejs/comments/62kfae/how_do_i_create_very_simple_instant_search_filter/
                        // Must be of string type
                        return item.title.toLowerCase().indexOf(self.searchText) >= 0 ||
                            item.producer.toLowerCase().indexOf(self.searchText) >= 0 ||
                            item.director.toLowerCase().indexOf(self.searchText) >= 0 ||
                            item.release_date.toString().indexOf(self.searchText) >= 0;

                    });
                }
            }
        });
        @endif
    </script>
@endsection