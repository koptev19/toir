<template>
    <div class="equipment-item">
        <div :class="'d-flex ' + activeClass">
            <a href="#" v-on:click="opened = !opened" class="d-flex equipment-item-left link-dark">
                <span v-show="childrencount && opened">-</span>
                <span v-show="childrencount && !opened">&gt;</span>
            </a>
            <a href="#" :class="'d-flex ' + htmlclass" @click="click">{{ name }}</a>
        </div>
        <div class="ps-3 pt-1" v-if="opened">
            <equipment-tree 
                :parent="id" 
                :selected="selected"
                :active="activeId"
                @select="$emit('select', {id: $event.id, name:name + ' / ' + $event.name})"
                @active="$emit('active', $event)"
            ></equipment-tree>
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
            },
            active: {
                type: Number,
                default: 0
            }
        },
        data() {
            return {
                opened: false,
                children: [],
                activeClass: '',
                activeId: 0
            }
        },
        methods: {
            click: function() {
                this.$emit('select', {id: this.id, name: this.name});
            }
        },
        mounted: function() {
            if(this.selected.length > 0 && this.selected.indexOf(Number(this.id)) > -1) {
                if(this.selected.indexOf(Number(this.id)) == this.selected.length - 1) {
                    this.activeClass = 'active';
                } else {
                    this.opened = true;
                }
            }
        },
        watch: {
            active: function(newActive, oldActive) {
                this.activeClass = newActive == this.id ? "active" : "";
                this.activeId = newActive;
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