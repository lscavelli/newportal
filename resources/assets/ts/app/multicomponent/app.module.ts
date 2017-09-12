import { NgModule } from '@angular/core';
import { BrowserModule }  from '@angular/platform-browser';

import { App1Component } from './app1.component';
import { App2Component } from './app2.component';

@NgModule({
  imports: [
    BrowserModule
  ],
  declarations: [
    App1Component,App2Component
  ],
  bootstrap: [ App1Component, App2Component ]
})
export class AppModule { }
