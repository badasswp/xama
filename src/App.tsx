import Layout from './layout/Layout';
import Sidebar from './components/Sidebar';
import Dashboard from './components/Dashboard';

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
  return (
    <Layout>
      <Sidebar/>
      <Dashboard/>
    </Layout>
  );
}

export default App;
