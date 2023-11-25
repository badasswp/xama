import { useState } from 'react';
import { useSelector, useDispatch } from 'react-redux';
import { stateProps } from '../store/reducers';

import actions from '../store/actions';

/**
 * Submit Component
 *
 * This component is responsible for rendering the submit button
 * that saves and submits answers for a question.
 *
 * @returns {JSX.Element}  The rendered Submit component.
 */
const Submit = () => {
  return (
    <button type="submit">Submit Answer</button>
  )
}

export default Submit;
