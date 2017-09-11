import { Routes, RouterModule } from '@angular/router';
import { PageNotFoundComponent } from "./components/page-not-found/page-not-found.component";
import { UploadFormComponent } from "./components/upload-form/upload-form.component";
import { LoginComponent } from "./components/admin/login/login.component";

export const routes: Routes = [
    {
        path: '',
        component: UploadFormComponent
    },
    {
        path: 'admin/login',
        component: LoginComponent
    },
    {
        path: '**',
        component: PageNotFoundComponent
    }
];
