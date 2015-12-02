# vaibhavpandeyvpz/acraviz

Getting Started
------
- Install [Node.js](https://nodejs.org/en/) on your machine.
- Install [Bower](http://bower.io/) and [Gulp](http://gulpjs.com/) globally using below commands:
```bash
npm i -g bower gulp
```
- Install [Composer](https://getcomposer.org/) using below command:
```bash
curl -sS https://getcomposer.org/installer | php -- --install-dir=bin --filename=composer
```
- Install [ACRAViz](https://github.com/vaibhavpandeyvpz/acraviz) via composer:
```bash
php bin/composer create-project vaibhavpandeyvpz/acraviz mysite "@dev"
```
- Use your ```package name``` as **Usename** and ```token``` as **Password** for setting up basic auth when using ACRA as shown below.
```java
package com.vaibhavpandey.acraviz.demo;

import android.app.Application;
import org.acra.ACRA;
import org.acra.annotation.ReportsCrashes;

@ReportsCrashes(
        formUri = "http://domain.tld/api",
        formUriBasicAuthLogin = BuildConfig.APPLICATION_ID,
        formUriBasicAuthPassword = "<token>")
public class MainApplication extends Application {

    @Override
    public void onCreate() {
        super.onCreate();
        ACRA.init(this);
    }

}
```
