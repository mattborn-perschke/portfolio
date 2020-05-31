import { Routes, RouterModule } from '@angular/router';
import { NgModule } from '@angular/core';


import {HomeComponent} from './components/home/home.component';
import {TaskComponent} from './components/task/task.component';
import {LoginComponent} from './components/login/login.component';
import {NewtaskComponent} from './components/newtask/newtask.component';

const routes: Routes = [
    { path: '', component: HomeComponent }, //path ist leer, lädt als erstes
    { path: 'task', component: TaskComponent },
    { path: 'login', component: LoginComponent },
    { path: 'newtask', component: NewtaskComponent },
]

@NgModule({
    imports: [RouterModule.forRoot(routes)],
    exports: [RouterModule]
})
export class AppRoutingModule {}