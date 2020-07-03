import { Injectable } from '@angular/core';
import { BehaviorSubject, Observable } from 'rxjs';
import { HttpClient } from '@angular/common/http';

@Injectable({
  providedIn: 'root'
})
export class LoginService {
  user$: BehaviorSubject<string> = new BehaviorSubject('Gast');
  userId$: BehaviorSubject<string> = new BehaviorSubject('');
  loginStatus$: BehaviorSubject<boolean> = new BehaviorSubject(false);
  response$: Observable<any>;
  response: any;

  constructor(private http: HttpClient) {}

  async performLogin(username, password) {
    let success = false;
    this.response$ = await this.http.get('http://localhost:4200/api/portfolio/public/login/'
                         + username + '/' + password);
    const myObserver = {
      next: x => {
        this.user$.next(x[0].name) ;
        this.userId$.next(x[0].id);
        this.loginStatus$.next(true);
        success = true;
      },
      error: err => {
        this.loginStatus$.next(false);
        },
      };
    this.response$.subscribe(myObserver);
    return success;
  }

  logout() {
    this.user$.next('Gast');
    this.loginStatus$.next(false);
  }

  getUser() {
    return this.user$;
  }
}
