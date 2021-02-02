<?php $this->componentTemplateStart(); ?>

<div :class="(selected == id ? \'equipment-select-selected\' : \'\') + \' p-1\'">
    <a href="#" v-on:click="equipmentClick">{{ title }}</a>
</div>
<div class="pl-4" v-if="showedChildren">
    <equipments-list 
        :equipments="children"
        :selectedChild="selected"
        v-on:equipmentSelect="equipmentSelect"
    ></equipments-list>
</div>

<?php $template= $this->componentTemplateContent(); ?>

<script>
BX.Vue.component('equipment-link', {
    template: '<div><?php echo $template; ?></div>',
    props: {
        id: {
            type: Number,
            default: 0
        },
        title: {
            type: String,
            default: ''
        },
        selected:{
            type:Number,
            default: 0
        }
    },
    data() {
        return {
            children: [],
            showedChildren: false,
        }
    },
    methods: {
        equipmentClick: function() {
            if(!this.showedChildren && this.children.length == 0) {
                axios.get('select_equipment.php?action=get&parent=' + this.id).then((response) => {
                    this.children = response.data.items;
                })
            }
            this.$emit('equipmentSelect', {id: this.id, 'name': this.title});
            this.showedChildren = !this.showedChildren;
        },
        equipmentSelect: function(data) {
            data.name = this.title + ' / ' + data.name;
            this.$emit('equipmentSelect', data)
        }
    },
    mounted() {
        axios.get('select_equipment.php?action=get').then((response) => {
            this.equipments = response.data.items;
        })
    }
});

</script>
