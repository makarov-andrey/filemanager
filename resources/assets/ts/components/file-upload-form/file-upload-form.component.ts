import {Component} from '@angular/core';
import {FileService} from "../../services/file.service";
import {FormGroup, Validators, FormControl} from '@angular/forms';
import {CustomValidators} from "ng2-validation";

@Component({
    selector: 'file-upload-form',
    template: require('./file-upload-form.template.html')
})
export class FileUploadFormComponent {
    form = new FormGroup({
        email: new FormControl(null, [Validators.required, CustomValidators.email]),
        fileName: new FormControl(null, Validators.required),
        fileCode: new FormControl(null, Validators.required),
        description: new FormControl(null, Validators.maxLength(1000))
    });
    uploadPromise: Promise<any> = new Promise(resolve => resolve());
    posted = false;
    uploaded = false;
    uploadingStarted = false;
    buttonDisabled = false;
    uploadProgress: number;

    minFileSize = 1 * 1024 * 1024;
    maxFileSize = 150 * 1024 * 1024;

    constructor(private fileService: FileService) { }

    onSubmit() {
        this.buttonDisabled = true;
        this.uploadPromise.then(() => this.postProperties());
    }

    postProperties() {
        this.buttonDisabled = false;
        this.touchForm();
        if (this.form.valid) {
            this.fileService.postProperties(this.form)
                .then(() => this.posted = true);
        }
    }

    uploadFile(file: File) {
        this.uploaded = false;
        this.uploadingStarted = false;

        if (this.form.value.fileCode) {
            this.fileService.deleteTempFile(this.form.value.fileCode)
        }

        this.form.controls['fileCode'].setValue('');

        if (!this.isFileValid(file)) {
            return;
        }

        this.form.controls['fileName'].setValue(file.name);

        this.uploadingStarted = true;
        this.uploadPromise = this.fileService.upload(file, progress => this.uploadProgress = progress)
            .then(response => {
                this.form.controls['fileCode'].setValue(response.code);
                this.uploaded = true;
            });
    }

    uploadOneMore() {
        this.form.reset();
        this.buttonDisabled = false;
        this.posted = false;
        this.uploaded = false;
        this.uploadingStarted = false;
    }

    isFileValid (file: File|null) {
        return file && file.size > this.minFileSize && file.size < this.maxFileSize;
    }

    touchForm () {
        for (let controlName in this.form.controls) {
            this.form.get(controlName).markAsTouched();
        }
    }

    isFieldInvalid(field: string) {
        if (field == 'file') {
            let HTMLFileObject = <HTMLInputElement> document.getElementById('file'),
                files = <FileList> HTMLFileObject.files;
            return !this.isFileValid(files[0]) && this.form.get('fileName').touched;
        }
        return this.form.get(field).invalid && this.form.get(field).touched;
    }

    isFieldHasSuccess(field: string) {
        return field == 'file' && this.uploaded;
    }

    displayFieldCss(field: string) {
        return {
            'has-error': this.isFieldInvalid(field),
            'has-success': this.isFieldHasSuccess(field),
        };
    }
}
