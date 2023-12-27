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
                <h1>
                  <span>Congratulations!</span>
                </h1>
                <p>
                  <span dangerouslySetInnerHTML={{ __html: `You have successfully completed the test for ${ post.title }` }} />
                </p>
                <p>
                  <a href={ user.url }>Return to Website</a>
                </p>
              </div>
            </div>
          </div>
        )
      }
    </>
  );
}

export default Result;
