class Users {
    constructor() {
        this.users = [];
    }

    addUser(id, room) {
        const user = { id, room };


        this.users.push(user);
        return user;
    }

    removeUser(id) {
        const user = this.getUser(id);
        if (user) {
            this.users = this.users.filter((user) => user.id != id);
        }
        return user;
    }

    getUser(room_id) {


         console.log('room_id',room_id);
        console.log('UsersLIST',this.users);


        return this.users.filter((user) => user.room === room_id)[0];
    }

}

module.exports = { Users };