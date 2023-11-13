/**
 * This file is responsible for rendering the front-end.
 * It ensures that the DOM content is fully loaded before
 * rendering the application.
 *
 * @since 1.0.0
 */
import ReactDOM from 'react-dom';
import { Provider } from 'react-redux';
import { Store } from './store';

import App from './App';
import './styles/app.scss';

document.addEventListener( 'DOMContentLoaded', function () {
  ReactDOM.render(
    <Provider store={ Store }>
      <App />
    </Provider>,
    document.getElementById( 'xama' )
  );
} );
