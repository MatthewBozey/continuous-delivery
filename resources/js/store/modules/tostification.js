import {useToast} from 'vue-toastification'

const toast = useToast()

export default {
    state: {
        message: "",
        settings: {}
    },
    mutations: {
        showToast(state, data) {
            state.message = data.message || "Пустое сообщение";
            state.settings = data
        }
    },
    actions: {
        showToast(state, data) {
            // state.commit("showToast", data)
            toast(data.message, {
                id: data.id ?? "auto",
                type: data.type ?? "default",
                position: data.position ?? "top-right",
                draggable: data.draggable ?? true,
                draggablePercent: data.draggablePercent ?? 0.6,
                pauseOnFocusLoss: data.pauseOnFocusLoss ?? true,
                pauseOnHover: data.pauseOnHover ?? true,
                closeOnClick: data.closeOnClick ?? true,
                timeout: data.timeout ?? 5000,
                toastClassName: data.toastClassName ?? [],
                bodyClassName: data.bodyClassName ?? [],
                hideProgressBar: data.hideProgressBar ?? false,
                hideCloseButton: data.hideCloseButton ?? false,
                icon: data.icon ?? true,
                closeButton: data.closeButton ?? false,
                onClick: data.onClick ?? function () {
                }
            })
        },
        showError(state, data) {
            if (data.error_list && data.error_list.length > 0) {
                for (const datum of data.error_list) {
                    toast(datum, {
                        type: "error",
                        draggable: false,
                        pauseOnHover: true,
                        pauseOnFocusLoss: false,
                        closeOnClick: false,
                        timeout: 15000,
                        hideCloseButton: true,
                        closeButton: false
                    })
                }
            } else {
                toast(data.message ?? data.error, {
                    type: "error",
                    draggable: false,
                    pauseOnHover: true,
                    pauseOnFocusLoss: false,
                    closeOnClick: false,
                    timeout: 15000,
                    hideCloseButton: true,
                    closeButton: false
                })
            }
            // state.commit("showToast", data)
        }
    }
}
