# vaibhavpandeyvpz/acraviz
Open-source, [Silex](http://silex.sensiolabs.org/)/[Doctrine](http://www.doctrine-project.org/) powered backend for visualizing crash reports from [ACRA](http://www.acra.ch/) library for Android

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
- Move the ```.env.example``` to ```.env```, and edit your database (**DB_***) credentials. Then execute below commands in project directory:
```bash
php acraviz db:import -F./schema.sql
php acraviz users:add -U<USER> -P<PASSWORD>
php acraviz security:rekey
```
- Navigate to [ACRAViz](https://github.com/vaibhavpandeyvpz/acraviz) via ```http```, use the credentials you entered earlier in command-line to *login*.
- Go to **Applications** from navigation at top, enter your application **title** & **package name** on the left for and hit **Add**.
- Now, you can use your ```package name``` as **Usename** and ```token``` as **Password** for setting up basic auth when using ACRA as shown below. Please note the **formUri** should point to [ACRAViz](https://github.com/vaibhavpandeyvpz/acraviz) installation + ```/api``` suffix.
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

Screenshots
------
![Screenshot #0](https://raw.githubusercontent.com/vaibhavpandeyvpz/acraviz/master/assets/screenshots/0.png "Screenshot #0")
![Screenshot #1](https://raw.githubusercontent.com/vaibhavpandeyvpz/acraviz/master/assets/screenshots/1.png "Screenshot #1")
![Screenshot #2](https://raw.githubusercontent.com/vaibhavpandeyvpz/acraviz/master/assets/screenshots/2.png "Screenshot #2")
![Screenshot #3](https://raw.githubusercontent.com/vaibhavpandeyvpz/acraviz/master/assets/screenshots/3.png "Screenshot #3")
![Screenshot #4](https://raw.githubusercontent.com/vaibhavpandeyvpz/acraviz/master/assets/screenshots/4.png "Screenshot #4")

License
------
See [LICENSE.md](https://github.com/vaibhavpandeyvpz/acraviz/blob/master/LICENSE.md) file.
