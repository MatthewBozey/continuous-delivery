<template>
    <div
        class="card cursor-pointer relative"
        draggable
        @dragstart="$emit('dragstart', task)"
    >
        <div class="card-header">
            <div class="card-title">{{ task.title }}</div>
            <div class="card-description">{{ task.description }}</div>
        </div>
        <div class="card-footer">
            <span class="badge">{{ task.status }}</span>
            <button @click="$emit('info', task)" class="btn-icon absolute top-2 right-2"></button>
        </div>
    </div>
</template>

<script>

export default {
    props: {
        task: Object
    },
    components: {}
};
</script>

<style scoped>
.card {
    background: #ffffff;
    border-radius: 0.5rem;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    padding: 1rem;
    user-select: none;
}

.card-header {
    margin-bottom: 0.5rem;
}

.card-title {
    font-weight: bold;
    font-size: 1.125rem;
}

.card-description {
    font-size: 0.875rem;
}

.card-footer {
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.badge {
    border: 1px solid #007bff;
    border-radius: 0.25rem;
    padding: 0.25rem 0.5rem;
    font-size: 0.875rem;
}
</style>
