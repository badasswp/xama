/**
 * This file is responsible for rendering the front-end.
 * It ensures that the DOM content is fully loaded before
 * rendering the application.
 *
 * @since 1.0.0
 */
import ReactDOM from 'react-dom';
import App from './App';

document.addEventListener( 'DOMContentLoaded', function () {
  ReactDOM.render( <App />, document.getElementById( 'app' ) );
} );
