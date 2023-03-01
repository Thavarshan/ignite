/* eslint-disable react-hooks/rules-of-hooks */
import React, { FunctionComponent } from 'react';
import { useToast } from '@chakra-ui/react';

export type ToastProp = {
    title: string;
    description: string;
    status: 'success' | 'error' | 'warning' | 'info';
    duration: number;
    isClosable: boolean;
    variant: 'solid' | 'subtle' | 'left-accent' | 'top-accent';
    position: 'top' | 'top-right' | 'top-left' | 'bottom' | 'bottom-right' | 'bottom-left';
};

const toast: FunctionComponent<ToastProp> = ({
    title,
    description,
    status = 'success',
    duration = 9000,
    isClosable = true,
    variant = 'subtle',
    position = 'bottom-right'
}) => {
    const toast = useToast();

    return (
        <>
            {() => toast({
                title: title,
                description: description,
                status: status,
                duration: duration,
                isClosable: isClosable,
                variant: variant,
                position: position
            })}
        </>
    );
};


export default toast;
