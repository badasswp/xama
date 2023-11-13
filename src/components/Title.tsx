import { useSelector } from 'react-redux';
import { stateProps } from '../store/reducers';

/**
 * Title Component
 *
 * This component is responsible for the big, bold caption displayed
 * on the frontend.
 *
 * @returns {JSX.Element}  The rendered Title component.
 */
const Title = () => {
  const post = useSelector( ( state: stateProps ) => state.post );

  return (
    <h1>{ post.title }</h1>
  )
}

export default Title;
