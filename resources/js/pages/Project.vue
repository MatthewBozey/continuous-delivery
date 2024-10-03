<template>
    <div class="card">
        <div class="grid">
            <div class="col">
                <DataTable ref="dt" :value="project" v-model:selection="selectedData"
                           selection-mode="single" :metaKeySelection="false" @row-select="selectTableRow"
                           @row-unselect="unselectTableRow"
                           responsiveLayout="stack" :select-all="false" :loading="loading">
                    <template #header>
                        <Toolbar>
                            <template #start>
                                <Button class="p-button-success p-button-outlined p-button-sm" label="Создать"
                                        icon="pi pi-plus" @click="createStatePlanning"
                                        :disabled="disabledCreateButton"
                                        v-if="this.check_permission('project create')"/>
                            </template>
                        </Toolbar>
                    </template>
                    <Column field="project_id" header="ID" :sortable="true" style="min-width:2rem"></Column>
                    <Column field="project_name" header="Название" :sortable="true"></Column>
                    <Column field="project_sysname" header="Системное Название" :sortable="true"></Column>
                    <Column field="project_title" header="Заголовок" :sortable="true"></Column>
                    <Column field="project_desc" header="Описание" :sortable="true"></Column>
                    <Column field="server_names" header="Сервера" :sortable="true" style="max-width:6rem"></Column>
                    <Column field="required_update_server_names" header="Обязательные Сервера" :sortable="true"
                            style="max-width:6rem"></Column>
                    <Column field="to_cd" header="В CD" :sortable="true">
                        <template #body="slotProps">
                            <Checkbox v-model="slotProps.data.to_cd" :binary="true" disabled/>
                        </template>
                    </Column>
                </DataTable>
            </div>
            <div class="col card" v-show="showEditRow">
                <div class="field grid">
                    <label for="project_name" class="col-fixed">Название</label>
                    <div class="col">
                        <InputText type="text" v-model="selectedData.project_name" id="project_name"
                                   :disabled="loading"/>
                    </div>
                </div>
                <div class="field grid">
                    <label for="project_sysname" class="col-fixed">Системное Название</label>
                    <div class="col">
                        <InputText type="text" v-model="selectedData.project_sysname" id="project_sysname"
                                   :disabled="loading"/>
                    </div>
                </div>
                <div class="field grid">
                    <label for="project_title" class="col-fixed">Заголовок</label>
                    <div class="col">
                        <InputText type="text" v-model="selectedData.project_title" id="project_title"
                                   :disabled="loading"/>
                    </div>
                </div>
                <div class="field grid">
                    <label for="project_desc" class="col-fixed">Описание</label>
                    <div class="col">
                        <Textarea v-model="selectedData.project_desc" rows="5" cols="30" id="project_desc"
                                  :disabled="loading"/>
                    </div>
                </div>
                <div class="field grid">
                    <label for="servers" class="col-fixed">Сервера</label>
                    <div class="col">
                        <MultiSelect :options="server" :loading="loading" :disabled="loading"
                                     v-model="selectedData.server_ids" option-value="server_id"
                                     max-selected-labels="2"
                                     data-key="server_id" display="comma" option-label="server_name" id="servers"/>
                    </div>
                </div>
                <div class="field grid">
                    <label for="servers" class="col-fixed">Обязательные сервера</label>
                    <div class="col">
                        <MultiSelect :options="required_servers" :loading="loading" :disabled="loading"
                                     v-model="selectedData.required_update_server_ids" option-value="server_id"
                                     max-selected-labels="2"
                                     data-key="server_id" display="comma" option-label="server_name" id="servers"/>
                    </div>
                </div>
                <div class="field grid">
                    <label for="to_cd" class="col-fixed">В CD</label>
                    <div class="col">
                        <Checkbox v-model="selectedData.to_cd" :binary="true" id="to_cd" :disabled="loading"/>
                    </div>
                </div>
                <div class="field grid gap-3 ">
                    <Button class="p-button-success p-button-outlined w-full col" @click='clickRow' :loading="loading"
                            label="Сохранить" icon="pi pi-plus" v-if="this.check_permission('project edit')"/>
                    <Button class="p-button-danger p-button-outlined w-full col" @click='clickTrashRow'
                            :loading="loading"
                            label="Удалить" icon="pi pi-trash" v-if="this.check_permission('project delete')"/>
                    <Button class="p-button-secondary p-button-outlined w-full col" @click='clickCancelRow'
                            :loading="loading"
                            label="Отменить" icon="pi pi-times"/>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import {mapGetters, mapMutations} from "vuex";
