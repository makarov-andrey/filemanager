import {Injectable} from '@angular/core';
import {Headers, Http, RequestOptions, Response} from '@angular/http';

@Injectable()
export class CsrfAuthorizedHttpService {
    options = new RequestOptions();

    constructor(private http: Http) {
        let headers = new Headers();
        headers.set('X-CSRF-TOKEN', CsrfAuthorizedHttpService.getToken());
        this.options.headers = headers;
    }

    get(url: string, options = new RequestOptions) {
        options.merge(this.options);
        return this.http.get(url, options);
    }

    post(url: string, body: any, options = new RequestOptions) {
        options.merge(this.options);
        return this.http.post(url, body, options);
    }

    put(url: string, body: any, options = new RequestOptions) {
        options.merge(this.options);
        return this.http.put(url, body, options);
    }

    delete(url: string, options = new RequestOptions) {
        options.merge(this.options);
        return this.http.delete(url, options);
    }

    static getToken(): string {
        let meta = <Element> document.querySelector('meta[name="csrf-token"]');
        return <string> meta.getAttribute('content');
    }
}

