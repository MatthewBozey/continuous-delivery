<template>
    <div class="card">
        <div class="grid">
            <div class="col">
                <DataTable ref="dt" :value="statePlanning" v-model:selection="selectedData"
                           selection-mode="single" :metaKeySelection="false" @row-select="selectStatePlanning"
                           @row-unselect="unselectStatePlanning"
                           responsiveLayout="stack" :select-all="false" :loading="loading">
                    <template #header>
                        <Toolbar>
                            <template #start>
                                <Button class="p-button-success p-button-outlined p-button-sm" label="Создать"
                                        icon="pi pi-plus" @click="createStatePlanning"
                                        :disabled="disabledCreateButton" v-if="this.check_permission('state-planning create')"/>
                            </template>
                        </Toolbar>
                    </template>
                    <Column field="server_status_id" header="ID" :sortable="true" style="min-width:2rem"></Column>
                    <Column field="status_title" header="Название" :sortable="true"></Column>
                    <Column field="status_name" header="Системное Название" :sortable="true"></Column>
                    <Column field="status_color" header="Цвет" :sortable="true" style="min-width:2rem">
                        <template #body="slotProps">
                            <ColorPicker v-model="slotProps.data.status_color" disabled/>
                        </template>
                    </Column>
                </DataTable>
            </div>
            <div class="col card" v-show="showEditRow">
                <div class="field grid">
                    <label for="firstname3" class="col-fixed">Название</label>
                    <div class="col">
                        <InputText type="text" v-model="selectedData.status_title"/>
                    </div>
                </div>
                <div class="field grid">
                    <label for="lastname3" class="col-fixed">Системное Название</label>
                    <div class="col">
                        <InputText type="text" v-model="selectedData.status_name"/>
                    </div>
                </div>
                <div class="field grid">
                    <label for="lastname3" class="col-fixed">Цвет Состояния</label>
                    <div class="col">
                        <ColorPicker v-model="selectedData.status_color"/>
                    </div>
                </div>
                <div class="field grid gap-3 ">
                    <Button class="p-button-success p-button-outlined w-full col" @click='clickRow'
                            label="Сохранить" icon="pi pi-plus" v-if="this.check_permission('state-planning edit')"/>
                    <Button class="p-button-danger p-button-outlined w-full col" @click='clickTrashRow'
                            label="Удалить" icon="pi pi-trash" v-if="this.check_permission('state-planning delete')"/>
                    <Button class="p-button-secondary p-button-outlined w-full col" @click='clickCancelRow'
                            label="Отменить" icon="pi pi-times"/>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    name: "StatePlanning",

    data() {
        return {
            loading: false,
            selectedData: {
                server_status_id: null,
                status_title: null,
                status_name: null,
                status_color: null
            },
            showEditRow: false,
            disabledCreateButton: false,
        }
    },

    mounted() {
        Echo.private(`StatePlanning`)
            .listen('.StatePlanningUpdated', (e) => {
                if (e.model.server_status_id === this.selectedData.server_status_id) {
                    this.selectedData = e.model;
                    this.$notification.warning('Выбранные данные были обновлены');
                }
                this.$store.dispatch('appendState', e.model);
            })
            .listen('.StatePlanningDeleted', (e) => {
                this.$store.dispatch('deleteStatePlanning', e.model);
            })
            .listen('.StatePlanningCreated', (e) => {
                this.$store.dispatch('appendState', e.model);
            });
        this.$devops_api.get('/api/state-planning').then(value => this.$store.commit('saveStatePlanning', value.data.data)).catch(reason => this.$store.dispatch('showError', reason));
        if (this.$route.query.method === 'create') {
            this.showEditRow = true;
        } else if (this.$route.query.method === 'update' && this.$route?.query?.state_id !== null) {
            this.$devops_api.get(`/api/state-planning/${this.$route.query.state_id}`).then(value => this.selectedData = value.data.data).catch(reason => this.$store.dispatch('showError', reason));
            this.showEditRow = true;
        }
    },

    computed: {
        statePlanning() {
            return this.$store.getters.state_planning;
        }
    },

    methods: {
        selectStatePlanning(element) {
            if(this.check_permission('state-planning show')) {
                this.resetSelected()
                this.showEditRow = true;
                this.disabledCreateButton = true;
                this.selectedData = element.data;
                this.$router.push({
                    path: this.$route.fullPath,
                    query: {method: "update", state_id: this.selectedData.server_status_id}
                });
            }
        },

        unselectStatePlanning() {
            this.$router.push({path: this.$route.fullPath, query: null});
            this.showEditRow = false;
            this.disabledCreateButton = false;
            this.selectedData = {
                status_code: null,
                status_color: null,
                status_title: null,
            };
        },

        clickRow(event) {
            this.$confirm.require({
                target: event.currentTarget,
                message: `Подтвердите`,
                header: 'Подтверждение',
                accept: () => {
                    if (this.$route.query.method === 'create') {
                        this.$devops_api.post('/api/state-planning', this.selectedData).catch(reason => this.$store.dispatch('showError', reason)).then((value) => {
                            this.showEditRow = false;
                            this.disabledCreateButton = false;
                            this.$store.dispatch('appendState', value.data.data);
                            this.resetSelected();
                            this.$notification.success('Добавлено');
                        }).finally(() => this.resetSelected());
                    } else if (this.$route.query.method === 'update' && this.selectedData.server_status_id !== null) {
                        this.$devops_api.put(`/api/state-planning/${this.selectedData.server_status_id}`, this.selectedData).catch(reason => this.$store.dispatch('showError', reason)).then((value) => {
                            this.showEditRow = false;
                            this.disabledCreateButton = false;
                            this.$store.dispatch('appendState', value.data.data);
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
                server_status_id: null,
                status_title: null,
                status_code: null,
                status_color: null
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
                    this.$devops_api.delete(`/api/state-planning/${this.selectedData.server_status_id}`).then(() => {
                        this.$store.dispatch('deleteStatePlanning', this.selectedData);
                        this.showEditRow = false;
                        this.disabledCreateButton = false;
                        this.resetSelected();
                        this.$notification.success('Удалено');
                    }).catch(reason => this.$store.dispatch('showError', reason));
                },
            });
        },
    }
}
</script>

<style scoped>

</style>
