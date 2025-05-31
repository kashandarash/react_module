DESCRIPTION
--------------
This is a test React application.
The app provides a simple todo list where you can create/delete items, and check every item as done.  
It communicated with a server using GraphQL protocol.
App files and all code are in ./src, built js file is in ./dis/main.js.
We are using minimal GraphQL client (graphql-request) instead of more advanced (like 'Apollo') because we need very limited and simple actions.  

RUN & BUILD
--------------
To be able to edit application, install node modules - "npm install".
To build/rebuild application run "npm run build".
To run separately, run "npm run dev".
Make sure index.html has the correct mocked drupalSettings.
In this config, we pass the same parameters that we have in Drupal block.
