<template>
    <div :id="'reply-'+id" class="card mb-3" :class="isBest ? 'bg-success': 'bg-light'">
        <div class="card-header bg-transparent">
            <div class="level">
                <img :src="reply.owner.avatar_path"
                     alt="reply.owner.name"
                     width="50"
                     height="50"
                     class="mr-3">
                <h5 class="flex">
                    <a :href="'/profiles/'+ reply.owner.name"
                        v-text="reply.owner.name">
                    </a> said <span v-text="ago"></span>
                </h5>

<!--                    @if (Auth::check())-->
                <div v-if="signedIn">
                    <favorite :reply="reply"></favorite>
                </div>
<!--                    @endif-->
            </div>
        </div>

        <div class="card-body">
            <div v-if="editing">
                <form @submit="update">
                    <div class="form-group">
                        <wysiwyg v-model="body"></wysiwyg>
<!--                        <textarea class="form-control" v-model="body" required></textarea>-->
                    </div>

                    <button class="btn btn-xs btn-primary">Update</button>
                    <button class="btn btn-xs btn-link" @click="editing = false" type="button">Cancel</button>
                </form>
            </div>

            <div v-else v-html="body"></div>
        </div>

<!--            Show can update if it user id.-->
        <div class="card-footer bg-transparent level" v-if="authorize('owns', reply) || authorize('owns', reply.thread)">
<!--                # authorize from resources/js/authorizations.js-->
            <div v-if="authorize('owns', reply)">
                <button class="btn btn-xs mr-1" @click="editing = true">Edit</button>
                <button class="btn btn-xs btn-danger mr-1" @click="destroy">Delete</button>
            </div>
<!--                <button class="btn-btn-xs btn-default ml-a" @click="markBestReply">Best Reply?</button>-->
            <button class="btn btn-xs btn-default ml-a" @click="markBestReply" v-if="authorize('owns', reply.thread)">Best Reply?</button>
        </div>
    </div>
</template>

<script>
    import Favorite from './Favorite.vue';
    import moment from 'moment';
    export default {
        props: ['reply'],
        components: { Favorite },
        data() {
            return {
                editing: false,
                id: this.reply.id,
                body: this.reply.body,
                isBest: this.reply.isBest,
            };
        },
        computed: {
            ago() {
                return moment(this.reply.created_at).fromNow() + '...';
            }
        },
        created () {
            window.events.$on('best-reply-selected', id => {
                this.isBest = (id === this.id);
            });
        },
        methods: {
            update() {
                axios.patch(
                    '/replies/' + this.id, {
                        body: this.body
                    })
                    .catch(error => {
                        flash(error.response.data, 'danger');
                    });
                this.editing = false;
                // # calls component flash.vue to show message
                flash('Updated!');
            },
            destroy() {
                axios.delete('/replies/' + this.id);
                this.$emit('deleted', this.id);
                // # make fade out with jquery dissapear and show flash message.
                // $(this.$el).fadeOut(300, () => {
                //     flash('Your reply has been deleted.')
                // });
            },
            markBestReply() {
                axios.post('/replies/' + this.id + '/best');
                window.events.$emit('best-reply-selected', this.reply.id);
            }
        }
    }
</script>
