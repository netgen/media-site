import { CopilotKit, CopilotMessagesContext } from '@copilotkit/react-core';

import '@copilotkit/react-ui/styles.css';

import { useState } from 'react';
import { Chat } from './Chat';

function App() {
    const [messages, setMessages] = useState([]);

    return (
        <CopilotKit runtimeUrl={`/ai`}>
            <CopilotMessagesContext.Provider value={{ messages, setMessages }}>
                <Chat />
            </CopilotMessagesContext.Provider>
        </CopilotKit>
    );
}

export default App;
