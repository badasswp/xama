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
  answer: {
    option: number;
    correct: number;
  }
  error: string;
}

export const initialState = {
  loading: false,
  post: {},
  answer: {
    option: 0,
    correct: 0,
  },
  error: '',
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
        answer: {
          ...state.answer,
          option: action.payload,
        },
      }

    case 'SET_MARKER_OPTION':
      return {
        ...state,
        answer: {
          ...state.answer,
          correct: action.payload,
        },
      }

    default: return state
  }
}

export default reducers;
