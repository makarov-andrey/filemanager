// Polyfills
import 'core-js/es6';
import 'core-js/es7/reflect';

// Zone.js
import 'zone.js/dist/zone';

// RxJS
import 'rxjs';

import { platformBrowserDynamic } from '@angular/platform-browser-dynamic';

import { AppModule } from './app.module';

platformBrowserDynamic().bootstrapModule(AppModule);
