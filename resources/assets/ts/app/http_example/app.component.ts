import { Component, OnInit } from '@angular/core';
import {BookService} from "./app.service";
import {Book} from "./book";

@Component({
  selector: 'my-app',
  templateUrl: './app.component.html',
  styleUrls: ['./app.component.css']
})

export class BookComponent implements OnInit {
  books: Book[];
  errMesg: any;

  constructor(private _bookService: BookService) { }

  ngOnInit() {
    this.getBook();
  }

  getBook(){
    this._bookService.getBooks()
      .subscribe(
        book => this.books = book,
        error => this.errMesg = <any>error
      )
  }

  sendBook(titolo:string, corpo:string){
    this._bookService.sendBook(titolo,corpo).subscribe(
        response => console.log('ok'), error=> console.log('ok')
    )
  }

}