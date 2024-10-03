<template>

    <div class="grid">
        <template v-for="(component, index) of components" :key="component.field + index">
            <label :for="component.field + index" class="col-5 font-semibold w-24 mt-2">{{ component.name }}:</label>
            <component :is="getComponentByName(component.type)" v-model="rowData[component.field]"
                       :id="component.field + index"
                       :class="['col-7', 'mt-2',  getControlClass(component.field, component.type)]"
                       v-bind="getAttrList(component.type, component.attrList)"/>
        </template>
    </div>
</template>
<script>
export default {
    name: "CustomComponentsGrid",
    props: {
        components: {
            default: []
        },
        modelValue: {
            type: Object,
            default: {}
        },
        requiredFields: {
            default: []
        },
        submitRowDialog: {
            type: Boolean,
        },
    },
    data() {
        return {}
    },
    methods: {
        checkValidate() {
            for (const key of this.requiredFields) {
                const className = this.getControlClass(key);
                //todo:  по дурацки
                if (className.includes('p-invalid')) {
                    return false;
                }
            }
            return true;
        },
        getComponentByName(type) {
            switch (type) {
                case 'text':
                    return 'InputText';
                case 'number':
                    return 'InputNumber';
                case 'float':
                    return 'InputNumber';
                case 'list':
                    return 'Dropdown';
                case 'multiselect':
                    return 'MultiSelect';
                case 'checkbox':
                    return 'Checkbox';
                default:
                    return 'InputText';
            }
        },
        getAttrList(type, attrList) {
            let result = attrList ?? {};
            switch (type) {
                case 'text':
                    result = {...result};
                    break;
                case 'number':
                    result = {...{min: 0, showButtons: true}, ...result};
                    break;
                case 'float':
                    result = {...{minFractionDigits: 2, step: 0.01}, ...result};
                    break;
                case 'list':
                    result = {...result};
                    break;
                case 'multiselect':
                    result = {...result};
                    break;
                case 'checkbox':
                    result = {...{binary: true}, ...result};
                    break;
                default:
                    result = {...result};
                    break;
            }
            return result;
        },
        getControlClass(field, type) {
            const required = this.requiredFields.indexOf(field) > -1
            let result = required ? 'p-required' : '';
            if (type !== 'checkbox') result = result + ' pt-0 pb-0';
            if (this.submitRowDialog && required) {
                result = result + ((this.rowData[field] || '') == '' ? ' p-invalid' : '');
            }
            return result
        },
    },
    computed: {
        rowData: {
            get() {
                return this.modelValue;
            },
            set(val) {
                this.$emit("update:modelValue", val);
            },
        },
    }
}
</script>
