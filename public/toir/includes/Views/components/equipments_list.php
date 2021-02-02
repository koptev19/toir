<?php $this->componentTemplateStart(); ?>

<div v-for="equipment in equipments" :key="equipment.ID">
    <equipment-link 
        :id="equipment.ID" 
        :title="equipment.NAME"
        :selected="selectedChild"
        v-on:equipmentSelect="equipmentSelect"
    ></equipments-list>
</div>

<?php $template= $this->componentTemplateContent(); ?>

<script>
BX.Vue.component('equipments-list', {
    template: '<div><?php echo $template; ?></div>',
    props: {
        equipments: {
            type: Array,
            default: () => ([])
        },
        selectedChild: {
            type: Number,
            default: 0
        }
    },
    methods: {
        equipmentSelect: function(data) {
            this.$emit('equipmentSelect', data)
        }
    }
    
});

</script>
