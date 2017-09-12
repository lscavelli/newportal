import { Component } from '@angular/core';

@Component({
  selector: 'my-app2',
  template: '<h1>{{title}}</h1>',
  styles: ['h1 {color: white}']
})
export class App2Component {
  title = 'componente 2 Angular'
}
