<template>
    <Toolbar class="p-0">
        <template #start>
            <div>
                <Button
                    title="Обновить"
                    icon="pi pi-sync"
                    class="m-1 p-button-sm"
                    @click="updateData()"
                />
                <Button
                    title="Добавить"
                    icon="pi pi-plus"
                    class="m-1 p-button-sm"
                    :disabled="!permissions.insert"
                    @click="insertRow()"
                />
                <Button
                    title="Редактировать"
                    icon="pi pi-pencil"
                    class="m-1 p-button-sm"
                    :disabled="!selection || !permissions.write"
                    @click="$emit('dblclick', selection, $event)"
                />
                <Button
                    title="Удалить"
                    icon="pi pi-trash"
                    class="p-button-danger m-1 p-button-sm"
                    :disabled="!selection || !permissions.delete"
                    @click="deleteDialog = true;"
                />
            </div>
        </template>
    </Toolbar>
    <DataTable
        :value="data"
        :loading="loading"
        autoLayout
        class="p-datatable-sm p-datatable-gridlines"
        @rowContextmenu="onRowContextMenu"
        v-model:contextMenuSelection="selection"
        :paginator="paginator"
        :scrollable="!!scrollHeight"
        :scrollHeight="scrollHeight"
        :rows="rows"
        paginatorTemplate="CurrentPageReport FirstPageLink PrevPageLink PageLinks NextPageLink LastPageLink RowsPerPageDropdown"
        :rowsPerPageOptions="rowsPerPageOptions"
        currentPageReportTemplate="Отображается с {first} по {last} из {totalRecords}"
        v-model:filters="currentFilters"
        filterDisplay="menu"
        :sortField="sortField ? sortField : undefined"
        v-model:selection="selection"
        selectionMode="single"
        :rowClass="rowClass ? onRowClass : undefined"
        @dblclick.prevent="$emit('dblclick', selection, $event)"
        ref="dataTable"
        :sortOrder="1"
    >
        <template #header v-if="$slots.header">
            <slot name="header"></slot>
        </template>

        <template #empty> Записи не найдены.</template>
        <template #loading> Записи загружаются. Пожалуйста, подождите.</template>
        <Column
            v-for="col of columns"
            :sortable="col.sortable ? col.sortable : false"
            :field="col.field"
            :header="col.header"
            :filterMatchMode="col.filterData ? col.filterData.mode : null"
            :filterFunction="col.filterData ? col.filterData.funcName : null"
            :key="col.field"
            :headerStyle="col.headerStyle ? col.headerStyle : undefined"
            :bodyStyle="col.bodyStyle ? col.bodyStyle : undefined"
            :showFilterMatchModes="false"
        >
            <template #body="slotProps">
                <div v-if="col.dataType === 'image'">
                    <!--<img
                      v-if="slotProps.data[col.field] > -1"
                      :src="`images/24x24/${col.image[Number(slotProps.data[col.field])]}.png`"
                      width="16"
                      :title="
                        col.hint
                          ? col.hint[Number(slotProps.data[col.field])]
                          : slotProps.data[col.field]
                      "
                    />-->
                </div>
                <div v-else-if="col.dataType === 'image-text'">
                    <!--  <img
                      class="image-text"
                      :src="require(`/public/images/24x24/${slotProps.data[col.imageField]}.png`)"
                      width="20"
                    />-->
                    <span>{{ slotProps.data[col.field] }}</span>
                </div>
                <div v-else-if="col.dataType === 'component'">
                    <component
                        v-for="comp of col.configComponents"
                        v-bind:is="comp.name"
                        v-model="slotProps.data[col.field]"
                        v-bind="comp.attrList ? comp.attrList : undefined"
                        :key="comp.funcName"
                        @click="
                comp.componentFunction
                  ? comp.componentFunction(slotProps.data)
                  : componentFunction(slotProps.data, comp.funcName)
              "
                    ></component>
                </div>
                <div v-else-if="col.dataType === 'check-box'">
                    <component
                        v-bind:is="col.configComponent.name"
                        :icon="
                slotProps.data[col.field]
                  ? col.configComponent.iconTrue
                  : col.configComponent.iconFalse
              "
                        :class="[
                slotProps.data[col.field]
                  ? col.configComponent.classTrue
                  : col.configComponent.classFalse,
              ]"
                        :key="col.configComponent.funcName"
                        @click="
                col.configComponent.componentFunction
                  ? col.configComponent.componentFunction(slotProps.data)
                  : componentFunction(slotProps.data, col.configComponent.funcName)
              "
                    ></component>
                </div>
                <div v-else-if="col.dataType === 'dropdown'">
                    <component
                        v-if="col.configComponent?.name"
                        v-bind:is="col.configComponent.name"
                        :modelValue="slotProps.data[col.field]"
                        v-bind="col.configComponent.attrList"
                        appendTo="body"
                        :key="col.configComponent"
                        @change="
                col.configComponent.componentFunction
                  ? col.configComponent.componentFunction(slotProps.data)
                  : componentFunction(slotProps.data, col.configComponent.funcName)
              "
                    ></component>
                    {{
                        getFieldName(
                            filterDictionary[col.loadOptions] ??
                            filterDictionary[col?.configComponent?.options] ??
                            col.configComponent?.attrList?.options,
                            slotProps.data[col.field],
                            col?.configComponent.value ?? col?.configComponent?.attrList.optionValue,
                            col?.configComponent.label ?? col?.configComponent?.attrList.optionLabel
                        )
                    }}
                </div>
                <div v-else v-tooltip.left="slotProps.data[col.tooltip]">
                    {{ typeFormat(slotProps.data[col.field], col.dataType) }}
                </div>
            </template>
            <template #filter="{ filterModel, filterCallback }" v-if="col.filterData">
                <!--TODO: если делать filterDisplay="row" то  записи ниже добавить в компонент фильтра
                @change="filterCallback()"
                @input="filterCallback()"-->
                <component
                    v-if="currentFilters[col.field]"
                    showClear
                    :placeholder="col.filterData.placeholder ? col.filterData.placeholder : ''             "
                    :options="filterDictionary[col.filterData.options]"
                    :optionLabel="col.filterData.valueName ? col.filterData.valueName : col.field"
                    :optionValue="col.filterData.valueId ? col.filterData.valueId : col.field"
                    :key="'filter_' + col.field"
                    v-model="filterModel.value"
                    v-bind:is="col.filterData.type"
                    v-bind="col.filterData.attrList"
                    appendTo="body"
                    :style="{
                         width: '100%','max-width': col.filterData['max-width']? col.filterData['max-width']: '100%',
                         }"
                >
                </component>
            </template>
            <template #footer v-if="col.summary">
                {{ col.summary.header }} {{ summary[col.field] }}
            </template>
        </Column>
        <template #footer v-if="$slots.footer">
            <slot name="footer"></slot>
        </template>
    </DataTable>

    <ContextMenu v-if="menuModel" :model="menuModel" ref="cm"/>

    <Dialog v-model:visible="deleteDialog" :style="{ width: '550px' }" :modal="true">
        <template #header>
            <i
                class="pi pi-question-circle p-mr-3"
                :style="{ 'font-size': '2rem', color: 'rgb(204,204,0)' }"
            />
            <div class="p-col-10 p-text-bold p-text-center" style="font-size: 20px">
                Подтверждение действий
            </div>
        </template>

        <div class="confirmation-content">
            <div class="text-center">
        <span v-if="selection" :style="{ 'font-size': '1.2rem' }"
        >Удалить запись?</span
        >
            </div>
        </div>
        <template #footer>
            <Button
                label="Нет"
                icon="pi pi-times"
                class="p-button-text"
                @click="deleteDialog = false"
            />
            <Button label="Да" icon="pi pi-check" class="p-button-text" @click="deleteRow()"/>
        </template>
    </Dialog>
