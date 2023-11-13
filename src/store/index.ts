/**
 * Store
 *
 * This module defines the Redux store used for storing
 * the global state of the application. It utilises the Redux
 * toolkit & thunk middleware alongside.
 *
 * @module store
 * @returns {Object}  Global store.
 */

import { configureStore } from '@reduxjs/toolkit';
import thunk from 'redux-thunk';
import reducers from './reducers';

export const Store = configureStore( {
  reducer: reducers,
  middleware: [ thunk ],
} );
