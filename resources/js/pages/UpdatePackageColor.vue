<template>
    <div class="card">
        <div class="grid">
            <div class="col">
                <DataTable ref="dt" :value="update_package_color" v-model:selection="selectedData"
                           selection-mode="single" :metaKeySelection="false" @row-select="selectStatePlanning"
                           @row-unselect="unselectStatePlanning"
                           responsiveLayout="stack" :select-all="false" :loading="loading">
                    <template #header>
                        <Toolbar>
                            <template #start>
                                <Button class="p-button-success p-button-outlined p-button-sm" label="Создать"
                                        icon="pi pi-plus" @click="createStatePlanning"
                                        :disabled="disabledCreateButton"
                                        v-if="this.check_permission('update_package_color create')"/>
                            </template>
                        </Toolbar>
                    </template>
                    <Column field="id" header="ID" :sortable="true" style="max-width:2rem"></Column>
                    <Column field="min_value" header="Минимальное значение" style="max-width:2rem"></Column>
                    <Column field="max_value" header="Максимальное значение" style="max-width:2rem"></Column>
                    <Column field="color" header="Цвет" :sortable="true" style="max-width:2rem">
                        <template #body="slotProps">
                            <ColorPicker v-model="slotProps.data.color" disabled/>
                        </template>
                    </Column>
                </DataTable>
            </div>
            <div class="col card" v-show="showEditRow">
                <div class="field grid">
                    <label class="col-fixed">Минимальное значение</label>
                    <div class="col">
                        <InputNumber type="text" v-model="selectedData.min_value"/>
                    </div>
                </div>
                <div class="field grid">
                    <label for="lastname3" class="col-fixed">Системное Название</label>
                    <div class="col">
                        <InputNumber type="text" v-model="selectedData.max_value"/>
                    </div>
                </div>
                <div class="field grid">
                    <label for="lastname3" class="col-fixed">Цвет Состояния</label>
                    <div class="col">
                        <ColorPicker v-model="selectedData.color"/>
                    </div>
                    <Button class="p-button-danger p-button-outlined w-full col" @click='resetColorHandle'
                            label="Очистить цвет" icon="pi pi-trash"/>
                </div>
                <div class="field grid gap-3 ">
                    <Button class="p-button-success p-button-outlined w-full col" @click='clickRow'
                            label="Сохранить" icon="pi pi-plus"
                            v-if="this.check_permission('update_package_color edit')"/>
                    <Button class="p-button-danger p-button-outlined w-full col" @click='clickTrashRow'
                            label="Удалить" icon="pi pi-trash"
                            v-if="this.check_permission('update_package_color delete')"/>
                    <Button class="p-button-secondary p-button-outlined w-full col" @click='clickCancelRow'
                            label="Отменить" icon="pi pi-times"/>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import {mapActions, mapGetters, mapMutations} from "vuex";

