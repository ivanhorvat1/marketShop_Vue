<template>
    <div>
        <h2>Products</h2>
        <!--<form @submit.prevent="addArticle" class="mb-3">
            <div class="form-group">
                <input type="text" class="form-control" placeholder="Name" v-model="article.title">
                <textarea class="form-control" placeholder="Description" v-model="article.body"></textarea>
                <button type="submit" class="btn btn-light btn-block">Save</button>
            </div>
        </form>-->
        <button @click="fetchArticles(0,'maxi','akcija')" class="btn btn-primary">Maxi Akcija</button>
        <button @click="storeArticles('maxi','akcija')" class="btn btn-primary">Ubaci Akcija Maxi</button>
        <button @click="fetchArticles(0, 'maxi', 'pice')" class="btn btn-primary">Maxi Pice</button>
        <button @click="storeArticles('maxi','pice')" class="btn btn-primary">Ubaci Pice Maxi</button>
        <button @click="fetchArticles(0, 'maxi', 'meso')" class="btn btn-primary">Maxi Meso</button>
        <button @click="storeArticles('maxi','meso')" class="btn btn-primary">Ubaci Meso Maxi</button>
        <button @click="fetchArticles(0, 'maxi', 'slatkisi')" class="btn btn-primary">Maxi Slatkisi</button>
        <button @click="storeArticles('maxi','slatkisi')" class="btn btn-primary">Ubaci Slatkise Maxi</button>
        <button @click="fetchArticles(0, 'maxi', 'smrznuti')" class="btn btn-primary">Maxi Smrznuto</button>
        <button @click="storeArticles('maxi','smrznuti')" class="btn btn-primary">Ubaci Smrznuto Maxi</button>
        <!--<button @click="fetchArticles(0,'meso')" class="btn btn-primary">Mesnati Proizvodi</button>
        <button @click="fetchArticles(0,'slatkisi')" class="btn btn-primary">Čokolade, keks, slane i slatke grickalice</button>-->
        <button @click="fetchArticles(0,'idea','akcija')" class="btn btn-primary">Idea Akcija</button>
        <button @click="storeArticles('idea','akcija')" class="btn btn-primary">Ubaci Akcija Idea</button>
        <button @click="getCategoriesIdea('60007883')" class="btn btn-primary">Pice Idea</button>
        <button @click="getCategoriesIdea('60007823')" class="btn btn-primary">Meso Idea</button>
        <button @click="getCategoriesIdea('60007780')" class="btn btn-primary">Meso2 Idea</button>
        <button @click="getCategoriesIdea('60007896')" class="btn btn-primary">Slatkisi Idea</button>
        <button @click="getCategoriesIdea('60007907')" class="btn btn-primary">Smrznuti Proiz Idea</button>
        <button @click="checkChildren()" class="btn btn-primary">check idea children</button>
        <!--:disabled="this.categoryArray.length < 16"-->
        <button @click="storeArticles('idea','pice')" class="btn btn-primary">Ubaci Pice Idea</button>
        <button @click="storeArticles('idea','meso')" class="btn btn-primary">Ubaci Meso Idea</button>
        <button @click="storeArticles('idea','slatkisi')" class="btn btn-primary">Ubaci Slatkisi Idea</button>
        <button @click="storeArticles('idea','smrznuti')" class="btn btn-primary">Ubaci smrznuti Idea</button>
        <br><br>
        <h4>Total on Action products: {{akcija.length}}</h4><br>

        <!-- slideshow -->

        <div align="center" class="container">
            <div id="demo" class="carousel slide mb-5 " data-ride="carousel">
                <div class="carousel-inner">
                    <div class="carousel-item active" style="height: 320px">
                        <h3 style="padding-top: 100px">Izdvajamo</h3>
                    </div>
                    <div class="carousel-item" v-for="article in akcija.slice(startSlice,endSlice)"
                         v-bind:key="article.code">
                        <img center v-if="article.imageUrl && article.shop == 'maxi'" class="center"
                             :src="'https://d3el976p2k4mvu.cloudfront.net'+article.imageUrl" width="180px"
                             height="180px">
                        <img center v-else-if="article.imageUrl && article.shop == 'idea'" class="center"
                             :src="'https://www.idea.rs/online/'+article.imageUrl" width="180px" height="180px">
                        <img center v-else :src="'article.imageDefault'">
                        <h6 align="center"><b>{{ article.title }}:</b> {{ article.body }}</h6>
                        <hr>
                        <h5 align="center"><img v-if="article.shop == 'idea'" style="height: 18px; width: 75px"
                                                src="https://upload.wikimedia.org/wikipedia/commons/2/2f/Idea_Logo.svg"/>
                            <img v-else style="height: 50px; width: 80px"
                                 src="https://www.seeklogovector.com/wp-content/uploads/2018/06/delhaize-maxi-logo-vector.png"/><b
                                    style="color: goldenrod"> {{
                                article.formattedPrice.substring(0,article.formattedPrice.length - 3) }}</b></h5>
                        <h5 v-if="article.maxiCena" align="center"><img style="height: 50px; width: 80px"
                                                                        src="https://www.seeklogovector.com/wp-content/uploads/2018/06/delhaize-maxi-logo-vector.png"/><b>
                            {{ article.maxiCena.substring(0, article.maxiCena.length - 3) }}</b></h5>
                        <h5 v-else align="center"><img style="height: 18px; width: 75px"
                                                       src="https://upload.wikimedia.org/wikipedia/commons/2/2f/Idea_Logo.svg"/><b>
                            {{ article.ideaCena.substring(0, article.ideaCena.length - 3) }}</b></h5>
                    </div>
                </div>

                <!-- Left and right controls -->
                <a class="carousel-control-prev" href="#demo" data-slide="prev">
                    <span class="carousel-control-prev-icon" style="background-color: black; border-radius: 50%; width: 30px; height: 30px;"></span>
                    <span class="sr-only">Previous</span>
                </a>
                <a class="carousel-control-next" href="#demo" data-slide="next">
                    <span class="carousel-control-next-icon" style="background-color: black; border-radius: 50%; width: 30px; height: 30px;"></span>
                    <span class="sr-only">Next</span>
                </a>
            </div>
        </div>

        <!-- end slideshow -->

        <h4 v-if="articles.length > 0">Total products: {{articles.length}}</h4><br>

        <div class="row">
            <div class="col-sm-3" v-for="article in articles.slice(startSlice,endSlice)" v-bind:key="article.code">
                <div class="card">
                    <div class="card-body">
                        <img center v-if="article.imageUrl && article.shop == 'maxi'" class="center"
                             :src="'https://d3el976p2k4mvu.cloudfront.net'+article.imageUrl" width="180px"
                             height="180px">
                        <img center v-else-if="article.imageUrl && article.shop == 'idea'" class="center"
                             :src="'https://www.idea.rs/online/'+article.imageUrl" width="180px" height="180px">
                        <img center v-else :src="'article.imageDefault'">
                        <p align="center"><b>{{ article.title }}:</b> {{ article.body }}</p>
                        <hr>
                        <p align="right"><img v-if="article.shop == 'idea'" style="height: 18px; width: 75px"
                                              src="https://upload.wikimedia.org/wikipedia/commons/2/2f/Idea_Logo.svg"/><img
                                v-else style="height: 50px; width: 80px"
                                src="https://www.seeklogovector.com/wp-content/uploads/2018/06/delhaize-maxi-logo-vector.png"/><b>
                            {{ article.formattedPrice.substring(0,article.formattedPrice.length - 3) }}</b></p>
                        <p v-if="article.maxiCena" align="right"><img style="height: 50px; width: 80px"
                                                                      src="https://www.seeklogovector.com/wp-content/uploads/2018/06/delhaize-maxi-logo-vector.png"/><b>
                            {{ article.maxiCena.substring(0, article.maxiCena.length - 3) }}</b></p>
                        <p v-else align="right"><img style="height: 18px; width: 75px"
                                                     src="https://upload.wikimedia.org/wikipedia/commons/2/2f/Idea_Logo.svg"/><b>
                            {{ article.ideaCena.substring(0, article.ideaCena.length - 3) }}</b></p>
                        <hr>
                    </div>
                </div>
            </div>
        </div>
        <button @click="toTopFunction()" id="BtnToTop" title="Go to top">&uarr;</button>
        <div id="loader"></div>
        <br><br>
        <!--<div class="col-md-12 text-center">
            <button :disabled="pagination.nextPage >= pagination.lastPage" @click="fetchArticles(pagination.nextPage,category)" class="btn btn-primary">Load More</button>
        </div>
        <br>-->
        <!--<nav aria-label="Page navigation example">
        <ul class="pagination">
            <li v-bind:class="[{disabled: pagination.prevPage<0}]" class="page-item"><a class="page-link" href="#"
                                                                                        @click="fetchArticles(pagination.prevPage)">Previous</a>
            </li>
            <li class="page-item disabled"><a class="page-link text-dark" href="#">Page {{ pagination.currentPage }} of {{ pagination.lastPage }}</a></li>
            <li v-bind:class="[{disabled: pagination.currentPage===pagination.lastPage}]" class="page-item"><a
                    class="page-link" href="#" @click="fetchArticles(pagination.nextPage)">Next</a></li>
        </ul>
    </nav>-->
    </div>
