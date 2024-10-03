<template>
    <div class="card">
      <div class="p-2">
        <DataTable :value="roles" data-key="id" headerClass="!text-center">
                <template #header>
                  <Toolbar class="">
                        <template #start>
                          <Button class="p-button-success" outlined
                                  icon="pi pi-plus" label="Добавить"
                                  @click="createRole" v-if="this.check_permission('role create')"
                          />
                        </template>
                    </Toolbar>
                </template>
                <Column header="ID" field="id" body-class="text-center"/>
                <Column header="Системное Название" field="name" body-class="text-center"/>
                <Column header="Название Роли" field="title" body-class="text-center"/>
                <Column>
                    <template #body="slotProps">
                        <div class="grid">
                          <Button class="p-button-rounded p-button-warning p-button-icon-only m-2"
                                  outlined icon="pi pi-pencil" v-if="this.check_permission('role edit')"
                                    @click="editRole(slotProps.data)"/>
                          <Button class="p-button-rounded p-button-danger p-button-icon-only m-2" outlined
                                  icon="pi pi-trash" v-if="this.check_permission('role delete')"
                                    @click="deleteRole($event, slotProps.data)"/>
                        </div>
                    </template>
                </Column>
            </DataTable>
        </div>

      <Dialog v-model:visible="showModal" style="width:50vw;height:50vh;" modal
                @show="loadDialog">
            <template #default>
                <form @submit.prevent="submitForm">
                    <div class="grid p-fluid m-1">

                      <div class="col-12 p-2">
                        <FloatLabel class="m-3">
                          <InputText id="name" type="text" v-model="role.name" :loading="loading" class="w-full"/>
                          <label for="name">Системное название</label>
                        </FloatLabel>
                        </div>

                      <div class="col-12 p-2">
                        <FloatLabel class="m-3">
                          <InputText id="title" type="text" v-model="role.title" :loading="loading" class="w-full"/>
                          <label for="title">Название роли</label>
                        </FloatLabel>
                        </div>

                      <div class="col-12 p-2">
                        <FloatLabel class="m-3">
                          <MultiSelect v-model="role.permissions" :loading="loading" class="w-full"
                                       :maxSelectedLabels="4" :selectedItemsLabel="'Выбрано прав: {0}'"
                                       :options="permissions" option-group-label="title"
                                             option-group-children="permissions" option-label="title"
                                             option-value="name"/>
                          <label for="title">Права роли</label>
                        </FloatLabel>
                        </div>
                      <Button type="submit" class="p-button-success m-3" severety="success" outlined
                              label="Сохранить"></Button>

                    </div>
                </form>
            </template>
            <template #footer>
            </template>
        </Dialog>
    </div>
</template>

<script>
import devops_api from "../service/devops_api";

export default {
    name: "Roles",
    data() {
        return {
            loading: false,
            showModal: false,
            role: {
                name: null,
                title: null,
                permissions: null,
            },
            permissions: null,
            roleId: null,
        }
    },
    mounted() {
        Echo.private("App.Models.Role")
            .listen(".created", (data) => {
                const roles = this.$store.getters.role;
                roles.push(data.role);
                this.$store.commit("saveRole", roles);
            })
            .listen(".updated", (data) => {
                const roles = this.$store.getters.role;
                let index = roles.findIndex((r => r.id === data.role.id));
                if (index !== -1) {
                    roles[index] = data.role;
                }
                this.$store.commit("saveRole", roles);
            })
            .listen(".deleted", (data) => {
                const roles = this.$store.getters.role;
                let index = roles.findIndex((r => r.id === data.role.id));
                if (index !== -1) {
                    roles.splice(index, 1);
                }
                this.$store.commit("saveRole", roles);
        })
        this.loading = true;
        devops_api.get("/api/role").then(value => this.$store.commit("saveRole", value.data.data)).catch(reason => this.$store.dispatch("showError", reason)).finally(() => this.loading = false)
    },
    computed: {
        roles() {
            return this.$store.getters.role;
        }
    },
    methods: {
        loadDialog() {
            this.loading = true;
            this.$devops_api.get("/api/permission-section").then(value => this.permissions = value.data.data).catch(reason => this.$store.dispatch("showError", reason)).finally(() => this.loading = false)
        },
        submitForm() {
            if (this.roleId === null) {
                this.$devops_api.post("/api/role", this.role).then(value => {
                    this.$notification.success(`Создана роль №${value.data.data.id}`);
                    this.showModal = false;
                }).catch(reason => this.$store.dispatch("showError", reason))
            } else {
                this.$devops_api.put(`/api/role/${this.roleId}`, this.role).then(value => {
                    this.$notification.success(`Роль №${value.data.data.id} отредактирована`);
                    this.showModal = false;
                }).catch(reason => this.$store.dispatch("showError", reason))
            }
        },
        editRole(data) {
            this.loading = true;
            this.roleId = data.id;
            this.$devops_api.get(`/api/role/${data.id}`)
                .then(value => {
                    this.role = value.data.data;
                    this.showModal = true;
                })
                .catch(reason => this.$store.dispatch("showError", reason))
                .finally(() => this.loading = false);
        },
        createRole() {
            this.showModal = true;
        },
        deleteRole(event, data) {
            this.$confirm.require({
                target: event.currentTarget,
                message: 'Вы уверены, что хотите продолжить?',
                icon: 'pi pi-exclamation-triangle',
                accept: () => {
                    this.$devops_api.delete(`/api/role/${data.id}`)
                        .then(value => this.$notification.success(`Роль №${data.id} удалена`))
                        .catch(reason => this.$store.dispatch("showError", reason))
                        .finally(() => this.loading = false);
                },
                reject: () => {},
                onShow: () => {},
                onHide: () => {}
            });
        },
    },
}
</script>

<style scoped>

</style>