import devops_api from "../service/devops_api";

export default {
    name: "Project",

    data() {
        return {
            selectedData: {
                project_id: null,
                project_name: null,
                project_sysname: null,
                project_title: null,
                project_desc: null,
                servers: null,
                required_servers: null,
                required_server_ids: null,
                to_cd: null,
                server_ids: [],
                required_update_server_ids: []
            },
            showEditRow: false,
            disabledCreateButton: false,
        }
    },
    mounted() {
        // this.SET_LOADING(true);
        Echo.private(`Project`)
            .listen('.ProjectUpdated', (e) => {
                if (e.model.project_id === this.selectedData.project_id) {
                    this.selectedData = e.model;
                    this.$notification.warning('Выбранные данные были обновлены');
                }
                this.$store.dispatch('appendProject', e.model);
            })
            .listen('.ProjectDeleted', (e) => {
                this.$store.dispatch('deleteProject', e.model);
            })
            .listen('.ProjectCreated', (e) => {
                this.$store.dispatch('appendProject', e.model);
            });
        Promise.all([
            devops_api.get("/api/project"),
            devops_api.get('/api/server')
        ])
            .then(([project, server]) => {
                this.saveProject(project.data.data);
                this.saveServer(server.data.data);
            })
            .catch((reason) => this.showError(reason));
        if (this.$route.query.method === 'create') {
            this.showEditRow = true;
        } else if (this.$route.query.method === 'update' && this.$route?.query?.state_id !== null) {
            this.$devops_api
                .get(`/api/project/${this.$route.query.state_id}`)
                .then(value => this.selectedData = value.data.data)
                .catch(reason => this.$store.dispatch('showError', reason));
            this.showEditRow = true;
        }
    },

    computed: {
        ...mapGetters(['project', 'server', 'loading', 'secondary_loading']),

        required_servers() {
            return this.server.filter(s => this.selectedData.server_ids.includes(s.server_id));
        }
    },

    methods: {
        ...mapMutations(['SET_LOADING', 'saveProject', 'saveServer']),

        selectTableRow(element) {
            if (this.check_permission('project show')) {
                this.resetSelected();
                this.$devops_api.get('/api/server')
                    .then(value => this.$store.commit('saveServer', value.data.data))
                    .catch(reason => this.$store.dispatch('showError', reason));
                this.showEditRow = true;
                this.disabledCreateButton = true;
                this.selectedData = element.data;
                this.$router.push({
                    path: this.$route.fullPath,
                    query: {method: "update", state_id: this.selectedData.project_id}
                });
            }
        },

        unselectTableRow() {
            this.$router.push({path: this.$route.fullPath, query: null});
            this.showEditRow = false;
            this.disabledCreateButton = false;
            this.selectedData = {
                project_id: null,
                project_name: null,
                project_sysname: null,
                project_title: null,
                project_desc: null,
                to_cd: null,
            };
        },

        clickRow(event) {
            this.$confirm.require({
                target: event.currentTarget,
                message: `Подтвердите`,
                header: 'Подтверждение',
                accept: () => {
                    if (this.$route.query.method === 'create') {
                        this.$devops_api.post('/api/project', this.selectedData).catch(reason => this.$store.dispatch('showError', reason)).then((value) => {
                            this.showEditRow = false;
                            this.disabledCreateButton = false;
                            this.$store.dispatch('appendProject', value.data.data);
                            this.resetSelected();
                            this.$notification.success('Добавлено');
                        }).finally(() => this.resetSelected());
                    } else if (this.$route.query.method === 'update' && this.selectedData.project_id !== null) {
                        this.$devops_api.put(`/api/project/${this.selectedData.project_id}`, this.selectedData).catch(reason => this.$store.dispatch('showError', reason)).then((value) => {
                            this.showEditRow = false;
                            this.disabledCreateButton = false;
                            this.$store.dispatch('appendProject', value.data.data);
                            this.resetSelected();
                            this.$notification.success('Отредактировано');

                        });
                    }
                },
            });
        },

        resetSelected() {
            this.SET_LOADING(false);
            this.$router.push({path: this.$route.fullPath, query: null});
            this.selectedData = {
                project_id: null,
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
                    this.$devops_api.delete(`/api/project/${this.selectedData.project_id}`).then(() => {
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
