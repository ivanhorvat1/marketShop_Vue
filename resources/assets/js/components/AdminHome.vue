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
                    <a class="nav-link active" @click="fetchAllUsers" data-toggle="tab" href="#home">User Config</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" @click="getFreezeArticles" data-toggle="tab" href="#menu1">Menu 1</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" data-toggle="tab" href="#menu2">Menu 2</a>
                </li>
            </ul>

            <!-- Tab panes -->
            <div class="tab-content">
                <div id="home" class="container tab-pane active"><br>
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
                        <b-table striped hover
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
                        <b-modal :id="infoModal.id"
                                 ref="modal"
                                 title="Submit Your Name"
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
                                            v-model="editTable.name"
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
                                        label-for="name-input"
                                >
                                    <b-form-input
                                            v-model="editTable.admin"
                                            id="name-input"
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
                <!-- SECOND TAB -->
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
                        <b-table striped hover
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
                        <b-modal :id="infoModal.id"
                                 ref="modal"
                                 title="Submit Your Name"
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
                                            v-model="editTable.name"
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
                                        label-for="name-input"
                                >
                                    <b-form-input
                                            v-model="editTable.admin"
                                            id="name-input"
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
                <div id="menu2" class="container tab-pane fade"><br>
                    <h3>Menu 2</h3>
                    <p>Sed ut perspiciatis unde omnis iste natus error sit voluptatem accusantium doloremque laudantium, totam rem aperiam.</p>
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
                response: '',
                users: [],
                freeze: [],
                totalRows: 1,
                perPage: 5,
                currentPage: 1,
                pageOptions: [2, 5, 10, 15],
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
                    {key: 'actions', label: 'Actions'}
                ],
                infoModal: {
                    id: 'info-modal',
                    name: '',
                    title: '',
                    content: ''
                },
                editTable: {
                    id: '',
                    name: '',
                    admin: '',
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
                this.infoModal.title = `Row index: ${index}`;
                this.editTable.id = item.id;
                this.editTable.name = item.name;
                this.editTable.admin = item.admin;
                // this.infoModal.content = JSON.stringify(item, null, 2);
                this.$root.$emit('bv::show::modal', this.infoModal.id, button)
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
                this.submittedNames.push(this.editTable.name);
                axios({
                    method: 'post',
                    url: '/api/update_user',
                    data: {
                        id: this.editTable.id,
                        name: this.editTable.name,
                        admin: this.editTable.admin
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
            }
        }
    }
</script>

<style scoped>

</style>