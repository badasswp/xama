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

      try {
        const id  = xama.getAttribute( 'data-id' );
        const url = xama.getAttribute( 'data-url' );

        const response = await fetch( `${url}/wp-json/xama/v1/quiz/${id}` );
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
      <Sidebar/>
      <Dashboard/>
    </Layout>
  );
}

export default App;
