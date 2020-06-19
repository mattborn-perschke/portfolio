import { Input, Component, OnInit } from '@angular/core';
import { Tasklist } from '../../models/tasklist.model';

@Component({
  selector: 'app-list-container',
  templateUrl: './list-container.component.html',
  styleUrls: ['./list-container.component.scss']
})
export class ListContainerComponent implements OnInit {
  @Input() count: number;
  @Input() tasklists: Tasklist[];

  tasklistgroups: Tasklist[][];

  constructor() { }

  ngOnInit(): void {
    }
  printTasks() {
    console.log(this.tasklists, this.count, 'hiers');
  }

}
