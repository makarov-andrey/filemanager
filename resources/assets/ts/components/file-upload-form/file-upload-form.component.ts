import {Component} from '@angular/core';
import {FileService} from "../../services/file.service";
import {FormGroup, Validators, FormControl} from '@angular/forms';
import {CustomValidators} from "ng2-validation";

@Component({
    selector: 'file-upload-form',
    template: require('./file-upload-form.template.html')
})
export class FileUploadFormComponent {
    fileForm = new FormGroup({
        email: new FormControl(null, [Validators.required, CustomValidators.email]),
        fileName: new FormControl(),
        fileCode: new FormControl(),
        description: new FormControl()
    });
    posted = false;
    uploaded = false;
    uploadingStarted = false;
    uploadPromise: Promise<any>;
    uploadProgress: number;
    fileInvalid = false;
    buttonDisabled = false;

    minFileSize = 1 * 1024 * 1024;
    maxFileSize = 150 * 1024 * 1024;

    constructor(private fileService: FileService) { }

    onSubmit() {
        this.buttonDisabled = true;
        this.uploadPromise.then(() => this.postProperties());
    }

    postProperties() {
        this.buttonDisabled = false;
        if (this.fileForm.valid) {
            this.fileService.postProperties(this.fileForm)
                .then(() => this.posted = true);
        }
    }

    uploadFile(file: File) {
        this.uploaded = false;
        this.uploadingStarted = false;

        if (this.fileForm.value.fileCode) {
            this.fileService.deleteTempFile(this.fileForm.value.fileCode)
        }

        this.fileForm.controls['fileCode'].setValue('');

        if (!this.validateFile(file)) {
            return;
        }

        this.fileForm.controls['fileName'].setValue(file.name);

        this.uploadingStarted = true;
        this.uploadPromise = this.fileService.upload(file, progress => this.uploadProgress = progress)
            .then(response => {
                this.fileForm.controls['fileCode'].setValue(response.code);
                this.uploaded = true;
            });
    }

    uploadOneMore() {
        this.fileForm.reset();
        this.buttonDisabled = false;
        this.posted = false;
        this.uploaded = false;
        this.uploadingStarted = false;
    }

    validateFile (file: File) {
        this.fileInvalid = file.size < this.minFileSize || file.size > this.maxFileSize;
        return !this.fileInvalid;
    }
}
