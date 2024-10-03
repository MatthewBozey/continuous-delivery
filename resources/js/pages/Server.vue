<template>
    <div class="card">
        <div class="grid">
            <div class="col">
                <DataTable ref="dt" :value="servers" v-model:selection="selectedData"
                           selection-mode="single" :metaKeySelection="false" @row-select="selectTableRow"
                           @row-unselect="unselectTableRow"
                           responsiveLayout="stack" :select-all="false" :loading="loading">
                    <template #header>
                        <Toolbar>
                            <template #start>
                                <Button class="p-button-success p-button-outlined p-button-sm" label="Создать"
                                        icon="pi pi-plus" @click="createStatePlanning"
                                        v-if="this.check_permission('server create')"
                                        :disabled="disabledCreateButton"/>
                            </template>
                        </Toolbar>
                    </template>
                    <Column field="server_id" header="ID" :sortable="true" style="min-width:2rem"></Column>
                    <Column field="server_name" header="Название сервера" :sortable="true"></Column>
                    <Column field="database_name" header="Название базы данных" :sortable="true"></Column>
                    <Column field="database_user" header="Пользователь базы данных" :sortable="true"></Column>
                    <Column field="database_password" header="Пароль базы данных" :sortable="true"></Column>
                    <Column field="ip_address" header="IP адрес" :sortable="true"></Column>
                    <Column field="port" header="Порт" :sortable="true"></Column>
                    <Column field="disabled" header="Отключено" :sortable="true">
                        <template #body="slotProps">
                            <Checkbox v-model="slotProps.data.disabled" :binary="true" disabled/>
                        </template>
                    </Column>
                    <Column field="update_required" header="Обновления обязательны" :sortable="true">
                        <template #body="slotProps">
                            <Checkbox v-model="slotProps.data.update_required" :binary="true" disabled/>
                        </template>
                    </Column>
                </DataTable>
            </div>
            <div class="col card" v-show="showEditRow">
                <div class="field grid">
                    <label for="server_name" class="col-fixed">Название сервера</label>
                    <div class="col">
                        <InputText type="text" v-model="selectedData.server_name" id="server_name"/>
                    </div>
                </div>
                <div class="field grid">
                    <label for="database_name" class="col-fixed">Название базы данных</label>
                    <div class="col">
                        <InputText type="text" v-model="selectedData.database_name" id="database_name"/>
                    </div>
                </div>
                <div class="field grid">
                    <label for="database_user" class="col-fixed">Пользователь базы данных</label>
                    <div class="col">
                        <InputText type="text" v-model="selectedData.database_user" id="database_user"/>
                    </div>
                </div>
                <div class="field grid">
                    <label for="database_password" class="col-fixed">Пароль базы данных</label>
                    <div class="col">
                        <InputText type="text" v-model="selectedData.database_password" id="database_password"/>
                    </div>
                </div>
                <div class="field grid">
                    <label for="ip_address" class="col-fixed">IP адрес</label>
                    <div class="col">
                        <InputText type="text" v-model="selectedData.ip_address" id="ip_address"/>
                    </div>
                </div>
                <div class="field grid">
                    <label for="disabled" class="col-fixed">Отключено</label>
                    <div class="col">
                        <InputText type="text" v-model="selectedData.disabled" id="disabled"/>
                    </div>
                </div>
                <div class="field grid">
                    <label for="update_required" class="col-fixed">Обновления обязательны</label>
                    <div class="col">
                        <Checkbox v-model="selectedData.update_required" :binary="true" id="update_required"/>
                    </div>
                </div>
                <div class="field grid gap-3 ">
                    <Button class="p-button-success p-button-outlined w-full col" @click='clickRow'
                            v-if="this.check_permission('server edit')"
                            label="Сохранить" icon="pi pi-plus"/>
                    <Button class="p-button-danger p-button-outlined w-full col" @click='clickTrashRow'
                            v-if="this.check_permission('server delete')"
                            label="Удалить" icon="pi pi-trash"/>
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
                server_id: null,
                server_name: null,
                database_name: null,
                database_user: null,
                database_password: null,
                ip_address: null,
                port: null,
                disabled: null,
                update_required: null,
            },
            showEditRow: false,
            disabledCreateButton: false,
        }
    },

    mounted() {
        Echo.private(`Server`)
            .listen('.ServerUpdated', (e) => {
                if (e.model.server_id === this.selectedData.server_id) {
                    this.selectedData = e.model;
                    this.$notification.warning('Выбранные данные были обновлены');
                }
                this.$store.dispatch('appendServer', e.model);
            })
            .listen('.ServerDeleted', (e) => {
                this.$store.dispatch('deleteProject', e.model);
            })
            .listen('.ServerCreated', (e) => {
                this.$store.dispatch('appendServer', e.model);
            });
        this.$devops_api.get('/api/server').then(value => this.$store.commit('saveServer', value.data.data)).catch(reason => this.$store.dispatch('showError', reason));
        if (this.$route.query.method === 'create') {
            this.showEditRow = true;
        } else if (this.$route.query.method === 'update' && this.$route?.query?.state_id !== null) {
            this.$devops_api.get(`/api/server/${this.$route.query.state_id}`).then(value => this.selectedData = value.data.data).catch(reason => this.$store.dispatch('showError', reason));
            this.showEditRow = true;
        }
    },

    computed: {
        servers() {
            return this.$store.getters.server;
        }
    },

    methods: {
        selectTableRow(element) {
            if (this.check_permission('server show'))
                this.resetSelected()
            this.showEditRow = true;
            this.disabledCreateButton = true;
            this.selectedData = element.data;
            this.$router.push({
                path: this.$route.fullPath,
                query: {method: "update", state_id: this.selectedData.server_id}
            });
        },

        unselectTableRow() {
            this.$router.push({path: this.$route.fullPath, query: null});
            this.showEditRow = false;
            this.disabledCreateButton = false;
            this.selectedData = {
                server_id: null,
                server_name: null,
                database_name: null,
                database_user: null,
                database_password: null,
                ip_address: null,
                port: null,
                disabled: null,
                update_required: null,
            };
        },

        clickRow(event) {
            this.$confirm.require({
                target: event.currentTarget,
                message: `Подтвердите`,
                header: 'Подтверждение',
                accept: () => {
                    if (this.$route.query.method === 'create') {
                        this.$devops_api.post('/api/server', this.selectedData).catch(reason => this.$store.dispatch('showError', reason)).then((value) => {
                            this.showEditRow = false;
                            this.disabledCreateButton = false;
                            this.$store.dispatch('appendServer', value.data.data);
                            this.resetSelected();
                            this.$notification.success('Добавлено');
                        }).finally(() => this.resetSelected());
                    } else if (this.$route.query.method === 'update' && this.selectedData.server_id !== null) {
                        this.$devops_api.put(`/api/server/${this.selectedData.server_id}`, this.selectedData).catch(reason => this.$store.dispatch('showError', reason)).then((value) => {
                            this.showEditRow = false;
                            this.disabledCreateButton = false;
                            this.$store.dispatch('appendServer', value.data.data);
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
                server_id: null,
                project_name: null,
                project_sysname: null,
                project_title: null,
                project_desc: null,
                to_cd: null,
            };
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
                    this.$devops_api.delete(`/api/server/${this.selectedData.server_id}`).then(() => {
                        this.$store.dispatch('deleteProject', this.selectedData);
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
