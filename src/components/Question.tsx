import { useSelector } from 'react-redux';
import { stateProps } from '../store/reducers';

/**
 * Question Component
 *
 * This component is responsible for the current question the user
 * is being asked on the frontend.
 *
 * @returns {JSX.Element}  The rendered Question component.
 */
const Question = () => {
  const { questions } = useSelector( ( state: stateProps ) => state.post );

  if ( ! questions || questions.length === 0 ) {
    return <h2>Loading...</h2>;
  }

  return (
    <h2>{ questions[0].title }</h2>
  )
}

export default Question;
