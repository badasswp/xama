/**
 * React Dependencies
 */
import { useEffect } from 'react';
import { useDispatch } from 'react-redux';

/**
 * Custom Components
 */
import Layout from './layout/Layout';
import Sidebar from './components/Sidebar';
import Dashboard from './components/Dashboard';
import Result from './components/Result';
import Loader from './components/Loader';

import actions from './store/actions';

/**
 * App Component
 *
 * This component serves as the entry point of our application
 * and top-level component. It defines the overall structure by
 * incorporating a Layout, which comprises Sidebar & Dashboard.
 *
 * @returns {JSX.Element}  The rendered App component.
 */
const App = () => {
  const dispatch = useDispatch();
  const xama = document.getElementById( 'xama' );

  const id   = xama.getAttribute( 'data-id' );
  const url  = xama.getAttribute( 'data-url' );
  const path = `${url}/wp-json/xama/v1`;

  useEffect( () => {
    ( async () => {
      /**
       * Dispatches the fetch post request action
       * on entry and sets loading to true.
       *
       * @name fetchData
       * @returns {void}
       */
      dispatch( actions.fetchPostRequest() );
      dispatch( actions.setRestPath( path ) );
      dispatch( actions.setUser(
        {
          ID:    xama.getAttribute( 'data-user' ),
          login: xama.getAttribute( 'data-login' ),
          url
        }
      ) );

      try {
        const response = await fetch( `${path}/quiz/${id}` );
        const { data } = await response.json();

        /**
         * On successful data fetch, dispatches the
         * fetch post success action with the received data to
         * update the state and set loading to false.
         *
         * @name fetchPostSuccess
         * @returns {void}
         */
        dispatch( actions.fetchPostSuccess( data ) );
      } catch ( error ) {
        /**
         * On error during data fetch, dispatch the
         * fetch post failure action with the encountered error
         * to update the state and set loading to false.
         *
         * @name fetchPostFailure
         * @returns {void}
         */
        dispatch( actions.fetchPostFailure( error ) );
      }
    } )();
  }, [] );

  return (
    <Layout>
      <Sidebar />
      <Dashboard />
      <Result />
      <Loader />
    </Layout>
  );
}

export default App;
