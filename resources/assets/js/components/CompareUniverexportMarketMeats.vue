<template>
    <div align="center" class="container">
        <h4 align="left">Total products: {{products.length}}</h4><br>
        <div class="row">
            <div class="col-sm-6" v-for="(article, index) in products" v-bind:key="index">
                <div class="card">
                    <div class="card-body">
                        <p>{{ article.univerexport.name}}</p>
                        <p>{{ article.univerexport.id}}</p>
                        <hr>
                        <form @submit.prevent="addUniverexportArticle(index,article.univerexport.id,article.univerexport.name,
                        article.univerexport.price_new,article.univerexport.price_old, article.univerexport.image_url,article.univerexport.manufacturer,
                        article.univerexport.price_measure,article.univerexport.price_reference)"
                              method="post">
                            <div v-for="baza in article.meats">
                                <input type="radio" v-model="articles.barcodes" :value="baza.barcodes"> {{baza.body}}
                                <!--<input type="text" v-model="articles.supplementary"/>-->
                                <br>
                            </div>
                            <hr>
                            <button type="submit" class="btn btn-info btn-block">Submit</button>
                        </form>
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
                products: [],
                // hidden: '',
                articles: {
                    barcodes: '',
                    // supplementary: ''
                }
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
                if (this.products.length > 0) {
                    let scroll = Math.ceil($(window).scrollTop() + $(window).height());
                    let windowHeight = Math.round($(document).height());

                    if (scroll == windowHeight) {
                        //if(this.pagination.nextPage <= this.pagination.lastPage) {
                        document.getElementById("loader").style.display = "block";
                        //}
                    } else {
                        document.getElementById("loader").style.display = "none";
                    }

                    if (this.products.length < this.endSlice) {
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
                fetch('api/compare_univerexport_market_meats')
                    .then(res => res.json())
                    .then(res => {
                        this.products = res;
                        $('body').addClass('loaded');
                    })
            },
            addUniverexportArticle(index, code, body, newPrice, oldPrice, imageUrl, title, price_measure, price_reference) {
                this.articles.code = code;
                this.articles.title = title;
                this.articles.body = body;
                this.articles.newPrice = newPrice;
                this.articles.oldPrice = oldPrice;
                this.articles.category = 'meso';
                this.articles.shop = 'univerexport';
                this.articles.imageUrl = imageUrl;
                this.articles.supplementaryPriceLabel1 = price_reference + ' ' + price_measure;

                // console.log(this.articles); throw '';

                if (this.articles.barcodes == '') {
                    return alert('Please select one of radio buttons');
                }

                fetch('api/storeUniverexportArticles', {
                    method: 'post',
                    body: JSON.stringify(this.articles),
                    headers: {
                        'content-type': 'application/json'
                    }
                })
                    .then(data => {
                        this.articles.barcodes = '';
                        // this.hidden = 'display: none;';
                        this.products.splice(index,1);
                        //alert('Article Added');
                    });
            }
        }
    }
</script>