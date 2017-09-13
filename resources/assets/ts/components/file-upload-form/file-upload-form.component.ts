import {Component, Input} from '@angular/core';
import {FileService} from "../../services/file/file.service";
import {FormsModule, NgForm} from '@angular/forms';
import {FileForm} from "../../model/FileForm";

@Component({
    selector: 'file-upload-form',
    template: require('./file-upload-form.template.html')
})
export class FileUploadFormComponent {
    fileForm: FileForm = new FileForm();

    constructor(private fileService: FileService) { }

    fileSelected (file: File) {
        this.fileForm.file = file;
    }

    onSubmit() {
        this.fileService.upload(this.fileForm).then((response: {}) => {
            console.log(response);
        });
    }
}
