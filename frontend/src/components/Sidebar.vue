<script lang="ts">
import { defineComponent, ref, onMounted } from "vue";

export default defineComponent({
  name: "Sidebar",
  emits: ["filter-change"],
  setup(_, { emit }) {
    const selectedPriority = ref("all");
    const selectedDate = ref<Date | null>(null);

    const today = new Date();

    const emitFilters = () => {
      emit("filter-change", {
        priority: selectedPriority.value,
        date: (selectedDate.value ?? today).toISOString().split("T")[0], 
      });
    };

    const setPriority = (priority: string) => {
      selectedPriority.value = priority;
      emitFilters();
    };

    const onDateChange = (date: Date | null) => {
      selectedDate.value = date;
      emitFilters();
    };

    const setToday = () => {
      selectedDate.value = today;
      emitFilters();
    };

    onMounted(() => {
      if (!selectedDate.value) {
        selectedDate.value = today;
        emitFilters();
      }
    });

    return {
      selectedPriority,
      selectedDate,
      setPriority,
      onDateChange,
      setToday,
    };
  },
});
</script>

<template>
  <div class="sidebar-container">

    <div class="priority-filter-container mb-6 mt-2 text-center">
  <h3 class="text-sm font-semibold text-white-700 mb-2">Priority Filter:</h3>
  <div class="flex justify-center space-x-2">
    <button
      @click="setPriority('all')"
      :class="['priority-btn', selectedPriority === 'all' ? 'active' : '']"
    >
      All
    </button>
    <button
      @click="setPriority('low')"
      :class="['priority-btn', selectedPriority === 'low' ? 'active' : '']"
    >
      Low
    </button>
    <button
      @click="setPriority('medium')"
      :class="['priority-btn', selectedPriority === 'medium' ? 'active' : '']"
    >
      Medium
    </button>
    <button
      @click="setPriority('high')"
      :class="['priority-btn', selectedPriority === 'high' ? 'active' : '']"
    >
      High
    </button>
  </div>
</div>


    <!-- Calendar Section -->
    <div class="bg-white p-3 rounded-xl shadow">
      <div class="sidebar-calendar">
        <v-date-picker
          v-model="selectedDate"
          is-inline
          @update:modelValue="onDateChange"
          color="light-pink" 

          :style="{
            '--vc-highlight-solid-bg': '#f687b3',
            '--vc-highlight-solid-content-color': '#ffffff',
            '--vc-day-content-hover-bg': '#F8DCE7',
            '--vc-header-arrow-color': '#ffffff',
            '--vc-header-title-color': '#ffffff',
            '--vc-color': '#ffffff',
            '--vc-focus-ring': '#f687b3',
            '--vc-header-arrow-hover-bg':'#BDA4AE',
            '--vc-border':'#FFFFFF00',

          }"

        />
      </div>

      <button
        @click="setToday"
        class="mt-2 px-3 py-1 text-white rounded text-xs"
      >
        Today
      </button>
    </div>

    <!-- Priority Filter -->
    

    <img src="../assets/light-cat.png" alt="light-cat" class="sidebar-img" />
  </div>
</template>
