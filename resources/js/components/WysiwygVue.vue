<template>
    <div>
    <input id="trix" type="hidden" :name="name" value="foobar">
    <VueTrix ref="trix" placeholder="Enter content" inputName="body" localStorage/>
    </div>
</template>

<script>
    import VueTrix from "vue-trix";

    export default {
        // ...
        data() {
            return {
                // editorContent: this.form.body
            };
        },
        components: {
            VueTrix
        },
        props: ['name'],

        mounted() {
            // # listen when user types in value in input
            this.$refs.trix.addEventListener('trix-change', e => {
                console.log('Handling');
                this.$emit('input', e.target.innerHTML);
            });

        }
    };
</script>

<style scoped>

</style>
