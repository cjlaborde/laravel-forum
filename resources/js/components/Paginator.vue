<template>
    <ul v-if="shouldPaginate">
        <li v-show="prevUrl" class="inline">
            <!--            prevent default-->
            <a href="#" aria-label="Previous" rel="prev" @click.prevent="page--">
                <span class="text-xs mr-2" aria-hidden="true">&laquo; Previous</span>
            </a>
        </li>
        <li v-show="nextUrl" class="inline">
            <a href="#" aria-label="Next" rel="next" @click.prevent="page++">
                <span class="text-xs" aria-hidden="true">Next &raquo;</span>
            </a>
        </li>
    </ul>
</template>

<script>
    export default {
        props: ["dataSet"],

        data() {
            return {
                // track current page:
                page: 1,
                prevUrl: false,
                nextUrl: false
            }
        },

        watch: {
            dataSet() {
                this.page = this.dataSet.current_page;
                this.prevUrl = this.dataSet.prev_page_url;
                this.nextUrl = this.dataSet.next_page_url;
            },
            // # keep eye on page property and if it ever get change broadcast it
            page() {
                this.broadcast().updateUrl();
            }
        },

        computed: {
            // # shold display pagination links only if we have both next and pre url pages
            shouldPaginate() {
                return !! this.prevUrl || !! this.nextUrl;
            }
        },

        methods: {
            broadcast() {
                // # update and send page user request
                return this.$emit("changed", this.page);
            },

            updateUrl() {
                // # dynamically change url query page=3
                history.pushState(null, null, "?page=" + this.page);
            }
        }
    }
</script>
