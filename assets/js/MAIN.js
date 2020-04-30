/*
 * Welcome to your app's main JavaScript file!
 *
 * We recommend including the built version of this JavaScript file
 * (and its CSS file) in your base layout (base.html.twig).
 */

// any CSS you import will output into a single css file (app.css in this case)
// import '../css/app.css';
require('bootstrap');
import '../sass/MAIN.scss'
// Need jQuery? Install it with "yarn add jquery", then uncomment to import it.
// import $ from 'jquery';
import $ from 'jquery';

// import the function from greet.js (the .js extension is optional)
// ./ (or ../) means to look for a local file
//  import greet from './greet';

$(document).ready(function () {
  require('./navigation');
  require('./introduction');
  require('./fielUploadFields');

})
