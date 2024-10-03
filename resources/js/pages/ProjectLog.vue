<script>
import AppTopBar from "../AppTopbar.vue";
import AppConfig from "../AppConfig.vue";

export default {
    name: 'ProjectLog',
    components: {AppConfig, AppTopBar},
    data() {
        return {
            selectedProjectLog: [],
            showProjectLogList: true,
        }
    },
    mounted() {
        this.$devops_api.get('api/project-log/listbox')
            .then(value => {
                this.$store.dispatch('saveProjectLog', value.data.data);
                this.$store.dispatch('saveProjectLogColumns', value.data.metadata.columns);
                if((this.$store.getters.projectLogSelectedColumns.length ?? 0) === 0) {
                    this.$store.dispatch('saveProjectLogSelectedColumns', value.data.metadata.columnIds);
                }
                this.onColumnsToggle(this.$store.getters.projectLogSelectedColumns);
            })
            .catch(reason => this.$store.dispatch("showError", reason));
    },
    computed: {
        projectLog() {
            return this.$store.getters.hiddenProjectLog;
        },

        projectLogColumns() {
            return this.$store.getters.projectLogColumns;
        },

        projectLogSelectedColumns: {

            get() {
                return this.$store.getters.projectLogSelectedColumns;
            },

            set(projectLogSelectedColumns) {
                return this.$store.dispatch('saveProjectLogSelectedColumns', projectLogSelectedColumns);
            }
        }
    },
    methods: {
        getOptionColor(data) {
            switch (data?.project_log_status_id) {
                case 2:
                    return 'green';
                case 3:
                    return 'red';
                default:
                    return 'black';
            }
        },

        onColumnsToggle(val){
            const data = this.$store.getters.projectLog;
            const filteredData = data.filter(item => val.includes(item.project_config_id));
            this.$store.dispatch('saveHiddenProjectLog', filteredData);
        }
    },
};
</script>

<template>
    <div class="">
        <AppTopBar @menu-toggle="() => this.showProjectLogList = !this.showProjectLogList"/>
        <div class="project-log-sidebar" v-show="showProjectLogList">
            <div class="d-flex">
                <Button class="text-center" @click="$router.push('dashboard')">Назад</Button>
                <MultiSelect v-model="projectLogSelectedColumns" :options="projectLogColumns"
                             optionLabel="project_config_title" option-value="project_config_id" @update:model-value="onColumnsToggle"
                             placeholder="Выберите поля для отображения" selectedItemsLabel="{0} элементов выбрано"
                             :maxSelectedLabels="2" class="w-full ml-1"  />
            </div>
            <Listbox v-model="selectedProjectLog" :options="projectLog"
                     optionValue="project_log_id" listStyle="height:65vh" filter filter-placeholder="Введите поле для поиска"
                     :pt="{item: {style: {margin: '1rem'}}}" :filterFields="['project_log_id', 'project_config_title']"
                     class="w-full h-fit mt-1 min-h-6">
                <template #option="slotProps">
                    <span v-tooltip="`${slotProps.option.project_log_start}`"
                        :style="{color: getOptionColor(slotProps.option)}">{{ slotProps?.option?.project_log_id }} {{ slotProps?.option.project_config_title }}</span>
                </template>
            </Listbox>
        </div>
    </div>
</template>

<style scoped>
.project-log-sidebar {
    position: fixed;
    width: 30vw;
    height: calc(100vh - 9rem);
    z-index: 999;
    overflow-y: auto;
    top: 7rem;
    left: 2rem;
    transition: transform $transitionDuration, left $transitionDuration;
    background-color: var(--surface-overlay);
    border-radius: 12px;
    padding: 1.5rem;
    box-shadow: 0px 3px 5px rgba(0, 0, 0, .02), 0px 0px 2px rgba(0, 0, 0, .05), 0px 1px 4px rgba(0, 0, 0, .08)
}

</style>
