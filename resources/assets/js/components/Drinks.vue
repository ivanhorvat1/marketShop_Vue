<template>
    <section>
        <div id="left_side" class="d-none d-xl-block">
            <div class="col-md-1 mb-2">
                <img style="background-size:cover; display: flex;" src="images/vertical_baner.png">
            </div>
            <!--<div class="col-md-1">
                <img style="background-size:cover; display: flex;" src="images/vertical_baner.png">
            </div>-->
        </div>
        <div id="right_side">
            <div align="center">
                <div class="row">
                    <div class="col-sm-8"></div>
                    <div class="form-group has-search col-sm-4">
                        <div class="search">
                            <div>
                                <input v-model="search" type="text"
                                       placeholder="Unesite proizvod ili marku (pivo,coca-cola,..)"
                                       required>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-sm-1"></div>
                    <div @click="fetchArticles('maxi')" class="buttonCustom1 col-lg-2">Maxi Pice</div>
                    <div @click="fetchArticles('idea')" class="buttonCustom1 col-lg-2">Idea Pice</div>
                    <div @click="fetchArticles('dis')" class="buttonCustom1 col-lg-2">Dis Pice</div>
                    <div @click="fetchArticles('univerexport')" class="buttonCustom1 col-lg-2">Univerexport Pice</div>
                </div>

                <div class="row">
                    <div class="col-md-3"></div>
                    <div class="col-md-3 mb-3">
                        <div class="select">
                            <select v-model="selected" @change="compareDynamically">
                                <option :value="null" disabled>Uporedi markete</option>
                                <option value="DrinksMvI">Uporedi Maxi/Idea</option>
                                <option value="DrinksAll">Uporedi sve markete</option>
                            </select>
                        </div>
                    </div>

                    <div v-if="articles.length > 0" class="form-group col-sm-4">
                        <!--<label for="sel1">Sortiranje</label>-->
                        <div class="select">
                            <select id="sel1" @change="fetchArticles(shop)" v-model="key">
                                <option :selected="key == 'opadajuce'" value="opadajuce">Sortiranje po opadajucim cenama
                                </option>
                                <option value="rastuce">Sortiranje po rastucim Cenama</option>
                            </select>
                        </div>
                    </div>
                </div>

                <h4 v-if="products.length > 0" align="left">Ukupan broj uporedjenih artikala:
                    {{filteredProducts.length}}</h4>
                <h4 v-else align="left">Ukupan broj artikala {{shop}}: {{filteredProducts.length}}</h4><br>

                <div class="row">
                    <div class="wrap">
                        <div class="box one" v-if="products.length > 0"
                             v-for="article in filteredProducts.slice(startSlice,endSlice)" v-bind:key="article.code"
                             v-bind:style="[{ 'background-image': 'url(' + article.imageUrl + ')' },styles]"
                             @click="info(article,$event.target)"
                             style="cursor: pointer; height: 550px;"
                             v-b-tooltip.hover.html="'<h6>'+article.body+'</h6>'">
                            <p class="textOverflow" align="center">{{ article.body }}</p>
                            <div class="poster p1">
                                <h4 v-if="article.maxiCena">
                                    <img style="height: 50px; width: 80px"
                                         src="images/market_logo/delhaize-maxi-logo-vector.png"/>
                                    <span><b>{{ article.maxiCena.substring(0, article.maxiCena.length - 3) }}</b></span>
                                </h4>
                                <h4 v-if="article.ideaCena">
                                    <img style="height: 50px; width: 80px"
                                         src="images/market_logo/Idea_Logo_resized.png"/>
                                    <span><b>{{ article.ideaCena.substring(0, article.ideaCena.length - 3) }}</b></span>
                                </h4>
                                <h4 v-if="article.disCena">
                                    <img style="height: 50px; width: 80px"
                                         src="images/market_logo/dis_krnjevo_resized.gif"/>
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
                            <p class="textOverflow" align="center">{{ articlea.body }}</p>
                            <div style="margin-top: 50px">
                                <img center v-if="articlea.imageUrl !== null && articlea.shop == 'maxi'" class="center"
                                     :src="'https://d3el976p2k4mvu.cloudfront.net'+articlea.imageUrl" width="180px">
                                <img center v-else-if="articlea.imageUrl !== null && articlea.shop == 'idea'"
                                     class="center"
                                     :src="'https://www.idea.rs/online/'+articlea.imageUrl" width="180px"
                                     height="180px">
                                <img center v-else-if="articlea.imageUrl !== null && articlea.shop == 'dis'"
                                     class="center"
                                     :src="'https://www.idea.rs/online/'+articlea.imageUrl" width="180px"
                                     height="180px">
                                <img center v-else-if="articlea.imageUrl !== null && articlea.shop == 'univerexport'"
                                     class="center"
                                     :src="articlea.imageUrl" width="90px" height="180px">
                                <img v-else center style="height: 180px; width: 180px;" :src=articlea.imageDefault>
                            </div>
                            <div class="poster p1" style="margin-top: 50px">
                                <h5 v-if="articlea.shop == 'maxi'">
                                    <img style="height: 50px; width: 80px"
                                         src="images/market_logo/delhaize-maxi-logo-vector.png"/>
                                    <b>{{articlea.formattedPrice }}</b>
                                </h5>
                                <h5 v-if="articlea.shop == 'idea'">
                                    <img style="height: 50px; width: 80px"
                                         src="images/market_logo/Idea_Logo_resized.png"/>
                                    <b>{{articlea.formattedPrice }}</b>
                                </h5>
                                <h5 v-if="articlea.shop == 'dis'">
                                    <img style="height: 50px; width: 80px"
                                         src="images/market_logo/dis_krnjevo_resized.gif"/>
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
                                 :src="imageUrl"/>
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
                    <!--<div class="spinnerr"></div>
                    <br/>
                    <h5>Loading...</h5>-->
                    <svg viewBox="0 0 100 100">
                        <path
                                class="pig"
                                d="m 49,81 c -4.97949,0.0288 -8.87182,-1.33563 -12.96403,-4.78789 -1.37863,4.03232 -1.98391,5.09242 -2.5173,7.67551 -1.7034,-1.84098 -1.85041,-2.38138 -3.40931,-4.01426 -1.66859,1.1093 -2.97808,2.47785 -3.73546,3.41895 -0.3552,-2.5689 -0.1426,-3.49976 -1.3115,-8.15934 -1.1825,-4.71376 -4.7989,-11.7127 -4.86568,-20.02735 -0.0386,-4.80812 2.45175,-8.918 3.87545,-11.06273 -2.56161,-0.82848 -3.66901,-2.05201 -4.32566,-3.72552 -1.38384,-3.52678 0.76087,-6.22823 2.88932,-5.55409 2.80181,0.88742 2.09867,3.7385 -0.93073,5.11902 -4.28599,1.95316 -6.12353,-0.052 -7.239,-1.3961 1.11484,1.3981 2.94928,3.34732 7.1873,1.34439 3.12689,-1.4778 3.77421,-4.17385 0.93073,-5.06731 -2.07984,-0.65352 -4.10537,2.03309 -2.80106,5.53267 0.64344,1.72641 1.72064,2.95171 4.2891,3.7835 3.5922,-4.8354 10.26924,-9.22914 21.7499,-11.29879 -1.46246,-2.03309 -2.43102,-3.39383 -2.3357,-5.74316 0.21875,-5.39103 3.59202,-7.97334 8.7965,-8.65912 5.65823,-0.74557 9.43731,3.64106 9.50417,7.02698 0.0731,3.70345 -1.3446,5.02967 -2.99274,7.33247 3.54373,0.58436 5.49027,1.12272 8.15459,1.87033 2.33525,-2.21395 5.54998,-6.14061 7.32208,-4.80249 2.29444,1.73253 1.38678,5.35445 0.96881,8.15092 2.61672,1.04146 5.01114,3.17616 6.80761,5.54521 1.79647,2.36905 1.2415,6.43267 3.77462,8.6351 1.47532,1.28272 2.93396,-0.18609 4.96389,0.62048 0.60399,0.58719 -0.95559,8.65166 -2.58536,10.39315 -1.43313,1.53137 -2.41395,0.58126 -3.36097,1.29268 -0.72765,1.00392 -0.65377,1.18862 -2.37593,2.64593 -3.13223,2.65054 -4.87112,2.82698 -7.98325,5.42039 -2.70413,2.25341 -2.78375,6.0969 -3.29151,11.01363 -1.9337,-1.7742 -2.2328,-2.24397 -3.77463,-3.5678 -1.50527,1.60119 -1.90499,1.93078 -2.99901,3.05073 -0.65527,-2.5363 -0.84208,-4.12175 -2.01658,-6.70437 -3.54762,2.27769 -5.52832,3.0772 -7.98223,3.69783 -2.23563,0.56543 -2.40172,0.98302 -5.41643,1.00045"
                                style="fill:none;fill-rule:evenodd;stroke:#0e232e;stroke-width:1.8;stroke-linecap:butt;stroke-linejoin:round;stroke-miterlimit:4;"/>
                        <g class="pig-inside">
                            <path
                                    d="m 77.46679,49.84793 c 0,0 -0.55878,-2.506 -2.24475,-2.5198 -1.68597,-0.0138 -2.27512,2.43024 -2.27512,2.43024"
                                    style="fill:none;fill-rule:evenodd;stroke:#0e232e;stroke-width:1.8;stroke-linecap:round;stroke-linejoin:miter;stroke-miterlimit:4;"/>
                            <path
                                    d="m 64.20841,40.34766 c 0.14612,-2.86924 -6.96545,-2.63546 -11.68582,-2.8956 -3.99042,-0.21991 -11.84046,-0.0114 -11.68583,2.79219 0.10861,1.96931 4.46343,0.72553 6.4117,0.56877 1.74768,-0.14062 3.47128,-0.25468 5.37753,-0.20683 1.90625,0.0479 4.62153,0.22331 6.56682,0.51708 1.92467,0.29066 4.93255,0.85518 5.0156,-0.77561 z"
                                    style="fill:none;fill-rule:evenodd;stroke:#0e232e;stroke-width:1.8;stroke-linecap:butt;stroke-linejoin:miter;stroke-miterlimit:4;"/>
                            <path
                                    id="path1382"
                                    d="m 25.95022,60.67043 c 0,0.0231 -0.80306,-2.92201 -0.62156,-5.63063 0.1815,-2.70862 1.82813,-5.59406 1.82813,-5.59406"
                                    style="fill:none;fill-rule:evenodd;stroke:#0e232e;stroke-width:1.8;stroke-linecap:round;stroke-linejoin:miter;stroke-miterlimit:4;"/>
                            <path
                                    id="path1384"
                                    d="m 29.21343,46.20996 0.0823,-0.0914"
                                    style="fill:none;fill-rule:evenodd;stroke:#0e232e;stroke-width:1.8;stroke-linecap:round;stroke-linejoin:miter;stroke-miterlimit:4;"/>
                            <path
                                    id="path1386"
                                    d="m 45.65754,32.52472 c 0,0 2.53269,2.42261 6.26597,2.46941 3.73329,0.0468 6.99876,-2.56708 6.99876,-2.56708"
                                    style="fill:none;fill-rule:evenodd;stroke:#0e232e;stroke-width:1.8;stroke-linecap:round;stroke-linejoin:miter;stroke-miterlimit:4;"/>
                            <path
                                    id="path1388"
                                    d="m 47.30272,26.77699 c 0.0452,-2.83115 2.67919,-4.8801 5.48438,-4.89937 2.65889,-0.0183 5.29285,1.68757 5.11875,4.3875 -0.19085,2.95966 -2.46255,4.88803 -5.15531,5.00906 -3.42581,0.15398 -5.48717,-2.03157 -5.44782,-4.49719 z"
                                    style="fill:none;fill-rule:evenodd;stroke:#0e232e;stroke-width:1.8;stroke-linecap:butt;stroke-linejoin:miter;stroke-miterlimit:4;"/>
                        </g>
                        <path
                                class="calculator"
                                style="fill:none;fill-rule:evenodd;stroke:#0e232e;stroke-width:1.8;stroke-linecap:butt;stroke-linejoin:miter;stroke-miterlimit:4;"
                                d="m 49,81 c -5.36249,-0.0991 -10.158823,-0.397154 -16.391123,-0.105624 -13.36698,0.62533 -14.7761,-2.5935 -15.3636,-9.24037 -0.12286,-1.38997 0.0274,-1.82983 -0.17286,-6.98451 -0.18004,-4.63433 -0.91747,-13.8623 -1.01488,-20.59616 -0.0974,-6.73386 -0.22911,-13.12783 0.585,-20.23421 0.71122,-6.20825 1.898,-8.80142 8.84293,-9.34225 3.16904,-0.24679 2.84265,-0.0705 6.12627,0.0502 3.28362,0.12066 10.45385,-0.35958 13.3161,-0.34268 2.86225,0.0169 2.23833,-0.0321 4.31223,0.034 2.0739,0.0661 3.98575,-0.4925 8.37756,-0.44762 4.39181,0.0449 11.53012,0.68765 16.965,0.77409 7.13142,0.11341 7.43838,1.67632 8.46476,7.94033 0.31163,1.90189 0.27364,1.85106 0.2674,6.80582 -0.004,3.14692 -0.51837,7.16816 -0.53329,12.26726 -0.0149,5.0991 0.59236,12.91401 0.57613,17.98875 -0.0162,5.07474 -0.12549,7.86774 -0.48159,12.68979 -0.59396,8.04329 -3.63307,7.50216 -5.95341,8.07771 -3.6146,0.89658 -7.77229,0.64536 -12.13875,0.73125 -5.477776,0.05888 -10.983592,0.02086 -15.783877,-0.06578"/>
                        <g>
                            <path
                                    style="fill:none;fill-rule:evenodd;stroke:#0e232e;stroke-width:1.8;stroke-linecap:butt;stroke-linejoin:miter;stroke-miterlimit:4;"
                                    d="m 25.567967,75.685996 c -2.42904,-0.13943 -3.40236,-1.83355 -3.36097,-3.2317 0.10012,-3.38113 -0.0705,-6.3494 0.31025,-8.42827 0.48236,-2.63342 1.08755,-3.04941 3.30926,-3.33511 1.9582,-0.25182 5.95106,-0.25822 8.06632,-0.15512 1.80447,0.088 3.60552,1.97904 3.56779,4.18828 -0.0365,2.13537 -0.26052,4.38767 -0.18872,5.68684 0.0718,1.29917 0.15696,1.34729 0.15436,2.39247 -0.003,1.07133 -0.78278,2.59918 -2.4168,2.68269 -1.40492,0.0718 -3.57965,0.17845 -5.17934,0.0953 -1.59969,-0.0831 -2.76077,0.1908 -4.26215,0.10462 z"/>
                            <path
                                    style="fill:none;fill-rule:evenodd;stroke:#0e232e;stroke-width:1.8;stroke-linecap:round;stroke-linejoin:miter;stroke-miterlimit:4;"
                                    d="m 24.693637,56.470656 c -1.23106,-0.14597 -2.36814,-1.01627 -2.46851,-2.75366 -0.087,-1.50516 0.12451,-3.80232 0.14625,-5.41125 0.0217,-1.60893 -0.0354,-2.81665 0.15512,-4.24125 0.20171,-1.5081 1.2532,-2.45828 3.54397,-2.60589 1.51096,-0.0974 2.95016,-0.0793 4.2235,-0.0783 1.27335,9.7e-4 2.45118,-0.24889 3.69174,-0.13738 1.28937,0.11591 3.12042,1.22152 3.2943,2.82159 0.15874,1.46073 -0.0428,3.3608 0.009,4.9725 0.0516,1.6117 0.19735,3.56506 0.0768,4.93854 -0.12885,1.46806 -1.08801,2.11527 -2.81271,2.36509 -1.6493,0.2389 -3.62065,0.0372 -5.22952,-0.034 -1.60886,-0.0711 -3.47365,0.30108 -4.62981,0.164 z"/>
                            <path
                                    style="fill:none;fill-rule:evenodd;stroke:#0e232e;stroke-width:1.8;stroke-linecap:butt;stroke-linejoin:miter;stroke-miterlimit:4;"
                                    d="m 45.645497,75.705576 c -2.95594,0.0975 -3.45169,-1.97336 -3.47952,-3.6989 -0.0228,-1.41559 0.20682,-2.34204 0.20682,-3.51609 0,-1.17405 -0.16472,-2.05707 -0.0971,-3.55264 0.10249,-2.268 0.67446,-3.56915 3.19956,-4.30685 1.73449,-0.50672 3.19652,0.0109 4.71943,-0.0857 1.5229,-0.0966 3.80153,-0.1512 5.18541,0.40327 1.38389,0.55447 2.01455,1.81765 2.07869,3.40532 0.0647,1.60269 -0.22852,2.88537 -0.19429,4.3434 0.0342,1.45803 0.6882,3.21295 0.25487,4.56278 -0.9984,3.11001 -1.68127,2.33124 -3.56413,2.4694 -1.3426,0.0985 -2.62468,-0.0175 -4.00395,-0.0606 -1.37926,-0.0431 -2.6506,-0.018 -4.30576,0.0366 z"/>
                            <path
                                    style="fill:none;fill-rule:evenodd;stroke:#0e232e;stroke-width:1.8;stroke-linecap:butt;stroke-linejoin:miter;stroke-miterlimit:4;"
                                    d="m 45.113247,56.276376 c -1.55904,-0.21353 -2.5947,-0.47161 -2.73515,-2.62731 -0.0794,-1.2192 -0.16888,-2.37997 -0.0997,-3.80769 0.0691,-1.42772 -0.12205,-3.05605 0.0562,-5.265 0.18127,-2.24686 1.99109,-3.00663 3.48415,-3.20863 1.4944,-0.20218 2.78959,-0.0537 4.16813,-0.0177 1.37853,0.036 2.72525,-0.12836 4.12085,0.082 2.09881,0.31638 3.01127,1.3628 3.19532,3.02398 0.20521,1.85224 -0.002,3.76213 -0.051,5.31227 -0.0492,1.55014 0.15608,2.72789 0.0731,3.94875 -0.15081,2.21882 -1.15558,2.59707 -2.55938,2.69676 -1.4536,0.10322 -2.99794,-0.0143 -4.60687,-0.0643 -1.60893,-0.05 -3.34131,0.16027 -5.04563,-0.0731 z"/>
                            <path
                                    style="fill:none;fill-rule:evenodd;stroke:#0e232e;stroke-width:1.8;stroke-linecap:butt;stroke-linejoin:miter;stroke-miterlimit:4;"
                                    d="m 65.123957,75.626186 c -1.38595,-0.27786 -2.98238,-0.0673 -3.20584,-3.30926 -0.22346,-3.242 0.24552,-11.3927 0.31024,-15.8224 0.0647,-4.4297 -0.11759,-8.23249 0.10342,-10.7551 0.22101,-2.52261 1.78746,-4.04561 3.41267,-4.18461 1.62521,-0.13899 3.51409,-0.0346 4.97017,-0.001 1.45608,0.0336 2.39146,-0.0532 3.71664,0.12851 1.32518,0.18168 2.80205,1.45386 2.76709,3.22986 -0.035,1.776 -0.10441,0.26814 -0.0355,4.24626 0.0689,3.97811 -0.06,15.53622 -0.14625,19.64246 -0.0863,4.10624 0.32496,1.83709 0.15145,4.23999 -0.17352,2.4029 -0.93136,2.68195 -3.04706,2.71906 -1.93294,0.0339 -2.94572,0.21019 -4.44681,0.17654 -1.50109,-0.0337 -3.16429,-0.0324 -4.55024,-0.31024 z"/>
                            <path
                                    style="fill:none;fill-rule:evenodd;stroke:#0e232e;stroke-width:1.8;stroke-linecap:butt;stroke-linejoin:miter;stroke-miterlimit:4;"
                                    d="m 26.320127,35.728246 c -3.03684,0.12742 -4.13946,-1.21939 -4.03442,-2.96784 0.0959,-1.5967 -0.064,-3.92949 0.0857,-5.36841 0.14888,-1.43175 -0.1065,-2.02241 0,-3.36375 0.15903,-2.00303 1.27568,-3.48396 2.64948,-3.44055 1.57209,0.0497 1.41595,-0.002 3.63927,0.0768 7.92144,0.28109 28.9105,-0.24327 44.02125,0.21937 2.85346,0.0874 4.12015,0.97927 4.3875,2.99813 0.23862,1.80188 0.13735,3.54878 0.12427,4.97276 -0.0131,1.42398 0.062,1.91711 -0.12427,3.36349 -0.20208,1.56934 -0.85138,3.4694 -4.78969,3.51 -8.66247,0.0893 -35.78219,-0.427 -45.95906,0 z"/>
                            <path
                                    style="fill:none;fill-rule:evenodd;stroke:#0e232e;stroke-width:1.8;stroke-linecap:round;stroke-linejoin:miter;stroke-miterlimit:4;"
                                    d="m 26.246997,71.486376 c 0,0 4.56944,-4.60347 5.77688,-5.5575 1.20744,-0.95403 1.02375,-0.65813 1.02375,-0.65813"/>
                            <path
                                    style="fill:none;fill-rule:evenodd;stroke:#0e232e;stroke-width:1.8;stroke-linecap:round;stroke-linejoin:miter;stroke-miterlimit:4;"
                                    d="m 26.466377,65.416996 c 0,0 0.10371,0.29932 0.95062,1.09688 0.84691,0.79756 3.54667,2.54633 4.3875,3.36375 0.84083,0.81742 0.95063,1.17 0.95063,1.17"/>
                            <path
                                    style="fill:none;fill-rule:evenodd;stroke:#0e232e;stroke-width:1.8;stroke-linecap:round;stroke-linejoin:miter;stroke-miterlimit:4;"
                                    d="m 25.735127,48.963866 c 0,0 2.1305,0.19733 3.8025,0.21938 1.672,0.0221 3.36375,-0.14625 3.36375,-0.14625"/>
                            <path
                                    style="fill:none;fill-rule:evenodd;stroke:#0e232e;stroke-width:1.8;stroke-linecap:round;stroke-linejoin:miter;stroke-miterlimit:4;"
                                    d="m 29.391377,45.563566 c 0,0 0.10764,0.49864 0.14625,1.42593 0.0386,0.92729 -0.20865,3.46092 -0.14625,4.31438 0.0624,0.85346 0.14625,1.13343 0.14625,1.13343"/>
                            <path
                                    style="fill:none;fill-rule:evenodd;stroke:#0e232e;stroke-width:1.8;stroke-linecap:round;stroke-linejoin:miter;stroke-miterlimit:4;"
                                    d="m 45.864337,68.630566 c 0,0 4.68665,-0.46291 5.96775,-0.44394 1.2811,0.019 1.968,0.20055 1.968,0.20055"/>
                            <path
                                    style="fill:none;fill-rule:evenodd;stroke:#0e232e;stroke-width:1.8;stroke-linecap:round;stroke-linejoin:miter;stroke-miterlimit:4;"
                                    d="m 49.890157,70.617046 h 0.0642"/>
                            <path
                                    d="m 49.765157,65.679546 h 0.0642"
                                    style="fill:none;fill-rule:evenodd;stroke:#0e232e;stroke-width:1.8;stroke-linecap:round;stroke-linejoin:miter;stroke-miterlimit:4;"/>
                            <path
                                    style="fill:none;fill-rule:evenodd;stroke:#0e232e;stroke-width:1.8;stroke-linecap:round;stroke-linejoin:miter;stroke-miterlimit:4;"
                                    d="m 46.390127,48.951376 c 0,0 1.0414,-0.0327 1.96874,-0.0469 0.92734,-0.0142 2.75877,0.10188 3.57813,0.10938 0.81936,0.007 1.25,-0.0625 1.25,-0.0625"/>
                            <path
                                    style="fill:none;fill-rule:evenodd;stroke:#0e232e;stroke-width:1.8;stroke-linecap:round;stroke-linejoin:miter;stroke-miterlimit:4;"
                                    d="m 66.299527,60.104306 c 0,0 2.59921,0.1669 3.71231,0.17677 1.1131,0.01 2.96101,-0.0884 2.96101,-0.0884"/>
                            <path
                                    style="fill:none;fill-rule:evenodd;stroke:#0e232e;stroke-width:1.8;stroke-linecap:round;stroke-linejoin:miter;stroke-miterlimit:4;"
                                    d="m 66.268287,57.611126 1.31287,0.0183 h 5.3475"/>
                            <path
                                    style="fill:none;fill-rule:evenodd;stroke:#0e232e;stroke-width:1.8;stroke-linecap:round;stroke-linejoin:miter;stroke-miterlimit:4;"
                                    d="m 25.508307,28.903216 c 0,0 -0.42577,-2.56773 0.0496,-3.52259 0.47532,-0.95486 2.24854,-1.69232 2.24854,-1.69232"/>
                            <path
                                    style="fill:none;fill-rule:evenodd;stroke:#0e232e;stroke-width:1.8;stroke-linecap:round;stroke-linejoin:miter;stroke-miterlimit:4;"
                                    d="m 30.339347,23.588086 h 0.0234"/>
                        </g>
                        <path
                                class="wallet"
                                d="m 49,81 c -4.119302,-0.133383 -7.114451,0.229393 -10.461024,0.236513 -3.346574,0.0071 -3.853134,0.717888 -10.684122,0.121158 -8.896148,-0.777134 -9.684309,-2.495817 -11.128959,-12.758497 -0.739191,-5.251153 -0.188781,-11.357532 -0.196435,-16.329076 -0.0077,-4.971544 0.142616,-4.268999 -0.534815,-15.501762 -0.677431,-11.232767 2.724601,-13.301977 10.031192,-13.133627 7.306591,0.16835 1.275353,0.0217 6.928763,0 5.65341,-0.0217 20.40657,-0.26322 26.99115,-0.20683 6.58458,0.0564 4.930203,0.67561 12.513138,0.31025 7.582935,-0.36536 9.134672,2.09561 9.307293,6.5151 0.172621,4.41949 -0.645835,4.05121 -0.503908,7.311335 3.651927,3.113879 1.830509,8.671976 1.90125,12.102187 4.604491,0.06646 4.663611,4.721185 4.219832,8.889884 -0.600323,5.639228 -0.354062,8.169757 -4.804832,7.892303 0.830451,11.81846 -1.363381,14.720503 -8.723541,15.097818 -7.36016,0.377315 -6.469716,0.427505 -10.599972,0.465365 -4.130256,0.03786 -10.135708,-0.878738 -14.25501,-1.012121"
                                style="fill:none;fill-rule:evenodd;stroke:#0e232e;stroke-width:1.8;stroke-linecap:butt;stroke-linejoin:miter;stroke-miterlimit:4;"/>
                        <g>
                            <path
                                    d="m 78.90793,36.248314 c 0.136772,-2.445285 0.354257,-4.404775 -2.150914,-4.447315 -12.41111,-0.21075 -24.566825,0.0739 -32.647713,0.034 -8.080888,-0.04 -11.970786,-0.12891 -15.627332,-0.16324 -3.656547,-0.0343 -4.902258,0.10758 -6.31904,-0.10341 -1.767686,-0.26325 -3.734807,-0.88581 -3.354878,-2.61995 0.316614,-1.44516 2.34135,-1.25497 4.451753,-1.40193 1.70421,-0.11866 1.718959,-0.19887 5.11875,-0.0731 3.399791,0.12575 9.103821,-0.0486 16.453125,0 7.349305,0.0486 22.263913,0.48059 27.641249,0.36562 2.685302,-0.34899 3.362797,-0.38618 4.581021,-0.20607 1.663644,0.24597 1.926535,2.85458 1.31625,4.56772 0.992638,1.01508 0.693457,1.73988 0.537729,4.047725 z"
                                    style="fill:none;fill-rule:evenodd;stroke:#0e232e;stroke-width:1.8;stroke-linecap:butt;stroke-linejoin:miter;stroke-miterlimit:4;"/>
                            <path
                                    d="m 17.200933,30.873719 c 0,0 0.07434,4.03 5.222426,4.13657 4.522694,0.0936 5.832619,-0.0831 11.530701,-0.15512 5.698083,-0.0721 10.14572,0.45794 13.598989,0.36196 6.041229,-0.16792 13.435475,-0.2661 20.217508,-0.15513 13.451079,0.22009 14.340805,2.844174 14.340805,2.844174"
                                    style="fill:none;fill-rule:evenodd;stroke:#0e232e;stroke-width:1.8;stroke-linecap:butt;stroke-linejoin:miter;stroke-miterlimit:4;"/>
                            <path
                                    d="m 16.605431,38.442064 1.60875,0.73125"
                                    style="fill:none;fill-rule:evenodd;stroke:#0e232e;stroke-width:1.8;stroke-linecap:round;stroke-linejoin:miter;stroke-miterlimit:4;"/>
                            <path
                                    d="m 22.202092,39.465814 2.291968,-0.219375"
                                    style="fill:none;fill-rule:evenodd;stroke:#0e232e;stroke-width:1.8;stroke-linecap:round;stroke-linejoin:miter;stroke-miterlimit:4;"/>
                            <path
                                    d="m 28.929592,39.465814 2.77084,-0.313773"
                                    style="fill:none;fill-rule:evenodd;stroke:#0e232e;stroke-width:1.8;stroke-linecap:round;stroke-linejoin:miter;stroke-miterlimit:4;"/>
                            <path
                                    d="m 35.691056,39.392689 2.184879,0.05171"
                                    style="fill:none;fill-rule:evenodd;stroke:#0e232e;stroke-width:1.8;stroke-linecap:round;stroke-linejoin:miter;stroke-miterlimit:4;"/>
                            <path
                                    d="m 42.711056,39.465814 h 2.48625"
                                    style="fill:none;fill-rule:evenodd;stroke:#0e232e;stroke-width:1.8;stroke-linecap:round;stroke-linejoin:miter;stroke-miterlimit:4;"/>
                            <path
                                    d="m 49.731056,39.444396 2.443414,0.14625"
                                    style="fill:none;fill-rule:evenodd;stroke:#0e232e;stroke-width:1.8;stroke-linecap:round;stroke-linejoin:miter;stroke-miterlimit:4;"/>
                            <path
                                    d="m 57.067171,39.405402 0.517072,-0.103414 1.551215,0.206828"
                                    style="fill:none;fill-rule:evenodd;stroke:#0e232e;stroke-width:1.8;stroke-linecap:round;stroke-linejoin:miter;stroke-miterlimit:4;"/>
                            <path
                                    d="m 63.478861,39.301988 0.723901,0.103414 2.481945,-0.103417"
                                    style="fill:none;fill-rule:evenodd;stroke:#0e232e;stroke-width:1.8;stroke-linecap:round;stroke-linejoin:miter;stroke-miterlimit:4;"/>
                            <path
                                    d="m 71.131524,39.301988 0.620487,-0.103415 2.01658,0.155122"
                                    style="fill:none;fill-rule:evenodd;stroke:#0e232e;stroke-width:1.8;stroke-linecap:round;stroke-linejoin:miter;stroke-miterlimit:4;"/>
                            <path
                                    d="m 77.491508,40.336131 0.568779,0.568779 0.672193,0.93073"
                                    style="fill:none;fill-rule:evenodd;stroke:#0e232e;stroke-width:1.8;stroke-linecap:round;stroke-linejoin:miter;stroke-miterlimit:4;"/>
                            <path
                                    d="m 78.887602,45.817093 0.155121,2.171701"
                                    style="fill:none;fill-rule:evenodd;stroke:#0e232e;stroke-width:1.8;stroke-linecap:round;stroke-linejoin:miter;stroke-miterlimit:4;"/>
                            <path
                                    d="m 83.533087,49.557064 c 0,0 -5.856818,0.283698 -9.609375,0.396604 -3.752557,0.112906 -8.155534,0.726474 -8.997049,8.06632 -0.841515,7.339846 5.930443,8.240479 9.152171,8.376563 3.221728,0.136084 9.033784,0.05239 9.033784,0.05239"
                                    style="fill:none;fill-rule:evenodd;stroke:#0e232e;stroke-width:1.8;stroke-linecap:round;stroke-linejoin:miter;stroke-miterlimit:4;"/>
                            <path
                                    d="m 72.47293,62.500189 c 2.4712,-0.140993 3.791824,-1.540065 4.24125,-4.277813 0.326567,-1.989331 -1.521923,-3.952497 -3.506481,-4.079254 -2.692693,-0.171986 -4.802746,2.420734 -4.720082,4.773942 0.07625,2.17063 2.277269,3.680577 3.985313,3.583125 z"
                                    style="fill:none;fill-rule:evenodd;stroke:#0e232e;stroke-width:1.8;stroke-linecap:butt;stroke-linejoin:miter;stroke-miterlimit:4;"/>
                            <path
                                    d="m 85.19668,58.917063 v -0.329062"
                                    style="fill:none;fill-rule:evenodd;stroke:#0e232e;stroke-width:1.8;stroke-linecap:round;stroke-linejoin:miter;stroke-miterlimit:4;"/>
                            <path
                                    d="m 85.452618,56.101751 c 0,0 -0.168059,-2.550352 -0.950625,-3.254063 -0.782566,-0.70371 -2.77875,-0.585 -2.77875,-0.585"
                                    style="fill:none;fill-rule:evenodd;stroke:#0e232e;stroke-width:1.8;stroke-linecap:round;stroke-linejoin:miter;stroke-miterlimit:4;"/>
                            <path
                                    d="m 51.345232,-69.280345 -0.103417,0.723901"
                                    style="fill:none;fill-rule:evenodd;stroke:#0e232e;stroke-width:1.8;stroke-linecap:round;stroke-linejoin:miter;stroke-miterlimit:4;"/>
                            <path
                                    d="m 79.559795,70.171176 -0.258536,1.551215"
                                    style="fill:none;fill-rule:evenodd;stroke:#0e232e;stroke-width:1.8;stroke-linecap:round;stroke-linejoin:miter;stroke-miterlimit:4;"/>
                            <path
                                    d="m 78.991016,75.135065 c 0,0 -0.307646,0.548749 -0.620486,0.775608 -0.31284,0.226859 -1.240972,0.465365 -1.240972,0.465365"
                                    style="fill:none;fill-rule:evenodd;stroke:#0e232e;stroke-width:1.8;stroke-linecap:round;stroke-linejoin:miter;stroke-miterlimit:4;"/>
                            <path
                                    d="m 74.33737,76.789695 -2.326823,0.36195"
                                    style="fill:none;fill-rule:evenodd;stroke:#0e232e;stroke-width:1.8;stroke-linecap:round;stroke-linejoin:miter;stroke-miterlimit:4;"/>
                            <path
                                    d="m 68.64958,77.048231 -2.895603,-0.05171"
                                    style="fill:none;fill-rule:evenodd;stroke:#0e232e;stroke-width:1.8;stroke-linecap:round;stroke-linejoin:miter;stroke-miterlimit:4;"/>
                            <path
                                    d="m 62.393011,76.996524 -1.240973,0.206829 -2.068287,-0.05171"
                                    style="fill:none;fill-rule:evenodd;stroke:#0e232e;stroke-width:1.8;stroke-linecap:round;stroke-linejoin:miter;stroke-miterlimit:4;"/>
                            <path
                                    d="m 55.61937,77.099938 -2.119995,0.05171"
                                    style="fill:none;fill-rule:evenodd;stroke:#0e232e;stroke-width:1.8;stroke-linecap:round;stroke-linejoin:miter;stroke-miterlimit:4;"/>
                            <path
                                    d="m 48.328657,76.841402 -1.861458,0.155122"
                                    style="fill:none;fill-rule:evenodd;stroke:#0e232e;stroke-width:1.8;stroke-linecap:round;stroke-linejoin:miter;stroke-miterlimit:4;"/>
                            <path
                                    d="m 41.606723,76.996524 -1.292679,0.05171 -1.344387,-0.05171"
                                    style="fill:none;fill-rule:evenodd;stroke:#0e232e;stroke-width:1.8;stroke-linecap:round;stroke-linejoin:miter;stroke-miterlimit:4;"/>
                            <path
                                    d="m 34.316011,77.099938 -3.30926,-0.155121"
                                    style="fill:none;fill-rule:evenodd;stroke:#0e232e;stroke-width:1.8;stroke-linecap:round;stroke-linejoin:miter;stroke-miterlimit:4;"/>
                            <path
                                    d="m 26.973591,77.151645 -1.706337,-0.103414"
                                    style="fill:none;fill-rule:evenodd;stroke:#0e232e;stroke-width:1.8;stroke-linecap:round;stroke-linejoin:miter;stroke-miterlimit:4;"/>
                            <path
                                    d="m 20.613607,76.065795 h -0.620486 l -1.344387,-0.723901"
                                    style="fill:none;fill-rule:evenodd;stroke:#0e232e;stroke-width:1.8;stroke-linecap:round;stroke-linejoin:miter;stroke-miterlimit:4;"/>
                        </g>
                    </svg>
                    <h4 class="loading">LOADING</h4>
                </div>
                <button class="load_more" @click="loadMore()" id="loadMoreButton">Load More</button>
                <!--<button @click="toTopFunction()" id="BtnToTop" title="Go to top">&uarr;</button>-->
                <div id="BtnToTop1" class="bg"></div>
                <button @click="toTopFunction()" id="BtnToTop" class="buttonToTop" target="_blank"><i
                        class="fa fa-chevron-up" aria-hidden="true"></i></button>
                <div id="loader"></div>
                <br><br>
            </div>
        </div>
    </section>
