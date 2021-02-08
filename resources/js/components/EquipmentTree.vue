<template>
    <div>
        <equipment-item v-for="child in children" :key="child.id" :id="child.id" :name="child.name" :childrencount="child.children_count" :htmlclass="child.html_class" :selected="selected" @select="$emit('select', $event)"></equipment-item>
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
            }
        },
        data() {
            return {
                children: []
            }
        },
        methods: {
        },
        mounted: function() {
            axios.get('/equipments/children/?parent=' + this.parent).then(({data}) => {
                this.children = data.items;
            }).catch(function (error) {
                alert('error');
            });
        }
    }
</script>
