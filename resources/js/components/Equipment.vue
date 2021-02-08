<template>
    <div class="p-2 border rounded">
        <input v-if="required" type="text" required class="equipment_hidden" v-model="id" name="equipment_id">
        <a href="#" data-bs-toggle="modal" data-bs-target="#equipmentModal">{{ linkText }}</a>

        <div class="modal fade" id="equipmentModal" tabindex="-1" aria-labelledby="equipmentModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-scrollable modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Выберите оборудование</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <equipment-tree
                            :selected="selected"
                            v-on:select="select"
                        ></equipment-tree>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-primary" data-bs-dismiss="modal">Выбрать</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        name: "equipment",
        props: {
            value: {
                type: String|Number,
                default: ""
            },
            selected: {
                type: Array,
                default: () => ([])
            },
            required: {
                type: Boolean,
                default: false
            }
        },
        data: function() {
            return {
                linkText: 'Выбрать оборудование',
                id: '',
                selected: []
            }
        },
        methods: {
            select: function(equipment) {
                this.id = equipment.id;
                this.linkText = equipment.name;
            }
        },
        mounted() {
            this.id = this.value;
            if(this.id) {
                this.linkText = '';
                this.selected = [];
                axios.get('/equipments/' + this.id + '/parents').then(({data}) => {
                    for (let key in data.items) {
                        let item = data.items[key];
                        this.selected.push(item.id);
                        this.linkText += (this.linkText ? ' / ' : '') + item.name;
                    }
                }).catch(function (error) {
                    alert('error');
                    console.log(error);
                });
            }
        }
    }
</script>

<style scoped>
.equipment_hidden {
    position:absolute;
    opacity:0;
    border:none;
    width:1px;
}
</style>