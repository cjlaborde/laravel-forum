<template>
    <div>
        <input id="qui" type="hidden" :name="name" :bind="quil">
        <vue-editor input="qui" v-model="quil"></vue-editor>
    </div>
</template>

<script>
    import { VueEditor } from "vue2-editor";

    export default {
        components: {
            VueEditor
        },
        props: ['name'],
        data() {
            return {
                content: "<h1>Some initial content</h1>",
                quil: this.quil
            };
        }
    };
</script>
