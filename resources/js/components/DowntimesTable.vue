<template>
    <div>
        <table class="table table-bordered m-3 table-hover w-auto">
            <thead>
                <tr class='text-center'>
                    <th>Оборудование</th>
                    <th>Простои</th>
                    <th>Операции</th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="row in rows" :key="row.id">
                    <td :style="'padding-left:' + 30 * (row.level-1) + 'px;' ">
                        <button v-if="row.children_count" :class="'btn py-0 ' + row.html_class" @click="showChildren(row)" type="button">
                            {{ row.equipment_name }}
                            <i v-if="row.show" class="fa fa-angle-up" aria-hidden="true"></i>
                            <i v-else class="fa fa-angle-down" aria-hidden="true"></i>
                        </button>
                        <span v-else :class="row.html_class">{{ row.equipment_name }}</span>
                    </td>
                    <td class="text-center">
                        {{ row.downtime }}
                    </td>
                    <td class="text-center">
                        <b-button v-if="row.exists_operations" variant="link" class="p-0" @click="showOperations(row)">Операции</b-button>
                    </td>
                </tr>
            </tbody>
        </table>

        <b-modal v-model="modalShow" size="xl" hide-footer centered hide-backdrop content-class="shadow" scrollable title="Операции">
            <table class="table table-bordered table-hover">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Оборудование</th>
                        <th>Название операции</th>
                        <th>Дата</th>
                        <th>Время</th>
                        <th>Исполнители</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="operation in operations" :key="operation.id">
                        <td>{{ operation.id }}</td>
                        <td>{{ operation.equipment_name }}</td>
                        <td>{{ operation.name }}</td>
                        <td>{{ operation.date }}</td>
                        <td>{{ operation.time }}</td>
                        <td>{{ operation.owner }}</td>
                    </tr>
                </tbody>
            </table>
        </b-modal>
    </div>        
</template>

<script>
    export default {
        name: "downtimesTable",
        props: {
            dates: {
                type: String,
                default: ''
            }
        },
        data() {
            return {
                rows: [],
                downtimes: {},
                showed: {},
                operations: [],
                modalShow: false
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
                let url = '/downtimes/items?' + this.dates;
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
            },
            showOperations: function(downtime) {
                this.operations = [];
                axios.get('/downtimes/' + downtime.id + '/operations?' + this.dates).then(({data}) => {
                    this.modalShow = true;
                    this.operations = data.items;
                }).catch(function (error) {
                    alert('error');
                });
            }
        },
        mounted: function() {
            this.getDowntimes(this.downtimes);
        }
    }
</script>
