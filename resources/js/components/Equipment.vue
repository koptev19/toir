<template>
    <div class="p-2 border rounded">
        <a href="#" data-bs-toggle="modal" data-bs-target="#equipmentModal">Выбрать оборудование</a>
        <input type="hidden" name="equipment_id" v-model="value">

        <div class="modal fade" id="equipmentModal" tabindex="-1" aria-labelledby="equipmentModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-scrollable modal-dialog-centered modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Выберите оборудование</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        ...
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-primary" data-bs-dismiss="modal" @click="select">Выбрать</button>
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
            }
        },
        methods: {
            select: function(event) {
                console.log('select');
            }
        },
        mounted() {
        }
    }
</script>
