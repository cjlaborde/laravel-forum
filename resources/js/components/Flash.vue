<template>
    <div :class="classes"
         style="right: 25px; bottom: 25px;"
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

        computed: {
            classes() {
                let defaults = ['fixed', 'p-4', 'border', 'text-white'];
                if (this.level === 'success') defaults.push('bg-green', 'border-green-dark');
                if (this.level === 'warning') defaults.push('bg-yellow', 'border-yellow-dark');
                if (this.level === 'danger') defaults.push('bg-red', 'border-red-dark');
                return defaults;
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
