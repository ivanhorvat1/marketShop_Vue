<template>
    <div align="center">
        <button @click="fetchArticles('maxi')" class="btn btn-primary">Maxi Smrznuto</button>
        <button @click="fetchArticles('idea')" class="btn btn-primary">Idea Smrznuto</button>
        <button @click="fetchArticles('dis')" class="btn btn-primary">Dis Smrznuto</button>
        <br><br>
        <button @click="fetchProducts()" class="btn btn-primary">Uporedi artikle</button>
        <h4 v-if="products.length > 0" align="left">Ukupan broj uporedjenih artikala: {{filteredProducts.length}}</h4>
        <h4 v-else align="left">Ukupan broj artikala {{shop}}: {{filteredProducts.length}}</h4><br>
        <!--<input type="text" v-model="search" placeholder="Search title.."/>-->

        <div class="form-group has-search col-sm-3">
            <span class="fa fa-search form-control-feedback"></span>
            <input type="text" v-model="search" class="form-control" placeholder="Search">
        </div>

        <div class="col-sm-8"></div>
        <div v-if="articles.length > 0" class="form-group col-sm-4">
            <label for="sel1">Sortiranje</label>
            <select class="form-control" id="sel1" @change="fetchArticles(shop)" v-model="key">
                <option :selected="key == 'opadajuce'" value="opadajuce">Sortiranje po opadajucim cenama</option>
                <option value="rastuce">Sortiranje po rastucim Cenama</option>
            </select>
        </div>
        <div class="row">
            <div class="wrap">
                <div class="box one" v-if="products.length > 0"
                     v-for="article in filteredProducts.slice(startSlice,endSlice)" v-bind:key="article.code"
                     v-bind:style="[{ 'background-image': 'url(https://d3el976p2k4mvu.cloudfront.net' + article.imageUrl + ')' },styles]"
                     @click="info(article,$event.target)"
                     style="cursor: pointer; height: 550px;" v-b-tooltip.hover :title="article.body">
                    <p class="textOverflow" align="center">{{ article.body }}</p>
                    <div class="poster p1">
                        <h4 v-if="article.maxiCena">
                            <img style="height: 50px; width: 80px" src="images/delhaize-maxi-logo-vector.png"/>
                            <span><b>{{ article.maxiCena.substring(0, article.maxiCena.length - 3) }}</b></span>
                        </h4>
                        <h4 v-if="article.ideaCena">
                            <img style="height: 50px; width: 80px" src="images/Idea_Logo_resized.png"/>
                            <span><b>{{ article.ideaCena.substring(0, article.ideaCena.length - 3) }}</b></span>
                        </h4>
                        <h4 v-if="article.disCena">
                            <img style="height: 50px; width: 80px" src="images/dis_krnjevo_resized.gif"/>
                            <span><b>{{ article.disCena.substring(0, article.disCena.length - 3) }}</b></span>
                        </h4>
                        <h4 v-if="article.univerexportCena">
                            <img style="height: 50px; width: 80px" src="images/univer_resized.png"/>
                            <span><b>{{ article.univerexportCena.substring(0, article.univerexportCena.length - 3) }}</b></span>
                        </h4>
                    </div>
                </div>
            </div>
            <!--<div v-if="products.length > 0" class="col-sm-3" v-for="article in filteredProducts.slice(startSlice,endSlice)" v-bind:key="article.code">
                <div class="card">
                    <div class="card-body">
                        <img center v-if="article.imageUrl /*&& article.shop == 'maxi'*/" class="center modal-trigger"
                             :src="'https://d3el976p2k4mvu.cloudfront.net'+article.imageUrl" width="180px"
                             height="180px"
                             @click="info(article,$event.target)"
                              style="cursor: pointer;" title="klik za dodatne info">
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
                        <hr>
                        &lt;!&ndash;<b-button @click="info(article,$event.target)" class="mr-1" variant="primary">
                            Info
                        </b-button>&ndash;&gt;
                    </div>
                </div>
            </div>-->
            <div class="wrap">
                <div style="height: 450px;" class="box one" v-if="articles.length > 0"
                     v-for="articlea in filteredProducts.slice(startSlice,endSlice)" v-bind:key="articlea.code" v-b-tooltip.hover :title="articlea.body">
                    <p class="textOverflowSeparated" align="center">{{ articlea.body }}</p>
                    <div  style="margin-top: 50px">
                        <img center v-if="articlea.imageUrl !== null && articlea.shop == 'maxi'" class="center"
                             :src="'https://d3el976p2k4mvu.cloudfront.net'+articlea.imageUrl" width="180px">
                        <img center v-else-if="articlea.imageUrl !== null && articlea.shop == 'idea'" class="center"
                             :src="'https://www.idea.rs/online/'+articlea.imageUrl" width="180px" height="180px">
                        <img center v-else-if="articlea.imageUrl !== null && articlea.shop == 'dis'" class="center"
                             :src="'https://www.idea.rs/online/'+articlea.imageUrl" width="180px" height="180px">
                        <img v-else center style="height: 200px; width: 180px;" :src=articlea.imageDefault>
                    </div>
                    <div class="poster p1"  style="margin-top: 50px">
                        <h5 v-if="articlea.shop == 'maxi'">
                            <img style="height: 50px; width: 80px" src="images/delhaize-maxi-logo-vector.png"/>
                            <b>{{articlea.formattedPrice }}</b>
                        </h5>
                        <h5 v-if="articlea.shop == 'idea'">
                            <img style="height: 50px; width: 80px" src="images/Idea_Logo_resized.png"/>
                            <b>{{articlea.formattedPrice }}</b>
                        </h5>
                        <h5 v-if="articlea.shop == 'dis'">
                            <img style="height: 50px; width: 80px" src="images/dis_krnjevo_resized.gif"/>
                            <b>{{articlea.formattedPrice }}</b>
                        </h5>
                        <h5 v-if="articlea.shop == 'univerexport'">
                            <img style="height: 50px; width: 80px" src="images/univer_resized.png"/>
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
                             :src="'https://d3el976p2k4mvu.cloudfront.net'+articlea.imageUrl" width="180px">
                        <img center v-else-if="articlea.imageUrl !== null && articlea.shop == 'idea'" class="center"
                             :src="'https://www.idea.rs/online/'+articlea.imageUrl" width="180px" height="180px">
                        <img center v-else-if="articlea.imageUrl !== null && articlea.shop == 'dis'" class="center"
                             :src="'https://www.idea.rs/online/'+articlea.imageUrl" width="180px" height="180px">
                        <img v-else center style="height: 200px; width: 180px;" :src=articlea.imageDefault>
                        <p align="center"><b>{{ articlea.title }}:</b> {{ articlea.body }}</p>
                        <hr>
                        <p align="right"><img v-if="articlea.shop == 'maxi'" style="height: 50px; width: 80px"
                                              src="images/delhaize-maxi-logo-vector.png"/>
                            <img v-else-if="articlea.shop == 'idea'" style="height: 25px; width: 75px"
                                 src="images/Idea_Logo.png"/>
                            <img v-else-if="articlea.shop == 'dis'" style="height: 50px; width: 80px"
                                 src="images/dis_krnjevo.gif"/>
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
                                 src="images/delhaize-maxi-logo-vector.png"/>
                            <h6><b>{{maxiCena}}</b></h6>
                            <h6><b>{{supplementaryPriceMaxi}}</b></h6><br>
                        </div>
                        <div class="col-sm-6">
                            <img style="height: 50px; width: 80px"
                                 src="images/Idea_Logo_resized.png"/>
                            <h6><b>{{ideaCena}}</b></h6>
                            <h6><b>{{supplementaryPriceIdea}}</b></h6>
                        </div>
                        <div class="col-sm-6">
                            <img style="height: 50px; width: 80px"
                                 src="images/dis_krnjevo_resized.gif"/>
                            <h6><b>{{disCena}}</b></h6>
                        </div>
                        <div class="col-sm-6">
                            <img style="height: 50px; width: 80px"
                                 src="images/univer_resized.png"/>
                            <h6><b>{{univerexportCena}}</b></h6>
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
                    <img style="height: 330px; width: 300px"
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
                    </div>
                </div>
            </div>
        </div>-->
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
                ideaCena: '--',
                maxiCena: '--',
                disCena: '--',
                univerexportCena: '--',
                shop: '',
                infoModal: {
                    id: 'info-modal'
                },
                search: '',
            }
        },
        computed: {
            filteredProducts (){
                if(this.search){
                    if(this.products.length > 0){
                        return this.products.filter((item)=>{
                            return item.body.toLowerCase().includes(this.search.toLowerCase());
                        })
                    }else{
                        return this.articles.filter((item)=>{
                            return item.body.toLowerCase().includes(this.search.toLowerCase());
                        })
                    }
                }else{
                    if(this.products.length > 0) {
                        return this.products;
                    }else {
                        return this.articles;
                    }
                }
            },
            styles: function() {
                var height = 450;


                if(this.products[0].disCena){
                    height = 500;
                }

                if(this.products[0].univerexportCena) {
                    height = 550;
                }

                return {
                    height: height+'px',
                    'cursor': 'pointer'
                };
            }
        },
        created() {
            this.fetchProducts();
            window.addEventListener('scroll', this.handleScroll);
        },
        methods: {
            info(article,button) {
                this.title = article.title;
                this.body = article.body;
                this.imageUrl = article.imageUrl;
                this.supplementaryPriceIdea = article.supplementaryPriceIdea;
                if (article.supplementaryPriceMaxi) {
                    this.supplementaryPriceMaxi = article.supplementaryPriceMaxi.replace('rsd/Kg', 'Din/Kg');
                } else {
                    this.supplementaryPriceMaxi = '';
                }

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
                fetch('api/action_freeze_fetch')
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
                axios.get('api/action_freeze_fetch_separate', {
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
            modalClick(modalId, title, body, imageurl, supplementaryPriceIdea, supplementaryPriceMaxi, ideaCena, maxiCena, disCena) {
                this.modalId = modalId;
                this.title = title;
                this.body = body;
                this.imageUrl = imageurl;
                this.supplementaryPriceIdea = supplementaryPriceIdea;
                if (supplementaryPriceMaxi) {
                    this.supplementaryPriceMaxi = supplementaryPriceMaxi.replace('rsd/Kg', 'Din/Kg');
                } else {
                    this.supplementaryPriceMaxi = '';
                }

                this.ideaCena = ideaCena.substring(0, ideaCena.length - 3) + 'Din';
                this.maxiCena = maxiCena.substring(0, maxiCena.length - 3) + 'Din';
                this.disCena = disCena.substring(0, disCena.length - 3) + 'Din';
            }
        }
    }
</script>