<template>
    <div>
        <div class="p-3">Общее время простоя: {{ totalTime }}</div>
        <table class="table table-bordered m-3 table-hover w-auto">
            <thead>
                <tr class='text-center'>
                    <th>Дата</th>
                    <th>План</th>
                    <th>Простой</th>
                    <th>Человеко-часы</th>
                    <th>Операции</th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="row in rows" :key="row.id">
                    <td :style="'padding-left:' + 30 * (row.level-1) + 'px;' ">
                        <button v-if="row.children_count" :class="'btn py-0 ' + row.html_class" @click="showChildren(row)" type="button">
                            {{ row.name }}
                            <i v-if="row.show" class="fa fa-angle-up" aria-hidden="true"></i>
                            <i v-else class="fa fa-angle-down" aria-hidden="true"></i>
                        </button>
                        <span v-else :class="row.html_class">{{ row.name }}</span>
                    </td>
                    <td class="text-center">
                        <div v-if="row.level < 4">
                            {{ zeroNum(row.plan.h) }} : {{ zeroNum(row.plan.m) }}
                        </div>
                        <div v-if="row.level == 4">
                            <input type="number" @change="changePlan(downtimes)" @keyup="changePlan(downtimes)" v-model.number="row.plan.h" placeholder="Часы" step="1" min="0" class="form-control form-control-sm d-inline downtime-plan"> :
                            <input type="number" @change="changePlan(downtimes)" @keyup="changePlan(downtimes)" v-model.number="row.plan.m" placeholder="Минуты" step="1" min="0" max="59" class="form-control form-control-sm d-inline downtime-plan">
                        </div>
                    </td>
                    <td class="text-center">
                        {{ row.downtime }}
                    </td>
                    <td class="text-center">
                        {{ row.worktime }}
                    </td>
                    <td class="text-center">
                        <b-button variant="link text-dark" class="p-0" @click="showOperations(row)"><i class="fa fa-align-justify" aria-hidden="true"></i></b-button>
                    </td>
                </tr>
            </tbody>
        </table>

        <b-modal v-model="modalShow" size="xl" hide-footer centered content-class="shadow" scrollable title="Операции">
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
                modalShow: false,
                totalTime: '00:00'
            }
        },
        methods: {
            showChildren: function(parentDowntime) {  
                parentDowntime.show = !parentDowntime.show;
                if(parentDowntime.show) {
                    this.getDowntimes(parentDowntime, parentDowntime.level);
                } else {
                    parentDowntime.children = {};
                    this.createRowsByDowntimes();
                }
            },
            getDowntimes: function(parentDowntime, parentLevel) {
                let url = '/downtimes/items?' + 'level=' + (parentLevel + 1);
                if(parentLevel == 0) {
                    url += '&' + this.dates;
                }
                if(parentDowntime.id) {
                    url += '&parent=' + parentDowntime.id;
                }
                axios.get(url).then(({data}) => {
                    if(parentLevel) {
                        parentDowntime.children = this.objectDowntimesFromArrayItems(data.items);
                    } else {
                        this.downtimes = this.objectDowntimesFromArrayItems(data.items);
                        this.totalTime = data.total;
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
                    items[itemKey].plan = {
                        h: 0,
                        m: 0
                    };
                    objectDownTimes[items[itemKey].id] = items[itemKey];
                }
                return objectDownTimes;
            },
            showOperations: function(downtime) {
                this.operations = [];
                axios.get('/downtimes/operations?id=' + downtime.id).then(({data}) => {
                    this.modalShow = true;
                    this.operations = data.items;
                }).catch(function (error) {
                    alert('error');
                });
            },
            zeroNum: function(n) {
                return (n < 10 ? '0' : '') + n;
            },
            changePlan: function(downtimes) {
                let childrenPlan = {h: 0, m: 0};
                for (let key in downtimes) {
                    let childPlan = {h: 0, m: 0}
                    if(downtimes[key].level > 4) {
                        continue;
                    }
                    if(downtimes[key].children && downtimes[key].level < 4) {
                        childPlan = this.changePlan(downtimes[key].children);
                        downtimes[key].plan.h = childPlan.h;
                        downtimes[key].plan.m = childPlan.m;
                        downtimes[key].plan.h += Math.floor(downtimes[key].plan.m / 60);
                        downtimes[key].plan.m = downtimes[key].plan.m % 60;
                    }
                    childrenPlan.h += downtimes[key].plan.h;
                    childrenPlan.m += downtimes[key].plan.m;
                }
                childrenPlan.h += Math.floor(childrenPlan.m / 60);
                childrenPlan.m = childrenPlan.m % 60;
                return childrenPlan;
            }
        },
        mounted: function() {
            this.getDowntimes(this.downtimes, 0);
        }
    }
</script>

<style scoped>
    .downtime-plan {
        width: 50px;
    }
</style>