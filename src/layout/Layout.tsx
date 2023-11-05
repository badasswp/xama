import Header from './Header';
import Footer from './Footer';

/**
 * Layout Component
 *
 * This component defines the basic layout structure for the frontend,
 * it acts as a container for the main content, which is passed as the
 * 'children' prop.
 *
 * @param {JSX.Element} props.children - The content to be rendered within the layout.
 * @returns {JSX.Element}  The rendered Layout component.
 */
const Layout = ( { children } ) => {
  return (
    <>
      <Header />
      { children }
      <Footer />
    </>
  )
}

export default Layout;
