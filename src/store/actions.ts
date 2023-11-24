/**
 * Actions
 *
 * This module defines a set of actions for managing state
 * changes. It provides a set of actions that can be dispatched
 * to update the global state object using keys.
 *
 * @module actions
 * @returns {Object}  An action object.
 */

const actions = {
  fetchPostRequest: () => {
    return {
      type: 'FETCH_POST_REQUEST'
    }
  },

  fetchPostSuccess: ( post ) => {
    return {
      type: 'FETCH_POST_SUCCESS',
      payload: post
    }
  },

  fetchPostFailure: ( error ) => {
    return {
      type: 'FETCH_POST_FAILURE',
      payload: error
    }
  },

  setSelectedOption: ( key ) => {
    return {
      type: 'SET_SELECTED_OPTION',
      payload: key
    }
  },

  setMarkerOption: ( key ) => {
    return {
      type: 'SET_MARKER_OPTION',
      payload: key
    }
  },
};

export default actions;
