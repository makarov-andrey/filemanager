import { BrowserModule } from '@angular/platform-browser';
import { FormsModule } from '@angular/forms';
import { HttpModule } from '@angular/http';
import { NgModule } from '@angular/core';
import { RouterModule } from '@angular/router';

import { routes } from './app.routing';

import { AppComponent } from './app.component';
import { PageNotFoundComponent } from "./components/page-not-found/page-not-found.component";
import { NavbarComponent } from "./components/navbar/navbar.component";
import { UploadFormComponent } from "./components/upload-form/upload-form.component";

import { FileUploadService } from   "./services/file-upload/file-upload.service";

import {APP_BASE_HREF} from '@angular/common';

@NgModule({
    imports: [
        BrowserModule,
        FormsModule,
        HttpModule,
        RouterModule.forRoot(routes)
    ],
    declarations: [
        AppComponent,
        PageNotFoundComponent,
        UploadFormComponent,
        NavbarComponent
    ],
    providers: [
        FileUploadService,
        {
            provide: APP_BASE_HREF,
            useValue : '/'
        }
    ],
    bootstrap:[
        AppComponent
    ]
})
export class AppModule {}
