import axios from "axios";
import app from "../main";
import router from "../router";
import store from "../store";

// Создайте экземпляр axios
const api = axios.create({
    // baseURL: process.env.VUE_APP_DEVOPS_URL
});

// Функция для обновления токена
const refreshToken = async () => {
    try {
        const response = await axios.post(
            "/api/auth/refresh",
            {},
            {
                headers: {
                    Authorization: `Bearer ${localStorage.getItem(
                        "access_token"
                    )}`,
                    Accept: "application/json",
                },
            }
        );
        localStorage.setItem("access_token", response.data.access_token);
        return response.data.access_token;
    } catch (error) {
        localStorage.removeItem("access_token");
        await router.push("/login");
        throw error;
    }
};

// Интерцептор запроса
api.interceptors.request.use((config) => {
        const token = localStorage.getItem("access_token");
    if (token) {
        config.headers = {
            Authorization: `Bearer ${token}`,
            Accept: "application/json",
        };
    } else {
        config.headers = { Accept: "application/json" };
    }

    store.commit("SET_LOADING", true);
    return config;
    },
    (error) => {
        app.config.globalProperties.$notification(
            error?.response?.data?.message || error?.message,
            {
                type: "error",
                timeout: 15000,
            }
        );
        return Promise.reject(error);
    });

// Интерцептор ответа
api.interceptors.response.use(
    (response) => {
        store.commit("SET_LOADING", false);
        return response;
    },
    async (error) => {
        store.commit("SET_LOADING", true);

        const originalRequest = error.config;

        // Если ошибка 401 и токен можно обновить
        if (error.response.status === 401 && !originalRequest._retry) {
            originalRequest._retry = true;
            try {
                const newToken = await refreshToken();
                // Установите новый токен в заголовки
                api.defaults.headers.common[
                    "Authorization"
                ] = `Bearer ${newToken}`;
                // Повторите запрос с новым токеном
                originalRequest.headers["Authorization"] = `Bearer ${newToken}`;
                return api(originalRequest);
            } catch (err) {
                app.config.globalProperties.$notification(
                    "Ваш токен доступа больше недействителен",
                    {
                        type: "error",
                        timeout: 15000,
                    }
                );
                localStorage.removeItem("access_token");
                await router.push("/login");
            }
        } else if (error.response.status === 404) {
            app.config.globalProperties.$notification(
                "Данной страницы не существует",
                { type: "error", timeout: 15000 }
            );
        } else {
            app.config.globalProperties.$notification(
                error?.response?.data?.message || error?.message,
                {
                    type: "error",
                    timeout: 15000,
                }
            );
        }

        store.commit("SET_LOADING", false);
        return Promise.reject(error);
    }
);

export default api;
