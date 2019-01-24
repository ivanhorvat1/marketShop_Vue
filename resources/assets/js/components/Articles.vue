<template>
    <div>
        <h2>Products</h2>
        <form @submit.prevent="addArticle" class="mb-3">
            <div class="form-group">
                <input type="text" class="form-control" placeholder="Name" v-model="article.title">
                <textarea class="form-control" placeholder="Description" v-model="article.body"></textarea>
                <button type="submit" class="btn btn-light btn-block">Save</button>
            </div>
        </form>
        <button @click="fetchArticles(0,'maxi')" class="btn btn-primary">Maxi Akcija</button>
        <button @click="storeArticles('maxi')" class="btn btn-primary">Ubaci Maxi</button>
        <!--<button @click="fetchArticles(0,'pice')" class="btn btn-primary">Pice</button>
        <button @click="fetchArticles(0,'meso')" class="btn btn-primary">Mesnati Proizvodi</button>
        <button @click="fetchArticles(0,'slatkisi')" class="btn btn-primary">Čokolade, keks, slane i slatke grickalice</button>-->
        <button @click="fetchArticles(0,'idea')" class="btn btn-primary">Idea Akcija</button>
        <button @click="storeArticles('idea')" class="btn btn-primary">Ubaci Idea</button><br><br>
        <h6>Total products: {{pagination.totalArticles}}</h6><br>
        <div class="row">
            <div class="col-sm-3" v-for="article in maxi.slice(startSlice,endSlice)" v-bind:key="article.code">
                <div class="card">
                    <div v-if="!article.price.formattedValue" class="card-header">
                        <span style="text-decoration: line-through; background-color:red; color:white;">{{article.offer.original_price.formatted_price}}</span>
                    </div>
                    <!-- article.price.formattedValue -->
                    <div class="card-body">
                        <img center v-if="article.images[2].url" class="center" :src="'https://d3el976p2k4mvu.cloudfront.net'+article.images[2].url" width="180px" height="180px">
                        <img center v-else-if="article.images[0].image_n" class="center" :src="'https://www.idea.rs/online/'+article.images[0].image_n" width="180px" height="180px">
                        <img center v-else :src="'https://d3el976p2k4mvu.cloudfront.net/_ui/responsive/common/images/product-details/product-no-image.svg?buildNumber=97d8e0570565bc1fcf193b453773e43360a2c694'">
                        <p align="center"><b>{{ article.manufacturer }}:</b> {{ article.name }}</p>
                        <!--<<p align="center" style="background-color:red; color:white" >{{article.discountFlag}}</p>
                        <p>{{ article.price.supplementaryPriceLabel1 }}</p>-->
                        <hr>
                        <p align="right" v-if="article.price.formattedValue">{{ article.price.supplementaryPriceLabel2 }}: <b>{{ article.price.formattedValue }}</b></p>
                        <p align="right" v-else><b>{{ article.price.formatted_price }}</b></p>
                        <!--<p align="right">{{ article.price.supplementaryPriceLabel2 }}: <b>{{ article.price.formattedValue }}</b></p>-->
                        <hr>
                        <button @click="editArticle(article)" class="btn btn-primary">Store</button>
                        <!--<button @click="deleteArticle(article.code)" class="btn btn-danger">Delete</button>-->
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
                category: ''
            }
        },
        created() {
            this.fetchArticles();
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
                    if(this.pagination.nextPage <= this.pagination.lastPage) {
                        document.getElementById("loader").style.display = "block";
                        //setTimeout(this.fetchArticles(this.pagination.nextPage, this.category), 5000);
                        this.endSlice += 12;
                    }
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
                }*/
                else{
                    if(currentPage == 0) {
                        url = 'https://cors-anywhere.herokuapp.com/https://www.maxi.rs/view/QlProductListComponentController/getSearchPageData?componentId=PromotionListingProductListingComponent&pageNumber=' + currentPage + '&sort=promotionType';
                    }else{
                        url = 'https://cors-anywhere.herokuapp.com/https://www.maxi.rs/view/QlProductListComponentController/loadMore?componentId=PromotionListingProductListingComponent&pageNumber=' + currentPage + '&sort=promotionType';
                    }
                }

                return url;
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
                        /*if(this.maxi.length > 0 && this.idea.length > 0){
                            this.getMatch(this.maxi,this.idea);
                        }*/
                    })
                    .catch(err => console.log(err));
            },
            getMatch(a, b){
                for ( var i = 0; i < a.length; i++ ) {
                    for ( var e = 0; e < b.length; e++ ) {
                        //if ( a[i] === b[e] ) this.maxi_idea.push( a[i] );
                    }
                }
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
                        for(let i=1; i<=pagination.lastPage; i++){
                            this.fetchArticles(i,this.category);
                        }
                    }else{
                        for(let i=1; i<=2; i++){
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
            deleteArticle(id){
                if(confirm('Are you sure?')){
                    fetch(`api/article/${id}`,{
                        method: 'delete',
                    })
                        .then(res => res.json())
                        .then(data => {
                            alert('Article Removed');
                            this.fetchArticles();
                        })
                        .catch(err => console.log(err));
                }
            },
            storeArticles(shop){
                if(shop == 'maxi') {
                    this.maxi.forEach(function (element) {
                        let imageUrl;
                        if (element.images[2]) {
                            imageUrl = element.images[2].url;
                        } else {
                            imageUrl = "https://d3el976p2k4mvu.cloudfront.net/_ui/responsive/common/images/product-details/product-no-image.svg?buildNumber=97d8e0570565bc1fcf193b453773e43360a2c694";
                        }

                        let articleAdd = {
                            title: element.manufacturerName,
                            body: element.name,
                            barcodes: element.eanCodes,
                            imageUrl: imageUrl,
                            formattedPrice: element.price.formattedValue,
                            supplementaryPriceLabel1: element.price.supplementaryPriceLabel1,
                            supplementaryPriceLabel2: element.price.supplementaryPriceLabel2
                        };

                        vm.addArticle(articleAdd);
                    });
                }
            },
            addArticle(article){
                if(this.edit === false){
                    //add
                    fetch('api/article',{
                        method: 'post',
                        body: JSON.stringify(article),
                        headers: {
                            'content-type': 'application/json'
                        }
                    })
                    .then(res => res.json())
                    /*.then(data => {
                        this.article.name = '';
                        this.article.description = '';
                        alert('Article Added');
                        //this.fetchArticles();
                    })*/
                    .catch(err => console.log(err));
                }else{
                    //update
                    fetch('api/article',{
                        method: 'put',
                        body: JSON.stringify(this.article),
                        headers: {
                            'content-type': 'application/json'
                        }
                    })
                        .then(res => res.json())
                        .then(data => {
                            this.article.name = '';
                            this.article.description = '';
                            alert('Article Updated');
                            this.fetchArticles();
                        })
                        .catch(err => console.log(err));
                }
            },
            editArticle(article){
                //this.edit = true;
                //this.article.article_id = article.code;
                //this.article.code = article.code;
                this.article.title = article.manufacturer;
                this.article.body = article.name;
            }
        }
    }
</script>