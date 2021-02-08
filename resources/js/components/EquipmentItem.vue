<template>
    <div class="equipment-item">
        <div :class="'d-flex ' + activeclass">
            <a href="#" v-on:click="open" class="d-flex equipment-item-left link-dark">
                <span v-show="childrencount && opened">-</span>
                <span v-show="childrencount && !opened">&gt;</span>
            </a>
            <a :href="'/equipments/' + id" :class="'d-flex ' + htmlclass">{{ name }}</a>
        </div>
        <div class="ps-3 pt-1" v-if="opened">
            <equipment-item v-for="child in children" :key="child.id" :id="child.id" :name="child.name" :childrencount="child.children_count" :htmlclass="child.html_class" :route="route" :selected="selected"></equipment-item>
        </div>
    </div>
</template>

<script>
    export default {
        name: "equipmentItem",
        props: {
            id: {
                type: String|Number,
                default: 0
            },
            name: {
                type: String,
                default: ''
            },
            route: {
                type: String,
                default: ''
            },
            childrencount: {
                type: String|Number,
                default: 0
            },
            htmlclass: {
                type: String,
                default: ''
            },
            selected: {
                type: Array,
                default: () => ([])
            }
        },
        data() {
            return {
                opened: false,
                children: [],
                activeclass: ''
            }
        },
        methods: {
            open: function(event) {
                if(!this.opened) {
                    if(this.children.length == 0) {
                        this.getChildren();
                    }
                }
                this.opened = !this.opened;
            },
            getChildren: function() {
                axios.get(this.route, {
                    params: {
                        parent: this.id,
                    }
                }).then(({data}) => {
                    this.children = data.items;
                }).catch(function (error) {
                    alert('error');
                });
            }
        },
        mounted: function() {
            if(this.selected.length > 0 && this.selected.indexOf(Number(this.id)) > -1) {
                this.opened = true;
                this.getChildren();
                if(this.selected.indexOf(Number(this.id)) == this.selected.length - 1) {
                    this.activeclass = 'active';
                }
            }
        }
    }
</script>

<style>
.equipment-item a {
    text-decoration: none;
}

.equipment-item .active {
    background-color: rgb(220, 220, 220);
}

.equipment-item-left {
    width: 15px;
}
</style>