import { Input, Component, OnInit } from '@angular/core';
import { Tasklist } from '../../models/tasklist.model';
import { Task } from '../../models/task.model';
import { HttpClient } from '@angular/common/http';
import { TasklistService } from '../../services/tasklist.service';

@Component({
  selector: 'app-list',
  templateUrl: './list.component.html',
  styleUrls: ['./list.component.scss']
})
export class ListComponent implements OnInit {
  @Input() tasklist: Tasklist;

  constructor(private http: HttpClient, private tasklistService: TasklistService) { }

  name = null;
  id = null;

  ngOnInit(): void {
    this.id = this.tasklist.id;
    this.name = this.tasklist.name;
  }

  async delete() {
    await this.http.delete('http://localhost/portfolio/public/aufgabenlisten/' + this.id)
    .subscribe((response) => {
    });
    await this.delay(600);
    this.tasklistService.loadTasklists();
  }
  delay(ms: number) {
    return new Promise( resolve => setTimeout(resolve, ms) );
  }

}
