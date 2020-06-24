import { Task } from './task.model';
export interface Tasklist {
    id: string;
    name: string;
    tasks: Task[];
    owner: string;
    status: boolean;
}

