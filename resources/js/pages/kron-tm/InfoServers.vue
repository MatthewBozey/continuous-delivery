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
        </custom-data-table>
    </div>
</template>

<script>
import CustomDataTable from "./CustomDataTable";
import {mapActions} from "vuex";

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
        }
    },
    components: {
        CustomDataTable,
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
