import { useCopilotMessagesContext } from '@copilotkit/react-core';
import { CopilotPopup } from '@copilotkit/react-ui';
import {
    ActionExecutionMessage,
    MessageRole,
    ResultMessage,
    TextMessage,
} from '@copilotkit/runtime-client-gql';
import { useEffect } from 'react';

interface ChatProps {
    userName: string;
}

export const Chat = ({ userName }: ChatProps) => {
    const { messages, setMessages } = useCopilotMessagesContext();

    // save to local storage when messages change
    useEffect(() => {
        if (messages.length !== 0) {
            localStorage.setItem('ai-chat-messages', JSON.stringify(messages));
        }
    }, [JSON.stringify(messages)]);

    // initially load from local storage
    useEffect(() => {
        const messages = localStorage.getItem('ai-chat-messages');
        if (messages) {
            const parsedMessages = JSON.parse(messages).map((message: any) => {
                if (message.type === 'TextMessage') {
                    return new TextMessage({
                        id: message.id,
                        role: message.role,
                        content: message.content,
                        createdAt: message.createdAt,
                    });
                } else if (message.type === 'ActionExecutionMessage') {
                    return new ActionExecutionMessage({
                        id: message.id,
                        name: message.name,
                        scope: message.scope,
                        arguments: message.arguments,
                        createdAt: message.createdAt,
                    });
                } else if (message.type === 'ResultMessage') {
                    return new ResultMessage({
                        id: message.id,
                        actionExecutionId: message.actionExecutionId,
                        actionName: message.actionName,
                        result: message.result,
                        createdAt: message.createdAt,
                    });
                } else {
                    throw new Error(`Unknown message type: ${message.type}`);
                }
            });

            const lastWelcomeMessage = [...parsedMessages]
                .reverse()
                .find(
                    (msg) => msg.id === 'authenticated-welcome' || msg.id === 'anonymous-welcome'
                );

            if (
                lastWelcomeMessage &&
                lastWelcomeMessage.id === 'anonymous-welcome' &&
                userName !== ''
            ) {
                const newWelcomeMessage = new TextMessage({
                    id: 'authenticated-welcome',
                    role: MessageRole.Assistant,
                    content: `Welcome ${userName}! How can I help you?`,
                    createdAt: '2025-01-20T14:47:19.401Z',
                });
                setMessages([newWelcomeMessage]);
            } else if (
                lastWelcomeMessage &&
                lastWelcomeMessage.id === 'authenticated-welcome' &&
                userName === ''
            ) {
                const newWelcomeMessage = new TextMessage({
                    id: 'anonymous-welcome',
                    role: MessageRole.Assistant,
                    content: `Hi, how can I help you?`,
                    createdAt: '2025-01-20T14:47:19.401Z',
                });
                setMessages([newWelcomeMessage]);
            } else {
                setMessages(parsedMessages);
            }
        } else {
            setMessages([
                new TextMessage({
                    id: `${userName === '' ? 'anonymous-welcome' : 'authenticated-welcome'}`,
                    role: MessageRole.Assistant,
                    content: `Hi${userName ? ` ${userName}` : ''}, how can I help you?`,
                    createdAt: '2025-01-20T14:47:19.401Z',
                }),
            ]);
        }
    }, []);

    return (
        <div>
            {
                <CopilotPopup
                    instructions={
                        'You are assisting the user as best as you can. Answer in the best way possible given the data you have.'
                    }
                    labels={{
                        title: 'Ibexa RAG Assistant',
                        // initial: [`Hi ${userName}, need any help?`],
                    }}
                />
            }
        </div>
    );
};
