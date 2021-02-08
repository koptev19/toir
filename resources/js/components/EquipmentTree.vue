<template>
    <div>
        <equipment-item 
            v-for="child in children" 
            :key="child.id" 
            :id="child.id" 
            :name="child.name" 
            :childrencount="child.children_count" 
            :htmlclass="child.html_class" 
            :selected="selected" 
            @select="select($event)"
            :active="activeId"
        ></equipment-item>
    </div>
</template>

<script>
    export default {
        name: "equipmentTree",
        props: {
            selected: {
                type: Array,
                default: () => ([])
            },
            parent: {
                type: String|Number,
                default: 0
            },
            active: {
                type: Number,
                default: 0
            }
        },
        data() {
            return {
                children: [],
                activeId: 0
            }
        },
        methods: {
            select: function(equipment) {
                this.$emit('select', equipment);
                if(this.parent) {
                    this.$emit('active', equipment.id);
                } else {
                    this.activeId = equipment.id;
                }
            }
        },
        mounted: function() {
            axios.get('/equipments/children?parent=' + this.parent).then(({data}) => {
                this.children = data.items;
            }).catch(function (error) {
                alert('error');
            });
        },
        watch: {
            active: function () {
                this.activeId = this.active;
            }
        }
    }
</script>
