import { Component, OnInit } from '@angular/core';
import { Tasklist } from '../../models/tasklist.model';
import { TasklistService } from '../../services/tasklist.service';
import { BehaviorSubject, Observable } from 'rxjs';
import { FormControl } from '@angular/forms';

@Component({
  selector: 'app-task',
  templateUrl: './task.component.html',
  styleUrls: ['./task.component.scss']
})
export class TaskComponent implements OnInit {
  title = 'todo-listen';

  tasklists$: Observable<any>;
  numberOfTasklists$: Observable<number>;

  constructor(private tasklistService: TasklistService) {

  }
  ngOnInit() {
    this.tasklistService.loadTasklists();

    this.tasklists$ = this.tasklistService.getTasklists();
    this.numberOfTasklists$ = this.tasklistService.getTotalNumberOfProducts();
  }
  printTasks() {
    console.log(this.tasklistService.tasklists$.getValue());
  }
}
