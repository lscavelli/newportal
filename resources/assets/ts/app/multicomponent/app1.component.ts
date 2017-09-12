import { Component } from '@angular/core';

@Component({
  selector: 'my-app1',
  template: '<h1>{{title}}</h1>',
  styles: ['h1 {color: red}']
})
export class App1Component {
  title = 'componente 1 Angular'
}
