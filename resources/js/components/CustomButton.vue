<template>
    <button :type="type"
        :class="['custom-button', buttonClass, { 'p-button-icon-only': icon && !label }]"
        @click="$emit('click', $event)"
    >
        <span v-if="icon" :class="['p-button-icon', icon]"></span>
        <span v-if="label" class="p-button-label">{{ label }}</span>
    </button>
</template>

<script>
export default {
    name: 'CustomButton',
    props: {
        label: {
            type: String,
            default: null,
        },
        icon: {
            type: String,
            default: null,
        },
        color: {
            type: String,
            default: 'primary',
            validator: (value) => ['primary', 'secondary', 'success', 'info', 'warning', 'danger', 'dark', 'light'].includes(value),
        },
        outlined: {
            type: Boolean,
            default: false,
        },
        rounded: {
            type: Boolean,
            default: false,
        },
        type: {
            type: String,
            default: 'button',
          validator: (value) => ['button', 'reset', 'submit'].includes(value)
        }
    },
    computed: {
        buttonClass() {
            return {
                'p-button-primary': this.color === 'primary' && !this.outlined,
                'p-button-secondary': this.color === 'secondary' && !this.outlined,
                'p-button-success': this.color === 'success' && !this.outlined,
                'p-button-info': this.color === 'info' && !this.outlined,
                'p-button-warning': this.color === 'warning' && !this.outlined,
                'p-button-danger': this.color === 'danger' && !this.outlined,
                'p-button-dark': this.color === 'dark' && !this.outlined,
                'p-button-light': this.color === 'light' && !this.outlined,
                'p-button-outlined p-button-primary': this.color === 'primary' && this.outlined,
                'p-button-outlined p-button-secondary': this.color === 'secondary' && this.outlined,
                'p-button-outlined p-button-success': this.color === 'success' && this.outlined,
                'p-button-outlined p-button-info': this.color === 'info' && this.outlined,
                'p-button-outlined p-button-warning': this.color === 'warning' && this.outlined,
                'p-button-outlined p-button-danger': this.color === 'danger' && this.outlined,
                'p-button-outlined p-button-dark': this.color === 'dark' && this.outlined,
                'p-button-outlined p-button-light': this.color === 'light' && this.outlined,
                'p-button-rounded': this.rounded,
            };
        },
    },
};
</script>

<style scoped>
.custom-button {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    font-size: 1rem;
    padding: 0.5rem 1rem;
    border: none;
    border-radius: 0.25rem;
    cursor: pointer;
    transition: background-color 0.3s, color 0.3s;
}
.p-button-primary {
    background-color: #007bff;
    color: #fff;
}
.p-button-secondary {
    background-color: #6c757d;
    color: #fff;
}
.p-button-success {
    background-color: #28a745;
    color: #fff;
}
.p-button-info {
    background-color: #17a2b8;
    color: #fff;
}
.p-button-warning {
    background-color: #ffc107;
    color: #212529;
}
.p-button-danger {
    background-color: #dc3545;
    color: #fff;
}
.p-button-dark {
    background-color: #343a40;
    color: #fff;
}
.p-button-light {
    background-color: #f8f9fa;
    color: #212529;
}
.p-button-outlined {
    background-color: transparent;
    border: 1px solid;
}
.p-button-outlined.p-button-primary {
    color: #007bff;
    border-color: #007bff;
}
.p-button-outlined.p-button-secondary {
    color: var(--gray);
    border-color: var(--gray);
}
.p-button-outlined.p-button-success {
    color: var(--green-500);
    border-color: var(--green-500);
}
.p-button-outlined.p-button-info {
    color: var(--blue);
    border-color: var(--blue);
}
.p-button-outlined.p-button-warning {
    color: var(--yellow);
    border-color: var(--yellow);
}
.p-button-outlined.p-button-danger {
    color: var(--red-500);
    border-color: var(--red-500);
}
.p-button-outlined.p-button-dark {
    color: var(--text-color);
    border-color: var(--text-color);
}
.p-button-outlined.p-button-light {
    color: #f8f9fa;
    border-color: #f8f9fa;
}
.p-button-rounded {
    border-radius: 50px;
}
.p-button-icon {
    margin-right: 0.5rem;
}
.p-button-icon-only .p-button-icon {
    margin-right: 0;
}
</style>
