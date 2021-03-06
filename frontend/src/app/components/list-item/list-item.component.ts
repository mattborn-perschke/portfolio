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

  async delete() {
    this.http.delete('http://localhost:4200/api/portfolio/public/aufgaben/' + this.task.id)
    .subscribe((response) => {
    });
    await this.delay(600);
    this.tasklistService.loadTasklists();
  }
  delay(ms: number) {
    return new Promise( resolve => setTimeout(resolve, ms) );
  }
}
