<template>
    <div>
        <custom-data-table
            :loading="loading"
            :data="data"
            :columns="columnsFit"
            :dictionary="dictionary"
            :summary="{}"
            v-model:selectedRow="selected"
            :paginator="true"
            @dblclick="rowDialog = true"
            @update="getData"
            @insert="rowDialog=true"
            :rowClass="getRowClass"
        >
            <template #header>
                <slot name="header">Все пользователи системы</slot>
            </template>
        </custom-data-table>

        <Dialog v-model:visible="rowDialog"
                :header="selected && Object.keys(selected).length? `Редактирование пользователя ${selected.userName}`:'Добавление пользователя'"
                :style="{ width: '28vw' }" maximizable modal
                :contentStyle="{ height: '600px' }"
                @show="onShowDialog"
                @hide="onHideDialog">
            <CustomComponentsGrid
                :components="components"
                v-model="rowData"
                :submitRowDialog="submitRowDialog"
                :requiredFields="['userName', 'userPassword', 'userCompany', 'userLastname', 'userFirstname', 'userMiddlename']"
                ref="customComponentsGrid"
            />
            <template #footer>
                <Button label="Отмена" icon="pi pi-times"
                        class="p-button-text" @click="rowDialog = false"/>
                <Button label="Сохранить" icon="pi pi-save" @click="onClickSave"/>

            </template>
        </Dialog>
    </div>
</template>

<script>
import CustomDataTable from "./CustomDataTable";
import CustomComponentsGrid from "./CustomComponentsGrid";

import {mapActions} from "vuex";
import {nextTick} from "vue";

export default {
    name: "Users",
    mounted() {
        this.start();
    },
    unmounted() {
        clearTimeout(this.routerTimer);
    },
    methods: {
        ...mapActions(['showError']),
        onShowDialog(entity) {
            const entityKey = entity ?? this.entityKey;
            this.rowData = {...this.selected};
            const query = {...this.$route.query, [entityKey]: this.selected[entityKey]};
            this.$router.replace({query: query});
        },
        onHideDialog(entity) {
            const entityKey = entity ?? this.entityKey;
            this.submitRowDialog = false;
            let query = {};
            for (const item in this.$route.query)
                if (item !== entityKey) query[item] = this.$route.query[item];
            this.$router.replace({query: query});
        },
        start() {
            this.getData();
        },
        getData(params = {}) {
            this.loading = true;
            this.$devops_api
                .get(this.api, {
                    params: params
                })
                .then((response) => {
                    this.data = response.data.data;
                    if (this.data.length) {
                        this.routerTimer = setTimeout(() => this.ifSelectRow('userId'), 200);
                    }
                    this.isFirstLoad = false;
                })
                .catch((reason) => this.showError(reason))
                .finally(() => this.loading = false);
        },
        getRowClass(data) {
            if (data.id) return "row-accessories-orange";
            else return null;
        },
        ifSelectRow(entity) {
            const entityKey = entity ?? this.entityKey;
            if (entityKey && !this.rowDialog) {
                for (const query in this.$route.query) {
                    if (query === entityKey) {
                        const index = this.data.findIndex(
                            (item) => item[entityKey] == this.$route.query[entityKey]
                        );
                        if (index > -1) this.selected = this.data[index];
                        else
                            this.selected = {
                                [entityKey]: this.$route.query[entityKey],
                            };
                        this.rowDialog = true;
                    }
                }
            }
        },
        onClickSave() {
            this.submitRowDialog = true;
            nextTick(() => {
                if (this.$refs.customComponentsGrid.checkValidate()) {
                    this.updateRow({[this.entityKey]: this.rowData[this.entityKey]})
                    this.rowDialog = false;
                    return;
                }
            })
        },
        updateRow(params, entity) {
            this.$devops_api
                .get(this.api, {
                    params: params
                })
                .then((response) => {
                    const entityKey = entity ?? this.entityKey
                    const newRow = response.data.data.find((item)=> item[entityKey] === params[entityKey]);
                    if(newRow){
                        const rowndex = this.data.findIndex((item)=> item[entityKey] === params[entityKey]);
                        if(rowndex>-1) this.data[rowndex] = newRow;
                    }
                })
                .catch((reason) => this.showError(reason))

        }
    },
    components: {
        CustomDataTable,
        CustomComponentsGrid
    },
    data() {
        return {
            data: [],
            api: `/api/kron-tm/users`,
            entityKey: 'userId',
            loading: false,
            selected: null,
            rowData: {},
            rowDialog: false,
            submitRowDialog: false,
            isFirstLoad: true,
            components: [{type: 'text', field: 'userName', name: 'Логин'},
                {type: 'text', field: 'userLastname', name: 'Фамилия'},
                {type: 'text', field: 'userFirstname', name: 'Имя'},
                {type: 'text', field: 'userMiddlename', name: 'Отчество'},
                {type: 'text', field: 'userPassword', name: 'Пароль'},
                {type: 'text', field: 'userMail', name: 'Почта'},
                {type: 'text', field: 'userCompany', name: 'Компания'},
                {type: 'list', field: 'userPost', name: 'Должность'},
                {type: 'checkbox', field: 'androidAccess', name: 'Доступ для андроида'},
                {type: 'checkbox', field: 'userIsActual', name: 'Актуальный'}
            ],
            columnsFit: [
                {
                    header: "ID",
                    field: "userId",
                    sortable: true,
                    bodyStyle: "text-align: right;",
                    filterData: {
                        type: "InputText",
                        attrList: {type: "number", step: "1"},
                        placeholder: "id",
                    },
                },
                {
                    header: "Пользователь",
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
                    header: "Компания",
                    field: "userCompany",
                    sortable: true,
                    filterData: {
                        options: "userCompany",
                        type: 'MultiSelect',
                        mode: "in"
                    },
                },
                {
                    header: "Должность",
                    field: "userPost",
                    sortable: true,
                    filterData: {
                        options: "userPost",
                        type: "MultiSelect",
                        mode: "in",
                    },
                },
                {
                    header: "Пароль",
                    field: "userPassword",
                    tooltip: "userPassword",
                    bodyStyle: "overflow: hidden",
                },
                {
                    header: "Почта",
                    field: "userMail",
                    tooltip: "userMail",
                    bodyStyle: "overflow: hidden",
                    sortable: true
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
            ],
            dictionary: [
                {
                    options: "userCompany",
                    save: {store: false, self: true},
                    load: "self",
                    keyName: "userCompany",
                    valueName: "userCompany",
                },
                {
                    options: "userFirstname",
                    save: {store: false, self: true},
                    load: "self",
                    keyName: "userFirstname",
                    valueName: "userFirstname",
                },
                {
                    options: "userLastname",
                    save: {store: false, self: true},
                    load: "self",
                    keyName: "userLastname",
                    valueName: "userLastname",
                },
                {
                    options: "userMiddlename",
                    save: {store: false, self: true},
                    load: "self",
                    keyName: "userMiddlename",
                    valueName: "userMiddlename",
                },
            ],

        }
    },
}
</script>

<style scoped>

</style>
