import { CopilotKit, CopilotMessagesContext } from '@copilotkit/react-core';

import '@copilotkit/react-ui/styles.css';

import { useState } from 'react';
import { Chat } from './Chat';

function App(props: { userName?: string }) {
    const [messages, setMessages] = useState([]);

    return (
        <CopilotKit runtimeUrl={`/ai`}>
            <CopilotMessagesContext.Provider value={{ messages, setMessages }}>
                <Chat userName={props.userName ?? ''} />
            </CopilotMessagesContext.Provider>
        </CopilotKit>
    );
}

export default App;
