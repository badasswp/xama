import { useSelector } from 'react-redux';
import { stateProps } from '../store/reducers';

/**
 * Content Component
 *
 * This component represents the content of the post. It
 * may hold text or/and images.
 *
 * @returns {JSX.Element}  The rendered Content component.
 */
const Content = () => {
  const post = useSelector( ( state: stateProps ) => state.post );

  return (
    <div id="content" dangerouslySetInnerHTML={{ __html: post.content }} />
  )
}

export default Content;
