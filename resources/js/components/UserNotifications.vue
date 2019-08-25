<template>
    <li class="dropdown" v-if="notifications.length">
        <a href="#" class="nav-link" data-toggle="dropdown">
            <i class="fas fa-bell"></i>
        </a>

        <ul class="dropdown-menu">
            <li v-for="notification in notifications">
                <a class="nav-link"
                   :href="notification.data.link"
                   v-text="notification.data.message"
                   @click="markAsRead(notification)"
                ></a>
            </li>
        </ul>
    </li>
</template>

<script>
    export default {
        data() {
            return { notifications: false }
        },

        created() {
            axios.get("/profiles/" + window.App.user.name + "/notifications")
                .then(response => this.notifications = response.data);
        },

        methods: {
            // "/profiles/{$user->name}/notifications/" . $user->unreadNotifications->first()->id
            markAsRead(notification) {
                axios.delete('/profiles/' + window.App.user.name + '/notifications/' + notification.id)
            }
        }
    }
</script>
