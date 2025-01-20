import {
    CopilotKit,
    CopilotMessagesContext,
    useCopilotChat,
    useCopilotMessagesContext,
} from '@copilotkit/react-core';
import { CopilotPopup } from '@copilotkit/react-ui';
import { ActionExecutionMessage, ResultMessage, TextMessage } from '@copilotkit/runtime-client-gql';
import React, { useEffect, useState } from 'react';

export const Chat = () => {
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
            setMessages(parsedMessages);
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
                        initial: ['Hi, need any help?'],
                    }}
                />
            }
        </div>
    );
};
