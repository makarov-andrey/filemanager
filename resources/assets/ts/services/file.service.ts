import {Injectable} from '@angular/core';
import {Headers, Http, RequestOptions, Response} from '@angular/http';
import {FormGroup} from "@angular/forms";
import {CsrfAuthorizedHttpService} from "./csrf-authorized-http.service";
import {Observable} from "rxjs/Observable";
import {Subscriber} from "rxjs/Subscriber";
import {CanceledRequestError} from "../errors/CanceledRequestError";

@Injectable()
export class FileService {
    private uploadXhr: XMLHttpRequest;

    constructor(private httpService: CsrfAuthorizedHttpService) { }

    upload(file: File, onProgress: Function): Promise<any> {
        //cancel pending request
        if (this.uploadXhr) {
            this.uploadXhr.abort();
        }

        this.uploadXhr  = new XMLHttpRequest();
        let formData = new FormData();
        formData.append('file', file);

        return new Promise((resolve, reject) => {
            this.uploadXhr.upload.onprogress = (event: any) => {
                let progress = Math.round(event.lengthComputable ? event.loaded * 100 / event.total : 0);
                onProgress(progress);
            };

            this.uploadXhr.onload = () => {
                if (this.uploadXhr.status == 200) {
                    resolve(JSON.parse(this.uploadXhr.response));
                } else {
                    reject(this.uploadXhr);
                }
            };

            this.uploadXhr.onerror = () => {
                reject(this.uploadXhr);
            };

            this.uploadXhr.onabort = () => {
                reject(this.uploadXhr);
            };

            this.uploadXhr.open("POST", '/backend/tempfile', true);
            this.uploadXhr.setRequestHeader('X-CSRF-TOKEN', CsrfAuthorizedHttpService.getToken());
            this.uploadXhr.send(formData);
        });
    }

    deleteTempFile(code: string): Promise<any> {
        return this.httpService.delete("backend/tempfile/" + code)
            .toPromise();
    }

    postProperties(fileForm: FormGroup): Promise<any> {
        let formData = new FormData();
        formData.append('fileCode', fileForm.value.fileCode);
        formData.append('fileName', fileForm.value.fileName);
        formData.append('email', fileForm.value.email);
        formData.append('description', fileForm.value.description);

        return this.httpService
            .post("backend/file-properties", formData)
            .toPromise()
    }
}
