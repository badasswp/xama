import Title from './Title';
import Content from './Content';

/**
 * Sidebar Component
 *
 * This component is responsible for rendering a fixed sidebar
 * section. It contains a title and content.
 *
 * @returns {JSX.Element}  The rendered Sidebar component.
 */
const Sidebar = () => {
  return (
    <section>
      <Title/>
      <Content/>
    </section>
  )
}

export default Sidebar;
