import { Injectable } from '@angular/core';
import { BehaviorSubject, combineLatest, Observable } from 'rxjs';
import { map } from 'rxjs/operators';
import { HttpClient } from '@angular/common/http';
import { Tasklist } from '../models/tasklist.model';
import { Task } from '../models/task.model';
import { LoginService } from './login.service';

export const getAllTasklists = () => {
  return map(([tasklists]:
                [Tasklist[]]) => {
    return tasklists;
  });
};

export const transformTasklists = () => {
  return map((tasklists: any[]) => {
    return tasklists.map(tasklist => {
      if (tasklist.status !== null) {
        const returnTask: Task = {
          id: tasklist.id,
          name: tasklist.name,
          status: tasklist.status,
          weight: tasklist.gewichtung,
          date: tasklist.zeitpunkt,
        };
        return returnTask;
      }
      const tasks = tasklist.ownAufgabe;
      const comtasks: Task[] = [];
      if (tasklist.ownAufgabe !== undefined) {
        for (const t of tasks) {
          const task: Task = {
            name: t.titel,
            date: t.zeitpunkt,
            status: t.status,
            weight: t.gewichtung,
            id: t.id
          };
          comtasks.push(task);
        }
      }
      const returnValue = {
        // ...tasklist,
        id: tasklist.id,
        name: tasklist.titel,
        tasks: comtasks,
        owner: tasklist.benutzer.name,
        status: tasklist.status
      };
      return returnValue;
    });
  });
};

@Injectable({
  providedIn: 'root'
})
export class TasklistService {
  tasklists$: BehaviorSubject<any> = new BehaviorSubject(null);
  tasklist$: BehaviorSubject<any> = new BehaviorSubject(null);

  constructor(private loginService: LoginService, private http: HttpClient) {
  }
  async loadTasklistsSorted(nach: string, value: string) {
    if (this.loginService.loginStatus$.getValue()) {
      const tasklists = await this.http.get<Tasklist[]>('http://localhost:4200/api/portfolio/public/aufgaben/'
        + nach + '/' +  value + '/'
        + this.loginService.userId$.getValue()).pipe(
        transformTasklists()).toPromise();
      this.tasklists$.next(tasklists);
    } else {
      this.tasklists$.next([]);
    }
  }
  async loadTasklistsFiltered(nach: string, value: string) {
    if (this.loginService.loginStatus$.getValue()) {
      const tasklists = await this.http.get<Tasklist[]>('http://localhost:4200/api/portfolio/public/aufgaben/'
        + nach + '/filter/' + value + '/'
        + this.loginService.userId$.getValue()).pipe(
        transformTasklists()).toPromise();
      this.tasklists$.next(tasklists);
    } else {
      this.tasklists$.next([]);
    }
  }
  async loadTasklists() {
    if (this.loginService.loginStatus$.getValue()) {
      const tasklists = await this.http.get<Tasklist[]>('http://localhost:4200/api/portfolio/public/aufgabenlisten/'
        + this.loginService.userId$.getValue()).pipe(
        transformTasklists()).toPromise();
      this.tasklists$.next(tasklists);
    } else {
      this.tasklists$.next([]);
    }
  }
  getTotalNumberOfProducts(): Observable<number> {
    return this.tasklists$.pipe(
      map(tasklists => (tasklists ? tasklists.length : 0))
    );
  }
  getTasklists(): Observable<any> {
    return combineLatest(this.tasklists$).pipe(
      getAllTasklists()
    );
  }
}
