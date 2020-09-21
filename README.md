# Fictional University

Most of the universities out there have a website. Therefore I took that as an inspiration to develop a non-existing university website with WordPress with a number of common features and information. For example:

- Home page with information about upcoming events and recent blogs
- Campus, Program and Professor pages
- Website live search that's aware of relationships
- Traditional non-JS search with WordPress
- Note-taking feature (private between users)
- Open registration

## Live Demo

*The website is hosted with SiteGround. Click [here](http://tylern1.sgedu.site/) to visit.*

*My startup hosting plan is expired on Dec 9, 2020*

![demo](/screenshots/demo.gif)

## Some of the things I learned from this project

- Converting static HTML to work with WordPress
- Creating custom post types, fields and plugins
- Limiting posts
- Establishing relationships between posts and pages 
- Using WordPress REST API with Ajax and also building custom routes
- Working with JSON data and different request methods
- Dynamic logics for live search and frontend updates with JavaScript and PHP 
- WP_Query targeting different types of data and relationships
- User-specific login feature 
- Custom roles and permission for specific tasks and content
- Private user generated content
- Escaping input to prevent malicious code
- Using templates for less duplication
- Using Google Map API
- Setting up a continuous deployment with SiteGround and DeployHQ

## Overall structure of the project

- Most of the important files lie in `wp-content/themes/fictional-university-theme`
- Inside `inc` directory are the files I wrote for custom route
- The `js/scripts.js` imports all the Javascript files located at `js/modules`. For the jQuery-free version of JavaScript files, visit `modules/jquery-free` and import them in `js/scripts.js`
- `template-parts` directory contains templates for displaying contents in different pages such as archive and front pages
- `functions.php` contains implementation for working with WordPress hooks and filters
- Beside the live search feature, `search.php`, `page-search.php` and `searchform.php` are provided for non-JS search feature if JavaScript is not enabled for some browsers

- `wp-content/plugins/my-first-amazing-plugin.php` is where I have my custom plugin
- For custom post type declarations, visit `wp-content/mu-plugins/university-post-types.php`

## Languages, technologies and tools that I used

- PHP and JavaScript
- Nodejs
- jQuery (with Ajax)
- Google Maps API
- [Local](https://localwp.com/)
- Postman
- SiteGround 
- [DeployHQ](https://deployhq.com)
