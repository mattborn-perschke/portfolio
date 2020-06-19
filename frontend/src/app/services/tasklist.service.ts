import { Injectable } from '@angular/core';
import { BehaviorSubject, combineLatest, Observable } from 'rxjs';
import { map } from 'rxjs/operators';
import { HttpClient } from '@angular/common/http';
import { Tasklist } from '../models/tasklist.model';
import { Task } from '../models/task.model';

export const getAllTasklists = () => {
  return map(([tasklists]:
              [Tasklist[]]) => {
          return tasklists;
      });
};

export const transformTasklists = () => {
    return map((tasklists: any[]) => {
        return tasklists.map(tasklist => {
          const tasks = tasklist[1];
          const comtasks: Task[] = [];
          for (const t of tasks) {
            const task: Task = {
              name: t[0],
              date: t[1],
              status: t[2],
              weight: t[3]
            };
            comtasks.push(task);
          }
          return {
              ...tasklist,
              id: tasklist[0],
              tasks: comtasks,
              owner: tasklist[2],
              status: tasklist[3]
          };
        });
    });
};

@Injectable({
  providedIn: 'root'
})
export class TasklistService {
    tasklists$: BehaviorSubject<Tasklist[]> = new BehaviorSubject(null);
    tasklist$: BehaviorSubject<Tasklist[]> = new BehaviorSubject(null);

    constructor(private http: HttpClient) {
    }

  async loadTasklists() {
      const tasklists = await this.http
          .get<Tasklist[]>('/assets/tasklistmock.json')
          .pipe(
              transformTasklists()
          )
          .toPromise();
      this.tasklists$.next(tasklists);
  }
  getTotalNumberOfProducts(): Observable<number> {
      return this.tasklists$.pipe(
          map(tasklists => (tasklists ? tasklists.length : 0))
      );
  }
   getTasklists(): Observable<Tasklist[]> {
        return combineLatest(this.tasklists$).pipe(
                getAllTasklists()
            );
    }
}
