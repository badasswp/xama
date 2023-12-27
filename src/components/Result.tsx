import { useSelector } from 'react-redux';
import { stateProps } from '../store/reducers';

/**
 * Result Component
 *
 * This component displays the result of the
 * Test | Quiz.
 *
 * @returns {JSX.Element}  The rendered Result component.
 */
const Result = () => {
  const {
    post, counter, user, percentage
  } = useSelector( ( state: stateProps ) => state );

  return (
    <>
      {
        counter === post.questions.length && (
          <div id="result">
            <div>
              <div>
                <p>
                  <strong>{ user.login } | Score: { percentage }%</strong>
                </p>
                <h1>Congratulations!</h1>
                <p>You have successfully completed the test for 10up Senior WordPress Engineer.</p>
              </div>
            </div>
          </div>
        )
      }
    </>
  );
}

export default Result;
