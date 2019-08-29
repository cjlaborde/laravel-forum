<template>
    <div>
        <input id="trix" type="hidden" :name="name" :value="value">

        <trix-editor ref="trix" input="trix" :placeholder="placeholder"></trix-editor>
    </div>
</template>

<style scoped>
    trix-editor {
        min-height: 100px;
    }
    .editor-content blockquote { font-style: italic; background: #1b1e21}
    .editor-content pre { background-color: rgb(239,240,241); }
</style>

<script>
    import Trix from 'trix';
    import 'trix/dist/trix.css';

    export default {
        props: ['name', 'value', 'placeholder', 'shouldClear'],
        mounted () {
            this.$refs.trix.addEventListener('trix-change', e => {
                console.log('handling');
                this.$emit('input', e.target.innerHTML);
            });
            // # same as watch()
            this.$watch('shouldClear', () => {
                // console.log('IT CHANGED!');
                this.$refs.trix.value = '';
            });
        }
    }
</script>
