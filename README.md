# Symfony Template

Template to quick start any Symfony project.

## Initialize (to remove!)

### Local side

After creating the project from the template and cloning it for **the first time**:

- Replace all occurrences of `symfo-base` with the new app name with:

  ```sh
  find . -type f -exec sed -i 's/symfo-base/<new-app-name>/g' {} +
  ```

- Customize the project name and description in the `README` and `composer.json`, and remove this **Initialize** part from `README`.
- Uncomment the desired lines in `.github/workflows/main.yml` to enable the workflow.
- Use git to add, commit and tag this first version with (:warning: don't push yet):

  ```sh
  git add . && git commit -m "Update to version 0.1.0" && git tag -a "0.1.0" -m "Update to version 0.1.0"
  ```

- Initialize `git flow init` or manually create a `develop` branch.  

### GitHub side

Some variables and secrets have to been set up:

- Global parameters:
  - variables: **SERV_ADDR** (domain name)
  - secrets: **SERV_PORT** for SSH connection
- Create two environments (stage and prod) and these parameters in each on of them:
  - variables: **SERV_PATH** where to copy the application on the server
  - secrets: **SERV_USER** and private **SERV_KEYS** for SSH connection

Push the project on `main`/`develop` or both branches to build the application and send it to the server.

### Server side

For each selected environment:

- Create the user and database in MariaDB.
- In the project root, create a `.env.local` file, customizing the `APP_ENV` and `DATABASE_URL` variables. Also check that the `var` directory has been created, otherwise run `mkdir var && chmod 775 var`.
- To correctly route the requests to the application if it lives in a sub-directory, use this nginx location block:

  ```ini
  location /symfo-base {
    return 301 /symfo-base/;
    access_log off; 
  }

  location @symfo-base {
    rewrite ^/symfo-base/(.*)$ /symfo-base/index.php/$1 last;
  }

  location /symfo-base/ {
    alias /usr/share/nginx/www/symfo-base/public/;
    try_files $uri @symfo-base;

    location ~ ^/symfo-base/index\.php(/|$) {
      fastcgi_split_path_info ^(/symfo-base/index\.php)(/.*)$;
      include fastcgi_params;
      fastcgi_param SCRIPT_FILENAME $document_root/index.php;
      fastcgi_pass php-fpm:9000;
      internal;
    }
  }
  ```

## Prerequisite

- Back-end:
  - **Symfony**: 7.1 framework
  - **PHP**: 8.2 (compatible with Symfony 7.1)
  - **Composer**: >= 2.8 for dependency management
  - **MariaDB**: 11.5 through docker for the database
- Front-end:
  - **Node.js**: 18.x
  - **npm**: >= 9.2 for dependency management
  - **Sass**: >= 1.82
  - **Webpack Encore**: 5.0
- **git** for source and version control
- **GitHub** to share and deploy

## Code quality

**Prettier** with custom modules from `@zackad/prettier-plugin-twig` and `@prettier/plugin-php` for twig and PHP files.  
To prettify one file:

- in the console, execute `npm run pretty-file <file>`.
- if using VSCode, install the *Prettier* extension and set the config file path to `linter/.prettierrc.json`, then use *Format Document*.

To prettify all files, run `npm run pretty-all`.

**Linter**:

- **php-cs-fixer**: for PHP files in `src` and `tests` directories
- **twig-cs-fixer**: for twig files in `templates` directory
- **stylelint**: for CSS/SCSS files in `assets/styles` directory
- **eslint**: for JS files in `assets/controllers` directory

To lint all files from one type, run `composer lint-[php|twig|scss|js]`.  
To lint all files, run `composer lint-all`.

## Install

After this first install or cloning the existing project:

- install the dependencies with `composer install` and `npm install`.
- copy the `.env.dev` file into a `.env.dev.local` file and customize the values.  
:information_source: `DATABASE_URL` is not mandatory for dev environment as Symfony will get the correct values from docker.  

To use default git hooks, run `git config core.hooksPath ./githooks`. Current hooks are

- prettify and linting all staged files before commit
- running all tests before push

## Dev

Start the php/web server along with docker and npm server with `symfony server:start -d`.  
Check the logs with `symfony server:logs`.  
Stop all the services with `symfony server:stop`.

To increment the version, use `symfony console bizkit:versioning:increment`.

## Deploy

A workflow to build and deploy the application is preconfigured.  
The workflow can be triggered manually in GitHub Actions or automatically when pushing to main (for prod) or to develop (for stage).  
:warning: Some triggers may not be available depending on the project.
