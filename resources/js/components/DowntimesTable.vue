<template>
    <table class="table table-bordered m-3 table-hover w-auto">
        <thead>
            <tr class='text-center'>
                <th>Оборудование</th>
                <th>Простои</th>
            </tr>
        </thead>
        <tbody>
            <tr v-for="row in rows" :key="row.id">
                <td>
                    <button :class="'btn py-0 ' + row.html_class" :style="'padding-left:' + 30 * (row.level-1) + 'px;' " @click="showChildren(row)">
                        {{ row.equipment_name }}
                        <i v-if="row.show" class="fa fa-angle-up" aria-hidden="true"></i>
                        <i v-else class="fa fa-angle-down" aria-hidden="true"></i>
                    </button>
                </td>
                <td></td>
            </tr>
        </tbody>
    </table>
</template>

<script>
    export default {
        name: "downtimesTable",
        props: {
            route: {
                type: String,
                default: ''
            }
        },
        data() {
            return {
                rows: [],
                downtimes: {},
                showed: {}
            }
        },
        methods: {
            showChildren: function(parentDowntime) {  
                parentDowntime.show = !parentDowntime.show;
                if(parentDowntime.show) {
                    this.getDowntimes(parentDowntime);
                } else {
                    parentDowntime.children = {};
                    this.createRowsByDowntimes();
                }
            },
            getDowntimes: function(parentDowntime) {
                let url = this.route;
                if(parentDowntime.id) {
                    url += '&parent=' + parentDowntime.id;
                }
                axios.get(url).then(({data}) => {
                    if(parentDowntime.id) {
                        parentDowntime.children = this.objectDowntimesFromArrayItems(data.items);
                    } else {
                        this.downtimes = this.objectDowntimesFromArrayItems(data.items);
                    }
                    this.createRowsByDowntimes();
                }).catch(function (error) {
                    alert('error');
                });
            },
            createRowsByDowntimes: function() {
                this.rows = [];
                this.createRowsByDowntimesRecursive(this.downtimes);
            },
            createRowsByDowntimesRecursive: function(items) {
                for(let itemKey in items) {
                    this.rows.push(items[itemKey]);
                    if(items[itemKey].children) {
                        this.createRowsByDowntimesRecursive(items[itemKey].children);
                    }
                }
            },
            objectDowntimesFromArrayItems: function (items) {
                let objectDownTimes = {};
                for(let itemKey in items) {
                    objectDownTimes[items[itemKey].id] = items[itemKey];
                }
                return objectDownTimes;
            }
        },
        mounted: function() {
            this.getDowntimes(this.downtimes);
        }
    }
</script>
