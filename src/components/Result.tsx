import { useSelector } from 'react-redux';
import { stateProps } from '../store/reducers';
import { IconUser, IconScore } from './Icons';

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
                <h1>{ percentage ? percentage : '0' }%</h1>
              </div>
              <div>
                <header>
                  <div>
                    <span>
                      <IconUser />
                    </span>
                    <strong>{ user.login }</strong>
                  </div>
                  <div>
                    <span>
                      <IconScore />
                    </span>
                    <strong>Score: { percentage ? percentage : '0' }%</strong>
                  </div>
                </header>
                <h1>
                  <span>Congratulations!</span>
                </h1>
                <p>
                  <span dangerouslySetInnerHTML={{ __html: `You have successfully completed the test for ${ post.title }.` }} />
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
