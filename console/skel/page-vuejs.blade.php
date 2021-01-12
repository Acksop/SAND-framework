@extends('body')

@section('top-javascript'')
    @parent
    <script src="https://unpkg.com/vue/dist/vue.js"></script>
    <script src="https://unpkg.com/axios/dist/axios.min.js"></script>
@endsection

@section('content')
    <h1>%PAGE% - VUE.js Controlleur</h1>
	<br/><br/><br/>
	<div id="app">
            <div>
                <input v-model="searchText" placeholder="Search...">
            </div>    
            <div v-if="items" >
                <a href="#" v-for="item in itemsSearched" :key="item.id">
                    <div>
                      <div>
                          <h2>
                            {{ item.title }}
                          </h2>
                        </div>
                    </div>
                    <div>
                        <p>
                            {{ item.description.slice(0, 300) + "..." }}
                          </p>
                    </div>
                    <div>
                      <span>Year : {{ item.release_date }}</span>
                      <span>Director : {{ item.director }}</span>
                      <span>Producer : {{ item.producer }}</span>
                    </div>
                </a>
            </div>
        
        </div>
@endsection

@section('bottom-javascript'')
    @parent
    <script>
        const vue = new Vue({
            el: '#app',
            data: {
                items: [],
                searchText: ''
            },
            mounted() {
                axios
					.get('https://ghibliapi.herokuapp.com/films')
					.then(response => {
						this.items = response.data;
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
    </script>
@endsection