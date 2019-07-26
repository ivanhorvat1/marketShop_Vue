<template>
    <div align="center">
        <!--<button @click="fetchArticles('maxi')" class="btn btn-primary">Maxi Pice</button>
        <button @click="fetchArticles('idea')" class="btn btn-primary">Idea Pice</button>
        <button @click="fetchArticles('dis')" class="btn btn-primary">Dis Pice</button>
        <button @click="fetchArticles('univerexport')" class="btn btn-primary">Univerexport Pice</button>
        <br><br>-->
        <!--<button @click="fetchProducts()" class="btn btn-primary">Uporedi artikle</button>-->

        <div class="row mb-3">
            <div class="col-sm-1"></div>
            <div @click="fetchArticles('maxi')" class="buttonCustom1 col-lg-2">Maxi Pice</div>
            <div @click="fetchArticles('idea')" class="buttonCustom1 col-lg-2">Idea Pice</div>
            <div @click="fetchArticles('dis')" class="buttonCustom1 col-lg-2">Dis Pice</div>
            <div @click="fetchArticles('univerexport')" class="buttonCustom1 col-lg-2">univerexport Pice</div>
        </div>

        <div class="col-md-3 mb-3">
            <div class="select">
                <select v-model="selected" @change="compareDynamically">
                    <option :value="null" disabled>Uporedi markete</option>
                    <option value="DrinksMvI">Uporedi Maxi/Idea</option>
                    <option value="DrinksAll">Uporedi sve markete</option>
                </select>
            </div>
        </div>

        <div class="row mb-2">

            <div class="col-sm-2"></div>
            <div class="form-group has-search col-sm-4">
                <!--<span class="fa fa-search form-control-feedback"></span>-->
                <!--<input type="text" v-model="search" class="form-control" placeholder="Unesite proizvod ili marku (pivo,coca-cola,..)">-->
                <div class="search">
                    <div>
                        <input v-model="search" type="text" placeholder="Unesite proizvod ili marku (pivo,coca-cola,..)" required>
                    </div>
                </div>
            </div>

            <div v-if="articles.length > 0" class="form-group col-sm-4">
                <!--<label for="sel1">Sortiranje</label>-->
                <div class="select">
                    <select id="sel1" @change="fetchArticles(shop)" v-model="key">
                        <option :selected="key == 'opadajuce'" value="opadajuce">Sortiranje po opadajucim cenama</option>
                        <option value="rastuce">Sortiranje po rastucim Cenama</option>
                    </select>
                </div>
            </div>
        </div>

        <h4 v-if="products.length > 0" align="left">Ukupan broj uporedjenih artikala: {{filteredProducts.length}}</h4>
        <h4 v-else align="left">Ukupan broj artikala {{shop}}: {{filteredProducts.length}}</h4><br>

        <div class="row">
            <div class="wrap">
                <div class="box one" v-if="products.length > 0"
                     v-for="article in filteredProducts.slice(startSlice,endSlice)" v-bind:key="article.code"
                     v-bind:style="[{ 'background-image': 'url(' + article.imageUrl + ')' },styles]"
                     @click="info(article,$event.target)"
                     style="cursor: pointer; height: 550px;" v-b-tooltip.hover.html="'<h6>'+article.body+'</h6>'">
                    <p class="textOverflow" align="center">{{ article.body }}</p>
                    <div class="poster p1">
                        <h4 v-if="article.maxiCena">
                            <img style="height: 50px; width: 80px" src="images/market_logo/delhaize-maxi-logo-vector.png"/>
                            <span><b>{{ article.maxiCena.substring(0, article.maxiCena.length - 3) }}</b></span>
                        </h4>
                        <h4 v-if="article.ideaCena">
                            <img style="height: 50px; width: 80px" src="images/market_logo/Idea_Logo_resized.png"/>
                            <span><b>{{ article.ideaCena.substring(0, article.ideaCena.length - 3) }}</b></span>
                        </h4>
                        <h4 v-if="article.disCena">
                            <img style="height: 50px; width: 80px" src="images/market_logo/dis_krnjevo_resized.gif"/>
                            <span><b>{{ article.disCena.substring(0, article.disCena.length - 3) }}</b></span>
                        </h4>
                        <h4 v-if="article.univerexportCena">
                            <img style="height: 50px; width: 80px" src="images/market_logo/univer_resized.png"/>
                            <span><b>{{ article.univerexportCena.substring(0, article.univerexportCena.length - 3) }}</b></span>
                        </h4>
                        <!--<h4>

                            <div class="h-25 d-inline-block" style="width: 120px;"><img
                                    style="height: 50px; width: 80px" src="images/delhaize-maxi-logo-vector.png"/>
                                <span><b>{{ article.maxiCena.substring(0, article.maxiCena.length - 3) }}</b></span>
                            </div>
                            <div class="h-25 d-inline-block" style="width: 120px;"><img
                                    style="height: 50px; width: 80px" src="images/Idea_Logo_resized.png"/>
                                <span><b>{{ article.ideaCena.substring(0, article.ideaCena.length - 3) }}</b></span>
                            </div>
                            <div class="h-25 d-inline-block" style="width: 120px;"><img
                                    style="height: 45px; width: 80px" src="images/dis_krnjevo.gif"/>
                                <span><b>{{ article.disCena.substring(0, article.disCena.length - 3) }}</b></span></div>
                            <div class="h-25 d-inline-block" style="width: 120px; background-color: rgba(0,0,255,.1)">
                                Height 100%
                            </div>

                        </h4>-->
                    </div>
                </div>
            </div>
            <!--<div v-if="products.length > 0" class="col-sm-3" v-for="article in filteredProducts.slice(startSlice,endSlice)" v-bind:key="article.code">
                <div class="card shadow-lg p-3 mb-5 bg-white rounded">
                    <div class="card-body">
                        <img center v-if="article.imageUrl /*&& article.shop == 'maxi'*/" class="center modal-trigger"
                             :src="'https://d3el976p2k4mvu.cloudfront.net'+article.imageUrl" width="180px"
                             height="180px"
                             @click="info(article,$event.target)"
                             style="cursor: pointer;" title="klik za dodatne info">
                        <img center v-if="article.imageUrl == null" :src=article.imageDefault>
                        &lt;!&ndash;<img center v-else-if="article.imageUrl && article.shop == 'idea'" class="center"
                             :src="'https://www.idea.rs/online/'+article.imageUrl" width="180px" height="180px">&ndash;&gt;
                        &lt;!&ndash;<img center v-else :src="'article.imageDefault'">&ndash;&gt;
                        <p class="textOverflow" align="center">{{ article.body }}</p>
                        <hr>
                        <p v-if="article.maxiCena" align="right"><img style="height: 50px; width: 80px"
                                                                      src="images/delhaize-maxi-logo-vector.png"/><b>
                            {{ article.maxiCena.substring(0, article.maxiCena.length - 3) }}</b></p>
                        <p v-if="article.ideaCena" align="right"><img style="height: 25px; width: 75px"
                                                                      src="images/Idea_Logo.png"/><b>
                            {{ article.ideaCena.substring(0, article.ideaCena.length - 3) }}</b></p>
                        &lt;!&ndash;<p v-else align="right"><img style="height: 18px; width: 75px"
                                                     src="https://upload.wikimedia.org/wikipedia/commons/2/2f/Idea_Logo.svg"/><b>
                            {{ article.ideaCena.substring(0, article.ideaCena.length - 3) }}</b></p>&ndash;&gt;
                        <p v-if="article.disCena" align="right"><img style="height: 50px; width: 80px"
                                                                     src="images/dis_krnjevo.gif"/><b>
                            {{ article.disCena.substring(0, article.disCena.length - 3) }}</b></p>
                        <p v-if="article.univerexportCena" align="right"><img style="height: 35px; width: 100px"
                                                                     src="images/univer.png"/><b>
                            {{ article.univerexportCena.substring(0, article.univerexportCena.length - 3) }}</b></p>
                        <hr>
                    </div>
                </div>
            </div>-->
            <div class="wrap">
                <div style="height: 450px;" class="box one" v-if="articles.length > 0"
                     v-for="articlea in filteredProducts.slice(startSlice,endSlice)" v-bind:key="articlea.code"
                     v-b-tooltip.hover.html="'<h6>'+articlea.body+'</h6>'">
                    <p class="textOverflowSeparated" align="center">{{ articlea.body }}</p>
                    <div style="margin-top: 50px">
                        <img center v-if="articlea.imageUrl !== null && articlea.shop == 'maxi'" class="center"
                             :src="'https://d3el976p2k4mvu.cloudfront.net'+articlea.imageUrl" width="180px">
                        <img center v-else-if="articlea.imageUrl !== null && articlea.shop == 'idea'" class="center"
                             :src="'https://www.idea.rs/online/'+articlea.imageUrl" width="180px" height="180px">
                        <img center v-else-if="articlea.imageUrl !== null && articlea.shop == 'dis'" class="center"
                             :src="'https://www.idea.rs/online/'+articlea.imageUrl" width="180px" height="180px">
                        <img center v-else-if="articlea.imageUrl !== null && articlea.shop == 'univerexport'" class="center"
                             :src="articlea.imageUrl" width="90px" height="180px">
                        <img v-else center style="height: 180px; width: 180px;" :src=articlea.imageDefault>
                    </div>
                    <div class="poster p1" style="margin-top: 50px">
                        <h5 v-if="articlea.shop == 'maxi'">
                            <img style="height: 50px; width: 80px" src="images/market_logo/delhaize-maxi-logo-vector.png"/>
                            <b>{{articlea.formattedPrice }}</b>
                        </h5>
                        <h5 v-if="articlea.shop == 'idea'">
                            <img style="height: 50px; width: 80px" src="images/market_logo/Idea_Logo_resized.png"/>
                            <b>{{articlea.formattedPrice }}</b>
                        </h5>
                        <h5 v-if="articlea.shop == 'dis'">
                            <img style="height: 50px; width: 80px" src="images/market_logo/dis_krnjevo_resized.gif"/>
                            <b>{{articlea.formattedPrice }}</b>
                        </h5>
                        <h5 v-if="articlea.shop == 'univerexport'">
                            <img style="height: 50px; width: 80px" src="images/market_logo/univer_resized.png"/>
                            <b>{{articlea.formattedPrice }}</b>
                        </h5>
                    </div>
                </div>
            </div>
            <!--<div v-if="articles.length > 0" class="col-sm-3" v-for="articlea in filteredProducts.slice(startSlice,endSlice)"
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
                        <p align="center"><b>{{ articlea.title }}:</b> {{ articlea.body }}</p>
                        <hr>
                        <p align="right"><img v-if="articlea.shop == 'maxi'" style="height: 50px; width: 80px"
                                              src="images/delhaize-maxi-logo-vector.png"/>
                            <img v-else-if="articlea.shop == 'idea'" style="height: 25px; width: 75px"
                                 src="images/Idea_Logo.png"/>
                            <img v-else-if="articlea.shop == 'dis'" style="height: 50px; width: 100px"
                                 src="images/dis_krnjevo.gif"/>
                            <img v-else-if="articlea.shop == 'univerexport'" style="height: 35px; width: 100px"
                                 src="images/univer.png"/>
                            &lt;!&ndash;http://www.serbianlogo.com/thumbnails/univerexport.gif&ndash;&gt;
                            <b>{{articlea.formattedPrice }}</b></p>
                        <hr>
                    </div>
                </div>
            </div>-->
        </div>
        <b-modal :id="infoModal.id"
                 ref="modal"
                 title="Info"
                 size="lg"
                 ok-only>
            <div class="row">
                <div class="modal-header col-sm-12">
                    <h4 style="align: center">{{body}}</h4>
                </div>
                <div class="modal-content col-sm-6">
                    <img class="mx-auto d-block" style="height: 330px; width: 300px"
                         :src="'https://d3el976p2k4mvu.cloudfront.net'+imageUrl"/>
                </div>
                <div class="modal-content col-sm-6">
                    <!--<h6>{{body}}</h6>-->
                    <div class="row">
                        <div class="col-sm-6">
                            <img style="height: 55px; width: 80px"
                                 src="images/market_logo/delhaize-maxi-logo-vector.png"/>
                            <h5><b>{{maxiCena}}</b></h5>
                            <h5><b>{{supplementaryPriceMaxi}}</b></h5><br>
                        </div>
                        <div class="col-sm-6">
                            <img style="height: 50px; width: 80px"
                                 src="images/market_logo/Idea_Logo_resized.png"/>
                            <h5><b>{{ideaCena}}</b></h5>
                            <h5><b>{{supplementaryPriceIdea}}</b></h5>
                        </div>
                        <div class="col-sm-6">
                            <img style="height: 50px; width: 80px"
                                 src="images/market_logo/dis_krnjevo_resized.gif"/>
                            <h5><b>{{disCena}}</b></h5>
                        </div>
                        <div class="col-sm-6">
                            <img style="height: 50px; width: 80px"
                                 src="images/market_logo/univer_resized.png"/>
                            <h5><b>{{univerexportCena}}</b></h5>
                        </div>
                    </div>
                </div>
            </div>
        </b-modal>
        <!--<div :id="'modal'+modalId" class="modal" style="width: 60%">
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
        </div>-->
        <div id="overlay" style="display:none;">
            <div class="spinnerr"></div>
            <br/>
            <h5>Loading...</h5>
        </div>
        <!--<button @click="toTopFunction()" id="BtnToTop" title="Go to top">&uarr;</button>-->
        <div id="BtnToTop1" class="bg"></div>
        <button @click="toTopFunction()" id="BtnToTop" class="buttonToTop" target="_blank"><i class="fa fa-chevron-up" aria-hidden="true"></i></button>
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
                ideaCena: '--',
                maxiCena: '--',
                disCena: '--',
                univerexportCena: '--',
                shop: '',
                search: '',
                infoModal: {
                    id: 'info-modal'
                },
                selected: 'DrinksAll',
            }
        },
        computed: {
            filteredProducts() {
                if (this.search) {
                    if (this.products.length > 0) {
                        return this.products.filter((item) => {
                            return item.body.toLowerCase().includes(this.search.toLowerCase());
                        })
                    } else {
                        return this.articles.filter((item) => {
                            return item.body.toLowerCase().includes(this.search.toLowerCase());
                        })
                    }
                } else {
                    if (this.products.length > 0) {
                        return this.products;
                    } else {
                        return this.articles;
                    }
                }
            },
            styles: function () {
                var height = 450;

                if (this.products[0].disCena) {
                    height = 500;
                }

                if (this.products[0].univerexportCena) {
                    height = 550;
                }

                return {
                    height: height + 'px',
                    'cursor': 'pointer'
                };
            }
        },
        created() {
            this.fetchProducts();
            window.addEventListener('scroll', this.handleScroll);
        },
        methods: {
            info(article, button) {
                this.title = article.title;
                this.body = article.body;
                this.imageUrl = article.imageUrl;
                this.supplementaryPriceIdea = article.supplementaryPriceIdea;
                this.supplementaryPriceMaxi = article.supplementaryPriceMaxi;

                if (article.ideaCena) {
                    this.ideaCena = article.ideaCena.substring(0, article.ideaCena.length - 3) + 'Din';
                }

                if (article.maxiCena) {
                    this.maxiCena = article.maxiCena.substring(0, article.maxiCena.length - 3) + 'Din';
                }

                if (article.disCena) {
                    this.disCena = article.disCena.substring(0, article.disCena.length - 3) + 'Din';
                }

                if (article.univerexportCena) {
                    this.univerexportCena = article.univerexportCena.substring(0, article.univerexportCena.length - 3) + 'Din';
                }
                this.$root.$emit('bv::show::modal', this.infoModal.id, button)
            },
            toTopFunction() {
                /*document.body.scrollTop = 0;
                document.documentElement.scrollTop = 0;*/
                const scrollToTop = () => {
                    const c = document.documentElement.scrollTop || document.body.scrollTop;
                    if (c > 0) {
                        window.requestAnimationFrame(scrollToTop);
                        window.scrollTo(0, c - c / 8);
                    }
                };
                scrollToTop();
            },
            handleScroll() {
                if (this.products.length > 0 || this.articles.length > 0) {
                    let scroll = Math.ceil($(window).scrollTop() + $(window).height());
                    let windowHeight = Math.round($(document).height());

                    if (scroll >= windowHeight) {
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
                        document.getElementById("BtnToTop1").style.display = "block";
                    } else {
                        document.getElementById("BtnToTop").style.display = "none";
                        document.getElementById("BtnToTop1").style.display = "none";
                    }
                }
            },
            fetchProducts() {
                fetch('api/action_drink_fetch')
                    .then(res => res.json())
                    .then(res => {
                        this.endSlice = 12;
                        this.articles = '';
                        this.products = _.orderBy(res, 'price', 'desc');
                        // $('#preloader-wrapper').css("display", "none");
                        $('body').addClass('loaded');
                        window.scrollTo(0, 0);
                        $('#overlay').fadeOut();
                    })
            }, fetchArticles(shop) {
                if (shop == null) {
                    shop = 'maxi';
                }
                this.shop = shop;
                this.selected = null;
                $('#overlay').fadeIn();
                axios.get('api/action_drink_fetch_separate', {
                    params: {
                        shop: shop,
                        sort: this.key
                    }
                }).then(res => {
                    this.endSlice = 12;
                    this.products = '';
                    this.articles = res.data;
                    window.scrollTo(0, 0);
                    $('#overlay').fadeOut();
                })
            }, compareDynamically() {
                let vm = this;
                // vm.createOverlay();
                $('#overlay').fadeIn();

                if (this.selected == 'DrinksAll') {
                    vm.fetchProducts();
                } else {
                    fetch('api/drinks_fetch_compare_dynamically')
                        .then(res => res.json())
                        .then(res => {
                            this.endSlice = 12;
                            this.articles = '';
                            this.products = _.orderBy(res, 'price', 'desc');
                            $('#preloader-wrapper').css("display", "none");
                            $('body').addClass('loaded');
                            // $(".overlay").remove();
                            window.scrollTo(0, 0);
                            $('#overlay').fadeOut();
                        });
                }
            }/*,
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
            }*/
        }
    }
</script>