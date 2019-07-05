<template>
    <div align="center">
        <button @click="fetchArticles('maxi')" class="btn btn-primary">Maxi Pice</button>
        <button @click="fetchArticles('idea')" class="btn btn-primary">Idea Pice</button>
        <button @click="fetchArticles('dis')" class="btn btn-primary">Dis Pice</button>
        <button @click="fetchArticles('univerexport')" class="btn btn-primary">Univerexport Pice</button>
        <br><br>
        <button @click="fetchProducts()" class="btn btn-primary">Compare All Products</button>
        <h4 v-if="products.length > 0" align="left">Total compared products: {{products.length}}</h4>
        <h4 v-else align="left">Total productss {{shop}}: {{articles.length}}</h4><br>
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
                        <img center v-if="article.imageUrl /*&& article.shop == 'maxi'*/" class="center modal-trigger"
                             :src="'https://d3el976p2k4mvu.cloudfront.net'+article.imageUrl" width="180px"
                             height="180px"
                             @click="modalClick(article.code, article.title, article.body, article.imageUrl, article.supplementaryPriceIdea, article.supplementaryPriceMaxi,
                              article.ideaCena, article.maxiCena, article.disCena, article.univerexportCena)"
                             :href="'#modal'+article.code" style="cursor: pointer;" title="dupli klik za dodatne info">
                        <!--<img center v-else-if="article.imageUrl && article.shop == 'idea'" class="center"
                             :src="'https://www.idea.rs/online/'+article.imageUrl" width="180px" height="180px">-->
                        <!--<img center v-else :src="'article.imageDefault'">-->
                        <p class="textOverflow" align="center"><!--<b>{{ article.title }}:</b>--> {{ article.body }}</p>
                        <hr>
                        <p v-if="article.maxiCena" align="right"><img style="height: 50px; width: 80px"
                                                                      src="images/delhaize-maxi-logo-vector.png"/><b>
                            {{ article.maxiCena.substring(0, article.maxiCena.length - 3) }}</b></p>
                        <p v-if="article.ideaCena" align="right"><img style="height: 25px; width: 75px"
                                                                      src="images/Idea_Logo.png"/><b>
                            {{ article.ideaCena.substring(0, article.ideaCena.length - 3) }}</b></p>
                        <!--<p v-else align="right"><img style="height: 18px; width: 75px"
                                                     src="https://upload.wikimedia.org/wikipedia/commons/2/2f/Idea_Logo.svg"/><b>
                            {{ article.ideaCena.substring(0, article.ideaCena.length - 3) }}</b></p>-->
                        <p v-if="article.disCena" align="right"><img style="height: 50px; width: 80px"
                                                                     src="images/dis_krnjevo.gif"/><b>
                            {{ article.disCena.substring(0, article.disCena.length - 3) }}</b></p>
                        <p v-if="article.univerexportCena" align="right"><img style="height: 35px; width: 100px"
                                                                     src="images/univer.png"/><b>
                            {{ article.univerexportCena.substring(0, article.univerexportCena.length - 3) }}</b></p>
                        <hr>
                    </div>
                </div>
            </div>
            <div v-if="articles.length > 0" class="col-sm-3" v-for="articlea in articles.slice(startSlice,endSlice)"
                 v-bind:key="articlea.code">
                <div class="card">
                    <div class="card-body">
                        <img center v-if="articlea.imageUrl !== null && articlea.shop == 'maxi'" class="center"
                             :src="'https://d3el976p2k4mvu.cloudfront.net'+articlea.imageUrl" width="180px"
                             height="180px">
                        <img center v-else-if="articlea.imageUrl !== null && articlea.shop == 'idea'" class="center"
                             :src="'https://www.idea.rs/online/'+articlea.imageUrl" width="180px" height="180px">
                        <img center v-else-if="articlea.imageUrl !== null && articlea.shop == 'dis'" class="center"
                             :src="'https://www.idea.rs/online/'+articlea.imageUrl" width="180px" height="180px">
                        <img center v-else-if="articlea.imageUrl !== null && articlea.shop == 'univerexport'" class="center"
                             :src="'https://www.idea.rs/online/'+articlea.imageUrl" width="180px" height="180px">
                        <img v-else center style="height: 200px; width: 180px" :src=articlea.imageDefault>
                        <p class="textOverflow" align="center"> {{ articlea.body }}</p>
                        <hr>
                        <p align="right"><img v-if="articlea.shop == 'maxi'" style="height: 50px; width: 80px"
                                              src="images/delhaize-maxi-logo-vector.png"/>
                            <img v-else-if="articlea.shop == 'idea'" style="height: 25px; width: 75px"
                                 src="images/Idea_Logo.png"/>
                            <img v-else-if="articlea.shop == 'dis'" style="height: 50px; width: 100px"
                                 src="images/dis_krnjevo.gif"/>
                            <img v-else-if="articlea.shop == 'univerexport'" style="height: 35px; width: 100px"
                                 src="images/univer.png"/>
                            <!--http://www.serbianlogo.com/thumbnails/univerexport.gif-->
                            <b>{{articlea.formattedPrice }}</b></p>
                        <hr>
                    </div>
                </div>
            </div>
        </div>
        <div :id="'modal'+modalId" class="modal" style="width: 60%">
            <div class="row">
                <div class="modal-header col-sm-12">
                    <h4 style="align: center">{{title}}</h4>
                </div>
                <div class="modal-content col-sm-6">
                    <img style="height: 280px; width: 270px"
                         :src="'https://d3el976p2k4mvu.cloudfront.net'+imageUrl"/>
                </div>
                <div class="modal-content col-sm-6">
                    <h6>{{body}}</h6>
                    <div class="row">
                        <div class="col-sm-6">
                            <img style="height: 55px; width: 80px"
                                 src="images/delhaize-maxi-logo-vector.png"/>
                            <h6><b>{{maxiCena}}</b></h6>
                            <h6><b>{{supplementaryPriceMaxi}}</b></h6><br>
                        </div>
                        <div class="col-sm-6 mt-3">
                            <img style="height: 25px; width: 75px"
                                 src="images/Idea_Logo.png"/>
                            <h6 class="mt-4"><b>{{ideaCena}}</b></h6>
                            <h6><b>{{supplementaryPriceIdea}}</b></h6>
                        </div>
                        <div class="col-sm-6">
                            <img style="height: 50px; width: 75px"
                                 src="images/dis_krnjevo.gif"/>
                            <h6><b>{{disCena}}</b></h6>
                        </div>
                        <div class="col-sm-6">
                            <img style="height: 35px; width: 100px"
                                 src="images/univer.png"/>
                            <h6><b>{{univerexportCena}}</b></h6>
                        </div>
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
                modalId: '',
                title: '',
                body: '',
                imageUrl: '',
                supplementaryPriceIdea: '',
                supplementaryPriceMaxi: '',
                ideaCena: '',
                maxiCena: '',
                disCena: '',
                univerexportCena: '',
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
                if (this.products.length > 0 || this.articles.length > 0) {
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
                fetch('api/action_drink_fetch')
                    .then(res => res.json())
                    .then(res => {
                        this.endSlice = 12;
                        this.articles = '';
                        this.products = _.orderBy(res, 'price','desc');
                        // $('#preloader-wrapper').css("display", "none");
                        $('body').addClass('loaded');
                    })
            }, fetchArticles(shop) {
                this.shop = shop;
                axios.get('api/action_drink_fetch_separate', {
                    params: {
                        shop: shop,
                        sort: this.key
                    }
                })
                    .then(res => {
                        this.endSlice = 12;
                        this.products = '';
                        this.articles = res.data;
                    })
            },
            modalClick(modalId, title, body, imageurl, supplementaryPriceIdea, supplementaryPriceMaxi, ideaCena, maxiCena, disCena, univerexportCena) {
                this.modalId = modalId;
                this.title = title;
                this.body = body;
                this.imageUrl = imageurl;
                this.supplementaryPriceIdea = supplementaryPriceIdea;
                if (supplementaryPriceMaxi) {
                    this.supplementaryPriceMaxi = supplementaryPriceMaxi.replace('rsd/L', 'Din/Kg');
                } else {
                    this.supplementaryPriceMaxi = '';
                }

                this.ideaCena = ideaCena.substring(0, ideaCena.length - 3) + 'Din';
                this.maxiCena = maxiCena.substring(0, maxiCena.length - 3) + 'Din';
                this.disCena = disCena.substring(0, disCena.length - 3) + 'Din';
                this.univerexportCena = univerexportCena.substring(0, univerexportCena.length - 3) + 'Din';
            }
        }
    }
</script>