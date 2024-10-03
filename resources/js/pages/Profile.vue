<template>
    <div class="p-2 gap-3">
        <div class="card p-3 shadow-2">
            <div
                class="flex flex-nowrap relative justify-end align-top p-1 text-h5"
            >
                <div class="absolute">
                    <i
                        class="ri-pencil-line cursor-pointer"
                        v-if="!this.editingProfileFields"
                        @click.prevent="editingProfileFieldsHandle"
                    />
                    <i
                        class="ri-close-line text-red cursor-pointer"
                        v-else-if="this.editingProfileFields"
                        @click.prevent="editingProfileFieldsHandle"
                    />
                </div>
            </div>

            <div class="card-header gap-2 p-1 m-2">
                <h2>Профиль</h2>
                <span class="text-sm"
                    >Обновление информации о Вашем профиле</span
                >
            </div>

            <div class="card-body p-4">
                <form @submit.prevent="profileFormSubmit">
                    <div class="field m-1">
                        <label for="" class="">Аватар</label>
                        <div class="w-full grid h-25">
                            <Skeleton
                                height="25rem"
                                width="25%"
                                v-show="firstLoading"
                            ></Skeleton>
                            <Avatar
                                :class="[
                                    'm-2',
                                    {
                                        'h-25 w-25 corner-radius-2':
                                            form.avatar_url,
                                    },
                                ]"
                                size="xlarge"
                                shape="square"
                                v-show="!firstLoading"
                                :label="
                                    form.avatar_url
                                        ? null
                                        : form.last_name[0] + form.first_name[0]
                                "
                                :image="form.avatar_url"
                                :icon="
                                    form.avatar_url ? '' : 'ri-user-smile-line'
                                "
                            />
                            <div
                                class="flex flex-wrap align-center justify-center m-1"
                                v-show="editingProfileFields"
                            >
                                <div
                                    class="field p-1"
                                    v-show="editingProfileFields"
                                >
                                    <h6 class="text-center">
                                        Загрузить новый аватар
                                    </h6>
                                    <FileUpload
                                        mode="basic"
                                        name="demo[]"
                                        accept="image/*"
                                        :maxFileSize="1000000"
                                        choose-label="Выберите файл для загрузки"
                                        upload-icon="ri-image-add-fill"
                                        :custom-upload="true"
                                        @select="onFileSelectHandle"
                                        @clear="onFileDeleteHandle"
                                        class="p-button-outlined p-button-success w-full m-1"
                                    />
                                    <CustomButton
                                        type="button"
                                        label="Удалить аватар"
                                        color="danger"
                                        class="w-full m-1"
                                        outlined
                                        v-show="
                                            form.avatar_url !== null ||
                                            form.avatar_url !== ''
                                        "
                                        icon="ri-delete-bin-2-line"
                                        @click="deleteAvatarHandle($event)"
                                    />
                                </div>
                            </div>
                        </div>
                        <small
                            v-if="form.invalid('username')"
                            class="text-red"
                            >{{ form.errors.username }}</small
                        >
                    </div>

                    <div class="field m-1">
                        <label for="" class="">Логин</label>
                        <Skeleton
                            height="2rem"
                            class="mb-2 w-full"
                            v-show="firstLoading"
                        ></Skeleton>
                        <InputText
                            v-model="form.username"
                            v-show="!firstLoading"
                            :invalid="form.invalid('username')"
                            :class="[
                                'w-full',
                                { 'p-invalid': this.form.invalid('username') },
                            ]"
                            :loading="loading || form.validating"
                            @change="form.validate('username')"
                            :disabled="!editingProfileFields"
                        />
                        <small
                            v-if="form.invalid('username')"
                            class="text-red"
                            >{{ form.errors.username }}</small
                        >
                    </div>

                    <div class="field m-1">
                        <label for="" class="">Фамилия</label>
                        <Skeleton
                            height="2rem"
                            class="mb-2 w-full"
                            v-show="firstLoading"
                        ></Skeleton>
                        <InputText
                            v-model="form.last_name"
                            class="w-full"
                            v-show="!firstLoading"
                            :disabled="!editingProfileFields"
                            :loading="loading"
                        />
                        <small
                            v-if="form.invalid('last_name')"
                            class="text-red"
                            >{{ form.errors.last_name }}</small
                        >
                    </div>

                    <div class="field m-1">
                        <label for="" class="">Имя</label>
                        <Skeleton
                            height="2rem"
                            class="mb-2 w-full"
                            v-show="firstLoading"
                        ></Skeleton>
                        <InputText
                            v-model="form.first_name"
                            class="w-full"
                            v-show="!firstLoading"
                            :disabled="!editingProfileFields"
                            :loading="loading"
                        />
                        <small
                            v-if="form.invalid('first_name')"
                            class="text-red"
                            >{{ form.errors.first_name }}</small
                        >
                    </div>

                    <div class="field m-1">
                        <label for="" class="">Отчество</label>
                        <Skeleton
                            height="2rem"
                            class="mb-2 w-full"
                            v-show="firstLoading"
                        ></Skeleton>
                        <InputText
                            v-model="form.patronymic"
                            class="w-full"
                            v-show="!firstLoading"
                            :disabled="!editingProfileFields"
                            :loading="loading"
                        />
                        <small
                            v-if="form.invalid('patronymic')"
                            class="text-red"
                            >{{ form.errors.patronymic }}</small
                        >
                    </div>

                    <div class="field m-1">
                        <label for="" class="">Электронная почта</label>
                        <Skeleton
                            height="2rem"
                            class="mb-2 w-full"
                            v-show="firstLoading"
                        ></Skeleton>
                        <InputText
                            v-model="form.email"
                            class="w-full"
                            v-show="!firstLoading"
                            :disabled="!editingProfileFields"
                            :loading="loading"
                        />
                        <small v-if="form.invalid('email')" class="text-red">{{
                            form.errors.email
                        }}</small>
                    </div>

                    <div
                        class="grid gap-2 justify-end m-1"
                        id="editingProfileButtonContainer"
                        v-show="editingProfileFields && showProfileFormButton"
                    >
                        <CustomButton
                            label="Сохранить изменения"
                            color="success"
                            outlined
                            @click="profileFormSubmit($event)"
                        />
                    </div>
                </form>
            </div>
        </div>

        <!--        Настройки аккаунта-->
        <div class="card p-3">
            <div class="card-header gap-1 p-1">
                <h2>Аккаунт</h2>
                <span class="text-sm">Управление настройками аккаунта.</span>
            </div>
            <div class="card-body p-4">
                <div class="grid gap-2 align-center m-1">
                    <label for="" class="w-25">Пароль</label>
                    <div class="col">
                        <Skeleton
                            height="2.5rem"
                            width="100%"
                            v-show="firstLoading"
                        ></Skeleton>
                        <Password
                            v-model="passwords.newPassword"
                            class="w-full"
                            width="100%"
                            v-show="!firstLoading"
                        >
                            <template #header><h6>Введите пароль</h6></template>
                            <template #footer>
                                <Divider />
                                <ul class="pl-2 ml-2 mt-1 list-none">
                                    <li
                                        :class="{
                                            'text-green': criteria.length,
                                        }"
                                    >
                                        Минимум 8 символов
                                    </li>
                                    <li
                                        :class="{
                                            'text-green': criteria.uppercase,
                                        }"
                                    >
                                        Как минимум одна заглавная буква
                                    </li>
                                    <li
                                        :class="{
                                            'text-green': criteria.lowercase,
                                        }"
                                    >
                                        Как минимум одна строчная буква
                                    </li>
                                    <li
                                        :class="{
                                            'text-green': criteria.number,
                                        }"
                                    >
                                        Как минимум одна цифра
                                    </li>
                                    <li
                                        :class="{
                                            'text-green': criteria.special,
                                        }"
                                    >
                                        Как минимум один специальный символ
                                    </li>
                                </ul>
                            </template>
                        </Password>
                    </div>
                </div>
                <div
                    class="grid gap-2 align-center m-1"
                    v-show="passwordCriteria"
                >
                    <label for="" class="w-25">Подтверждение пароля</label>
                    <div class="col">
                        <Password
                            v-model="passwords.newPassword_confirmation"
                            class="w-full"
                            width="100%"
                        >
                            <template #footer>
                                <Divider />
                                <h6
                                    class="text-red"
                                    v-show="!resetPasswordButtonVisible"
                                >
                                    Пароли не совпадают
                                </h6>
                            </template>
                        </Password>
                    </div>
                </div>
                <div
                    class="grid gap-2 justify-end m-1"
                    v-show="resetPasswordButtonVisible"
                >
                    <CustomButton
                        label="Сбросить пароль"
                        color="dark"
                        outlined
                        @click="resetPasswordHandle"
                    />
                </div>

                <div class="grid gap-2 align-center m-1">
                    <label for="" class="w-25">
                        <i class="ri-telegram-line"></i> Telegram
                    </label>
                    <div class="col">
                        <!-- Показываем скелетон, если данные еще загружаются -->
                        <Skeleton
                            height="2.5rem"
                            width="100%"
                            v-show="firstLoading"
                        ></Skeleton>

                        <!-- Если Telegram не привязан, показываем кнопку для привязки -->
                        <CustomButton
                            v-if="!firstLoading && !user?.telegram"
                            class=""
                            color="success"
                            label="Привязать аккаунт"
                            icon="ri-link"
                            outlined
                            @click="linkUserTelegramHandle"
                        />

                        <!-- Если Telegram привязан, показываем кнопку для отвязки -->
                        <CustomButton
                            v-else-if="!firstLoading && user?.telegram"
                            class=""
                            color="danger"
                            label="Отвязать аккаунт"
                            icon="ri-link-unlink"
                            outlined
                            @click.prevent="unlinkUserTelegramHandle"
                        />
                    </div>
                </div>
            </div>

            <div class="card-header gap-1 p-1">
                <h2>Конфиденциальность</h2>
                <span class="text-sm"
                    >Управление настройками конфиденциальности.</span
                >
            </div>

            <div class="card-body p-2 gap-3">
                <div class="grid gap-2 align-center m-2">
                    <h5><i class="ri-mail-open-line"></i> Электронная почта</h5>
                </div>

                <div class="grid gap-2 align-center ml-10 mt-2">
                  <ToggleSwitch
                        id="data-sharing"
                        v-model="user.notify_password_reset_via_email"
                        :disabled="!user.email || loading"
                        @change="
                            settingsChangeHandle(
                                'notify_password_reset_via_email',
                                user.notify_password_reset_via_email
                            )
                        "
                    />
                    <Label for="data-sharing"
                        >Разрешить уведомления о сбросе пароля</Label
                    >
                </div>
            </div>

            <div class="card-body p-2 gap-3">
                <div class="grid gap-2 align-center m-2">
                    <h5><i class="ri-telegram-2-line"></i> Telegram</h5>
                </div>

                <div class="grid gap-2 align-center ml-10 mt-2">
                  <ToggleSwitch
                        id="data-sharing"
                        v-model="user.notify_password_reset_via_telegram"
                        :disabled="!user.telegram || loading"
                        @change="
                            settingsChangeHandle(
                                'notify_password_reset_via_telegram',
                                user?.notify_password_reset_via_telegram
                            )
                        "
                    />
                    <Label for="data-sharing"
                        >Разрешить уведомления о сбросе пароля</Label
                    >
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import CustomButton from "../components/CustomButton.vue";
import { mapActions, mapGetters, mapMutations } from "vuex";
import { useForm } from "laravel-precognition-vue";