</template>
<script>
    export default {
        data() {
            return {
                startSlice: 0,
                endSlice: 12,
                articles: [],
                akcija: [],
                maxi: [],
                idea: [],
                maxi_idea: [],
                article: {
                    id: '',
                    title: '',
                    body: ''
                },
                article_id: '',
                pagination: {},
                edit: false,
                shop: '',
                products: [],
                categoryArray: [],
                drinks: [],
                meat: [],
                sweet: [],
                freeze: []
            }
        },
        created() {
            this.fetchSaleProducts();
            //this.fetchDrinkProducts();
            //this.fetchSweetProducts();
            //this.fetchMeatProducts();
            window.addEventListener('scroll', this.handleScroll);
        },
        methods: {
            toTopFunction() {
                document.body.scrollTop = 0;
                document.documentElement.scrollTop = 0;
            },
            handleScroll() {
                if(this.articles.length > 0) {
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

                    if (this.articles.length < this.endSlice) {
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
            hideLoader() {
                document.getElementById("loader").style.display = "none";
            },
            dinamicUrl(currentPage, shop, category) {
                let url;
                //https://cors-anywhere.herokuapp.com/
                //https://crossorigin.me/
                if (shop === 'maxi' && category === 'akcija') {
                    /*if (currentPage == 0) {
                        url = 'https://www.maxi.rs/view/QlProductListComponentController/getSearchPageData?componentId=PromotionListingProductListingComponent&pageNumber=' + currentPage + '&sort=promotionType';
                    } else {
                        url = 'https://www.maxi.rs/view/QlProductListComponentController/loadMore?componentId=PromotionListingProductListingComponent&pageNumber=' + currentPage + '&sort=promotionType';
                    }*/
                   url = 'https://www.maxi.rs/view/QlProductListComponentController/getSearchPageData?componentId=PromotionListingProductListingComponent&pageNumber=40&sort=promotion'
                }
                else if (shop === 'idea' && category === 'akcija') {
                    url = 'https://cors-anywhere.herokuapp.com/https://www.idea.rs/online/v2/offers?per_page=48&page=' + currentPage + '&filter%5Bsort%5D=offerSoldStatisticsDesc';
                }
                else if (shop === 'maxi' && category === 'pice') {
                    url = 'https://www.maxi.rs/online/Pice%2C-kafa-i-caj/c/01/getSearchPageData?pageSize=5000&pageNumber=0&sort=promotion';
                }
                else if (shop === 'maxi' && category === 'meso') {
                    url = 'https://www.maxi.rs/online/Meso%2C-mesne-i-riblje-prera%C4%91evine/c/02/getSearchPageData?pageSize=5000&pageNumber=0&sort=promotion';
                }
                else if (shop === 'maxi' && category === 'slatkisi') {
                    url = 'https://www.maxi.rs/online/Cokolade%2C-keks%2C-slane-i-slatke-grickalice/c/09/getSearchPageData?q=%3Apopularity&sort=promotion&pageSize=5000&pageNumber=0';
                }
                else if (shop === 'maxi' && category === 'smrznuti') {
                    url = 'https://www.maxi.rs/online/Smrznuti-proizvodi/c/10/getSearchPageData?pageSize=5000&pageNumber=0&sort=promotion';
                }
                /*else if (shop === 'idea' && category === 'alkpica') {
                    url = 'https://www.idea.rs/online/v2/categories/60007888/products?per_page=5000&page=1&filter%5Bsort%5D=offerSoldStatisticsDesc';
                }
                else if(this.shop === 'meso'){
                    if(currentPage == 0) {
                         url = 'https://cors-anywhere.herokuapp.com/https://www.maxi.rs/online/Meso%2C-mesne-i-riblje-prera%C4%91evine/c/02/getSearchPageData?pageSize=12&pageNumber=' + currentPage + '&sort=promotion';
                    }else{
                         url = 'https://cors-anywhere.herokuapp.com/https://www.maxi.rs/online/Meso,-mesne-i-riblje-prera%C4%91evine/c/02/loadMore?pageSize=12&pageNumber=' + currentPage + '&sort=promotion';
                    }
                }
                else if(this.shop === 'slatkisi') {
                    if (currentPage == 0) {
                        url = 'https://cors-anywhere.herokuapp.com/https://www.maxi.rs/online/Cokolade%2C-keks%2C-slane-i-slatke-grickalice/c/09/getSearchPageData?pageSize=12';
                    } else {
                        url = 'https://cors-anywhere.herokuapp.com/https://www.maxi.rs/online/Cokolade%2C-keks%2C-slane-i-slatke-grickalice/c/09/loadMore?pageSize=12&pageNumber=' + currentPage + '&sort=popularity';
                    }
                }
                else{
                    if(currentPage == 0) {
                        url = 'https://cors-anywhere.herokuapp.com/https://www.maxi.rs/view/QlProductListComponentController/getSearchPageData?componentId=PromotionListingProductListingComponent&pageNumber=' + currentPage + '&sort=promotionType';
                    }else{
                        url = 'https://cors-anywhere.herokuapp.com/https://www.maxi.rs/view/QlProductListComponentController/loadMore?componentId=PromotionListingProductListingComponent&pageNumber=' + currentPage + '&sort=promotionType';
                    }
                }*/

                return url;
            },
            fetchSaleProducts() {
                fetch('api/action_sale_fetch')
                    .then(res => res.json())
                    .then(res => {
                        this.akcija = JSON.parse(res.data);
                        $('body').addClass('loaded');
                    })
            },
            fetchDrinkProducts() {
                fetch('api/action_drink_fetch')
                    .then(res => res.json())
                    .then(res => {
                        this.articles = JSON.parse(res.data);
                    })
            },
            fetchMeatProducts() {
                fetch('api/action_meat_fetch')
                    .then(res => res.json())
                    .then(res => {
                        this.articles = JSON.parse(res.data);
                    })
            },
            fetchSweetProducts() {
                fetch('api/action_sweet_fetch')
                    .then(res => res.json())
                    .then(res => {
                        this.articles = JSON.parse(res.data);
                    })
            },
            fetchArticles(currentPage, shop, category) {
                let vm = this;
                let url;

                this.shop = shop;
                if (currentPage == 0) {
                    this.articles = [];
                }
                currentPage = currentPage || '0';
                url = vm.dinamicUrl(currentPage, shop, category);

                if(shop == 'idea') {
                    fetch(url)
                        .then(res => res.json())
                        .then(res => {
                            if (currentPage == 0) {
                                if (this.shop === 'idea') {
                                    //this.articles = res.products;
                                    this.idea = res.products;
                                    vm.makePagination(res._page, currentPage, category);
                                } else {
                                    //this.articles = res.results;
                                    this.maxi = res.results;
                                    vm.makePagination(res.pagination, currentPage, category);
                                }
                            } else {
                                if (this.shop === 'idea') {
                                    //this.articles = this.articles.concat(res.products);
                                    this.idea = this.idea.concat(res.products);
                                    vm.makePagination(this.pagination, currentPage, category);
                                } else {
                                    //this.articles = this.articles.concat(res);
                                    this.maxi = this.maxi.concat(res);
                                    vm.makePagination(this.pagination, currentPage, category);
                                }
                            }
                        })
                        .catch(err => console.log(err));
                }else{
                    if(category == 'akcija'){
                        for (let i = 0; i <= 60; i++) {
                                $.getJSON('http://api.allorigins.ml/get?url=' + encodeURIComponent("https://www.maxi.rs/view/QlProductListComponentController/getSearchPageData?componentId=PromotionListingProductListingComponent&pageNumber="+i+"&sort=promotion") + '&callback=?', function(data){
                                    let res = JSON.parse(data.contents);
                                    vm.storeMaxi(res,i);
                                });
                        }
                    }else{
                        $.getJSON('http://api.allorigins.ml/get?url=' + encodeURIComponent(url) + '&callback=?', function(data){
                            let res = JSON.parse(data.contents);
                            vm.storeMaxi(res);
                            alert('done');
                        });
                    }
                }
            },
            storeMaxi(res,i){
                if(i == 0){
                    this.maxi = res.results;
                }else if(i > 0){
                    this.maxi = this.maxi.concat(res.results);
                }else{
                    this.maxi = res.results;
                }
                console.log(this.maxi);
            },
            makePagination(paginate, currentPage, category) {
                let pagination;
                if (currentPage == 0) {
                    if (this.shop === 'idea') {
                        pagination = {
                            totalArticles: paginate.item_count,
                            currentPage: paginate.current,
                            lastPage: paginate.page_count,
                            nextPage: paginate.current + 1,
                            prevPage: paginate.current - 1
                        };
                    } else {
                        pagination = {
                            totalArticles: paginate.totalNumberOfResults,
                            currentPage: paginate.currentPage,
                            lastPage: paginate.numberOfPages,
                            nextPage: paginate.currentPage + 1,
                            prevPage: paginate.currentPage - 1
                        };
                    }

                    if (this.shop === 'idea' && category == 'akcija') {
                        for (let i = 2; i <= pagination.lastPage; i++) {
                            this.fetchArticles(i, this.shop, category);
                        }
                    }/* else if (this.shop === 'maxi' && category == 'akcija') {
                        for (let i = 1; i <= 60; i++) {
                            this.fetchArticles(i, this.shop, category);
                        }
                    }*/
                } else {
                    document.getElementById("loader").style.display = "none";
                    pagination = {
                        totalArticles: this.pagination.totalArticles,
                        currentPage: currentPage,
                        lastPage: this.pagination.lastPage,
                        nextPage: currentPage + 1,
                        prevPage: currentPage - 1
                    };
                }
                this.pagination = pagination;
            },
            storeArticles(shop, category) {
                let vm = this;
                let picaIdea = [];
                if (shop == 'maxi') {
                    vm.storeVisit(this.maxi, shop, category);
                } else if (shop == 'idea' && category == 'pice' || category == 'meso' || category == 'slatkisi' || category == 'smrznuti') {
                    this.idea.forEach(function (item) {
                        item.forEach(function (data) {
                            picaIdea.push(data);
                        })
                    })
                    vm.storeVisit(picaIdea, shop, category);
                } else if (shop == 'idea' && category == 'akcija') {
                    vm.storeVisit(this.idea, shop, category);
                }
            },
            storeVisit(article, shop, category) {
                this.products = [];
                this.products.push(article);
                axios({
                    method: 'post',
                    url: '/api/action_sale_store',
                    data: {
                        products: this.products,
                        shop: shop,
                        category: category
                    }
                });
            },
            getCategoriesIdea(categoryNumber, data) {
                let vm = this;
                let url = 'https://cors-anywhere.herokuapp.com/https://www.idea.rs/online/v2/categories/' + categoryNumber;

                this.categoryArray.push(data);
                //console.log(this.categoryArray);
                fetch(url)
                    .then(res => res.json())
                    .then(res => {
                        if (res.has_children) {
                            res.children.forEach(function (item) {
                                vm.getCategoriesIdea(item.id, item);
                            })
                        }
                    })
            },
            checkChildren() {
                let noChildren = [];
                let ideapice = [];
                this.categoryArray.splice(0, 1);
                this.categoryArray.forEach(function (item) {
                    if (!item.has_children) {
                        noChildren.push(item.id)
                    }
                });

                noChildren.forEach(function (items) {
                    let url = 'https://cors-anywhere.herokuapp.com/https://www.idea.rs/online/v2/categories/' + items + '/products?per_page=5000&page=1&filter%5Bsort%5D=offerSoldStatisticsDesc';
                    fetch(url)
                        .then(res => res.json())
                        .then(res => {
                            ideapice.push(res.products);
                        })
                });

                this.idea = ideapice;
            }
        }
    }
</script>