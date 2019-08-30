<template>
<!--    <button class="btn btn" :class="active ? 'btn-success' : 'btn-outline-success'" @click.prevent="subscribe" v-text="isActive ? 'Subscribed' : 'Subscribe'"></button>-->
    <a href="#" class="ml-2 pl-2 border-l" :class="isActive ? 'font-bold': ''" @click.prevent="subscribe" v-text="isActive ? 'Subscribed' : 'Subscribe'"></a>
</template>

<script>
export default {
        // # active used to see which btn color should be applies
    props: ["active"],
    data() {
        return {
            isActive: this.active
        };
    },

//  submit post to endpoint
//  which request type should be used. Post when you subscribe and delete when you unsubscribe
    methods: {
        subscribe() {
            axios[this.isActive ? "delete" : "post"](
                location.pathname + "/subscriptions"
            );
            this.isActive = !this.isActive;
            if (this.isActive) {
                flash("Okay, we'll notify you when this thread is updated!");
            }
        }
    }
};
</script>
