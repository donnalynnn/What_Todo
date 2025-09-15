import api from "./api";
import type { Task } from "../types";

export const getTasks = async (): Promise<Task[]> => {
  const res = await api.get("/tasks");
  return res.data;
};

export const addTask = async (newTask: Partial<Task>): Promise<Task> => {
  const res = await api.post("/tasks", newTask);
  return res.data;
};

export const updateTask = async (task: Task): Promise<Task> => {
  const res = await api.put(`/tasks/${task.id}`, task);
  return res.data;
};

export const deleteTask = async (id: number): Promise<void> => {
  await api.delete(`/tasks/${id}`);
};
