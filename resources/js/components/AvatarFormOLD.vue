<template>
    <div>
        <h1 v-text="user.name"></h1>

        <form v-if="canUpdate" method="POST" enctype="multipart/form-data">
<!--            # only accept images  @change is when user select new image-->
<!--            # 1) User chooses an image on the input-->
<!--            # 2) when it changes fires event onChange-->
            <input type="file" name="avatar" accept="image/*" @change="onChange">

            <button type="submit" class="btn btn-primary">Add Avatar</button>
        </form>
    <img :src="avatar" alt="avatar" width="100" height="100">
    </div>
</template>

<script>
    // #user who owns the profile
    export default {
        props: ['user'],
        data() {
            return {
                avatar: this.loadPath()
            };
        },
        computed: {
            canUpdate() {
                // # are they signed in ?
                return this.authorize(user => user.id === this.user.id)
            },
        },
        methods: {
            loadPath() {

                if (this.user.avatar_path.length == 0 ) {
                    return '/storage/avatars/default.png'
                }
                if (this.user.avatar_path.length > 100) {
                    return this.user.avatar_path
                }
                else {
                    return '/storage/' + this.user.avatar_path
                }
                // if (this.user.avatar_path.length > 100)
                // {
                //
                // }
                // return this.user.avatar_path.length > 100 ? this.user.avatar_path : '/storage/' + this.user.avatar_path
            },
            onChange(e) {
                // # if no lenght no need to do anything
                // # 3) checks to see if we do have a file if not we do nothing
                if (! e.target.files.length) return;
                // #otherwise this file will be equal to the image submited
                // # 4) if there is file it gets the first file on the target array
                let avatar = e.target.files[0];

                // # javascript api for reading content of a avatar
                // # 5) We read the file as Data URL
                let reader = new FileReader();

                reader.readAsDataURL(avatar);

                // # 6) Once that image is fully loaded. We will take  e.target.result; the data url
                // # 7) data() { return { avatar: '' }; } we assign it to
                reader.onload = e => {
                    // console.log(e);
                    // # load the image data on the page instantly
                    this.avatar = e.target.result;
                    console.log(this.avatar);
                };

                // Persist to the server so image get automatically uploaded without clicking button.
                // # 8) We call persists and send through the file not the url string but file object itself.
                    this.persist(avatar);
                },

                persist(avatar) {
                // # 9) we built the FormData
                    let data = new FormData();
                // # 10) We append the avatar name and make that equal to the file object.
                    data.append('avatar', avatar);
                    // # 11) We post that to the server
                    axios.post(`/api/users/${this.user.name}/avatar`, data)
                        .then(() => flash('Avatar uploaded!'));
                }
            }
    }
</script>
