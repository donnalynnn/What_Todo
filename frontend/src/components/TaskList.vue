<script lang="ts">
import { defineComponent } from 'vue';
import type { Task } from '../types';
import TaskItem from './TaskItem.vue';

export default defineComponent({
  name: 'TaskList',
  components: { TaskItem },
  props: {
    tasks: { type: Array as () => Task[], required: true },
  },
  emits: ['edit-task', 'delete-task', 'toggle-task'],

  setup(_, { emit }) {
    const forwardEdit = (task: Task) => emit('edit-task', task);
    const forwardDelete = (id: number) => emit('delete-task', id);
    const forwardToggle = (task: Task) => emit('toggle-task', task);

    return { forwardEdit, forwardDelete, forwardToggle };
  }
});
</script>

<template>
  <div class="task-list-container">
    <TaskItem 
      v-for="task in tasks" 
      :key="task.id" 
      :task="task" 
      @edit-task="$emit('edit-task', $event)" 
      @delete-task="$emit('delete-task', $event)" 
      @toggle-task="$emit('toggle-task', $event)"
    />
  </div>
</template>
