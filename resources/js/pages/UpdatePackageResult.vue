<template>
    <div class="card p-2" style="min-height: 75vh;">
        <section>
            <DataTable :value="servers" scrollable v-model:expandedRows="expandedRows" scrollHeight="75vh"
                       :loading="loading" :row-style="getTableStyle" :row-class="data => 'p-1'">
                <template #loading>Происходит загрузка данных</template>
                <template #empty>Нет Данных Для Отображений</template>
                <Column expander style="width: 5rem"/>
                <Column header="ID" field="project_log_id"/>
                <Column header="Автор" field="author"/>
                <Column header="Дата создания" field="created_at"/>
                <Column header="Версия" field="version"/>
                <template #expansion="slotProps">
                    <div class="card">

                        <DataTable :value="slotProps.data.production_project_log_server ?? []"
                                   v-model:expandedRows="expandedScriptRows" :loading="loading"
                                   :row-style="getTableStyle">
                            <template #empty>Нет Данных Для Отображений</template>
                          <Column expander style="width: 1rem"/>
                            <Column header="ID" field="project_log_server_id"/>
                            <Column header="Сервер" field="server.server_name"/>
                            <Column header="Статус" field="server_status.status_title">
                                <template #body="rowProp">
                                    <Chip
                                        :pt="{root: {style: 'background: #' + rowProp.data.server_status.status_color ?? 'ffffff'}}">
                                        {{ rowProp.data.server_status.status_title }}
                                    </Chip>
                                </template>
                            </Column>
                            <Column header="Дата выполнения" field="created_at"/>
                            <template #expansion="slotSciptProps">
                                <div class="card p-1 m-2 grid" v-show="slotSciptProps.data.production_project_log_server_error.length ?? 0 > 0 ">
                                    <Message severity="error" :closable="false" v-for="error in slotSciptProps.data.production_project_log_server_error" class="col"
                                             :key="error.id"> {{ error.error_message }}</Message>
                                </div>
                                <div v-show="slotSciptProps.data.production_project_log_script ?? 0 > 0">
                                    <DataTable :value="slotSciptProps.data.production_project_log_script ?? []"
                                               :loading="loading" :row-style="getTableStyle">
                                        <template #empty>Нет Данных Для Отображений</template>
                                        <Column header="Тип скрипта" field="script.script_type.script_type_title"/>
                                        <Column header="Название скрипта" field="script.script_name"/>
                                        <Column header="Ошибки" field="script.script_name">
                                            <template #body="slotScriptPropRow">
                                                <Message severity="error" :closable="false"
                                                         v-for="error in slotScriptPropRow.data.production_project_log_script_error"
                                                         :key="error.id"> {{ error.error_text }}
                                                </Message>
                                            </template>
                                        </Column>
                                    </DataTable>
                                </div>
                            </template>
                        </DataTable>
                    </div>
                </template>
            </DataTable>
        </section>
    </div>
</template>

<script>
import CustomMarkdown from "../components/CustomMarkdown.vue";
import {inject} from "vue";

export default {
    inject: ['dialogRef'],
    components: {CustomMarkdown},
    data() {
        return {
            regexProcedure: /\[([^\]]+)\]/g,
            showScriptCodeSideBar: false,
            scriptText: '',
            scriptName: '',
            servers: null, // Здесь будет храниться массив серверов из JSON
            errorMessage: '',
            showErrorMessage: false,
            expandedRows: [],
            expandedScriptRows: [],
            loading: false,
            closableMomentPackage: false,
        };
    },
    mounted() {
        const params = this.dialogRef?.data;
        this.loading = true;
        if (params && params.projectLog && params.projectLog.project_log_id) {
            this.$devops_api
                .get(`/api/production-project-log/${params.projectLog.project_log_id}`)
                .then(value => this.servers = [value.data.data])
                .catch(reason => this.$store.dispatch('showError', reason))
                .finally(() => this.loading = false);
        } else {
            this.$devops_api
                .get('/api/production-project-log')
                .then(value => this.servers = value.data.data)
                .catch(reason => this.$store.dispatch('showError', reason))
                .finally(() => this.loading = false);
        }

    },
    methods: {

        hideScriptCodeHandle() {
            this.scriptText = '';
            this.scriptName = '';
            this.showScriptCodeSideBar = false;
        },

        showScriptCodeHandle(event, error_text) {
            if (error_text) {
                this.errorMessage = error_text;
                this.$refs.op.show(event)
            }
            /*this.scriptText = (`\`\`\`sql \n${script || ''}\n \`\`\``);
            const match = script.match(this.regexProcedure);
            this.scriptName = match ? `${match[0]}.${match[1]}` : '';
            this.showScriptCodeSideBar = true;*/
        },

        getTableStyle(data) {
            return 'background: ' + this.hexToRgbA('#' + ((data?.server_status?.status_color) ?? (data?.status_color) ?? 'ffffff'), 0.3);
        }

    },
};
</script>

<style scoped>

</style>
