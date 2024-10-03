import { definePreset } from '@primevue/themes'
import '../css/tailwind.css'
import 'primeflex/primeflex.css'
import 'primeicons/primeicons.css'
import 'prismjs/themes/prism-coy.css'
import './assets/styles/layout.scss'
import 'vue-toastification/dist/index.css'
import devops_api from './service/devops_api'
import { createApp } from 'vue'
import store from './store'
import router from './router'
import AppWrapper from './AppWrapper.vue'
import Accordion from 'primevue/accordion'
import AutoComplete from 'primevue/autocomplete'
import Avatar from 'primevue/avatar'
import AvatarGroup from 'primevue/avatargroup'
import Badge from 'primevue/badge'
import BadgeDirective from 'primevue/badgedirective'
import Breadcrumb from 'primevue/breadcrumb'
import Button from 'primevue/button'
import Card from 'primevue/card'
import Carousel from 'primevue/carousel'
import Checkbox from 'primevue/checkbox'
import Chip from 'primevue/chip'
import ColorPicker from 'primevue/colorpicker'
import Column from 'primevue/column'
import ColumnGroup from 'primevue/columngroup'
import ConfirmationService from 'primevue/confirmationservice'
import ConfirmDialog from 'primevue/confirmdialog'
import ConfirmPopup from 'primevue/confirmpopup'
import ContextMenu from 'primevue/contextmenu'
import DataTable from 'primevue/datatable'
import DataView from 'primevue/dataview'
import DatePicker from 'primevue/datepicker'
import Dialog from 'primevue/dialog'
import DialogService from 'primevue/dialogservice'
import Divider from 'primevue/divider'
import DynamicDialog from 'primevue/dynamicdialog'
import Drawer from 'primevue/drawer'
import Fieldset from 'primevue/fieldset'
import FloatLabel from 'primevue/floatlabel'
import FileUpload from 'primevue/fileupload'
import Galleria from 'primevue/galleria'
import Image from 'primevue/image'
import Inplace from 'primevue/inplace'
import InputGroup from 'primevue/inputgroup'
import InputGroupAddon from 'primevue/inputgroupaddon'
import InputMask from 'primevue/inputmask'
import InputNumber from 'primevue/inputnumber'
import InputText from 'primevue/inputtext'
import Knob from 'primevue/knob'
import Listbox from 'primevue/listbox'
import MegaMenu from 'primevue/megamenu'
import Menu from 'primevue/menu'
import Menubar from 'primevue/menubar'
import Message from 'primevue/message'
import MultiSelect from 'primevue/multiselect'
import OrderList from 'primevue/orderlist'
import Paginator from 'primevue/paginator'
import Panel from 'primevue/panel'
import PanelMenu from 'primevue/panelmenu'
import Password from 'primevue/password'
import PickList from 'primevue/picklist'
import PrimeVue from 'primevue/config'
import Popover from 'primevue/popover'
import ProgressBar from 'primevue/progressbar'
import RadioButton from 'primevue/radiobutton'
import Rating from 'primevue/rating'
import Ripple from 'primevue/ripple'
import Row from 'primevue/row'
import ScrollPanel from 'primevue/scrollpanel'
import ScrollTop from 'primevue/scrolltop'
import Select from 'primevue/select'
import SelectButton from 'primevue/selectbutton'
import Skeleton from 'primevue/skeleton'
import Slider from 'primevue/slider'
import SplitButton from 'primevue/splitbutton'
import Splitter from 'primevue/splitter'
import SplitterPanel from 'primevue/splitterpanel'
import Steps from 'primevue/steps'
import StyleClass from 'primevue/styleclass'
import TabMenu from 'primevue/tabmenu'
import TabPanel from 'primevue/tabpanel'
import Tag from 'primevue/tag'
import Textarea from 'primevue/textarea'
import TieredMenu from 'primevue/tieredmenu'
import Timeline from 'primevue/timeline'
import Toast from 'primevue/toast'
import ToastService from 'primevue/toastservice'
import ToggleButton from 'primevue/togglebutton'
import ToggleSwitch from 'primevue/toggleswitch'
import Toolbar from 'primevue/toolbar'
import Tooltip from 'primevue/tooltip'
import Tree from 'primevue/tree'
import TreeSelect from 'primevue/treeselect'
import TreeTable from 'primevue/treetable'

import CodeHighlight from './AppCodeHighlight'
import BlockViewer from './BlockViewer'

import Aura from '@primevue/themes/lara'
import moment from 'moment'
import VueDisableAutocomplete from 'vue-disable-autocomplete'
import Toasting, { useToast } from 'vue-toastification'
import Login from './pages/Login'
import Application from './App'
import Vue3Tour from 'vue3-tour'
import pusher from './service/pusher'
import VueHighlightJS from 'vue3-highlightjs'
import './assets/styles/highlight.css'
import CustomMarkdown from './components/CustomMarkdown'

