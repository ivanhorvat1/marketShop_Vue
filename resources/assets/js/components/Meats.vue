<template>
    <div align="center">
        <button @click="fetchArticles('maxi')" class="btn btn-primary">Maxi Meso</button>
        <button @click="fetchArticles('idea')" class="btn btn-primary">Idea Meso</button>
        <button @click="fetchArticles('dis')" class="btn btn-primary">Dis Meso</button><br><br>
        <button @click="fetchProducts()" class="btn btn-primary">Compare All Products</button>
        <h4 v-if="products.length > 0" align="left">Total compared products: {{products.length}}</h4>
        <h4 v-else align="left">Total products {{shop}}: {{articles.length}}</h4><br>
        <div class="col-sm-8"></div>
        <div v-if="articles.length > 0" class="form-group col-sm-4">
            <label for="sel1">Sortiranje</label>
            <select class="form-control" id="sel1" @change="fetchArticles(shop)" v-model="key">
                <option :selected="key == 'opadajuce'" value="opadajuce">Sortiranje po opadajucim cenama</option>
                <option value="rastuce">Sortiranje po rastucim Cenama</option>
            </select>
        </div>
        <div class="row">
            <div v-if="products.length > 0" class="col-sm-3" v-for="article in products.slice(startSlice,endSlice)" v-bind:key="article.code">
                <div class="card">
                    <div class="card-body">
                        <img center v-if="article.imageUrl !== null /*&& article.shop == 'maxi'*/" class="center"
                             :src="'https://d3el976p2k4mvu.cloudfront.net'+article.imageUrl" width="180px"
                             height="180px">
                        <img center v-if="article.imageUrl == null" :src=article.imageDefault>
                        <!--<img center v-else-if="article.imageUrl && article.shop == 'idea'" class="center"
                             :src="'https://www.idea.rs/online/'+article.imageUrl" width="180px" height="180px">-->
                        <p class="textOverflow" align="center"><!--<b>{{ article.title }}:</b>--> {{ article.body }}</p>
                        <hr>
                        <p v-if="article.maxiCena" align="right"><img style="height: 50px; width: 80px"
                                                                      src="https://www.seeklogovector.com/wp-content/uploads/2018/06/delhaize-maxi-logo-vector.png"/><b>
                            {{ article.maxiCena.substring(0, article.maxiCena.length - 3) }}</b></p>
                        <p v-if="article.ideaCena" align="right"><img style="height: 20px; width: 75px"
                                                                      src="https://upload.wikimedia.org/wikipedia/commons/2/2f/Idea_Logo.svg"/><b>
                            {{ article.ideaCena.substring(0, article.ideaCena.length - 3) }}</b></p>
                        <!--<p v-else align="right"><img style="height: 18px; width: 75px"
                                                     src="https://upload.wikimedia.org/wikipedia/commons/2/2f/Idea_Logo.svg"/><b>
                            {{ article.ideaCena.substring(0, article.ideaCena.length - 3) }}</b></p>-->
                        <!--https://liftoglasi.rs/wp-content/uploads/2015/02/dis-logo1.jpg-->
                        <p v-if="article.disCena" align="right"><img style="height: 50px; width: 80px"
                                                                     src="http://www.serbianlogo.com/thumbnails/dis_krnjevo.gif"/><b>
                            {{ article.disCena.substring(0, article.disCena.length - 3) }}</b></p>
                        <hr>
                    </div>
                </div>
            </div>
            <div v-if="articles.length > 0" class="col-sm-3" v-for="articlea in articles.slice(startSlice,endSlice)" v-bind:key="articlea.code">
                <div class="card">
                    <div class="card-body">
                        <img center v-if="articlea.imageUrl !== null && articlea.shop == 'maxi'" class="center"
                             :src="'https://d3el976p2k4mvu.cloudfront.net'+articlea.imageUrl" width="180px"
                             height="180px">
                        <img center v-else-if="articlea.imageUrl !== null && articlea.shop == 'idea'" class="center"
                             :src="'https://www.idea.rs/online/'+articlea.imageUrl" width="180px" height="180px">
                        <img center v-else-if="articlea.imageUrl !== null && articlea.shop == 'dis'" class="center"
                             :src="'https://www.idea.rs/online/'+articlea.imageUrl" width="180px" height="180px">
                        <img v-else center style="height: 200px; width: 180px" :src=articlea.imageDefault>
                        <p align="center"><b>{{ articlea.title }}:</b> {{ articlea.body }}</p>
                        <hr>
                        <p align="right"><img v-if="articlea.shop == 'maxi'" style="height: 50px; width: 80px"
                                              src="https://www.seeklogovector.com/wp-content/uploads/2018/06/delhaize-maxi-logo-vector.png"/>
                            <img v-else-if="articlea.shop == 'idea'" style="height: 20px; width: 75px"
                                 src="https://upload.wikimedia.org/wikipedia/commons/2/2f/Idea_Logo.svg"/>
                            <img v-else-if="articlea.shop == 'dis'" style="height: 50px; width: 80px"
                                 src="http://www.serbianlogo.com/thumbnails/dis_krnjevo.gif"/>
                            <b>{{articlea.formattedPrice }}</b></p>
                        <hr>
                    </div>
                </div>
            </div>
        </div>
        <button @click="toTopFunction()" id="BtnToTop" title="Go to top">&uarr;</button>
        <div id="loader"></div>
        <br><br>
    </div>
</template>
<script>
    export default {
        data() {
            return {
                startSlice: 0,
                endSlice: 12,
                products: [],
                articles: [],
                key: 'opadajuce',
                shop: ''
            }
        },
        created() {
            this.fetchProducts();
            window.addEventListener('scroll', this.handleScroll);
        },
        methods: {
            toTopFunction() {
                document.body.scrollTop = 0;
                document.documentElement.scrollTop = 0;
            },
            handleScroll() {
                if(this.products.length > 0 || this.articles.length > 0) {
                    let scroll = Math.ceil($(window).scrollTop() + $(window).height());
                    let windowHeight = Math.round($(document).height());

                    if (scroll == windowHeight) {
                        //if(this.pagination.nextPage <= this.pagination.lastPage) {
                        document.getElementById("loader").style.display = "block";
                        this.endSlice += 12;
                        //}
                    } else {
                        document.getElementById("loader").style.display = "none";
                    }

                    if (this.products.length < this.endSlice && this.articles.length < this.endSlice) {
                        document.getElementById("loader").style.display = "none";
                        return;
                    }

                    if (document.body.scrollTop > 20 || document.documentElement.scrollTop > 20) {
                        document.getElementById("BtnToTop").style.display = "block";
                    } else {
                        document.getElementById("BtnToTop").style.display = "none";
                    }
                }
            },
            fetchProducts() {
                let vm = this;
                fetch('api/action_meat_fetch')
                    .then(res => res.json())
                    .then(res => {
                        this.endSlice = 12;
                        this.articles = '';
                        this.products = _.orderBy(res, 'price','desc');
                        $('body').addClass('loaded');
                    })
            },fetchArticles(shop) {
                this.shop = shop;
                axios.get('api/action_meat_fetch_separate', {
                    params: {
                        shop: shop,
                        sort: this.key
                    }
                })
                    //.then(res => res.json())
                    .then(res => {
                        this.endSlice = 12;
                        this.products = '';
                        this.articles = res.data;
                    })
            }
        }
    }
</script>