</template>
<script>
import Utils from "./utils.js";
import {FilterMatchMode} from "@primevue/core/api";

export default {
    /* todo: параметры
    data - массив данных
    columns - массив с описанием колонок, пример в pages/ticket/ticket_column.js
    filterDictionary - объект с массивами справочников для фильтров, имя(ключ) массива должно совпадать с описанием в columns,
        пример структуры - filters: { users: [],executors: [] }
    menuModel - контекстное меню, необязателен, пример - menuModel: [{ label: 'Редактировать', icon: 'pi pi-pencil', command: () => {код команды}},
    loading - логическое параметр, показывает загрузку грида, необязателен, по умолчанию false
    paginator, rows - если paginator - true, то rows - максимальное значение записей на одной странице, необязателен, по умолчанию false
    rowClass - поле ожидающее метод для закрашивания записей по условию, входной параметр - одиночный объект из массива data, необязателен
    selectedRow - выделенная строка, поддерживает обратное обновление(можно снять выделение)
    functionComonent - ожидает методы для компонентов(н-р button внутри полей грида), входной параметр - запись на которой расположена компонент,
        имя метода(funcName) должно совпадать с именем из columns, необязателен, пример pages/ticket/ticket_facility_column.js
    disableComponent - ожидает объект вида {"funcName": true/false}, блокировка компонентов при необходимости, необязателен
    summary - нижнее саммари, саммари должно включено в одном из columns (summary: {header: "Ср"}), передается { "наименование field": "значение" }
    */
    mixins: [Utils],
    emits: ["dblclick", "update:selectedRow", "update", "insert", "delete"],
    props: {
        data: Array,
        columns: Array,
        dictionary: {
            type: Array,
            default: () => [],
        },
        menuModel: {
            type: Array,
            default: null,
        },
        loading: {
            type: Boolean,
            default: false,
        },
        rows: {
            type: Number,
            default: 50,
        },
        rowClass: {
            type: null,
            default: null,
        },
        selectedRow: {
            type: Object,
            default: null,
        },
        paginator: {
            type: Boolean,
            default: false,
        },
        disableCopmonent: {
            type: Object,
            default: null,
        },
        functionComonent: {
            type: Object,
            default: null,
        },
        sortField: {
            default: null,
            type: String,
        },
        summary: {
            type: Object,
            default: {},
        },
        scrollHeight: {
            type: String,
            default: null,
        },
        permissions: {
            type: Object,
            default: {write: true, insert: true, delete: true},
        },

    },
    data() {
        return {
            dataType: "",
            currentFilters: {},
            deleteDialog: false,
            filterDictionary: {}
        };
    },
    created() {
        this.prepareFilters();
        this.createFilterServices(this.dictionary);
    },
    mounted() {
        this.loadFilters();
    },
    watch: {
        data() {
            this.fillData();
        }
    },
    methods: {
        prepareFilters() {
            let currentFilters = {};
            for (const column of this.columns) {
                if (column.filterData) {
                    currentFilters[column.field] = {
                        value: null,
                        matchMode: column.filterData.mode ? column.filterData.mode : FilterMatchMode.CONTAINS
                    }
                }
            }
            this.currentFilters = currentFilters;
        },
        exportCSV() {
            this.$refs.dataTable.exportCSV();
        },
        onRowClass(data) {
            let tt;
            if (this.rowClass) {
                tt = this.rowClass(data);
            }
            return tt;
        },
        onRowContextMenu(event) {
            if (this.menuModel) this.$refs.cm.show(event.originalEvent);
        },
        componentFunction(data, funcName) {
            if (this.functionComonent && this.functionComonent[funcName])
                this.functionComonent[funcName](data);
        },
        getFieldName(array, value, key, label) {
            if (!array) {
                return value;
            }
            const index = array.findIndex((item) => item[key] == value);
            if (index === -1) return "";
            return array[index][label];
        },
        updateData() {
            this.$emit("update");
        },
        insertRow() {
            this.selection = {};
            this.$emit("insert");
        },
        deleteRow() {
            this.$emit("delete");
            this.deleteDialog = false;
        },
        createFilterServices(filters) {
            for (const item of filters) {
                if (item.serviceName) {
                    //if (service) this[item.options + "Service"] = service;
                }
            }
        },
        fillData(data) {
            let selfFilters = [];
            this.dictionary.forEach((item) => {
                if (item.load === "self") {
                    selfFilters.push(item);
                    this.filterDictionary[item.options] = [];
                }
            });
            /*for (const col of this.columns) {
                if (col.conversion) {
                    data = data.map((item) => ({
                        ...item,
                        [col.field]: item[col.field] ? new Date(item[col.field]) : null,
                    }));
                }
                if (col.summary && col.summary.type) {
                    if (col.summary.type == "sum") {
                        const sum = data.reduce((sum, item) => sum + (item[col.field] || 0), 0);
                        this.summary[col.field] = sum.toFixed(2);
                    }
                    if (col.summary.type == "avg") {
                        const sum = data.reduce((sum, item) => sum + (item[col.field] || 0), 0);
                        this.summary[col.field] = (sum ? sum / data.length : 0).toFixed(2);
                    }
                    if (col.summary.type == "min") {
                        let min = data.length > 0 ? data[0][col.field] : 0;
                        for (const item of data) {
                            if (item[col.field] && item[col.field] < min) min = item[col.field];
                        }
                        this.summary[col.field] = min;
                    }
                    if (col.summary.type == "max") {
                        const max = Math.max(...data.map((item) => item[col.field]));
                        this.summary[col.field] = max || 0;
                    }
                }
            }
            this.data = data;*/
            for (const item of this.data) {
                //супер дорого
                for (const filter of selfFilters)
                    if (
                        !this.filterDictionary[filter.options].find(
                            (ar) =>
                                ar[filter.valueName || "name"] === item[filter.keyName || filter.options]
                        )
                    ) {
                        this.filterDictionary[filter.options].push({
                            [filter.valueName || "name"]: item[filter.keyName || filter.options],
                        });
                    }
            }
            /*if (this.isFirstLoad || this.entityKey) {
                this.isFirstLoad = false;
                this.routerTimer = setTimeout(() => this.ifShowDialog(), 200);
            }*/
        },
        loadFilters() {
            for (const item of this.dictionary) {
                if (this[item.options + "Service"] && item.load === "db")
                    this[item.options + "Service"].getData(item.params ?? {}).then((data) => {
                        if (item.save.store) {
                            this.$store.commit(
                                "set" + this.$store.getters.upperCaseLetter(item.options),
                                data
                            );
                        }
                        if (item.save.self) {
                            this.filterDictionary[item.options] = data;
                        }
                        this.$emit("dictIsReady", item.options, data);
                    });
                if (item.load === "store") {
                    if (item.save.self) {
                        this.filterDictionary[item.options] = this.$store.getters[
                        item.keyName || item.options
                            ];
                    }
                    this.$emit("dictIsReady", item.options, this.filterDictionary[item.options]);
                }
                if (item.load === "parent") {
                    if (item.save.self) {
                        if (typeof item.data == "function") {
                            this.filterDictionary[item.options] = item.data();
                        } else {
                            this.filterDictionary[item.options] = item.data;
                        }
                    }
                    this.$emit("dictIsReady", item.options, this.filterDictionary[item.options]);
                }
            }
        },
    },
    computed: {
        rowsPerPageOptions() {
            return [this.rows, this.rows * 2, this.rows * 3];
        },
        selection: {
            get() {
                return this.selectedRow;
            },
            set(val) {
                this.$emit("update:selectedRow", val);
            },
        },
    }
};
</script>

<style scoped lang="scss">
::v-deep(.row-accessories-blue) {
    background-color: rgba(57, 26, 196, 0.425) !important;
}

::v-deep(.row-accessories-green) {
    background-color: rgba(27, 221, 37, 0.425) !important;
}

::v-deep(.row-accessories-red) {
    background-color: rgba(221, 28, 28, 0.425) !important;
}

::v-deep(.row-accessories-yellow) {
    background-color: rgba(255, 255, 0, 0.425) !important;
}

::v-deep(.row-accessories-orange) {
    background-color: rgba(255, 128, 0, 0.425) !important;
}

.tabview-custom {
    i,
    span {
        vertical-align: middle;
    }

    s span {
        margin: 0 0.5rem;
    }
}

.image-text {
    vertical-align: middle;
}
</style>

<script setup>
</script>
