import { Component, OnInit } from '@angular/core';
import { Tasklist } from './models/tasklist.model';
import { TasklistService } from './services/tasklist.service';
import { LoginService } from './services/login.service';
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
  user$: BehaviorSubject<string>;
  loginStatus$: BehaviorSubject<boolean>;
  user: string;

  constructor(private tasklistService: TasklistService, private loginService: LoginService) {
    this.user$ = loginService.user$;
    this.loginStatus$ = loginService.loginStatus$;
  }
  ngOnInit(): void {
  }

  logout(): void {
    this.loginService.logout();
  }

}