// Vuetify
import 'vuetify/styles'
import { createVuetify } from 'vuetify'
import * as components from 'vuetify/components'
import * as directives from 'vuetify/directives'

import '@fortawesome/fontawesome-free/css/all.css'
import 'remixicon/fonts/remixicon.css'
import { client } from 'laravel-precognition-vue'

require("./bootstrap.js");

const vuetify = createVuetify({
    components,
    directives,
});

router.beforeEach(function (to, from, next) {
    window.scrollTo(0, 0);
    next();
});

moment.locale("ru_RU")

const app = createApp(AppWrapper);

app.config.globalProperties.$devops_api = devops_api;

client.axios().defaults.headers.common["Authorization"] =
    "Bearer " + localStorage.getItem("access_token");

app.config.globalProperties.check_permission = (perm) => {
    return window.localStorage.getItem("permissions").includes(perm) || false;
};
app.config.globalProperties.hexToRgbA = (hex, opacity = 1) => {
    let c;
    if(/^#([A-Fa-f0-9]{3}){1,2}$/.test(hex)){
        c= hex.substring(1).split('');
        if(c.length=== 3){
            c= [c[0], c[0], c[1], c[1], c[2], c[2]];
        }
        c= '0x'+c.join('');
        return 'rgba('+[(c>>16)&255, (c>>8)&255, c&255].join(',')+', ' + opacity + ')';
    }
    throw new Error('Bad Hex');
}

const presets = definePreset(Aura, {
    semantic: {
        primary: {
            50: '{zinc.50}',
            100: '{zinc.100}',
            200: '{zinc.200}',
            300: '{zinc.300}',
            400: '{zinc.400}',
            500: '{zinc.500}',
            600: '{zinc.600}',
            700: '{zinc.700}',
            800: '{zinc.800}',
            900: '{zinc.900}',
            950: '{zinc.950}'
        },
        colorScheme: {
            light: {
                primary: {
                    color: '{zinc.950}',
                    inverseColor: '#ffffff',
                    hoverColor: '{zinc.900}',
                    activeColor: '{zinc.800}'
                },
                highlight: {
                    background: '{zinc.950}',
                    focusBackground: '{zinc.700}',
                    color: '#ffffff',
                    focusColor: '#ffffff'
                }
            },
            dark: {
                primary: {
                    color: '{zinc.50}',
                    inverseColor: '{zinc.950}',
                    hoverColor: '{zinc.100}',
                    activeColor: '{zinc.200}'
                },
                highlight: {
                    background: 'rgba(250, 250, 250, .16)',
                    focusBackground: 'rgba(250, 250, 250, .24)',
                    color: 'rgba(255,255,255,.87)',
                    focusColor: 'rgba(255,255,255,.87)'
                }
            }
        }
    }
});
app.use(PrimeVue, {
    unstyled: false,
    theme: {
        preset: presets,
        options: {
            darkModeSelector: ".app-dark",
        },
    },
    locale: {
        startsWith: "Начинается с",
        contains: "Содержит",
        notContains: "Не содержит",
        endsWith: "Заканчивается с",
        equals: "Равно",
        notEquals: "Не равно",
        noFilter: "Без фильтра",
        lt: "Меньше чем",
        lte: "Меньше чем или рано",
        gt: "Больше чем",
        gte: "Больше чем или равно",
        dateIs: "Date is",
        dateIsNot: "Date is not",
        dateBefore: "Date is before",
        dateAfter: "Date is after",
        clear: "Очистить",
        apply: "Применить",
        matchAll: "Match All",
        matchAny: "Match Any",
        addRule: "Добавить правило",
        removeRule: "Удалить правило",
        accept: "Да",
        reject: "Нет",
        choose: "Выбор",
        upload: "Загрузка",
        cancel: "Отказ",
        dayNames: [
            "Воскресенье",
            "Понедельник",
            "Вторник",
            "Среда",
            "Четверг",
            "Пятница",
            "Суббота",
        ],
        dayNamesShort: ["Вос", "Пон", "Вто", "Сре", "Чет", "Пят", "Суб"],
        dayNamesMin: ["ВС", "ПН", "ВТ", "СР", "ЧТ", "ПТ", "СБ"],
        monthNames: [
            "Январь",
            "Февраль",
            "Март",
            "Апрель",
            "Май",
            "Июнь",
            "Июль",
            "Август",
            "Сентябрь",
            "Октябрь",
            "Ноябрь",
            "Декабрь",
        ],
        monthNamesShort: [
            "Янв",
            "Фев",
            "Мар",
            "Апр",
            "Май",
            "Июн",
            "Июл",
            "Авг",
            "Сен",
            "Окт",
            "Ноя",
            "Дек",
        ],
        today: "Сегодня",
        weekHeader: "Wk",
        firstDayOfWeek: 0,
        dateFormat: "mm/dd/yy",
        weak: "Слабый",
        medium: "Средний",
        strong: "Сильный",
        passwordPrompt: "Введите пароль",
        emptyFilterMessage: "Результатов нет",
        emptyMessage: "Нет доступных опций",
        selectionMessage: "Выбрано элементов: {0}",
    },
});
app.use(ConfirmationService);
app.use(ToastService);
app.use(DialogService);
app.use(VueDisableAutocomplete);
app.use(router);
app.use(store);

