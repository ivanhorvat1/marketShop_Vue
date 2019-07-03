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
        <div class="col-sm-3" v-for="user in users">

            <p>{{ user.name }}</p>

        </div>
    </div>
</template>
<script>
    export default {
        name: "AdminHome",
        data() {
            return {
                // startSlice: 0,
                // endSlice: 12,
                // products: [],
                // articles: [],
                // key: 'opadajuce',
                // modalId: '',
                // title: '',
                // body: '',
                // imageUrl: '',
                // supplementaryPriceIdea: '',
                // supplementaryPriceMaxi: '',
                // ideaCena: '',
                // maxiCena: '',
                // disCena: '',
                // univerexportCena: '',
                // shop: ''
                users: []
            }
        },
        created() {
            this.fetchAllUsers();
            $('body').addClass('loaded');
        },
        methods: {
            fetchAllUsers() {
                fetch('api/fetchAllUsers')
                    .then(res => res.json())
                    .then(res => {
                        this.users = res;
                        $('body').addClass('loaded');
                        //console.log(res);
                    })
            },
        }
    }
</script>

<style scoped>

</style>