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
