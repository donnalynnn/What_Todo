<script lang="ts">
import { defineComponent, ref, watch } from "vue";
import TaskList from "../components/TaskList.vue";
import type { Task } from "../types";
import api from "../services/api";
import { computed } from "vue";
export default defineComponent({
  name: "Home",
  components: { TaskList },
  props: {
    filters: {
      type: Object as () => { date: string | null; priority: string | null },
      required: true,
    },
  },
  setup(props) {
    const tasks = ref<Task[]>([]);
    const newTaskDescription = ref("");
    const newTaskPriority = ref<"low" | "medium" | "high">("medium");
    
    // const today = new Date().toISOString().split("T")[0]; // "YYYY-MM-DD"
    // const selectedDate = ref(today);


    const formattedDate = computed(() => {
      const dateStr = props.filters.date; 
      if (!dateStr) return ""; 
      const dateObj = new Date(dateStr);
      return dateObj.toLocaleDateString("en-US", {
        month: "long",
        day: "numeric",
      });
    });



    const fetchTasks = async () => {
      try {
        const response = await api.get("/tasks", {
          params: {
            date: props.filters?.date,      
            priority: props.filters?.priority,
          },
        });
        tasks.value = response.data;
      } catch (err) {
        console.error("Error fetching tasks:", err);
      }
    };

    const addTask = async () => {
      if (!newTaskDescription.value || !props.filters.date) return;

      try {
        const response = await api.post("/tasks", {
          description: newTaskDescription.value,
          priority: newTaskPriority.value,
          due_date: props.filters.date,
          completed: false,
        });
        tasks.value.push(response.data);
        newTaskDescription.value = "";
      } catch (err) {
        console.error("Error adding task:", err);
      }
    };

    const editTask = async (task: Task) => {
      try {
        const response = await api.put(`/tasks/${task.id}`, {
          description: task.description,
          priority: task.priority,
        });
        const index = tasks.value.findIndex((t) => t.id === task.id);
        if (index !== -1) tasks.value[index] = response.data;
      } catch (err) {
        console.error("Error editing task:", err);
      }
    };

    const deleteTask = async (taskId: number) => {
      try {
        await api.delete(`/tasks/${taskId}`);
        tasks.value = tasks.value.filter((t) => t.id !== taskId);
      } catch (err) {
        console.error("Error deleting task:", err);
      }
    };

    const toggleTask = async (task: Task) => {
      try {
        const response = await api.put(`/tasks/${task.id}`, {
          completed: task.completed,
        });
        task.completed = response.data.completed;
      } catch (err) {
        console.error("Error toggling task:", err);
      }
    };

    const automateTask = async (task: Task) => {
      try {
        const response = await api.post("/automation/task", {
          task_id: task.id,
          description: task.description,
          date: props.filters.date, 
        });

        console.log("Automation response:", response.data);
      } catch (err) {
        console.error("Error sending task to Make.com:", err);
      }
    };

  
    watch(
      () => props.filters,
      () => {
        fetchTasks();
      },
      { deep: true, immediate: true }
    );

    return {
      tasks,
      newTaskDescription,
      newTaskPriority,
      addTask,
      editTask,
      deleteTask,
      toggleTask,
      formattedDate,
      automateTask
    };
  },
});
</script>

<template>
  <div class="home-container">
    
    <img src="../assets/what-todo.png" alt="What Todo" class="w-94 mb-4" />

    <p class="subheader">
      <span class="date">{{ formattedDate }}</span>
      |
      <span class="prompt">WHAT ARE WE DOING TODAY?</span>
    </p>

    <div class="flex gap-2 mb-4">
  <input
      v-model="newTaskDescription"
      type="text"
      placeholder="Enter task description"
      class="home-input rounded-lg px-4 py-3 text-base flex-1"
    />
    <select
      v-model="newTaskPriority"
      class="home-select rounded-lg px-3 py-2 text-sm"
    >
      <option value="low">Low</option>
      <option value="medium">Medium</option>
      <option value="high">High</option>
    </select>
    <button
      @click="addTask"
      class="add-task-btn"
    >
      Add Task
    </button>
  </div>

    <TaskList
      :tasks="tasks"
      @edit-task="editTask"
      @delete-task="deleteTask"
      @toggle-task="toggleTask"
      @automate-task="automateTask"

    />
  </div>
</template>

