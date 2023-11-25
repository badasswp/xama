import { useState } from 'react';
import { useSelector, useDispatch } from 'react-redux';
import { stateProps } from '../store/reducers';

import actions from '../store/actions';

/**
 * Submit Component
 *
 * This component is responsible for rendering the submit button
 * that saves and submits answers for a question.
 *
 * @returns {JSX.Element}  The rendered Submit component.
 */
const Submit = () => {
  const dispatch = useDispatch();

  const { post, answer, counter, user } = useSelector( ( state: stateProps ) => state );
  const [ answered, setAnswered ]       = useState<boolean>( false );
  const [ buttonText, setButtonText ]   = useState<string>( 'Submit Answer' );

  const onSubmit = async () => {
    if ( answered ) {
      /**
       * Dispatch relevant global state values
       */
      dispatch( actions.setCounterPosition( counter + 1 ) );
      dispatch( actions.setMarkerOption( 0 ) );

      /**
       * Set component state values
       */
      setAnswered( false );
      setButtonText( 'Submit Answer' );

      /**
       * Reset radio options
       */
      document.querySelectorAll( 'input[type="radio"]' ).forEach( ( radio ) => {
        radio.checked = false;
      } );

      return;
    }

    /**
     * Perform POST request.
     */
    try {
      const response = await fetch(
        url,
        {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json',
          },
          body: JSON.stringify(
            {
              user: {
                id: user.ID,
                login: user.login
              },
              userQuiz: post.ID,
              userQuestion: post.questions[counter].ID,
              userAnswer: answer.option,
              userScore: answer.score,
            }
          )
        }
      );

      const { data } = await response.json();
      console.log( data );
      /**
       * Dispatch relevant global state values
       */
      dispatch( actions.setMarkerOption( parseInt( data.answer ) ) );
      dispatch( actions.setScoreID( data.scoreID ) );

      /**
       * Set component state values
       */
      setAnswered( true );
      setButtonText( 'Continue' );
    } catch ( error ) {
      console.log( error );
    }
  }

  return (
    <button
      type="button"
      style={
        {
          backgroundColor: 'Continue' === buttonText ? 'red' : 'black'
        }
      }
      onClick={ onSubmit }
    >
      { buttonText }
    </button>
  )
}

export default Submit;
