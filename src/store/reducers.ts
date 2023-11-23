/**
 * Reducers
 *
 * This module defines a set of reducers for manipulating
 * state. It provides a new set of state objects based on the
 * dispatched action by the user.
 *
 * @module reducers
 * @returns {Object}  An action object.
 */

export interface stateProps {
  loading: boolean;
  post: {
    id: string;
    title: string;
    content: string;
    questions: {
      id: string;
      title: string;
      content: string;
      options: string[];
    } [];
  };
  error: string;
  option: number;
}

export const initialState = {
  loading: false,
  post: {},
  error: '',
  option: 0,
}

const reducers = ( state = initialState, action ) => {
  switch ( action.type ) {
    case 'FETCH_POST_REQUEST':
      return {
        ...state,
        loading: true
      }

    case 'FETCH_POST_SUCCESS':
      return {
        loading: false,
        post: action.payload,
        error: ''
      }

    case 'FETCH_POST_FAILURE':
      return {
        loading: false,
        post: {},
        error: action.payload
      }

    case 'SET_SELECTED_OPTION':
      return {
        ...state,
        option: action.payload
      }

    default: return state
  }
}

export default reducers;
