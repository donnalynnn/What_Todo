export interface Task {
  id: number;
  description: string;
  completed: boolean;
  priority: 'low' | 'medium' | 'high';
  due_date: string;
}
