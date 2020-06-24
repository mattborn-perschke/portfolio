import { Input, Component, OnInit } from '@angular/core';
import { Task } from '../../models/task.model';
import { HttpClient } from '@angular/common/http';
import { TasklistService } from '../../services/tasklist.service';

@Component({
  selector: 'app-list-item',
  templateUrl: './list-item.component.html',
  styleUrls: ['./list-item.component.scss']
})
export class ListItemComponent implements OnInit {
  @Input() task: Task;

  constructor(private http: HttpClient, private tasklistService: TasklistService) { }

  ngOnInit(): void {
  }

  delete() {
    this.http.delete('http://localhost/portfolio/public/aufgaben/' + this.task.id)
    .subscribe((response) => {
    });
    this.tasklistService.loadTasklists();
  }
}
