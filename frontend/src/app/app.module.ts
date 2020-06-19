import { BrowserModule } from '@angular/platform-browser';
import { NgModule } from '@angular/core';
import { HttpClientModule } from '@angular/common/http';

import { AppComponent } from './app.component';
import { ListComponent } from './components/list/list.component';
import { TaskComponent } from './components/task/task.component';
import { HomeComponent } from './components/home/home.component';
import { TasklistService } from './services/tasklist.service';
import { ListContainerComponent } from './components/list-container/list-container.component';
import { ScrollingModule } from '@angular/cdk/scrolling';
import { BrowserAnimationsModule } from '@angular/platform-browser/animations';
import { MatCardModule } from '@angular/material/card';
import { MatToolbarModule } from '@angular/material/toolbar';
import { MatButtonModule } from '@angular/material/button';
import { NgbModule } from '@ng-bootstrap/ng-bootstrap';
import { NgChunkPipeModule } from 'angular-pipes';
import { FontAwesomeModule, FaIconLibrary } from '@fortawesome/angular-fontawesome';
import { fas } from '@fortawesome/free-solid-svg-icons';
import { far } from '@fortawesome/free-regular-svg-icons';
import { fab } from '@fortawesome/free-brands-svg-icons';
import { RouterModule } from '@angular/router';
import { AppRoutingModule } from './app-routing.module';
import { LoginComponent } from './components/login/login.component';
import { NewtaskComponent } from './components/newtask/newtask.component';


@NgModule({
  declarations: [
    AppComponent,
    ListComponent,
    TaskComponent,
    ListContainerComponent,
    HomeComponent,
    LoginComponent,
    NewtaskComponent
  ],
  imports: [
    BrowserModule,
    HttpClientModule,
    ScrollingModule,
    BrowserAnimationsModule,
    NgChunkPipeModule,
    MatCardModule,
    MatToolbarModule,
    MatButtonModule,
    FontAwesomeModule,
    NgbModule,
    RouterModule,
    AppRoutingModule
  ],
  providers: [HttpClientModule],
  bootstrap: [AppComponent]
})
export class AppModule {
    constructor(library: FaIconLibrary) {
        library.addIconPacks(fas,
                    far,
                    fab);
  }
}