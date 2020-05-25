import { BrowserModule } from '@angular/platform-browser';
import { NgModule } from '@angular/core';
import { HttpClientModule } from '@angular/common/http';

import { AppComponent } from './app.component';
import { ListComponent } from './components/list/list.component';
import { TaskComponent } from './components/task/task.component';
import { TasklistService } from './services/tasklist.service';
import { ListContainerComponent } from './components/list-container/list-container.component';
import { ScrollingModule } from '@angular/cdk/scrolling';

@NgModule({
  declarations: [
    AppComponent,
    ListComponent,
    TaskComponent,
    ListContainerComponent
  ],
  imports: [
    BrowserModule,
    HttpClientModule,
    ScrollingModule
  ],
  providers: [HttpClientModule],
  bootstrap: [AppComponent]
})
export class AppModule { }