</template>
<script>
    /*$(document).ready(function () {
        var wrap = $("#one");
console.log(document.documentElement.scrollTop);
        if (document.body.scrollTop > 120 || document.documentElement.scrollTop > 120) {
            wrap.addClass("fix-one");
        } else {
            wrap.removeClass("fix-one");
        }
    });*/

    export default {
        data() {
            return {
                startSlice: 0,
                endSlice: '',
                onScrollSlice: '',
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
        mounted() {
            let recaptchaScript = document.createElement('script');
            recaptchaScript.setAttribute('src', '/js/loader.js');
            document.head.appendChild(recaptchaScript);
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
            var width = $(window).width();
            if(width > 1900){
                this.endSlice = 15;
                this.onScrollSlice = 15;
            }else{
                this.endSlice = 12;
                this.onScrollSlice = 12;
            }

            /*$(window).on('resize', function() {
                if ($(this).width() != width) {
                    width = $(this).width();
                    if(width > 1900){
                        this.endSlice = 15;
                    }else{
                        this.endSlice = 12;
                    }
                }
            });*/
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
            loadMore(){
                if (this.products.length > 0 || this.articles.length > 0) {
                    if (this.products.length < this.endSlice && this.articles.length < this.endSlice) {
                        document.getElementById("loadMoreButton").style.display = "none";
                        return;
                    }
                    this.endSlice += this.onScrollSlice;
                }
            },
            handleScroll() {
                if (this.products.length > 0 || this.articles.length > 0) {
                    let scroll = Math.ceil($(window).scrollTop() + $(window).height());
                    let windowHeight = Math.round($(document).height());
                    let width = $(window).width();
                    if(width > 1500) {
                        document.getElementById("loadMoreButton").style.display = "none";
                    }

                    if (document.documentElement.scrollTop> 100) {
                        $('#left_side').addClass("fix-one");
                    } else {
                        $('#left_side').removeClass("fix-one");
                    }

                    if (scroll >= windowHeight) {
                        //if(this.pagination.nextPage <= this.pagination.lastPage) {
                        document.getElementById("loader").style.display = "block";
                        this.endSlice += this.onScrollSlice;
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
                /*fetch('api/action_drink_fetch')
                    .then(res => res.json())*/
                axios.get('api/action_drink_fetch', {
                    timeout: 60 * 4 * 1000
                })
                    .then(res => {
                        this.endSlice = this.onScrollSlice;
                        this.articles = '';
                        this.products = _.orderBy(res.data, 'price', 'desc');
                        // $('#preloader-wrapper').css("display", "none");
                        $('body').addClass('loaded');
                        window.scrollTo(0, 0);

                        if($(window).width() < 1500) {
                            document.getElementById("loadMoreButton").style.display = "block";
                        }

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
                    timeout: 60 * 4 * 1000,
                    params: {
                        shop: shop,
                        sort: this.key
                    }
                }).then(res => {
                    this.endSlice = this.onScrollSlice;
                    this.products = '';
                    this.articles = res.data;
                    window.scrollTo(0, 0);

                    if($(window).width() < 1500) {
                        document.getElementById("loadMoreButton").style.display = "block";
                    }

                    $('#overlay').fadeOut();
                })
            }, compareDynamically() {
                let vm = this;
                // vm.createOverlay();
                $('#overlay').fadeIn();

                if (this.selected == 'DrinksAll') {
                    vm.fetchProducts();
                } else {
                    /*fetch('api/drinks_fetch_compare_dynamically')
                        .then(res => res.json())*/
                    axios.get('api/drinks_fetch_compare_dynamically', {
                        timeout: 60 * 4 * 1000
                    })
                        .then(res => {
                            this.endSlice = this.onScrollSlice;
                            this.articles = '';
                            this.products = _.orderBy(res.data, 'price', 'desc');
                            $('#preloader-wrapper').css("display", "none");
                            $('body').addClass('loaded');
                            // $(".overlay").remove();
                            window.scrollTo(0, 0);

                            if($(window).width() < 1500) {
                                document.getElementById("loadMoreButton").style.display = "block";
                            }

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