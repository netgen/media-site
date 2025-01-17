import { CopilotKit } from '@copilotkit/react-core';
import { CopilotPopup } from '@copilotkit/react-ui';
import '@copilotkit/react-ui/styles.css';

function App() {
    return (
        <div>
            <CopilotKit runtimeUrl={`/ai`}>
                {
                    <CopilotPopup
                        instructions={
                            'You are assisting the user as best as you can. Answer in the best way possible given the data you have.'
                        }
                        labels={{
                            title: 'Popup Assistant',
                            initial: 'Need any help?',
                        }}
                    />
                }
            </CopilotKit>
        </div>
    );
}

export default App;
