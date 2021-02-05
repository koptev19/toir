<template>
    <table class="table table-bordered table-hover text-center" >
        <thead>
            <tr>
                <th class="text-left" rowspan="2">ФИО</th>
                <th rowspan="2">Администратор</th>
                <th rowspan="2">Службы</th>
                <th :colspan="workshops.length + 1">Цеха</th>
                <th rowspan="2">&nbsp;</th>
            </tr>
            <tr>
                <th>Все цеха</th>
                <th v-for="workshop in workshops" :key="workshop.id">
                    {{ workshop.name }}
                </th>
            </tr>
        </thead>
        <tbody>
            <tr v-for="user in connectedUsers" :key="user.id">
                <td class="text-start">{{ user.fullname}}</td>
                <td><input type="checkbox" v-model="user.is_admin" @change="setAdmin(user)"></td>
                <td>
                    <label v-for="department in departments" :key="department.id" class="d-block mb-2">
                        <input type="checkbox" v-model="user.connectedDepartments[department.id]" :disabled="user.is_admin"> {{ department.name }}
                    </label>
                </td>
                <td><input type="checkbox" v-model="user.all_workshops" :disabled="user.is_admin" @change="setAllWorkshops(user)"></td>
                <td v-for="workshop in workshops" :key="workshop.id">
                    <input type="checkbox" v-model="user.connectedWorkshops[workshop.id]" :disabled="user.all_workshops">
                </td>
                <td><button class="btn" @click="deleteUser(user)"><img src="/images/x.svg"></button></td>
            </tr>
        </tbody>
    </table>
</template>

<script>
    export default {
        name: "usersManagingTable",
        props: {
            users: {
                type: Array,
                default: () => ([])
            },
            workshops: {
                type: Array,
                default: () => ([])
            },
            departments: {
                type: Array,
                default: () => ([])
            }
        },
        data() {
            return {
                connectedUsers: []
            }
        },
        methods: {
            setAdmin: function(user) {
                if(user.is_admin) {
                    user.all_workshops = true;
                    for(let departmentNum in this.departments) {
                        user.connectedDepartments[this.departments[departmentNum].id] = true;
                    }
                    this.setAllWorkshops(user);
                }
            },
            setAllWorkshops: function(user) {
                if(user.all_workshops) {
                    for(let workshopNum in this.workshops) {
                        user.connectedWorkshops[this.workshops[workshopNum].id] = true;
                    }
                }
            },
            deleteUser: function(user) {
                this.connectedUsers.splice(this.connectedUsers.indexOf(user), 1);
            }
        },
        mounted: function() {
            for(let userNum in this.users) {
                let user = this.users[userNum];

                user.connectedWorkshops = {};
                for(let workshopNum in this.workshops) {
                    let workshop = this.workshops[workshopNum];
                    user.connectedWorkshops[workshop.id] = user.is_admin || user.all_workshops || user.workshops.indexOf(workshop.id) !== -1;
                }

                user.connectedDepartments = {};
                for(let departmentNum in this.departments) {
                    let department = this.departments[departmentNum];
                    user.connectedDepartments[department.id] = user.is_admin || user.departments.indexOf(department.id) !== -1;
                }

                if(user.is_admin) {
                    user.all_workshops = true;
                }

                if(user.connected) {
                    this.connectedUsers.push(user);
                }
            }
        }
    }
</script>
