import { Task } from './task.model';
export interface Tasklist {
    id: string;
    tasks: Task[];
    owner: string;
    status: boolean;
}