export default {
    name: "UpdatePackageColorPage",

    data() {
        return {
            loading: false,
            selectedData: {
                id: null,
                min_value: null,
                max_value: null,
                color: null,
                author: null
            },
            showEditRow: false,
            disabledCreateButton: false,
        }
    },

    mounted() {
        Echo.private(`UpdatePackageColor`)
            .listen('.UpdatePackageColorUpdated', (e) => {
                if (e.model.id === this.selectedData.id) {
                    this.selectedData = e.model;
                    this.$notification.warning('Выбранные данные были обновлены');
                }
                this.appendUpdatePackageColor(e.model);
            })
            .listen('.UpdatePackageColorDeleted', (e) => {
                this.deleteUpdatePackageColor(e.model);
            })
            .listen('.UpdatePackageColorCreated', (e) => {
                this.appendUpdatePackageColor(e.model);
            });
        this.$devops_api.get('/api/update-package-color')
            .then(value => this.saveUpdatePackageColor(value.data.data))
            .catch(reason => this.showError(reason));
        if (this.$route.query.method === 'create') {
            this.showEditRow = true;
        } else if (this.$route.query.method === 'update' && this.$route?.query?.state_id !== null) {
            this.$devops_api.get(`/api/update-package-color/${this.$route.query.state_id}`)
                .then(value => this.selectedData = value.data.data)
                .catch(reason => this.showError(reason));
            this.showEditRow = true;
        }
    },

    computed: {
        ...mapGetters(['update_package_color', 'defaultBodyBackground']),
    },

    methods: {

        ...mapMutations(['saveUpdatePackageColor']),
        ...mapActions(['appendUpdatePackageColor', 'deleteUpdatePackageColor', 'showError', 'showToast', 'getDefaultBackgroundColor']),

        selectStatePlanning(element) {
            console.log(element);
            if (this.check_permission('update_package_color show')) {
                this.resetSelected()
                this.showEditRow = true;
                this.disabledCreateButton = true;
                this.selectedData = element.data;
                this.$router.push({
                    path: this.$route.fullPath,
                    query: {method: "update", state_id: this.selectedData.id}
                });
            } else {
                this.showToast('Отказано в доступе', {type: 'error'})
            }
        },

        unselectStatePlanning() {
            this.$router.push({path: this.$route.fullPath, query: null});
            this.showEditRow = false;
            this.disabledCreateButton = false;
            this.selectedData = {
                id: null,
                min_value: null,
                max_value: null,
                color: null,
                author: null
            };
        },

        clickRow(event) {
            this.$confirm.require({
                target: event.currentTarget,
                message: `Подтвердите`,
                header: 'Подтверждение',
                accept: () => {
                    if (this.$route.query.method === 'create') {
                        this.$devops_api.post('/api/update-package-color', this.selectedData)
                            .catch(reason => this.showError(reason))
                            .then((value) => {
                                this.showEditRow = false;
                                this.disabledCreateButton = false;
                                this.appendUpdatePackageColor(value.data.data);
                                this.resetSelected();
                                this.$notification.success('Добавлено');
                            })
                            .finally(() => {});
                    } else if (this.$route.query.method === 'update' && this.selectedData.id !== null) {
                        this.$devops_api.put(`/api/update-package-color/${this.selectedData.id}`, this.selectedData)
                            .catch(reason => this.showError(reason))
                            .then((value) => {
                                this.showEditRow = false;
                                this.disabledCreateButton = false;
                                this.appendUpdatePackageColor(value.data.data);
                                this.resetSelected();
                                this.$notification.success('Отредактировано');
                            });
                    }
                },
            });
        },

        resetSelected() {
            this.$router.push({path: this.$route.fullPath, query: null});
            this.selectedData = {
                id: null,
                min_value: null,
                max_value: null,
                color: null,
                author: null
            }
        },

        createStatePlanning() {
            this.disabledCreateButton = true;
            this.showEditRow = true;
            this.resetSelected();
            this.$router.push({path: this.$route.fullPath, query: {method: "create"}});
        },

        clickCancelRow(event) {
            this.$confirm.require({
                target: event.currentTarget,
                message: `Подтвердите`,
                header: 'Подтверждение',
                accept: () => {
                    this.showEditRow = false;
                    this.resetSelected();
                    this.disabledCreateButton = false;
                    this.$router.push({path: this.$route.fullPath, query: null});
                },
            });
        },

        clickTrashRow(event) {
            this.$confirm.require({
                target: event.currentTarget,
                message: `Подтвердите удаление`,
                header: 'Подтверждение',
                accept: () => {
                    this.$devops_api.delete(`/api/update-package-color/${this.selectedData.id}`).then(() => {
                        this.deleteUpdatePackageColor(this.selectedData);
                        this.showEditRow = false;
                        this.disabledCreateButton = false;
                        this.resetSelected();
                        this.$notification.success('Удалено');
                    }).catch(reason => this.showError(reason));
                },
            });
        },

        resetColorHandle(event) {
            this.$confirm.require({
                target: event.currentTarget,
                message: `Подтвердите очистку цвета`,
                header: 'Подтверждение',
                accept: () => {
                    this.selectedData.color = null;
                },
            });
        },
    }
}
</script>

<style scoped>

</style>
