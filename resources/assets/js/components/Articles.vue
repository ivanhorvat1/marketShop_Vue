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
        <button @click="fetchArticles(0,'maxi')" class="btn btn-primary">Maxi Akcija</button>
        <button @click="storeArticles('maxi','akcija')" class="btn btn-primary">Ubaci Akcija Maxi</button>
        <!--<button @click="fetchArticles(0,'pice')" class="btn btn-primary">Pice</button>
        <button @click="fetchArticles(0,'meso')" class="btn btn-primary">Mesnati Proizvodi</button>
        <button @click="fetchArticles(0,'slatkisi')" class="btn btn-primary">Čokolade, keks, slane i slatke grickalice</button>-->
        <button @click="fetchArticles(0,'idea')" class="btn btn-primary">Idea Akcija</button>
        <button @click="storeArticles('idea','akcija')" class="btn btn-primary">Ubaci Akcija Idea</button><br><br>
        <h4>Total products: {{akcija.length}}</h4><br>
        <div align="center" class="container">
            <div id="demo" class="carousel slide mb-5 " data-ride="carousel">

                <!-- The slideshow -->
                <div class="carousel-inner">
                    <div class="carousel-item active" style="height: 250px">
                        <h3 style="padding-top: 100px">Izdvajamo</h3>
                    </div>
                    <div class="carousel-item" v-for="article in akcija.slice(startSlice,endSlice)" v-bind:key="article.code">
                        <img center v-if="article.imageUrl && article.shop == 'maxi'" class="center" :src="'https://d3el976p2k4mvu.cloudfront.net'+article.imageUrl" width="180px" height="180px">
                        <img center v-else-if="article.imageUrl && article.shop == 'idea'" class="center" :src="'https://www.idea.rs/online/'+article.imageUrl" width="180px" height="180px">
                        <img center v-else :src="'article.imageDefault'">
                        <h6 align="center"><b>{{ article.title }}:</b> {{ article.body }}</h6>
                        <hr>
                        <h5 align="center"><img v-if="article.shop == 'idea'" style="height: 18px; width: 75px" src="https://upload.wikimedia.org/wikipedia/commons/2/2f/Idea_Logo.svg" />
                            <img v-else style="height: 50px; width: 80px" src="https://www.seeklogovector.com/wp-content/uploads/2018/06/delhaize-maxi-logo-vector.png" /><b style="color: goldenrod"> {{ article.formattedPrice.substring(0,article.formattedPrice.length - 3) }}</b></h5>
                        <h5 v-if="article.maxiCena" align="center"><img style="height: 50px; width: 80px" src="https://www.seeklogovector.com/wp-content/uploads/2018/06/delhaize-maxi-logo-vector.png" /><b> {{ article.maxiCena.substring(0, article.maxiCena.length - 3) }}</b></h5>
                        <h5 v-else align="center"><img style="height: 18px; width: 75px" src="https://upload.wikimedia.org/wikipedia/commons/2/2f/Idea_Logo.svg" /><b> {{ article.ideaCena.substring(0, article.ideaCena.length - 3) }}</b></h5>
                    </div>
                </div>

                <!-- Left and right controls -->
                <a class="carousel-control-prev" href="#demo" data-slide="prev">
                    <span class="carousel-control-prev-icon" style="background-color: black; border-radius: 50%; width: 30px; height: 30px;"></span>
                </a>
                <a class="carousel-control-next" href="#demo" data-slide="next">
                    <span class="carousel-control-next-icon" style="background-color: black; border-radius: 50%; width: 30px; height: 30px;"></span>
                </a>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-3" v-for="article in akcija.slice(startSlice,endSlice)" v-bind:key="article.code">
                <div class="card">
                    <div class="card-body">
                        <img center v-if="article.imageUrl && article.shop == 'maxi'" class="center" :src="'https://d3el976p2k4mvu.cloudfront.net'+article.imageUrl" width="180px" height="180px">
                        <img center v-else-if="article.imageUrl && article.shop == 'idea'" class="center" :src="'https://www.idea.rs/online/'+article.imageUrl" width="180px" height="180px">
                        <img center v-else :src="'article.imageDefault'">
                        <p align="center"><b>{{ article.title }}:</b> {{ article.body }}</p>
                        <hr>
                        <p align="right"><img v-if="article.shop == 'idea'" style="height: 18px; width: 75px" src="https://upload.wikimedia.org/wikipedia/commons/2/2f/Idea_Logo.svg" /><img v-else style="height: 50px; width: 80px" src="https://www.seeklogovector.com/wp-content/uploads/2018/06/delhaize-maxi-logo-vector.png" /><b> {{ article.formattedPrice.substring(0,article.formattedPrice.length - 3) }}</b></p>
                        <p v-if="article.maxiCena" align="right"><img style="height: 50px; width: 80px" src="https://www.seeklogovector.com/wp-content/uploads/2018/06/delhaize-maxi-logo-vector.png" /><b> {{ article.maxiCena.substring(0, article.maxiCena.length - 3) }}</b></p>
                        <p v-else align="right"><img style="height: 18px; width: 75px" src="https://upload.wikimedia.org/wikipedia/commons/2/2f/Idea_Logo.svg" /><b> {{ article.ideaCena.substring(0, article.ideaCena.length - 3) }}</b></p>
                        <hr>
                    </div>
                </div>
            </div>
        </div>
        <button @click="toTopFunction()" id="BtnToTop" title="Go to top">&uarr;</button>
        <div id="loader"></div><br><br>
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
<style>
    #BtnToTop {
        display: none;
        position: fixed;
        bottom: 40px;
        right: 30px;
        z-index: 99;
        font-size: 25px;
        border: none;
        outline: none;
        background-color: red;
        color: white;
        cursor: pointer;
        padding-left: 15px;
        padding-right: 15px;
        padding-top: 5px;
        padding-bottom: 5px;
        border-radius: 4px;
    }

    #BtnToTop:hover {
        background-color: #555;
    }

    #loader {
        display:none;
        position: fixed;
        left: 820px;
        bottom: 50px;
        z-index: 1;
        width: 50px;
        height: 50px;
        margin: -75px 0 0 -75px;
        border: 8px solid #f3f3f3;
        border-radius: 50%;
        border-top: 8px solid #3498db;
        -webkit-animation: spin 2s linear infinite;
        animation: spin 2s linear infinite;
    }

    /* Safari */
    @-webkit-keyframes spin {
        0% { -webkit-transform: rotate(0deg); }
        100% { -webkit-transform: rotate(360deg); }
    }

    @keyframes spin {
        0% { transform: rotate(0deg); }
        100% { transform: rotate(360deg); }
    }
