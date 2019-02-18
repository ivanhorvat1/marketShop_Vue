<template>
    <div align="center" class="container">
        <div class="row">
            <div class="col-sm-3" v-for="article in freeze.slice(startSlice,endSlice)" v-bind:key="article.code">
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
        display: none;
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
        0% {
            -webkit-transform: rotate(0deg);
        }
        100% {
            -webkit-transform: rotate(360deg);
        }
    }

    @keyframes spin {
        0% {
            transform: rotate(0deg);
        }
        100% {
            transform: rotate(360deg);
        }
    }
</style>
<script>
    export default {
        data() {
            return {
                startSlice: 0,
                endSlice: 12,
                freeze: []
            }
        },
        created() {
            this.fetchFreezeProducts();
            window.addEventListener('scroll', this.handleScroll);
        },
        methods: {
            toTopFunction() {
                document.body.scrollTop = 0;
                document.documentElement.scrollTop = 0;
            },
            handleScroll() {
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

                if (document.body.scrollTop > 20 || document.documentElement.scrollTop > 20) {
                    document.getElementById("BtnToTop").style.display = "block";
                } else {
                    document.getElementById("BtnToTop").style.display = "none";
                }
            },
            fetchFreezeProducts() {
                fetch('api/action_freeze_fetch')
                    .then(res => res.json())
                    .then(res => {
                        this.freeze = JSON.parse(res.data);
                    })
            },
        }
    }
</script>