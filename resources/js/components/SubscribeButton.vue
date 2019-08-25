<template>
<!--    <button class="btn btn" :class="active ? 'btn-success' : 'btn-outline-success'" @click="subscribe">Subscribe</button>-->
    <button :class="classes" @click="subscribe">Subscribe</button>
</template>

<script>
    export default {
        // # active used to see which btn color should be applies
        props: ['active'],

        computed: {
            classes() {
                return ['btn', this.active ? 'btn-success' : 'btn-outline-success']
            }
        },
        methods: {
            subscribe() {
                //  submit post to endpoint
                axios[
                    // # which request type should be used. Post when you subscribe and delete when you unsubscribe
                    (this.active ? 'delete' : 'post')
                    ](location.pathname + '/subscriptions');

                this.active = ! this.active;
            }
        }
    }
</script>
