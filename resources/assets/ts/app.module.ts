import {BrowserModule} from '@angular/platform-browser';
import {FormsModule} from '@angular/forms';
import {HttpModule} from '@angular/http';
import {NgModule} from '@angular/core';
import {RouterModule} from '@angular/router';

import {routes} from './app.routing';

import {AppComponent} from './app.component';
import {PageNotFoundComponent} from "./components/page-not-found/page-not-found.component";
import {NavigationComponent} from "./components/navigation/navigation.component";
import {FileUploadFormComponent} from "./components/file-upload-form/file-upload-form.component";

import {FileService} from   "./services/file/file.service";

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
        FileUploadFormComponent,
        NavigationComponent
    ],
    providers: [
        FileService,
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
