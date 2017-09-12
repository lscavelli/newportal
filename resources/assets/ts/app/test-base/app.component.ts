import { Component } from '@angular/core';
import { Hero } from './hero';

import '../../assets/css/styles.css';

@Component({
  selector: 'my-app',
  templateUrl: './app.component.html',
  styleUrls: ['./app.component.css']
  //oppure inline
  //template: '<h1>{{title}}</h1>',
  //styles: ['h1 {color: red}']
})
export class AppComponent {
  clickMessage = '';

  heroes = [
    new Hero(1, 'Windstorm'),
    new Hero(13, 'Bombasto'),
    new Hero(15, 'Magneta'),
    new Hero(20, 'Tornado')
  ];
  myHero = this.heroes[0];

  onClickMe() {
    this.clickMessage = 'Hello hai cliccato!';
  }

}