export default {
    components: { CustomButton },
    data() {
        return {
            editingProfileFields: false,
            firstLoading: false,
            form: useForm("put", "api/users/update-info", {
                username: "",
                last_name: "",
                first_name: "",
                patronymic: "",
                email: "",
                avatar: "",
                avatar_url: "",
                user_id: null,
            }),
            avatarHistory: null,
            passwords: {
                newPassword: null,
                newPassword_confirmation: null,
            },
        };
    },

    setup() {},
    computed: {
        ...mapGetters(["user", "changedUserInfo", "loading"]),

        disableProfileFormButton() {
            return this.form.hasErrors;
        },

        showProfileFormButton() {
            return (
                this.form.username !== this.changedUserInfo.username ||
                this.form.first_name !== this.changedUserInfo.first_name ||
                this.form.last_name !== this.changedUserInfo.last_name ||
                this.form.patronymic !== this.changedUserInfo.patronymic ||
                this.form.email !== this.changedUserInfo.email ||
                this.form.avatar_url !== this.avatarHistory
            );
        },

        confirmPasswordVisible() {
            return (
                this.passwords.newPassword !== null &&
                this.passwords.newPassword !== ""
            );
        },

        criteria() {
            const length = this.passwords.newPassword?.length >= 8 ?? false;
            const uppercase = /[A-Z]/.test(this.passwords.newPassword);
            const lowercase = /[a-z]/.test(this.passwords.newPassword);
            const number = /[0-9]/.test(this.passwords.newPassword);
            const special = /[!@#$%^&*(),.?":{}|<>]/.test(
                this.passwords.newPassword
            );

            return { length, uppercase, lowercase, number, special };
        },

        passwordCriteria() {
            const { length, uppercase, lowercase, number, special } =
                this.criteria;
            return length && uppercase && lowercase && number && special;
        },

        resetPasswordButtonVisible() {
            return (
                this.passwords.newPassword ===
                    this.passwords.newPassword_confirmation &&
                this.passwords.newPassword !== null &&
                this.passwords.newPassword !== ""
            );
        },
    },
    created() {
        this.firstLoading = true;
    },
    mounted() {
        this.$devops_api
            .get(
                "api/users/get-info?user_id=" + localStorage.getItem("user_id")
            )
            .then((value) => {
                this.saveChangedUserInfo(value.data.data);
                this.saveUser(value.data.data);
                this.form = useForm(
                    "post",
                    "api/users/update-info",
                    value.data.data
                );
                this.avatarHistory = value.data?.data?.avatar_url;
            })
            .catch((reason) => this.showError(reason))
            .finally(() => (this.firstLoading = false));
    },
    methods: {
        ...mapMutations(["saveChangedUserInfo", "saveUser"]),
        ...mapActions(["showError"]),

        editingProfileFieldsHandle() {
            this.editingProfileFields = !this.editingProfileFields;
        },

        profileFormSubmit() {
            this.$confirm.require({
                header: "Подтвердите изменения данных",
                message: `Вы собираетесь изменить свои данные. Пожалуйста, убедитесь, что вся введённая информация корректна перед сохранением. Вы действительно хотите продолжить?`,
                position: "bottom",
                accept: () => {
                    const formData = new FormData();
                    formData.append("username", this.form.username);
                    formData.append("first_name", this.form.first_name);
                    formData.append("last_name", this.form.last_name);
                    formData.append("patronymic", this.form.patronymic);
                    formData.append("email", this.form.email);
                    formData.append("user_id", this.form.user_id);
                    if (
                        this.form.avatar_url !== this.avatarHistory &&
                        this.form.avatar !== null
                    ) {
                        formData.append(
                            "avatar",
                            this.form.avatar,
                            this.form.avatar.name
                        );
                    }
                    this.$devops_api
                        .post("/api/users/update-info", formData)
                        .then((value) => {
                            this.form = useForm(
                                "post",
                                "api/users/update-info",
                                value.data.data
                            );
                            this.saveUser(value.data.data);
                            this.saveChangedUserInfo(value.data.data);
                            this.avatarHistory = value.data.data.avatar_url;
                            this.$notification.success(
                                "Данные успешно обновлены"
                            );
                            this.editingProfileFields = false;
                        })
                        .catch((reason) => this.showError(reason));
                },
                onHide: () => {},
            });
        },

        deleteAvatarHandle(event) {
            this.$confirm.require({
                target: event.target,
                header: "Подтвердите удаление аватара",
                message: `Вы собираетесь свой аватар. Вы действительно хотите продолжить?`,
                position: "bottom",
                accept: () => {
                    this.$devops_api
                        .delete(
                            `api/users/delete-avatar-users?user_id=${localStorage.getItem(
                                "user_id"
                            )}`
                        )
                        .then((value) => {
                            this.saveUser(value.data.data);
                            this.saveChangedUserInfo(value.data.data);
                            this.form = useForm(
                                "post",
                                "api/users/update-info",
                                value.data.data
                            );
                            this.$notification.success("Аватар успешно удален");
                        });
                },
                onHide: () => {},
            });
        },

        onFileSelectHandle(event) {
            this.form.avatar = event.files[0];
            this.form.avatar_url = URL.createObjectURL(this.form.avatar);
        },

        onFileDeleteHandle() {
            this.form.avatar_url = this.avatarHistory;
        },

        resetPasswordHandle(event) {
            this.$confirm.require({
                target: event.target,
                header: "Подтвердите сброс пароля",
                message: `Вы собираетесь свой пароль. Вы действительно хотите продолжить?`,
                position: "bottom",
                accept: () => {
                    this.$devops_api
                        .post("api/users/reset-user-password", {
                            ...this.passwords,
                            user_id: localStorage.getItem("user_id"),
                        })
                        .then((value) => {
                            /*localStorage.setItem(
                                "access_token",
                                value.data.data.token
                            );*/
                            this.passwords = {
                                newPassword: null,
                                newPassword_confirmation: null,
                            };
                            this.$notification.success("Пароль сброшен");
                        })
                        .catch((reason) => this.showError(reason));
                },
                onHide: () => {},
            });
        },

        settingsChangeHandle(code, value) {
            this.$devops_api
                .put("api/users/save-user-setting", {
                    code: code,
                    value: value,
                    user_id: localStorage.getItem("user_id"),
                })
                .then((response) => {
                    this.saveUser(response.data.data);
                    this.saveChangedUserInfo(response.data.data);
                    this.form = useForm(
                        "post",
                        "api/users/update-info",
                        response.data.data
                    );
                    this.$notification.success("Значение сохранено");
                })
                .catch((reason) => this.showError(reason));
        },

        unlinkUserTelegramHandle(event) {
            this.$confirm.require({
                target: event.target,
                header: "Подтвердите отвязку аккаунта",
                message: `Вы собираетесь отвязать аккаунт Telegram. Вы действительно хотите продолжить?`,
                position: "bottom",
                accept: () => {
                    this.$devops_api
                        .put("api/user-telegram/unlink", {})
                        .then((value) => {
                            this.saveUser(value.data.data);
                            this.saveChangedUserInfo(value.data.data);
                        })
                        .catch((reason) => this.showError(reason));
                },
                onHide: () => {},
            });
        },
        linkUserTelegramHandle(event) {
            this.$confirm.require({
                target: event.target,
                header: "Подтвердите привязку аккаунта",
                message: `Вы собираетесь привязать аккаунт Telegram. Вы действительно хотите продолжить?`,
                position: "bottom",
                accept: () => {
                    this.$devops_api
                        .post("api/user-telegram/link", {})
                        .then((value) => {
                            const telegramUrl = value.data?.data?.telegram_url;
                            if (telegramUrl) {
                                window.open(telegramUrl, "_blank");
                            }
                        })
                        .catch((reason) => this.showError(reason));
                },
                onHide: () => {},
            });
        },
    },
};
</script>

<style scoped></style>
