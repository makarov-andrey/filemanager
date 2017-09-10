import { Routes, RouterModule } from '@angular/router';
import { PageNotFoundComponent } from "./components/page-not-found/page-not-found.component";
import { UploadFormComponent } from "./components/upload-form/upload-form.component";

export const routes: Routes = [
    {
        path: 'angular',
        component: UploadFormComponent
    },
    // {
    //     path: 'edit',
    //     component: SecondComponent
    // },
    {
        path: '**',
        component: PageNotFoundComponent
    }
];
