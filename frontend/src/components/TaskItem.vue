<script lang="ts">
import { defineComponent, ref, computed  } from 'vue';
import type { PropType } from 'vue';
import type { Task } from '../types';
import api from '../services/api';

export default defineComponent({
  name: 'TaskItem',
  props: {
    task: {
      type: Object as PropType<Task>,
      required: true
    }
  },
  emits: ['edit-task', 'delete-task', 'toggle-task', 'automate-task']
,
  setup(props, { emit }) {
    const showEditModal = ref(false);
    const editedDescription = ref(props.task.description);
    const editedPriority = ref(props.task.priority);

    const toggleComplete = () => {
       emit('toggle-task', { ...props.task, completed: !props.task.completed });
    };

    const automateTask = () => {
       emit('automate-task', { ...props.task});
    };

    const saveEdit = () => {
      if (!editedDescription.value.trim()) return;
      emit('edit-task', { ...props.task, description: editedDescription.value, priority: editedPriority.value });
      showEditModal.value = false;
    };

    const deleteTask = () => {
      emit('delete-task', props.task.id);
    };

    const formattedDescription = computed(() => {
      return props.task.description.replace(/(.{30})/g, '$1\n');
    });

    return { toggleComplete, showEditModal, editedDescription, editedPriority, saveEdit, deleteTask, automateTask,formattedDescription  };
  }
});
</script>

<template>
  <div class="flex items-center justify-between p-2 border-b">
    <div class="flex items-center gap-2">
      
      <input class="task-item accent-pink-500" type="checkbox" :checked="task.completed" @change="toggleComplete" />
      
      <div class="priority-wrapper">
        <span class="priority-badge" :class="task.priority">
          {{ task.priority }}
        </span>
      </div>

      <span style="white-space: pre-line; text-align: left; display: block;">
        {{ task.description}}
      </span>
    </div>

    <div class="flex gap-2">
      <div class="flex gap-2">
        <button @click="showEditModal = true">
          <div class="icon-wrapper">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 30 30" fill="currentColor" class="w-5 h-5 text-white">
              <path d="M21.731 2.269a2.625 2.625 0 0 0-3.712 0l-1.157 1.157 3.712 3.712 1.157-1.157a2.625 2.625 0 0 0 0-3.712ZM19.513 8.199l-3.712-3.712-12.15 12.15a5.25 5.25 0 0 0-1.32 2.214l-.8 2.685a.75.75 0 0 0 .933.933l2.685-.8a5.25 5.25 0 0 0 2.214-1.32L19.513 8.2Z" />
            </svg>
          </div>
        </button>
        <button @click="deleteTask">
          <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 30 30" fill="white" class="w-5 h-5">
            <path fill-rule="evenodd" d="M16.5 4.478v.227a48.816 48.816 0 0 1 3.878.512.75.75 0 1 1-.256 1.478l-.209-.035-1.005 13.07a3 3 0 0 1-2.991 2.77H8.084a3 3 0 0 1-2.991-2.77L4.087 6.66l-.209.035a.75.75 0 0 1-.256-1.478A48.567 48.567 0 0 1 7.5 4.705v-.227c0-1.564 1.213-2.9 2.816-2.951a52.662 52.662 0 0 1 3.369 0c1.603.051 2.815 1.387 2.815 2.951Zm-6.136-1.452a51.196 51.196 0 0 1 3.273 0C14.39 3.05 15 3.684 15 4.478v.113a49.488 49.488 0 0 0-6 0v-.113c0-.794.609-1.428 1.364-1.452Zm-.355 5.945a.75.75 0 1 0-1.5.058l.347 9a.75.75 0 1 0 1.499-.058l-.346-9Zm5.48.058a.75.75 0 1 0-1.498-.058l-.347 9a.75.75 0 0 0 1.5.058l.345-9Z" clip-rule="evenodd" />
          </svg>
        </button>
      </div>

      <button @click="automateTask" class="robot-btn">
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 30 30" fill="white" class="w-5 h-5">
      <path fill-rule="evenodd" d="M14.615 1.595a.75.75 0 0 1 .359.852L12.982 9.75h7.268a.75.75 0 0 1 .548 1.262l-10.5 11.25a.75.75 0 0 1-1.272-.71l1.992-7.302H3.75a.75.75 0 0 1-.548-1.262l10.5-11.25a.75.75 0 0 1 .913-.143Z" clip-rule="evenodd" />
    </svg>
      </button>
    </div>

    <div v-if="showEditModal" class="modal-overlay">
      <div class="modal-content">
        <h3>Edit Task</h3>

        <input type="text" v-model="editedDescription" placeholder="Description" class="modal-input" />

        <select v-model="editedPriority" class="modal-select">
          <option value="low">Low</option>
          <option value="medium">Medium</option>
          <option value="high">High</option>
        </select>

        <div class="modal-actions">
          <button @click="showEditModal = false" class="modal-btn cancel-btn">Cancel</button>
          <button @click="saveEdit" class="modal-btn save-btn">Save</button>
        </div>
      </div>
    </div>

  </div>
</template>
