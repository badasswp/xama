import { useSelector } from 'react-redux';
import { stateProps } from '../store/reducers';

/**
 * Options Component
 *
 * This component is responsible for the current question the user
 * is being asked on the frontend.
 *
 * @returns {JSX.Element}  The rendered Options component.
 */
const Options = () => {
  const { questions } = useSelector( ( state: stateProps ) => state.post );
  const options = [ 'A', 'B', 'C', 'D' ];

  if ( ! questions || questions.length === 0 ) {
    return '';
  }

  return (
    <ol>
      {
        questions[0].options.map( ( item, key ) => {
          return (
            <li key={ key }>
              <input type="radio" name="user_answer_1" value="1" />
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
