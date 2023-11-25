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
    ID: string;
    title: string;
    content: string;
    questions: {
      ID: string;
      title: string;
      content: string;
      options: string[];
    } [];
  };
  answer: {
    option: number;
    correct: number;
    score: number;
  };
  user: {
    ID: number,
    login: string,
  },
  counter: number;
  error: string;
}

export const initialState: stateProps = {
  loading: false,
  post: {
    ID: '',
    title: '',
    content: '',
    questions: [],
  },
  answer: {
    option: 0,
    correct: 0,
    score: 0,
  },
  user: {
    ID: 0,
    login: '',
  },
  counter: 0,
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
        ...state,
        loading: false,
        post: action.payload,
      }

    case 'FETCH_POST_FAILURE':
      return {
        ...state,
        loading: false,
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

    case 'SET_COUNTER_POSITION':
      return {
        ...state,
        counter: action.payload,
      }

    case 'SET_SCORE_ID':
      return {
        ...state,
        answer: {
          ...state.answer,
          score: action.payload,
        },
      }

    case 'SET_USER':
      return {
        ...state,
        user: action.payload,
      }

    default: return state
  }
}

export default reducers;
