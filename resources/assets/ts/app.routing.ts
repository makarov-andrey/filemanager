import {Routes, RouterModule} from '@angular/router';
import {PageNotFoundComponent} from "./components/page-not-found/page-not-found.component";
import {FileUploadFormComponent} from "./components/file-upload-form/file-upload-form.component";

export const routes: Routes = [
    {
        path: '',
        component: FileUploadFormComponent
    },
    {
        path: '**',
        component: PageNotFoundComponent
    }
];
