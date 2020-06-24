import { Component, OnInit } from '@angular/core';
import { LoginService } from '../../services/login.service';
import { Router } from '@angular/router';

@Component({
  selector: 'app-login',
  templateUrl: './login.component.html',
  styleUrls: ['./login.component.scss']
})
export class LoginComponent implements OnInit {

  constructor(private loginService: LoginService, private router: Router) {
  }

  ngOnInit(): void {
  }

  doLogin(username, password) {
    const success = this.loginService.performLogin(username, password);
    console.log(success);
    if (success) {
      this.router.navigate(['/']);
    }
  }

}
