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

  setCounterPosition: ( key ) => {
    return {
      type: 'SET_COUNTER_POSITION',
      payload: key
    }
  },

  setScoreID: ( key ) => {
    return {
      type: 'SET_SCORE_ID',
      payload: key
    }
  },

  setUser: ( key ) => {
    return {
      type: 'SET_USER',
      payload: key
    }
  },

  setRestPath: ( key ) => {
    return {
      type: 'SET_REST_PATH',
      payload: key
    }
  },

  setLoading: ( key ) => {
    return {
      type: 'SET_LOADING',
      payload: key
    }
  },
};

export default actions;
