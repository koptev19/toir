<template>
    <div>
        <div class="row mb-5 h2">
            <div class='col-3'></div>
            <div class="col-9">{{ equipment }}</div>
        </div>
        <div class="row mb-5 h2">
            <div class='col-3'>Фамилия</div>
            <div class="col-9">
                <input type="text" name="fio" class="form-control form-control-lg border-dark" required v-model="fio" @change="changeFio" @keyup="changeFio">
            </div>
        </div>
        <div class="mb-5" v-show="buttons1Show">
            <input value="Приемка оборудования" type="button" class="btn btn-success btn-lg mr-3 h1" @click="accept">
            <input value="Замечания в процессе работы" type="button" class="btn btn-danger btn-lg mr-3 h1" @click="notesWork">
        </div>
        <div v-if="(checklist.length > 0) && checklistShow" class="row h2">
                <div class='col-3'>Чек-лист</div>
                <div class="col-9">
                    <div v-for="(item, index) in checklist" :key="index" class="form-check custom-control custom-checkbox mb-5">
                        <input class="form-check-input custom-control-input checklist" type="checkbox" :id="'check-' + index" @change="checkChange" v-model="checked[index]">
                        <label class="form-check-label custom-control-label pl-4" :for="'check-' + index">{{ item }}</label>
                    </div>
                </div>
        </div>

        <div v-if="buttons2Show" class="mb-5">
            <input value="Нет замечаний" type="submit" class="btn btn-success btn-lg mr-3 h1">
            <button type="button" class="btn btn-danger btn-lg h1" @click="notes">Есть замечания</button>
        </div>

        <div v-if="commentsShow" class="mb-5">
            <div class="row mb-5 h2">	
                <div class="col-3">Комментарий</div>
                <div class="col-9">
                    <textarea name="comment" class="form-control border-dark form-control-lg" required rows="5"></textarea>
                </div>
            </div>	
            <div class="row mb-5 h2">	
                <div class="col-3">Файлы</div>
                <div class="col-9">
                    <input type="file" multiple name="files[]" class="form-control form-control-lg" />
                </div>
            </div>
            <div class="row mb-5">
                <div class="col-6">
                    <input value="Сохранить" type="submit" class="btn btn-primary btn-lg">
                </div>
                <div class="col-6 text-end">
                    <button type="button" class="btn btn-outline-secondary btn-lg" @click="noNotes">Отмена</button>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        name: "acceptHistoryCreate",
        props: {
            equipment: {
                type: String,
                default: ""
            },
            checklist: {
                type: Array,
                default: () => ([])
            }
        },
        data() {
            return {
                fio: "",
                buttons1Show: false,
                buttons2Show: false,
                checklistShow: false,
                commentsShow: false,
                checked: {}
            }
        },
        methods: {
            changeFio: function() {
                if(this.fio.length > 1) {
                    this.buttons1Show = true;
                }
            },
            accept: function() {
                this.buttons1Show = false;
                this.checklistShow = true;
                this.checkChange();
            },
            checkChange: function() {
                let countTrue = 0;
                for(let key in this.checked) {
                    if(this.checked[key]) {
                        countTrue++;
                    }
                }
                if(countTrue == this.checklist.length) {
                    this.buttons2Show = true;
                } else {
                    this.buttons2Show = false;
                    this.commentsShow = false;
                }
            },
            notes: function() {
                this.commentsShow = true;
                this.buttons2Show = false;
            },
            noNotes: function() {
                this.buttons1Show = true;
                this.commentsShow = false;
                this.buttons2Show = false;
                this.checklistShow = false;
            },
            notesWork: function() {
                this.commentsShow = true;
                this.buttons1Show = false;
            },
        }
    }
</script>

<style>
    .custom-control-label:before, .custom-control-label:after, .form-check-input{ height:2rem; width:2rem;}
</style>
