import 'rxjs/add/operator/catch';
import 'rxjs/add/operator/delay';
import { Injectable } from '@angular/core';
import { Http, Response } from '@angular/http';
import { Observable } from 'rxjs/Observable';

@Injectable()
export class FileUploadService {

    constructor (private http: Http) { }

    /**
     * @param requestUrl
     * @returns {Observable<{}>}
     */
    public upload (requestUrl: string): Observable<{}> {
        return this.http
            .post(requestUrl, '')
            .map(FileUploadService.extractData);
    }

    /**
     * @param res
     * @returns {any}
     */
    private static extractData (res: Response): any {
        let body = res.json();

        return body.message || {};
    }
}
