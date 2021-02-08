<template>
    <div class="equipment-item">
        <div :class="'d-flex ' + activeclass">
            <a href="#" v-on:click="opened = !opened" class="d-flex equipment-item-left link-dark">
                <span v-show="childrencount && opened">-</span>
                <span v-show="childrencount && !opened">&gt;</span>
            </a>
            <a href="#" :class="'d-flex ' + htmlclass" @click="click">{{ name }}</a>
        </div>
        <div class="ps-3 pt-1" v-if="opened">
            <equipment-tree :parent="id" :selected="selected" @select="$emit('select', {id: $event.id, name:name + ' / ' + $event.name})"></equipment-tree>
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
            click: function() {
                this.$emit('select', {id: this.id, name: this.name});
                this.activeclass = 'active';
            }
        },
        mounted: function() {
            if(this.selected.length > 0 && this.selected.indexOf(Number(this.id)) > -1) {
                if(this.selected.indexOf(Number(this.id)) == this.selected.length - 1) {
                    this.activeclass = 'active';
                } else {
                    this.opened = true;
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