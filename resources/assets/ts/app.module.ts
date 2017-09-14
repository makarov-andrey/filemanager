import {BrowserModule} from '@angular/platform-browser';
import {ReactiveFormsModule} from '@angular/forms';
import {HttpModule} from '@angular/http';
import {NgModule} from '@angular/core';
import {RouterModule} from '@angular/router';

import {routes} from './app.routing';

import {AppComponent} from './app.component';
import {PageNotFoundComponent} from "./components/page-not-found/page-not-found.component";
import {NavigationComponent} from "./components/navigation/navigation.component";
import {FileUploadFormComponent} from "./components/file-upload-form/file-upload-form.component";

import {FileService} from   "./services/file.service";

import {APP_BASE_HREF} from '@angular/common';
import {CsrfAuthorizedHttpService} from "./services/csrf-authorized-http.service";
import {CustomFormsModule} from "ng2-validation";

@NgModule({
    imports: [
        BrowserModule,
        ReactiveFormsModule,
        HttpModule,
        CustomFormsModule,
        RouterModule.forRoot(routes)
    ],
    declarations: [
        AppComponent,
        PageNotFoundComponent,
        FileUploadFormComponent,
        NavigationComponent
    ],
    providers: [
        FileService,
        CsrfAuthorizedHttpService,
        {
            provide: APP_BASE_HREF,
            useValue: '/'
        }
    ],
    bootstrap: [
        AppComponent
    ]
})
export class AppModule { }
