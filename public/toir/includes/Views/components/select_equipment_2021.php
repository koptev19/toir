<?php 
$this->component('equipment_link');
$this->component('equipments_list');

$this->componentTemplateStart();
$seId = 'se_' . uniqid();
?>

<div class="border round p-2">
  <a href="#" v-on:click="showModal">
    {{ title }}
  </a>
  <input type="hidden" v-model="equipment">
</div>

<div class="modal" id="<?php echo $seId; ?>" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Выберите оборудование</h5>
      </div>
      <div class="modal-body">
        <equipments-list 
          :equipments="equipments"
          :selectedChild="selectedChild"
          v-on:equipmentSelect="equipmentSelect"
        ></equipments-list>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-primary" v-on:click="selectAndCloseModal" data-bs-dismiss="modal">Выбрать</button>
      </div>
    </div>
  </div>
</div>

<?php $template= $this->componentTemplateContent(); ?>

<script>
BX.Vue.component('select-equipment', {
    template: '<div><?php echo $template; ?></div>',
    props: {
      equipment: {
        type: Number,
        default: '',
      }
    },
    data() {
        return {
            equipments: [],
            selectedChild: '',
            name: '',
            title: 'Выберите оборудование',
            modal: null
        }
    },
    methods: {
      equipmentSelect: function(data) {
        this.name = data.name;
        this.selectedChild = data.id;
      },
      selectAndCloseModal: function() {
        this.equipment = this.selectedChild,
        this.title = this.name;
        this.modal.hide();
      },
      showModal: function() {
        this.modal = new bootstrap.Modal(document.getElementById('<?php echo $seId; ?>'), {});
        this.modal.show();
      }
    },
    mounted() {
        axios.get('select_equipment.php?action=get').then((response) => {
            this.equipments = response.data.items;
        })
    }
    
});

</script>

<style>
.equipment-select-selected {
    background-color:#d0d0d0;
}
</style>
