<template>
    <div class="card shadow-8">
        <form @submit.prevent="submitForm">
            <div class="grid p-fluid m-1">
                <div class="field col-12 md:col-4">
                    <FloatLabel>
                        <InputNumber
                            id="update_package_id"
                            type="text"
                            readonly
                            class="w-full"
                            v-model="update_package.update_package_id"
                            :loading="loading"
                        />
                        <label for="update_package_id"
                            >ID конфигурационного пакета</label
                        >
                    </FloatLabel>
                </div>

                <div class="field col-12 md:col-4">
                    <FloatLabel>
                        <InputNumber
                            id="version"
                            type="text"
                            v-model="update_package.version"
                            :loading="loading"
                            class="w-full"
                        />
                        <label for="version">Номер версии</label>
                    </FloatLabel>
                </div>

                <div class="field col-12 md:col-4">
                    <FloatLabel>
                        <Select
                            id="project_id"
                            v-model="update_package.project_id"
                            :options="project"
                            option-label="project_title"
                            option-value="project_id"
                            class="w-full"
                            :loading="loading"
                        />
                        <label for="project_id">Проект</label>
                    </FloatLabel>
                </div>

                <div class="field col-12 md:col-4">
                    <FloatLabel>
                        <Select
                            id="package_type_id"
                            v-model="update_package.package_type_id"
                            :options="package_type"
                            option-label="package_name"
                            option-value="package_type_id"
                            class="w-full"
                            :loading="loading"
                        />
                        <label for="package_type_id"
                            >Тип конфигурационного пакета</label
                        >
                    </FloatLabel>
                </div>
            </div>

            <div class="grid p-fluid m-1">
                <div class="field col-12 md:col-4">
                    <FloatLabel>
                        <DatePicker
                            id="package_create_date"
                            type="text"
                            class="w-full"
                            v-model="update_package.package_create_date"
                            date-format="yy-mm-dd"
                            :show-time="true"
                            :show-seconds="true"
                            :loading="loading"
                        />
                        <label for="package_create_date"
                            >Дата создания конфигурационного пакета</label
                        >
                    </FloatLabel>
                </div>

                <div class="field col-12 md:col-4">
                    <FloatLabel>
                        <DatePicker
                            id="package_done_date"
                            type="text"
                            v-model="update_package.package_done_date"
                            date-format="yy-mm-dd"
                            class="w-full"
                            :show-time="true"
                            showButtonBar
                            :show-seconds="true"
                            :loading="loading"
                        />
                        <label for="package_done_date"
                            >Дата выполнения конфигурационного пакета</label
                        >
                    </FloatLabel>
                </div>
            </div>

            <div class="grid p-fluid m-1">
                <div class="field col-12 md:col-4">
                    <InputGroup class="p-1">
                        <Checkbox
                            v-model="update_package.verified"
                            :binary="true"
                            class="mr-2"
                            :loading="loading"
                            :disabled="update_package.has_errors"
                        />
                        <label for="package_create_date"
                            >Конфигурационный пакет обработан</label
                        >
                    </InputGroup>
                </div>

                <div class="field col-12 md:col-4">
                    <InputGroup class="p-1">
                        <Checkbox
                            v-model="update_package.has_errors"
                            :binary="true"
                            class="mr-2"
                            :loading="loading"
                            :disabled="
                                !this.check_permission(
                                    'update-package update-package-has-error'
                                )
                            "
                        />
                        <label for="package_done_date"
                            >Конфигурационный пакет имеет ошибки</label
                        >
                    </InputGroup>
                </div>
            </div>

            <div
                class="col-12 md:col-4 align-content-center justify-content-center flex"
            >
                <Button
                    class="p-button-success p-button-sm m-2 w-full flex"
                    outlined
                    type="submit"
                    label="Сохранить"
                    :disabled="loading"
                    :loading="loading"
                />
                <Button
                    class="p-button-danger p-button-sm m-2 w-full flex"
                    label="Отменить"
                    outlined
                    :disabled="loading"
                    @click="cancelButtonClickHandle"
                    :loading="loading"
                />
            </div>
        </form>
    </div>
</template>

<script>
import devops_api from "../service/devops_api";
import UpdateScript from "./UpdateScript.vue";
import { mapActions, mapGetters, mapMutations } from "vuex";

export default {
    name: "UpdatePackageRow",
    inject: ["dialogRef"],
    components: { UpdateScript },
    data() {
        return {
            update_package: {
                update_package_id: null,
                package_create_date: null,
                package_done_date: null,
                package_type_id: null,
                package_plan_date: null,
                project_id: null,
                verified: null,
                has_errors: null,
                version: null,
                update_package_name: null,
            },
            method: null,
            updatePackageId: null,
            modal: null,
        };
    },
    mounted() {
        Promise.all([
            devops_api.get("/api/project"),
            devops_api.get("/api/package-type"),
        ])
            .then(([project, packageType]) => {
                this.$store.commit("saveProject", project.data.data);
                this.$store.commit("savePackageType", packageType.data.data);
            })
            .catch((reason) => this.$store.dispatch("showError", reason));

        const params = this.dialogRef?.data;
        this.updatePackageId =
            params.updatePackageId ??
            this.$route.params["updatePackageId"] ??
            null;
        this.method = params.method ?? this.$route.query["method"] ?? null;
        this.modal = params.modal ?? this.$route.query["modal"] ?? null;
        if (!this.update_package?.project_id) {
            if (this.selected_project) {
                this.update_package.project_id = this.selected_project;
            }
        }
        if (this.updatePackageId === null && this.method === null) {
        } else if (this.method === "update") {
            devops_api
                .get(`/api/update-package/${this.updatePackageId}`)
                .then((value) => (this.update_package = value.data.data))
                .catch((reason) => this.showError(reason));

            window.Echo.private(
                `App.Models.UpdatePackage.${this.updatePackageId}`
            ).listen(".UpdatePackageUpdated", (e) => {
                this.update_package = e.model;
                this.$notification.warning("Данные обновлены");
            });
        }
    },
    computed: {
        ...mapGetters([
            "project",
            "package_type",
            "selected_project",
            "loading",
            "secondary_loading",
        ]),
    },
    methods: {
        ...mapActions(["showError"]),
        ...mapMutations(["SET_LOADING"]),

        submitForm() {
            if (this.$route.query["method"] === "update") {
                devops_api
                    .put(
                        `/api/update-package/${this.updatePackageId}`,
                        this.update_package
                    )
                    .then((value) => {
                        if (this.modal === true) this.dialogRef.close();
                        else this.$router.push("/update-package");
                        this.$notification.success(
                            `Пакет №${value.data.data.update_package_id} успешно отредактирован`
                        );
                        this.$store.commit(
                            "updateUpdatePackage",
                            value.data.data
                        );
                    })
                    .catch((reason) =>
                        this.$store.dispatch("showError", reason)
                    );
            } else {
                devops_api
                    .post("/api/update-package", this.update_package)
                    .then((value) => {
                        this.$notification.success(
                            `Пакет №${value.data.data.update_package_id} успешно создан`
                        );
                        if (this.modal === true) this.dialogRef.close();
                        else this.$router.push("/update-package");
                    })
                    .catch((reason) =>
                        this.$store.dispatch("showError", reason)
                    );
            }
        },

        cancelButtonClickHandle() {
            if (this.modal) this.dialogRef.close();
            else this.$router.push("/update-package");
        },
    },
};
</script>

<style scoped></style>
