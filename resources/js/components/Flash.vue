<template>
    <div class="alert alert-flash"
         :class="'alert-'+level"
         role="alert"
         v-show="show"
         v-text="body">
    </div>
</template>

<script>
    export default {
        props: ['message'],

        data() {
            return {
                body: this.message,
                level: 'success',
                show: false
            }
        },

        created() {
            if (this.message) {
                this.flash();
            }

            // # monitors for flash emit in bootstrap.js then show message when found
            window.events.$on(
                'flash', data => this.flash(data)
            );
        },

        methods: {
            flash(data) {
                // if data override the default
                if (data) {
                    this.body = data.message;
                    this.level = data.level;
                }
                this.show = true;

                this.hide();
            },

            hide() {
                // # flash message will disappear after 3 seconds
                setTimeout(() => {
                    this.show = false;
                }, 3000);
            }
        }
    }
</script>

<style>
    .alert-flash {
        position: fixed;
        right: 25px;
        bottom: 25px;
    }
</style>
