import { Component, OnInit } from '@angular/core';
import { BehaviorSubject } from 'rxjs';
import { LoginService } from '../../services/login.service';

@Component({
  selector: 'app-home',
  templateUrl: './home.component.html',
  styleUrls: ['./home.component.scss']
})
export class HomeComponent implements OnInit {

  user$: BehaviorSubject<string>;
  loginStatus$: BehaviorSubject<boolean>;
  constructor(loginService: LoginService) {
    this.user$ = loginService.user$;
    this.loginStatus$ = loginService.loginStatus$;
  }

  ngOnInit(): void {
  }

}