</style>
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
                category: '',
                products: []
            }
        },
        created() {
            //this.fetchArticles();
            this.fetchProducts();
            window.addEventListener('scroll', this.handleScroll);
        },
        methods: {
            toTopFunction() {
                document.body.scrollTop = 0;
                document.documentElement.scrollTop = 0;
            },
            handleScroll(){
                let scroll = Math.ceil($(window).scrollTop() + $(window).height());
                let windowHeight = Math.round($(document).height());

                if(scroll == windowHeight) {
                    //if(this.pagination.nextPage <= this.pagination.lastPage) {
                        document.getElementById("loader").style.display = "block";
                        this.endSlice += 12;
                    //}
                }else {
                    document.getElementById("loader").style.display = "none";
                }

                if (document.body.scrollTop > 20 || document.documentElement.scrollTop > 20) {
                    document.getElementById("BtnToTop").style.display = "block";
                } else {
                    document.getElementById("BtnToTop").style.display = "none";
                }
            },
            hideLoader(){
                document.getElementById("loader").style.display = "none";
            },
            dinamicUrl(currentPage){
                let url;

                if(this.category === 'maxi'){
                    if(currentPage == 0) {
                        url = 'https://cors-anywhere.herokuapp.com/https://www.maxi.rs/view/QlProductListComponentController/getSearchPageData?componentId=PromotionListingProductListingComponent&pageNumber=' + currentPage + '&sort=promotionType';
                    }else{
                        url = 'https://cors-anywhere.herokuapp.com/https://www.maxi.rs/view/QlProductListComponentController/loadMore?componentId=PromotionListingProductListingComponent&pageNumber=' + currentPage + '&sort=promotionType';
                    }
                }
                else if(this.category === 'idea'){
                    url = 'https://cors-anywhere.herokuapp.com/https://www.idea.rs/online/v2/offers?per_page=48&page=' + currentPage + '&filter%5Bsort%5D=offerSoldStatisticsDesc';
                }
                /*else if(this.category === 'meso'){
                    if(currentPage == 0) {
                         url = 'https://www.maxi.rs/online/Meso%2C-mesne-i-riblje-prera%C4%91evine/c/02/getSearchPageData?pageSize=12&pageNumber=' + currentPage + '&sort=promotion';
                    }else{
                         url = 'https://www.maxi.rs/online/Meso,-mesne-i-riblje-prera%C4%91evine/c/02/loadMore?pageSize=12&pageNumber=' + currentPage + '&sort=promotion';
                    }
                }
                else if(this.category === 'slatkisi') {
                    if (currentPage == 0) {
                        url = 'https://www.maxi.rs/online/Cokolade%2C-keks%2C-slane-i-slatke-grickalice/c/09/getSearchPageData?pageSize=12';
                    } else {
                        url = 'https://www.maxi.rs/online/Cokolade%2C-keks%2C-slane-i-slatke-grickalice/c/09/loadMore?pageSize=12&pageNumber=' + currentPage + '&sort=popularity';
                    }
                }
                else if(this.category === 'pice'){
                    if(currentPage == 0) {
                        url = 'https://www.maxi.rs/online/Pice%2C-kafa-i-caj/c/01/getSearchPageData?pageSize=12&pageNumber=' + currentPage + '&sort=promotion';
                    }else{
                        url = 'https://www.maxi.rs/online/Pice,-kafa-i-caj/c/01/loadMore?pageSize=12&pageNumber=' + currentPage + '&sort=promotion';
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
            fetchProducts(){
                fetch('api/articles')
                    .then(res => res.json())
                    .then(res => {
                        this.akcija = JSON.parse(res.data);
                    })
            },
            fetchArticles(currentPage,category) {
                let vm = this;
                let url;

                this.category = category;
                if(currentPage == 0){
                    this.articles = [];
                }
                currentPage = currentPage || '0';
                url = this.dinamicUrl(currentPage);
                fetch(url)
                    .then(res => res.json())
                    .then(res => {
                        if(currentPage == 0) {
                            if(this.category === 'idea'){
                                //this.articles = res.products;
                                this.idea = res.products;
                                vm.makePagination(res._page, currentPage);
                            }else {
                                //this.articles = res.results;
                                this.maxi = res.results;
                                vm.makePagination(res.pagination, currentPage);
                            }
                        }else{
                            if(this.category === 'idea'){
                                //this.articles = this.articles.concat(res.products);
                                this.idea = this.idea.concat(res.products);
                                vm.makePagination(this.pagination,currentPage);
                            }else{
                                //this.articles = this.articles.concat(res);
                                this.maxi = this.maxi.concat(res);
                                vm.makePagination(this.pagination,currentPage);
                            }
                        }
                    })
                    .catch(err => console.log(err));
            },
            makePagination(paginate,currentPage) {
                let pagination;
                if(currentPage == 0) {
                    if(this.category === 'idea'){
                        pagination = {
                            totalArticles: paginate.item_count,
                            currentPage: paginate.current,
                            lastPage: paginate.page_count,
                            nextPage: paginate.current + 1,
                            prevPage: paginate.current - 1
                        };
                    }else{
                        pagination = {
                            totalArticles: paginate.totalNumberOfResults,
                            currentPage: paginate.currentPage,
                            lastPage: paginate.numberOfPages,
                            nextPage: paginate.currentPage + 1,
                            prevPage: paginate.currentPage - 1
                        };
                    }

                    if(this.category === 'idea'){
                        for(let i=2; i<=pagination.lastPage; i++){
                            this.fetchArticles(i,this.category);
                        }
                    }else{
                        for(let i=1; i<=60; i++){
                            this.fetchArticles(i,this.category);
                        }
                    }
                }else{
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
            storeArticles(shop,category){
                let vm = this;
                if(shop == 'maxi') {
                    vm.storeVisit(this.maxi,shop,category);
                }else if(shop == 'idea'){
                    vm.storeVisit(this.idea,shop,category);
                }
            },
            storeVisit(article,shop,category)
            {
                this.products = [];
                this.products.push(article);
                axios({
                    method: 'post',
                    url: '/api/article',
                    data: {
                        products: this.products,
                        shop: shop,
                        category: category
                    }
                });
            }
        }
    }
</script>