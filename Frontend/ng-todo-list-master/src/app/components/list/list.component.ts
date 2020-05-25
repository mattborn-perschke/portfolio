import { Input, Component, OnInit } from '@angular/core';
import { Tasklist } from '../../models/tasklist.model';
import { Task } from '../../models/task.model';

@Component({
  selector: 'app-list',
  templateUrl: './list.component.html',
  styleUrls: ['./list.component.scss']
})
export class ListComponent implements OnInit {
  @Input() tasklist: Tasklist;

  constructor() { }
  name = this.tasklist.id;

  ngOnInit(): void {
    console.log(this.tasklist, 'test');
  }

}
