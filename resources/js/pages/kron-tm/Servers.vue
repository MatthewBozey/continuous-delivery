<template>
    <div>
        <ProgressBar class="mt-1" v-if="!servers.length" mode="indeterminate" style="height: 6px"></ProgressBar>
        <div class="grid mt-1">
            <div v-for="server of servers" :key="server.cloudId" class="col-server col-2 items-center">
                <RadioButton v-model="selectedServer" :inputId="server.serverName" :name="server.serverName"
                             :value="server" @change="onChangeServer"/>
                <label :for="server.serverName" class="ml-2">{{ server.serverName }}</label>
            </div>

        </div>
        <custom-data-table
            v-if="selectedServer"
            :loading="loading"
            :data="data"
            :columns="columnsFit"
            :dictionary="dictionary"
            :summary="{}"
            v-model:selectedRow="selected"
            :paginator="true"
            @dblclick="rowDialog = true"
            :permissions="{write:true, insert:false, delete:false}"
        >
            <template #header>
                <slot name="header">Пользователи {{ selectedServer?.serverName }}</slot>
            </template>
        </custom-data-table>

        <Dialog v-model:visible="rowDialog"
                :header="`Редактирование пользователя ${selected ?selected.userName:''}`"
                :style="{ width: '28vw' }"  modal
                :contentStyle="{ height: '400px' }"
                @show="onShowDialog('userId')"
                @hide="onHideDialog('userId')">
            <div class="w-24 mt-2 text-center">
                <label class="font-semibold">Роли:</label>
                <SelectButton v-model="userRole" :options="['Администратор', 'Аналитика', 'Управление']"
                              aria-labelledby="basic"/>
            </div>
            <div class="w-24 mt-2 text-center">
                <Button label="Добавить андройд устройство" class="mt-2"
                        icon="pi pi-plus" style="width: 20vw"/>
            </div>
            <div class="w-24 mt-2 text-center">
                <Button label="Удалить одно устройство" class="mt-2"
                        icon="pi pi-refresh" style="width: 20vw"/>
            </div>
            <div class="w-24 mt-2 text-center">
                <Button label="Сбросить привязку к устройствам" class="mt-2"
                        icon="pi pi-trash" style="width: 20vw"/>
            </div>

            <template #footer>
                <Button label="Закрыть" icon="pi pi-times"
                        class="p-button-text" @click="rowDialog = false"/>
            </template>
        </Dialog>
    </div>
</template>

<script>
import CustomDataTable from "./CustomDataTable";
import Users from "./Users.vue";
import axios from 'axios';

export default {
    name: "Servers",
    mixins: [Users],
    methods: {
        start() {
            this.getServers()
        },
        getServers() {
            const api = `/api/kron-tm/servers`;
            this.$devops_api
                .get(api, {})
                .then((respons) => {
                    this.servers = respons.data.data;
                    if (this.servers.length) {
                        this.routerTimer = setTimeout(() => this.ifSelectServer(), 200);
                    }
                })
                .catch((reason) => this.showError(reason));
        },
        onChangeServer() {
            const query = {...this.$route.query, [this.entityKey]: this.selectedServer[this.entityKey]};
            this.$router.replace({query: query});
            this.getData({})
        },
        ifSelectServer() {
            if (this.entityKey) {
                for (const query in this.$route.query) {
                    if (query === this.entityKey) {
                        const index = this.servers.findIndex(
                            (item) => item[this.entityKey] == this.$route.query[this.entityKey]
                        );
                        if (index > -1) this.selectedServer = this.servers[index];
                        else
                            this.selectedServer = {
                                [this.entityKey]: this.$route.query[this.entityKey],
                            };

                    }

                }
                if (this.selectedServer) this.getData();

                //this.ifSelectRow('userId');
            }
        },
        /* getData(params = {}) {
             this.loading = true;
             console.log(`http://${this.selectedServer.serverExternalIp}:${this.selectedServer.wildflyPortExternal}/deploy.production/webresources/deployment/users`)
             axios.get(`http://${this.selectedServer.serverExternalIp.trim()}:${this.selectedServer.wildflyPortExternal}/deploy.production/webresources/deployment/users`, {
                 params: params
             })
                 .then((response) => this.data = response.data.data)
                 .catch((reason) => this.showError(reason))
                 .finally(() => this.loading = false);
         },*/
    },
    components: {
        CustomDataTable
    },
    data() {
        return {
            api: `/api/kron-tm/users`,
            servers: [],
            selectedServer: null,
            entityKey: 'serverId',
            userRole: null,
            columnsFit: [
                {
                    header: "ID",
                    field: "userId",
                    sortable: true,
                    bodyStyle: "text-align: right;",
                    filterData: {
                        type: "InputText",
                        attrList: {type: "number", step: "1"},
                        placeholder: " ",
                    },
                },
                {
                    header: "Логин",
                    field: "userName",
                    sortable: true,
                    filterData: {
                        type: "InputText",
                        placeholder: " ",
                    },
                },
                {
                    header: "Фамилия",
                    field: "userLastname",
                    sortable: true,
                    filterData: {
                        options: "userLastname",
                        type: "MultiSelect",
                        mode: "in",
                    },
                },
                {
                    header: "Имя",
                    field: "userFirstname",
                    sortable: true,
                    filterData: {
                        options: "userFirstname",
                        type: "MultiSelect",
                        mode: "in",
                    },
                },
                {
                    header: "Отчество",
                    field: "userMiddlename",
                    sortable: true,
                    filterData: {
                        options: "userMiddlename",
                        type: "MultiSelect",
                        mode: "in",
                    },
                },
                {
                    header: "Пароль",
                    field: "userPassword",
                    sortable: true
                },
                {
                    header: "Роль",
                    field: "postName",
                    sortable: true,
                    filterData: {
                        options: "post",
                        type: "postName",
                        mode: "in",
                    },
                },
                {
                    header: "Актуал",
                    field: "userIsActual",
                    sortable: true,
                    bodyStyle: "text-align: center;",
                    dataType: "component",
                    configComponents: [
                        {
                            name: "checkbox",
                            attrList: {
                                binary: true,
                                disabled: true,
                            },
                        },
                    ],
                    editor: {
                        name: "checkbox",
                        attrList: {
                            binary: true,
                        },
                    },
                    filterData: {
                        attrList: {
                            options: [{userIsActual: false}, {userIsActual: true}],
                        },
                        "max-width": "155px",
                        type: "Dropdown",
                        mode: "equals",
                        label: "name",
                    },
                },
                {
                    header: "Андройд",
                    field: "androidAccess",
                    sortable: true,
                    bodyStyle: "text-align: center;",
                    dataType: "component",
                    configComponents: [
                        {
                            name: "checkbox",
                            attrList: {
                                binary: true,
                                disabled: true,
                            },
                        },
                    ],
                    editor: {
                        name: "checkbox",
                        attrList: {
                            binary: true,
                        },
                    },
                    filterData: {
                        attrList: {
                            options: [{androidAccess: false}, {androidAccess: true}],
                        },
                        "max-width": "155px",
                        type: "Dropdown",
                        mode: "equals",
                        label: "name",
                    },
                },
                {
                    header: "Андройд/без привязки",
                    field: "post_name",
                    sortable: true
                },
            ],
        }
    }

}
</script>

<style scoped lang="scss">
.col-server {
    padding: 0rem !important;
}
</style>
