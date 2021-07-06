# REST API

This module provides a REST API for xentral.

## Generating documentation

It is possible to generate a local documentation of all the existing endpoints by running:
   ```
   npm run api-docs:build
   ```

After this the documentation will be available at:
   ```
   http://<your-domain>/api/docs.html
   ```

## Entry point

Entry point for REST API calls is the `www/api/index.php` file. It has its own front controller, which allows using clean URL paths such as: `/api/foo/bar/`.

However, in case the target server does not have support for rewrite rules, it is also possible to call the API directly with an URL path such as:
```
/api/index.php?path=/v1/artikelkategorien&sort=bezeichnung
```

## Steps for adding a new API endpoint

1. Enable debug mode (although not mandatory) by updating this line in `www/api/index.php`:
   ```php
   define('DEBUG_MODE', false);
2. Define route in `classes/Modules/Api/routes.php`:
   ```php
   [
       'GET',
       '/v1/foobar',
       ['Version1', 'FooBar', 'Generic', 'listAction']
   ],
3. Implement a controller at `classes/Modules/Api/Controller/Version1/FooBarController.php`
   - The name of the class must be the name defined in the route combined with “Controller”, so in this case: `FoobarController`
   - The class should have the method defined in the route (in this case `listAction()`)
4. Define permissions at `\Api::getGroupedPermissions()`
5. Generate the new permission options into the database (the `api_permission` table) with:
   ```
   php upgradedbonly.php
   ```
6. Before testing the new endpoint, edit your API account at `index.php?module=api_account&action=list` and save the new permissions to it
7. Update contents of the `www/api/docs.raml` file
   - See instructions at https://raml.org/
8. Rebuild the API documentation by running:
   ```
   npm run api-docs:build
   ```
9. Verify the contents of the documentation at:
   ```
   http://<your-domain>/api/docs.html
   ```

## See also
- https://xentral.atlassian.net/wiki/spaces/TD/pages/1177419787/REST+API
- https://community.xentral.com/hc/de/articles/360017436919-API-Dokumentation
- https://community.xentral.com/hc/de/articles/360017441459-Xentral-API
