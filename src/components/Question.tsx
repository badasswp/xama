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
  const { post, counter } = useSelector( ( state: stateProps ) => state );

  if ( ! post.questions || post.questions.length === 0 || typeof counter === 'undefined' ) {
    return <h2>Loading...</h2>;
  }

  return (
    <h2>{ post.questions[counter].title }</h2>
  )
}

export default Question;
