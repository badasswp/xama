import Question from './Question';
import Options from './Options';
import Submit from './Submit';

/**
 * Dashboard Component
 *
 * This component is responsible for rendering the question
 * and the options for the displayed question.
 *
 * @returns {JSX.Element}  The rendered Dashboard component.
 */
const Dashboard = () => {
  return (
    <main>
      <Question/>
      <Options/>
      <Submit/>
    </main>
  )
}

export default Dashboard;
