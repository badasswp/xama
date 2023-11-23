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
  const { questions } = useSelector( ( state: stateProps ) => state.post );

  const onSelect = ( e ) => {
    dispatch( actions.setSelectedOption( e.target.value ) );
  }

  if ( ! questions || questions.length === 0 ) {
    return '';
  }

  return (
    <ol>
      {
        questions[0].options.map( ( item, key ) => {
          return (
            <li key={ key }>
              <input type="radio" name="xama_options" value={ key + 1 } onClick={ onSelect } />
              <p>
                <span>{ options[key] }.</span>
                { item }
              </p>
            </li>
          );
        } )
      }
    </ol>
  )
}

export default Options;
