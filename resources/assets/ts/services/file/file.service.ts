import {Injectable} from '@angular/core';
import {Headers, Http, RequestOptions, Response} from '@angular/http';
import {FileForm} from "../../model/FileForm";

@Injectable()
export class FileService {
    private url = "backend/file";

    constructor(private http: Http) { }

    public upload(fileForm: FileForm): Promise<{}> {
        let options = new RequestOptions();

        let headers = new Headers();
        headers.set('X-CSRF-TOKEN', FileService.getToken());

        options.headers = headers;

        let formData = new FormData();
        formData.append('file', fileForm.file);
        formData.append('email', fileForm.email);
        formData.append('description', fileForm.description);

        return this.http
            .post(this.url, formData, options).toPromise()
            .then(FileService.extractData);
    }

    private static extractData(res: Response): {} {
        return res.json();
    }

    static getToken(): string {
        let meta = <Element> document.querySelector('meta[name="csrf-token"]');
        return <string> meta.getAttribute('content');
    }
}
