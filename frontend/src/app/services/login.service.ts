import { Injectable } from '@angular/core';
import { BehaviorSubject, Observable } from 'rxjs';
import { HttpClient } from '@angular/common/http';

@Injectable({
  providedIn: 'root'
})
export class LoginService {
  user$: BehaviorSubject<string> = new BehaviorSubject('Gast');
  loginStatus$: BehaviorSubject<boolean> = new BehaviorSubject(false);
  response$: Observable<any>;
  response: any;

  constructor(private http: HttpClient) {}

  performLogin(username, password) {
    this.response$ = this.http.get('http://192.168.178.45/portfolio/public/login/'
                         + username + '/' + password);
    const myObserver = {
      next: x => {
        this.user$.next(x[0].name) ;
        this.loginStatus$.next(true);
      },
      error: err => {
        this.loginStatus$.next(false);
        },
      };

    this.response$.subscribe(myObserver);
  }

  logout() {
    this.user$.next('Gast');
    this.loginStatus$.next(false);
  }

  getUser() {
    return this.user$;
  }
}
