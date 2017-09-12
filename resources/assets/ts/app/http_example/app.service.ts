import { Injectable } from '@angular/core';
import {Http, Response} from "@angular/http";
import {Observable} from "rxjs";
import {Book} from "./book";

@Injectable()
export class BookService {

  constructor(private _http: Http) { }

  getBooks():Observable<Book[]>{
    let url = 'http://localhost/api/book';
    return this._http.get(url)
      .map(res=> res.json())
      .catch(this.handleError)
  }

  private handleError (error: Response | any) {
    // In a real world app, we might use a remote logging infrastructure
    let errMsg: string;
    if (error instanceof Response) {
      const body = error.json() || '';
      const err = body.error || JSON.stringify(body);
      errMsg = `${error.status} - ${error.statusText || ''} ${err}`;
    } else {
      errMsg = error.message ? error.message : error.toString();
    }
    console.error(errMsg);
    return Observable.throw(errMsg);
  }

  sendBook(titolo:string, corpo:string) {
    let url = 'http://localhost/api/book';
    let body =  JSON.stringify({title:titolo,body:corpo});
    return this._http.post(url,body)
        .map(res=> res.json())
  }

}