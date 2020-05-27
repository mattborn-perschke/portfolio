import { Component, OnInit } from '@angular/core';
import { Tasklist } from './models/tasklist.model';
import { TasklistService } from './services/tasklist.service';
import { BehaviorSubject, Observable } from 'rxjs';

@Component({
  selector: 'app-root',
  templateUrl: './app.component.html',
  styleUrls: ['./app.component.scss']
})
export class AppComponent implements OnInit {
  title = 'todo-listen';

  tasklists$: Observable<Tasklist[]>;
  numberOfTasklists$: Observable<number>;

  constructor(private tasklistService: TasklistService) {

  }
  ngOnInit() {
    this.tasklistService.loadTasklists();

    this.tasklists$ = this.tasklistService.getTasklists();
    this.numberOfTasklists$ = this.tasklistService.getTotalNumberOfProducts();
  }
  printTasks() {
    console.log(this.tasklistService.tasklists$.getValue().length);
  }
}
