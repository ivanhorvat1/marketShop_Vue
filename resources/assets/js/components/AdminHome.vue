<template>
    <div class="container">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <div class="panel panel-default">
                    <div class="panel-heading">Dashboard</div>

                    <div class="panel-body">
                        Welcome
                        You are logged in as admin!
                    </div>
                </div>
            </div>
        </div>
        <div id="mydiv" style="display: none" class="alert alert-success" role="alert">{{ response }}</div>
        <div class="container mt-3">
            <br>
            <!-- Nav tabs -->
            <ul class="nav nav-tabs">
                <li class="nav-item">
                    <a class="nav-link active" data-toggle="tab" href="#menu2">Updating articles</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" @click="fetchAllUsers" data-toggle="tab" href="#home">User Config</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" @click="getFreezeArticles" data-toggle="tab" href="#menu1">Freezed Articles</a>
                </li>
            </ul>

            <!-- Tab panes -->
            <!-- FIRST TAB updating articles from online stores -->
            <div class="tab-content">
                <div id="menu2" class="container tab-pane active"><br>
                    <!--<button @click="fetchArticles(0,'maxi','akcija')" class="btn btn-primary waves-effect waves-light">Maxi Akcija</button>
                    <button @click="storeArticles('maxi','akcija')" class="btn btn-warning">Ubaci Akcija Maxi</button>
                    <button @click="fetchArticles(0, 'maxi', 'pice')" class="btn btn-warning">Ubaci Maxi Pice</button>
                    <button @click="fetchArticles(0, 'maxi', 'meso')" class="btn btn-warning">Ubaci Maxi Meso</button>
                    <button @click="fetchArticles(0, 'maxi', 'slatkisi')" class="btn btn-warning">Ubaci Maxi Slatkisi</button>
                    <button @click="fetchArticles(0, 'maxi', 'smrznuti')" class="btn btn-warning">Ubaci Maxi Smrznuto</button>-->
                    <button @click="updateMaxiAction()" class="btn btn-warning">updateMaxiAction</button>
                    <button @click="updateMaxiDrink()" class="btn btn-warning">updateMaxiDrink</button>
                    <button @click="updateMaxiMeats()" class="btn btn-warning">updateMaxiMeats</button>
                    <button @click="updateMaxiSweets()" class="btn btn-warning">updateMaxiSweets</button>
                    <button @click="updateMaxiFreeze()" class="btn btn-warning">updateMaxiFreeze</button>
                    <br>
                    <br>
                    <!--<button @click="fetchArticles(0,'idea','akcija')" class="btn btn-primary">Idea Akcija</button>
                    <button @click="storeArticles('idea','akcija')" class="btn btn-info">Ubaci Akcija Idea</button><br><br>
                    <button @click="getCategoriesIdea('60007883')" class="btn btn-primary">Pice Idea</button>
                    <button @click="getCategoriesIdea('60007823')" class="btn btn-primary">Meso Idea</button>
                    <button @click="getCategoriesIdea('60007780')" class="btn btn-primary">Meso2 Idea</button>
                    <button @click="getCategoriesIdea('60007896')" class="btn btn-primary">Slatkisi Idea</button>
                    <button @click="getCategoriesIdea('60007907')" class="btn btn-primary">Smrznuti Proiz Idea</button><br>
                    <button @click="checkChildren()" class="btn btn-danger">check idea children</button>
                    &lt;!&ndash;:disabled="this.categoryArray.length < 16"&ndash;&gt;
                    <button @click="storeArticles('idea','pice')" class="btn btn-warning">Ubaci Pice Idea</button>
                    <button @click="storeArticles('idea','meso')" class="btn btn-warning">Ubaci Meso Idea</button>
                    <button @click="storeArticles('idea','slatkisi')" class="btn btn-warning">Ubaci Slatkisi Idea</button>
                    <button @click="storeArticles('idea','smrznuti')" class="btn btn-warning">Ubaci smrznuti Idea</button>-->
                    <button @click="updateIdeaAction()" class="btn btn-warning">updateIdeaAction</button>
                    <button @click="deleteIdeaRecords()" class="btn btn-danger">deleteIdeaRecords</button>
                    <button class="btn btn-warning"><a href="/getIdeaDrinkScheduler/60007883/null" style="color: black">UpdateIdeaDrinkScheduler</a></button>
                    <button class="btn btn-warning"><a href="/getIdeaMeatScheduler/60007823/null" style="color: black">UpdateIdeaMeatScheduler</a></button>
                    <button class="btn btn-warning"><a href="/getIdeaMeat2Scheduler/60007780/null" style="color: black">UpdateIdeaMeat2Scheduler</a></button>
                    <button class="btn btn-warning"><a href="/getIdeaSweetScheduler/60007896/null" style="color: black">UpdateIdeaSweetScheduler</a></button>
                    <button class="btn btn-warning"><a href="/getIdeaFreezeScheduler/60007907/null" style="color: black">UpdateIdeaFreezeScheduler</a></button>
                    <br><br>
                    <button class="btn btn-primary"><a href="/disDrink" style="color: white">Dis Market Pice</a></button>
                    <button class="btn btn-primary"><a href="/disMeat" style="color: white">Dis Market Meso</a></button>
                    <button class="btn btn-primary"><a href="/disFreeze" style="color: white">Dis Market Smrznuto</a></button>
                    <button class="btn btn-primary"><a href="/disSweet" style="color: white">Dis Market Slatkisi</a></button>
                    <button @click="updateDisDrinks()" class="btn btn-warning">Dis update Drinks</button>
                    <button @click="updateDisMeat()" class="btn btn-warning">Dis update Meat</button>
                    <button @click="updateDisFreeze()" class="btn btn-warning">Dis update Freeze</button>
                    <button @click="updateDisSweet()" class="btn btn-warning">Dis update Sweet</button>
                    <br><br>
                    <button class="btn btn-primary"><a href="/univerexportDrinks" style="color: white">Univerexport Market Pice</a></button>
                    <button class="btn btn-primary"><a href="/univerexportFreeze" style="color: white">Univerexport Market Smrznuto</a></button>
                    <button class="btn btn-primary"><a href="/univerexportSweets" style="color: white">Univerexport Market Slatkisi</a></button>
                    <button class="btn btn-primary"><a href="/univerexportMeats" style="color: white">Univerexport Market Meso</a></button>
                    <button @click="updateUniverexportArticles()" class="btn btn-warning">Univer update Articles</button>
                    <br><br>
                    <button @click="deleteCachedData()" class="btn btn-danger">deleteCachedData</button>
                    <button class="btn btn-primary"><a href="/scheduleRun" style="color: white">Schedule run</a></button>
                </div>
                <!-- SECOND TAB configure users -->
                <div id="home" class="container tab-pane fade"><br>
                    <br>
                    <b-container fluid>
                        <b-row>
                            <b-col md="6" class="my-1">
                                <b-form-group label-cols-sm="3" label="Filter" class="mb-0">
                                    <b-input-group>
                                        <b-form-input v-model="filter" placeholder="Type to Search"></b-form-input>
                                        <b-input-group-append>
                                            <b-button :disabled="!filter" @click="filter = ''">Clear</b-button>
                                        </b-input-group-append>
                                    </b-input-group>
                                </b-form-group>
                            </b-col>
                            <b-col md="6" class="my-1">
                                <b-form-group label-cols-sm="3" label="Per page" class="mb-0">
                                    <b-form-select v-model="perPage" :options="pageOptions"></b-form-select>
                                </b-form-group>
                            </b-col>
                        </b-row>
                        <b-table striped
                                 hover
                                 stacked="md"
                                 show-empty
                                 id="my-table"
                                 :items="users"
                                 :fields="fields"
                                 :per-page="perPage"
                                 :current-page="currentPage"
                                 :filter="filter"
                                 @filtered="onFiltered"
                        >
                            <template slot="actions" slot-scope="row">
                                <b-button size="sm" @click="row.toggleDetails">
                                    {{ row.detailsShowing ? 'Hide' : 'Show' }} Details
                                </b-button>
                                <b-button size="sm" @click="info(row.item, row.index, $event.target)" class="mr-1" variant="warning">
                                    Update
                                </b-button>
                                <b-button size="sm" @click="remove(row.item, row.index, $event.target)" class="mr-1" variant="danger">
                                    Remove
                                </b-button>
                            </template>
                            <template slot="row-details" slot-scope="row">
                                <b-card>
                                    <ul>
                                        <li v-for="(value, key) in row.item" :key="key">{{ key }}: {{ value }}</li>
                                    </ul>
                                </b-card>
                            </template>
                        </b-table>
                        <b-row>
                            <b-col md="6" class="my-1">
                                <b-pagination
                                        v-model="currentPage"
                                        :total-rows="totalRows"
                                        :per-page="perPage"
                                        class="my-0"
                                ></b-pagination>
                            </b-col>
                        </b-row>
                        <!--<p class="mt-3">Current Page: {{ currentPage }}</p>-->
                        <b-modal id="info-modal-user"
                                 ref="modal"
                                 title="Submit user"
                                 @show="resetModal"
                                 @hidden="resetModal"
                                 @ok="handleOk">
                            <form ref="form" @submit.stop.prevent="handleSubmit">
                                <b-form-group
                                        :state="nameState"
                                        label="Name"
                                        label-for="name-input"
                                        invalid-feedback="Name is required"
                                >
                                    <b-form-input
                                            v-model="editUser.name"
                                            id="name-input"
                                            :state="nameState"
                                            required
                                    ></b-form-input>
                                    <!--
                                    :value="infoModal.name"-->
                                </b-form-group>
                                <b-form-group
                                        :state="nameState"
                                        label="admin"
                                        label-for="admin-input"
                                >
                                    <b-form-input
                                            v-model="editUser.admin"
                                            id="admin-input"
                                            :state="nameState"
                                    ></b-form-input>
                                    <!--
                                    :value="infoModal.name"-->
                                </b-form-group>
                            </form>
                            <!--<pre>{{ infoModal.title }}</pre>-->
                        </b-modal>
                    </b-container>
                </div>
                <!-- THIRD TAB configure freezed articles -->
                <div id="menu1" class="container tab-pane fade"><br>
                    <br>
                    <b-container fluid>
                        <b-row>
                            <b-col md="6" class="my-1">
                                <b-form-group label-cols-sm="3" label="Filter" class="mb-0">
                                    <b-input-group>
                                        <b-form-input v-model="filter" placeholder="Type to Search"></b-form-input>
                                        <b-input-group-append>
                                            <b-button :disabled="!filter" @click="filter = ''">Clear</b-button>
                                        </b-input-group-append>
                                    </b-input-group>
                                </b-form-group>
                            </b-col>
                            <b-col md="6" class="my-1">
                                <b-form-group label-cols-sm="3" label="Per page" class="mb-0">
                                    <b-form-select v-model="perPage" :options="pageOptions"></b-form-select>
                                </b-form-group>
                            </b-col>
                        </b-row>
                        <b-table striped
                                 hover
                                 stacked="md"
                                 show-empty
                                 id="my-table"
                                 :items="freeze"
                                 :fields="fieldsFreeze"
                                 :per-page="perPage"
                                 :current-page="currentPage"
                                 :filter="filter"
                                 @filtered="onFiltered"
                        >
                            <template slot="actions" slot-scope="row">
                                <b-button size="sm" @click="row.toggleDetails">
                                    {{ row.detailsShowing ? 'Hide' : 'Show' }} Details
                                </b-button>
                                <b-button size="sm" @click="infoArticle(row.item, row.index, $event.target)" class="mr-1" variant="warning">
                                    Update
                                </b-button>
                                <b-button size="sm" @click="remove(row.item, row.index, $event.target)" class="mr-1" variant="danger">
                                    Remove
                                </b-button>
                            </template>
                            <template slot="row-details" slot-scope="row">
                                <b-card>
                                    <ul>
                                        <li v-for="(value, key) in row.item" :key="key">{{ key }}: {{ value }}</li>
                                    </ul>
                                </b-card>
                            </template>
                        </b-table>
                        <b-row>
                            <b-col md="6" class="my-1">
                                <b-pagination
                                        v-model="currentPage"
                                        :total-rows="totalRows"
                                        :per-page="perPage"
                                        class="my-0"
                                ></b-pagination>
                            </b-col>
                        </b-row>
                        <!--<p class="mt-3">Current Page: {{ currentPage }}</p>-->
                        <b-modal id="info-modal-freeze"
                                 ref="modal1"
                                 title="Submit market"
                                 @show="resetModal"
                                 @hidden="resetModal"
                                 @ok="handleOk">
                            <form ref="form" @submit.stop.prevent="handleSubmit">
                                <b-form-group
                                        :state="nameState"
                                        label="title"
                                        label-for="title-input"
                                        invalid-feedback="Name is required"
                                >
                                    <b-form-input
                                            v-model="editArticle.title"
                                            id="title-input"
                                            :state="nameState"
                                            required
                                    ></b-form-input>
                                    <!--
                                    :value="infoModal.name"-->
                                </b-form-group>
                                <b-form-group
                                        :state="nameState"
                                        label="forrmatedPrice"
                                        label-for="forrmatedPrice-input"
                                >
                                    <b-form-input
                                            v-model="editArticle.formattedPrice"
                                            id="forrmatedPrice-input"
                                            :state="nameState"
                                    ></b-form-input>
                                    <!--
                                    :value="infoModal.name"-->
                                </b-form-group>
                                <b-form-group
                                        :state="nameState"
                                        label="deleted"
                                        label-for="deleted-input"
                                >
                                    <b-form-input
                                            v-model="editArticle.deleted"
                                            id="deleted-input"
                                            :state="nameState"
                                    ></b-form-input>
                                    <!--
                                    :value="infoModal.name"-->
                                </b-form-group>
                            </form>
                            <!--<pre>{{ infoModal.title }}</pre>-->
                        </b-modal>
                    </b-container>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
    export default {
        name: "AdminHome",
        data() {
            return {
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
                response: '',
                users: [],
                freeze: [],
                totalRows: 1,
                perPage: 5,
                currentPage: 1,
                pageOptions: [5, 10, 15],
                filter: null,
                fields: [
                    {key: 'id', label: 'Id', sortable: true},
                    {key: 'name', label: 'Person name', sortable: true, sortDirection: 'asc'},
                    {key: 'email', label: 'Email'},
                    {key: 'admin', label: 'admin', sortable: true},
                    {key: 'actions', label: 'Actions'}
                ],
                fieldsFreeze: [
                    {key: 'id', label: 'Id', sortable: true},
                    {key: 'title', label: 'Title', sortable: true, sortDirection: 'asc'},
                    {key: 'body', label: 'body'},
                    {key: 'shop', label: 'shop'},
                    {key: 'formattedPrice', label: 'formattedPrice'},
                    {key: 'deleted', label: 'Deleted'},
                    {key: 'actions', label: 'Actions'}
                ],
                infoModalUser: {
                    id: 'info-modal-user',
                    name: '',
                    admin: ''
                },infoModalArticle: {
                    id: 'info-modal-freeze',
                    title: '',
                    body: '',
                    formattedPrice: '',
                    deleted: ''
                },
                editUser: {
                    id: '',
                    name: '',
                    admin: '',
                },
                editArticle: {
                    id: '',
                    title: '',
                    formattedPrice: '',
                    deleted: '',
                },
                nameState: null,
                submittedNames: []
            }
        },
        /*computed: {
            sortOptions() {
                // Create an options list from our fields
                return this.fields
                    .filter(f => f.sortable)
                    .map(f => {
                        return {text: f.label, value: f.key}
                    })
            }
        },*/
        created() {
            this.fetchAllUsers();
        },
        methods: {
            handleQuantityChange(event, line) {
                console.log(line);
            },
            fetchAllUsers() {
                fetch('api/fetchAllUsers')
                    .then(res => res.json())
                    .then(res => {
                        this.totalRows = res.length;
                        this.users = res;
                        $('body').addClass('loaded');
                        //console.log(res);
                    })
            },
            getFreezeArticles() {
                // $('body').removeClass('loaded');
                fetch('api/getAllFreezeArticles')
                    .then(res => res.json())
                    .then(res => {
                        this.totalRows = res.length;
                        this.freeze = res;
                        $('body').addClass('loaded');
                        //console.log(res);
                    })
            },
            onFiltered(filteredItems) {
                // Trigger pagination to update the number of buttons/pages due to filtering
                this.totalRows = filteredItems.length;
                this.currentPage = 1
            },
            info(item, index, button) {
                this.infoModalUser.title = `Row index: ${index}`;
                this.editUser.id = item.id;
                this.editUser.name = item.name;
                this.editUser.admin = item.admin;
                // this.infoModal.content = JSON.stringify(item, null, 2);
                this.$root.$emit('bv::show::modal', this.infoModalUser.id, button)
            },
            infoArticle(item, index, button){
                this.infoModalArticle.title = `Row index: ${index}`;
                this.editArticle.id = item.id;
                this.editArticle.title = item.title;
                this.editArticle.formattedPrice = item.formattedPrice;
                this.editArticle.deleted = item.deleted;
                this.$root.$emit('bv::show::modal', this.infoModalArticle.id, button)
            },
            remove(item, index, button) {
                this.$bvModal.msgBoxConfirm('Please confirm that you want to delete '+item.name+' user', {
                    title: 'Please Confirm',
                    size: 'sm',
                    buttonSize: 'sm',
                    okVariant: 'danger',
                    okTitle: 'YES',
                    cancelTitle: 'NO',
                    footerClass: 'p-2',
                    hideHeaderClose: false,
                    centered: true
                })
                    .then(value => {
                        if(value){
                            axios({
                                method: 'post',
                                url: '/api/remove_user',
                                data: {
                                    id: item.id
                                }
                            }).then(res => {
                                this.response = res.data.success;
                                $('#mydiv').css("display", "block");
                                setTimeout(function() {
                                    $('#mydiv').fadeOut('fast');
                                }, 5000);
                                this.fetchAllUsers();
                            });
                        }
                    })
                    .catch(err => {
                        // An error occurred
                    })
            },
            /*delete(item, index, button) {
                this.editTable.id = item.id;
                this.$root.$emit('bv::show::modal', this.infoModal.id, button)
            },*/
            resetInfoModal() {
                this.infoModal.title = '';
                this.infoModal.content = '';
            },
            checkFormValidity() {
                const valid = this.$refs.form.checkValidity();
                this.nameState = valid ? 'valid' : 'invalid';
                return valid;
            },
            resetModal() {
                //this.name = ''
                //this.admin = ''
                this.nameState = null;
            },
            handleOk(bvModalEvt) {
                // Prevent modal from closing
                bvModalEvt.preventDefault()
                // Trigger submit handler
                this.handleSubmit()
            },
            handleSubmit() {
                // Exit when the form isn't valid
                if (!this.checkFormValidity()) {
                    return
                }
                // Push the name to submitted names
                this.submittedNames.push(this.editUser.name);
                axios({
                    method: 'post',
                    url: '/api/update_user',
                    data: {
                        id: this.editUser.id,
                        name: this.editUser.name,
                        admin: this.editUser.admin
                    }
                }).then(res => {
                    this.response = res.data.success;
                    $('#mydiv').css("display", "block");
                    setTimeout(function() {
                        $('#mydiv').fadeOut('fast');
                    }, 5000);
                    this.fetchAllUsers();
                });
                // Hide the modal manually
                this.$nextTick(() => {
                    this.$refs.modal.hide()
                });
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
            fetchArticles(currentPage, shop, category) {
                let vm = this;
                let url;

                this.shop = shop;
                if (currentPage == 0) {
                    this.articles = [];
                }
                currentPage = currentPage || '0';
                url = vm.dinamicUrl(currentPage, shop, category);

                if (shop == 'idea') {
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
                } else {
                    if (category == 'akcija') {

                        //https://www.maxi.rs/view/QlProductListComponentController/getSearchPageData?componentId=PromotionListingProductListingComponent&pageNumber="+i+"&sort=promotion
                        //http://api.allorigins.ml
                        for (let i = 0; i <= 100; i++) {
                            // $.getJSON('https://api.allorigins.win/get?url=' + encodeURIComponent("https://www.maxi.rs/search/promotions/getSearchPageData?pageSize=20&pageNumber=" + i + "&sort=promotion") + '&callback=?', function (data) {
                            $.getJSON('http://www.whateverorigin.org/get?url=' + encodeURIComponent("https://www.maxi.rs/search/promotions/getSearchPageData?pageSize=20&pageNumber=" + i + "&sort=promotion") + '&callback=?', function (data) {
                                let res = JSON.parse(data.contents);
                                vm.storeMaxi(res, i);
                            });

                            /*if(i > 59){
                                alert('done');
                            }*/
                        }
                    } else {
                        $.getJSON('http://www.whateverorigin.org/get?url=' + encodeURIComponent(url) + '&callback=?', function (data) {
                            let res = JSON.parse(data.contents);
                            vm.storeMaxi(res);
                            vm.storeArticles(shop, category);
                            //M.toast({html: 'Succesfully added', classes: 'rounded'});
                        });
                    }
                }
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
                    } else if (this.shop === 'maxi' && category == 'akcija') {
                    }/* else if (this.shop === 'maxi' && category == 'akcija') {
                        for (let i = 1; i <= 60; i++) {
                            this.fetchArticles(i, this.shop, category);
                        }
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
            storeMaxi(res, i) {
                if (i == 0) {
                    this.maxi = [];
                    this.maxi = res.results;
                } else if (i > 0) {
                    this.maxi = this.maxi.concat(res.results);
                } else {
                    this.maxi = res.results;
                }
            },
            storeArticles(shop, category) {
                let vm = this;
                let picaIdea = [];
                if (this.maxi.length == 0 || this.idea.length == 0){
                    alert('prazno');
                }
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

                if (data != undefined) {
                    this.categoryArray.push(data);
                } else {
                    this.categoryArray = [];
                }
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
            },
            updateDisDrinks() {
                fetch('api/dis_update_drinks')
                    .then(res => res.json())
                    .then(res => {
                        //M.toast({html: res.success, classes: 'rounded'}, 3000);
                    })
            },
            updateDisMeat() {
                fetch('api/dis_update_meat')
                    .then(res => res.json())
                    .then(res => {
                       // M.toast({html: res.success, classes: 'rounded'}, 3000);
                    })
            },
            updateDisFreeze() {
                fetch('api/dis_update_freeze')
                    .then(res => res.json())
                    .then(res => {
                        //M.toast({html: res.success, classes: 'rounded'}, 3000);
                    })
            },
            updateDisSweet() {
                fetch('api/dis_update_sweet')
                    .then(res => res.json())
                    .then(res => {
                        //M.toast({html: res.success, classes: 'rounded'}, 3000);
                    })
            },
            updateUniverexportArticles() {
                fetch('api/univer_update_articles')
                    .then(res => res.json())
                    .then(res => {
                        //M.toast({html: res.success, classes: 'rounded'}, 3000);
                    })
            },updateMaxiAction() {
                fetch('api/updateMaxiAction')
                    .then(res => res.json())
                    .then(res => {
                        //M.toast({html: res.success, classes: 'rounded'}, 3000);
                    })
            },updateMaxiDrink() {
                fetch('api/updateMaxiDrink')
                    .then(res => res.json())
                    .then(res => {
                        //M.toast({html: res.success, classes: 'rounded'}, 3000);
                    })
            },updateMaxiMeats() {
                fetch('api/updateMaxiMeats')
                    .then(res => res.json())
                    .then(res => {
                        //M.toast({html: res.success, classes: 'rounded'}, 3000);
                    })
            },updateMaxiSweets() {
                fetch('api/updateMaxiSweets')
                    .then(res => res.json())
                    .then(res => {
                        //M.toast({html: res.success, classes: 'rounded'}, 3000);
                    })
            },updateMaxiFreeze() {
                fetch('api/updateMaxiFreeze')
                    .then(res => res.json())
                    .then(res => {
                        //M.toast({html: res.success, classes: 'rounded'}, 3000);
                    })
            },deleteIdeaRecords() {
                fetch('api/deleteIdeaRecords')
                    .then(res => res.json())
                    .then(res => {
                        //M.toast({html: res.success, classes: 'rounded'}, 3000);
                    })
            },updateIdeaAction() {
                fetch('api/updateIdeaAction')
                    .then(res => res.json())
                    .then(res => {
                        //M.toast({html: res.success, classes: 'rounded'}, 3000);
                    })
            },deleteCachedData() {
                fetch('api/deleteCachedData')
                    .then(res => res.json())
                    .then(res => {
                        //M.toast({html: res.success, classes: 'rounded'}, 3000);
                    })
            }
        }
    }
</script>

<style scoped>

</style>