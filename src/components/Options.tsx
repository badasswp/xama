import { useSelector, useDispatch } from 'react-redux';
import { stateProps } from '../store/reducers';

import actions from '../store/actions';

/**
 * Options Component
 *
 * This component is responsible for the current question the user
 * is being asked on the frontend.
 *
 * @returns {JSX.Element}  The rendered Options component.
 */
const Options = () => {
  const dispatch = useDispatch();
  const options: string[] = [ 'A', 'B', 'C', 'D' ];
  const { post, answer, counter } = useSelector( ( state: stateProps ) => state );

  const onSelect = ( e ) => {
    dispatch( actions.setSelectedOption( e.target.value ) );
  }

  if ( ! post.questions || post.questions.length === 0 || typeof counter === 'undefined' ) {
    return '';
  }

  return (
    <ol>
      {
        post.questions[counter].options.map( ( item, key ) => {
          return (
            <li key={ key }>
              <input type="radio" name="xama_options" value={ key + 1 } onClick={ onSelect } />
              <p>
                <span>{ options[key] }.</span>
                { item }
              </p>
              { answer && answer.correct === key + 1 ? <i></i> : '' }
            </li>
          );
        } )
      }
    </ol>
  )
}

export default Options;
