import { useSelector } from 'react-redux';
import { stateProps } from '../store/reducers';

/**
 * Loader Component
 *
 * This component represents the placeholder content in between
 * API calls.
 *
 * @returns {JSX.Element}  The rendered Loader component.
 */
const Loader = () => {
  const loading = useSelector( ( state: stateProps ) => state.loading );

  return (
    <>
      {
        loading && (
          <div id="loader">
            <h1>Loading...</h1>
          </div>
        )
      }
    </>
  );
}

export default Loader;
