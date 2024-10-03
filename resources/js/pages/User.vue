<template>
    <div class="card">
        <DataTable :value="users" :loading="loading" responsiveLayout="stack">
            <template #header>
                <Toolbar>
                    <template #start>
                        <Button class="p-button-success" icon="pi pi-plus" label="Добавить" @click="createUser" v-if="this.check_permission('users create')"/>
                    </template>
                </Toolbar>
            </template>
            <Column field="user_id" header="ID"></Column>
            <Column field="username" header="Логин"></Column>
            <Column header="Права">
                <template #body="slotProps">
                    <div class="">
                        <Chip :label="item.title" v-for="item of slotProps?.data?.permissions" :key="item.id" class="m-1"></Chip>
                    </div>
                </template>
            </Column>
            <Column header="Роли">
                <template #body="slotProps">
                    <div class="grid">
                        <Chip :label="item.title" v-for="item of slotProps?.data?.roles" :key="item.id" class="m-1"></Chip>
                    </div>
                </template>
            </Column>
            <Column field="last_name" header="Фамилия"></Column>
            <Column field="first_name" header="Имя"></Column>
            <Column field="patronymic" header="Отчество"></Column>
            <Column field="email" header="E-mail"></Column>
            <Column body-class="p-1">
                <template #body="slotProps">
                    <Button class="p-button-warning p-button-icon-only m-1" icon="pi pi-pencil" @click="editUser(slotProps.data)" v-if="this.check_permission('users edit')"></Button>
                    <Button class="p-button-danger p-button-icon-only m-1" icon="pi pi-trash" v-if="this.check_permission('users delete')"></Button>
                </template>
            </Column>

        </DataTable>
    </div>
</template>

<script>
import devops_api from "../service/devops_api";

export default {
    name: "User",
    data() {
        return {
            loading: false,
        }
    },
    created() {

        this.loading = true;
        devops_api.get("/api/users")
            .then(d => {
                this.$store.commit("saveCdUser", d.data.data);
            })
            .finally(() => {
                this.loading = false;
            })
    },
    computed: {
        users() {
            return this.$store.getters.cdUsers;
        },
        getMessage() {
            return this.$moment().format();
        }
    },
    methods: {
        editUser(data) {
            this.$router.push({
                name: "UsersRowItem",
                params: {user_id: data.user_id},
                query: {method: "update"},
            });
        },
        createUser() {
            this.$router.push('/users-row')
        },
    },
}
</script>

<style scoped>
.p-chip {
    background: var(--primary-color);
    color: var(--primary-color-text);
    font-weight: bold;
}
</style>
