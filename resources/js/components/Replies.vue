<template>
    <div>
        <div v-for="(reply, index) in items" :key="reply.id">
            <reply :reply="reply" @deleted="remove(index)"></reply>
        </div>

<!--        # when ever there is changes it will cascade down to the paginator component-->
        <paginator :dataSet="dataSet" @changed="fetch"></paginator>

        <p v-if="$parent.locked">
            This thread has been locked. no more replies are allowed.
        </p>

        <new-reply  @created="add" v-else></new-reply>
    </div>
</template>

<script>
    import Reply from './Reply.vue';
    import NewReply from './NewReply.vue';
    import collection from '../mixins/collection';
    export default {
        components: { Reply, NewReply },
        mixins: [collection],
        data() {
            return {
                dataSet: false,
                // # if you type location.pathname in console you get the url path "/threads/repellat/84"
                // endpoint: location.pathname + '/replies'
            }
        },
        created() {
            // # when component renders fetch the data
            this.fetch();
        },
        methods: {
            // # page used for broadcast in paginator component
            fetch(page) {
                // # fetch url
                axios.get(this.url(page))
                    // # refresh content
                    .then(this.refresh);
            },
            url(page) {
                if (! page) {
                    // # page url query changes page=2
                    let query = location.search.match(/page=(\d+)/);
                    page = query ? query[1] : 1;
                }
                return `${location.pathname}/replies?page=${page}`;
            },
            refresh({data}) {
                this.dataSet = data;
                this.items = data.data;
                // console.log(data);
                // # after changing page in pagination it will scroll to top
                window.scrollTo(0,0);
            }
        }
    }
</script>