app.use(Toasting, {
    transition: "Vue-Toastification__bounce",
    maxToasts: 30,
    newestOnTop: true,
    timeout: 15000,
    closeButton: false,
    closeOnClick: false,
})

require('vue3-tour/dist/vue3-tour.css');
app.use(Vue3Tour, {});

app.use(VueHighlightJS, {});
app.use(pusher);
app.use(vuetify);

app.config.globalProperties.$notification = useToast();
app.config.globalProperties.$moment = moment;
app.config.globalProperties.$pusher = pusher;

app.directive('tooltip', Tooltip);
app.directive('ripple', Ripple);
app.directive('code', CodeHighlight);
app.directive('badge', BadgeDirective);
app.directive('styleclass', StyleClass);

app.component("CustomMarkdown", CustomMarkdown);
app.component('Accordion', Accordion);
app.component('Application', Application);
app.component('AutoComplete', AutoComplete);
app.component('Avatar', Avatar);
app.component('AvatarGroup', AvatarGroup);
app.component('Badge', Badge);
app.component('Breadcrumb', Breadcrumb);
app.component('Button', Button);
app.component('Card', Card);
app.component('Carousel', Carousel);
app.component('Checkbox', Checkbox);
app.component('Chip', Chip);
app.component('ColorPicker', ColorPicker);
app.component('Column', Column);
app.component("ColumnGroup", ColumnGroup);
app.component("ConfirmDialog", ConfirmDialog);
app.component("ConfirmPopup", ConfirmPopup);
app.component("ContextMenu", ContextMenu);
app.component("DataTable", DataTable);
app.component("DataView", DataView);
app.component("DatePicker", DatePicker);
app.component("Dialog", Dialog);
app.component("Divider", Divider);
app.component("Drawer", Drawer);
app.component("DynamicDialog", DynamicDialog);
app.component("Fieldset", Fieldset);
app.component("FloatLabel", FloatLabel);
app.component("FileUpload", FileUpload);
app.component("Galleria", Galleria);
app.component("Image", Image);
app.component("Inplace", Inplace);
app.component("InputGroup", InputGroup);
app.component("InputGroupAddon", InputGroupAddon);
app.component('InputMask', InputMask);
app.component('InputNumber', InputNumber);
app.component('InputText', InputText);
app.component('Knob', Knob);
app.component('Listbox', Listbox);
app.component('login', Login);
app.component('MegaMenu', MegaMenu);
app.component('Menu', Menu);
app.component('Menubar', Menubar);
app.component('Message', Message);
app.component('MultiSelect', MultiSelect);
app.component('OrderList', OrderList);
app.component('Paginator', Paginator);
app.component('Panel', Panel);
app.component("PanelMenu", PanelMenu);
app.component("Password", Password);
app.component("PickList", PickList);
app.component("ProgressBar", ProgressBar);
app.component("RadioButton", RadioButton);
app.component("Popover", Popover);
app.component("Rating", Rating);
app.component("Row", Row);
app.component("ScrollPanel", ScrollPanel);
app.component("ScrollTop", ScrollTop);
app.component("Select", Select);
app.component("SelectButton", SelectButton);
app.component("Skeleton", Skeleton);
app.component("Slider", Slider);
app.component('SplitButton', SplitButton);
app.component('Splitter', Splitter);
app.component('SplitterPanel', SplitterPanel);
app.component('Steps', Steps);
app.component('TabMenu', TabMenu);
app.component("TabPanel", TabPanel);
app.component("Tag", Tag);
app.component("Textarea", Textarea);
app.component("TieredMenu", TieredMenu);
app.component("Timeline", Timeline);
app.component("Toast", Toast);
app.component("ToggleButton", ToggleButton);
app.component("ToggleSwitch", ToggleSwitch);
app.component('Toolbar', Toolbar);
app.component('Tree', Tree);
app.component('TreeSelect', TreeSelect);
app.component('TreeTable', TreeTable);

app.component('BlockViewer', BlockViewer);


app.mount('#app');

export default app;
