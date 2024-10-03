//import axios from "axios";
//import EventBus from "../../../core/EventBus";
export default {
    methods: {
        getFieldName(array, value, key, label) {
            if (!array) {
                return value;
            }
            const index = array.findIndex((item) => item[key] == value);
            if (index === -1) return "";
            return array[index][label];
        },
        typeFormat(value, type) {
            if (value === undefined || value === null)
                return ''
            switch (type) {
                case "date": {
                    return value.toLocaleDateString();
                }
                case "date-time": {
                    return (value.toLocaleDateString() + ' ' + value.toLocaleTimeString());
                }
                case "currency":
                    return value.toLocaleString("ru-Ru", {
                        style: "currency",
                        currency: "RUB",
                    });
                default:
                    return value;
            }
        },
        formatSqlDate: () => (date) => {
            return date === null ? null : date.getFullYear() + ("0" + (date.getMonth() + 1)).slice(-2) + ("0" + date.getDate()).slice(-2);
        },
        formatSqlDateTime: () => (date) => {
            return date == null ? null : date.getFullYear() + ("0" + (date.getMonth() + 1)).slice(-2) + ("0" + date.getDate()).slice(-2) + ("0" + date.getHours()).slice(-2) + ("0" + date.getMinutes()).slice(-2) + ("0" + date.getSeconds()).slice(-2)
        },
        dateFilter: (s, g) => (value, filter) => {
            if (
                filter === undefined ||
                filter === null ||
                (typeof filter === "string" && filter.trim() === "")
            ) {
                return true;
            }
            if (value === undefined || value === null) {
                return false;
            }
            return g.typeFormat(value, "date") === g.typeFormat(filter, "date");
        },
    }


}
