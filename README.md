ABOUT
-------------
The main goal of this module is to create a block that has a Rect app inside.
This way we are testing a possibility to have an atomic decoupled component that can be used independently in any part of the site.
The React app communicates with the backend by GraphQL endpoints (./graphqls/react_module_schema.graphqls and ./src/Plugin/GraphQL/*).
This architecture can allow to the use of same app inside the Drupal or in externally, for example in mobile app.
The only difference is that we have to handle user authentication (in our case app will have site cookies).
Please check ./js/README.md for the app details.

INSTALL
-------------
Install and enable graphql drupal module, ideally ^4.8.
Enable react_module.
Go to block layout (/admin/structure/block) and place 'React App Example Block' block to any region.
That's it, the configured page should have a React block.
To check the list of all "Todo Items" go to '/admin/content/react-module-todo-item'.

If you plan to use the React app outside of Drupal, don't forget to configure CORS.
```
cors.config:
    enabled: true
    allowedHeaders: [ '*' ]
    allowedMethods: [ '*' ]
    allowedOrigins: [ '*' ]
    exposedHeaders: false
    maxAge: false
    supportsCredentials: true
```
