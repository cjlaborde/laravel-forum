let user = window.App.user;

module.exports = {
    // # owns the thread or reply
    owns(model, prop = "user_id") {
        return model[prop] === user.id;
    },

    isAdmin() {
        // return ['JohnDoe', 'John'].includes(user.name);
        return user.isAdmin;
    }
};
