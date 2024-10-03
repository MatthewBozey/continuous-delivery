<template>
    <div class="card shadow-8">
        <form @submit.prevent="submitForm">

            <div class="grid p-fluid m-1">

                <div class="field col-12 md:col-4">
                    <span class="">
                        <label for="update_script_id">ID конфигурационного скрипта </label>
                        <InputNumber id="update_script_id" type="text" readonly placeholder="ID конфигурационного скрипта"
                                     v-model="update_script.update_script_id" :loading="loading"/>
                    </span>
                </div>

                <div class="field col-12 md:col-4">
                    <span class="">
                        <label for="version">ID конфигурационного пакета</label>
                        <InputNumber id="version" type="text" v-model="update_script.update_package_id" :loading="loading" readonly placeholder="ID конфигурационного пакета"/>
                    </span>
                </div>

                <div class="field col-12 md:col-4">
                    <span class="">
                        <label for="script_type">Тип конфигурационного пакета</label>
                        <Select id="script_type" v-model="update_script.script_type_id" :options="script_type"
                                option-label="script_type_title" option-value="script_type_id"
                                placeholder="Тип конфигурационного пакета"></Select>
                    </span>
                </div>

            </div>

            <div class="grid p-fluid m-1">

                <div class="field col-12 md:col-4">
                    <span class="">
                        <label for="package_create_date">Дата создания конфигурационного скрипта</label>
                        <Calendar id="package_create_date" type="text" v-model="update_script.script_date" placeholder="Дата создания конфигурационного скрипта"
                                  date-format="yy-mm-dd" :show-time="true" :show-seconds="true" :loading="loading"/>
                    </span>
                </div>

                <div class="field col-12 md:col-4">
                    <span class="">
                        <label for="package_create_date">Дата выполнения конфигурационного скрипта</label>
                        <Calendar id="package_create_date" type="text" v-model="update_script.script_done_date" placeholder="Дата создания конфигурационного скрипта"
                                  date-format="yy-mm-dd" :show-time="true" :show-seconds="true" :loading="loading"/>
                    </span>
                </div>

                <div class="field col-12 md:col-4">
                    <span class="">
                        <label for="package_create_date">Название скрипта</label>
                        <InputText id="script_name" type="text" v-model="update_script.script_name" placeholder="Название скрипта" :loading="loading"/>
                    </span>
                </div>

            </div>

            <div class="grid p-fluid m-1">

                <div class="field col-12">
                    <span>
                        <label for="package_create_date">Текст конфигурационного скрипта</label>
                        <Textarea v-model="update_script.script_text" rows="10" :auto-resize="false"/>
                    </span>
                </div>

            </div>

            <div class="grid p-fluid m-1">

                <div class="field col-12 md:col-4">
                    <span>
                        <Checkbox v-model="update_script.is_done" :binary="true" class="mr-2"/>
                        <label for="package_create_date">Конфигурационный скрипт обработан</label>
                    </span>
                </div>

            </div>

            <div class="col-12 md:col-4 align-content-center justify-content-center flex">
                <Button class="p-button-success p-button-lg m-2 w-full flex" type="submit" label="Сохранить"
                        :loading="loading"/>
                <Button class="p-button-danger p-button-lg m-2 w-full flex" label="Отменить"
                        @click="cancelHandle" :loading="loading"/>
            </div>
        </form>

    </div>
</template>

<script>
import devops_api from "../service/devops_api";

export default {
    inject: ['dialogRef'],
    name: "UpdateScriptRow",
    components: {},
    data() {
        return {
            update_script: {
                update_script_id: null,
                update_package_id: null,
                script_date: null,
                script_text: null,
                is_done: null,
                script_done_date: null,
                script_name: null,
                script_order: null,
                script_type_id: null
            },
            loading: false,
            scriptText: '',
            method: null,
            updateScriptId: null,
            modal: false,
        }
    },
    mounted() {

        const params = this.dialogRef.data;
        this.updateScriptId = params.updateScriptId ?? this.$route.params.update_script_id ?? null;
        this.update_script.update_package_id = params.updatePackageId ?? this.$route.params.updatePackageId ?? null;
        this.method = params.method ?? this.$route.query["method"] ?? null;
        this.modal = params.modal ?? false;
        if (this.updateScriptId === null || this.method === null) {
            this.$router.push("/update-package");
            this.dialogRef.close();
        } else {
            Echo.private(`App.Models.UpdateScript.${this.updateScriptId}`)
                .listen('.UpdateScriptUpdated', (e) => {
                    this.update_script = e.model;
                    this.$notification.warning('Данные обновлены');
                });
            if (this.method === "update") {
                devops_api.get(`/api/update-script/${this.updateScriptId}`).then(value => {
                    this.update_script = value.data.data;
                    this.scriptText = (`\`\`\`sql \n${value?.data?.data?.script_text}\n \`\`\``);
                }).catch(reason => this.$store.dispatch("showError", reason));
                // this.scriptText = (`\`\`\`sql \n${this.update_script.script_text}\n \`\`\``);
                Echo.private(`App.Models.UpdateScript.${this.updateScriptId}`)
                    .listen('.UpdateScriptUpdated', (e) => {
                        this.update_script = e.model;
                        this.$notification.warning('Данные обновлены');
                    });
            }
        }

        Promise.all([
            devops_api.get("/api/project"),
            devops_api.get("/api/script-type")
        ]).then(([project, scriptType]) => {
            this.$store.commit("saveProject", project.data.data);
            this.$store.commit("saveScriptType", scriptType.data.data);
        })
            .catch((reason) => this.$store.dispatch("showError", reason));
    },
    computed: {
        project() {
            return this.$store.getters.project;
        },
        script_type() {
            return this.$store.getters.script_type;
        }
    },
    methods: {
        submitForm() {
            if (this.method === "update") {
                devops_api.put(`/api/update-script/${this.updateScriptId}`, this.update_script)
                    .then(value => {
                        this.$notification.success(`Скрипт №${value?.data?.data?.update_script_id} успешно отредактирован`);
                        if (this.modal) this.dialogRef.close();
                        else this.$router.push("/update-package");
                    })
                    .catch(reason => this.$store.dispatch("showError", reason))
            } else {

                devops_api.post("/api/update-script", this.update_script)
                    .then(value => {
                        this.$notification.success(`Скрипт №${value?.data?.data?.update_script_id} успешно создан`);
                        if (this.modal) this.dialogRef.close();
                        else this.$router.push("/update-package");
                    })
                    .catch(reason => this.$store.dispatch("showError", reason))
            }
        },

        cancelHandle() {
            console.log(this.modal);
            if (this.modal) this.dialogRef.close();
            else this.$router.push("/update-package");
        }
    },
}
</script>

<style scoped>

</style>
